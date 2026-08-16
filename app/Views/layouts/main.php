<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Institutional Support and Services Project portal">
    <title><?= esc($title ?? 'ISSP') ?> | ISSP</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#2563eb',
                            600: '#1d4ed8',
                            700: '#1e40af',
                        },
                    },
                    boxShadow: {
                        soft: '0 12px 30px rgba(15, 23, 42, 0.08)',
                    },
                    borderRadius: {
                        xl2: '1rem',
                    },
                },
            },
        };
    </script>
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
</head>
<body class="app-shell">
    <?= $this->include('components/toasts') ?>
    <?= $this->renderSection('content') ?>
    <script src="<?= base_url('assets/js/app.js') ?>" defer></script>
</body>
</html>
