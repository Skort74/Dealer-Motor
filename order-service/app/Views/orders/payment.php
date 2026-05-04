<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Pembayaran - <?= esc($order['kode_order']) ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
    <?php
        $deadline = strtotime($order['payment_deadline']);
        $sisaDetik = $deadline - time();
        $expired = $sisaDetik <= 0;
    ?>

    <a href="<?= base_url('/orders') ?>" class="back-link">← Kembali ke Dashboard</a>

    <div class="page-header" style="margin-top:1rem">
        <h1>💳 Pembayaran</h1>
        <p>Selesaikan pembayaran untuk pesanan <strong style="color:var(--primary-light)"><?= esc($order['kode_order']) ?></strong></p>
    </div>

    <!-- PAYMENT REMINDER / COUNTDOWN -->
    <div id="payment-reminder" style="padding:1rem 1.25rem;border-radius:var(--radius-lg, 12px);margin-bottom:1.5rem;display:flex;align-items:center;justify-content:space-between;gap:12px;
        <?php if($expired): ?>
            background:rgba(239,68,68,0.15);border:1px solid rgba(239,68,68,0.3)
        <?php else: ?>
            background:rgba(245,158,11,0.12);border:1px solid rgba(245,158,11,0.3)
        <?php endif; ?>">
        <div style="display:flex;align-items:center;gap:10px">
            <span style="font-size:1.5rem"><?= $expired ? '❌' : '⏰' ?></span>
            <div>
                <div style="font-weight:700;font-size:0.95rem;color:<?= $expired ? '#fca5a5' : '#fcd34d' ?>">
                    <?= $expired ? 'Waktu Pembayaran Habis!' : 'Segera Selesaikan Pembayaran' ?>
                </div>
                <div style="font-size:0.8rem;color:var(--text-muted)">Batas waktu pembayaran 12 jam setelah pemesanan</div>
            </div>
        </div>
        <?php if(!$expired): ?>
            <div id="countdown" style="text-align:right">
                <div style="font-size:1.5rem;font-weight:800;font-family:monospace;color:#fbbf24" id="timer">--:--:--</div>
                <div style="font-size:0.7rem;color:var(--text-muted)">Sisa Waktu</div>
            </div>
        <?php endif; ?>
    </div>

    <!-- NOTIFICATION AREA -->
    <?php if(session()->getFlashdata('success')): ?>
        <div style="padding:1rem;background:rgba(34,197,94,0.15);border:1px solid rgba(34,197,94,0.3);border-radius:var(--radius);margin-bottom:1.5rem;display:flex;align-items:center;gap:10px">
            <span style="font-size:1.2rem">✅</span>
            <span style="color:#6ee7b7;font-weight:600"><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('error')): ?>
        <div style="padding:1rem;background:rgba(239,68,68,0.15);border:1px solid rgba(239,68,68,0.3);border-radius:var(--radius, 8px);margin-bottom:1.5rem;display:flex;align-items:center;gap:10px">
            <span style="font-size:1.2rem">❌</span>
            <span style="color:#fca5a5;font-weight:600"><?= session()->getFlashdata('error') ?></span>
        </div>
    <?php endif; ?>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:2rem">
        <!-- LEFT: Order Summary -->
        <div class="form-card">
            <h3 style="margin-bottom:1.25rem;color:var(--text-primary)">📋 Ringkasan Pesanan</h3>
            
            <div style="display:grid;gap:0.75rem">
                <div style="display:flex;justify-content:space-between;padding-bottom:0.75rem;border-bottom:1px solid var(--border)">
                    <span style="color:var(--text-muted);font-size:0.85rem">Kode Pesanan</span>
                    <span style="font-weight:700;color:var(--primary-light)"><?= esc($order['kode_order']) ?></span>
                </div>
                <div style="display:flex;justify-content:space-between;padding-bottom:0.75rem;border-bottom:1px solid var(--border)">
                    <span style="color:var(--text-muted);font-size:0.85rem">Motor</span>
                    <span style="font-weight:600"><?= esc($order['motor_nama']) ?></span>
                </div>
                <div style="display:flex;justify-content:space-between;padding-bottom:0.75rem;border-bottom:1px solid var(--border)">
                    <span style="color:var(--text-muted);font-size:0.85rem">Pelanggan</span>
                    <span style="font-weight:600"><?= esc($order['nama_pelanggan']) ?></span>
                </div>
                <div style="display:flex;justify-content:space-between;padding-bottom:0.75rem;border-bottom:1px solid var(--border)">
                    <span style="color:var(--text-muted);font-size:0.85rem">Telepon</span>
                    <span><?= esc($order['no_telepon']) ?></span>
                </div>
                <div style="display:flex;justify-content:space-between;padding-bottom:0.75rem;border-bottom:1px solid var(--border)">
                    <span style="color:var(--text-muted);font-size:0.85rem">Harga Satuan</span>
                    <span>Rp <?= number_format($order['harga_satuan'], 0, ',', '.') ?></span>
                </div>
                <div style="display:flex;justify-content:space-between;padding-bottom:0.75rem;border-bottom:1px solid var(--border)">
                    <span style="color:var(--text-muted);font-size:0.85rem">Jumlah</span>
                    <span><?= esc($order['jumlah']) ?> unit</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding:0.75rem 0">
                    <span style="font-weight:700;font-size:1rem;color:var(--text-primary)">Total Bayar</span>
                    <span class="price-text" style="font-size:1.3rem">Rp <?= number_format($order['total'], 0, ',', '.') ?></span>
                </div>
            </div>
        </div>

        <!-- RIGHT: Payment Form -->
        <div>
            <?php if(!$expired): ?>
            <div class="form-card">
                <h3 style="margin-bottom:1.25rem;color:var(--text-primary)">💳 Pilih Metode Pembayaran</h3>
                
                <form method="POST" action="<?= base_url('/orders/pay/' . $order['id']) ?>" id="paymentForm">
                    <?= csrf_field() ?>

                    <div style="display:grid;gap:0.75rem;margin-bottom:1.5rem">
                        <!-- Bank Transfer -->
                        <label class="payment-option" data-method="Transfer Bank BCA">
                            <input type="radio" name="payment_method" value="Transfer Bank BCA" required>
                            <div class="payment-option-content">
                                <span class="payment-icon">🏦</span>
                                <div>
                                    <div style="font-weight:600;font-size:0.9rem">Transfer Bank BCA</div>
                                    <div style="font-size:0.75rem;color:var(--text-muted)">No. Rek: 123-456-7890</div>
                                </div>
                            </div>
                        </label>
                        <label class="payment-option" data-method="Transfer Bank Mandiri">
                            <input type="radio" name="payment_method" value="Transfer Bank Mandiri" required>
                            <div class="payment-option-content">
                                <span class="payment-icon">🏦</span>
                                <div>
                                    <div style="font-weight:600;font-size:0.9rem">Transfer Bank Mandiri</div>
                                    <div style="font-size:0.75rem;color:var(--text-muted)">No. Rek: 098-765-4321</div>
                                </div>
                            </div>
                        </label>
                        <!-- E-Wallet -->
                        <label class="payment-option" data-method="GoPay">
                            <input type="radio" name="payment_method" value="GoPay" required>
                            <div class="payment-option-content">
                                <span class="payment-icon">📱</span>
                                <div>
                                    <div style="font-weight:600;font-size:0.9rem">GoPay</div>
                                    <div style="font-size:0.75rem;color:var(--text-muted)">Bayar via e-wallet GoPay</div>
                                </div>
                            </div>
                        </label>
                        <label class="payment-option" data-method="OVO">
                            <input type="radio" name="payment_method" value="OVO" required>
                            <div class="payment-option-content">
                                <span class="payment-icon">📱</span>
                                <div>
                                    <div style="font-weight:600;font-size:0.9rem">OVO</div>
                                    <div style="font-size:0.75rem;color:var(--text-muted)">Bayar via e-wallet OVO</div>
                                </div>
                            </div>
                        </label>
                        <!-- Cash -->
                        <label class="payment-option" data-method="Bayar di Tempat (COD)">
                            <input type="radio" name="payment_method" value="Bayar di Tempat (COD)" required>
                            <div class="payment-option-content">
                                <span class="payment-icon">💵</span>
                                <div>
                                    <div style="font-weight:600;font-size:0.9rem">Bayar di Tempat (COD)</div>
                                    <div style="font-size:0.75rem;color:var(--text-muted)">Bayar saat motor diantar</div>
                                </div>
                            </div>
                        </label>
                    </div>

                    <button type="submit" id="payBtn" class="btn btn-primary" style="width:100%;padding:14px;font-size:1rem;font-weight:700" disabled>
                        🔒 Pilih Metode Pembayaran
                    </button>
                </form>
            </div>
            <?php else: ?>
            <div class="form-card" style="text-align:center">
                <span style="font-size:3rem;display:block;margin-bottom:1rem">⏰</span>
                <h3 style="color:#fca5a5;margin-bottom:0.5rem">Waktu Pembayaran Habis</h3>
                <p style="color:var(--text-muted);margin-bottom:1.5rem">Batas pembayaran 12 jam telah terlewati. Pesanan otomatis dibatalkan.</p>
                <a href="<?= base_url('/orders') ?>" class="btn btn-secondary">Kembali ke Dashboard</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .payment-option {
        display:block;cursor:pointer;
    }
    .payment-option input[type="radio"] { display:none; }
    .payment-option-content {
        display:flex;align-items:center;gap:12px;
        padding:12px 16px;border-radius:var(--radius, 8px);
    }
    .payment-option input:checked + .payment-option-content {
        border-color:var(--primary);background:rgba(139,92,246,0.1);
        box-shadow:0 0 0 1px var(--primary);
    }
    .payment-option:hover .payment-option-content {
        border-color:var(--primary-light);
    }
    .payment-icon {
        font-size:1.5rem;width:40px;text-align:center;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Countdown timer
    <?php if(!$expired): ?>
    const deadline = <?= $deadline * 1000 ?>;
    const timerEl = document.getElementById('timer');
    const reminderEl = document.getElementById('payment-reminder');

    function updateCountdown() {
        const now = Date.now();
        const diff = deadline - now;

        if (diff <= 0) {
            timerEl.textContent = '00:00:00';
            reminderEl.style.background = 'rgba(239,68,68,0.15)';
            reminderEl.style.borderColor = 'rgba(239,68,68,0.3)';
            timerEl.style.color = '#fca5a5';
            document.getElementById('payBtn').disabled = true;
            document.getElementById('payBtn').textContent = '⏰ Waktu Habis';
            return;
        }

        const h = String(Math.floor(diff / 3600000)).padStart(2, '0');
        const m = String(Math.floor((diff % 3600000) / 60000)).padStart(2, '0');
        const s = String(Math.floor((diff % 60000) / 1000)).padStart(2, '0');
        timerEl.textContent = h + ':' + m + ':' + s;

        // Warning color when < 1 hour
        if (diff < 3600000) {
            timerEl.style.color = '#ef4444';
            timerEl.style.animation = 'pulse 1s ease-in-out infinite';
        }

        requestAnimationFrame(updateCountdown);
    }
    updateCountdown();
    <?php endif; ?>

    // Payment method selection
    const radios = document.querySelectorAll('input[name="payment_method"]');
    const payBtn = document.getElementById('payBtn');

    radios.forEach(radio => {
        radio.addEventListener('change', function() {
            payBtn.disabled = false;
            payBtn.textContent = '💳 Bayar Sekarang — Rp <?= number_format($order['total'], 0, ',', '.') ?>';
            payBtn.style.background = 'linear-gradient(135deg, #22c55e, #16a34a)';
        });
    });

    // Payment form - no confirm needed
    document.getElementById('paymentForm')?.addEventListener('submit', function(e) {
        const btn = document.getElementById('payBtn');
        btn.disabled = true;
        btn.textContent = '⏳ Memproses Pembayaran...';
    });
</script>
<?= $this->endSection() ?>