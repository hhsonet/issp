<?= $this->extend('admin/layout') ?>
<?= $this->section('adminContent') ?>
<?php
$filters = $filters ?? ['round' => '', 'status' => '', 'university' => '', 'q' => ''];
$applications = $applications ?? [];
$roundOptions = $roundOptions ?? [];
$universityOptions = $universityOptions ?? [];
$totalApplications = (int) ($totalApplications ?? 0);
$hasFilters = (bool) array_filter($filters, static fn($value) => trim((string) $value) !== '');
$queryParams = array_filter($filters, static fn($value) => trim((string) $value) !== '');

if (! function_exists('admin_application_format_datetime')) {
    function admin_application_format_datetime(?string $value): string
    {
        if (! $value) {
            return '—';
        }

        $timestamp = strtotime($value);
        if ($timestamp === false) {
            return (string) $value;
        }

        return date('M d, Y h:i A', $timestamp);
    }
}

if (! function_exists('admin_application_status_class')) {
    function admin_application_status_class(string $status): string
    {
        return match ($status) {
            'Submitted' => 'border-slate-200 bg-slate-100 text-slate-700',
            'Under Review' => 'border-amber-200 bg-amber-50 text-amber-700',
            'Approved' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
            'Rejected' => 'border-rose-200 bg-rose-50 text-rose-700',
            default => 'border-slate-200 bg-slate-100 text-slate-600',
        };
    }
}
?>

<section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-soft">
    <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h2 class="text-xl font-semibold text-slate-950">Applications</h2>
            <p class="mt-1 text-sm text-slate-500"><?= esc((string) $totalApplications) ?> matching results</p>
        </div>
        <a class="inline-flex items-center rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50" href="<?= site_url('admin/application') ?>">Reset</a>
    </div>

    <form class="mt-6 grid gap-4 rounded-3xl border border-slate-200 bg-slate-50 p-4" method="get" action="<?= site_url('admin/application') ?>">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700" for="round">Application Call</label>
                <select id="round" name="round" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm">
                    <option value="">All Calls</option>
                    <?php foreach ($roundOptions as $round): ?>
                        <option value="<?= esc($round['round_code']) ?>" <?= ($filters['round'] ?? '') === (string) ($round['round_code'] ?? '') ? 'selected' : '' ?>>
                            <?= esc($round['round_code']) ?> — <?= esc($round['title']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700" for="status">Submission Status</label>
                <select id="status" name="status" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm">
                    <option value="">All Statuses</option>
                    <?php foreach (['Submitted', 'Under Review', 'Approved', 'Rejected'] as $status): ?>
                        <option value="<?= esc($status) ?>" <?= ($filters['status'] ?? '') === $status ? 'selected' : '' ?>><?= esc($status) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="relative" data-admin-university-filter>
                <label class="mb-2 block text-sm font-medium text-slate-700" for="university_search">University</label>
                <input type="hidden" name="university" id="university" value="<?= esc($filters['university'] ?? '') ?>">
                <input id="university_search" type="text" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm" placeholder="All Universities" autocomplete="off" value="">
                <div class="absolute z-20 mt-2 hidden w-full overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-lg" data-university-panel>
                    <ul class="max-h-72 overflow-auto py-1" data-university-list>
                        <li class="hidden px-4 py-3 text-sm text-slate-500" data-university-empty>No university found</li>
                    </ul>
                </div>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700" for="q">Search</label>
                <input id="q" name="q" value="<?= esc($filters['q'] ?? '') ?>" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm" placeholder="Application code, name, email or student ID">
            </div>
        </div>

        <div class="flex flex-wrap gap-3">
            <button class="inline-flex items-center rounded-2xl bg-brand-600 px-4 py-3 text-sm font-semibold text-white hover:bg-brand-700" type="submit">Apply Filters</button>
            <a class="inline-flex items-center rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50" href="<?= site_url('admin/application') ?>">Clear Filters</a>
        </div>

        <?php if ($hasFilters): ?>
            <div class="flex flex-wrap gap-2 text-xs font-semibold">
                <?php foreach ($filters as $key => $value): ?>
                    <?php if (trim((string) $value) === '') continue; ?>
                    <span class="inline-flex rounded-full border border-slate-200 bg-white px-3 py-1 text-slate-600"><?= esc($key) ?>: <?= esc((string) $value) ?></span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </form>
</section>

<section class="mt-6 w-full min-w-0 rounded-3xl border border-slate-200 bg-white p-4 shadow-soft sm:p-6">
    <?php if (empty($applications)): ?>
        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6 text-sm text-slate-600">
            <?= $hasFilters ? 'No applications match the selected filters.' : 'No applications have been submitted yet.' ?>
        </div>
    <?php else: ?>
        <div class="hidden lg:block">
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="text-xs uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="border-b border-slate-200 px-3 py-3">Application Code</th>
                            <th class="border-b border-slate-200 px-3 py-3">Applicant Name</th>
                            <th class="border-b border-slate-200 px-3 py-3">University</th>
                            <th class="border-b border-slate-200 px-3 py-3">Submitted</th>
                            <th class="border-b border-slate-200 px-3 py-3">Edit Access</th>
                            <th class="border-b border-slate-200 px-3 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($applications as $row): ?>
                            <?php
                            $applicationCode = (string) ($row['application_code'] ?? '');
                            $editEnabled = (int) ($row['edit_enabled'] ?? 0) === 1;
                            $editLabel = $editEnabled ? 'Allowed' : 'Locked';
                            ?>
                            <tr class="align-top">
                                <td class="border-b border-slate-100 px-3 py-4 align-top">
                                    <a href="<?= site_url('admin/application/' . $applicationCode) ?>" class="font-semibold text-brand-700 hover:underline"><?= esc($applicationCode) ?></a>
                                </td>
                                <td class="border-b border-slate-100 px-3 py-4 align-top">
                                    <div class="font-medium text-slate-950"><?= esc($row['full_name'] ?? '') ?></div>
                                    <?php if (! empty($row['email'])): ?>
                                        <div class="text-xs text-slate-500"><?= esc($row['email']) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="border-b border-slate-100 px-3 py-4 align-top">
                                    <div class="font-medium text-slate-950"><?= esc($row['university_name'] ?? 'Not provided') ?></div>
                                    <div class="mt-1 inline-flex rounded-full border border-slate-200 bg-slate-100 px-2 py-1 text-[11px] font-semibold text-slate-600">
                                        <?= esc($row['university_type'] ?? 'Unknown') ?>
                                    </div>
                                </td>
                                <td class="border-b border-slate-100 px-3 py-4 align-top">
                                    <div class="font-medium text-slate-950"><?= esc(admin_application_format_datetime($row['submitted_at'] ?? null)) ?></div>
                                    <?php if (! empty($row['status'])): ?>
                                        <div class="mt-1 inline-flex rounded-full border px-2 py-1 text-[11px] font-semibold <?= esc(admin_application_status_class((string) $row['status'])) ?>">
                                            <?= esc($row['status']) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="border-b border-slate-100 px-3 py-4 align-top">
                                    <form method="post" action="<?= site_url('admin/application/' . $applicationCode . '/edit-access') ?>" class="inline-flex items-center gap-3" data-edit-access-form>
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="edit_enabled" value="<?= $editEnabled ? '0' : '1' ?>">
                                        <button
                                            type="submit"
                                            class="inline-flex items-center gap-3 rounded-full border px-3 py-2 text-left transition focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 <?= $editEnabled ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : 'border-slate-200 bg-slate-100 text-slate-600' ?>"
                                            role="switch"
                                            aria-checked="<?= $editEnabled ? 'true' : 'false' ?>"
                                            aria-label="Toggle edit access for application code <?= esc($applicationCode) ?>"
                                            data-edit-toggle
                                        >
                                            <span class="relative inline-flex h-6 w-11 items-center rounded-full <?= $editEnabled ? 'bg-emerald-500' : 'bg-slate-300' ?>">
                                                <span class="sr-only">Edit access toggle</span>
                                                <span class="inline-block h-5 w-5 transform rounded-full bg-white shadow transition <?= $editEnabled ? 'translate-x-5' : 'translate-x-1' ?>"></span>
                                            </span>
                                            <span class="text-sm font-semibold"><?= esc($editLabel) ?></span>
                                        </button>
                                    </form>
                                </td>
                                <td class="border-b border-slate-100 px-3 py-4 align-top">
                                    <div class="flex flex-nowrap items-center gap-2 whitespace-nowrap">
                                        <a class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50" href="<?= site_url('admin/application/' . $applicationCode) ?>">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12Z"/><circle cx="12" cy="12" r="3"/></svg>
                                            View
                                        </a>
                                        <button
                                            type="button"
                                            class="inline-flex items-center gap-2 rounded-2xl border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-700 hover:bg-rose-100"
                                            data-delete-open
                                            data-code="<?= esc($applicationCode) ?>"
                                            data-name="<?= esc($row['full_name'] ?? '') ?>"
                                        >
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M7 6h10l-1 14H8L7 6Z"/><path d="M10 11v6"/><path d="M14 11v6"/></svg>
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="grid gap-4 lg:hidden">
            <?php foreach ($applications as $row): ?>
                <?php
                $applicationCode = (string) ($row['application_code'] ?? '');
                $editEnabled = (int) ($row['edit_enabled'] ?? 0) === 1;
                $editLabel = $editEnabled ? 'Allowed' : 'Locked';
                ?>
                <article class="w-full rounded-3xl border border-slate-200 bg-slate-50 p-5">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <a href="<?= site_url('admin/application/' . $applicationCode) ?>" class="text-sm font-semibold text-brand-700 hover:underline"><?= esc($applicationCode) ?></a>
                            <h3 class="mt-1 text-lg font-semibold text-slate-950"><?= esc($row['full_name'] ?? '') ?></h3>
                            <?php if (! empty($row['email'])): ?>
                                <p class="text-xs text-slate-500"><?= esc($row['email']) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mt-4 grid gap-3 text-sm text-slate-600">
                        <div>
                            <span class="font-semibold text-slate-500">University:</span>
                            <div class="mt-1 font-medium text-slate-950"><?= esc($row['university_name'] ?? 'Not provided') ?></div>
                            <div class="mt-1 inline-flex rounded-full border border-slate-200 bg-slate-100 px-2 py-1 text-[11px] font-semibold text-slate-600">
                                <?= esc($row['university_type'] ?? 'Unknown') ?>
                            </div>
                        </div>

                        <div>
                            <span class="font-semibold text-slate-500">Submitted:</span>
                            <div class="mt-1 font-medium text-slate-950"><?= esc(admin_application_format_datetime($row['submitted_at'] ?? null)) ?></div>
                            <?php if (! empty($row['status'])): ?>
                                <div class="mt-1 inline-flex rounded-full border px-2 py-1 text-[11px] font-semibold <?= esc(admin_application_status_class((string) $row['status'])) ?>">
                                    <?= esc($row['status']) ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div>
                            <span class="font-semibold text-slate-500">Edit Access:</span>
                            <form method="post" action="<?= site_url('admin/application/' . $applicationCode . '/edit-access') ?>" class="mt-2 inline-flex items-center gap-3" data-edit-access-form>
                                <?= csrf_field() ?>
                                <input type="hidden" name="edit_enabled" value="<?= $editEnabled ? '0' : '1' ?>">
                                <button
                                    type="submit"
                                    class="inline-flex items-center gap-3 rounded-full border px-3 py-2 text-left transition focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 <?= $editEnabled ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : 'border-slate-200 bg-slate-100 text-slate-600' ?>"
                                    aria-label="Toggle edit access for <?= esc($applicationCode) ?>"
                                    data-edit-toggle
                                >
                                    <span class="relative inline-flex h-6 w-11 items-center rounded-full <?= $editEnabled ? 'bg-emerald-500' : 'bg-slate-300' ?>">
                                        <span class="sr-only">Edit access toggle</span>
                                        <span class="inline-block h-5 w-5 transform rounded-full bg-white shadow transition <?= $editEnabled ? 'translate-x-5' : 'translate-x-1' ?>"></span>
                                    </span>
                                    <span class="text-sm font-semibold"><?= esc($editLabel) ?></span>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="mt-4 flex flex-col gap-2 sm:flex-row sm:items-center">
                        <a class="inline-flex flex-1 items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50" href="<?= site_url('admin/application/' . $applicationCode) ?>">
                            <svg class="mr-2 h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12Z"/><circle cx="12" cy="12" r="3"/></svg>
                            View
                        </a>
                        <button type="button" class="inline-flex flex-1 items-center justify-center rounded-2xl border border-rose-200 bg-rose-50 px-4 py-2 text-sm font-semibold text-rose-700 hover:bg-rose-100" data-delete-open data-code="<?= esc($applicationCode) ?>" data-name="<?= esc($row['full_name'] ?? '') ?>">
                            <svg class="mr-2 h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M7 6h10l-1 14H8L7 6Z"/><path d="M10 11v6"/><path d="M14 11v6"/></svg>
                            Delete
                        </button>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <div class="mt-4 flex flex-wrap items-center justify-between gap-3 text-sm text-slate-600">
            <div>Showing <?= esc((string) count($applications)) ?> of <?= esc((string) $totalApplications) ?></div>
            <div class="flex gap-2">
                <?php if (($pager->hasPreviousPage ?? false)): ?>
                    <a class="rounded-2xl border border-slate-200 bg-white px-3 py-2 font-semibold text-slate-700 hover:bg-slate-50" href="<?= current_url() . '?' . http_build_query(array_merge($queryParams, ['page' => $pager->previousPage])) ?>">Prev</a>
                <?php endif; ?>
                <?php if (($pager->hasNextPage ?? false)): ?>
                    <a class="rounded-2xl border border-slate-200 bg-white px-3 py-2 font-semibold text-slate-700 hover:bg-slate-50" href="<?= current_url() . '?' . http_build_query(array_merge($queryParams, ['page' => $pager->nextPage])) ?>">Next</a>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</section>

<dialog id="deleteDialog" class="rounded-3xl border border-slate-200 p-0 shadow-soft">
    <form method="post" id="deleteForm" class="w-[min(92vw,520px)] p-6">
        <?= csrf_field() ?>
        <h3 class="text-lg font-semibold text-slate-950">Delete Application</h3>
        <p class="mt-2 text-sm text-slate-500">Enter a reason to delete this application.</p>
        <div class="mt-4 rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700">
            <div class="font-semibold" id="deleteName"></div>
            <div class="text-xs text-slate-500" id="deleteCodeLabel"></div>
        </div>
        <textarea name="reason" required class="mt-4 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20" rows="4" placeholder="Reason for deletion"></textarea>
        <div class="mt-4 flex justify-end gap-3">
            <button type="button" class="rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50" data-delete-close>Cancel</button>
            <button type="submit" class="rounded-2xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700">Delete</button>
        </div>
    </form>
</dialog>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const filter = document.querySelector('[data-admin-university-filter]');
  const search = document.getElementById('university_search');
  const hidden = document.getElementById('university');
  const panel = filter ? filter.querySelector('[data-university-panel]') : null;
  const list = filter ? filter.querySelector('[data-university-list]') : null;
  const empty = filter ? filter.querySelector('[data-university-empty]') : null;
  const universities = <?= json_encode(array_map(static fn($u) => ['id' => (string) $u['id'], 'name' => (string) $u['name'], 'type' => (string) $u['type']], $universityOptions), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
  const deleteDialog = document.getElementById('deleteDialog');
  const deleteForm = document.getElementById('deleteForm');
  const deleteName = document.getElementById('deleteName');
  const deleteCodeLabel = document.getElementById('deleteCodeLabel');

  if (search && hidden && list && panel && empty) {
    const renderOptions = function (query) {
      list.querySelectorAll('[data-option]').forEach(function (el) { el.remove(); });

      const normalized = query.trim().toLowerCase();
      const filtered = universities.filter(function (u) {
        return u.name.toLowerCase().includes(normalized) || u.type.toLowerCase().includes(normalized);
      });

      filtered.forEach(function (u) {
        const li = document.createElement('li');
        li.dataset.option = '1';
        li.dataset.id = u.id;
        li.className = 'cursor-pointer px-4 py-2 text-sm hover:bg-slate-50';
        li.textContent = u.name + ' — ' + u.type;
        list.insertBefore(li, empty);
      });

      empty.classList.toggle('hidden', filtered.length > 0);
      panel.classList.remove('hidden');
    };

    const selected = universities.find(function (u) { return u.id === hidden.value; });
    if (selected) {
      search.value = selected.name + ' — ' + selected.type;
    }

    search.addEventListener('input', function () {
      hidden.value = '';
      renderOptions(search.value);
    });

    search.addEventListener('focus', function () {
      renderOptions(search.value);
    });

    list.addEventListener('click', function (event) {
      const option = event.target.closest('[data-option]');
      if (!option) {
        return;
      }

      const item = universities.find(function (u) { return u.id === option.dataset.id; });
      if (!item) {
        return;
      }

      hidden.value = item.id;
      search.value = item.name + ' — ' + item.type;
      panel.classList.add('hidden');
    });

    document.addEventListener('click', function (event) {
      if (!filter.contains(event.target)) {
        panel.classList.add('hidden');
      }
    });
  }

  document.querySelectorAll('[data-delete-open]').forEach(function (button) {
    button.addEventListener('click', function () {
      if (!deleteDialog || !deleteForm || !deleteName || !deleteCodeLabel) {
        return;
      }

      deleteName.textContent = button.dataset.name || '';
      deleteCodeLabel.textContent = button.dataset.code || '';
      deleteForm.action = '<?= site_url('admin/application') ?>/' + encodeURIComponent(button.dataset.code || '') + '/delete';
      deleteDialog.showModal();
    });
  });

  document.querySelectorAll('[data-delete-close]').forEach(function (button) {
    button.addEventListener('click', function () {
      if (deleteDialog) {
        deleteDialog.close();
      }
    });
  });

  document.querySelectorAll('[data-edit-access-form]').forEach(function (form) {
    form.addEventListener('submit', function () {
      const button = form.querySelector('[data-edit-toggle]');
      if (button) {
        button.disabled = true;
      }
    });

    form.querySelectorAll('[data-edit-toggle]').forEach(function (toggle) {
      toggle.addEventListener('click', function () {
        const button = form.querySelector('[data-edit-toggle]');
        if (button) {
          button.disabled = true;
        }
      });
    });
  });
});
</script>
<?= $this->endSection() ?>
