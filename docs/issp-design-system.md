# ISSP Design System

This project’s UI is built from a small, portable design system that can be copied into another CodeIgniter 4 app or any Tailwind-based frontend.

## What to reuse

- `public/css/issp-design-system.css`
- The Tailwind token setup inside the layout head
- Shared partials:
  - `app/Views/components/topbar.php`
  - `app/Views/components/sidebar.php`
  - `app/Views/components/toasts.php`
  - `app/Views/components/empty_state.php`
  - `app/Views/components/form_errors.php`

## Visual language

- Background: soft slate
- Surfaces: white cards with subtle borders
- Primary action: blue brand button
- Secondary action: outline button
- Status: neutral, success, warning, danger, info badges
- Typography: Inter, compact spacing, restrained shadows

## Copy into another project

1. Copy `public/css/issp-design-system.css` into the new project.
2. Load it from your layout:

```php
<link rel="stylesheet" href="<?= base_url('assets/css/issp-design-system.css') ?>">
```

3. Reuse the class names:

- `.issp-card`
- `.issp-btn`
- `.issp-btn-primary`
- `.issp-btn-outline`
- `.issp-field`
- `.issp-badge`
- `.issp-table`
- `.issp-toast`

4. If desired, copy the shared partials and adapt the labels/content.

## Recommended CI4 page structure

```php
<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<main class="issp-shell">
    <section class="issp-card issp-container">
        ...
    </section>
</main>
<?= $this->endSection() ?>
```

## Notes

- The stylesheet is intentionally framework-light so it can be used in cPanel-friendly CI4 apps.
- It is safe to pair with Tailwind classes when you want finer layout control.
