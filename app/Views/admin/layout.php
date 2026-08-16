<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="min-h-screen bg-slate-50 text-slate-900 lg:flex">
    <?= $this->include('admin/sidebar', ['page' => $page ?? 'dashboard']) ?>
    <main class="min-w-0 flex-1">
        <?= $this->include('admin/topbar', ['title' => $title ?? 'Admin']) ?>
        <div class="p-4 sm:p-6 lg:p-8">
            <?= $this->renderSection('adminContent') ?>
        </div>
    </main>
</div>
<script>
(function(){
  const sidebar = document.querySelector('[data-admin-sidebar]');
  const open = document.querySelector('[data-admin-sidebar-open]');
  const dropdownToggle = document.querySelector('[data-admin-user-dropdown]');
  const dropdown = document.querySelector('[data-admin-dropdown]');
  if (open && sidebar) open.addEventListener('click', () => sidebar.classList.toggle('hidden'));
  if (dropdownToggle && dropdown) dropdownToggle.addEventListener('click', () => dropdown.classList.toggle('hidden'));
})();
</script>
<?= $this->endSection() ?>
