<?= $this->extend('layouts/authenticated') ?>
<?= $this->section('content') ?>
<?php
$application = $application ?? [];
$currentPage = 'applications';
$eligibilityStatus = ((float) ($application['current_cgpa'] ?? 0) >= 2.75 && (float) ($application['total_credits'] ?? 0) > 0 && (float) ($application['earned_credits'] ?? 0) <= (float) ($application['total_credits'] ?? 0) && ((float) ($application['earned_credits'] ?? 0) * 100 >= (float) ($application['total_credits'] ?? 0) * 75))
    ? 'Eligible'
    : 'Not eligible';
$creditCompletion = ((float) ($application['total_credits'] ?? 0) > 0)
    ? number_format(((float) ($application['earned_credits'] ?? 0) / (float) ($application['total_credits'] ?? 0)) * 100, 2, '.', '')
    : '0.00';
$hasDisability = (($application['disability_status'] ?? '') === 'Yes');
$hasEthnic = (($application['ethnic_minority_status'] ?? '') === 'Yes');
$website = trim((string) ($application['organization_website_url'] ?? ''));
?>
<div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
    <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-soft">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-700">Dashboard / My Applications / <?= esc((string) ($application['application_code'] ?? '')) ?></p>
        <h1 class="mt-2 text-3xl font-semibold tracking-tight text-slate-950">Application Details</h1>
        <p class="mt-2 text-sm text-slate-500">
            <?= esc((string) ($application['round_title'] ?? 'Application')) ?>
            <?php if (! empty($application['round_code'])): ?>
                <span class="text-slate-400">·</span> Round <?= esc((string) $application['round_code']) ?>
            <?php endif; ?>
        </p>
    </section>

    <div class="mt-4 grid gap-6 xl:grid-cols-[minmax(0,1fr)_320px]">
        <div class="grid gap-6">
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-soft">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-semibold text-slate-950">Application summary</h2>
                        <p class="mt-1 text-sm text-slate-500">Read-only submission details.</p>
                    </div>
                    <span class="inline-flex rounded-full bg-brand-50 px-3 py-1 text-xs font-semibold text-brand-700"><?= esc((string) ($application['status'] ?? '')) ?></span>
                </div>
                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4"><div class="text-xs uppercase tracking-wide text-slate-500">Application code</div><div class="mt-1 font-semibold text-slate-950"><?= esc((string) ($application['application_code'] ?? '')) ?></div></div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4"><div class="text-xs uppercase tracking-wide text-slate-500">Application title</div><div class="mt-1 font-semibold text-slate-950"><?= esc((string) ($application['round_title'] ?? '')) ?></div></div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4"><div class="text-xs uppercase tracking-wide text-slate-500">Round code</div><div class="mt-1 font-semibold text-slate-950"><?= esc((string) ($application['round_code'] ?? '')) ?></div></div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4"><div class="text-xs uppercase tracking-wide text-slate-500">Edit access</div><div class="mt-1 font-semibold text-slate-950"><?= ((int) ($application['edit_enabled'] ?? 0) === 1) ? 'Editing Allowed' : 'Editing Locked' ?></div></div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4"><div class="text-xs uppercase tracking-wide text-slate-500">Submission date</div><div class="mt-1 font-semibold text-slate-950"><?= esc((string) ($application['submitted_at'] ?? 'Not provided')) ?></div></div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4"><div class="text-xs uppercase tracking-wide text-slate-500">Last updated</div><div class="mt-1 font-semibold text-slate-950"><?= esc((string) ($application['updated_at'] ?? 'Not provided')) ?></div></div>
                </div>
            </section>

            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-soft">
                <h2 class="text-xl font-semibold text-slate-950">Student information</h2>
                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4"><div class="text-xs uppercase tracking-wide text-slate-500">Full name</div><div class="mt-1 font-semibold text-slate-950"><?= esc((string) ($application['full_name'] ?? 'Not provided')) ?></div></div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4"><div class="text-xs uppercase tracking-wide text-slate-500">Gender identity</div><div class="mt-1 font-semibold text-slate-950"><?= esc((string) ($application['profile_gender_identity'] ?? $application['gender_identity'] ?? 'Not provided')) ?></div></div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4"><div class="text-xs uppercase tracking-wide text-slate-500">Student ID</div><div class="mt-1 font-semibold text-slate-950"><?= esc((string) ($application['student_id'] ?? 'Not provided')) ?></div></div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4"><div class="text-xs uppercase tracking-wide text-slate-500">University</div><div class="mt-1 font-semibold text-slate-950"><?= esc((string) ($application['university_name'] ?? 'Not provided')) ?><?= ! empty($application['university_type']) ? ' (' . esc((string) $application['university_type']) . ')' : '' ?></div></div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4"><div class="text-xs uppercase tracking-wide text-slate-500">Department</div><div class="mt-1 font-semibold text-slate-950"><?= esc((string) ($application['department'] ?? 'Not provided')) ?></div></div>
                    <?php if (($application['department'] ?? '') === 'Other'): ?>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4"><div class="text-xs uppercase tracking-wide text-slate-500">Custom department</div><div class="mt-1 font-semibold text-slate-950"><?= esc((string) ($application['other_department'] ?? 'Not provided')) ?></div></div>
                    <?php endif; ?>
                </div>
            </section>

            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-soft">
                <h2 class="text-xl font-semibold text-slate-950">Academic information</h2>
                <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4"><div class="text-xs uppercase tracking-wide text-slate-500">Current CGPA</div><div class="mt-1 font-semibold text-slate-950"><?= number_format((float) ($application['current_cgpa'] ?? 0), 2, '.', '') ?></div></div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4"><div class="text-xs uppercase tracking-wide text-slate-500">Total credits</div><div class="mt-1 font-semibold text-slate-950"><?= number_format((float) ($application['total_credits'] ?? 0), 2, '.', '') ?></div></div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4"><div class="text-xs uppercase tracking-wide text-slate-500">Earned credits</div><div class="mt-1 font-semibold text-slate-950"><?= number_format((float) ($application['earned_credits'] ?? 0), 2, '.', '') ?></div></div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4"><div class="text-xs uppercase tracking-wide text-slate-500">Credit completed</div><div class="mt-1 font-semibold text-slate-950"><?= esc($creditCompletion) ?>%</div></div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 sm:col-span-2"><div class="text-xs uppercase tracking-wide text-slate-500">Eligibility status</div><div class="mt-1 font-semibold text-slate-950"><?= esc($eligibilityStatus) ?></div></div>
                </div>
            </section>

            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-soft">
                <h2 class="text-xl font-semibold text-slate-950">Internship information</h2>
                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4"><div class="text-xs uppercase tracking-wide text-slate-500">Internship type</div><div class="mt-1 font-semibold text-slate-950"><?= esc((string) ($application['internship_type'] ?? 'Not provided')) ?></div></div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4"><div class="text-xs uppercase tracking-wide text-slate-500">Start date</div><div class="mt-1 font-semibold text-slate-950"><?= esc((string) ($application['internship_start_date'] ?? 'Not provided')) ?></div></div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4"><div class="text-xs uppercase tracking-wide text-slate-500">End date</div><div class="mt-1 font-semibold text-slate-950"><?= esc((string) ($application['internship_end_date'] ?? 'Not provided')) ?></div></div>
                    <?php if ((string) ($application['internship_type'] ?? '') === 'Capstone'): ?>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4"><div class="text-xs uppercase tracking-wide text-slate-500">Team members</div><div class="mt-1 font-semibold text-slate-950"><?= esc((string) ($application['team_member_count'] ?? 'Not provided')) ?></div></div>
                    <?php endif; ?>
                </div>
            </section>

            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-soft">
                <h2 class="text-xl font-semibold text-slate-950">Supervisor information</h2>
                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4"><div class="text-xs uppercase tracking-wide text-slate-500">Supervisor name</div><div class="mt-1 font-semibold text-slate-950"><?= esc((string) ($application['supervisor_name'] ?? 'Not provided')) ?></div></div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4"><div class="text-xs uppercase tracking-wide text-slate-500">Email</div><div class="mt-1 font-semibold text-slate-950"><?= esc((string) ($application['supervisor_email'] ?? 'Not provided')) ?></div></div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4"><div class="text-xs uppercase tracking-wide text-slate-500">University</div><div class="mt-1 font-semibold text-slate-950"><?= esc((string) ($application['supervisor_university'] ?? 'Not provided')) ?></div></div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4"><div class="text-xs uppercase tracking-wide text-slate-500">Department</div><div class="mt-1 font-semibold text-slate-950"><?= esc((string) ($application['supervisor_department'] ?? 'Not provided')) ?></div></div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4"><div class="text-xs uppercase tracking-wide text-slate-500">Designation</div><div class="mt-1 font-semibold text-slate-950"><?= esc((string) ($application['supervisor_designation'] ?? 'Not provided')) ?></div></div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4"><div class="text-xs uppercase tracking-wide text-slate-500">Phone number</div><div class="mt-1 font-semibold text-slate-950"><?= esc((string) ($application['supervisor_phone'] ?? 'Not provided')) ?></div></div>
                </div>
            </section>

            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-soft">
                <h2 class="text-xl font-semibold text-slate-950">Placement organization</h2>
                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4"><div class="text-xs uppercase tracking-wide text-slate-500">Organization name</div><div class="mt-1 font-semibold text-slate-950"><?= esc((string) ($application['placement_organization_name'] ?? 'Not provided')) ?></div></div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <div class="text-xs uppercase tracking-wide text-slate-500">Website URL</div>
                        <div class="mt-1 font-semibold text-slate-950">
                            <?php if ($website !== '' && filter_var($website, FILTER_VALIDATE_URL) && in_array(strtolower((string) parse_url($website, PHP_URL_SCHEME)), ['http', 'https'], true)): ?>
                                <a class="text-brand-700 underline underline-offset-2" href="<?= esc($website) ?>" target="_blank" rel="noopener noreferrer"><?= esc($website) ?></a>
                            <?php else: ?>
                                Not provided
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </section>

            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-soft">
                <h2 class="text-xl font-semibold text-slate-950">Mentor information</h2>
                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4"><div class="text-xs uppercase tracking-wide text-slate-500">Mentor name</div><div class="mt-1 font-semibold text-slate-950"><?= esc((string) ($application['mentor_name'] ?? 'Not provided')) ?></div></div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4"><div class="text-xs uppercase tracking-wide text-slate-500">Mentor email</div><div class="mt-1 font-semibold text-slate-950"><?= esc((string) ($application['mentor_email'] ?? 'Not provided')) ?></div></div>
                </div>
            </section>

            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-soft">
                <h2 class="text-xl font-semibold text-slate-950">Demographic information</h2>
                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4"><div class="text-xs uppercase tracking-wide text-slate-500">Disability status</div><div class="mt-1 font-semibold text-slate-950"><?= esc((string) ($application['disability_status'] ?? 'Not provided')) ?></div></div>
                    <?php if ($hasDisability): ?>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4"><div class="text-xs uppercase tracking-wide text-slate-500">Disability type</div><div class="mt-1 font-semibold text-slate-950"><?= esc((string) ($application['disability_type'] ?? 'Not provided')) ?></div></div>
                    <?php endif; ?>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4"><div class="text-xs uppercase tracking-wide text-slate-500">Ethnic-minority status</div><div class="mt-1 font-semibold text-slate-950"><?= esc((string) ($application['ethnic_minority_status'] ?? 'Not provided')) ?></div></div>
                    <?php if ($hasEthnic): ?>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4"><div class="text-xs uppercase tracking-wide text-slate-500">Community / group name</div><div class="mt-1 font-semibold text-slate-950"><?= esc((string) ($application['ethnic_group_name'] ?? 'Not provided')) ?></div></div>
                    <?php endif; ?>
                </div>
            </section>

            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-soft">
                <h2 class="text-xl font-semibold text-slate-950">Declaration</h2>
                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4"><div class="text-xs uppercase tracking-wide text-slate-500">Information declaration</div><div class="mt-1 font-semibold text-slate-950"><?= ((int) ($application['information_declaration'] ?? 0) === 1) ? 'Accepted' : 'Not accepted' ?></div></div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4"><div class="text-xs uppercase tracking-wide text-slate-500">Declaration date and time</div><div class="mt-1 font-semibold text-slate-950"><?= esc((string) ($application['declared_at'] ?? 'Not provided')) ?></div></div>
                </div>
            </section>
        </div>

        <aside class="grid gap-4 self-start">
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-soft">
                <h2 class="text-lg font-semibold text-slate-950">Actions</h2>
                <div class="mt-4 grid gap-3">
                    <a class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50" href="<?= site_url('applications') ?>">Back to My Applications</a>
                    <?php if ((int) ($application['edit_enabled'] ?? 0) === 1): ?>
                        <a class="inline-flex items-center justify-center rounded-2xl bg-brand-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-brand-700" href="<?= site_url('applications/' . ($application['application_code'] ?? '') . '/edit') ?>">Edit Application</a>
                    <?php endif; ?>
                </div>
            </section>
        </aside>
    </div>
</div>
<?= $this->endSection() ?>
