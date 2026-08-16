<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<main class="min-h-screen bg-slate-50 text-slate-900">
    <header class="sticky top-0 z-40 border-b border-slate-200/80 bg-white/90 backdrop-blur">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
            <a class="flex items-center gap-3" href="<?= site_url('/') ?>"><span class="grid h-11 w-11 place-items-center rounded-2xl bg-slate-950 text-sm font-extrabold text-white">IS</span><span><span class="block text-base font-semibold tracking-tight">ISSP</span><span class="block text-xs text-slate-500">Internship portal</span></span></a>
            <div class="flex items-center gap-2">
                <a class="inline-flex items-center rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700" href="<?= site_url('apply') ?>">Apply</a>
                <form method="post" action="<?= site_url('logout') ?>"><?= csrf_field() ?><button class="inline-flex items-center rounded-full bg-brand-600 px-4 py-2 text-sm font-semibold text-white" type="submit">Logout</button></form>
            </div>
        </div>
    </header>
    <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-soft">
            <h1 class="text-3xl font-semibold tracking-tight text-slate-950">My Applications</h1>
            <p class="mt-2 text-sm text-slate-500">Applications are grouped by round.</p>
            <div class="mt-6 overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead><tr class="text-xs uppercase text-slate-500"><th class="py-3 pr-4">Round</th><th class="py-3 pr-4">Organization</th><th class="py-3 pr-4">Status</th><th class="py-3 pr-4">Submitted</th><th class="py-3 pr-4"></th></tr></thead>
                    <tbody>
                    <?php if (! $applications): ?>
                        <tr><td colspan="5" class="py-10"><?= view('components/empty_state', ['title' => 'No applications yet.', 'message' => 'Start a new application when an open round is available.', 'actionUrl' => site_url('apply'), 'actionLabel' => 'Apply Now']) ?></td></tr>
                    <?php else: foreach ($applications as $application): ?>
                        <tr class="border-t border-slate-200">
                            <td class="py-4 pr-4">Round <?= esc($application['round_number']) ?></td>
                            <td class="py-4 pr-4"><?= esc($application['placement_organization_name']) ?></td>
                            <td class="py-4 pr-4"><?= esc($application['status']) ?></td>
                            <td class="py-4 pr-4"><?= esc($application['submitted_at']) ?></td>
                            <td class="py-4 pr-4"><a class="font-semibold text-brand-700" href="<?= site_url('applications/' . $application['id']) ?>">View</a></td>
                        </tr>
                    <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
<?= $this->endSection() ?>
