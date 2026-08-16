<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?php
$application = $application ?? [];
$roundCode = (string) ($application['round_code'] ?? '');
$creditCompletion = number_format((float) ($application['credit_completion_percentage'] ?? 0), 2, '.', '');
$showDisability = (($application['disability_status'] ?? '') === 'Yes');
$showEthnic = (($application['ethnic_minority_status'] ?? '') === 'Yes');
?>
<main class="min-h-screen bg-slate-50 text-slate-900">
    <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-sm font-medium text-brand-600">Application details</p>
                <h1 class="mt-1 text-3xl font-semibold tracking-tight text-slate-950"><?= esc($application['round_title'] ?? 'Application') ?></h1>
                <p class="mt-2 text-sm text-slate-500">Review the submitted information for this application round.</p>
            </div>
            <a href="<?= site_url('applications') ?>" class="inline-flex items-center rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">Back to applications</a>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 space-y-6">
                <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-soft">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <h2 class="text-xl font-semibold text-slate-950">Submission overview</h2>
                            <p class="mt-1 text-sm text-slate-500">Core details recorded with this application.</p>
                        </div>
                        <?php if (! empty($application['status'])): ?>
                            <span class="inline-flex items-center rounded-full bg-brand-50 px-3 py-1 text-xs font-semibold text-brand-700"><?= esc($application['status']) ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="mt-6 grid gap-4 sm:grid-cols-2">
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <div class="text-xs font-medium uppercase tracking-wide text-slate-500">Round code</div>
                            <div class="mt-1 text-sm font-semibold text-slate-950"><?= esc($roundCode) ?></div>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <div class="text-xs font-medium uppercase tracking-wide text-slate-500">Student name</div>
                            <div class="mt-1 text-sm font-semibold text-slate-950"><?= esc($application['full_name'] ?? '') ?></div>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <div class="text-xs font-medium uppercase tracking-wide text-slate-500">Student ID</div>
                            <div class="mt-1 text-sm font-semibold text-slate-950"><?= esc($application['student_id'] ?? '') ?></div>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <div class="text-xs font-medium uppercase tracking-wide text-slate-500">Submitted at</div>
                            <div class="mt-1 text-sm font-semibold text-slate-950"><?= esc($application['submitted_at'] ?? '') ?></div>
                        </div>
                    </div>
                </section>

                <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-soft">
                    <h2 class="text-xl font-semibold text-slate-950">Academic eligibility</h2>
                    <p class="mt-1 text-sm text-slate-500">Eligibility was verified before submission.</p>
                    <div class="mt-6 grid gap-4 sm:grid-cols-3">
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <div class="text-xs uppercase tracking-wide text-slate-500">Current CGPA</div>
                            <div class="mt-1 text-sm font-semibold text-slate-950"><?= esc((string) ($application['current_cgpa'] ?? '')) ?></div>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <div class="text-xs uppercase tracking-wide text-slate-500">Total credits</div>
                            <div class="mt-1 text-sm font-semibold text-slate-950"><?= esc((string) ($application['total_credits'] ?? '')) ?></div>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <div class="text-xs uppercase tracking-wide text-slate-500">Earned credits</div>
                            <div class="mt-1 text-sm font-semibold text-slate-950"><?= esc((string) ($application['earned_credits'] ?? '')) ?></div>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 sm:col-span-2">
                            <div class="text-xs uppercase tracking-wide text-slate-500">Credit completed</div>
                            <div class="mt-1 text-sm font-semibold text-slate-950"><?= esc($creditCompletion) ?>%</div>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <div class="text-xs uppercase tracking-wide text-slate-500">Declaration</div>
                            <div class="mt-1 text-sm font-semibold text-slate-950"><?= ((int) ($application['information_declaration'] ?? 0) === 1) ? 'Accepted' : 'Not accepted' ?></div>
                        </div>
                    </div>
                    <?php if (! empty($application['declared_at'])): ?>
                        <p class="mt-4 text-sm text-slate-500">Declared at <?= esc($application['declared_at']) ?></p>
                    <?php endif; ?>
                </section>

                <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-soft">
                    <h2 class="text-xl font-semibold text-slate-950">Profile summary</h2>
                    <p class="mt-1 text-sm text-slate-500">The profile information attached to this application.</p>
                    <div class="mt-6 grid gap-4 sm:grid-cols-2">
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <div class="text-xs uppercase tracking-wide text-slate-500">Gender identity</div>
                            <div class="mt-1 text-sm font-semibold text-slate-950"><?= esc((string) ($application['profile_gender_identity'] ?? $application['gender_identity'] ?? '')) ?></div>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <div class="text-xs uppercase tracking-wide text-slate-500">Disability status</div>
                            <div class="mt-1 text-sm font-semibold text-slate-950"><?= esc((string) ($application['disability_status'] ?? '')) ?></div>
                        </div>
                        <?php if ($showDisability): ?>
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 sm:col-span-2">
                                <div class="text-xs uppercase tracking-wide text-slate-500">Please specify the type of disability</div>
                                <div class="mt-1 text-sm font-semibold text-slate-950"><?= esc((string) ($application['disability_type'] ?? '')) ?></div>
                            </div>
                        <?php endif; ?>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <div class="text-xs uppercase tracking-wide text-slate-500">Ethnic minority status</div>
                            <div class="mt-1 text-sm font-semibold text-slate-950"><?= esc((string) ($application['ethnic_minority_status'] ?? '')) ?></div>
                        </div>
                        <?php if ($showEthnic): ?>
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 sm:col-span-2">
                                <div class="text-xs uppercase tracking-wide text-slate-500">Community / group name</div>
                                <div class="mt-1 text-sm font-semibold text-slate-950"><?= esc((string) ($application['ethnic_group_name'] ?? '')) ?></div>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>
            </div>

            <aside class="space-y-6">
                <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-soft">
                    <h2 class="text-lg font-semibold text-slate-950">Placement details</h2>
                    <div class="mt-4 space-y-3 text-sm text-slate-700">
                        <div><span class="font-medium text-slate-500">University:</span> <?= esc($application['supervisor_university'] ?? '') ?></div>
                        <div><span class="font-medium text-slate-500">Department:</span> <?= esc($application['supervisor_department'] ?? '') ?></div>
                        <div><span class="font-medium text-slate-500">Organization:</span> <?= esc($application['placement_organization_name'] ?? '') ?></div>
                        <div><span class="font-medium text-slate-500">Supervisor:</span> <?= esc($application['supervisor_name'] ?? '') ?></div>
                    </div>
                </section>

                <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-soft">
                    <h2 class="text-lg font-semibold text-slate-950">Application contact</h2>
                    <div class="mt-4 space-y-3 text-sm text-slate-700">
                        <div><span class="font-medium text-slate-500">Supervisor email:</span> <?= esc($application['supervisor_email'] ?? '') ?></div>
                        <div><span class="font-medium text-slate-500">Supervisor phone:</span> <?= esc($application['supervisor_phone'] ?? '') ?></div>
                        <div><span class="font-medium text-slate-500">Mentor:</span> <?= esc($application['mentor_name'] ?? '') ?></div>
                        <div><span class="font-medium text-slate-500">Mentor email:</span> <?= esc($application['mentor_email'] ?? '') ?></div>
                    </div>
                </section>
            </aside>
        </div>
    </div>
</main>
<?= $this->endSection() ?>
