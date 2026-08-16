<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<main class="min-h-screen bg-slate-50 text-slate-900">
    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-soft">
            <h1 class="text-3xl font-semibold tracking-tight text-slate-950">Application Rounds</h1>
            <p class="mt-2 text-sm text-slate-500">Admin-only round management.</p>
            <div class="mt-6 grid gap-6 lg:grid-cols-[1fr,.9fr]">
                <form method="post" action="<?= site_url('admin/application-rounds') ?>" class="grid gap-4">
                    <?= csrf_field() ?>
                    <input name="round_number" type="number" min="1" placeholder="Round number" class="rounded-2xl border border-slate-200 px-4 py-3">
                    <input name="title" placeholder="Title" class="rounded-2xl border border-slate-200 px-4 py-3">
                    <textarea name="description" placeholder="Description" class="rounded-2xl border border-slate-200 px-4 py-3"></textarea>
                    <input name="opens_at" type="datetime-local" class="rounded-2xl border border-slate-200 px-4 py-3">
                    <input name="closes_at" type="datetime-local" class="rounded-2xl border border-slate-200 px-4 py-3">
                    <select name="status" class="rounded-2xl border border-slate-200 px-4 py-3"><option>Draft</option><option>Open</option><option>Closed</option></select>
                    <button class="rounded-2xl bg-brand-600 px-4 py-3 text-sm font-semibold text-white">Create Round</button>
                </form>
                <div class="space-y-3">
                    <?php foreach ($rounds as $round): ?>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <strong>Round <?= esc($round['round_number']) ?> · <?= esc($round['title']) ?></strong>
                            <div class="mt-1 text-sm text-slate-500"><?= esc($round['status']) ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</main>
<?= $this->endSection() ?>
