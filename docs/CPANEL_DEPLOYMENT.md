# ISSP cPanel Production Deployment Guide

This guide prepares the existing CodeIgniter 4 ISSP project for secure production deployment on cPanel.

## 1) Required environment

- PHP 8.2 or 8.3
- Apache with `mod_rewrite`
- MySQL or MariaDB
- Composer access on the deployment machine or locally before upload
- Writable directories for cache, logs, sessions, and uploads

Recommended PHP extensions:

- `intl`
- `mbstring`
- `mysqli`
- `json`
- `curl`
- `openssl`
- `fileinfo`
- `gd` if image processing is used
- `zip` if archive handling is used

## 2) Recommended directory structure

Best option:

```text
/home/CPANEL_USER/issp-app/
├── app/
├── system/
├── vendor/
├── writable/
├── .env
├── composer.json
├── composer.lock
└── public/   ← web document root
```

Preferred document root:

```text
/home/CPANEL_USER/issp-app/public
```

If cPanel can point the subdirectory `/issp` to that document root, the public URL can remain:

```text
https://YOUR-DOMAIN.com/issp/
```

Do not expose `app`, `system`, `vendor`, `writable`, `.env`, or repository files to the web.

## 3) Asset strategy

This project should use the locally compiled assets already shipped under:

```text
public/build/assets/app.css
public/build/assets/app.js
```

The shared layouts are configured to load those local assets instead of the Tailwind CDN.

No Node.js runtime is required on cPanel.

If you need to rebuild assets locally, use the project’s own frontend workflow first, then upload only the generated files.

## 4) Composer installation

Install production dependencies without dev packages:

```bash
composer install --no-dev --prefer-dist --optimize-autoloader
```

Do not use `composer update` during deployment.

If cPanel has no Composer terminal access, build `vendor/` locally using the same PHP version and upload it.

## 5) `.env` setup

Copy `.env.production.example` to `.env` and fill in real values on the server.

Required values:

```ini
CI_ENVIRONMENT = production
app.baseURL = 'https://ssiicsetep.ugc.gov.bd/'
app.indexPage = ''
app.forceGlobalSecureRequests = true
database.default.hostname = localhost
database.default.database = CPANEL_DATABASE
database.default.username = CPANEL_DATABASE_USER
database.default.password = CHANGE_ME
database.default.DBDriver = MySQLi
database.default.port = 3306
cookie.secure = true
cookie.httponly = true
cookie.samesite = Lax
```

Also configure:

- email / SMTP settings
- OTP settings if enabled
- SMS gateway settings if enabled
- encryption key
- session settings if you override defaults

Never commit production credentials.

## 6) Database setup

1. Create the MySQL/MariaDB database in cPanel.
2. Create a database user.
3. Assign the user to the database with the needed privileges.
4. Import the existing database backup through phpMyAdmin or CLI.
5. Update `.env` with the live database credentials.
6. Check migration status:

```bash
php spark migrate:status
```

7. Run any pending migrations only after a verified backup:

```bash
php spark migrate --all
```

Do not run destructive seeders on production.

## 7) Apache and `.htaccess`

The app uses `public/.htaccess` for routing and index removal.

Verified behaviors expected in production:

- rewrite requests to `index.php`
- remove `index.php` from URLs
- work correctly in the `/issp` subdirectory
- preserve `Authorization` headers
- disable directory listing

The `www` redirect rule should preserve HTTPS.

## 8) File permissions

Suggested starting permissions:

- directories: `755`
- files: `644`
- `.env`: `600` or `640`
- writable directories: `775` only when required

Ensure these writable paths exist and are writable by PHP:

- `writable/cache`
- `writable/logs`
- `writable/session`
- `writable/uploads` if used

## 9) Git-based cPanel deployment

If you use cPanel Git Version Control, adapt `.cpanel.yml.example` to your account and deployment path.

Keep the live file out of Git if your hosting setup requires a different path.

Do not deploy:

- `.env`
- `.git`
- `node_modules`
- test files
- local database exports
- runtime cache/log/session data

## 10) Smoke-test checklist

After deployment, verify:

- landing page
- signup
- login
- dashboard
- profile
- application submission
- application detail pages
- admin dashboard
- admin applications listing
- edit-access toggle
- delete flow
- logout

Also check:

- CSRF failures show friendly errors
- login and signup routes remain public
- authenticated routes remain protected
- admin routes remain admin-only
- application-code URLs still work

## 11) Rollback procedure

1. Keep a backup of the prior release directory.
2. Keep a backup of the database before migrations.
3. Restore the previous code if deployment fails.
4. Restore the database only if a migration introduced an incompatibility.
5. Preserve `writable/` data and uploads.

Do not delete the production database.

## 12) Common cPanel issues

- Wrong document root: point the domain or subdomain to `public/`.
- Missing `mod_rewrite`: confirm Apache rewrite is enabled.
- Bad base URL: set `app.baseURL` in `.env`.
- Mixed content: ensure HTTPS is forced and URLs are generated with `site_url()` / `base_url()`.
- Permission errors: confirm writable directories are writable by PHP.

