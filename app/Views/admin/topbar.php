<?php /** @var string $title */ ?>
<header class="flex items-center justify-between gap-4 border-b border-slate-200 bg-white px-4 py-4 shadow-soft sm:px-6 lg:px-8">
    <div class="flex items-center gap-3">
        <button type="button" class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-700 lg:hidden" data-admin-sidebar-open aria-label="Open admin menu">☰</button>
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-700">Administrator</p>
            <h1 class="text-xl font-semibold tracking-tight text-slate-950"><?= esc($title ?? 'Admin') ?></h1>
        </div>
    </div>
    <div class="relative">
        <button type="button" class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-3 py-2 text-left" data-admin-user-dropdown aria-expanded="false">
            <span class="grid h-9 w-9 place-items-center rounded-xl bg-slate-950 text-sm font-bold text-white"><?= esc(strtoupper(substr((string) session('full_name'), 0, 2))) ?></span>
            <span class="hidden sm:block">
                <span class="block text-sm font-semibold text-slate-950"><?= esc(session('full_name')) ?></span>
                <span class="block text-xs text-slate-500"><?= esc((string) session('role')) ?></span>
            </span>
        </button>
        <div class="absolute right-0 mt-2 hidden w-48 rounded-2xl border border-slate-200 bg-white p-2 shadow-soft" data-admin-dropdown>
            <a class="block rounded-xl px-3 py-2 text-sm text-slate-700 hover:bg-slate-50" href="<?= site_url('dashboard') ?>">View Website</a>
            <a class="block rounded-xl px-3 py-2 text-sm text-slate-700 hover:bg-slate-50" href="<?= site_url('admin/users') ?>">Administrators</a>
        </div>
    </div>
</header>
