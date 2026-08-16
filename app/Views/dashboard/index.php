<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="min-h-screen bg-slate-50 text-slate-900 lg:grid lg:grid-cols-[280px,1fr]">
    <div class="drawer-backdrop" data-drawer-backdrop></div>
    <?= $this->include('components/sidebar') ?>

    <main class="min-w-0 px-4 py-4 sm:px-6 lg:px-8">
        <?= $this->include('components/topbar', [
            'eyebrow' => 'Dashboard',
            'title' => 'Application portal',
            'description' => 'Track your ISSP profile status, application progress, and important documents.',
        ]) ?>

        <section class="grid gap-4 rounded-3xl border border-slate-200 bg-white p-5 shadow-soft lg:grid-cols-[1.4fr,.9fr]">
            <div class="space-y-4">
                <span class="inline-flex rounded-full bg-brand-50 px-3 py-1 text-xs font-semibold text-brand-700">Profile update recommended</span>
                <h2 class="text-3xl font-semibold tracking-tight sm:text-4xl">Welcome back, <?= esc(explode(' ', (string) session('full_name'))[0] ?? session('full_name')) ?></h2>
                <p class="max-w-2xl text-sm leading-6 text-slate-500">Finish your profile, start an application, and review the latest notices from the ISSP team.</p>
                <div class="flex flex-wrap gap-3">
                    <a class="rounded-2xl bg-brand-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-brand-700" href="<?= site_url('profile') ?>">Complete Profile</a>
                    <a class="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50" href="<?= site_url('applications') ?>">Start Application</a>
                </div>
            </div>
            <div class="grid gap-3">
                <?php foreach ([['Account status', 'Active'], ['Next step', 'Finish contact details'], ['Application call', 'Open now']] as [$label, $value]): ?>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <div class="text-xs font-semibold uppercase tracking-wide text-slate-500"><?= esc($label) ?></div>
                        <div class="mt-1 text-lg font-semibold"><?= esc($value) ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="mt-4 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <?php foreach ([
                ['Profile Completion', $summary['profile_completion'] . '%', 'Your profile is partially complete.', 'info'],
                ['Total Applications', $summary['total_applications'], 'All active records in your account.', 'primary'],
                ['Draft Applications', $summary['draft_applications'], 'Saved drafts awaiting review.', 'warning'],
                ['Submitted Applications', $summary['submitted_applications'], 'Completed submissions ready for processing.', 'success'],
            ] as [$label, $value, $note, $tone]): ?>
                <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-soft">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-100 text-lg font-bold text-slate-700"><?= esc($tone === 'primary' ? '◈' : ($tone === 'warning' ? '!' : '•')) ?></div>
                    <div class="mt-4 text-sm font-medium text-slate-500"><?= esc($label) ?></div>
                    <div class="mt-2 text-3xl font-semibold tracking-tight"><?= esc($value) ?></div>
                    <p class="mt-2 text-sm text-slate-500"><?= esc($note) ?></p>
                </article>
            <?php endforeach; ?>
        </section>

        <section class="mt-4 grid gap-4 xl:grid-cols-[1.35fr,.9fr]">
            <div class="space-y-4">
                <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-soft">
                    <?= view('components/section_heading', ['eyebrow' => 'Progress', 'title' => 'Profile completion', 'description' => 'Complete the core details before starting an application.']) ?>
                    <div class="mt-5 grid gap-4 lg:grid-cols-[180px,1fr] lg:items-center">
                        <div class="mx-auto grid h-44 w-44 place-items-center rounded-full bg-brand-50 text-center">
                            <div class="grid h-32 w-32 place-items-center rounded-full bg-white text-3xl font-bold text-brand-700 shadow-sm"><?= esc($summary['profile_completion']) ?>%</div>
                        </div>
                        <div>
                            <div class="h-3 overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full bg-brand-600" style="width:<?= esc((int) $summary['profile_completion']) ?>%"></div>
                            </div>
                            <ul class="mt-4 space-y-3 text-sm text-slate-600">
                                <?php foreach ($checklist as $item): ?>
                                    <li class="flex items-center gap-3">
                                        <span class="grid h-6 w-6 place-items-center rounded-full <?= $item['done'] ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' ?>"><?= $item['done'] ? '✓' : '○' ?></span>
                                        <span><?= esc($item['label']) ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <a class="mt-5 inline-flex rounded-2xl bg-brand-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-brand-700" href="<?= site_url('profile') ?>">Complete Profile</a>
                        </div>
                    </div>
                </article>

                <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-soft">
                    <?= view('components/section_heading', ['eyebrow' => 'Applications', 'title' => 'Recent applications', 'description' => 'Track submitted and draft records at a glance.']) ?>
                    <div class="mt-5 overflow-x-auto">
                        <table class="min-w-full border-separate border-spacing-0 text-left text-sm">
                            <thead>
                                <tr class="text-xs uppercase tracking-wide text-slate-500">
                                    <th class="border-b border-slate-200 px-3 py-3">Application ID</th>
                                    <th class="border-b border-slate-200 px-3 py-3">Project Title</th>
                                    <th class="border-b border-slate-200 px-3 py-3">Status</th>
                                    <th class="border-b border-slate-200 px-3 py-3">Last Updated</th>
                                    <th class="border-b border-slate-200 px-3 py-3">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="5" class="px-3 py-10">
                                        <?= view('components/empty_state', [
                                            'title' => 'You have not created an application yet.',
                                            'message' => 'Start a new application when you are ready to continue.',
                                            'actionUrl' => site_url('applications'),
                                            'actionLabel' => 'Create New Application',
                                        ]) ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </article>
            </div>

            <aside class="space-y-4">
                <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-soft" id="announcements">
                    <?= view('components/section_heading', ['eyebrow' => 'Updates', 'title' => 'Announcements', 'description' => 'Latest ISSP notices and reminders.']) ?>
                    <div class="mt-5 space-y-3">
                        <?php foreach ($announcements as $announcement): ?>
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <div class="flex items-start gap-3">
                                    <span class="mt-0.5 grid h-7 w-7 place-items-center rounded-full bg-brand-50 text-brand-700">i</span>
                                    <div>
                                        <strong class="block text-sm"><?= esc($announcement) ?></strong>
                                        <p class="mt-1 text-sm text-slate-500">Check the portal for any action required on your account.</p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </article>

                <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-soft" id="downloads">
                    <?= view('components/section_heading', ['eyebrow' => 'Resources', 'title' => 'Important downloads', 'description' => 'Frequently used project documents.']) ?>
                    <div class="mt-5 space-y-3">
                        <?php foreach ($downloads as $download): ?>
                            <div class="flex items-center justify-between gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <div>
                                    <strong class="block text-sm"><?= esc($download['title']) ?></strong>
                                    <span class="text-xs text-slate-500"><?= esc($download['available'] ? 'PDF file' : 'Unavailable') ?></span>
                                </div>
                                <?php if ($download['available']): ?>
                                    <a class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100" href="<?= esc($download['url']) ?>">Download</a>
                                <?php else: ?>
                                    <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">Coming Soon</span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </article>

                <article class="rounded-3xl border border-sky-100 bg-sky-50 p-5 shadow-soft" id="support">
                    <div class="flex items-start gap-4">
                        <div class="grid h-11 w-11 place-items-center rounded-2xl bg-white text-sky-700 shadow-sm">✉</div>
                        <div>
                            <h3 class="text-lg font-semibold">Need help with your account?</h3>
                            <p class="mt-2 text-sm text-sky-800/80">Reach the ISSP help desk for application guidance or technical support.</p>
                            <div class="mt-3 space-y-1 text-sm text-sky-800">
                                <div>support@issp.gov.bd</div>
                                <div>+880 2 0000 0000</div>
                            </div>
                            <a class="mt-4 inline-flex rounded-2xl bg-sky-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-sky-700" href="<?= site_url('/') ?>#contact">Contact Support</a>
                        </div>
                    </div>
                </article>
            </aside>
        </section>
    </main>
</div>
<?= $this->endSection() ?>
