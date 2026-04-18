<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        :root {
            color-scheme: light;
            --bg: #f3efe7;
            --panel: rgba(255, 255, 255, 0.8);
            --text: #18230f;
            --muted: #4f5d45;
            --line: rgba(24, 35, 15, 0.14);
            --accent: #355f2e;
            --accent-soft: #d8e6c8;
            --code: #13210f;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at top left, rgba(83, 141, 78, 0.18), transparent 32%),
                radial-gradient(circle at bottom right, rgba(237, 181, 63, 0.2), transparent 28%),
                linear-gradient(135deg, #f8f5ef 0%, #ecf3e2 100%);
            min-height: 100vh;
        }

        .layout {
            width: min(1120px, calc(100% - 32px));
            margin: 0 auto;
            padding: 40px 0 56px;
        }

        .hero {
            display: grid;
            grid-template-columns: 1.3fr 0.9fr;
            gap: 24px;
            align-items: stretch;
        }

        .card {
            background: var(--panel);
            backdrop-filter: blur(16px);
            border: 1px solid var(--line);
            border-radius: 28px;
            box-shadow: 0 18px 60px rgba(24, 35, 15, 0.08);
        }

        .hero-copy {
            padding: 36px;
        }

        .eyebrow {
            display: inline-flex;
            padding: 8px 14px;
            border-radius: 999px;
            background: var(--accent-soft);
            color: var(--accent);
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        h1 {
            margin: 18px 0 12px;
            font-size: clamp(36px, 5vw, 64px);
            line-height: 0.98;
        }

        .subtitle {
            margin: 0;
            max-width: 62ch;
            color: var(--muted);
            font-size: 18px;
            line-height: 1.7;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 28px;
        }

        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 18px;
            border-radius: 14px;
            text-decoration: none;
            font-weight: 700;
        }

        .button-primary {
            background: var(--accent);
            color: #fff;
        }

        .button-secondary {
            border: 1px solid var(--line);
            color: var(--text);
        }

        .hero-meta {
            padding: 28px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 18px;
        }

        .meta-title {
            margin: 0 0 8px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--muted);
        }

        .meta-grid {
            display: grid;
            gap: 12px;
        }

        .meta-item {
            padding: 14px 16px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.78);
            border: 1px solid rgba(24, 35, 15, 0.08);
        }

        .meta-item strong {
            display: block;
            margin-bottom: 4px;
            font-size: 13px;
            color: var(--muted);
        }

        .meta-item span {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            color: var(--code);
            word-break: break-word;
        }

        .section {
            margin-top: 24px;
            padding: 30px;
        }

        .section h2 {
            margin-top: 0;
            font-size: 24px;
        }

        .section p,
        .section li {
            color: var(--muted);
            line-height: 1.75;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
        }

        .step {
            padding: 20px;
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.7);
            border: 1px solid rgba(24, 35, 15, 0.08);
        }

        .step h3 {
            margin: 8px 0 12px;
            font-size: 18px;
        }

        @media (max-width: 900px) {
            .hero,
            .grid {
                grid-template-columns: 1fr;
            }

            .layout {
                width: min(100% - 24px, 1120px);
                padding-top: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="layout">
        <section class="hero">
            <article class="card hero-copy">
                <span class="eyebrow">Projek Cloud 1</span>
                <h1>{{ $title }}</h1>
                <p class="subtitle">
                    {{ $subtitle }}. Proyek ini disiapkan untuk belajar pengembangan lokal dengan container,
                    koneksi database MySQL, serta deployment aplikasi Laravel ke Render menggunakan alur yang rapi.
                </p>

                <div class="actions">
                    <a class="button button-primary" href="/health">Cek Health Endpoint</a>
                    <a class="button button-secondary" href="https://render.com/docs/cli" target="_blank" rel="noreferrer">Render CLI Docs</a>
                </div>
            </article>

            <aside class="card hero-meta">
                <div>
                    <p class="meta-title">Runtime checks</p>
                    <div class="meta-grid">
                        @foreach ($checks as $label => $value)
                            <div class="meta-item">
                                <strong>{{ $label }}</strong>
                                <span>{{ $value }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="meta-item">
                    <strong>Endpoint uji</strong>
                    <span>GET /health</span>
                </div>
            </aside>
        </section>

        <section class="card section">
            <h2>Yang sudah disiapkan</h2>
            <div class="grid">
                <article class="step">
                    <strong>01</strong>
                    <h3>Laravel app</h3>
                    <p>Aplikasi menampilkan landing page khusus untuk proyek praktikum, bukan halaman default Laravel.</p>
                </article>
                <article class="step">
                    <strong>02</strong>
                    <h3>Docker + MySQL</h3>
                    <p>Stack lokal akan menjalankan container aplikasi PHP dan MySQL untuk pengujian konsisten.</p>
                </article>
                <article class="step">
                    <strong>03</strong>
                    <h3>Render-ready</h3>
                    <p>Repositori akan berisi panduan dan konfigurasi agar lebih mudah dipakai dengan Docker dan Render.</p>
                </article>
            </div>
        </section>
    </div>
</body>
</html>
