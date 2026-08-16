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
    </div>
</div>
<?= $this->endSection() ?>
