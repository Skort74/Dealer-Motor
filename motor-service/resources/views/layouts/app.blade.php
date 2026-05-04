<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Dealer Motor - Katalog Motor Terlengkap">
    <title>@yield('title', 'Dealer Motor - Katalog')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* ============================================
           DESIGN SYSTEM - MOTOR SERVICE
           ============================================ */
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #818cf8;
            --secondary: #0ea5e9;
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

            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3), 0 2px 4px -2px rgba(0, 0, 0, 0.2);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.4), 0 4px 6px -4px rgba(0, 0, 0, 0.3);
            --shadow-glow: 0 0 20px rgba(99, 102, 241, 0.3);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-primary);
            line-height: 1.6;
            min-height: 100vh;
        }

        /* ============================================
           NAVBAR
           ============================================ */
        .navbar {
            background: rgba(30, 41, 59, 0.8);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 100;
            padding: 0 2rem;
        }

        .navbar-inner {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 70px;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: var(--text-primary);
        }

        .navbar-brand .logo {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: 800;
            color: white;
        }

        .navbar-brand span {
            font-size: 1.25rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary-light), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .navbar-info {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .navbar-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            background: rgba(99, 102, 241, 0.15);
            border: 1px solid rgba(99, 102, 241, 0.3);
            border-radius: 20px;
            font-size: 0.8rem;
            color: var(--primary-light);
            font-weight: 500;
        }

        .navbar-badge .dot {
            width: 8px;
            height: 8px;
            background: var(--success);
            border-radius: 50%;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(0.8); }
        }

        /* ============================================
           CONTAINER
           ============================================ */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* ============================================
           ALERTS
           ============================================ */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: var(--radius);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.9rem;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #6ee7b7;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
        }

        /* ============================================
           BUTTONS
           ============================================ */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border: none;
            border-radius: var(--radius-sm);
            font-family: 'Inter', sans-serif;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            box-shadow: 0 2px 10px rgba(99, 102, 241, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(99, 102, 241, 0.4);
        }

        .btn-secondary {
            background: var(--bg-card);
            color: var(--text-secondary);
            border: 1px solid var(--border);
        }

        .btn-secondary:hover {
            background: var(--bg-card-hover);
            color: var(--text-primary);
        }

        .btn-accent {
            background: linear-gradient(135deg, var(--accent), #d97706);
            color: white;
        }

        .btn-accent:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(245, 158, 11, 0.4);
        }

        .btn-sm {
            padding: 6px 14px;
            font-size: 0.8rem;
        }

        /* ============================================
           CARDS
           ============================================ */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
            border-color: var(--border-light);
        }

        .card-image {
            height: 200px;
            background: linear-gradient(135deg, var(--bg-surface), var(--bg-card));
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .card-image .motor-icon {
            font-size: 4rem;
            opacity: 0.6;
        }

        .card-image .badge-terlaris {
            position: absolute;
            top: 12px;
            right: 12px;
            background: linear-gradient(135deg, #ef4444, #f97316);
            color: white;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 4px;
            animation: badgePulse 2s ease-in-out infinite;
            box-shadow: 0 2px 10px rgba(239, 68, 68, 0.4);
        }

        @keyframes badgePulse {
            0%, 100% { box-shadow: 0 2px 10px rgba(239, 68, 68, 0.4); }
            50% { box-shadow: 0 2px 20px rgba(239, 68, 68, 0.6); }
        }

        .card-image .badge-merk {
            position: absolute;
            top: 12px;
            left: 12px;
            background: rgba(99, 102, 241, 0.2);
            border: 1px solid rgba(99, 102, 241, 0.4);
            color: var(--primary-light);
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .card-body {
            padding: 1.25rem;
        }

        .card-title {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }

        .card-title a {
            color: inherit;
            text-decoration: none;
            transition: color 0.2s;
        }

        .card-title a:hover {
            color: var(--primary-light);
        }

        .card-meta {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 0.75rem;
        }

        .card-tag {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 8px;
            background: rgba(100, 116, 139, 0.2);
            border-radius: 6px;
            font-size: 0.75rem;
            color: var(--text-secondary);
        }

        .card-price {
            font-size: 1.2rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--success), #34d399);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.75rem;
        }

        .card-stock {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 0.75rem;
            border-top: 1px solid var(--border);
        }

        .stock-indicator {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.8rem;
        }

        .stock-indicator .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .stock-indicator.available .dot { background: var(--success); }
        .stock-indicator.low .dot { background: var(--warning); }
        .stock-indicator.empty .dot { background: var(--danger); }

        .stock-indicator.available { color: #6ee7b7; }
        .stock-indicator.low { color: #fcd34d; }
        .stock-indicator.empty { color: #fca5a5; }

        /* ============================================
           GRID
           ============================================ */
        .motor-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 1.5rem;
        }

        /* ============================================
           HERO / PAGE HEADER
           ============================================ */
        .page-header {
            margin-bottom: 2rem;
        }

        .page-header h1 {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, var(--text-primary), var(--text-secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .page-header p {
            color: var(--text-secondary);
            font-size: 1rem;
        }

        /* ============================================
           FILTERS
           ============================================ */
        .filters {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            align-items: center;
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .filter-group label {
            font-size: 0.85rem;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .filter-select, .filter-input {
            padding: 8px 14px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            color: var(--text-primary);
            font-family: 'Inter', sans-serif;
            font-size: 0.85rem;
            outline: none;
            transition: border-color 0.2s;
        }

        .filter-select:focus, .filter-input:focus {
            border-color: var(--primary);
        }

        .filter-select option {
            background: var(--bg-card);
            color: var(--text-primary);
        }

        /* ============================================
           TOOLBAR
           ============================================ */
        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .stats-bar {
            display: flex;
            gap: 1.5rem;
        }

        .stat-item {
            display: flex;
            flex-direction: column;
            padding: 1rem 1.5rem;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            min-width: 140px;
        }

        .stat-item .stat-value {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary-light);
        }

        .stat-item .stat-label {
            font-size: 0.75rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* ============================================
           DETAIL PAGE
           ============================================ */
        .detail-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-top: 1rem;
        }

        .detail-image {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 6rem;
            position: relative;
        }

        .detail-info {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .detail-title {
            font-size: 2rem;
            font-weight: 800;
        }

        .detail-price {
            font-size: 2rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--success), #34d399);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .detail-specs {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .spec-item {
            padding: 1rem;
            background: var(--bg-surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
        }

        .spec-item .spec-label {
            font-size: 0.75rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .spec-item .spec-value {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .detail-desc {
            padding: 1.5rem;
            background: var(--bg-surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            color: var(--text-secondary);
            line-height: 1.8;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: color 0.2s;
        }

        .back-link:hover {
            color: var(--primary-light);
        }

        /* ============================================
           FOOTER
           ============================================ */
        .footer {
            text-align: center;
            padding: 2rem;
            margin-top: 3rem;
            border-top: 1px solid var(--border);
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        .footer .service-info {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 0.5rem;
            padding: 4px 12px;
            background: rgba(99, 102, 241, 0.1);
            border-radius: 20px;
            font-size: 0.75rem;
            color: var(--primary-light);
        }

        /* ============================================
           RESPONSIVE
           ============================================ */
        @media (max-width: 768px) {
            .container { padding: 1rem; }
            .motor-grid { grid-template-columns: 1fr; }
            .detail-container { grid-template-columns: 1fr; }
            .detail-specs { grid-template-columns: 1fr; }
            .navbar-inner { padding: 0; }
            .stats-bar { flex-direction: column; width: 100%; }
            .filters { flex-direction: column; }
            .toolbar { flex-direction: column; }
        }
    </style>
</head>
<body>
    <!-- NAVBAR -->
    <nav class="navbar">
        <div class="navbar-inner">
            <a href="{{ route('motors.index') }}" class="navbar-brand">
                <div class="logo">🏍️</div>
                <span>Katalog</span>
            </a>
            <div class="navbar-info">
                <div class="navbar-badge">
                    <div class="dot"></div>
                    Port 8001 · Layanan Inventaris
                </div>
            </div>
        </div>
    </nav>

    <!-- CONTENT -->
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                ❌ {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>

    <!-- FOOTER -->
    <footer class="footer">
        <p>Dealer Motor &copy; {{ date('Y') }} — Sistem Integrasi Antar Layanan</p>
        <div class="service-info">
            🔗 Katalog (Provider & Consumer) · REST API · Laravel
        </div>
    </footer>

    @yield('scripts')
</body>
</html>
