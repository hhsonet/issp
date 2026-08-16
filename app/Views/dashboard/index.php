<?= $this->extend('layouts/authenticated') ?>
<?= $this->section('content') ?>
<?php $currentPage = 'dashboard'; ?>
<div class="min-h-screen">
    <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
        <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-soft">
            <div class="grid gap-4 lg:grid-cols-[1.4fr,.9fr]">
                <div class="space-y-4">
                    <span class="inline-flex rounded-full bg-brand-50 px-3 py-1 text-xs font-semibold text-brand-700">Profile update recommended</span>
                    <h2 class="text-3xl font-semibold tracking-tight sm:text-4xl">Welcome back, <?= esc(explode(' ', (string) session('full_name'))[0] ?? session('full_name')) ?></h2>
                    <p class="max-w-2xl text-sm leading-6 text-slate-500">Finish your profile, then apply when a new round opens.</p>
                    <div class="flex flex-wrap gap-3">
                        <a class="rounded-2xl bg-brand-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-brand-700" href="<?= site_url('profile') ?>">Complete Profile</a>
                        <a class="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50" href="<?= site_url('applications') ?>">My Applications</a>
                    </div>
                </div>
                <div class="grid gap-3">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">Account status</div>
                        <div class="mt-1 text-lg font-semibold">Active</div>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">Next step</div>
                        <div class="mt-1 text-lg font-semibold">Complete your profile</div>
                    </div>
                </div>
            </div>
        </section>

        <?php if (! empty($openCalls)): ?>
            <section class="mt-4 rounded-3xl border border-slate-200 bg-white p-5 shadow-soft">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-700">Open Calls for Application</p>
                        <h2 class="mt-2 text-2xl font-semibold tracking-tight text-slate-950">Apply to a current round</h2>
                    </div>
                </div>

                <div class="mt-5 grid gap-4">
                    <?php foreach ($openCalls as $call): ?>
                        <article class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <span class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">Accepting Applications</span>
                                    <h3 class="mt-3 text-xl font-semibold tracking-tight text-slate-950"><?= esc($call['title']) ?></h3>
                                </div>
                                <div class="rounded-2xl bg-white px-3 py-2 text-right text-xs font-semibold text-slate-600 shadow-sm">
                                    <?= esc($call['remaining_label']) ?>
                                </div>
                            </div>
                            <p class="mt-4 text-sm leading-6 text-slate-600"><?= esc($call['description']) ?></p>
                            <dl class="mt-4 grid gap-3 text-sm text-slate-600 sm:grid-cols-2">
                                <div>
                                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Opens</dt>
                                    <dd class="mt-1"><?= esc(date('M j, Y g:i A', strtotime((string) $call['opens_at']))) ?></dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Deadline</dt>
                                    <dd class="mt-1 font-semibold text-slate-900"><?= esc(date('M j, Y g:i A', strtotime((string) $call['closes_at']))) ?></dd>
                                </div>
                            </dl>
                            <div class="mt-5 flex flex-wrap items-center gap-3">
                                <a class="inline-flex rounded-2xl bg-brand-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-brand-700" href="<?= site_url('apply/' . $call['round_code']) ?>">Apply Now</a>
                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600"><?= esc($call['effective_status']) ?></span>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php else: ?>
            <div class="mt-4 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-500 shadow-soft">No new application calls are currently available.</div>
        <?php endif; ?>

        <section class="mt-4 rounded-3xl border border-slate-200 bg-white p-5 shadow-soft">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-700">My Applications</p>
                    <h2 class="mt-2 text-2xl font-semibold tracking-tight text-slate-950">Recent applications</h2>
                </div>
                <a class="inline-flex items-center rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50" href="<?= site_url('applications') ?>">View All Applications</a>
            </div>

            <?php if (! empty($applicationHistory)): ?>
                <div class="mt-5 hidden overflow-x-auto lg:block">
                    <table class="min-w-full text-left text-sm">
                        <thead>
                            <tr class="text-xs uppercase tracking-wide text-slate-500">
                                <th class="py-3 pr-4">Application</th>
                                <th class="py-3 pr-4">Title</th>
                                <th class="py-3 pr-4">Round</th>
                                <th class="py-3 pr-4">Type</th>
                                <th class="py-3 pr-4">University</th>
                                <th class="py-3 pr-4">Submitted</th>
                                <th class="py-3 pr-4">Status</th>
                                <th class="py-3 pr-4">Edit Access</th>
                                <th class="py-3 pr-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($applicationHistory as $application): ?>
                                <tr class="border-t border-slate-200">
                                    <td class="py-4 pr-4 font-medium text-brand-700"><?= esc($application['application_code'] ?? '') ?></td>
                                    <td class="py-4 pr-4"><?= esc($application['round_title'] ?? 'Not provided') ?></td>
                                    <td class="py-4 pr-4"><?= esc($application['round_code'] ?? 'Not provided') ?></td>
                                    <td class="py-4 pr-4"><?= esc($application['internship_type'] ?? 'Not provided') ?></td>
                                    <td class="py-4 pr-4"><?= esc($application['university_name'] ?? 'Not provided') ?></td>
                                    <td class="py-4 pr-4"><?= esc($application['submitted_at'] ?? 'Not provided') ?></td>
                                    <td class="py-4 pr-4">
                                        <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600"><?= esc($application['status'] ?? 'Not provided') ?></span>
                                    </td>
                                    <td class="py-4 pr-4">
                                        <?php if ((int) ($application['edit_enabled'] ?? 0) === 1): ?>
                                            <span class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">Editing Allowed</span>
                                        <?php else: ?>
                                            <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-500">Editing Locked</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-4 pr-4">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <?php if (! empty($application['application_code'])): ?>
                                                <a class="inline-flex rounded-2xl bg-brand-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-brand-700" href="<?= site_url('applications/' . $application['application_code']) ?>">View</a>
                                                <?php if ((int) ($application['edit_enabled'] ?? 0) === 1): ?>
                                                    <a class="inline-flex rounded-2xl border border-brand-200 bg-brand-50 px-3 py-2 text-xs font-semibold text-brand-700 transition hover:bg-brand-100" href="<?= site_url('applications/' . $application['application_code'] . '/edit') ?>">Edit</a>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-5 grid gap-4 lg:hidden">
                    <?php foreach ($applicationHistory as $application): ?>
                        <article class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <div class="text-sm font-semibold text-brand-700"><?= esc($application['application_code'] ?? '') ?></div>
                                    <h3 class="mt-1 text-lg font-semibold text-slate-950"><?= esc($application['round_title'] ?? 'Not provided') ?></h3>
                                </div>
                                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600"><?= esc($application['status'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="mt-4 grid gap-3 text-sm text-slate-600">
                                <div><span class="font-semibold text-slate-500">Round:</span> <?= esc($application['round_code'] ?? 'Not provided') ?></div>
                                <div><span class="font-semibold text-slate-500">Type:</span> <?= esc($application['internship_type'] ?? 'Not provided') ?></div>
                                <div><span class="font-semibold text-slate-500">University:</span> <?= esc($application['university_name'] ?? 'Not provided') ?></div>
                                <div><span class="font-semibold text-slate-500">Submitted:</span> <?= esc($application['submitted_at'] ?? 'Not provided') ?></div>
                                <div>
                                    <span class="font-semibold text-slate-500">Edit Access:</span>
                                    <?= ((int) ($application['edit_enabled'] ?? 0) === 1)
                                        ? '<span class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">Editing Allowed</span>'
                                        : '<span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-500">Editing Locked</span>' ?>
                                </div>
                            </div>
                            <div class="mt-5 flex flex-wrap gap-2">
                                <?php if (! empty($application['application_code'])): ?>
                                    <a class="inline-flex rounded-2xl bg-brand-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-brand-700" href="<?= site_url('applications/' . $application['application_code']) ?>">View</a>
                                    <?php if ((int) ($application['edit_enabled'] ?? 0) === 1): ?>
                                        <a class="inline-flex rounded-2xl border border-brand-200 bg-brand-50 px-4 py-2 text-sm font-semibold text-brand-700 transition hover:bg-brand-100" href="<?= site_url('applications/' . $application['application_code'] . '/edit') ?>">Edit</a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="mt-5 rounded-2xl border border-slate-200 bg-slate-50 p-5 text-sm text-slate-600">
                    <p>You have not submitted any applications yet.</p>
                    <?php if (! empty($openCalls)): ?>
                        <a class="mt-3 inline-flex rounded-2xl bg-brand-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-brand-700" href="<?= site_url('apply') ?>">View Open Calls</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </section>
    </div>
</div>
<?= $this->endSection() ?>
