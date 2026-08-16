<?= $this->extend('admin/layout') ?>
<?= $this->section('adminContent') ?>
<div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
    <?php foreach ([
        ['label' => 'Total application calls', 'value' => $summary['total_calls'] ?? 0],
        ['label' => 'Currently accepting calls', 'value' => $summary['open_calls'] ?? 0],
        ['label' => 'Total submitted applications', 'value' => $summary['total_applications'] ?? 0],
        ['label' => 'Latest round applications', 'value' => $summary['latest_round_applications'] ?? 0],
        ['label' => 'Total registered users', 'value' => $summary['total_users'] ?? 0],
        ['label' => 'Total universities', 'value' => $summary['total_universities'] ?? 0],
        ['label' => 'Total departments', 'value' => $summary['total_departments'] ?? 0],
    ] as $card): ?>
        <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-soft">
            <div class="text-sm text-slate-500"><?= esc($card['label']) ?></div>
            <div class="mt-2 text-3xl font-semibold tracking-tight text-slate-950"><?= esc($card['value']) ?></div>
        </article>
    <?php endforeach; ?>
</div>

<div class="mt-6 grid gap-6 xl:grid-cols-[1.3fr,.9fr]">
    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-soft">
        <h2 class="text-xl font-semibold text-slate-950">Recent Applications</h2>
        <div class="mt-4 overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="border-b border-slate-200 px-3 py-3">Applicant</th>
                        <th class="border-b border-slate-200 px-3 py-3">Student ID</th>
                        <th class="border-b border-slate-200 px-3 py-3">University</th>
                        <th class="border-b border-slate-200 px-3 py-3">Type</th>
                        <th class="border-b border-slate-200 px-3 py-3">Round</th>
                        <th class="border-b border-slate-200 px-3 py-3">Submitted</th>
                        <th class="border-b border-slate-200 px-3 py-3">Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($recentApplications as $row): ?>
                    <tr>
                        <td class="border-b border-slate-100 px-3 py-4 font-medium text-slate-900"><?= esc($row['full_name']) ?></td>
                        <td class="border-b border-slate-100 px-3 py-4"><?= esc($row['student_id']) ?></td>
                        <td class="border-b border-slate-100 px-3 py-4"><?= esc($row['university_name'] ?? '') ?></td>
                        <td class="border-b border-slate-100 px-3 py-4"><?= esc($row['internship_type']) ?></td>
                        <td class="border-b border-slate-100 px-3 py-4"><?= esc($row['round_code'] ?? '') ?></td>
                        <td class="border-b border-slate-100 px-3 py-4"><?= esc($row['submitted_at'] ?? '') ?></td>
                        <td class="border-b border-slate-100 px-3 py-4"><?= esc($row['status']) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-soft">
        <h2 class="text-xl font-semibold text-slate-950">Active and Upcoming Calls</h2>
        <div class="mt-4 space-y-3">
            <?php foreach ($activeCalls as $call): ?>
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="text-sm font-semibold text-slate-900"><?= esc($call['round_code']) ?> · <?= esc($call['title']) ?></div>
                            <div class="mt-1 text-xs text-slate-500"><?= esc($call['opens_at']) ?> → <?= esc($call['closes_at']) ?></div>
                        </div>
                        <div class="text-right text-xs text-slate-500">
                            <div><?= esc($call['status']) ?></div>
                            <div><?= esc((string) ($call['applications_count'] ?? 0)) ?> applications</div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a class="inline-flex rounded-2xl bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-700" href="<?= site_url('admin/calls/' . $call['id'] . '/edit') ?>">Manage</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</div>
<?= $this->endSection() ?>
