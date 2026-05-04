<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Edit Pesanan - Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <a href="<?= base_url('/orders') ?>" class="back-link">← Kembali ke Dashboard</a>

    <div class="page-header" style="margin-top:1rem">
        <h1>✏️ Edit Pesanan</h1>
        <p>Kode: <strong style="color:var(--primary-light)"><?= esc($order['kode_order']) ?></strong> — Motor: <strong><?= esc($order['motor_nama']) ?></strong></p>
    </div>

    <?php 
        $createdAt = strtotime($order['created_at']);
        $sisaDetik = ($createdAt + 4*3600) - time();
        $sisaJam = floor($sisaDetik / 3600);
        $sisaMenit = floor(($sisaDetik % 3600) / 60);
    ?>
    <div style="padding:0.75rem 1rem;background:rgba(245,158,11,0.15);border:1px solid rgba(245,158,11,0.3);border-radius:var(--radius);margin-bottom:1.5rem;display:flex;align-items:center;gap:10px">
        <span>⏰</span>
        <span style="font-size:0.85rem;color:#fcd34d">Sisa waktu edit: <strong><?= $sisaJam ?>j <?= $sisaMenit ?>m</strong> — Edit hanya bisa dilakukan dalam 4 jam setelah pemesanan</span>
    </div>

    <div style="display:grid;grid-template-columns:2fr 1fr;gap:2rem">
        <div class="form-card">
            <h3 style="margin-bottom:1.5rem;color:var(--text-primary)">📝 Edit Data Pesanan</h3>

            <form method="POST" action="<?= base_url('/orders/update/' . $order['id']) ?>">
                <?= csrf_field() ?>

                <!-- Motor info (read-only) -->
                <div class="form-group">
                    <label>🏍️ Motor (tidak dapat diubah)</label>
                    <input type="text" class="form-control" value="<?= esc($order['motor_nama']) ?>" disabled
                           style="opacity:0.6;cursor:not-allowed">
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="nama_pelanggan">👤 Nama Pelanggan</label>
                        <input type="text" name="nama_pelanggan" id="nama_pelanggan" class="form-control"
                               value="<?= esc(old('nama_pelanggan', $order['nama_pelanggan'])) ?>" placeholder="Nama lengkap" required>
                    </div>
                    <div class="form-group">
                        <label for="no_telepon">📱 No. Telepon</label>
                        <input type="text" name="no_telepon" id="no_telepon" class="form-control"
                               value="<?= esc(old('no_telepon', $order['no_telepon'])) ?>" placeholder="08xxxxxxxxxx" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="alamat">📍 Alamat</label>
                    <textarea name="alamat" id="alamat" class="form-control" placeholder="Alamat lengkap" required><?= esc(old('alamat', $order['alamat'])) ?></textarea>
                </div>

                <div class="form-group">
                    <label for="jumlah">📦 Jumlah Unit</label>
                    <input type="number" name="jumlah" id="jumlah" class="form-control" 
                           value="<?= esc(old('jumlah', $order['jumlah'])) ?>" min="1" required>
                </div>

                <div class="form-group">
                    <label for="catatan">📝 Catatan (opsional)</label>
                    <textarea name="catatan" id="catatan" class="form-control" placeholder="Catatan tambahan..."><?= esc(old('catatan', $order['catatan'])) ?></textarea>
                </div>

                <div style="display:flex;gap:1rem;margin-top:1.5rem">
                    <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
                    <a href="<?= base_url('/orders') ?>" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>

        <!-- Sidebar: Summary -->
        <div>
            <div class="form-card" style="position:sticky;top:90px">
                <h3 style="margin-bottom:1rem;color:var(--text-primary)">📋 Info Pesanan</h3>
                <div style="margin-bottom:0.75rem;padding-bottom:0.75rem;border-bottom:1px solid var(--border)">
                    <div style="font-size:0.7rem;color:var(--text-muted);text-transform:uppercase;margin-bottom:4px">Kode Order</div>
                    <div style="font-weight:700;color:var(--primary-light)"><?= esc($order['kode_order']) ?></div>
                </div>
                <div style="margin-bottom:0.75rem;padding-bottom:0.75rem;border-bottom:1px solid var(--border)">
                    <div style="font-size:0.7rem;color:var(--text-muted);text-transform:uppercase;margin-bottom:4px">Motor</div>
                    <div style="font-weight:600;color:var(--text-primary)"><?= esc($order['motor_nama']) ?></div>
                </div>
                <div style="margin-bottom:0.75rem;padding-bottom:0.75rem;border-bottom:1px solid var(--border)">
                    <div style="font-size:0.7rem;color:var(--text-muted);text-transform:uppercase;margin-bottom:4px">Harga Satuan</div>
                    <div style="font-weight:600;color:var(--text-primary)">Rp <?= number_format($order['harga_satuan'], 0, ',', '.') ?></div>
                </div>
                <div style="margin-bottom:0.75rem;padding-bottom:0.75rem;border-bottom:1px solid var(--border)">
                    <div style="font-size:0.7rem;color:var(--text-muted);text-transform:uppercase;margin-bottom:4px">Status</div>
                    <span class="status-badge status-<?= esc($order['status']) ?>"><?= ucfirst(esc($order['status'])) ?></span>
                </div>
                <div style="margin-bottom:0.75rem;padding-bottom:0.75rem;border-bottom:1px solid var(--border)">
                    <div style="font-size:0.7rem;color:var(--text-muted);text-transform:uppercase;margin-bottom:4px">Tanggal Pesan</div>
                    <div style="font-size:0.85rem;color:var(--text-secondary)"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></div>
                </div>
                <div id="live-total">
                    <div style="font-size:0.7rem;color:var(--text-muted);text-transform:uppercase;margin-bottom:4px">Total Saat Ini</div>
                    <div class="price-text" style="font-size:1.3rem" id="total-display">Rp <?= number_format($order['total'], 0, ',', '.') ?></div>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const jumlahInput = document.getElementById('jumlah');
    const totalDisplay = document.getElementById('total-display');
    const hargaSatuan = <?= (float)$order['harga_satuan'] ?>;

    jumlahInput.addEventListener('input', function() {
        const jumlah = parseInt(this.value) || 0;
        const total = hargaSatuan * jumlah;
        totalDisplay.textContent = 'Rp ' + total.toLocaleString('id-ID');
    });
</script>
<?= $this->endSection() ?>
