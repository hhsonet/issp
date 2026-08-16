<?php

namespace App\Controllers;

use App\Models\ApplicationRoundModel;
use App\Models\DepartmentModel;
use App\Models\InternshipApplicationModel;
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
}
