<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Заявки в ремонтную службу')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            * { box-sizing: border-box; }
            body { font-family: 'Instrument Sans', sans-serif; margin: 0; padding: 1rem; background: #f5f5f0; color: #1a1a1a; min-height: 100vh; }
            .container { max-width: 900px; margin: 0 auto; }
            nav { display: flex; gap: 1rem; align-items: center; flex-wrap: wrap; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid #ddd; }
            nav a { color: #2563eb; text-decoration: none; }
            nav a:hover { text-decoration: underline; }
            .btn { display: inline-block; padding: 0.5rem 1rem; background: #2563eb; color: #fff; border: none; border-radius: 6px; cursor: pointer; font: inherit; text-decoration: none; }
            .btn:hover { background: #1d4ed8; }
            .btn-sm { padding: 0.35rem 0.75rem; font-size: 0.875rem; }
            .btn-danger { background: #dc2626; }
            .btn-danger:hover { background: #b91c1c; }
            .btn-success { background: #16a34a; }
            .btn-success:hover { background: #15803d; }
            .alert { padding: 0.75rem 1rem; border-radius: 6px; margin-bottom: 1rem; }
            .alert-success { background: #dcfce7; color: #166534; }
            .alert-error { background: #fee2e2; color: #991b1b; }
            .form-group { margin-bottom: 1rem; }
            .form-group label { display: block; margin-bottom: 0.25rem; font-weight: 500; }
            .form-group input, .form-group textarea, .form-group select { width: 100%; max-width: 400px; padding: 0.5rem; border: 1px solid #ccc; border-radius: 6px; font: inherit; }
            table { width: 100%; border-collapse: collapse; }
            th, td { padding: 0.5rem 0.75rem; text-align: left; border-bottom: 1px solid #e5e5e5; }
            th { background: #f0f0f0; font-weight: 600; }
            .badge { display: inline-block; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.8rem; }
            .badge-new { background: #dbeafe; color: #1e40af; }
            .badge-assigned { background: #fef3c7; color: #92400e; }
            .badge-in_progress { background: #e0e7ff; color: #3730a3; }
            .badge-done { background: #d1fae5; color: #065f46; }
            .badge-canceled { background: #f3f4f6; color: #4b5563; }
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
                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-sm">Выйти ({{ auth()->user()->name }})</button>
                </form>
            @else
                <a href="{{ route('login') }}">Войти</a>
            @endauth
        </nav>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-error">
                <ul style="margin:0; padding-left:1.2rem;">
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
