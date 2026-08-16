<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<main class="min-h-screen bg-slate-50 text-slate-900">
    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-soft">
            <h1 class="text-3xl font-semibold tracking-tight text-slate-950">Application Details</h1>
            <p class="mt-2 text-sm text-slate-500">Round <?= esc($application['round_number']) ?> · <?= esc($application['round_title']) ?></p>
            <div class="mt-6 grid gap-4 md:grid-cols-2">
                <?php foreach ($application as $key => $value): if (in_array($key, ['id', 'round_id', 'user_id', 'created_at', 'updated_at'])) continue; ?>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <div class="text-xs uppercase text-slate-500"><?= esc(str_replace('_', ' ', $key)) ?></div>
                        <div class="mt-1 text-sm font-medium text-slate-950"><?= esc((string) $value) ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</main>
<?= $this->endSection() ?>
