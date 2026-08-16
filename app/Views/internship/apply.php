<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?php
$openRound = $openRound ?? null;
$application = $application ?? null;
$user = $user ?? [];
$errors = $errors ?? [];
$selectedUniversity = old('university_id');
?>
<main class="min-h-screen bg-slate-50 text-slate-900">
    <header class="sticky top-0 z-40 border-b border-slate-200/80 bg-white/90 backdrop-blur">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
            <a class="flex items-center gap-3" href="<?= site_url('/') ?>">
                <span class="grid h-11 w-11 place-items-center rounded-2xl bg-slate-950 text-sm font-extrabold text-white">IS</span>
                <span>
                    <span class="block text-base font-semibold tracking-tight">ISSP</span>
                    <span class="block text-xs text-slate-500">Internship portal</span>
                </span>
            </a>
            <nav class="hidden items-center gap-6 text-sm font-medium text-slate-600 md:flex" aria-label="Primary">
                <a class="transition hover:text-slate-950" href="<?= site_url('dashboard') ?>">Dashboard</a>
                <a class="transition hover:text-slate-950" href="<?= site_url('applications') ?>">Applications</a>
                <a class="transition hover:text-slate-950" href="<?= site_url('profile') ?>">Profile</a>
            </nav>
            <div class="flex items-center gap-2">
                <a class="inline-flex items-center rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-300 hover:bg-slate-50" href="<?= site_url('applications') ?>">My Applications</a>
                <form method="post" action="<?= site_url('logout') ?>">
                    <?= csrf_field() ?>
                    <button class="inline-flex items-center rounded-full bg-brand-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-brand-700" type="submit">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
        <?php if (! $openRound): ?>
            <div class="rounded-3xl border border-slate-200 bg-white p-8 text-center shadow-soft">
                <h1 class="text-3xl font-semibold tracking-tight text-slate-950">Apply for Internship</h1>
                <p class="mt-3 text-slate-600">There is currently no open call for applications.</p>
                <div class="mt-6">
                    <a class="inline-flex rounded-2xl bg-brand-600 px-5 py-3 text-sm font-semibold text-white" href="<?= site_url('dashboard') ?>">Back to Dashboard</a>
                </div>
            </div>
        <?php elseif ($application): ?>
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-soft sm:p-8">
                <div class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">Already submitted</div>
                <h1 class="mt-4 text-3xl font-semibold tracking-tight text-slate-950">You already applied for this round</h1>
                <p class="mt-2 text-sm text-slate-500">We’re showing your submitted application instead of the form.</p>
                <div class="mt-6 grid gap-4 md:grid-cols-2">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4"><span class="text-xs uppercase text-slate-500">Round</span><strong class="mt-1 block">Round <?= esc($openRound['round_number']) ?>: <?= esc($openRound['title']) ?></strong></div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4"><span class="text-xs uppercase text-slate-500">Status</span><strong class="mt-1 block"><?= esc($application['status']) ?></strong></div>
                </div>
                <div class="mt-6">
                    <a class="inline-flex rounded-2xl bg-brand-600 px-5 py-3 text-sm font-semibold text-white" href="<?= site_url('applications/' . $application['id']) ?>">View Application Details</a>
                </div>
            </div>
        <?php else: ?>
            <div class="rounded-3xl border border-slate-200 bg-white shadow-soft">
                <div class="border-b border-slate-200 px-6 py-6 sm:px-8">
                    <div class="inline-flex rounded-full bg-brand-50 px-3 py-1 text-xs font-semibold text-brand-700">Open round</div>
                    <h1 class="mt-4 text-3xl font-semibold tracking-tight text-slate-950">Apply for Round <?= esc($openRound['round_number']) ?></h1>
                    <p class="mt-2 text-sm text-slate-500"><?= esc($openRound['title']) ?></p>
                    <p class="mt-3 text-sm leading-6 text-slate-600"><?= esc($openRound['description'] ?? '') ?></p>
                    <p class="mt-3 text-sm font-medium text-slate-700">Opens: <?= esc($openRound['opens_at']) ?> · Closes: <?= esc($openRound['closes_at']) ?></p>
                </div>

                <div class="px-6 py-6 sm:px-8">
                    <?= form_open(site_url('apply'), ['class' => 'grid gap-8', 'id' => 'internship-form']) ?>
                        <?= csrf_field() ?>
                        <section class="grid gap-5">
                            <div>
                                <h2 class="text-lg font-semibold text-slate-950">1. Student Information</h2>
                            </div>
                            <div class="grid gap-5 md:grid-cols-2">
                                <div>
                                    <label class="mb-2 block text-sm font-medium">Full Name *</label>
                                    <input readonly class="w-full rounded-2xl border border-slate-200 bg-slate-100 px-4 py-3 text-sm text-slate-700" value="<?= esc($user['full_name'] ?? '') ?>">
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-medium">Gender Identity *</label>
                                    <input readonly class="w-full rounded-2xl border border-slate-200 bg-slate-100 px-4 py-3 text-sm text-slate-700" value="<?= esc($user['gender_identity'] ?? '') ?>">
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-medium" for="student_id">Student ID *</label>
                                    <input id="student_id" name="student_id" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm" value="<?= esc(old('student_id')) ?>" aria-invalid="<?= isset($errors['student_id']) ? 'true' : 'false' ?>">
                                    <?= view('components/form_errors', ['error' => $errors['student_id'] ?? null]) ?>
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-medium" for="university_id">University *</label>
                                    <select id="university_id" name="university_id" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm" aria-invalid="<?= isset($errors['university_id']) ? 'true' : 'false' ?>">
                                        <option value="">Select university...</option>
                                        <?php foreach ($universities as $uni): ?>
                                            <option value="<?= esc($uni['id']) ?>" <?= (string) old('university_id') === (string) $uni['id'] ? 'selected' : '' ?>><?= esc($uni['name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?= view('components/form_errors', ['error' => $errors['university_id'] ?? null]) ?>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="mb-2 block text-sm font-medium" for="department_id">Department *</label>
                                    <select id="department_id" name="department_id" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm" aria-invalid="<?= isset($errors['department_id']) ? 'true' : 'false' ?>">
                                        <option value="">Select department...</option>
                                        <?php foreach ($departments as $dept): ?>
                                            <option data-university="<?= esc($dept['university_id']) ?>" value="<?= esc($dept['id']) ?>" <?= (string) old('department_id') === (string) $dept['id'] ? 'selected' : '' ?>><?= esc($dept['name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?= view('components/form_errors', ['error' => $errors['department_id'] ?? null]) ?>
                                </div>
                            </div>
                        </section>

                        <section class="grid gap-5">
                            <div><h2 class="text-lg font-semibold text-slate-950">2. Academic Information</h2></div>
                            <div class="grid gap-5 md:grid-cols-3">
                                <div>
                                    <label class="mb-2 block text-sm font-medium" for="current_cgpa">Current CGPA *</label>
                                    <input id="current_cgpa" name="current_cgpa" inputmode="decimal" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm" value="<?= esc(old('current_cgpa')) ?>" aria-invalid="<?= isset($errors['current_cgpa']) ? 'true' : 'false' ?>">
                                    <?= view('components/form_errors', ['error' => $errors['current_cgpa'] ?? null]) ?>
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-medium" for="total_credits">Total Credits *</label>
                                    <input id="total_credits" name="total_credits" inputmode="decimal" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm" value="<?= esc(old('total_credits')) ?>" aria-invalid="<?= isset($errors['total_credits']) ? 'true' : 'false' ?>">
                                    <?= view('components/form_errors', ['error' => $errors['total_credits'] ?? null]) ?>
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-medium" for="earned_credits">Earned Credits *</label>
                                    <input id="earned_credits" name="earned_credits" inputmode="decimal" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm" value="<?= esc(old('earned_credits')) ?>" aria-invalid="<?= isset($errors['earned_credits']) ? 'true' : 'false' ?>">
                                    <?= view('components/form_errors', ['error' => $errors['earned_credits'] ?? null]) ?>
                                </div>
                            </div>
                        </section>

                        <section class="grid gap-5">
                            <div><h2 class="text-lg font-semibold text-slate-950">3. Internship Information</h2></div>
                            <div class="grid gap-5 md:grid-cols-2">
                                <div class="md:col-span-2">
                                    <label class="mb-2 block text-sm font-medium">Internship Type *</label>
                                    <div class="flex flex-wrap gap-3">
                                        <?php foreach (['Industry', 'Capstone'] as $type): ?>
                                            <label class="flex cursor-pointer items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm">
                                                <input type="radio" name="internship_type" value="<?= esc($type) ?>" <?= old('internship_type') === $type ? 'checked' : '' ?>>
                                                <span><?= esc($type) ?></span>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                    <?= view('components/form_errors', ['error' => $errors['internship_type'] ?? null]) ?>
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-medium" for="internship_start_date">Internship Start Date *</label>
                                    <input type="date" id="internship_start_date" name="internship_start_date" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm" value="<?= esc(old('internship_start_date')) ?>" aria-invalid="<?= isset($errors['internship_start_date']) ? 'true' : 'false' ?>">
                                    <?= view('components/form_errors', ['error' => $errors['internship_start_date'] ?? null]) ?>
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-medium" for="internship_end_date">Internship End Date *</label>
                                    <input type="date" id="internship_end_date" name="internship_end_date" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm" value="<?= esc(old('internship_end_date')) ?>" aria-invalid="<?= isset($errors['internship_end_date']) ? 'true' : 'false' ?>">
                                    <?= view('components/form_errors', ['error' => $errors['internship_end_date'] ?? null]) ?>
                                </div>
                                <div id="team_member_wrap" class="md:col-span-2" hidden>
                                    <label class="mb-2 block text-sm font-medium" for="team_member_count">Number of Team Members *</label>
                                    <input type="number" min="1" id="team_member_count" name="team_member_count" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm" value="<?= esc(old('team_member_count')) ?>" aria-invalid="<?= isset($errors['team_member_count']) ? 'true' : 'false' ?>">
                                    <?= view('components/form_errors', ['error' => $errors['team_member_count'] ?? null]) ?>
                                </div>
                            </div>
                        </section>

                        <section class="grid gap-5">
                            <div><h2 class="text-lg font-semibold text-slate-950">4. Supervisor Information</h2></div>
                            <div class="grid gap-5 md:grid-cols-2">
                                <?php foreach ([
                                    ['supervisor_name', 'Supervisor Name'],
                                    ['supervisor_email', 'Supervisor Email', 'email'],
                                    ['supervisor_university', 'Supervisor University'],
                                    ['supervisor_department', 'Supervisor Department'],
                                    ['supervisor_designation', 'Supervisor Designation'],
                                    ['supervisor_phone', 'Supervisor Phone Number', 'tel'],
                                ] as $field): ?>
                                    <div>
                                        <label class="mb-2 block text-sm font-medium" for="<?= esc($field[0]) ?>"><?= esc($field[1]) ?> *</label>
                                        <input id="<?= esc($field[0]) ?>" name="<?= esc($field[0]) ?>" type="<?= esc($field[2] ?? 'text') ?>" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm" value="<?= esc(old($field[0])) ?>" aria-invalid="<?= isset($errors[$field[0]]) ? 'true' : 'false' ?>">
                                        <?= view('components/form_errors', ['error' => $errors[$field[0]] ?? null]) ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </section>

                        <section class="grid gap-5">
                            <div><h2 class="text-lg font-semibold text-slate-950">5. Placement Organization</h2></div>
                            <div class="grid gap-5 md:grid-cols-2">
                                <div class="md:col-span-2">
                                    <label class="mb-2 block text-sm font-medium" for="placement_organization_name">Placement Organization Name *</label>
                                    <input id="placement_organization_name" name="placement_organization_name" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm" value="<?= esc(old('placement_organization_name')) ?>" aria-invalid="<?= isset($errors['placement_organization_name']) ? 'true' : 'false' ?>">
                                    <?= view('components/form_errors', ['error' => $errors['placement_organization_name'] ?? null]) ?>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="mb-2 block text-sm font-medium" for="organization_website_url">Organization Website URL</label>
                                    <input id="organization_website_url" name="organization_website_url" type="url" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm" value="<?= esc(old('organization_website_url')) ?>" aria-invalid="<?= isset($errors['organization_website_url']) ? 'true' : 'false' ?>">
                                    <?= view('components/form_errors', ['error' => $errors['organization_website_url'] ?? null]) ?>
                                </div>
                            </div>
                        </section>

                        <section class="grid gap-5">
                            <div><h2 class="text-lg font-semibold text-slate-950">6. Mentor Information</h2></div>
                            <div class="grid gap-5 md:grid-cols-2">
                                <div>
                                    <label class="mb-2 block text-sm font-medium" for="mentor_name">Mentor Name *</label>
                                    <input id="mentor_name" name="mentor_name" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm" value="<?= esc(old('mentor_name')) ?>" aria-invalid="<?= isset($errors['mentor_name']) ? 'true' : 'false' ?>">
                                    <?= view('components/form_errors', ['error' => $errors['mentor_name'] ?? null]) ?>
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-medium" for="mentor_email">Mentor Email *</label>
                                    <input id="mentor_email" name="mentor_email" type="email" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm" value="<?= esc(old('mentor_email')) ?>" aria-invalid="<?= isset($errors['mentor_email']) ? 'true' : 'false' ?>">
                                    <?= view('components/form_errors', ['error' => $errors['mentor_email'] ?? null]) ?>
                                </div>
                            </div>
                        </section>

                        <button class="w-full rounded-2xl bg-brand-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-brand-700 disabled:opacity-60" type="submit">Submit Application</button>
                    <?= form_close() ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const university = document.getElementById('university_id');
  const department = document.getElementById('department_id');
  const teamWrap = document.getElementById('team_member_wrap');
  const internshipTypeInputs = Array.from(document.querySelectorAll('input[name="internship_type"]'));
  const deptOptions = department ? Array.from(department.querySelectorAll('option[data-university]')) : [];

  function syncDepartments() {
    if (!university || !department) return;
    const universityId = university.value;
    deptOptions.forEach(function (option) {
      option.hidden = universityId && option.getAttribute('data-university') !== universityId;
      option.disabled = universityId && option.getAttribute('data-university') !== universityId;
    });
    if (department.selectedOptions[0] && department.selectedOptions[0].hidden) {
      department.value = '';
    }
  }

  function syncTeam() {
    const checked = internshipTypeInputs.find(function (input) { return input.checked; });
    const capstone = checked && checked.value === 'Capstone';
    if (teamWrap) {
      teamWrap.hidden = !capstone;
      const field = teamWrap.querySelector('input');
      if (field) {
        field.disabled = !capstone;
        if (!capstone) field.value = '';
        if (capstone) field.setAttribute('required', 'required');
        else field.removeAttribute('required');
      }
    }
  }

  if (university) university.addEventListener('change', syncDepartments);
  internshipTypeInputs.forEach(function (input) { input.addEventListener('change', syncTeam); });
  syncDepartments();
  syncTeam();
});
</script>
<?= $this->endSection() ?>
