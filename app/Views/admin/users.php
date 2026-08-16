<?= $this->extend('admin/layout') ?>
<?= $this->section('adminContent') ?>
<section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-soft">
    <h2 class="text-xl font-semibold text-slate-950">Users</h2>
    <div class="mt-4 overflow-x-auto">
        <table class="min-w-full text-left text-sm">
            <thead class="text-xs uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="border-b border-slate-200 px-3 py-3">Full Name</th>
                    <th class="border-b border-slate-200 px-3 py-3">Email</th>
                    <th class="border-b border-slate-200 px-3 py-3">Phone</th>
                    <th class="border-b border-slate-200 px-3 py-3">Role</th>
                    <th class="border-b border-slate-200 px-3 py-3">Active</th>
                    <th class="border-b border-slate-200 px-3 py-3">Registered</th>
                    <th class="border-b border-slate-200 px-3 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $row): ?>
                    <tr>
                        <td class="border-b border-slate-100 px-3 py-4"><?= esc($row['full_name']) ?></td>
                        <td class="border-b border-slate-100 px-3 py-4"><?= esc($row['email']) ?></td>
                        <td class="border-b border-slate-100 px-3 py-4"><?= esc($row['phone'] ?? '') ?></td>
                        <td class="border-b border-slate-100 px-3 py-4"><?= esc($row['role'] ?? 'user') ?></td>
                        <td class="border-b border-slate-100 px-3 py-4"><?= ((int) ($row['is_active'] ?? 1) === 1) ? 'Yes' : 'No' ?></td>
                        <td class="border-b border-slate-100 px-3 py-4"><?= esc($row['created_at'] ?? '') ?></td>
                        <td class="border-b border-slate-100 px-3 py-4">
                            <div class="flex gap-2">
                                <form method="post" action="<?= site_url('admin/users/' . $row['id'] . '/status') ?>">
                                    <?= csrf_field() ?>
                                    <button class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50" type="submit">Toggle Status</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
<?= $this->endSection() ?>
