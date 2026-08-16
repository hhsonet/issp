<?= $this->extend('layouts/authenticated') ?>
<?= $this->section('content') ?>
<?php
$application = $application ?? [];
$universities = $universities ?? [];
$errors = session('errors') ?? [];
$currentPage = 'applications';
$selectedUniversity = old('university_id', $application['university_id'] ?? '');
$selectedDepartment = old('department', $application['department'] ?? '');
$selectedOtherDepartment = old('other_department', $application['other_department'] ?? '');
?>
<div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
    <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-soft">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-700">Dashboard / My Applications / <?= esc((string) ($application['application_code'] ?? '')) ?> / Edit</p>
        <h1 class="mt-2 text-3xl font-semibold tracking-tight text-slate-950">Edit Application</h1>
        <p class="mt-2 text-sm text-slate-500"><?= esc((string) ($application['round_title'] ?? 'Application')) ?></p>
    </section>

    <div class="mt-4 grid gap-6 xl:grid-cols-[minmax(0,1fr)_320px]">
        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-soft">
            <?= form_open(site_url('applications/' . ($application['application_code'] ?? '') . '/edit'), ['class' => 'grid gap-8', 'id' => 'application-edit-form']) ?>
                <?= csrf_field() ?>

                <div class="grid gap-5">
                    <h2 class="text-lg font-semibold text-slate-950">Student information</h2>
                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-medium" for="student_id">Student ID *</label>
                            <input class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm" id="student_id" name="student_id" value="<?= esc(old('student_id', $application['student_id'] ?? '')) ?>">
                        </div>
                        <div class="relative" data-combobox="university">
                            <label class="mb-2 block text-sm font-medium" id="university_id_label" for="university_id_search">University *</label>
                            <input type="hidden" id="university_id" name="university_id" value="<?= esc($selectedUniversity) ?>">
                            <input type="text" id="university_id_search" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 pr-10 text-sm" placeholder="Search and select a university..." autocomplete="off" value="<?= esc((string) ($application['university_name'] ?? '')) ?><?= ! empty($application['university_type']) ? ' — ' . esc((string) $application['university_type']) . ' University' : '' ?>">
                            <button type="button" class="pointer-events-none absolute right-3 top-[42px] text-slate-400" tabindex="-1" aria-hidden="true">⌄</button>
                            <div class="absolute z-30 mt-2 hidden w-full overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-lg" data-combobox-panel>
                                <ul id="university_id_listbox" role="listbox" class="max-h-72 overflow-auto py-1" data-combobox-list>
                                    <li class="hidden px-4 py-3 text-sm text-slate-500" data-combobox-empty>No university found</li>
                                </ul>
                            </div>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium" for="department">Department *</label>
                            <select class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm" id="department" name="department">
                                <option value="">Select department...</option>
                                <?php foreach ([
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
                                ] as $option): ?>
                                    <option value="<?= esc($option) ?>" <?= (string) $selectedDepartment === (string) $option ? 'selected' : '' ?>><?= esc($option) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div id="other_department_wrap" class="hidden">
                            <label class="mb-2 block text-sm font-medium" for="other_department">Specify Department *</label>
                            <input class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm" id="other_department" name="other_department" value="<?= esc($selectedOtherDepartment) ?>">
                        </div>
                    </div>
                </div>

                <div class="grid gap-5">
                    <h2 class="text-lg font-semibold text-slate-950">Academic information</h2>
                    <div class="grid gap-5 md:grid-cols-3">
                        <?php foreach (['current_cgpa' => 'Current CGPA', 'total_credits' => 'Total Credits', 'earned_credits' => 'Earned Credits'] as $name => $label): ?>
                            <div>
                                <label class="mb-2 block text-sm font-medium" for="<?= esc($name) ?>"><?= esc($label) ?> *</label>
                                <input class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm" id="<?= esc($name) ?>" name="<?= esc($name) ?>" value="<?= esc(old($name, $application[$name] ?? '')) ?>">
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="grid gap-5">
                    <h2 class="text-lg font-semibold text-slate-950">Internship information</h2>
                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-medium">Internship Type *</label>
                            <div class="flex gap-3">
                                <?php foreach (['Industry', 'Capstone'] as $type): ?>
                                    <label class="flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm">
                                        <input type="radio" name="internship_type" value="<?= esc($type) ?>" <?= old('internship_type', $application['internship_type'] ?? '') === $type ? 'checked' : '' ?>>
                                        <span><?= esc($type) ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium" for="team_member_count">Team Member Count</label>
                            <input class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm" id="team_member_count" name="team_member_count" value="<?= esc(old('team_member_count', $application['team_member_count'] ?? '')) ?>">
                        </div>
                    </div>
                    <div class="grid gap-5 md:grid-cols-2">
                        <div><label class="mb-2 block text-sm font-medium" for="internship_start_date">Start Date *</label><input type="date" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm" id="internship_start_date" name="internship_start_date" value="<?= esc(old('internship_start_date', $application['internship_start_date'] ?? '')) ?>"></div>
                        <div><label class="mb-2 block text-sm font-medium" for="internship_end_date">End Date *</label><input type="date" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm" id="internship_end_date" name="internship_end_date" value="<?= esc(old('internship_end_date', $application['internship_end_date'] ?? '')) ?>"></div>
                    </div>
                </div>

                <div class="grid gap-5">
                    <h2 class="text-lg font-semibold text-slate-950">Supervisor information</h2>
                    <div class="grid gap-5 md:grid-cols-2">
                        <?php foreach (['supervisor_name' => 'Supervisor Name', 'supervisor_email' => 'Supervisor Email', 'supervisor_university' => 'Supervisor University', 'supervisor_department' => 'Supervisor Department', 'supervisor_designation' => 'Supervisor Designation', 'supervisor_phone' => 'Supervisor Phone', 'placement_organization_name' => 'Organization Name', 'organization_website_url' => 'Website URL', 'mentor_name' => 'Mentor Name', 'mentor_email' => 'Mentor Email'] as $name => $label): ?>
                            <div>
                                <label class="mb-2 block text-sm font-medium" for="<?= esc($name) ?>"><?= esc($label) ?> *</label>
                                <input class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm" id="<?= esc($name) ?>" name="<?= esc($name) ?>" value="<?= esc(old($name, $application[$name] ?? '')) ?>">
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700">
                    <label class="flex items-start gap-3">
                        <input type="checkbox" name="information_declaration" value="1" class="mt-1 h-4 w-4 rounded border-slate-300 text-brand-600" required>
                        <span>I hereby declare that all information provided in this application is complete, accurate and correct.</span>
                    </label>
                </div>

                <div class="flex flex-wrap gap-3">
                    <button class="inline-flex items-center rounded-2xl bg-brand-600 px-4 py-3 text-sm font-semibold text-white" type="submit">Save Changes</button>
                    <a class="inline-flex items-center rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700" href="<?= site_url('applications/' . ($application['application_code'] ?? '')) ?>">Cancel</a>
                </div>
            <?= form_close() ?>
        </section>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const root = document.querySelector('[data-combobox="university"]');
  const search = document.getElementById('university_id_search');
  const hidden = document.getElementById('university_id');
  const panel = root ? root.querySelector('[data-combobox-panel]') : null;
  const list = root ? root.querySelector('[data-combobox-list]') : null;
  const empty = root ? root.querySelector('[data-combobox-empty]') : null;
  const department = document.getElementById('department');
  const otherWrap = document.getElementById('other_department_wrap');
  const otherInput = document.getElementById('other_department');
  const universities = <?= json_encode(array_map(static fn($u) => ['id' => (string) $u['id'], 'name' => (string) $u['name'], 'type' => (string) ($u['type'] ?? ''), 'is_active' => (int) ($u['is_active'] ?? 0)], $universities), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
  let selectedId = hidden ? hidden.value : '';

  function renderOptions(query) {
    if (! list || ! panel || ! empty || ! search) return;
    list.querySelectorAll('[data-option]').forEach(function (el) { el.remove(); });
    const q = (query || '').trim().toLowerCase();
    const filtered = universities.filter(function (u) {
      return u.name.toLowerCase().includes(q) || (u.type || '').toLowerCase().includes(q);
    });
    filtered.forEach(function (u) {
      const li = document.createElement('li');
      li.setAttribute('data-option', '1');
      li.className = 'cursor-pointer px-4 py-2 text-sm hover:bg-slate-50';
      li.textContent = u.name + ' — ' + u.type + (u.is_active === 1 ? '' : ' (Inactive)');
      li.dataset.id = u.id;
      list.insertBefore(li, empty);
    });
    empty.classList.toggle('hidden', filtered.length !== 0);
    panel.classList.remove('hidden');
  }

  if (root && search && hidden && panel && list) {
    const selected = universities.find(function (u) { return u.id === selectedId; });
    if (selected && ! search.value) {
      search.value = selected.name + ' — ' + selected.type + (selected.is_active === 1 ? '' : ' (Inactive)');
    }
    search.addEventListener('focus', function () { renderOptions(search.value); });
    search.addEventListener('input', function () {
      selectedId = '';
      hidden.value = '';
      renderOptions(search.value);
    });
    list.addEventListener('click', function (event) {
      const option = event.target.closest('[data-option]');
      if (! option) return;
      const item = universities.find(function (u) { return u.id === option.dataset.id; });
      if (! item) return;
      hidden.value = item.id;
      selectedId = item.id;
      search.value = item.name + ' — ' + item.type + (item.is_active === 1 ? '' : ' (Inactive)');
      panel.classList.add('hidden');
    });
    document.addEventListener('click', function (event) {
      if (! root.contains(event.target)) panel.classList.add('hidden');
    });
  }

  function syncDepartment(initial) {
    const isOther = department && department.value === 'Other';
    if (otherWrap) {
      otherWrap.hidden = !isOther;
    }
    if (otherInput) {
      otherInput.required = isOther;
      if (!isOther && !initial) {
        otherInput.value = '';
      }
    }
  }

  if (department) {
    department.addEventListener('change', function () { syncDepartment(false); });
  }
  syncDepartment(true);
});
</script>
<?= $this->endSection() ?>
