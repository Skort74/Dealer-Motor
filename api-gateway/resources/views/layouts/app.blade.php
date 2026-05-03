<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="API Gateway - Dealer Motor Microservices">
    <title>@yield('title', 'API Gateway - Dealer Motor')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #10b981;
            --primary-dark: #059669;
            --primary-light: #34d399;
            --secondary: #06b6d4;
            --accent: #f59e0b;
            --purple: #8b5cf6;
            --indigo: #6366f1;
            --danger: #ef4444;
            --bg-dark: #0a0f1a;
            --bg-card: #141b2d;
            --bg-card-hover: #1e293b;
            --bg-surface: #111827;
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --text-muted: #64748b;
            --border: #1e293b;
            --border-light: #334155;
            --radius: 12px;
            --radius-sm: 8px;
            --radius-lg: 16px;
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Inter',sans-serif; background:var(--bg-dark); color:var(--text-primary); line-height:1.6; min-height:100vh; }
        .navbar {
            background:rgba(20,27,45,0.9); backdrop-filter:blur(20px);
            border-bottom:1px solid var(--border); position:sticky; top:0; z-index:100; padding:0 2rem;
        }
        .navbar-inner { max-width:1400px; margin:0 auto; display:flex; align-items:center; justify-content:space-between; height:70px; }
        .navbar-brand { display:flex; align-items:center; gap:12px; text-decoration:none; color:var(--text-primary); }
        .navbar-brand .logo {
            width:42px; height:42px; background:linear-gradient(135deg,var(--primary),var(--secondary));
            border-radius:var(--radius-sm); display:flex; align-items:center; justify-content:center;
            font-size:18px; font-weight:800; color:#fff;
        }
        .navbar-brand span { font-size:1.25rem; font-weight:700; background:linear-gradient(135deg,var(--primary-light),var(--secondary)); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
        .navbar-links { display:flex; gap:0.5rem; align-items:center; }
        .navbar-links a {
            color:var(--text-secondary); text-decoration:none; font-size:0.85rem; font-weight:500;
            padding:8px 14px; border-radius:var(--radius-sm); transition:all 0.2s;
        }
        .navbar-links a:hover, .navbar-links a.active { color:var(--primary-light); background:rgba(16,185,129,0.1); }
        .navbar-badge { display:inline-flex; align-items:center; gap:6px; padding:6px 14px; background:rgba(16,185,129,0.15); border:1px solid rgba(16,185,129,0.3); border-radius:20px; font-size:0.8rem; color:var(--primary-light); font-weight:500; }
        .navbar-badge .dot { width:8px; height:8px; background:var(--primary); border-radius:50%; animation:pulse 2s ease-in-out infinite; }
        @keyframes pulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:0.5;transform:scale(0.8)} }
        .container { max-width:1400px; margin:0 auto; padding:2rem; }
        .alert { padding:1rem 1.5rem; border-radius:var(--radius); margin-bottom:1.5rem; display:flex; align-items:center; gap:10px; font-size:0.9rem; animation:slideDown 0.3s ease; }
        @keyframes slideDown { from{opacity:0;transform:translateY(-10px)} to{opacity:1;transform:translateY(0)} }
        .alert-success { background:rgba(16,185,129,0.15); border:1px solid rgba(16,185,129,0.3); color:#6ee7b7; }
        .alert-error { background:rgba(239,68,68,0.15); border:1px solid rgba(239,68,68,0.3); color:#fca5a5; }
        .btn { display:inline-flex; align-items:center; gap:8px; padding:10px 20px; border:none; border-radius:var(--radius-sm); font-family:'Inter',sans-serif; font-size:0.875rem; font-weight:600; cursor:pointer; transition:all 0.2s; text-decoration:none; }
        .btn-primary { background:linear-gradient(135deg,var(--primary),var(--primary-dark)); color:#fff; box-shadow:0 2px 10px rgba(16,185,129,0.3); }
        .btn-primary:hover { transform:translateY(-2px); box-shadow:0 4px 20px rgba(16,185,129,0.4); }
        .btn-secondary { background:var(--bg-card); color:var(--text-secondary); border:1px solid var(--border); }
        .btn-secondary:hover { background:var(--bg-card-hover); color:var(--text-primary); }
        .btn-accent { background:linear-gradient(135deg,var(--accent),#d97706); color:#fff; }
        .btn-accent:hover { transform:translateY(-2px); box-shadow:0 4px 20px rgba(245,158,11,0.4); }
        .btn-purple { background:linear-gradient(135deg,var(--purple),#7c3aed); color:#fff; }
        .btn-honda { background:linear-gradient(135deg,#ef4444,#dc2626); color:#fff; }
        .btn-yamaha { background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff; }
        .btn-sm { padding:6px 14px; font-size:0.8rem; }
        .page-header { margin-bottom:2rem; }
        .page-header h1 { font-size:2rem; font-weight:800; margin-bottom:0.5rem; }
        .page-header p { color:var(--text-secondary); }
        .stats-bar { display:flex; gap:1.5rem; margin-bottom:2rem; flex-wrap:wrap; }
        .stat-item { display:flex; flex-direction:column; padding:1.25rem 1.5rem; background:var(--bg-card); border:1px solid var(--border); border-radius:var(--radius); min-width:160px; flex:1; }
        .stat-item .stat-value { font-size:1.75rem; font-weight:800; color:var(--primary-light); }
        .stat-item .stat-label { font-size:0.75rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px; }
        .card { background:var(--bg-card); border:1px solid var(--border); border-radius:var(--radius-lg); overflow:hidden; transition:all 0.3s; }
        .card:hover { transform:translateY(-4px); box-shadow:0 10px 15px -3px rgba(0,0,0,0.4); border-color:var(--border-light); }
        .card-image { height:180px; background:linear-gradient(135deg,var(--bg-surface),var(--bg-card)); display:flex; align-items:center; justify-content:center; position:relative; }
        .card-image .motor-icon { font-size:3.5rem; opacity:0.6; }
        .card-image .badge-terlaris { position:absolute; top:10px; right:10px; background:linear-gradient(135deg,#ef4444,#f97316); color:#fff; padding:5px 12px; border-radius:20px; font-size:0.7rem; font-weight:700; animation:badgePulse 2s ease-in-out infinite; }
        @keyframes badgePulse { 0%,100%{box-shadow:0 2px 10px rgba(239,68,68,0.4)} 50%{box-shadow:0 2px 20px rgba(239,68,68,0.6)} }
        .card-image .badge-merk { position:absolute; top:10px; left:10px; padding:4px 10px; border-radius:20px; font-size:0.7rem; font-weight:600; text-transform:uppercase; letter-spacing:0.5px; }
        .badge-honda { background:rgba(239,68,68,0.2); border:1px solid rgba(239,68,68,0.4); color:#fca5a5; }
        .badge-yamaha { background:rgba(59,130,246,0.2); border:1px solid rgba(59,130,246,0.4); color:#93c5fd; }
        .badge-suzuki { background:rgba(245,158,11,0.2); border:1px solid rgba(245,158,11,0.4); color:#fcd34d; }
        .badge-kawasaki { background:rgba(16,185,129,0.2); border:1px solid rgba(16,185,129,0.4); color:#6ee7b7; }
        .badge-default { background:rgba(99,102,241,0.2); border:1px solid rgba(99,102,241,0.4); color:#a5b4fc; }
        .card-body { padding:1.25rem; }
        .card-title { font-size:1rem; font-weight:700; margin-bottom:0.5rem; }
        .card-title a { color:inherit; text-decoration:none; transition:color 0.2s; }
        .card-title a:hover { color:var(--primary-light); }
        .card-meta { display:flex; gap:6px; flex-wrap:wrap; margin-bottom:0.5rem; }
        .card-tag { display:inline-flex; align-items:center; gap:4px; padding:2px 8px; background:rgba(100,116,139,0.2); border-radius:6px; font-size:0.7rem; color:var(--text-secondary); }
        .card-price { font-size:1.1rem; font-weight:800; background:linear-gradient(135deg,var(--primary),#34d399); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; margin-bottom:0.5rem; }
        .card-stock { display:flex; align-items:center; justify-content:space-between; padding-top:0.5rem; border-top:1px solid var(--border); }
        .stock-indicator { display:flex; align-items:center; gap:6px; font-size:0.75rem; }
        .stock-indicator .dot { width:7px; height:7px; border-radius:50%; }
        .stock-available .dot { background:#10b981; } .stock-available { color:#6ee7b7; }
        .stock-low .dot { background:#f59e0b; } .stock-low { color:#fcd34d; }
        .stock-empty .dot { background:#ef4444; } .stock-empty { color:#fca5a5; }
        .motor-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(290px,1fr)); gap:1.25rem; }
        .table-wrapper { background:var(--bg-card); border:1px solid var(--border); border-radius:var(--radius-lg); overflow:hidden; }
        table { width:100%; border-collapse:collapse; }
        thead { background:var(--bg-surface); }
        th { padding:10px 14px; text-align:left; font-size:0.7rem; font-weight:600; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid var(--border); }
        td { padding:10px 14px; font-size:0.85rem; border-bottom:1px solid var(--border); color:var(--text-secondary); }
        tr:last-child td { border-bottom:none; }
        tr:hover td { background:rgba(16,185,129,0.03); }
        .status-badge { display:inline-flex; padding:3px 10px; border-radius:20px; font-size:0.7rem; font-weight:600; }
        .status-confirmed { background:rgba(16,185,129,0.15); color:#6ee7b7; }
        .status-pending { background:rgba(245,158,11,0.15); color:#fcd34d; }
        .status-cancelled { background:rgba(239,68,68,0.15); color:#fca5a5; }
        .service-status { display:inline-flex; align-items:center; gap:6px; padding:4px 12px; border-radius:20px; font-size:0.75rem; font-weight:600; }
        .service-up { background:rgba(16,185,129,0.15); color:#6ee7b7; }
        .service-down { background:rgba(239,68,68,0.15); color:#fca5a5; }
        .form-card { background:var(--bg-card); border:1px solid var(--border); border-radius:var(--radius-lg); padding:2rem; }
        .form-group { margin-bottom:1.25rem; }
        .form-group label { display:block; margin-bottom:6px; font-size:0.85rem; font-weight:600; color:var(--text-secondary); }
        .form-control { width:100%; padding:10px 14px; background:var(--bg-surface); border:1px solid var(--border); border-radius:var(--radius-sm); color:var(--text-primary); font-family:'Inter',sans-serif; font-size:0.9rem; outline:none; transition:border-color 0.2s; }
        .form-control:focus { border-color:var(--primary); }
        select.form-control option { background:var(--bg-card); color:var(--text-primary); }
        textarea.form-control { resize:vertical; min-height:80px; }
        .form-grid { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
        .detail-grid { display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; }
        .detail-card { background:var(--bg-card); border:1px solid var(--border); border-radius:var(--radius-lg); padding:1.5rem; }
        .detail-card h3 { margin-bottom:1rem; color:var(--text-primary); font-size:1rem; }
        .detail-row { display:flex; justify-content:space-between; padding:8px 0; border-bottom:1px solid var(--border); }
        .detail-row:last-child { border-bottom:none; }
        .detail-row .label { color:var(--text-muted); font-size:0.85rem; }
        .detail-row .value { color:var(--text-primary); font-weight:600; font-size:0.85rem; }
        .price-text { font-weight:800; background:linear-gradient(135deg,var(--primary),#34d399); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
        .back-link { display:inline-flex; align-items:center; gap:8px; color:var(--text-secondary); text-decoration:none; font-size:0.9rem; font-weight:500; transition:color 0.2s; }
        .back-link:hover { color:var(--primary-light); }
        .gateway-badge { display:inline-flex; align-items:center; gap:6px; padding:4px 10px; background:rgba(16,185,129,0.1); border:1px solid rgba(16,185,129,0.2); border-radius:6px; font-size:0.7rem; color:var(--primary-light); font-weight:500; margin-bottom:0.5rem; }
        .footer { text-align:center; padding:2rem; margin-top:3rem; border-top:1px solid var(--border); color:var(--text-muted); font-size:0.85rem; }
        .footer .service-info { display:inline-flex; align-items:center; gap:6px; margin-top:0.5rem; padding:4px 12px; background:rgba(16,185,129,0.1); border-radius:20px; font-size:0.75rem; color:var(--primary-light); }
        .filter-bar { display:flex; gap:0.75rem; margin-bottom:1.5rem; flex-wrap:wrap; align-items:center; }

        /* Clean Dashboard Utilities */
        .grid-service-cards { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
        .mb-6 { margin-bottom: 1.5rem; }
        .mb-8 { margin-bottom: 2rem; }
        .grid-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
        .service-icon { font-size: 1.2rem; margin-right: 0.75rem; opacity: 0.9; }
        .table-responsive { overflow-x: auto; }
        .section-title { font-size: 1.25rem; font-weight: 700; margin-bottom: 1.5rem; color: var(--text-primary); }
        .clean-btn { transition: all 0.2s ease; }
        .service-card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; }
        
        /* Additional Cleanup Utilities */
        .actions-grid { display: flex; gap: 0.5rem; flex-wrap: nowrap; align-items: center; }
        .summary-card { position: sticky; top: 90px; height: fit-content; }
        .form-section { margin-bottom: 1.5rem; }
        .inline-form { display: inline-flex; gap: 0.5rem; align-items: center; }
        @media(max-width:768px) { .container{padding:1rem} .motor-grid{grid-template-columns:1fr} .detail-grid{grid-template-columns:1fr} .form-grid{grid-template-columns:1fr} .stats-bar{flex-direction:column} .navbar-links{display:none} .filter-bar{flex-direction:column} }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-inner">
            <a href="{{ route('gateway.index') }}" class="navbar-brand">
            <div class="navbar-links">
                <a href="{{ route('gateway.index') }}" class="{{ request()->routeIs('gateway.index') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('gateway.motors') }}" class="{{ request()->routeIs('gateway.motors') ? 'active' : '' }}">Motor</a>
                <a href="{{ route('gateway.orders') }}" class="{{ request()->routeIs('gateway.orders') ? 'active' : '' }}">Transaksi</a>
                <a href="{{ route('gateway.order.create') }}" class="{{ request()->routeIs('gateway.order.create') ? 'active' : '' }}"> Pesan</a>
                <a href="{{ route('gateway.external') }}" class="{{ request()->routeIs('gateway.external') ? 'active' : '' }}">External API</a>
            </div>
            <div class="navbar-badge">
                <div class="dot"></div>
            </div>
        </div>
    </nav>
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">❌ {{ session('error') }}</div>
        @endif
        @yield('content')
    </div>
    @yield('scripts')
</body>
</html>
