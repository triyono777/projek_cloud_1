<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Projek Cloud 1')</title>
    <style>
        :root {
            --bg: #f6f1e8;
            --panel: #fffaf1;
            --ink: #1d2418;
            --muted: #68705e;
            --line: rgba(29, 36, 24, 0.14);
            --accent: #31572c;
            --accent-2: #d9a441;
            --danger: #a33d2d;
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            color: var(--ink);
            font-family: "Avenir Next", "Trebuchet MS", sans-serif;
            background:
                radial-gradient(circle at 8% 12%, rgba(217, 164, 65, .25), transparent 28%),
                radial-gradient(circle at 88% 18%, rgba(49, 87, 44, .18), transparent 28%),
                linear-gradient(135deg, #fbf7ef, #eaf1de);
        }

        a { color: inherit; }
        .wrap { width: min(1120px, calc(100% - 32px)); margin: 0 auto; }
        .nav {
            padding: 22px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }
        .brand { font-weight: 900; text-decoration: none; letter-spacing: -.04em; font-size: 24px; }
        .nav-links { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
        .nav-links a, .link-button {
            border: 1px solid var(--line);
            border-radius: 999px;
            padding: 10px 14px;
            background: rgba(255, 250, 241, .75);
            text-decoration: none;
            color: var(--ink);
            font: inherit;
            cursor: pointer;
        }
        .hero, .panel {
            border: 1px solid var(--line);
            border-radius: 30px;
            background: rgba(255, 250, 241, .82);
            box-shadow: 0 20px 70px rgba(29, 36, 24, .08);
        }
        .hero { padding: clamp(28px, 5vw, 58px); margin: 18px 0 26px; }
        .eyebrow {
            color: var(--accent);
            font-size: 13px;
            font-weight: 900;
            letter-spacing: .12em;
            text-transform: uppercase;
        }
        h1 { font-size: clamp(38px, 6vw, 78px); line-height: .95; margin: 14px 0; letter-spacing: -.06em; }
        h2 { font-size: clamp(28px, 4vw, 44px); letter-spacing: -.04em; }
        h3 { margin-bottom: 8px; }
        p { color: var(--muted); line-height: 1.75; }
        .grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 18px; }
        .post-card { padding: 24px; }
        .post-card h2, .post-card h3 { margin-top: 0; }
        .meta { color: var(--muted); font-size: 14px; }
        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 16px;
            border-radius: 14px;
            border: 0;
            background: var(--accent);
            color: #fff;
            font-weight: 800;
            text-decoration: none;
            cursor: pointer;
        }
        .button-secondary { background: #eadfcb; color: var(--ink); }
        .button-danger { background: var(--danger); color: #fff; }
        .form { display: grid; gap: 16px; }
        label { display: grid; gap: 8px; font-weight: 800; }
        input, textarea {
            width: 100%;
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 14px 16px;
            background: rgba(255, 255, 255, .72);
            color: var(--ink);
            font: inherit;
        }
        textarea { min-height: 220px; resize: vertical; }
        .alert { padding: 14px 16px; border-radius: 16px; margin-bottom: 16px; }
        .alert-ok { background: #dfeccd; color: var(--accent); }
        .alert-error { background: #f4d6d0; color: var(--danger); }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { padding: 14px 10px; border-bottom: 1px solid var(--line); text-align: left; vertical-align: top; }
        .table-actions { display: flex; gap: 8px; flex-wrap: wrap; }
        .article { padding: clamp(24px, 5vw, 54px); }
        .article-body { white-space: pre-line; font-size: 18px; line-height: 1.85; color: var(--ink); }
        footer { padding: 36px 0; color: var(--muted); }

        @media (max-width: 760px) {
            .grid { grid-template-columns: 1fr; }
            .nav { align-items: flex-start; flex-direction: column; }
            .table { display: block; overflow-x: auto; }
        }
    </style>
</head>
<body>
    <nav class="wrap nav">
        <a class="brand" href="{{ route('home') }}">Projek Cloud 1</a>
        <div class="nav-links">
            <a href="{{ route('home') }}">Blog</a>
            @auth
                <a href="{{ route('dashboard.posts.index') }}">Dashboard</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="link-button" type="submit">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}">Login</a>
            @endauth
        </div>
    </nav>

    <main class="wrap">
        @yield('content')
    </main>

    <footer class="wrap">
        Projek Cloud 1 - Laravel, Docker, MySQL, Railway.
    </footer>
</body>
</html>
