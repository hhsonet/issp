<?php $currentPage = $currentPage ?? ($page ?? 'dashboard'); ?>
<aside class="sidebar" data-sidebar aria-hidden="true">
    <div class="sidebar__brand">
        <div class="logo-mark logo-mark--sm">IS</div>
        <div>
            <div class="sidebar__title">ISSP Portal</div>
            <div class="sidebar__subtitle">Institutional workspace</div>
        </div>
        <button class="ml-auto inline-grid h-9 w-9 place-items-center rounded-xl border border-white/10 bg-white/5 text-white lg:hidden" type="button" data-sidebar-close aria-label="Close navigation">✕</button>
    </div>

    <div class="sidebar__section">Workspace</div>
    <nav class="nav-group" aria-label="Primary navigation">
        <a class="nav-link <?= $currentPage === 'dashboard' ? 'is-active' : '' ?>" href="<?= site_url('dashboard') ?>" <?= $currentPage === 'dashboard' ? 'aria-current="page"' : '' ?>>Dashboard</a>
        <a class="nav-link <?= $currentPage === 'profile' ? 'is-active' : '' ?>" href="<?= site_url('profile') ?>" <?= $currentPage === 'profile' ? 'aria-current="page"' : '' ?>>My Profile</a>
        <a class="nav-link <?= $currentPage === 'applications' ? 'is-active' : '' ?>" href="<?= site_url('applications') ?>" <?= $currentPage === 'applications' ? 'aria-current="page"' : '' ?>>My Applications</a>
        <a class="nav-link" href="#downloads">Documents</a>
        <a class="nav-link" href="#announcements">Notifications</a>
        <a class="nav-link" href="<?= site_url('/') ?>#support">Help and Support</a>
    </nav>

    <div class="sidebar__footer">
        <div class="user-summary">
            <div class="avatar" aria-hidden="true"><?= esc(strtoupper(substr((string) session('full_name'), 0, 2))) ?></div>
            <div>
                <div class="user-summary__name"><?= esc(session('full_name')) ?></div>
                <div class="user-summary__meta"><?= esc(session('email')) ?></div>
            </div>
        </div>
        <form method="post" action="<?= site_url('logout') ?>">
            <?= csrf_field() ?>
            <button class="btn btn-secondary btn-block" type="submit">Logout</button>
        </form>
    </div>
</aside>
