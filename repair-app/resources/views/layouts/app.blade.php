<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Заявки в ремонтную службу')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            :root {
                --bg: #f0f4f8;
                --card-bg: #fff;
                --text: #1e293b;
                --text-muted: #64748b;
                --primary: #0f766e;
                --primary-hover: #0d9488;
                --danger: #dc2626;
                --danger-hover: #b91c1c;
                --success: #059669;
                --success-hover: #047857;
                --border: #e2e8f0;
                --radius: 10px;
                --shadow: 0 1px 3px rgba(0,0,0,.08);
                --shadow-lg: 0 4px 14px rgba(0,0,0,.08);
            }
            * { box-sizing: border-box; }
            body {
                font-family: 'Instrument Sans', sans-serif;
                margin: 0;
                padding: 0;
                background: var(--bg);
                color: var(--text);
                min-height: 100vh;
                line-height: 1.5;
            }
            .container { max-width: 1100px; margin: 0 auto; padding: 1.5rem; }
            .page-header { margin-bottom: 1.5rem; }
            .page-header h1 { margin: 0 0 0.25rem; font-size: 1.75rem; font-weight: 700; color: var(--text); }
            .page-header p { margin: 0; color: var(--text-muted); font-size: 0.95rem; }

            nav {
                display: flex;
                gap: 0.75rem;
                align-items: center;
                flex-wrap: wrap;
                margin-bottom: 1.5rem;
                padding: 1rem 1.25rem;
                background: var(--card-bg);
                border-radius: var(--radius);
                box-shadow: var(--shadow);
            }
            nav a {
                color: var(--primary);
                text-decoration: none;
                font-weight: 500;
                padding: 0.4rem 0.6rem;
                border-radius: 6px;
                transition: background .15s, color .15s;
            }
            nav a:hover { background: #ccfbf1; color: var(--primary-hover); }
            nav .nav-spacer { flex: 1; }
            nav form { margin: 0; }

            .btn {
                display: inline-block;
                padding: 0.5rem 1rem;
                background: var(--primary);
                color: #fff;
                border: none;
                border-radius: 8px;
                cursor: pointer;
                font: inherit;
                font-weight: 500;
                text-decoration: none;
                transition: background .15s, transform .1s;
            }
            .btn:hover { background: var(--primary-hover); }
            .btn:active { transform: scale(0.98); }
            .btn-sm { padding: 0.4rem 0.75rem; font-size: 0.875rem; }
            .btn-danger { background: var(--danger); }
            .btn-danger:hover { background: var(--danger-hover); }
            .btn-success { background: var(--success); }
            .btn-success:hover { background: var(--success-hover); }

            .alert { padding: 0.875rem 1.25rem; border-radius: var(--radius); margin-bottom: 1.25rem; }
            .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
            .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
            .alert-error ul { margin: 0; padding-left: 1.25rem; }

            .card {
                background: var(--card-bg);
                border-radius: var(--radius);
                box-shadow: var(--shadow);
                padding: 1.5rem;
                margin-bottom: 1.5rem;
            }

            .form-group { margin-bottom: 1.25rem; }
            .form-group label { display: block; margin-bottom: 0.35rem; font-weight: 500; color: var(--text); }
            .form-group input,
            .form-group textarea,
            .form-group select {
                width: 100%;
                max-width: 400px;
                padding: 0.6rem 0.75rem;
                border: 1px solid var(--border);
                border-radius: 8px;
                font: inherit;
                font-size: 1rem;
                transition: border-color .15s, box-shadow .15s;
            }
            .form-group input:focus,
            .form-group textarea:focus,
            .form-group select:focus {
                outline: none;
                border-color: var(--primary);
                box-shadow: 0 0 0 3px rgba(15, 118, 110, .15);
            }
            .form-group textarea { min-height: 100px; resize: vertical; }
            .form-row { display: flex; flex-wrap: wrap; gap: 0.5rem; align-items: center; }

            .table-wrap { overflow-x: auto; border-radius: var(--radius); box-shadow: var(--shadow); background: var(--card-bg); }
            table { width: 100%; border-collapse: collapse; }
            th, td { padding: 0.75rem 1rem; text-align: left; border-bottom: 1px solid var(--border); }
            th { background: #f8fafc; font-weight: 600; font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: .02em; }
            tr:last-child td { border-bottom: none; }
            tr:hover td { background: #fafafa; }
            .actions-cell { white-space: nowrap; }
            .actions-cell .form-inline { display: inline-flex; flex-wrap: wrap; gap: 0.5rem; align-items: center; margin: 0; }
            .actions-cell .form-inline + .form-inline { margin-left: 0; }
            .actions-cell select { width: auto; min-width: 140px; max-width: 180px; padding: 0.4rem 0.5rem; margin: 0; }

            .badge {
                display: inline-block;
                padding: 0.25rem 0.6rem;
                border-radius: 6px;
                font-size: 0.8rem;
                font-weight: 500;
            }
            .badge-new { background: #dbeafe; color: #1e40af; }
            .badge-assigned { background: #fef3c7; color: #92400e; }
            .badge-in_progress { background: #e0e7ff; color: #3730a3; }
            .badge-done { background: #d1fae5; color: #065f46; }
            .badge-canceled { background: #f1f5f9; color: #475569; }

            .filter-bar { display: flex; flex-wrap: wrap; gap: 0.5rem; align-items: center; margin-bottom: 1.25rem; }
            .filter-bar label { margin: 0; font-weight: 500; color: var(--text-muted); }
            .filter-bar select { width: auto; min-width: 160px; }

            .pagination { margin-top: 1.5rem; display: flex; justify-content: center; gap: 0.25rem; flex-wrap: wrap; }
            .pagination a, .pagination span { display: inline-block; padding: 0.5rem 0.75rem; border-radius: 6px; text-decoration: none; color: var(--text); background: var(--card-bg); border: 1px solid var(--border); font-size: 0.9rem; }
            .pagination a:hover { background: #f1f5f9; border-color: var(--primary); color: var(--primary); }
            .pagination .current span { background: var(--primary); color: #fff; border-color: var(--primary); }
            .pagination .disabled span { opacity: .6; cursor: default; }

            .hint { margin-top: 1.5rem; padding: 1rem; background: #f8fafc; border-radius: var(--radius); color: var(--text-muted); font-size: 0.9rem; }
            .hint code { background: #e2e8f0; padding: 0.15rem 0.4rem; border-radius: 4px; font-size: 0.85em; }
        </style>
    @endif
</head>
<body>
    <div class="container">
        <nav>
            <a href="{{ url('/') }}">Главная</a>
            <a href="{{ route('requests.create') }}">Создать заявку</a>
            @auth
                @if(auth()->user()->isDispatcher())
                    <a href="{{ route('dispatcher.index') }}">Панель диспетчера</a>
                @else
                    <a href="{{ route('master.index') }}">Панель мастера</a>
                @endif
                <span class="nav-spacer"></span>
                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-sm">Выйти ({{ auth()->user()->name }})</button>
                </form>
            @else
                <span class="nav-spacer"></span>
                <a href="{{ route('login') }}">Войти</a>
            @endauth
        </nav>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>
</body>
</html>
