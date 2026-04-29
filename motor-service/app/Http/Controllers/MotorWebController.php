<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use App\Services\OrderServiceClient;
use Illuminate\Http\Request;

class MotorWebController extends Controller
{
    /**
     * Halaman katalog motor
     */
    public function index(Request $request)
    {
        $query = Motor::query();

        if ($request->has('merk') && $request->merk) {
            $query->where('merk', $request->merk);
        }

        if ($request->has('tipe') && $request->tipe) {
            $query->where('tipe', $request->tipe);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('merk', 'like', "%{$search}%");
            });
        }

        $motors = $query->orderBy('is_terlaris', 'desc')->orderBy('nama')->get();
        $merks = Motor::distinct()->pluck('merk');
        $tipes = Motor::distinct()->pluck('tipe');

        return view('motors.index', compact('motors', 'merks', 'tipes'));
    }

    /**
     * Detail motor
     */
    public function show(int $id)
    {
        $motor = Motor::findOrFail($id);
        return view('motors.show', compact('motor'));
    }

    /**
     * Trigger sync motor terlaris dari OrderService
     */
    public function syncTerlaris()
    {
        $client = new OrderServiceClient();
        $statistik = $client->getStatistikPenjualan();

        if (!$statistik || !isset($statistik['success']) || !$statistik['success']) {
            return redirect()->route('motors.index')
                ->with('error', 'Gagal mengambil data statistik dari OrderService. Pastikan OrderService berjalan di port 8002.');
        }

        // Reset semua label terlaris
        Motor::where('is_terlaris', true)->update(['is_terlaris' => false]);

        // Set motor terlaris
        $terlaris = $statistik['data']['terlaris'] ?? [];
        $count = 0;

        foreach ($terlaris as $item) {
            $motor = Motor::find($item['motor_id']);
            if ($motor) {
                $motor->is_terlaris = true;
                $motor->save();
                $count++;
            }
        }

        return redirect()->route('motors.index')
            ->with('success', "Label motor terlaris berhasil diperbarui! ({$count} motor ditandai sebagai terlaris)");
    }
}
