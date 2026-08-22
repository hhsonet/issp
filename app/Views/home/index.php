<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<main class="min-h-screen bg-slate-50 text-slate-900">
    <a class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-50 focus:rounded-full focus:bg-white focus:px-4 focus:py-2 focus:text-sm focus:shadow-soft" href="#main-content">Skip to content</a>

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
                <a class="transition hover:text-slate-950" href="#overview">Overview</a>
                <a class="transition hover:text-slate-950" href="#tracks">Tracks</a>
                <a class="transition hover:text-slate-950" href="#eligibility">Eligibility</a>
                <a class="transition hover:text-slate-950" href="#downloads">Downloads</a>
                <a class="transition hover:text-slate-950" href="#support">Support</a>
            </nav>

            <div class="flex items-center gap-2">
                <a class="inline-flex items-center rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-300 hover:bg-slate-50" href="<?= site_url('/') . '#login' ?>">Sign In</a>
                <a class="inline-flex items-center rounded-full bg-brand-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-brand-700" href="<?= site_url('signup') ?>">Create Account</a>
            </div>
        </div>
    </header>

    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(37,99,235,0.12),_transparent_40%),radial-gradient(circle_at_top_right,_rgba(15,23,42,0.08),_transparent_30%)]"></div>
        <div class="relative mx-auto grid max-w-7xl gap-10 px-4 py-14 sm:px-6 lg:grid-cols-[1.2fr_0.8fr] lg:px-8 lg:py-20">
            <div class="max-w-3xl">
                <div class="inline-flex items-center rounded-full border border-brand-100 bg-brand-50 px-3 py-1 text-xs font-semibold text-brand-700">
                    ICSETEP Domestic Internship
                </div>
                <h1 class="mt-5 text-4xl font-semibold tracking-tight text-slate-950 sm:text-5xl lg:text-6xl">
                    Apply for the ICSETEP domestic internship program.
                </h1>
                <p class="mt-5 max-w-2xl text-base leading-7 text-slate-600 sm:text-lg">
                    Funded by the Asian Development Bank, implemented through the University Grants Commission of Bangladesh, and managed by the Project Management Unit, this portal supports CSE and IT students applying for domestic internship opportunities nationwide.
                </p>

                <div class="mt-8 flex flex-wrap gap-3">
                    <a class="inline-flex items-center rounded-full bg-brand-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-brand-700" href="<?= site_url('signup') ?>">Create Account</a>
                    <a class="inline-flex items-center rounded-full border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50" href="<?= site_url('/') . '#login' ?>">Sign In</a>
                    <a class="inline-flex items-center rounded-full border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50" href="#downloads">View Documents</a>
                </div>

                <dl class="mt-10 grid gap-4 sm:grid-cols-3">
                    <?php foreach ([
                        ['label' => 'Stipend', 'value' => 'BDT 15,000'],
                        ['label' => 'Duration', 'value' => '3 months'],
                        ['label' => 'Coverage', 'value' => 'UGC universities'],
                    ] as $item): ?>
                        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-soft">
                            <dt class="text-sm text-slate-500"><?= esc($item['label']) ?></dt>
                            <dd class="mt-2 text-lg font-semibold text-slate-950"><?= esc($item['value']) ?></dd>
                        </div>
                    <?php endforeach; ?>
                </dl>
            </div>

            <div class="lg:pl-4" id="login">
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-soft">
                    <div class="inline-flex rounded-full bg-brand-50 px-3 py-1 text-xs font-semibold text-brand-700">Sign in</div>
                    <h2 class="mt-4 text-2xl font-semibold tracking-tight text-slate-950">Welcome back</h2>
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
                                    <svg data-icon="eye" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                    <svg data-icon="eye-off" class="hidden h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 3l18 18"/><path d="M10.58 10.58A3 3 0 0 0 12 15a3 3 0 0 0 2.12-.88"/><path d="M9.88 5.07A10.94 10.94 0 0 1 12 5c6.5 0 10 7 10 7a18.34 18.34 0 0 1-2.67 3.89"/><path d="M6.61 6.61C3.95 8.49 2 12 2 12s3.5 7 10 7a10.93 10.93 0 0 0 4.5-.93"/></svg>
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
        </div>
    </section>

    <section id="overview" class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-brand-700">Program overview</p>
            <h2 class="mt-3 text-3xl font-semibold tracking-tight text-slate-950">What the program offers</h2>
            <p class="mt-4 text-slate-600">
                ICSETEP Domestic Internship is a 3-month stipend-supported placement program for undergraduate students in CSE and IT fields across UGC-affiliated universities in Bangladesh, with separate allocations managed by DU, BUET, and JUST.
            </p>
        </div>

        <div class="mt-8 grid gap-4 md:grid-cols-3">
            <?php foreach ([
                ['title' => 'Stipend support', 'text' => 'BDT 15,000 total — BDT 5,000 per month for the 3-month internship period.'],
                ['title' => 'Nationwide access', 'text' => 'Open to students from all UGC-affiliated universities except the three PIU universities with their own allocations.'],
                ['title' => 'Two application tracks', 'text' => 'Choose either Industry Attachment or Industry-Collaboration Capstone Project.'],
            ] as $card): ?>
                <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-soft">
                    <h3 class="text-lg font-semibold text-slate-950"><?= esc($card['title']) ?></h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600"><?= esc($card['text']) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <section id="tracks" class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <div class="max-w-2xl">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-brand-700">Tracks</p>
            <h2 class="mt-3 text-3xl font-semibold tracking-tight text-slate-950">Two ways to participate</h2>
            <p class="mt-4 text-slate-600">Applicants choose one domestic internship category when they apply.</p>
        </div>

        <div class="mt-8 grid gap-4 lg:grid-cols-2">
            <article class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-soft">
                <p class="text-sm font-semibold uppercase tracking-[0.16em] text-brand-700">Track 1</p>
                <h3 class="mt-3 text-2xl font-semibold text-slate-950">Industry Attachment</h3>
                <p class="mt-4 text-sm leading-6 text-slate-600">
                    A full-time placement of at least 3 months in a software or IT organisation, jointly supervised by an industry mentor and an academic supervisor.
                </p>
                <ul class="mt-5 space-y-3 text-sm text-slate-600">
                    <li>Host organisation must be recommended by the university.</li>
                    <li>Should be a BCS, BASIS, BACCO, or ISPAB member, a licensed Hi-Tech Park entity, or a renowned national or multinational office in Bangladesh.</li>
                    <li>Must have 50+ employees and provide a safe, gender-responsive work environment.</li>
                    <li>A consent letter from the host organisation is required.</li>
                </ul>
            </article>

            <article class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-soft">
                <p class="text-sm font-semibold uppercase tracking-[0.16em] text-brand-700">Track 2</p>
                <h3 class="mt-3 text-2xl font-semibold text-slate-950">Industry-Collaboration Capstone Project</h3>
                <p class="mt-4 text-sm leading-6 text-slate-600">
                    A team-based, year-long project that solves a real industry problem, jointly supervised by an academic supervisor and an industry mentor.
                </p>
                <ul class="mt-5 space-y-3 text-sm text-slate-600">
                    <li>Each team member applies individually.</li>
                    <li>Teams with at least one female student are encouraged.</li>
                    <li>Female-inclusive teams receive tiebreaker preference in scoring.</li>
                </ul>
            </article>
        </div>
    </section>

    <section id="eligibility" class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <div class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-soft sm:p-8">
            <div class="max-w-2xl">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-brand-700">Eligibility</p>
                <h2 class="mt-3 text-3xl font-semibold tracking-tight text-slate-950">Who can apply</h2>
            </div>

            <div class="mt-8 grid gap-4 md:grid-cols-2">
                <?php foreach ([
                    'Regular undergraduate student in a CSE or IT programme',
                    'Registered for the internship or capstone course',
                    'Completed at least 75% of required graduation credits',
                    'Minimum CGPA of 2.75 on a 4.00 scale',
                    'May apply to only one domestic internship category',
                    'Must not be currently receiving another internship stipend or employed',
                ] as $item): ?>
                    <div class="flex items-start gap-3 rounded-3xl border border-slate-200 bg-slate-50 p-5">
                        <span class="mt-0.5 grid h-6 w-6 shrink-0 place-items-center rounded-full bg-emerald-50 text-xs font-bold text-emerald-700">✓</span>
                        <p class="text-sm leading-6 text-slate-700"><?= esc($item) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section id="downloads" class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <div class="max-w-2xl">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-brand-700">Documents</p>
            <h2 class="mt-3 text-3xl font-semibold tracking-tight text-slate-950">Important documents</h2>
        </div>

        <div class="mt-8 overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-soft">
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-[0.16em] text-slate-500">
                        <tr>
                            <th class="px-5 py-4 font-semibold">Document</th>
                            <th class="px-5 py-4 font-semibold">Description</th>
                            <th class="px-5 py-4 font-semibold">Status</th>
                            <th class="px-5 py-4 font-semibold">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        <?php foreach ([
                            ['title' => 'Application Guidelines', 'state' => 'Available'],
                            ['title' => 'Project Operations Manual', 'state' => 'Available'],
                            ['title' => 'Evaluation Guidelines', 'state' => 'Coming soon'],
                        ] as $doc): ?>
                            <tr>
                                <td class="px-5 py-4 font-semibold text-slate-950"><?= esc($doc['title']) ?></td>
                                <td class="px-5 py-4 text-slate-600">Program documents, reporting templates, and selection guidance for the domestic internship cycle.</td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold <?= $doc['state'] === 'Coming soon' ? 'bg-amber-50 text-amber-700' : 'bg-emerald-50 text-emerald-700' ?>">
                                        <?= esc($doc['state']) ?>
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <a class="inline-flex items-center rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-brand-700 transition hover:border-slate-300 hover:bg-slate-50" href="<?= site_url('signup') ?>">
                                        Apply now
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section id="support" class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <div class="rounded-[2rem] border border-slate-200 bg-slate-950 p-8 text-white shadow-soft sm:p-10">
            <div class="max-w-2xl">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-300">Support</p>
                <h2 class="mt-3 text-3xl font-semibold tracking-tight">Need help getting started?</h2>
                <p class="mt-4 text-slate-300">If you need help with eligibility, document submission, or internship track selection, use the portal support channel for assistance.</p>
                <div class="mt-6 flex flex-wrap gap-3 text-sm text-slate-200">
                    <span class="rounded-full bg-white/10 px-4 py-2">support@issp.gov.bd</span>
                    <span class="rounded-full bg-white/10 px-4 py-2">+880 2 0000 0000</span>
                </div>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a class="inline-flex items-center rounded-full bg-white px-5 py-3 text-sm font-semibold text-slate-950 transition hover:bg-slate-100" href="<?= site_url('/') . '#login' ?>">Sign In</a>
                    <a class="inline-flex items-center rounded-full border border-white/15 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/10" href="<?= site_url('signup') ?>">Create Account</a>
                </div>
            </div>
        </div>
    </section>

    <footer class="border-t border-slate-200 bg-white">
        <div class="mx-auto grid max-w-7xl gap-8 px-4 py-10 text-sm text-slate-600 sm:px-6 lg:grid-cols-4 lg:px-8">
            <div class="lg:col-span-2">
                <div class="flex items-center gap-3">
                    <span class="grid h-10 w-10 place-items-center rounded-2xl bg-slate-950 text-sm font-extrabold text-white">IS</span>
                    <div>
                        <p class="font-semibold text-slate-950">ICSETEP Domestic Internship Portal</p>
                        <p class="text-slate-500">Managed through UGC Bangladesh and PMU</p>
                    </div>
                </div>
                <p class="mt-4 max-w-xl">Secure registration, application tracking, and document access for the ICSETEP domestic internship cycle.</p>
            </div>

            <div>
                <p class="font-semibold text-slate-950">Quick links</p>
                <div class="mt-3 grid gap-2">
                    <a class="transition hover:text-slate-950" href="<?= site_url('/') . '#login' ?>">Sign In</a>
                    <a class="transition hover:text-slate-950" href="<?= site_url('signup') ?>">Create Account</a>
                    <a class="transition hover:text-slate-950" href="#downloads">Downloads</a>
                </div>
            </div>

            <div>
                <p class="font-semibold text-slate-950">Contact</p>
                <div class="mt-3 grid gap-2">
                    <span>support@issp.gov.bd</span>
                    <span>+880 2 0000 0000</span>
                    <span>&copy; <?= date('Y') ?> ISSP</span>
                </div>
            </div>
        </div>
    </footer>
</main>
<?= $this->endSection() ?>

