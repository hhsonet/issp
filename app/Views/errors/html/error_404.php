<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Page not found | ISSP</title>
    <style>
        body{margin:0;min-height:100vh;display:grid;place-items:center;background:#f8fafc;color:#0f172a;font:16px/1.5 Inter,ui-sans-serif,system-ui,sans-serif}
        .card{width:min(520px,calc(100% - 2rem));background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:2rem;box-shadow:0 1px 2px rgba(15,23,42,.04);text-align:center}
        .badge{display:inline-flex;padding:.25rem .6rem;border-radius:999px;background:#f1f5f9;color:#334155;font-weight:700;font-size:.85rem}
        a{display:inline-flex;margin-top:1rem;color:#fff;background:#0f172a;padding:.75rem 1rem;border-radius:.75rem;text-decoration:none;font-weight:700}
    </style>
</head>
<body>
    <main class="card">
        <div class="badge">404</div>
        <h1>Page not found</h1>
        <p>The page you requested is unavailable. Please return to the ISSP portal and try again.</p>
        <a href="<?= site_url('/') ?>">Return home</a>
    </main>
</body>
</html>
