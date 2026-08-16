<?php $currentPage = $page ?? 'dashboard'; ?>
<aside class="hidden w-72 shrink-0 border-r border-slate-200 bg-slate-950 text-white lg:flex lg:flex-col" data-admin-sidebar>
    <div class="border-b border-white/10 p-6">
        <a href="<?= site_url('admin/dashboard') ?>" class="flex items-center gap-3">
            <span class="grid h-11 w-11 place-items-center rounded-2xl bg-white text-slate-950 font-extrabold">IS</span>
            <div>
                <div class="text-base font-semibold">ISSP Admin</div>
                <div class="text-xs text-slate-300">Management console</div>
            </div>
        </a>
    </div>
    <nav class="flex-1 space-y-1 p-4 text-sm" aria-label="Admin navigation">
        <a class="block rounded-2xl px-4 py-3 <?= $currentPage === 'dashboard' ? 'bg-white text-slate-950' : 'text-slate-300 hover:bg-white/5 hover:text-white' ?>" href="<?= site_url('admin/dashboard') ?>">Dashboard</a>
        <a class="block rounded-2xl px-4 py-3 <?= in_array($currentPage, ['calls', 'calls-create']) ? 'bg-white text-slate-950' : 'text-slate-300 hover:bg-white/5 hover:text-white' ?>" href="<?= site_url('admin/calls') ?>">Application Calls</a>
        <a class="block rounded-2xl px-4 py-3 <?= $currentPage === 'applications' ? 'bg-white text-slate-950' : 'text-slate-300 hover:bg-white/5 hover:text-white' ?>" href="<?= site_url('admin/applications') ?>">Applications</a>
        <a class="block rounded-2xl px-4 py-3 <?= $currentPage === 'users' ? 'bg-white text-slate-950' : 'text-slate-300 hover:bg-white/5 hover:text-white' ?>" href="<?= site_url('admin/users') ?>">Users</a>
        <a class="block rounded-2xl px-4 py-3 text-slate-300 hover:bg-white/5 hover:text-white" href="<?= site_url('dashboard') ?>">View Website</a>
    </nav>
    <div class="border-t border-white/10 p-4">
        <div class="mb-3 rounded-2xl bg-white/5 p-4">
            <div class="text-sm font-semibold"><?= esc(session('full_name')) ?></div>
            <div class="text-xs text-slate-300"><?= esc((string) session('role')) ?></div>
        </div>
        <form method="post" action="<?= site_url('logout') ?>">
            <?= csrf_field() ?>
            <button type="submit" class="w-full rounded-2xl border border-white/10 px-4 py-3 text-sm font-semibold text-white hover:bg-white/10">Logout</button>
        </form>
    </div>
</aside>
