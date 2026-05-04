<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\OrderModel;
use App\Services\MotorServiceClient;

class OrderController extends ResourceController
{
    protected $modelName = OrderModel::class;
    protected $format    = 'json';

    private const MAX_EDIT_HOURS = 4;

    /**
     * Check if an order is still within the editable time window (4 hours)
     */
    private function isEditable(array $order): bool
    {
        $createdAt = strtotime($order['created_at']);
        $hoursElapsed = (time() - $createdAt) / 3600;
        return $hoursElapsed <= self::MAX_EDIT_HOURS && !in_array($order['status'], ['dibatalkan', 'selesai']);
    }

    /**
     * GET /api/orders — List all orders
     */
    public function index()
    {
        $orders = $this->model->orderBy('created_at', 'DESC')->findAll();
        // Add editable flag to each order
        foreach ($orders as &$order) {
            $order['can_edit'] = $this->isEditable($order);
        }
        return $this->respond(['data' => $orders]);
    }

    /**
     * GET /api/orders/{id} — Show a single order
     */
    public function show($id = null)
    {
        $order = $this->model->find($id);
        if (!$order) {
            return $this->failNotFound('Pesanan tidak ditemukan');
        }
        $order['can_edit'] = $this->isEditable($order);
        return $this->respond(['data' => $order]);
    }

    /**
     * POST /api/orders — Create a new order
     */
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
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $motorId = $this->request->getVar('motor_id');
        $jumlah = $this->request->getVar('jumlah');

        $motorClient = new MotorServiceClient();
        
        $stockCheck = $motorClient->checkStock($motorId, $jumlah);
        
        if (!$stockCheck['success']) {
            return $this->respond([
                'message' => $stockCheck['message'] ?? 'Verifikasi stok gagal'
            ], 400);
        }

        $motor = $stockCheck['motor'];
        $hargaSatuan = $motor['harga'];
        $total = $hargaSatuan * $jumlah;

        $kodeOrder = 'ORD-' . strtoupper(substr(md5(uniqid()), 0, 8));

        $orderData = [
            'kode_order'       => $kodeOrder,
            'nama_pelanggan'   => $this->request->getVar('nama_pelanggan'),
            'no_telepon'       => $this->request->getVar('no_telepon'),
            'alamat'           => $this->request->getVar('alamat'),
            'motor_id'         => $motorId,
            'motor_nama'       => $motor['nama'] . ' - ' . $motor['merk'],
            'harga_satuan'     => $hargaSatuan,
            'jumlah'           => $jumlah,
            'total'            => $total,
            'status'           => 'diproses',
            'catatan'          => $this->request->getVar('catatan'),
            'payment_status'   => 'belum_bayar',
            'payment_deadline' => date('Y-m-d H:i:s', strtotime('+12 hours')),
        ];

        if ($this->model->insert($orderData)) {
            $decreased = $motorClient->decreaseStock($motorId, $jumlah);
            
            if (!$decreased) {
                $this->model->update($this->model->getInsertID(), [
                    'status'  => 'dibatalkan',
                    'catatan' => 'Gagal sinkronisasi stok dengan MotorService'
                ]);
                return $this->respond([
                    'message' => 'Gagal mengurangi stok di layanan MotorService'
                ], 500);
            }

            return $this->respondCreated([
                'message' => 'Pesanan berhasil dibuat',
                'data'    => $orderData
            ]);
        }

        return $this->failServerError('Gagal menyimpan pesanan');
    }

    /**
     * PUT /api/orders/{id} — Update an order (within 4 hours)
     */
    public function update($id = null)
    {
        $order = $this->model->find($id);
        if (!$order) {
            return $this->failNotFound('Pesanan tidak ditemukan');
        }

        if (!$this->isEditable($order)) {
            return $this->respond([
                'message' => 'Pesanan tidak dapat diedit. Batas waktu edit (4 jam) telah terlampaui atau status pesanan sudah final.'
            ], 403);
        }

        $rules = [
            'nama_pelanggan' => 'required',
            'no_telepon'     => 'required',
            'alamat'         => 'required',
            'jumlah'         => 'required|numeric|greater_than[0]'
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $newJumlah = (int)$this->request->getVar('jumlah');
        $oldJumlah = (int)$order['jumlah'];
        $motorId = $order['motor_id'];

        $motorClient = new MotorServiceClient();

        // If quantity changed, need to adjust stock
        if ($newJumlah !== $oldJumlah) {
            $diff = $newJumlah - $oldJumlah;
            if ($diff > 0) {
                // Need more stock
                $stockCheck = $motorClient->checkStock($motorId, $diff);
                if (!$stockCheck['success']) {
                    return $this->respond([
                        'message' => 'Stok tidak mencukupi untuk perubahan jumlah. ' . ($stockCheck['message'] ?? '')
                    ], 400);
                }
                $motorClient->decreaseStock($motorId, $diff);
            } else {
                // Return stock
                $motorClient->increaseStock($motorId, abs($diff));
            }
        }

        $updateData = [
            'nama_pelanggan' => $this->request->getVar('nama_pelanggan'),
            'no_telepon'     => $this->request->getVar('no_telepon'),
            'alamat'         => $this->request->getVar('alamat'),
            'jumlah'         => $newJumlah,
            'total'          => (float)$order['harga_satuan'] * $newJumlah,
            'catatan'        => $this->request->getVar('catatan'),
        ];

        if ($this->model->update($id, $updateData)) {
            $updated = $this->model->find($id);
            return $this->respond([
                'message' => 'Pesanan berhasil diperbarui',
                'data'    => $updated
            ]);
        }

        return $this->failServerError('Gagal memperbarui pesanan');
    }

    /**
     * DELETE /api/orders/{id} — Cancel an order (within 4 hours)
     */
    public function cancel($id = null)
    {
        $order = $this->model->find($id);
        if (!$order) {
            return $this->failNotFound('Pesanan tidak ditemukan');
        }

        if (!$this->isEditable($order)) {
            return $this->respond([
                'message' => 'Pesanan tidak dapat dibatalkan. Batas waktu pembatalan (4 jam) telah terlampaui atau status pesanan sudah final.'
            ], 403);
        }

        // Restore stock to MotorService
        $motorClient = new MotorServiceClient();
        $motorClient->increaseStock($order['motor_id'], (int)$order['jumlah']);

        // Mark as cancelled
        $this->model->update($id, [
            'status'  => 'dibatalkan',
            'catatan' => ($order['catatan'] ? $order['catatan'] . ' | ' : '') . 'Dibatalkan oleh pelanggan pada ' . date('d/m/Y H:i')
        ]);

        return $this->respond([
            'message' => 'Pesanan berhasil dibatalkan. Stok motor telah dikembalikan.',
            'data'    => $this->model->find($id)
        ]);
    }

    /**
     * POST /api/orders/{id}/pay — Process payment
     */
    public function pay($id = null)
    {
        $order = $this->model->find($id);
        if (!$order) {
            return $this->failNotFound('Pesanan tidak ditemukan');
        }

        if ($order['payment_status'] === 'berhasil') {
            return $this->respond(['message' => 'Pesanan sudah dibayar'], 400);
        }

        if ($order['payment_deadline'] && strtotime($order['payment_deadline']) < time()) {
            return $this->respond(['message' => 'Batas waktu pembayaran telah berakhir'], 403);
        }

        $paymentMethod = $this->request->getVar('payment_method');
        if (!$paymentMethod) {
            return $this->respond(['message' => 'Metode pembayaran wajib diisi'], 400);
        }

        $this->model->update($id, [
            'payment_method' => $paymentMethod,
            'payment_status' => 'berhasil',
            'paid_at'        => date('Y-m-d H:i:s'),
            'status'         => 'selesai',
        ]);

        return $this->respond([
            'message' => 'Pembayaran berhasil',
            'data'    => $this->model->find($id)
        ]);
    }

    /**
     * GET /api/orders/statistics
     */
    public function statistics()
    {
        $builder = $this->model->builder();
        $builder->select('COUNT(id) as total_transaksi, SUM(total) as total_pendapatan');
        $builder->where('status', 'selesai');
        $query = $builder->get();
        $stats = $query->getRowArray();

        return $this->respond([
            'data' => [
                'total_transaksi'  => (int)($stats['total_transaksi'] ?? 0),
                'total_pendapatan' => (float)($stats['total_pendapatan'] ?? 0)
            ]
        ]);
    }
}
