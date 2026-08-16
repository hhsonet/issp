<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div id="app"></div>
<script id="issp-data" type="application/json"><?= json_encode([
    'baseUrl' => base_url(),
    'homeUrl' => site_url('/'),
    'dashboardUrl' => site_url('dashboard'),
    'loginUrl' => site_url('login'),
    'signupUrl' => site_url('signup'),
    'profileUrl' => site_url('profile'),
    'applicationsUrl' => site_url('applications'),
    'logoutUrl' => site_url('logout'),
    'supportUrl' => site_url('/') . '#support',
    'page' => $page,
    'csrfName' => csrf_token(),
    'csrfHash' => csrf_hash(),
    'user' => $user,
    'summary' => $summary,
    'checklist' => $checklist,
    'applications' => $applications,
    'announcements' => $announcements,
    'downloads' => $downloads,
    'flashMessage' => $flashMessage ?? null,
    'flashType' => $flashType ?? null,
    'year' => $year,
], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?></script>
<?= $this->endSection() ?>
