<?php

namespace App\Controllers;

use App\Models\ApplicationRoundModel;
use App\Models\DepartmentModel;
use App\Models\InternshipApplicationModel;
use App\Models\UniversityModel;
use App\Models\UserModel;

class Admin extends BaseController
{
    public function dashboard(): string
    {
        $db = db_connect();
        $rounds = new ApplicationRoundModel();
        $applications = new InternshipApplicationModel();
        $users = new UserModel();

        return view('admin/dashboard', [
            'title' => 'Admin Dashboard',
            'page' => 'dashboard',
            'user' => $this->currentAdmin(),
            'summary' => [
                'total_calls' => $db->table('application_rounds')->countAllResults(),
                'open_calls' => $db->table('application_rounds')->where('status', 'Open')->countAllResults(),
                'total_applications' => $db->table('internship_applications')->countAllResults(),
                'latest_round_applications' => $this->latestRoundApplications(),
                'total_users' => $db->table('users')->countAllResults(),
                'total_universities' => $db->table('universities')->countAllResults(),
                'total_departments' => $db->table('departments')->countAllResults(),
            ],
            'recentApplications' => $this->recentApplications(),
            'activeCalls' => $this->activeAndUpcomingCalls(),
        ]);
    }

    public function users(): string
    {
        return view('admin/users', [
            'title' => 'Users',
            'page' => 'users',
            'user' => $this->currentAdmin(),
            'users' => (new UserModel())->orderBy('created_at', 'desc')->findAll(200),
        ]);
    }

    public function promote(string $email)
    {
        $admin = $this->currentAdmin();
        $user = (new UserModel())->where('email', strtolower(trim($email)))->first();

        if (! $user) {
            return redirect()->to(site_url('admin/users'))->with('error', 'User not found.');
        }

        if ((int) $user['id'] === (int) $admin['id']) {
            return redirect()->to(site_url('admin/users'))->with('error', 'You cannot change your own administrator access.');
        }

        (new UserModel())->update($user['id'], ['role' => 'admin']);

        return redirect()->to(site_url('admin/users'))->with('success', 'Administrator access granted.');
    }

    public function toggleStatus(int $id)
    {
        $admin = $this->currentAdmin();
        $userModel = new UserModel();
        $user = $userModel->find($id);

        if (! $user) {
            return redirect()->to(site_url('admin/users'))->with('error', 'User not found.');
        }

        if ((int) $user['id'] === (int) $admin['id']) {
            return redirect()->to(site_url('admin/users'))->with('error', 'You cannot disable your own account.');
        }

        $newStatus = ((int) ($user['is_active'] ?? 1) === 1) ? 0 : 1;
        $userModel->update($id, ['is_active' => $newStatus]);

        return redirect()->to(site_url('admin/users'))->with('success', $newStatus ? 'Account reactivated.' : 'Account disabled.');
    }

    public function applications(): string
    {
        $filters = $this->validatedApplicationFilters();
        $builder = $this->applicationAdminQuery($filters);
        $page = max(1, (int) ($this->request->getGet('page') ?? 1));
        $perPage = 15;
        $total = (clone $builder)->countAllResults();
        $applications = $builder->limit($perPage, ($page - 1) * $perPage)->get()->getResultArray();

        return view('admin/applications', [
            'title' => 'Applications',
            'page' => 'applications',
            'user' => $this->currentAdmin(),
            'filters' => $filters,
            'roundOptions' => (new ApplicationRoundModel())->select('round_code, title, opens_at')->orderBy('opens_at', 'desc')->findAll(),
            'universityOptions' => (new UniversityModel())->select('id, name, type, is_active')->orderBy('name', 'asc')->findAll(),
            'applications' => $applications,
            'pager' => $this->buildPager($page, $perPage, $total),
            'totalApplications' => $total,
        ]);
    }

    public function applicationView(string $applicationCode): string|\CodeIgniter\HTTP\ResponseInterface
    {
        $application = $this->adminApplicationByCode($applicationCode);
        if (! $application) {
            return $this->response->setStatusCode(404)->setBody(view('errors/html/error_404'));
        }

        return view('admin/application_show', [
            'title' => 'View Application',
            'page' => 'applications',
            'user' => $this->currentAdmin(),
            'application' => $application,
        ]);
    }

    public function grantEditAccess(string $applicationCode)
    {
        $this->requireAdminRequest();
        $application = $this->adminApplicationByCode($applicationCode, false);
        if (! $application) {
            return redirect()->to(site_url('admin/applications'))->with('error', 'Application not found.');
        }

        db_connect()->table('internship_applications')->where('id', $application['id'])->update([
            'edit_enabled' => 1,
            'edit_enabled_at' => date('Y-m-d H:i:s'),
            'edit_enabled_by' => (int) session('user_id'),
        ]);
        $this->logAdminApplicationAction('edit_access_granted', $application['application_code'], null, ['application_code' => $application['application_code']]);

        return redirect()->to(site_url('admin/applications'))->with('success', 'Edit access granted.');
    }

    public function revokeEditAccess(string $applicationCode)
    {
        $this->requireAdminRequest();
        $application = $this->adminApplicationByCode($applicationCode, false);
        if (! $application) {
            return redirect()->to(site_url('admin/applications'))->with('error', 'Application not found.');
        }

        db_connect()->table('internship_applications')->where('id', $application['id'])->update([
            'edit_enabled' => 0,
            'edit_enabled_at' => null,
            'edit_enabled_by' => null,
        ]);
        $this->logAdminApplicationAction('edit_access_revoked', $application['application_code'], null, ['application_code' => $application['application_code']]);

        return redirect()->to(site_url('admin/applications'))->with('success', 'Edit access revoked.');
    }

    public function toggleApplicationEditAccess(string $applicationCode)
    {
        return $this->toggleEditAccess($applicationCode);
    }

    public function toggleEditAccess(string $applicationCode)
    {
        $this->requireAdminRequest();
        $application = $this->adminApplicationByCode($applicationCode, false);
        if (! $application) {
            return $this->redirectBackToApplications('Application not found.', 'error');
        }

        $desired = (string) $this->request->getPost('edit_enabled') === '1' ? 1 : 0;

        $db = db_connect();
        $db->transStart();
        $db->table('internship_applications')->where('application_code', $application['application_code'])->where('deleted_at', null)->update([
            'edit_enabled' => $desired,
            'edit_enabled_at' => $desired === 1 ? date('Y-m-d H:i:s') : null,
            'edit_enabled_by' => $desired === 1 ? (int) session('user_id') : null,
        ]);
        $db->transComplete();

        if (! $db->transStatus()) {
            return $this->redirectBackToApplications('Unable to update edit access. Please try again.', 'error');
        }

        $this->logAdminApplicationAction(
            $desired === 1 ? 'edit_access_granted' : 'edit_access_revoked',
            $application['application_code'],
            null,
            ['application_code' => $application['application_code'], 'edit_enabled' => $desired]
        );

        return $this->redirectBackToApplications(
            $desired === 1 ? 'Edit access granted successfully.' : 'Edit access revoked successfully.',
            'success'
        );
    }

    public function deleteApplication(string $applicationCode)
    {
        $this->requireAdminRequest();
        $reason = trim((string) $this->request->getPost('reason'));
        if ($reason === '') {
            return redirect()->to(site_url('admin/applications'))->with('error', 'Please provide a deletion reason.');
        }

        $application = $this->adminApplicationByCode($applicationCode, false);
        if (! $application) {
            return redirect()->to(site_url('admin/applications'))->with('error', 'Application not found.');
        }

        db_connect()->table('internship_applications')->where('id', $application['id'])->update([
            'deleted_at' => date('Y-m-d H:i:s'),
        ]);
        $this->logAdminApplicationAction('application_deleted', $application['application_code'], $reason, ['application_code' => $application['application_code']]);

        return redirect()->to(site_url('admin/applications'))->with('success', 'Application deleted.');
    }

    private function currentAdmin(): array
    {
        $user = (new UserModel())->find((int) session('user_id'));
        if (! $user || ! (bool) ($user['is_active'] ?? 1) || ($user['role'] ?? 'user') !== 'admin') {
            redirect()->to(site_url('login'))->with('error', 'Your session has expired. Please refresh and try again.')->send();
            exit;
        }
        return $user;
    }

    private function latestRoundApplications(): int
    {
        $round = (new ApplicationRoundModel())->orderBy('opens_at', 'desc')->first();
        if (! $round) {
            return 0;
        }
        return (new InternshipApplicationModel())->where('round_id', $round['id'])->countAllResults();
    }

    private function recentApplications(): array
    {
        return db_connect()->table('internship_applications ia')
            ->select('ia.id, ia.full_name, ia.student_id, ia.internship_type, ia.submitted_at, ia.status, ar.round_code, ar.title as round_title, u.name as university_name')
            ->join('application_rounds ar', 'ar.id = ia.round_id', 'left')
            ->join('universities u', 'u.id = ia.university_id', 'left')
            ->orderBy('ia.submitted_at', 'desc')
            ->limit(10)
            ->get()->getResultArray();
    }

    private function activeAndUpcomingCalls(): array
    {
        return db_connect()->table('application_rounds ar')
            ->select('ar.id, ar.round_code, ar.title, ar.opens_at, ar.closes_at, ar.status, COUNT(ia.id) as applications_count')
            ->join('internship_applications ia', 'ia.round_id = ar.id', 'left')
            ->whereIn('ar.status', ['Open', 'Draft'])
            ->groupBy('ar.id')
            ->orderBy('ar.opens_at', 'asc')
            ->limit(10)
            ->get()->getResultArray();
    }

    private function validatedApplicationFilters(): array
    {
        $round = trim((string) $this->request->getGet('round'));
        $status = trim((string) $this->request->getGet('status'));
        $university = trim((string) $this->request->getGet('university'));
        $search = trim((string) $this->request->getGet('q'));

        $allowedStatuses = ['Submitted', 'Under Review', 'Approved', 'Rejected'];
        $roundAllowed = array_map(static fn(array $row) => (string) ($row['round_code'] ?? ''), (new ApplicationRoundModel())->select('round_code')->findAll());
        $universityAllowed = array_map(static fn(array $row) => (string) ($row['id'] ?? ''), (new UniversityModel())->select('id')->findAll());

        return [
            'round' => in_array($round, $roundAllowed, true) ? $round : '',
            'status' => in_array($status, $allowedStatuses, true) ? $status : '',
            'university' => in_array($university, $universityAllowed, true) ? $university : '',
            'q' => $search,
        ];
    }

    private function applicationAdminQuery(array $filters)
    {
        $builder = db_connect()->table('internship_applications ia')
            ->select('ia.application_code, ia.full_name, ia.student_id, ia.status, ia.submitted_at, ia.edit_enabled, ia.internship_type, ia.department, ia.other_department, ia.deleted_at, u.email, un.name as university_name, un.type as university_type, ar.round_code, ar.title as round_title, ar.id as round_id')
            ->join('users u', 'u.id = ia.user_id', 'left')
            ->join('universities un', 'un.id = ia.university_id', 'left')
            ->join('application_rounds ar', 'ar.id = ia.round_id', 'left')
            ->where('ia.deleted_at', null);

        if (! empty($filters['round'])) {
            $builder->where('ar.round_code', $filters['round']);
        }
        if (! empty($filters['status'])) {
            $builder->where('ia.status', $filters['status']);
        }
        if (! empty($filters['university'])) {
            $builder->where('ia.university_id', (int) $filters['university']);
        }
        if (! empty($filters['q'])) {
            $builder->groupStart()
                ->like('ia.application_code', $filters['q'])
                ->orLike('ia.full_name', $filters['q'])
                ->orLike('u.email', $filters['q'])
                ->orLike('ia.student_id', $filters['q'])
                ->groupEnd();
        }

        return $builder->orderBy('ia.submitted_at', 'desc');
    }

    private function adminApplicationByCode(string $applicationCode, bool $mustBeVisible = true): ?array
    {
        $builder = db_connect()->table('internship_applications ia')
            ->select('ia.*, u.email, un.name as university_name, un.type as university_type, ar.round_code, ar.title as round_title')
            ->join('users u', 'u.id = ia.user_id', 'left')
            ->join('universities un', 'un.id = ia.university_id', 'left')
            ->join('application_rounds ar', 'ar.id = ia.round_id', 'left')
            ->where('ia.application_code', strtoupper(trim($applicationCode)));

        if ($mustBeVisible) {
            $builder->where('ia.deleted_at', null);
        }

        return $builder->get()->getRowArray();
    }

    private function requireAdminRequest(): void
    {
        if (! $this->request->is('post')) {
            redirect()->to(site_url('admin/applications'))->send();
            exit;
        }
        $this->currentAdmin();
    }

    private function redirectBackToApplications(string $message, string $type)
    {
        $url = site_url('admin/application');
        $ref = (string) ($this->request->getServer('HTTP_REFERER') ?? '');
        if ($ref !== '' && str_contains($ref, site_url('admin/application'))) {
            $url = $ref;
        }

        return redirect()->to($url)->with($type, $message);
    }

    private function logAdminApplicationAction(string $action, string $entityPublicCode, ?string $reason, array $metadata = []): void
    {
        if (! db_connect()->tableExists('admin_action_logs')) {
            return;
        }

        db_connect()->table('admin_action_logs')->insert([
            'admin_user_id' => (int) session('user_id'),
            'action' => $action,
            'entity_type' => 'application',
            'entity_public_code' => $entityPublicCode,
            'reason' => $reason,
            'metadata' => json_encode($metadata, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    private function buildPager(int $page, int $perPage, int $total): object
    {
        $page = max(1, $page);
        $pages = max(1, (int) ceil($total / $perPage));

        return (object) [
            'currentPage' => $page,
            'lastPage' => $pages,
            'hasPreviousPage' => $page > 1,
            'hasNextPage' => $page < $pages,
            'previousPage' => max(1, $page - 1),
            'nextPage' => min($pages, $page + 1),
        ];
    }
}
