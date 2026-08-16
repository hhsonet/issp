<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<main class="min-h-screen bg-slate-50 text-slate-900">
    <header class="sticky top-0 z-40 border-b border-slate-200/80 bg-white/90 backdrop-blur">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
            <a class="flex items-center gap-3" href="<?= site_url('/') ?>">
                <span class="grid h-11 w-11 place-items-center rounded-2xl bg-slate-950 text-sm font-extrabold text-white">IS</span>
                <span>
                    <span class="block text-base font-semibold tracking-tight">ISSP</span>
                    <span class="block text-xs text-slate-500">Higher Education Initiative</span>
                </span>
            </a>

            <nav class="hidden items-center gap-6 text-sm font-medium text-slate-600 md:flex" aria-label="Primary">
                <a class="transition hover:text-slate-950" href="<?= site_url('/') ?>#features">Features</a>
                <a class="transition hover:text-slate-950" href="<?= site_url('/') ?>#process">How it works</a>
                <a class="transition hover:text-slate-950" href="<?= site_url('/') ?>#downloads">Downloads</a>
                <a class="transition hover:text-slate-950" href="<?= site_url('/') ?>#support">Support</a>
            </nav>

            <div class="flex items-center gap-2">
                <a class="inline-flex items-center rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-300 hover:bg-slate-50" href="<?= site_url('login') ?>">Sign In</a>
                <a class="inline-flex items-center rounded-full bg-brand-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-brand-700" href="<?= site_url('signup') ?>">Create Account</a>
            </div>
        </div>
    </header>

    <div class="mx-auto w-full max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="rounded-3xl border border-slate-200 bg-white shadow-soft">
            <div class="border-b border-slate-200 px-6 py-6 sm:px-8">
                <div class="inline-flex rounded-full bg-brand-50 px-3 py-1 text-xs font-semibold text-brand-700">Create account</div>
                <h1 class="mt-4 text-3xl font-semibold tracking-tight text-slate-950 sm:text-4xl">Create Your Account</h1>
                <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600 sm:text-base">
                    Please complete the form below to create your ISSP account. Your information is validated securely on the server, and conditional questions will appear only when needed.
                </p>
            </div>

            <div class="px-6 py-6 sm:px-8 sm:py-8">
                <?= form_open(site_url('signup'), ['class' => 'grid gap-8']) ?>
                    <?= csrf_field() ?>

                    <section class="grid gap-5">
                        <div>
                            <h2 class="text-lg font-semibold text-slate-950">1. Account Information</h2>
                            <p class="mt-1 text-sm text-slate-500">Use your basic contact details to set up the account.</p>
                        </div>

                        <div class="grid gap-5 md:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-sm font-medium" for="full_name">Student Name <span class="text-rose-600">*</span></label>
                                <input class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-100" id="full_name" name="full_name" value="<?= esc(old('full_name')) ?>" placeholder="" autocomplete="name" aria-invalid="<?= isset($errors['full_name']) ? 'true' : 'false' ?>">
                                <?= view('components/form_errors', ['error' => $errors['full_name'] ?? null]) ?>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium" for="email">Email Address <span class="text-rose-600">*</span></label>
                                <input class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-100" id="email" name="email" type="email" value="<?= esc(old('email')) ?>" placeholder="[hasan@student.just.edu]" autocomplete="email" aria-invalid="<?= isset($errors['email']) ? 'true' : 'false' ?>">
                                <?= view('components/form_errors', ['error' => $errors['email'] ?? null]) ?>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium" for="date_of_birth">Date of Birth <span class="text-rose-600">*</span></label>
                                <input class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-100" id="date_of_birth" name="date_of_birth" type="date" max="<?= esc(date('Y-m-d')) ?>" value="<?= esc(old('date_of_birth')) ?>" autocomplete="bday" aria-invalid="<?= isset($errors['date_of_birth']) ? 'true' : 'false' ?>">
                                <?= view('components/form_errors', ['error' => $errors['date_of_birth'] ?? null]) ?>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium" for="gender_identity">Gender Identity <span class="text-rose-600">*</span></label>
                                <select class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-100" id="gender_identity" name="gender_identity" aria-invalid="<?= isset($errors['gender_identity']) ? 'true' : 'false' ?>">
                                    <option value="">Select...</option>
                                    <?php foreach (['Woman', 'Man', 'Gender Diverse Individuals'] as $value): ?>
                                        <option value="<?= esc($value) ?>" <?= old('gender_identity') === $value ? 'selected' : '' ?>><?= esc($value) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <?= view('components/form_errors', ['error' => $errors['gender_identity'] ?? null]) ?>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium" for="phone">Phone Number <span class="text-rose-600">*</span></label>
                                <input class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-100" id="phone" name="phone" value="<?= esc(old('phone')) ?>" placeholder="01712345678 or +8801712345678" autocomplete="tel" aria-invalid="<?= isset($errors['phone']) ? 'true' : 'false' ?>">
                                <?= view('components/form_errors', ['error' => $errors['phone'] ?? null]) ?>
                            </div>
                        </div>
                    </section>

                    <section class="grid gap-5">
                        <div>
                            <h2 class="text-lg font-semibold text-slate-950">2. Personal and Demographic Information</h2>
                            <p class="mt-1 text-sm text-slate-500">Answer the following questions to complete your profile setup.</p>
                        </div>

                        <fieldset class="rounded-3xl border border-slate-200 bg-slate-50 p-4 sm:p-5" data-conditional-group data-target="disability" data-value="Yes" aria-describedby="disability_status_help">
                            <legend class="px-1 text-sm font-medium text-slate-900">Do you identify yourself as a person with disabilities? <span class="text-rose-600">*</span></legend>
                            <p id="disability_status_help" class="mt-1 text-sm text-slate-500">Select yes or no to continue.</p>
                            <div class="mt-4 flex flex-wrap gap-3">
                                <?php foreach (['Yes', 'No'] as $value): ?>
                                    <label class="flex cursor-pointer items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm transition hover:border-brand-300 hover:bg-brand-50/40">
                                        <input type="radio" name="disability_status" value="<?= esc($value) ?>" class="h-4 w-4 border-slate-300 text-brand-600 focus:ring-brand-500" aria-controls="disability_type_wrap" aria-expanded="<?= old('disability_status') === $value && $value === 'Yes' ? 'true' : 'false' ?>" <?= old('disability_status') === $value ? 'checked' : '' ?>>
                                        <span><?= esc($value) ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                            <div id="disability_type_wrap" class="mt-4 rounded-2xl border border-brand-100 bg-white p-4" data-conditional="disability" data-required="true" aria-hidden="<?= old('disability_status') === 'Yes' ? 'false' : 'true' ?>" <?= old('disability_status') === 'Yes' ? '' : 'hidden' ?>>
                                <label class="mb-2 block text-sm font-medium" for="disability_type">Please specify the type <span class="text-rose-600">*</span></label>
                                <input class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-100" id="disability_type" name="disability_type" value="<?= esc(old('disability_type')) ?>" placeholder="Specify disability type..." aria-invalid="<?= isset($errors['disability_type']) ? 'true' : 'false' ?>" <?= old('disability_status') === 'Yes' ? 'required' : 'disabled' ?>>
                            </div>
                            <?= view('components/form_errors', ['error' => $errors['disability_status'] ?? null]) ?>
                            <?= view('components/form_errors', ['error' => $errors['disability_type'] ?? null]) ?>
                        </fieldset>

                        <fieldset class="rounded-3xl border border-slate-200 bg-slate-50 p-4 sm:p-5" data-conditional-group data-target="ethnic" data-value="Yes" aria-describedby="ethnic_status_help">
                            <legend class="px-1 text-sm font-medium text-slate-900">Do you identify yourself as a member of an ethnic minority/indigenous/marginalized group? <span class="text-rose-600">*</span></legend>
                            <p id="ethnic_status_help" class="mt-1 text-sm text-slate-500">Select yes or no to continue.</p>
                            <div class="mt-4 flex flex-wrap gap-3">
                                <?php foreach (['Yes', 'No'] as $value): ?>
                                    <label class="flex cursor-pointer items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm transition hover:border-brand-300 hover:bg-brand-50/40">
                                        <input type="radio" name="ethnic_minority_status" value="<?= esc($value) ?>" class="h-4 w-4 border-slate-300 text-brand-600 focus:ring-brand-500" aria-controls="ethnic_group_name_wrap" aria-expanded="<?= old('ethnic_minority_status') === $value && $value === 'Yes' ? 'true' : 'false' ?>" <?= old('ethnic_minority_status') === $value ? 'checked' : '' ?>>
                                        <span><?= esc($value) ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                            <div id="ethnic_group_name_wrap" class="mt-4 rounded-2xl border border-brand-100 bg-white p-4" data-conditional="ethnic" data-required="true" aria-hidden="<?= old('ethnic_minority_status') === 'Yes' ? 'false' : 'true' ?>" <?= old('ethnic_minority_status') === 'Yes' ? '' : 'hidden' ?>>
                                <label class="mb-2 block text-sm font-medium" for="ethnic_group_name">Please specify the name of the community/group <span class="text-rose-600">*</span></label>
                                <input class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-100" id="ethnic_group_name" name="ethnic_group_name" value="<?= esc(old('ethnic_group_name')) ?>" placeholder="Specify community/group name..." aria-invalid="<?= isset($errors['ethnic_group_name']) ? 'true' : 'false' ?>" <?= old('ethnic_minority_status') === 'Yes' ? 'required' : 'disabled' ?>>
                            </div>
                            <?= view('components/form_errors', ['error' => $errors['ethnic_minority_status'] ?? null]) ?>
                            <?= view('components/form_errors', ['error' => $errors['ethnic_group_name'] ?? null]) ?>
                        </fieldset>
                    </section>

                    <section class="grid gap-5 border-t border-slate-200 pt-2">
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-500">
                            Password must be at least 8 characters and include uppercase, lowercase, number, and special character.
                        </div>

                        <div class="grid gap-5 md:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-sm font-medium" for="signup_password">Password <span class="text-rose-600">*</span></label>
                                <div class="relative">
                                    <input class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 pr-14 text-sm outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-100" id="signup_password" name="password" type="password" autocomplete="new-password" placeholder="Create a strong password" aria-invalid="<?= isset($errors['password']) ? 'true' : 'false' ?>">
                                    <button type="button" class="absolute right-2 top-1/2 grid h-9 w-9 -translate-y-1/2 place-items-center rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-100 focus:outline-none focus:ring-4 focus:ring-brand-100" data-toggle-password="signup_password" aria-label="Show password" aria-pressed="false">
                                        <span class="sr-only">Toggle password visibility</span>
                                        <svg data-icon="eye" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                        <svg data-icon="eye-off" class="hidden h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 3l18 18"/><path d="M10.58 10.58A3 3 0 0 0 12 15a3 3 0 0 0 2.12-.88"/><path d="M9.88 5.07A10.94 10.94 0 0 1 12 5c6.5 0 10 7 10 7a18.34 18.34 0 0 1-2.67 3.89"/><path d="M6.61 6.61C3.95 8.49 2 12 2 12s3.5 7 10 7a10.93 10.93 0 0 0 4.5-.93"/></svg>
                                    </button>
                                </div>
                                <?= view('components/form_errors', ['error' => $errors['password'] ?? null]) ?>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium" for="confirm_password">Confirm password <span class="text-rose-600">*</span></label>
                                <div class="relative">
                                    <input class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 pr-14 text-sm outline-none transition focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-100" id="confirm_password" name="confirm_password" type="password" autocomplete="new-password" placeholder="Repeat your password" aria-invalid="<?= isset($errors['confirm_password']) ? 'true' : 'false' ?>">
                                    <button type="button" class="absolute right-2 top-1/2 grid h-9 w-9 -translate-y-1/2 place-items-center rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-100 focus:outline-none focus:ring-4 focus:ring-brand-100" data-toggle-password="confirm_password" aria-label="Show password" aria-pressed="false">
                                        <span class="sr-only">Toggle password visibility</span>
                                        <svg data-icon="eye" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                        <svg data-icon="eye-off" class="hidden h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 3l18 18"/><path d="M10.58 10.58A3 3 0 0 0 12 15a3 3 0 0 0 2.12-.88"/><path d="M9.88 5.07A10.94 10.94 0 0 1 12 5c6.5 0 10 7 10 7a18.34 18.34 0 0 1-2.67 3.89"/><path d="M6.61 6.61C3.95 8.49 2 12 2 12s3.5 7 10 7a10.93 10.93 0 0 0 4.5-.93"/></svg>
                                    </button>
                                </div>
                                <?= view('components/form_errors', ['error' => $errors['confirm_password'] ?? null]) ?>
                            </div>
                        </div>
                    </section>

                    <div class="grid gap-4 pt-2">
                        <button class="w-full rounded-2xl bg-brand-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-brand-700 disabled:cursor-not-allowed disabled:opacity-60" type="submit">Create Account</button>
                        <p class="text-center text-sm text-slate-500">Already have an account? <a href="<?= site_url('login') ?>" class="font-semibold text-brand-700 hover:text-brand-600">Log in</a></p>
                    </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</main>
<?= $this->endSection() ?>
