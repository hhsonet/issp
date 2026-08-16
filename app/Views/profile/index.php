<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?php $profileUser = $profileUser ?? []; ?>
<div class="min-h-screen bg-slate-50 text-slate-900 lg:grid lg:grid-cols-[280px,1fr]">
    <div class="drawer-backdrop" data-drawer-backdrop></div>
    <?= $this->include('components/sidebar') ?>

    <main class="min-w-0 px-4 py-4 sm:px-6 lg:px-8">
        <?= $this->include('components/topbar', [
            'eyebrow' => 'Profile',
            'title' => 'Profile Settings',
            'description' => 'Update your account details and password from inside the ISSP dashboard.',
        ]) ?>

        <div class="mx-auto w-full max-w-4xl">
            <div class="rounded-3xl border border-slate-200 bg-white shadow-soft">
                <div class="border-b border-slate-200 px-6 py-6 sm:px-8">
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-brand-700">Profile Settings</p>
                    <h1 class="mt-3 text-3xl font-semibold tracking-tight text-slate-950 sm:text-4xl">Profile Settings</h1>
                    <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600 sm:text-base">
                        Keep your profile current without leaving the dashboard. Password fields remain empty until you decide to change them.
                    </p>
                </div>

                <div class="px-6 py-6 sm:px-8 sm:py-8">
                    <div class="grid gap-8">
                        <section class="grid gap-5">
                            <div>
                                <h2 class="text-lg font-semibold text-slate-950">1. Personal Information</h2>
                                <p class="mt-1 text-sm text-slate-500">Keep your account details current.</p>
                            </div>

                            <?= form_open(site_url('profile'), ['class' => 'grid gap-5']) ?>
                                <?= csrf_field() ?>
                                <div class="grid gap-5 md:grid-cols-2">
                                    <div>
                                        <label class="mb-2 block text-sm font-medium" for="full_name">Full Name <span class="text-rose-600">*</span></label>
                                        <input class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-100" id="full_name" name="full_name" type="text" value="<?= esc(old('full_name', $profileUser['full_name'] ?? '')) ?>" autocomplete="name" aria-invalid="<?= isset(($profileErrors ?? [])['full_name']) ? 'true' : 'false' ?>">
                                        <?= view('components/form_errors', ['error' => ($profileErrors['full_name'] ?? null)]) ?>
                                    </div>

                                    <div>
                                        <label class="mb-2 block text-sm font-medium" for="phone">Phone Number <span class="text-rose-600">*</span></label>
                                        <input class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-100" id="phone" name="phone" type="tel" value="<?= esc(old('phone', $profileUser['phone'] ?? '')) ?>" placeholder="01712345678 or +8801712345678" autocomplete="tel" aria-invalid="<?= isset(($profileErrors ?? [])['phone']) ? 'true' : 'false' ?>">
                                        <?= view('components/form_errors', ['error' => ($profileErrors['phone'] ?? null)]) ?>
                                    </div>

                                    <div>
                                        <label class="mb-2 block text-sm font-medium" for="gender_identity">Gender Identity <span class="text-rose-600">*</span></label>
                                        <select class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-100" id="gender_identity" name="gender_identity" aria-invalid="<?= isset(($profileErrors ?? [])['gender_identity']) ? 'true' : 'false' ?>">
                                            <option value="">Select...</option>
                                            <?php foreach (['Woman', 'Man', 'Gender Diverse Individuals'] as $value): ?>
                                                <option value="<?= esc($value) ?>" <?= old('gender_identity', $profileUser['gender_identity'] ?? '') === $value ? 'selected' : '' ?>><?= esc($value) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?= view('components/form_errors', ['error' => ($profileErrors['gender_identity'] ?? null)]) ?>
                                    </div>
                                </div>

                                <div class="flex justify-end pt-2">
                                    <button class="inline-flex w-full items-center justify-center rounded-2xl bg-brand-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-brand-700 disabled:cursor-not-allowed disabled:opacity-60 md:w-auto" type="submit">Save Personal Information</button>
                                </div>
                            <?= form_close() ?>
                        </section>

                        <section class="grid gap-5 border-t border-slate-200 pt-8">
                            <div>
                                <h2 class="text-lg font-semibold text-slate-950">2. Change Password</h2>
                                <p class="mt-1 text-sm text-slate-500">Change your password only when needed. All password fields stay empty by default.</p>
                            </div>

                            <?= form_open(site_url('profile/password'), ['class' => 'grid gap-5']) ?>
                                <?= csrf_field() ?>
                                <div class="grid gap-5 md:grid-cols-3">
                                    <div>
                                        <label class="mb-2 block text-sm font-medium" for="current_password">Current Password</label>
                                        <div class="relative">
                                            <input class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 pr-20 text-sm outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-100" id="current_password" name="current_password" type="password" autocomplete="current-password" aria-invalid="<?= isset(($profileErrors ?? [])['current_password']) ? 'true' : 'false' ?>">
                                            <button type="button" class="absolute right-2 top-2 grid h-10 w-10 place-items-center rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-100 focus:outline-none focus:ring-4 focus:ring-brand-100" data-toggle-password="current_password" aria-label="Show current password" aria-pressed="false">
                                                <span class="sr-only">Toggle password visibility</span>
                                                <svg data-icon="eye" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                                <svg data-icon="eye-off" class="hidden h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 3l18 18"/><path d="M10.58 10.58A3 3 0 0 0 12 15a3 3 0 0 0 2.12-.88"/><path d="M9.88 5.07A10.94 10.94 0 0 1 12 5c6.5 0 10 7 10 7a18.34 18.34 0 0 1-2.67 3.89"/><path d="M6.61 6.61C3.95 8.49 2 12 2 12s3.5 7 10 7a10.93 10.93 0 0 0 4.5-.93"/></svg>
                                            </button>
                                        </div>
                                        <?= view('components/form_errors', ['error' => ($profileErrors['current_password'] ?? null)]) ?>
                                    </div>

                                    <div>
                                        <label class="mb-2 block text-sm font-medium" for="new_password">New Password</label>
                                        <div class="relative">
                                            <input class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 pr-20 text-sm outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-100" id="new_password" name="new_password" type="password" autocomplete="new-password" aria-invalid="<?= isset(($profileErrors ?? [])['new_password']) ? 'true' : 'false' ?>">
                                            <button type="button" class="absolute right-2 top-2 grid h-10 w-10 place-items-center rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-100 focus:outline-none focus:ring-4 focus:ring-brand-100" data-toggle-password="new_password" aria-label="Show new password" aria-pressed="false">
                                                <span class="sr-only">Toggle password visibility</span>
                                                <svg data-icon="eye" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                                <svg data-icon="eye-off" class="hidden h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 3l18 18"/><path d="M10.58 10.58A3 3 0 0 0 12 15a3 3 0 0 0 2.12-.88"/><path d="M9.88 5.07A10.94 10.94 0 0 1 12 5c6.5 0 10 7 10 7a18.34 18.34 0 0 1-2.67 3.89"/><path d="M6.61 6.61C3.95 8.49 2 12 2 12s3.5 7 10 7a10.93 10.93 0 0 0 4.5-.93"/></svg>
                                            </button>
                                        </div>
                                        <?= view('components/form_errors', ['error' => ($profileErrors['new_password'] ?? null)]) ?>
                                    </div>

                                    <div>
                                        <label class="mb-2 block text-sm font-medium" for="confirm_new_password">Confirm New Password</label>
                                        <div class="relative">
                                            <input class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 pr-20 text-sm outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-100" id="confirm_new_password" name="confirm_new_password" type="password" autocomplete="new-password" aria-invalid="<?= isset(($profileErrors ?? [])['confirm_new_password']) ? 'true' : 'false' ?>">
                                            <button type="button" class="absolute right-2 top-2 grid h-10 w-10 place-items-center rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-100 focus:outline-none focus:ring-4 focus:ring-brand-100" data-toggle-password="confirm_new_password" aria-label="Show confirm password" aria-pressed="false">
                                                <span class="sr-only">Toggle password visibility</span>
                                                <svg data-icon="eye" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                                <svg data-icon="eye-off" class="hidden h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 3l18 18"/><path d="M10.58 10.58A3 3 0 0 0 12 15a3 3 0 0 0 2.12-.88"/><path d="M9.88 5.07A10.94 10.94 0 0 1 12 5c6.5 0 10 7 10 7a18.34 18.34 0 0 1-2.67 3.89"/><path d="M6.61 6.61C3.95 8.49 2 12 2 12s3.5 7 10 7a10.93 10.93 0 0 0 4.5-.93"/></svg>
                                            </button>
                                        </div>
                                        <?= view('components/form_errors', ['error' => ($profileErrors['confirm_new_password'] ?? null)]) ?>
                                    </div>
                                </div>

                                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-500">
                                    Leave the password fields empty if you only want to update your personal information.
                                </div>

                                <div class="flex justify-end pt-2">
                                    <button class="inline-flex w-full items-center justify-center rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-60 md:w-auto" type="submit">Update Password</button>
                                </div>
                            <?= form_close() ?>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
<?= $this->endSection() ?>
