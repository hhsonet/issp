<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?php
$round = $round ?? null;
$mode = $mode ?? 'create';
$actionUrl = $mode === 'edit' && $round ? site_url('admin/calls/' . $round['id']) : site_url('admin/calls');
?>
<main class="min-h-screen bg-slate-50 text-slate-900">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <header class="rounded-3xl border border-slate-200 bg-white p-6 shadow-soft">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-700">Admin</p>
            <div class="mt-2 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <h1 class="text-3xl font-semibold tracking-tight text-slate-950">Internship calls</h1>
                    <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-500">Create, update, open, and close application calls from one place. All actions use CSRF-protected POST requests.</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a class="inline-flex rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50" href="<?= site_url('admin/calls/create') ?>">Create New Call</a>
                    <a class="inline-flex rounded-2xl bg-brand-600 px-4 py-3 text-sm font-semibold text-white hover:bg-brand-700" href="<?= site_url('admin/calls') ?>">Refresh</a>
                </div>
            </div>
        </header>

        <section class="mt-6 grid gap-6 lg:grid-cols-[.95fr,1.3fr]">
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-soft">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-700"><?= $mode === 'edit' ? 'Edit call' : 'Create call' ?></p>
                        <h2 class="mt-2 text-2xl font-semibold tracking-tight text-slate-950"><?= $mode === 'edit' ? 'Update application call' : 'Set up a new application call' ?></h2>
                    </div>
                    <?php if ($round): ?>
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">Editing #<?= esc($round['id']) ?></span>
                    <?php endif; ?>
                </div>

                <?= form_open($actionUrl, ['class' => 'mt-6 grid gap-4']) ?>
                    <?= csrf_field() ?>
                    <div>
                        <label class="mb-2 block text-sm font-medium" for="round_code">Round Code *</label>
                        <div class="flex gap-2">
                            <input id="round_code" name="round_code" type="text" maxlength="6" inputmode="numeric" placeholder="250001" value="<?= esc(old('round_code', $round['round_code'] ?? '')) ?>" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-100" aria-invalid="<?= isset(($errors ?? [])['round_code']) ? 'true' : 'false' ?>">
                            <button type="button" class="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50" data-generate-code>Generate</button>
                        </div>
                        <?= view('components/form_errors', ['error' => ($errors['round_code'] ?? null)]) ?>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium" for="title">Application Title *</label>
                        <input id="title" name="title" type="text" maxlength="255" placeholder="e.g. Summer Internship 2026" value="<?= esc(old('title', $round['title'] ?? '')) ?>" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-100" aria-invalid="<?= isset(($errors ?? [])['title']) ? 'true' : 'false' ?>">
                        <?= view('components/form_errors', ['error' => ($errors['title'] ?? null)]) ?>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium" for="description">Description *</label>
                        <textarea id="description" name="description" rows="5" placeholder="Add the call details, eligibility, and any important instructions." class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-100" aria-invalid="<?= isset(($errors ?? [])['description']) ? 'true' : 'false' ?>"><?= esc(old('description', $round['description'] ?? '')) ?></textarea>
                        <?= view('components/form_errors', ['error' => ($errors['description'] ?? null)]) ?>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-medium" for="opens_at">Start Date and Time *</label>
                            <input id="opens_at" name="opens_at" type="datetime-local" value="<?= esc(old('opens_at', isset($round['opens_at']) ? str_replace(' ', 'T', substr((string) $round['opens_at'], 0, 16)) : '')) ?>" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-100" aria-invalid="<?= isset(($errors ?? [])['opens_at']) ? 'true' : 'false' ?>">
                            <?= view('components/form_errors', ['error' => ($errors['opens_at'] ?? null)]) ?>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium" for="closes_at">End Date and Time *</label>
                            <input id="closes_at" name="closes_at" type="datetime-local" value="<?= esc(old('closes_at', isset($round['closes_at']) ? str_replace(' ', 'T', substr((string) $round['closes_at'], 0, 16)) : '')) ?>" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-100" aria-invalid="<?= isset(($errors ?? [])['closes_at']) ? 'true' : 'false' ?>">
                            <?= view('components/form_errors', ['error' => ($errors['closes_at'] ?? null)]) ?>
                        </div>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium" for="status">Call Status *</label>
                        <select id="status" name="status" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-100" aria-invalid="<?= isset(($errors ?? [])['status']) ? 'true' : 'false' ?>">
                            <?php foreach (['Draft', 'Open', 'Closed'] as $status): ?>
                                <option value="<?= esc($status) ?>" <?= old('status', $round['status'] ?? 'Draft') === $status ? 'selected' : '' ?>><?= esc($status) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?= view('components/form_errors', ['error' => ($errors['status'] ?? null)]) ?>
                    </div>

                    <div class="flex flex-wrap gap-3 pt-2">
                        <button id="submit-call" class="inline-flex rounded-2xl bg-brand-600 px-5 py-3 text-sm font-semibold text-white hover:bg-brand-700 disabled:cursor-not-allowed disabled:opacity-60" type="submit"><?= $mode === 'edit' ? 'Update Call' : 'Create Call' ?></button>
                        <a class="inline-flex rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50" href="<?= site_url('admin/calls') ?>">Back to list</a>
                    </div>
                <?= form_close() ?>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-soft">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-700">Existing calls</p>
                        <h2 class="mt-2 text-2xl font-semibold tracking-tight text-slate-950">Manage listing</h2>
                    </div>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700"><?= count($rounds) ?> total</span>
                </div>

                <div class="mt-5 overflow-x-auto">
                    <table class="min-w-full border-separate border-spacing-0 text-left text-sm">
                        <thead>
                            <tr class="text-xs uppercase tracking-wide text-slate-500">
                                <th class="border-b border-slate-200 px-3 py-3">Code</th>
                                <th class="border-b border-slate-200 px-3 py-3">Title</th>
                                <th class="border-b border-slate-200 px-3 py-3">Start</th>
                                <th class="border-b border-slate-200 px-3 py-3">End</th>
                                <th class="border-b border-slate-200 px-3 py-3">Configured</th>
                                <th class="border-b border-slate-200 px-3 py-3">Effective</th>
                                <th class="border-b border-slate-200 px-3 py-3">Apps</th>
                                <th class="border-b border-slate-200 px-3 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($rounds)): ?>
                                <tr><td colspan="8" class="px-3 py-10 text-center text-slate-500">No calls have been created yet.</td></tr>
                            <?php endif; ?>
                            <?php foreach ($rounds as $item): ?>
                                <tr class="align-top">
                                    <td class="border-b border-slate-100 px-3 py-4 font-semibold text-slate-900"><?= esc($item['round_code']) ?></td>
                                    <td class="border-b border-slate-100 px-3 py-4"><?= esc($item['title']) ?></td>
                                    <td class="border-b border-slate-100 px-3 py-4"><?= esc(date('M j, Y g:i A', strtotime((string) $item['opens_at']))) ?></td>
                                    <td class="border-b border-slate-100 px-3 py-4"><?= esc(date('M j, Y g:i A', strtotime((string) $item['closes_at']))) ?></td>
                                    <td class="border-b border-slate-100 px-3 py-4">
                                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700"><?= esc($item['status']) ?></span>
                                    </td>
                                    <td class="border-b border-slate-100 px-3 py-4">
                                        <?php
                                            $tone = match ($item['effective_status']) {
                                                'Accepting Applications' => 'bg-emerald-50 text-emerald-700',
                                                'Upcoming' => 'bg-sky-50 text-sky-700',
                                                'Expired' => 'bg-amber-50 text-amber-700',
                                                'Closed' => 'bg-slate-100 text-slate-700',
                                                default => 'bg-slate-100 text-slate-700',
                                            };
                                        ?>
                                        <span class="rounded-full px-3 py-1 text-xs font-semibold <?= esc($tone) ?>"><?= esc($item['effective_status']) ?></span>
                                    </td>
                                    <td class="border-b border-slate-100 px-3 py-4"><?= esc((string) $item['applications_count']) ?></td>
                                    <td class="border-b border-slate-100 px-3 py-4">
                                        <div class="flex flex-wrap gap-2">
                                            <a class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50" href="<?= site_url('admin/calls/' . $item['id'] . '/edit') ?>">Edit</a>
                                            <a class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50" href="<?= site_url('applications/' . ($item['application_code'] ?? '')) ?>" aria-disabled="true">View</a>
                                            <?php if ($item['can_open']): ?>
                                                <form method="post" action="<?= site_url('admin/calls/' . $item['id'] . '/status') ?>" class="inline">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="status" value="Open">
                                                    <button class="rounded-xl bg-brand-600 px-3 py-2 text-xs font-semibold text-white hover:bg-brand-700" type="submit">Open Call</button>
                                                </form>
                                            <?php endif; ?>
                                            <?php if ($item['can_close']): ?>
                                                <form method="post" action="<?= site_url('admin/calls/' . $item['id'] . '/status') ?>" class="inline" data-confirm-close>
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="status" value="Closed">
                                                    <button class="rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-700 hover:bg-rose-100" type="submit">Close Call</button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</main>
<script>
(function () {
    const form = document.querySelector('form[data-confirm-close]');
    if (form) {
        form.addEventListener('submit', function (event) {
            if (! window.confirm('Close this call? This will stop new applications.')) {
                event.preventDefault();
            }
        });
    }

    const submit = document.getElementById('submit-call');
    const generate = document.querySelector('[data-generate-code]');
    if (generate) {
        generate.addEventListener('click', function () {
            const input = document.getElementById('round_code');
            if (input) {
                input.value = String(Math.floor(100000 + Math.random() * 900000));
            }
        });
    }
    if (submit) {
        const formEl = submit.closest('form');
        if (formEl) {
            formEl.addEventListener('submit', function () {
                submit.disabled = true;
                submit.textContent = 'Saving...';
            });
        }
    }
})();
</script>
<?= $this->endSection() ?>
