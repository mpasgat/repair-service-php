<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Repair Service</title>
    <style>
        :root {
            --bg: #f7f8fa;
            --card: #ffffff;
            --text: #1f2937;
            --muted: #6b7280;
            --primary: #0f766e;
            --danger: #b91c1c;
            --border: #d1d5db;
        }
        body {
            margin: 0;
            font-family: "Segoe UI", Tahoma, sans-serif;
            background: linear-gradient(180deg, #f0fdfa 0%, var(--bg) 220px);
            color: var(--text);
        }
        .container {
            max-width: 1080px;
            margin: 32px auto;
            padding: 0 16px;
        }
        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.04);
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
            gap: 12px;
        }
        h1, h2 {
            margin: 0;
            font-weight: 600;
        }
        form.inline {
            display: inline;
        }
        .grid {
            display: grid;
            gap: 12px;
        }
        .grid.two {
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        }
        input, select, textarea, button {
            width: 100%;
            box-sizing: border-box;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 14px;
            font-family: inherit;
        }
        textarea {
            min-height: 100px;
            resize: vertical;
        }
        button {
            cursor: pointer;
            background: var(--primary);
            color: #fff;
            border: none;
            font-weight: 600;
        }
        button.secondary {
            background: #334155;
        }
        button.danger {
            background: var(--danger);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }
        th, td {
            border-bottom: 1px solid var(--border);
            text-align: left;
            padding: 10px;
            vertical-align: top;
            font-size: 14px;
        }
        .status {
            font-size: 12px;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .flash {
            padding: 10px 12px;
            border-radius: 8px;
            margin-bottom: 12px;
        }
        .flash.success {
            background: #dcfce7;
            color: #14532d;
            border: 1px solid #86efac;
        }
        .flash.error {
            background: #fee2e2;
            color: #7f1d1d;
            border: 1px solid #fca5a5;
        }
        .actions {
            display: grid;
            gap: 8px;
            min-width: 180px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Заявки в ремонтную службу</h1>
        <div>
            <a href="{{ route('requests.create') }}">Создать заявку</a>
            @auth
                | <a href="{{ auth()->user()->role === 'dispatcher' ? route('dispatcher.index') : route('master.index') }}">Мой кабинет</a>
                <form class="inline" method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button style="width:auto;padding:8px 10px;display:inline-block;" class="secondary" type="submit">Выйти</button>
                </form>
            @else
                | <a href="{{ route('login') }}">Вход</a>
            @endauth
        </div>
    </div>

    @if (session('success'))
        <div class="flash success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="flash error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    @yield('content')
</div>
</body>
</html>
