<?php

namespace App\Http\Controllers;

use App\Services\GatewayService;
use Illuminate\Http\Request;

class GatewayWebController extends Controller
{
    private GatewayService $gateway;

    public function __construct()
    {
        $this->gateway = new GatewayService();
    }

    /**
     * Dashboard utama — menampilkan status semua service, katalog, dan transaksi
     */
    public function index()
    {
        $health = $this->gateway->healthCheck();

        // Ambil data motor dari MotorService
        $motorsResult = $this->gateway->proxyToMotorService('GET', '/api/motors');
        $motors = $motorsResult['success'] ? ($motorsResult['data']['data'] ?? []) : [];

        // Ambil data transaksi dari OrderService
        $ordersResult = $this->gateway->proxyToOrderService('GET', '/api/orders');
        $orders = $ordersResult['success'] ? ($ordersResult['data']['data'] ?? []) : [];

        // Ambil statistik dari OrderService
        $statsResult = $this->gateway->proxyToOrderService('GET', '/api/orders/statistics');
        $statistics = $statsResult['success'] ? ($statsResult['data']['data'] ?? []) : [];

        return view('gateway.index', compact('health', 'motors', 'orders', 'statistics'));
    }

    /**
     * Halaman katalog motor (via gateway)
     */
    public function motors(Request $request)
    {
        $motorsResult = $this->gateway->proxyToMotorService('GET', '/api/motors', $request->all());
        $motors = $motorsResult['success'] ? ($motorsResult['data']['data'] ?? []) : [];
        $error = $motorsResult['success'] ? null : 'MotorService tidak tersedia';

        return view('gateway.motors', compact('motors', 'error'));
    }

    /**
     * Detail motor (via gateway)
     */
    public function motorDetail(int $id)
    {
        $result = $this->gateway->proxyToMotorService('GET', "/api/motors/{$id}");
        if (!$result['success']) {
            abort(404, 'Motor tidak ditemukan');
        }
        $motor = $result['data']['data'];
        return view('gateway.motor-detail', compact('motor'));
    }

    /**
     * Form pemesanan (via gateway)
     */
    public function orderCreate(Request $request)
    {
        $motorsResult = $this->gateway->proxyToMotorService('GET', '/api/motors');
        $motors = $motorsResult['success'] ? ($motorsResult['data']['data'] ?? []) : [];
        $error = $motorsResult['success'] ? null : 'MotorService tidak tersedia';
        $selectedMotorId = $request->query('motor_id');

        return view('gateway.order-create', compact('motors', 'error', 'selectedMotorId'));
    }

    /**
     * Simpan pesanan (via gateway)
     */
    public function orderStore(Request $request)
    {
        $result = $this->gateway->proxyToOrderService('POST', '/api/orders', $request->all());

        if ($result['success']) {
            $orderId = $result['data']['data']['id'] ?? null;
            return redirect()->route('gateway.orders')
                ->with('success', 'Pesanan berhasil dibuat melalui API Gateway! Kode: ' . ($result['data']['data']['kode_order'] ?? ''));
        }

        return redirect()->back()->withInput()
            ->with('error', $result['data']['message'] ?? 'Gagal membuat pesanan');
    }

    /**
     * Daftar transaksi (via gateway)
     */
    public function orders()
    {
        $ordersResult = $this->gateway->proxyToOrderService('GET', '/api/orders');
        $orders = $ordersResult['success'] ? ($ordersResult['data']['data'] ?? []) : [];
        $error = $ordersResult['success'] ? null : 'OrderService tidak tersedia';

        return view('gateway.orders', compact('orders', 'error'));
    }

    /**
     * Detail pesanan (via gateway)
     */
    public function orderDetail(int $id)
    {
        $result = $this->gateway->proxyToOrderService('GET', "/api/orders/{$id}");
        if (!$result['success']) {
            abort(404, 'Pesanan tidak ditemukan');
        }
        $order = $result['data']['data'];
        return view('gateway.order-detail', compact('order'));
    }

    /**
     * Form edit pesanan (via gateway)
     */
    public function orderEdit(int $id)
    {
        $result = $this->gateway->proxyToOrderService('GET', "/api/orders/{$id}");
        if (!$result['success']) {
            abort(404, 'Pesanan tidak ditemukan');
        }
        $order = $result['data']['data'];

        if (empty($order['can_edit'])) {
            return redirect()->route('gateway.orders')
                ->with('error', 'Pesanan tidak dapat diedit. Batas waktu 4 jam telah terlampaui atau status sudah final.');
        }

        return view('gateway.order-edit', compact('order'));
    }

    /**
     * Update pesanan (via gateway)
     */
    public function orderUpdate(Request $request, int $id)
    {
        $result = $this->gateway->proxyToOrderService('PUT', "/api/orders/{$id}", $request->all());

        if ($result['success']) {
            return redirect()->route('gateway.orders')
                ->with('success', 'Pesanan berhasil diperbarui melalui API Gateway!');
        }

        return redirect()->back()->withInput()
            ->with('error', $result['data']['message'] ?? 'Gagal memperbarui pesanan');
    }

    /**
     * Batalkan pesanan (via gateway)
     */
    public function orderCancel(int $id)
    {
        $result = $this->gateway->proxyToOrderService('DELETE', "/api/orders/{$id}");

        if ($result['success']) {
            return redirect()->route('gateway.orders')
                ->with('success', $result['data']['message'] ?? 'Pesanan berhasil dibatalkan melalui API Gateway!');
        }

        return redirect()->route('gateway.orders')
            ->with('error', $result['data']['message'] ?? 'Gagal membatalkan pesanan');
    }

    /**
     * Form tambah motor baru
     */
    public function motorCreate()
    {
        return view('gateway.motor-create');
    }

    /**
     * Simpan motor baru (via gateway → MotorService)
     */
    public function motorStore(Request $request)
    {
        $result = $this->gateway->proxyToMotorService('POST', '/api/motors', $request->all());

        if ($result['success']) {
            return redirect()->route('gateway.motors')
                ->with('success', 'Motor berhasil ditambahkan!');
        }

        return redirect()->back()->withInput()
            ->with('error', $result['data']['message'] ?? 'Gagal menambahkan motor');
    }

    /**
     * Form edit motor
     */
    public function motorEdit(int $id)
    {
        $result = $this->gateway->proxyToMotorService('GET', "/api/motors/{$id}");
        if (!$result['success']) {
            abort(404, 'Motor tidak ditemukan');
        }
        $motor = $result['data']['data'];
        return view('gateway.motor-edit', compact('motor'));
    }

    /**
     * Update motor (via gateway → MotorService)
     */
    public function motorUpdate(Request $request, int $id)
    {
        $result = $this->gateway->proxyToMotorService('PUT', "/api/motors/{$id}", $request->all());

        if ($result['success']) {
            return redirect()->route('gateway.motors')
                ->with('success', 'Motor berhasil diperbarui!');
        }

        return redirect()->back()->withInput()
            ->with('error', $result['data']['message'] ?? 'Gagal memperbarui motor');
    }

    /**
     * Hapus motor (via gateway → MotorService)
     */
    public function motorDestroy(int $id)
    {
        $result = $this->gateway->proxyToMotorService('DELETE', "/api/motors/{$id}");

        if ($result['success']) {
            return redirect()->route('gateway.motors')
                ->with('success', 'Motor berhasil dihapus!');
        }

        return redirect()->route('gateway.motors')
            ->with('error', $result['data']['message'] ?? 'Gagal menghapus motor');
    }

    /**
     * Trigger sync terlaris (via gateway)
     */
    public function syncTerlaris()
    {
        $result = $this->gateway->proxyToMotorService('POST', '/api/motors/update-terlaris');

        if ($result['success']) {
            return redirect()->route('gateway.motors')
                ->with('success', 'Label motor terlaris berhasil diperbarui melalui API Gateway!');
        }

        return redirect()->route('gateway.motors')
            ->with('error', $result['data']['message'] ?? 'Gagal sync motor terlaris');
    }

    /**
     * Halaman External API — Data motor dari API Ninjas
     */
    public function externalApi(Request $request)
    {
        $make = $request->input('make', 'Honda');
        $year = $request->input('year');

        $params = $request->all();
        if (!isset($params['make'])) {
            $params['make'] = 'Honda';
        }

        $result = $this->gateway->proxyToMotorService('GET', '/api/external/motorcycles', $params);
        $motorcycles = ($result['success'] && isset($result['data']['data'])) ? $result['data']['data'] : [];
        $error = $result['success'] ? null : ($result['data']['message'] ?? 'Gagal mengambil data dari API Ninjas');
        $source = $result['data']['source'] ?? 'API Ninjas';

        return view('gateway.external-api', compact('motorcycles', 'make', 'year', 'error', 'source'));
    }
}
