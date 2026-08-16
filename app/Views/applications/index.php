<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="app-layout">
    <div class="drawer-backdrop" data-drawer-backdrop></div>
    <?= $this->include('components/sidebar') ?>
    <main class="app-main">
        <?= view('components/topbar', [
            'eyebrow' => 'Applications',
            'title' => 'My Applications',
            'description' => 'Create and manage ISSP application records in one place.',
        ]) ?>

        <section class="page-toolbar">
            <a class="btn btn-primary" href="#">Create New Application</a>
            <div class="filter-pills" aria-label="Status filters">
                <span class="pill is-active">All</span>
                <span class="pill">Draft</span>
                <span class="pill">Submitted</span>
                <span class="pill">Under Review</span>
            </div>
        </section>

        <section class="card panel">
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Application ID</th>
                            <th>Project Title</th>
                            <th>Status</th>
                            <th>Last Updated</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="5">
                                <?= view('components/empty_state', [
                                    'title' => 'You have not created an application yet.',
                                    'message' => 'Use the button above to begin a new application whenever you are ready.',
                                    'actionUrl' => '#',
                                    'actionLabel' => 'Create New Application',
                                ]) ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>
<?= $this->endSection() ?>
