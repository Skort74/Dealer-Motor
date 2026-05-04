<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Services\MotorServiceClient;

class OrderWebController extends BaseController
{
    protected $orderModel;
    protected $motorClient;

    private const MAX_EDIT_HOURS = 12;
    private const PAYMENT_DEADLINE_HOURS = 12;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->motorClient = new MotorServiceClient();
    }

    private function isEditable(array $order): bool
    {
        $createdAt = strtotime($order['created_at']);
        $hoursElapsed = (time() - $createdAt) / 3600;
        return $hoursElapsed <= self::MAX_EDIT_HOURS && !in_array($order['status'], ['dibatalkan', 'selesai']);
    }

    /**
     * Check and auto-expire unpaid orders past deadline
     */
    private function checkExpiredPayments()
    {
        $pending = $this->orderModel
            ->whereIn('payment_status', ['belum_bayar', 'menunggu'])
            ->where('payment_deadline IS NOT NULL')
            ->where('payment_deadline <', date('Y-m-d H:i:s'))
            ->findAll();

        foreach ($pending as $order) {
            $this->orderModel->update($order['id'], [
                'payment_status' => 'kadaluarsa',
                'status' => 'dibatalkan',
                'catatan' => ($order['catatan'] ? $order['catatan'] . ' | ' : '') . 'Pembayaran kadaluarsa (melewati batas 12 jam)'
            ]);
            // Restore stock
            $this->motorClient->increaseStock($order['motor_id'], (int)$order['jumlah']);
        }
    }

    public function index()
    {
        $this->checkExpiredPayments();

        $orders = $this->orderModel->orderBy('created_at', 'DESC')->findAll();
        foreach ($orders as &$order) {
            $order['can_edit'] = $this->isEditable($order);
        }

        $motorsFromApi = $this->motorClient->getMotors();

        foreach ($motorsFromApi as &$m) {
           
            if (isset($m['gambar']) && !filter_var($m['gambar'], FILTER_VALIDATE_URL)) {
                $m['gambar_url'] = "http://localhost:8001/images/motors/" . $m['gambar'];
            } else {
                $m['gambar_url'] = $m['gambar'] ?? base_url('images/default_motor.png');
            }
        }

        $data = [
            'orders'  => $orders,
            'motors'  => $motorsFromApi, 
            'error'   => session()->getFlashdata('error'),
            'success' => session()->getFlashdata('success'),
        ];
        return view('orders/index', $data);
    }

    public function create()
    {
        $motorId = $this->request->getGet('motor_id');
        $motors = $this->motorClient->getMotors();

        $selectedMotor = null;
        if ($motorId) {
            foreach ($motors as $m) {
                if ($m['id'] == $motorId) {
                    $selectedMotor = $m;
                    break;
                }
            }
        }

        $data = [
            'motors'        => $motors,
            'selectedMotor' => $selectedMotor,
            'error'         => session()->getFlashdata('error'),
        ];
        return view('orders/create', $data);
    }

    public function store()
    {
        $rules = [
            'nama_pelanggan' => 'required',
            'no_telepon'     => 'required',
            'alamat'         => 'required',
            'motor_id'       => 'required|numeric',
            'jumlah'         => 'required|numeric|greater_than[0]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Semua form wajib diisi dengan benar.');
        }

        $motorId = $this->request->getPost('motor_id');
        $jumlah = $this->request->getPost('jumlah');

        $stockCheck = $this->motorClient->checkStock($motorId, $jumlah);

        if (!$stockCheck['success']) {
            return redirect()->back()->withInput()->with('error', $stockCheck['message']);
        }

        $motor = $stockCheck['motor'];
        $hargaSatuan = $motor['harga'];
        $total = $hargaSatuan * $jumlah;

        $kodeOrder = 'ORD-' . strtoupper(substr(md5(uniqid()), 0, 8));
        $now = date('Y-m-d H:i:s');
        $deadline = date('Y-m-d H:i:s', strtotime('+' . self::PAYMENT_DEADLINE_HOURS . ' hours'));

        $orderData = [
            'kode_order'       => $kodeOrder,
            'nama_pelanggan'   => $this->request->getPost('nama_pelanggan'),
            'no_telepon'       => $this->request->getPost('no_telepon'),
            'alamat'           => $this->request->getPost('alamat'),
            'motor_id'         => $motorId,
            'motor_nama'       => $motor['nama'] . ' - ' . $motor['merk'],
            'harga_satuan'     => $hargaSatuan,
            'jumlah'           => $jumlah,
            'total'            => $total,
            'status'           => 'diproses',
            'catatan'          => $this->request->getPost('catatan'),
            'payment_status'   => 'belum_bayar',
            'payment_deadline' => $deadline,
        ];

        if ($this->orderModel->insert($orderData)) {
            $decreased = $this->motorClient->decreaseStock($motorId, $jumlah);

            if (!$decreased) {
                $this->orderModel->update($this->orderModel->getInsertID(), ['status' => 'dibatalkan', 'catatan' => 'Gagal sinkronisasi stok']);
                return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem saat menghubungi MotorService.');
            }

            $insertId = $this->orderModel->getInsertID();
            // Redirect to payment page
            return redirect()->to('/orders/payment/' . $insertId)->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal menyimpan pesanan.');
    }

    /**
     * Payment page
     */
    public function payment($id)
    {
        $order = $this->orderModel->find($id);
        if (!$order) {
            return redirect()->to('/orders')->with('error', 'Pesanan tidak ditemukan.');
        }

        // Check if already paid
        if ($order['payment_status'] === 'berhasil') {
            return redirect()->to('/orders')->with('success', 'Pesanan ' . $order['kode_order'] . ' sudah dibayar.');
        }

        // Check if expired
        if ($order['payment_deadline'] && strtotime($order['payment_deadline']) < time()) {
            if (!in_array($order['payment_status'], ['kadaluarsa', 'berhasil'])) {
                $this->orderModel->update($id, [
                    'payment_status' => 'kadaluarsa',
                    'status' => 'dibatalkan',
                ]);
                $this->motorClient->increaseStock($order['motor_id'], (int)$order['jumlah']);
            }
            return redirect()->to('/orders')->with('error', 'Batas waktu pembayaran untuk pesanan ' . $order['kode_order'] . ' telah berakhir.');
        }

        $data = [
            'order'   => $order,
            'error'   => session()->getFlashdata('error'),
            'success' => session()->getFlashdata('success'),
        ];
        return view('orders/payment', $data);
    }

    /**
     * Process payment
     */
    public function pay($id)
    {
        $order = $this->orderModel->find($id);
        if (!$order) {
            return redirect()->to('/orders')->with('error', 'Pesanan tidak ditemukan.');
        }

        if ($order['payment_status'] === 'berhasil') {
            return redirect()->to('/orders')->with('success', 'Pesanan sudah dibayar sebelumnya.');
        }

        if ($order['payment_deadline'] && strtotime($order['payment_deadline']) < time()) {
            return redirect()->to('/orders')->with('error', 'Batas waktu pembayaran telah berakhir.');
        }

        $paymentMethod = $this->request->getPost('payment_method');
        if (!$paymentMethod) {
            return redirect()->back()->with('error', 'Silakan pilih metode pembayaran.');
        }

        // Simulate payment processing (in real app, integrate with payment gateway)
        $paymentSuccess = true; // Simulate success

        if ($paymentSuccess) {
            $this->orderModel->update($id, [
                'payment_method' => $paymentMethod,
                'payment_status' => 'berhasil',
                'paid_at'        => date('Y-m-d H:i:s'),
                'status'         => 'selesai',
            ]);
            return redirect()->to('/orders')->with('success', '✅ Pembayaran berhasil! Pesanan ' . $order['kode_order'] . ' telah dikonfirmasi. Metode: ' . $paymentMethod);
        } else {
            $this->orderModel->update($id, [
                'payment_method' => $paymentMethod,
                'payment_status' => 'gagal',
            ]);
            return redirect()->to('/orders/payment/' . $id)->with('error', '❌ Pembayaran gagal! Silakan coba lagi atau pilih metode pembayaran lain.');
        }
    }

    // ========== EDIT / CANCEL ==========

    public function edit($id)
    {
        $order = $this->orderModel->find($id);
        if (!$order) {
            return redirect()->to('/orders')->with('error', 'Pesanan tidak ditemukan.');
        }
        if (!$this->isEditable($order)) {
            return redirect()->to('/orders')->with('error', 'Pesanan tidak dapat diedit.');
        }
        $data = ['order' => $order, 'error' => session()->getFlashdata('error')];
        return view('orders/edit', $data);
    }

    public function update($id)
    {
        $order = $this->orderModel->find($id);
        if (!$order || !$this->isEditable($order)) {
            return redirect()->to('/orders')->with('error', 'Pesanan tidak dapat diedit.');
        }

        $rules = [
            'nama_pelanggan' => 'required',
            'no_telepon'     => 'required',
            'alamat'         => 'required',
            'jumlah'         => 'required|numeric|greater_than[0]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Semua form wajib diisi.');
        }

        $newJumlah = (int)$this->request->getPost('jumlah');
        $oldJumlah = (int)$order['jumlah'];

        if ($newJumlah !== $oldJumlah) {
            $diff = $newJumlah - $oldJumlah;
            if ($diff > 0) {
                $stockCheck = $this->motorClient->checkStock($order['motor_id'], $diff);
                if (!$stockCheck['success']) {
                    return redirect()->back()->withInput()->with('error', 'Stok tidak mencukupi.');
                }
                $this->motorClient->decreaseStock($order['motor_id'], $diff);
            } else {
                $this->motorClient->increaseStock($order['motor_id'], abs($diff));
            }
        }

        $this->orderModel->update($id, [
            'nama_pelanggan' => $this->request->getPost('nama_pelanggan'),
            'no_telepon'     => $this->request->getPost('no_telepon'),
            'alamat'         => $this->request->getPost('alamat'),
            'jumlah'         => $newJumlah,
            'total'          => (float)$order['harga_satuan'] * $newJumlah,
            'catatan'        => $this->request->getPost('catatan'),
        ]);

        return redirect()->to('/orders')->with('success', 'Pesanan ' . $order['kode_order'] . ' berhasil diperbarui.');
    }

    public function cancel($id)
    {
        $order = $this->orderModel->find($id);
        if (!$order || !$this->isEditable($order)) {
            return redirect()->to('/orders')->with('error', 'Pesanan tidak dapat dibatalkan.');
        }

        $this->motorClient->increaseStock($order['motor_id'], (int)$order['jumlah']);

        $this->orderModel->update($id, [
            'status'         => 'dibatalkan',
            'payment_status' => 'gagal',
            'catatan'        => ($order['catatan'] ? $order['catatan'] . ' | ' : '') . 'Dibatalkan pada ' . date('d/m/Y H:i')
        ]);

        return redirect()->to('/orders')->with('success', 'Pesanan ' . $order['kode_order'] . ' dibatalkan. Stok dikembalikan.');
    }
}
