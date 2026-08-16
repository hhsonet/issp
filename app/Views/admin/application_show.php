<?= $this->extend('admin/layout') ?>
<?= $this->section('adminContent') ?>
<?php
$application = $application ?? [];

if (! function_exists('admin_app_value')) {
    function admin_app_value(array $application, string $key, string $default = '—'): string
    {
        $value = $application[$key] ?? null;
        if ($value === null || $value === '') {
            return $default;
        }

        return (string) $value;
    }
}

if (! function_exists('admin_app_display_date')) {
    function admin_app_display_date($value): string
    {
        if (! $value) {
            return '—';
        }

        $timestamp = strtotime((string) $value);
        if ($timestamp === false) {
            return (string) $value;
        }

        return date('M d, Y h:i A', $timestamp);
    }
}
?>

<section class="w-full min-w-0 rounded-3xl border border-slate-200 bg-white p-4 shadow-soft sm:p-6">
    <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h2 class="text-xl font-semibold text-slate-950">Application Details</h2>
            <p class="mt-1 text-sm text-slate-500"><?= esc(admin_app_value($application, 'application_code')) ?></p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a class="inline-flex items-center rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50" href="<?= site_url('admin/application') ?>">Back to Applications</a>
            <?php if ((int) ($application['edit_enabled'] ?? 0) === 1): ?>
                <form method="post" action="<?= site_url('admin/application/' . ($application['application_code'] ?? '') . '/toggle-edit-access') ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="edit_enabled" value="0">
                    <button class="inline-flex items-center rounded-2xl border border-amber-200 bg-amber-50 px-4 py-2 text-sm font-semibold text-amber-700 hover:bg-amber-100" type="submit">Revoke Edit Access</button>
                </form>
            <?php else: ?>
                <form method="post" action="<?= site_url('admin/application/' . ($application['application_code'] ?? '') . '/toggle-edit-access') ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="edit_enabled" value="1">
                    <button class="inline-flex items-center rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-700 hover:bg-emerald-100" type="submit">Grant Edit Access</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <div class="mt-6 overflow-hidden rounded-3xl border border-slate-200">
        <table class="min-w-full border-collapse text-left text-sm">
            <tbody class="divide-y divide-slate-200">
                <tr>
                    <th class="w-56 bg-slate-50 px-4 py-3 font-semibold text-slate-600">Application Code</th>
                    <td class="px-4 py-3 font-medium text-slate-950"><?= esc(admin_app_value($application, 'application_code')) ?></td>
                </tr>
                <tr>
                    <th class="bg-slate-50 px-4 py-3 font-semibold text-slate-600">Applicant Name</th>
                    <td class="px-4 py-3">
                        <div class="font-medium text-slate-950"><?= esc(admin_app_value($application, 'full_name')) ?></div>
                        <div class="text-xs text-slate-500"><?= esc(admin_app_value($application, 'email')) ?></div>
                    </td>
                </tr>
                <tr>
                    <th class="bg-slate-50 px-4 py-3 font-semibold text-slate-600">Student ID</th>
                    <td class="px-4 py-3 font-medium text-slate-950"><?= esc(admin_app_value($application, 'student_id')) ?></td>
                </tr>
                <tr>
                    <th class="bg-slate-50 px-4 py-3 font-semibold text-slate-600">University</th>
                    <td class="px-4 py-3">
                        <div class="font-medium text-slate-950"><?= esc(admin_app_value($application, 'university_name')) ?></div>
                        <?php if (! empty($application['university_type'])): ?>
                            <div class="mt-1 inline-flex rounded-full border border-slate-200 bg-slate-100 px-2 py-1 text-[11px] font-semibold text-slate-600">
                                <?= esc((string) $application['university_type']) ?>
                            </div>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th class="bg-slate-50 px-4 py-3 font-semibold text-slate-600">Department</th>
                    <td class="px-4 py-3 font-medium text-slate-950">
                        <?= esc(admin_app_value($application, 'department')) ?>
                        <?php if (! empty($application['other_department'])): ?>
                            <div class="mt-1 text-xs text-slate-500">Other: <?= esc((string) $application['other_department']) ?></div>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th class="bg-slate-50 px-4 py-3 font-semibold text-slate-600">Gender Identity</th>
                    <td class="px-4 py-3 font-medium text-slate-950"><?= esc(admin_app_value($application, 'gender_identity')) ?></td>
                </tr>
                <tr>
                    <th class="bg-slate-50 px-4 py-3 font-semibold text-slate-600">Date of Birth</th>
                    <td class="px-4 py-3 font-medium text-slate-950"><?= esc(admin_app_display_date($application['date_of_birth'] ?? null)) ?></td>
                </tr>
                <tr>
                    <th class="bg-slate-50 px-4 py-3 font-semibold text-slate-600">Phone</th>
                    <td class="px-4 py-3 font-medium text-slate-950"><?= esc(admin_app_value($application, 'phone')) ?></td>
                </tr>
                <tr>
                    <th class="bg-slate-50 px-4 py-3 font-semibold text-slate-600">Current CGPA</th>
                    <td class="px-4 py-3 font-medium text-slate-950"><?= esc(admin_app_value($application, 'current_cgpa')) ?></td>
                </tr>
                <tr>
                    <th class="bg-slate-50 px-4 py-3 font-semibold text-slate-600">Total Credits</th>
                    <td class="px-4 py-3 font-medium text-slate-950"><?= esc(admin_app_value($application, 'total_credits')) ?></td>
                </tr>
                <tr>
                    <th class="bg-slate-50 px-4 py-3 font-semibold text-slate-600">Earned Credits</th>
                    <td class="px-4 py-3 font-medium text-slate-950"><?= esc(admin_app_value($application, 'earned_credits')) ?></td>
                </tr>
                <tr>
                    <th class="bg-slate-50 px-4 py-3 font-semibold text-slate-600">Credit Completion</th>
                    <td class="px-4 py-3 font-medium text-slate-950">
                        <?= esc(admin_app_value($application, 'credit_completion_percentage')) ?><?php if (! empty($application['credit_completion_percentage'])): ?>%<?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th class="bg-slate-50 px-4 py-3 font-semibold text-slate-600">Disability Status</th>
                    <td class="px-4 py-3 font-medium text-slate-950">
                        <?= esc(admin_app_value($application, 'disability_status')) ?>
                        <?php if (! empty($application['disability_type']) && (string) ($application['disability_status'] ?? '') === 'Yes'): ?>
                            <div class="mt-1 text-xs text-slate-500">Type: <?= esc((string) $application['disability_type']) ?></div>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th class="bg-slate-50 px-4 py-3 font-semibold text-slate-600">Ethnic Minority Status</th>
                    <td class="px-4 py-3 font-medium text-slate-950">
                        <?= esc(admin_app_value($application, 'ethnic_minority_status')) ?>
                        <?php if (! empty($application['ethnic_group_name']) && (string) ($application['ethnic_minority_status'] ?? '') === 'Yes'): ?>
                            <div class="mt-1 text-xs text-slate-500">Community/Group: <?= esc((string) $application['ethnic_group_name']) ?></div>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th class="bg-slate-50 px-4 py-3 font-semibold text-slate-600">Internship Type</th>
                    <td class="px-4 py-3 font-medium text-slate-950"><?= esc(admin_app_value($application, 'internship_type')) ?></td>
                </tr>
                <tr>
                    <th class="bg-slate-50 px-4 py-3 font-semibold text-slate-600">Placement Organization</th>
                    <td class="px-4 py-3 font-medium text-slate-950"><?= esc(admin_app_value($application, 'placement_organization_name')) ?></td>
                </tr>
                <tr>
                    <th class="bg-slate-50 px-4 py-3 font-semibold text-slate-600">Submission Status</th>
                    <td class="px-4 py-3 font-medium text-slate-950"><?= esc(admin_app_value($application, 'status')) ?></td>
                </tr>
                <tr>
                    <th class="bg-slate-50 px-4 py-3 font-semibold text-slate-600">Submitted At</th>
                    <td class="px-4 py-3 font-medium text-slate-950"><?= esc(admin_app_display_date($application['submitted_at'] ?? null)) ?></td>
                </tr>
                <tr>
                    <th class="bg-slate-50 px-4 py-3 font-semibold text-slate-600">Edit Access</th>
                    <td class="px-4 py-3 font-medium text-slate-950">
                        <?= ((int) ($application['edit_enabled'] ?? 0) === 1) ? 'Allowed' : 'Locked' ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</section>
<?= $this->endSection() ?>
