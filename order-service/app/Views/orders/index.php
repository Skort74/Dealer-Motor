<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Dashboard - Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="page-header">
        <h1>🏠 Dashboard Dashboard</h1>
        <p>Pilih motor dari katalog untuk melakukan pemesanan</p>
    </div>

    <!-- ===== NOTIFIKASI ===== -->
    <?php if(session()->getFlashdata('success')): ?>
        <div style="padding:1rem;background:rgba(34,197,94,0.15);border:1px solid rgba(34,197,94,0.3);border-radius:var(--radius);margin-bottom:1.5rem;display:flex;align-items:center;gap:10px;animation:slideDown 0.3s ease">
            <span style="font-size:1.2rem">✅</span>
            <span style="color:#6ee7b7;font-weight:600"><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('error')): ?>
        <div style="padding:1rem;background:rgba(239,68,68,0.15);border:1px solid rgba(239,68,68,0.3);border-radius:var(--radius);margin-bottom:1.5rem;display:flex;align-items:center;gap:10px;animation:slideDown 0.3s ease">
            <span style="font-size:1.2rem">❌</span>
            <span style="color:#fca5a5;font-weight:600"><?= session()->getFlashdata('error') ?></span>
        </div>
    <?php endif; ?>

    <!-- ===== PENGINGAT PEMBAYARAN ===== -->
    <?php
        $pendingPayments = array_filter($orders, fn($o) => in_array($o['payment_status'] ?? '', ['belum_bayar', 'menunggu']) && $o['status'] !== 'dibatalkan');
    ?>
    <?php if(count($pendingPayments) > 0): ?>
        <div style="padding:1rem 1.25rem;background:rgba(245,158,11,0.12);border:1px solid rgba(245,158,11,0.3);border-radius:var(--radius-lg);margin-bottom:1.5rem">
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:0.75rem">
                <span style="font-size:1.2rem">🔔</span>
                <span style="font-weight:700;color:#fcd34d;font-size:0.95rem">Pengingat Pembayaran</span>
            </div>
            <?php foreach($pendingPayments as $po): ?>
                <?php
                    $dl = strtotime($po['payment_deadline'] ?? '');
                    $sisa = $dl ? $dl - time() : 0;
                    $jam = floor($sisa / 3600);
                    $mnt = floor(($sisa % 3600) / 60);
                ?>
                <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 12px;background:var(--bg-card);border-radius:var(--radius);margin-bottom:6px;border:1px solid var(--border)">
                    <div>
                        <span style="font-weight:600;color:var(--primary-light);font-size:0.85rem"><?= esc($po['kode_order']) ?></span>
                        <span style="color:var(--text-muted);font-size:0.8rem"> — <?= esc($po['motor_nama']) ?></span>
                        <span class="price-text" style="font-size:0.85rem;margin-left:8px">Rp <?= number_format($po['total'], 0, ',', '.') ?></span>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px">
                        <?php if($sisa > 0): ?>
                            <span style="font-size:0.75rem;color:<?= $sisa < 3600 ? '#ef4444' : '#fcd34d' ?>;font-weight:600">⏰ <?= $jam ?>j <?= $mnt ?>m tersisa</span>
                        <?php else: ?>
                            <span style="font-size:0.75rem;color:#fca5a5;font-weight:600">⏰ Kadaluarsa</span>
                        <?php endif; ?>
                        <a href="<?= base_url('/orders/payment/' . $po['id']) ?>" class="btn btn-primary btn-sm" style="padding:4px 12px;font-size:0.75rem;background:linear-gradient(135deg,#22c55e,#16a34a)">💳 Bayar</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- ===== MENU NAVIGASI ===== -->
    <div style="display:flex;gap:1rem;margin-bottom:2rem;flex-wrap:wrap">
        <a href="#katalog" class="btn btn-primary" style="flex:1;justify-content:center;min-width:200px">
            🏍️ Katalog Motor
        </a>
        <a href="<?= base_url('/orders/create') ?>" class="btn btn-success" style="flex:1;justify-content:center;min-width:200px">
            ➕ Form Pemesanan
        </a>
        <a href="#riwayat" class="btn btn-secondary" style="flex:1;justify-content:center;min-width:200px">
            📋 Riwayat Transaksi
        </a>
    </div>

    <!-- ===== KATALOG MOTOR ===== -->
    <div id="katalog" style="scroll-margin-top:90px">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
            <h2 style="font-size:1.3rem;font-weight:700">🏍️ Katalog Motor</h2>
            <span style="font-size:0.85rem;color:var(--text-muted)">Data dari Katalog :8001</span>
        </div>

        <?php if(!empty($motors)): ?>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1.25rem;margin-bottom:2.5rem">
                <?php foreach($motors as $motor): ?>
                    <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-lg);overflow:hidden;transition:all 0.3s;position:relative"
                         onmouseenter="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 24px rgba(0,0,0,0.3)';this.style.borderColor='var(--primary)'"
                         onmouseleave="this.style.transform='';this.style.boxShadow='';this.style.borderColor='var(--border)'">
                        
                        <?php if(!empty($motor['is_terlaris'])): ?>
                            <span style="position:absolute;top:12px;right:12px;background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;font-size:0.7rem;font-weight:700;padding:4px 10px;border-radius:20px;z-index:5">🔥 Terlaris</span>
                        <?php endif; ?>

                        <div style="width:100%;height:180px;background:var(--bg-surface);display:flex;align-items:center;justify-content:center;overflow:hidden">
                            <?php if(!empty($motor['gambar'])): ?>
                                <div style="width:100%;height:100%;background-image:url('<?= esc($motor['gambar']) ?>');background-size:contain;background-position:center;background-repeat:no-repeat"></div>
                            <?php else: ?>
                                <span style="font-size:3.5rem">🏍️</span>
                            <?php endif; ?>
                        </div>

                        <div style="padding:1.25rem">
                            <div style="display:flex;gap:6px;margin-bottom:0.5rem;flex-wrap:wrap">
                                <span style="font-size:0.65rem;padding:3px 8px;background:rgba(139,92,246,0.15);color:var(--primary-light);border-radius:12px;font-weight:600"><?= esc($motor['merk']) ?></span>
                                <span style="font-size:0.65rem;padding:3px 8px;background:rgba(6,182,212,0.15);color:var(--secondary);border-radius:12px;font-weight:600"><?= esc($motor['tipe']) ?></span>
                                <span style="font-size:0.65rem;padding:3px 8px;background:rgba(245,158,11,0.15);color:#fcd34d;border-radius:12px;font-weight:600"><?= esc($motor['tahun']) ?></span>
                            </div>
                            <h3 style="font-size:1rem;font-weight:700;margin-bottom:0.25rem;color:var(--text-primary)"><?= esc($motor['nama']) ?></h3>
                            <div style="font-size:0.8rem;color:var(--text-muted);margin-bottom:0.75rem"><?= esc($motor['warna']) ?></div>
                            
                            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem">
                                <span class="price-text" style="font-size:1.1rem">Rp <?= number_format($motor['harga'], 0, ',', '.') ?></span>
                                <span style="font-size:0.8rem;color:<?= $motor['stok'] > 0 ? '#6ee7b7' : '#fca5a5' ?>;font-weight:600">Stok: <?= esc($motor['stok']) ?></span>
                            </div>

                            <?php if($motor['stok'] > 0): ?>
                                <a href="<?= base_url('/orders/create?motor_id=' . $motor['id']) ?>" 
                                   style="display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:10px;background:linear-gradient(135deg,var(--primary),var(--primary-dark));color:#fff;border-radius:var(--radius-sm);text-decoration:none;font-size:0.85rem;font-weight:600;transition:all 0.2s"
                                   onmouseenter="this.style.transform='translateY(-1px)';this.style.boxShadow='0 4px 12px rgba(139,92,246,0.4)'"
                                   onmouseleave="this.style.transform='';this.style.boxShadow=''">
                                    🛒 Pesan Motor Ini
                                </a>
                            <?php else: ?>
                                <div style="display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:10px;background:var(--bg-surface);color:var(--text-muted);border-radius:var(--radius-sm);font-size:0.85rem;font-weight:600;cursor:not-allowed">
                                    ❌ Stok Habis
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div style="text-align:center;padding:3rem;color:var(--text-muted);background:var(--bg-card);border-radius:var(--radius-lg);border:1px solid var(--border);margin-bottom:2.5rem">
                <p style="font-size:2.5rem;margin-bottom:0.5rem">🏍️</p>
                <p>Tidak dapat memuat katalog motor. Pastikan Katalog berjalan di port 8001.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- ===== RIWAYAT TRANSAKSI ===== -->
    <div id="riwayat" style="scroll-margin-top:90px">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
            <h2 style="font-size:1.3rem;font-weight:700">📋 Riwayat Transaksi</h2>
            <a href="<?= base_url('/orders/create') ?>" class="btn btn-primary btn-sm">➕ Buat Pesanan Baru</a>
        </div>

        <?php if(count($orders) > 0): ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Kode Order</th>
                            <th>Pelanggan</th>
                            <th>Motor</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Pembayaran</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($orders as $order): ?>
                            <tr>
                                <td style="font-weight:600;color:var(--primary-light)"><?= esc($order['kode_order']) ?></td>
                                <td><?= esc($order['nama_pelanggan']) ?></td>
                                <td style="font-size:0.85rem"><?= esc($order['motor_nama']) ?></td>
                                <td><span class="price-text">Rp <?= number_format($order['total'], 0, ',', '.') ?></span></td>
                                <td>
                                    <span class="status-badge status-<?= esc($order['status']) ?>">
                                        <?= ucfirst(esc($order['status'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php
                                        $ps = $order['payment_status'] ?? 'belum_bayar';
                                        $psColors = [
                                            'belum_bayar' => 'background:rgba(245,158,11,0.2);color:#fcd34d',
                                            'menunggu'    => 'background:rgba(59,130,246,0.2);color:#93c5fd',
                                            'berhasil'    => 'background:rgba(34,197,94,0.2);color:#6ee7b7',
                                            'gagal'       => 'background:rgba(239,68,68,0.2);color:#fca5a5',
                                            'kadaluarsa'  => 'background:rgba(107,114,128,0.2);color:#9ca3af',
                                        ];
                                        $psLabels = [
                                            'belum_bayar' => '⏳ Belum Bayar',
                                            'menunggu'    => '🔄 Menunggu',
                                            'berhasil'    => '✅ Lunas',
                                            'gagal'       => '❌ Gagal',
                                            'kadaluarsa'  => '⏰ Kadaluarsa',
                                        ];
                                    ?>
                                    <span style="padding:3px 10px;border-radius:12px;font-size:0.7rem;font-weight:600;white-space:nowrap;<?= $psColors[$ps] ?? '' ?>">
                                        <?= $psLabels[$ps] ?? ucfirst($ps) ?>
                                    </span>
                                </td>
                                <td style="font-size:0.8rem"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                                <td>
                                    <div style="display:flex;gap:4px;flex-wrap:nowrap">
                                        <?php if(in_array($ps, ['belum_bayar', 'menunggu']) && $order['status'] !== 'dibatalkan'): ?>
                                            <a href="<?= base_url('/orders/payment/' . $order['id']) ?>" class="btn btn-sm" style="padding:4px 10px;font-size:0.72rem;background:linear-gradient(135deg,#22c55e,#16a34a);color:#fff;border:none">💳 Bayar</a>
                                        <?php endif; ?>
                                        <?php 
                                            $isDisabled = empty($order['can_edit']); 
                                            $disabledStyle = $isDisabled ? 'opacity:0.5;pointer-events:none;' : '';
                                        ?>
                                        <a href="<?= base_url('/orders/edit/' . $order['id']) ?>" class="btn btn-primary btn-sm" style="padding:4px 8px;font-size:0.72rem;<?= $disabledStyle ?>">✏️ Edit (Maks 4 Jam)</a>
                                        <form method="POST" action="<?= base_url('/orders/cancel/' . $order['id']) ?>" style="margin:0" onsubmit="return confirm('Yakin membatalkan <?= esc($order['kode_order']) ?>?')">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm" style="padding:4px 8px;font-size:0.72rem;background:rgba(239,68,68,0.2);color:#fca5a5;border:1px solid rgba(239,68,68,0.3);cursor:pointer;<?= $disabledStyle ?>" <?= $isDisabled ? 'disabled' : '' ?>>🗑️ Batalkan</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div style="text-align:center;padding:4rem;color:var(--text-muted);background:var(--bg-card);border-radius:var(--radius-lg);border:1px solid var(--border)">
                <p style="font-size:3rem;margin-bottom:1rem">📋</p>
                <p style="font-size:1.1rem;margin-bottom:1rem">Belum ada transaksi</p>
                <a href="<?= base_url('/orders/create') ?>" class="btn btn-primary">➕ Buat Pesanan Pertama</a>
            </div>
        <?php endif; ?>
    </div>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    @keyframes slideDown {
        from { transform: translateY(-10px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
</style>
<?= $this->endSection() ?>
