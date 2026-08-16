<?php /** @var string $title */ /** @var string $description */ ?>
<header class="topbar">
    <div class="topbar__start">
        <button class="icon-button mobile-only" type="button" data-sidebar-open aria-label="Open navigation">☰</button>
        <div>
            <div class="eyebrow"><?= esc($eyebrow ?? 'Workspace') ?></div>
            <h1><?= esc($title ?? '') ?></h1>
            <?php if (! empty($description)): ?><p><?= esc($description) ?></p><?php endif; ?>
        </div>
    </div>
    <div class="topbar__actions">
        <label class="search">
            <span class="search__icon" aria-hidden="true">⌕</span>
            <input type="search" placeholder="Search" aria-label="Search" disabled>
        </label>
        <button class="icon-button" type="button" aria-label="Notifications">🔔</button>
        <button class="avatar avatar-button" type="button" data-user-dropdown aria-expanded="false" aria-label="User menu">
            <?= esc(strtoupper(substr((string) session('full_name'), 0, 2))) ?>
        </button>
        <div class="dropdown" data-dropdown hidden>
            <a href="<?= site_url('profile') ?>">Profile</a>
            <a href="<?= site_url('applications') ?>">Applications</a>
        </div>
    </div>
</header>
