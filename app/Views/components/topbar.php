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
        <button class="avatar avatar-button" type="button" data-user-dropdown aria-expanded="false" aria-label="User menu">
            <?= esc(strtoupper(substr((string) session('full_name'), 0, 2))) ?>
        </button>
        <div class="dropdown" data-dropdown hidden>
            <a href="<?= site_url('profile') ?>">Profile</a>
            <a href="<?= site_url('applications') ?>">Applications</a>
            <form method="post" action="<?= site_url('logout') ?>" class="px-2 pt-1">
                <?= csrf_field() ?>
                <button class="w-full rounded-lg px-3 py-2 text-left text-sm font-medium text-slate-700 hover:bg-slate-100" type="submit">Logout</button>
            </form>
        </div>
    </div>
</header>
