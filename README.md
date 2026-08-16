# ISSP — Institutional Support and Services Project

A beginner-friendly CodeIgniter 4 portal with a React, Vite, Tailwind CSS, shadcn-style component, and Lucide icon frontend. CI4 owns routing, views, CSRF, and server-side form validation; Vite compiles the UI into `public/build`.

## Requirements

- PHP 8.2+ with `intl`, `mbstring`, and `openssl`
- Composer 2
- Node.js 20+ and npm
- Apache with `mod_rewrite` (Laragon, XAMPP, or equivalent)

## Installation

```bash
composer install
copy env .env
cd frontend
npm install
npm run build
```

Set these values in `.env`:

```ini
CI_ENVIRONMENT = development
app.baseURL = 'http://localhost/issp/'
auth.demoEmail = 'applicant@example.org'
auth.demoPasswordHash = '$2y$...'
```

Generate the local demo password hash without committing the password:

```bash
php -r "echo password_hash('choose-a-password', PASSWORD_DEFAULT);"
```

The tracked `env` template contains no credentials and demo login remains disabled until both values are configured.

## Development workflow

```bash
php spark serve
cd frontend
npm run dev
```

For the normal integrated application, use the production frontend build. CI4 loads compiled files from `public/build`.

```bash
cd frontend
npm run typecheck
npm run build
cd ..
php spark routes
vendor/bin/phpunit
```

## Laragon / XAMPP / Apache

Place the repository in the web-root subfolder `issp`, such as `C:\laragon\www\issp`. Enable Apache `mod_rewrite` and set `AllowOverride All` for the web root. The root `.htaccess` routes application requests through `public/index.php`, while compiled assets are served from `public/build`.

Open [http://localhost/issp/](http://localhost/issp/). The health endpoint is `/issp/health`.

For production, point the virtual-host document root directly to `public` and update `app.baseURL`. Keep `.env` out of version control.

## Writable permissions

The web-server account needs write access to `writable/cache`, `writable/logs`, `writable/session`, and `writable/uploads`, but should not have unnecessary write access elsewhere.

## Documents and placeholders

Put published PDFs in `public/uploads/documents` using these names:

- `project-operations-manual.pdf`
- `application-format-annexes.pdf`
- `evaluation-guidelines.pdf`

Until a file exists, the UI displays **Coming Soon**. Replace the placeholder support email, office address, and organization label before launch. Password reset currently confirms a request without sending email; connect a mail provider when real accounts are implemented.

## Troubleshooting subdirectory 404 errors

- Confirm `app.baseURL` ends with `/issp/`.
- Confirm Apache `mod_rewrite` is enabled and `AllowOverride All` is set.
- Restart Apache after configuration changes.
- Verify both `.htaccess` files exist; file managers may hide dotfiles.
- Run `php spark routes` to confirm the requested route exists.
- If a virtual host already points to `public`, change `public/.htaccess` `RewriteBase` to `/` and use that host URL as `app.baseURL`.
