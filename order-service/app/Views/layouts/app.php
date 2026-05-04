<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Dealer Motor - Sistem Transaksi Pemesanan">
    <title><?= $this->renderSection('title') ?: 'Dashboard - Transaksi' ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <?= $this->renderSection('styles') ?>
    <style>
        :root {
            --primary: #8b5cf6;
            --primary-dark: #7c3aed;
            --primary-light: #a78bfa;
            --secondary: #06b6d4;
            --accent: #f59e0b;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --bg-dark: #0f172a;
            --bg-card: #1e293b;
            --bg-card-hover: #334155;
            --bg-surface: #162032;
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --text-muted: #64748b;
            --border: #334155;
            --border-light: #475569;
            --radius: 12px;
            --radius-sm: 8px;
            --radius-lg: 16px;
            --shadow: 0 4px 6px -1px rgba(0,0,0,0.3);
            --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.4);
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-dark);
            color: var(--text-primary);
            line-height: 1.6;
            min-height: 100vh;
        }
        .navbar {
            background: rgba(30,41,59,0.8);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            position: sticky; top:0; z-index:100;
            padding: 0 2rem;
        }
        .navbar-inner {
            max-width:1400px; margin:0 auto;
            display:flex; align-items:center; justify-content:space-between; height:70px;
        }
        .navbar-brand {
            display:flex; align-items:center; gap:12px;
            text-decoration:none; color:var(--text-primary);
        }
        .navbar-brand .logo {
            width:42px; height:42px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: var(--radius-sm);
            display:flex; align-items:center; justify-content:center;
            font-size:20px; font-weight:800; color:#fff;
        }
        .navbar-brand span {
            font-size:1.25rem; font-weight:700;
            background: linear-gradient(135deg, var(--primary-light), var(--secondary));
            -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;
        }
        .navbar-links { display:flex; gap:1rem; align-items:center; }
        .navbar-links a {
            color:var(--text-secondary); text-decoration:none; font-size:0.9rem; font-weight:500;
            padding:6px 14px; border-radius:var(--radius-sm); transition:all 0.2s;
        }
        .navbar-links a:hover, .navbar-links a.active {
            color:var(--primary-light); background:rgba(139,92,246,0.1);
        }
        .navbar-badge {
            display:inline-flex; align-items:center; gap:6px;
            padding:6px 14px; background:rgba(139,92,246,0.15);
            border:1px solid rgba(139,92,246,0.3); border-radius:20px;
            font-size:0.8rem; color:var(--primary-light); font-weight:500;
        }
        .navbar-badge .dot {
            width:8px; height:8px; background:var(--success); border-radius:50%;
            animation: pulse 2s ease-in-out infinite;
        }
        @keyframes pulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:0.5;transform:scale(0.8)} }
        .container { max-width:1400px; margin:0 auto; padding:2rem; }
        .alert {
            padding:1rem 1.5rem; border-radius:var(--radius); margin-bottom:1.5rem;
            display:flex; align-items:center; gap:10px; font-size:0.9rem;
            animation: slideDown 0.3s ease;
        }
        @keyframes slideDown { from{opacity:0;transform:translateY(-10px)} to{opacity:1;transform:translateY(0)} }
        .alert-success { background:rgba(16,185,129,0.15); border:1px solid rgba(16,185,129,0.3); color:#6ee7b7; }
        .alert-error { background:rgba(239,68,68,0.15); border:1px solid rgba(239,68,68,0.3); color:#fca5a5; }
        .btn {
            display:inline-flex; align-items:center; gap:8px;
            padding:10px 20px; border:none; border-radius:var(--radius-sm);
            font-family:'Inter',sans-serif; font-size:0.875rem; font-weight:600;
            cursor:pointer; transition:all 0.2s; text-decoration:none;
        }
        .btn-primary {
            background:linear-gradient(135deg, var(--primary), var(--primary-dark));
            color:#fff; box-shadow:0 2px 10px rgba(139,92,246,0.3);
        }
        .btn-primary:hover { transform:translateY(-2px); box-shadow:0 4px 20px rgba(139,92,246,0.4); }
        .btn-secondary { background:var(--bg-card); color:var(--text-secondary); border:1px solid var(--border); }
        .btn-secondary:hover { background:var(--bg-card-hover); color:var(--text-primary); }
        .btn-success { background:linear-gradient(135deg,var(--success),#059669); color:#fff; }
        .btn-success:hover { transform:translateY(-2px); box-shadow:0 4px 20px rgba(16,185,129,0.4); }
        .btn-sm { padding:6px 14px; font-size:0.8rem; }
        .page-header { margin-bottom:2rem; }
        .page-header h1 {
            font-size:2rem; font-weight:800; margin-bottom:0.5rem;
            background:linear-gradient(135deg,var(--text-primary),var(--text-secondary));
            -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;
        }
        .page-header p { color:var(--text-secondary); }
        .stats-bar { display:flex; gap:1.5rem; margin-bottom:2rem; flex-wrap:wrap; }
        .stat-item {
            display:flex; flex-direction:column; padding:1.25rem 1.5rem;
            background:var(--bg-card); border:1px solid var(--border); border-radius:var(--radius); min-width:160px; flex:1;
        }
        .stat-item .stat-value { font-size:1.75rem; font-weight:800; color:var(--primary-light); }
        .stat-item .stat-label { font-size:0.75rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px; }
        /* TABLE */
        .table-wrapper {
            background:var(--bg-card); border:1px solid var(--border); border-radius:var(--radius-lg); overflow:hidden;
        }
        table { width:100%; border-collapse:collapse; }
        thead { background:var(--bg-surface); }
        th {
            padding:12px 16px; text-align:left; font-size:0.75rem; font-weight:600;
            color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px;
            border-bottom:1px solid var(--border);
        }
        td { padding:12px 16px; font-size:0.9rem; border-bottom:1px solid var(--border); color:var(--text-secondary); }
        tr:last-child td { border-bottom:none; }
        tr:hover td { background:rgba(139,92,246,0.05); }
        .status-badge {
            display:inline-flex; padding:3px 10px; border-radius:20px; font-size:0.75rem; font-weight:600;
        }
        .status-confirmed { background:rgba(16,185,129,0.15); color:#6ee7b7; }
        .status-pending { background:rgba(245,158,11,0.15); color:#fcd34d; }
        .status-cancelled { background:rgba(239,68,68,0.15); color:#fca5a5; }
        /* FORM */
        .form-card {
            background:var(--bg-card); border:1px solid var(--border); border-radius:var(--radius-lg); padding:2rem;
        }
        .form-group { margin-bottom:1.25rem; }
        .form-group label {
            display:block; margin-bottom:6px; font-size:0.85rem; font-weight:600; color:var(--text-secondary);
        }
        .form-control {
            width:100%; padding:10px 14px; background:var(--bg-surface); border:1px solid var(--border);
            border-radius:var(--radius-sm); color:var(--text-primary); font-family:'Inter',sans-serif; font-size:0.9rem;
            outline:none; transition:border-color 0.2s;
        }
        .form-control:focus { border-color:var(--primary); }
        select.form-control option { background:var(--bg-card); color:var(--text-primary); }
        textarea.form-control { resize:vertical; min-height:80px; }
        .form-grid { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
        /* DETAIL */
        .detail-grid { display:grid; grid-template-columns:1fr 1fr; gap:2rem; }
        .detail-card {
            background:var(--bg-card); border:1px solid var(--border); border-radius:var(--radius-lg); padding:1.5rem;
        }
        .detail-card h3 { margin-bottom:1rem; color:var(--text-primary); font-size:1.1rem; }
        .detail-row { display:flex; justify-content:space-between; padding:8px 0; border-bottom:1px solid var(--border); }
        .detail-row:last-child { border-bottom:none; }
        .detail-row .label { color:var(--text-muted); font-size:0.85rem; }
        .detail-row .value { color:var(--text-primary); font-weight:600; font-size:0.9rem; }
        .back-link {
            display:inline-flex; align-items:center; gap:8px;
            color:var(--text-secondary); text-decoration:none; font-size:0.9rem; font-weight:500; transition:color 0.2s;
        }
        .back-link:hover { color:var(--primary-light); }
        .footer {
            text-align:center; padding:2rem; margin-top:3rem; border-top:1px solid var(--border);
            color:var(--text-muted); font-size:0.85rem;
        }
        .footer .service-info {
            display:inline-flex; align-items:center; gap:6px; margin-top:0.5rem;
            padding:4px 12px; background:rgba(139,92,246,0.1); border-radius:20px;
            font-size:0.75rem; color:var(--primary-light);
        }
        .price-text {
            font-weight:800;
            background:linear-gradient(135deg,var(--success),#34d399);
            -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;
        }
        @media(max-width:768px) {
            .container{padding:1rem} .form-grid{grid-template-columns:1fr}
            .detail-grid{grid-template-columns:1fr} .stats-bar{flex-direction:column}
            .navbar-links{display:none}
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-inner">
            <a href="<?= base_url('/orders') ?>" class="navbar-brand">
                <div class="logo">📋</div>
                <span>Dashboard</span>
            </a>
            <div class="navbar-links">
                <a href="<?= base_url('/orders') ?>" class="<?= uri_string() == 'orders' || uri_string() == '/' ? 'active' : '' ?>">📊 Dashboard</a>
                <a href="<?= base_url('/orders/create') ?>" class="<?= uri_string() == 'orders/create' ? 'active' : '' ?>">➕ Buat Pesanan</a>
                <a href="http://localhost:8001" target="_blank">🏍️ Katalog</a>
            </div>
            <div class="navbar-badge">
                <div class="dot"></div>
                Port 8002 · Layanan Transaksi
            </div>
        </div>
    </nav>
    <div class="container">
        <?php if(isset($success) && $success): ?>
            <div class="alert alert-success">✅ <?= esc($success) ?></div>
        <?php endif; ?>
        <?php if(isset($error) && $error): ?>
            <div class="alert alert-error">❌ <?= esc($error) ?></div>
        <?php endif; ?>
        
        <?= $this->renderSection('content') ?>
    </div>
    <footer class="footer">
        <p>Dealer Motor &copy; <?= date('Y') ?> — Sistem Integrasi Antar Layanan</p>
        <div class="service-info">🔗 Dashboard (Provider & Consumer) · REST API · CodeIgniter 4</div>
    </footer>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
