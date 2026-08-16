<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<main class="min-h-screen bg-slate-50 text-slate-900">
    <div class="mx-auto grid min-h-screen max-w-7xl lg:grid-cols-2">
        <section class="hidden lg:flex lg:flex-col lg:justify-between bg-slate-950 text-white p-10 xl:p-14">
            <div>
                <a href="<?= site_url('/') ?>" class="inline-flex items-center gap-3 rounded-2xl border border-white/10 bg-white/5 px-4 py-3 backdrop-blur">
                    <span class="grid h-11 w-11 place-items-center rounded-2xl bg-white text-slate-950 font-extrabold">IS</span>
                    <span>
                        <span class="block text-lg font-semibold">ISSP</span>
                        <span class="block text-sm text-slate-300">Higher Education Initiative</span>
                    </span>
                </a>
                <h1 class="mt-16 max-w-xl text-5xl font-semibold leading-tight">A modern portal for secure institutional applications.</h1>
                <p class="mt-6 max-w-xl text-base text-slate-300">Sign in to review your profile, track applications, and manage official documents with a calm, professional interface.</p>
                <div class="mt-10 space-y-4">
                    <?php foreach (['Secure, server-side validation', 'Responsive across all devices', 'Fast access to your applications'] as $item): ?>
                        <div class="flex items-start gap-3 text-slate-200">
                            <span class="mt-1 grid h-6 w-6 place-items-center rounded-full bg-white/10 text-sm">✓</span>
                            <span><?= esc($item) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="rounded-3xl border border-white/10 bg-white/5 p-5 text-sm text-slate-300">
                ISSP keeps access secure with native CI4 sessions, CSRF protection, and clean server-rendered forms.
            </div>
        </section>

        <section class="flex items-center justify-center px-4 py-8 sm:px-6 lg:px-10">
            <div class="w-full max-w-md">
                <a href="<?= site_url('/') ?>" class="mb-6 inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-slate-900">
                    <span aria-hidden="true">←</span> Back to Home
                </a>
                <div class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-soft">
                    <div class="inline-flex rounded-full bg-brand-50 px-3 py-1 text-xs font-semibold text-brand-700">Sign in</div>
                    <h2 class="mt-4 text-3xl font-semibold tracking-tight">Welcome back</h2>
                    <p class="mt-2 text-sm text-slate-500">Enter your email address and password to continue.</p>

                    <?= form_open(site_url('login'), ['class' => 'mt-8 space-y-5']) ?>
                        <?= csrf_field() ?>
                        <div>
                            <label class="mb-2 block text-sm font-medium" for="email">Email address <span class="text-rose-600">*</span></label>
                            <input class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-100" id="email" name="email" type="email" value="<?= esc(old('email')) ?>" autocomplete="email" placeholder="name@example.com" aria-invalid="<?= isset($errors['email']) ? 'true' : 'false' ?>">
                            <?= view('components/form_errors', ['error' => $errors['email'] ?? null]) ?>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-medium" for="password">Password <span class="text-rose-600">*</span></label>
                            <div class="relative">
                                <input class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 pr-14 text-sm outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-100" id="password" name="password" type="password" autocomplete="current-password" placeholder="Enter your password" aria-invalid="<?= isset($errors['password']) ? 'true' : 'false' ?>">
                                <button type="button" class="absolute right-2 top-1/2 grid h-9 w-9 -translate-y-1/2 place-items-center rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-100 focus:outline-none focus:ring-4 focus:ring-brand-100" data-toggle-password="password" aria-label="Show password" aria-pressed="false">
                                    <span class="sr-only">Toggle password visibility</span>
                                    <svg data-icon="eye" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                    <svg data-icon="eye-off" class="hidden h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M3 3l18 18" />
                                        <path d="M10.58 10.58A3 3 0 0 0 12 15a3 3 0 0 0 2.12-.88" />
                                        <path d="M9.88 5.07A10.94 10.94 0 0 1 12 5c6.5 0 10 7 10 7a18.34 18.34 0 0 1-2.67 3.89" />
                                        <path d="M6.61 6.61C3.95 8.49 2 12 2 12s3.5 7 10 7a10.93 10.93 0 0 0 4.5-.93" />
                                    </svg>
                                </button>
                            </div>
                            <?= view('components/form_errors', ['error' => $errors['password'] ?? null]) ?>
                        </div>

                        <div class="flex items-center justify-between gap-4 text-sm">
                            <label class="inline-flex items-center gap-2 text-slate-600">
                                <input class="h-4 w-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500" type="checkbox" name="remember" value="1">
                                Remember me
                            </label>
                            <a class="font-medium text-brand-700 hover:text-brand-600" href="<?= site_url('forgot-password') ?>">Forgot password?</a>
                        </div>

                        <button class="w-full rounded-2xl bg-brand-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-brand-700 disabled:cursor-not-allowed disabled:opacity-60" type="submit">Sign In</button>

                        <p class="text-sm text-slate-500">New to ISSP? <a href="<?= site_url('signup') ?>" class="font-semibold text-brand-700 hover:text-brand-600">Create Account</a></p>
                        <p class="text-xs text-slate-400">Your session is protected with native CI4 CSRF and secure server-side validation.</p>
                    <?= form_close() ?>
                </div>
            </div>
        </section>
    </div>
</main>
<?= $this->endSection() ?>
