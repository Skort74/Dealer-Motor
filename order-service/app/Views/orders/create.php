<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Buat Pesanan - OrderService<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <a href="<?= base_url('/orders') ?>" class="back-link">← Kembali ke Dashboard</a>

    <div class="page-header" style="margin-top:1rem">
        <h1>➕ Buat Pesanan Baru</h1>
        <p>Isi formulir untuk melakukan pemesanan motor. Stok akan diverifikasi langsung ke MotorService.</p>
    </div>

    <div style="display:grid;grid-template-columns:2fr 1fr;gap:2rem">
        <div class="form-card">
            <h3 style="margin-bottom:1.5rem;color:var(--text-primary)">📝 Formulir Pemesanan</h3>

            <form method="POST" action="<?= base_url('/orders/store') ?>">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label for="motor_id">🏍️ Pilih Motor</label>
                    <select name="motor_id" id="motor_id" class="form-control" required>
                        <option value="">-- Pilih Motor --</option>
                        <?php foreach($motors as $motor): ?>
                            <option value="<?= esc($motor['id']) ?>"
                                data-harga="<?= esc($motor['harga']) ?>"
                                data-stok="<?= esc($motor['stok']) ?>"
                                data-merk="<?= esc($motor['merk']) ?>"
                                data-gambar="<?= esc($motor['gambar'] ?? '') ?>"
                                <?= (old('motor_id') ?? ($selectedMotor['id'] ?? '')) == $motor['id'] ? 'selected' : '' ?>>
                                <?= esc($motor['nama']) ?> — <?= esc($motor['merk']) ?> (Stok: <?= esc($motor['stok']) ?>) — Rp <?= number_format($motor['harga'], 0, ',', '.') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="nama_pelanggan">👤 Nama Pelanggan</label>
                        <input type="text" name="nama_pelanggan" id="nama_pelanggan" class="form-control"
                               value="<?= esc(old('nama_pelanggan')) ?>" placeholder="Nama lengkap" required>
                    </div>
                    <div class="form-group">
                        <label for="no_telepon">📱 No. Telepon</label>
                        <input type="text" name="no_telepon" id="no_telepon" class="form-control"
                               value="<?= esc(old('no_telepon')) ?>" placeholder="08xxxxxxxxxx" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="alamat">📍 Alamat</label>
                    <textarea name="alamat" id="alamat" class="form-control" placeholder="Alamat lengkap" required><?= esc(old('alamat')) ?></textarea>
                </div>

                <div class="form-group">
                    <label for="jumlah">📦 Jumlah Unit</label>
                    <input type="number" name="jumlah" id="jumlah" class="form-control" value="<?= esc(old('jumlah', 1)) ?>" min="1" required>
                </div>

                <div class="form-group">
                    <label for="catatan">📝 Catatan (opsional)</label>
                    <textarea name="catatan" id="catatan" class="form-control" placeholder="Catatan tambahan..."><?= esc(old('catatan')) ?></textarea>
                </div>

                <div style="display:flex;gap:1rem;margin-top:1.5rem">
                    <button type="submit" class="btn btn-success">✅ Konfirmasi Pesanan</button>
                    <a href="<?= base_url('/orders') ?>" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>

        <div>
            <div class="form-card" style="position:sticky;top:90px">
                <h3 style="margin-bottom:1rem;color:var(--text-primary)">💰 Ringkasan</h3>
                <div id="order-summary">
                    <div style="text-align:center;padding:2rem;color:var(--text-muted)">
                        <p style="font-size:2rem;margin-bottom:0.5rem">🏍️</p>
                        <p>Pilih motor untuk melihat ringkasan</p>
                    </div>
                </div>
            </div>

            <div class="form-card" style="margin-top:1rem">
                <h3 style="margin-bottom:0.75rem;color:var(--text-primary)">🔗 Komunikasi</h3>
                <div style="font-size:0.8rem;color:var(--text-secondary);line-height:1.8">
                    <p>📡 <strong>Consumer → MotorService</strong></p>
                    <p style="font-family:monospace;color:var(--secondary);margin-bottom:0.5rem">GET :8001/api/motors/{id}/stock</p>
                    <p>Verifikasi stok sebelum order dikonfirmasi</p>
                    <hr style="border:none;border-top:1px solid var(--border);margin:0.75rem 0">
                    <p>📡 <strong>Consumer → MotorService</strong></p>
                    <p style="font-family:monospace;color:var(--secondary)">PUT :8001/api/motors/{id}/stock</p>
                    <p>Kurangi stok setelah order berhasil</p>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const motorSelect = document.getElementById('motor_id');
    const jumlahInput = document.getElementById('jumlah');
    const summaryDiv = document.getElementById('order-summary');

    function updateSummary() {
        const opt = motorSelect.options[motorSelect.selectedIndex];
        if (!opt || !opt.value) {
            summaryDiv.innerHTML = '<div style="text-align:center;padding:2rem;color:var(--text-muted)"><p style="font-size:2rem;margin-bottom:0.5rem">🏍️</p><p>Pilih motor untuk melihat ringkasan</p></div>';
            return;
        }
        const harga = parseFloat(opt.dataset.harga);
        const stok = parseInt(opt.dataset.stok);
        const merk = opt.dataset.merk;
        const gambar = opt.dataset.gambar;
        const jumlah = parseInt(jumlahInput.value) || 1;
        const total = harga * jumlah;
        const nama = opt.text.split(' — ')[0];

        let imageHtml = '';
        if (gambar && gambar !== '') {
            imageHtml = `<div style="width: 100%; height: 150px; background-image: url('${gambar}'); background-size: contain; background-position: center; background-repeat: no-repeat; margin-bottom: 1rem; border-radius: var(--radius-sm);"></div>`;
        } else {
            imageHtml = `<div style="text-align:center;padding:1rem;color:var(--text-muted)"><p style="font-size:2rem;margin-bottom:0.5rem">🏍️</p></div>`;
        }

        summaryDiv.innerHTML = `
            ${imageHtml}
            <div style="margin-bottom:1rem;padding-bottom:1rem;border-bottom:1px solid var(--border)">
                <div style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;margin-bottom:4px">Motor</div>
                <div style="font-weight:700;color:var(--text-primary)">${nama}</div>
                <div style="font-size:0.8rem;color:var(--text-secondary)">${merk}</div>
            </div>
            <div style="display:flex;justify-content:space-between;margin-bottom:0.5rem">
                <span style="color:var(--text-muted);font-size:0.85rem">Harga/unit</span>
                <span style="color:var(--text-primary);font-weight:600">Rp ${harga.toLocaleString('id-ID')}</span>
            </div>
            <div style="display:flex;justify-content:space-between;margin-bottom:0.5rem">
                <span style="color:var(--text-muted);font-size:0.85rem">Jumlah</span>
                <span style="color:var(--text-primary);font-weight:600">${jumlah} unit</span>
            </div>
            <div style="display:flex;justify-content:space-between;margin-bottom:0.5rem">
                <span style="color:var(--text-muted);font-size:0.85rem">Stok tersedia</span>
                <span style="color:${stok > 0 ? '#6ee7b7' : '#fca5a5'};font-weight:600">${stok} unit</span>
            </div>
            <hr style="border:none;border-top:1px solid var(--border);margin:1rem 0">
            <div style="display:flex;justify-content:space-between">
                <span style="color:var(--text-primary);font-weight:700;font-size:1rem">Total</span>
                <span style="font-weight:800;font-size:1.2rem;background:linear-gradient(135deg,#10b981,#34d399);-webkit-background-clip:text;-webkit-text-fill-color:transparent">Rp ${total.toLocaleString('id-ID')}</span>
            </div>`;
    }

    motorSelect.addEventListener('change', updateSummary);
    jumlahInput.addEventListener('input', updateSummary);
    updateSummary();
</script>
<?= $this->endSection() ?>
