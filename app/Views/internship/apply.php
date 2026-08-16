<?= $this->extend('layouts/authenticated') ?>
<?= $this->section('content') ?>
<?php $currentPage = 'applications'; ?>
<?php
$openRound = $openRound ?? null;
$application = $application ?? null;
$user = $user ?? [];
$errors = $errors ?? [];
$eligibility = $eligibility ?? ['state' => 'pending', 'message' => '', 'credit_completion_percentage' => '0.00', 'cgpa_failed' => false, 'credit_failed' => false];
$selectedUniversity = old('university_id');
$selectedDepartment = old('department');
$selectedOtherDepartment = old('other_department');
$selectedUniversityName = '';
$selectedUniversityType = '';
$selectedDepartmentName = '';
foreach ($universities ?? [] as $uni) {
    if ((string) ($uni['id'] ?? '') === (string) $selectedUniversity) {
        $selectedUniversityName = (string) ($uni['name'] ?? '');
        $selectedUniversityType = (string) ($uni['type'] ?? '');
        break;
    }
}
$departmentOptions = [
    'Computer Science and Engineering (CSE)',
    'Computer Science (CS)',
    'Computer Engineering (CE)',
    'Information Technology (IT)',
    'Information and Communication Technology (ICT)',
    'Software Engineering (SWE)',
    'Software Engineering and Information Systems',
    'Data Science (DS)',
    'Cyber Security',
    'Cyber Security Engineering',
    'Robotics and Mechatronics Engineering',
    'Other',
];
?>
<div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
    <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-soft">
        <div class="flex flex-col gap-3">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-700">Dashboard / Apply / <?= esc((string) ($openRound['round_code'] ?? '')) ?></p>
                <h1 class="mt-2 text-3xl font-semibold tracking-tight text-slate-950">Internship Application</h1>
                <p class="mt-2 text-sm text-slate-500">
                    <?= esc((string) ($openRound['title'] ?? '')) ?>
                    <?php if (! empty($openRound['round_code'])): ?>
                        <span class="text-slate-400">·</span> Round <?= esc((string) $openRound['round_code']) ?>
                    <?php endif; ?>
                </p>
            </div>
        </div>
    </section>

    <div class="mt-4">
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
                <p class="mt-2 text-sm text-slate-500">Weâ€™re showing your submitted application instead of the form.</p>
                <div class="mt-6 grid gap-4 md:grid-cols-2">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4"><span class="text-xs uppercase text-slate-500">Round</span><strong class="mt-1 block">Round <?= esc($openRound['round_number']) ?>: <?= esc($openRound['title']) ?></strong></div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4"><span class="text-xs uppercase text-slate-500">Status</span><strong class="mt-1 block"><?= esc($application['status']) ?></strong></div>
                </div>
                <div class="mt-6">
                    <a class="inline-flex rounded-2xl bg-brand-600 px-5 py-3 text-sm font-semibold text-white" href="<?= site_url('applications/' . $application['application_code']) ?>">View Application Details</a>
                </div>
            </div>
        <?php else: ?>
            <div class="rounded-3xl border border-slate-200 bg-white shadow-soft">
                <div class="border-b border-slate-200 px-6 py-6 sm:px-8">
                    <div class="inline-flex rounded-full bg-brand-50 px-3 py-1 text-xs font-semibold text-brand-700">Open round</div>
                    <h1 class="mt-4 text-3xl font-semibold tracking-tight text-slate-950">Apply for Round <?= esc($openRound['round_number']) ?></h1>
                    <p class="mt-2 text-sm text-slate-500"><?= esc($openRound['title']) ?></p>
                    <p class="mt-3 text-sm leading-6 text-slate-600"><?= esc($openRound['description'] ?? '') ?></p>
                    <p class="mt-3 text-sm font-medium text-slate-700">Opens: <?= esc($openRound['opens_at']) ?> Â· Closes: <?= esc($openRound['closes_at']) ?></p>
                </div>

                <div class="px-6 py-6 sm:px-8">
                    <?= form_open(site_url('apply/' . ($openRound['round_code'] ?? '')), ['class' => 'grid gap-8', 'id' => 'internship-form']) ?>
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
                                <div class="relative" data-combobox="university">
                                    <label class="mb-2 block text-sm font-medium" id="university_id_label" for="university_id_search">University *</label>
                                    <input type="hidden" id="university_id" name="university_id" value="<?= esc($selectedUniversity) ?>">
                                    <input
                                        type="text"
                                        id="university_id_search"
                                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 pr-10 text-sm transition focus:outline-none focus:ring-2 focus:ring-brand-500"
                                        placeholder="Search and select a university..."
                                        autocomplete="off"
                                        role="combobox"
                                        aria-autocomplete="list"
                                        aria-expanded="false"
                                        aria-controls="university_id_listbox"
                                        aria-labelledby="university_id_label"
                                        value="<?= esc($selectedUniversityName !== '' ? $selectedUniversityName : '') ?>"
                                    >
                                    <button type="button" class="pointer-events-none absolute right-3 top-[42px] text-slate-400" tabindex="-1" aria-hidden="true">
                                        <svg viewBox="0 0 20 20" fill="none" class="h-4 w-4">
                                            <path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </button>
                                    <div class="absolute z-30 mt-2 hidden w-full overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-lg" data-combobox-panel>
                                        <ul id="university_id_listbox" role="listbox" class="max-h-72 overflow-auto py-1" data-combobox-list>
                                            <li class="hidden px-4 py-3 text-sm text-slate-500" data-combobox-empty>No university found</li>
                                        </ul>
                                    </div>
                                    <?= view('components/form_errors', ['error' => $errors['university_id'] ?? null]) ?>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="mb-2 block text-sm font-medium" for="department">Department *</label>
                                    <select id="department" name="department" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm" aria-invalid="<?= isset($errors['department']) ? 'true' : 'false' ?>">
                                        <option value="">Select department...</option>
                                        <?php foreach ($departmentOptions as $departmentOption): ?>
                                            <option value="<?= esc($departmentOption) ?>" <?= (string) $selectedDepartment === (string) $departmentOption ? 'selected' : '' ?>><?= esc($departmentOption) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?= view('components/form_errors', ['error' => $errors['department'] ?? null]) ?>
                                </div>
                                <div id="other_department_wrap" class="md:col-span-2" hidden>
                                    <label class="mb-2 block text-sm font-medium" for="other_department">Specify Department *</label>
                                    <input id="other_department" name="other_department" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm" maxlength="150" placeholder="Enter your department name..." value="<?= esc($selectedOtherDepartment) ?>" aria-invalid="<?= isset($errors['other_department']) ? 'true' : 'false' ?>">
                                    <?= view('components/form_errors', ['error' => $errors['other_department'] ?? null]) ?>
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
                            <div id="eligibility_notice" class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600" aria-live="polite">
                                Please enter your academic information to check eligibility.
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

                        <div id="declaration_block" class="grid gap-4" hidden aria-hidden="true">
                            <label class="flex cursor-pointer items-start gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700">
                                <input id="information_declaration" name="information_declaration" value="1" type="checkbox" class="mt-1 h-4 w-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                                <span>I hereby declare that all information provided in this application is complete, accurate and correct. I understand that providing false or misleading information may result in rejection or cancellation of my application.</span>
                            </label>
                            <input type="hidden" name="information_declaration_present" value="1">
                        </div>
                        <button id="submit_application_btn" class="w-full rounded-2xl bg-brand-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-brand-700 disabled:opacity-60" type="submit" disabled hidden>Submit Application</button>
                    <?= form_close() ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const universityRoot = document.querySelector('[data-combobox="university"]');
  const university = document.getElementById('university_id');
  const universitySearch = document.getElementById('university_id_search');
  const department = document.getElementById('department');
  const otherDepartmentWrap = document.getElementById('other_department_wrap');
  const otherDepartment = document.getElementById('other_department');
  const teamWrap = document.getElementById('team_member_wrap');
  const internshipTypeInputs = Array.from(document.querySelectorAll('input[name="internship_type"]'));
  const cgpaField = document.getElementById('current_cgpa');
  const totalCreditsField = document.getElementById('total_credits');
  const earnedCreditsField = document.getElementById('earned_credits');
  const eligibilityNotice = document.getElementById('eligibility_notice');
  const declarationBlock = document.getElementById('declaration_block');
  const declarationCheckbox = document.getElementById('information_declaration');
  const submitButton = document.getElementById('submit_application_btn');
  const universityData = <?= json_encode(array_map(static function (array $uni): array {
      return ['id' => (string) $uni['id'], 'name' => (string) $uni['name'], 'type' => (string) ($uni['type'] ?? '')];
  }, $universities ?? []), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;

  function createCombobox(root, input, options, emptyMessage) {
    if (!root || !input) return null;
    const panel = root.querySelector('[data-combobox-panel]');
    const list = root.querySelector('[data-combobox-list]');
    const empty = root.querySelector('[data-combobox-empty]');
    const hidden = root.querySelector('input[type="hidden"]');
    let filtered = options.slice();
    let activeIndex = -1;

    function render(query) {
      const normalized = (query || '').trim().toLowerCase();
      filtered = options.filter(function (item) {
        const name = item.name.toLowerCase();
        const type = (item.type || '').toLowerCase();
        return name.includes(normalized) || type.includes(normalized);
      });
      list.querySelectorAll('[data-option-row]').forEach(function (row) { row.remove(); });
      filtered.forEach(function (item, index) {
        const row = document.createElement('li');
        row.setAttribute('role', 'option');
        row.setAttribute('tabindex', '-1');
        row.dataset.optionRow = 'true';
        row.dataset.value = item.id;
        row.className = 'cursor-pointer px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 focus:bg-slate-50 focus:outline-none';
        row.innerHTML = '<span class="block font-medium">' + item.name + '</span>' + (item.type ? '<span class="mt-1 block text-xs text-slate-500">' + item.type + ' University</span>' : '');
        list.insertBefore(row, empty);
        if (index === 0) activeIndex = 0;
      });
      empty.classList.toggle('hidden', filtered.length > 0);
      panel.classList.remove('hidden');
      input.setAttribute('aria-expanded', 'true');
    }

    function close() {
      panel.classList.add('hidden');
      input.setAttribute('aria-expanded', 'false');
      activeIndex = -1;
    }

    function select(item) {
      hidden.value = item.id;
      input.value = item.name + (item.type ? ' — ' + item.type + ' University' : '');
      input.dataset.selectedId = item.id;
      hidden.dispatchEvent(new Event('change', { bubbles: true }));
      close();
    }

    function setOptions(nextOptions, selectedId) {
      options = nextOptions.slice();
      if (selectedId) {
        const selected = options.find(function (item) {
          return item.id === String(selectedId);
        });
        if (selected) {
          hidden.value = selected.id;
          input.value = selected.name + (selected.type ? ' — ' + selected.type + ' University' : '');
          input.dataset.selectedId = selected.id;
        } else {
          hidden.value = '';
          input.value = '';
          input.dataset.selectedId = '';
        }
      } else {
        hidden.value = '';
        input.value = '';
        input.dataset.selectedId = '';
      }
      render(input.value);
    }

    input.addEventListener('focus', function () { render(input.value); });
    input.addEventListener('input', function () {
      hidden.value = '';
      input.dataset.selectedId = '';
      render(input.value);
    });
    input.addEventListener('keydown', function (event) {
      if (event.key === 'Escape') {
        close();
        input.blur();
      }
      if (event.key === 'Enter') {
        const item = filtered[activeIndex] || filtered[0];
        if (item) {
          event.preventDefault();
          select(item);
        }
      }
      if (event.key === 'ArrowDown') {
        event.preventDefault();
        activeIndex = Math.min(activeIndex + 1, Math.max(filtered.length - 1, 0));
      }
      if (event.key === 'ArrowUp') {
        event.preventDefault();
        activeIndex = Math.max(activeIndex - 1, 0);
      }
    });
    list.addEventListener('click', function (event) {
      const row = event.target.closest('[data-option-row]');
      if (!row) return;
      const item = options.find(function (candidate) {
        return candidate.id === String(row.dataset.value);
      });
      if (item) select(item);
    });
    document.addEventListener('click', function (event) {
      if (!root.contains(event.target)) close();
    });

    render('');
    return { setOptions: setOptions, close: close };
  }

  const universityCombobox = createCombobox(universityRoot, universitySearch, universityData, 'No university found');

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

  function syncDepartmentField() {
    const isOther = department && department.value === 'Other';
    if (otherDepartmentWrap) {
      otherDepartmentWrap.hidden = !isOther;
      otherDepartmentWrap.setAttribute('aria-hidden', isOther ? 'false' : 'true');
    }
    if (otherDepartment) {
      otherDepartment.disabled = !isOther;
      if (isOther) {
        otherDepartment.setAttribute('required', 'required');
      } else {
        otherDepartment.removeAttribute('required');
        otherDepartment.value = '';
      }
    }
  }

  function evaluateEligibility() {
    const cgpa = parseFloat(cgpaField ? cgpaField.value : '');
    const totalCredits = parseFloat(totalCreditsField ? totalCreditsField.value : '');
    const earnedCredits = parseFloat(earnedCreditsField ? earnedCreditsField.value : '');

    const hasAny = (cgpaField && cgpaField.value !== '') || (totalCreditsField && totalCreditsField.value !== '') || (earnedCreditsField && earnedCreditsField.value !== '');
    const validNumbers = !Number.isNaN(cgpa) && !Number.isNaN(totalCredits) && !Number.isNaN(earnedCredits);

    if (!hasAny) {
      eligibilityNotice.className = 'rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600';
      eligibilityNotice.textContent = 'Please enter your academic information to check eligibility.';
      declarationBlock.hidden = true;
      declarationBlock.setAttribute('aria-hidden', 'true');
      submitButton.hidden = true;
      submitButton.disabled = true;
      if (declarationCheckbox) declarationCheckbox.checked = false;
      return;
    }

    if (!validNumbers || totalCredits <= 0 || earnedCredits < 0 || earnedCredits > totalCredits) {
      eligibilityNotice.className = 'rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900';
      eligibilityNotice.textContent = 'Please enter valid credit information. Earned credits cannot exceed total credits, and total credits must be greater than zero.';
      declarationBlock.hidden = true;
      declarationBlock.setAttribute('aria-hidden', 'true');
      submitButton.hidden = true;
      submitButton.disabled = true;
      if (declarationCheckbox) declarationCheckbox.checked = false;
      return;
    }

    const creditPercentage = (earnedCredits / totalCredits) * 100;
    const cgpaFailed = cgpa < 2.75;
    const creditFailed = earnedCredits * 100 < totalCredits * 75;

    if (cgpaFailed || creditFailed) {
      eligibilityNotice.className = 'rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-900';
      eligibilityNotice.textContent = cgpaFailed && creditFailed
        ? 'You are not eligible to apply. A minimum CGPA of 2.75 and completion of at least 75% of total program credits are required.'
        : (cgpaFailed
          ? 'You are not eligible to apply because a minimum CGPA of 2.75 is required.'
          : 'You are not eligible to apply because you must complete at least 75% of your total program credits.');
      declarationBlock.hidden = true;
      declarationBlock.setAttribute('aria-hidden', 'true');
      submitButton.hidden = true;
      submitButton.disabled = true;
      if (declarationCheckbox) declarationCheckbox.checked = false;
      return;
    }

    eligibilityNotice.className = 'rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-900';
    eligibilityNotice.textContent = 'You meet the academic eligibility requirements for this application. Credit completed: ' + creditPercentage.toFixed(2) + '%';
    declarationBlock.hidden = false;
    declarationBlock.setAttribute('aria-hidden', 'false');
    submitButton.hidden = false;
    submitButton.disabled = ! (declarationCheckbox && declarationCheckbox.checked);
  }

  if (universityCombobox) {
    const selectedUniversity = university.value;
    if (selectedUniversity) {
      const selectedItem = universityData.find(function (item) {
        return item.id === String(selectedUniversity);
      });
      if (selectedItem) {
        universitySearch.value = selectedItem.name + (selectedItem.type ? ' — ' + selectedItem.type + ' University' : '');
      }
    }
  }

  if (universitySearch) {
    universitySearch.addEventListener('input', function () {
      const match = universityData.find(function (item) {
        return item.name.toLowerCase() === universitySearch.value.trim().toLowerCase();
      });
      university.value = match ? match.id : '';
      if (department) {
        // keep department untouched when university changes
      }
      if (university.value === '') {
        universitySearch.dataset.selectedId = '';
      }
    });
  }

  if (department) department.addEventListener('change', syncDepartmentField);
  if (cgpaField) cgpaField.addEventListener('input', evaluateEligibility);
  if (totalCreditsField) totalCreditsField.addEventListener('input', evaluateEligibility);
  if (earnedCreditsField) earnedCreditsField.addEventListener('input', evaluateEligibility);
  if (declarationCheckbox) declarationCheckbox.addEventListener('change', evaluateEligibility);

  internshipTypeInputs.forEach(function (input) { input.addEventListener('change', syncTeam); });
  syncTeam();
  syncDepartmentField();
  evaluateEligibility();
});
</script>
<?= $this->endSection() ?>









