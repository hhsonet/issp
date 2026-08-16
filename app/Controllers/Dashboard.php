<?php

namespace App\Controllers;

use App\Models\ApplicationRoundModel;
use App\Models\InternshipApplicationModel;
use App\Models\UserModel;

class Dashboard extends BaseController
{
    public function index(): string
    {
        return $this->renderPage('dashboard/index', 'Dashboard', $this->dashboardData('dashboard'));
    }

    public function profile(): string
    {
        $user = $this->currentUser();

        return $this->renderPage('profile/index', 'Profile Settings', $this->dashboardData('profile') + [
            'profileUser' => $user,
            'profileErrors' => session('errors') ?? [],
        ]);
    }

    public function updateProfile()
    {
        $user = $this->currentUser();

        $rules = [
            'full_name' => 'required|trim|min_length[3]|max_length[150]',
            'phone' => 'required|regex_match[/^(?:01[3-9]\d{8}|\+8801[3-9]\d{8})$/]|max_length[20]',
            'gender_identity' => 'required|in_list[Woman,Man,Gender Diverse Individuals]',
            'date_of_birth' => 'required|valid_date[Y-m-d]',
            'disability_status' => 'required|in_list[Yes,No]',
            'disability_type' => 'permit_empty|max_length[255]',
            'ethnic_minority_status' => 'required|in_list[Yes,No]',
            'ethnic_group_name' => 'permit_empty|max_length[255]',
        ];

        if (($this->request->getPost('disability_status') ?? '') === 'Yes') {
            $rules['disability_type'] = 'required|max_length[255]';
        }
        if (($this->request->getPost('ethnic_minority_status') ?? '') === 'Yes') {
            $rules['ethnic_group_name'] = 'required|max_length[255]';
        }

        if (! $this->validate($rules)) {
            return redirect()->to(site_url('profile'))
                ->withInput([
                    'full_name' => $this->request->getPost('full_name'),
                    'phone' => $this->request->getPost('phone'),
                    'gender_identity' => $this->request->getPost('gender_identity'),
                    'date_of_birth' => $this->request->getPost('date_of_birth'),
                    'disability_status' => $this->request->getPost('disability_status'),
                    'disability_type' => $this->request->getPost('disability_type'),
                    'ethnic_minority_status' => $this->request->getPost('ethnic_minority_status'),
                    'ethnic_group_name' => $this->request->getPost('ethnic_group_name'),
                ])
                ->with('errors', $this->validator->getErrors())
                ->with('error', 'Please check the highlighted fields.');
        }

        $disabilityStatus = trim((string) $this->request->getPost('disability_status'));
        $ethnicMinorityStatus = trim((string) $this->request->getPost('ethnic_minority_status'));
        $disabilityType = trim((string) $this->request->getPost('disability_type'));
        $ethnicGroupName = trim((string) $this->request->getPost('ethnic_group_name'));
        if ($disabilityStatus !== 'Yes') {
            $disabilityType = '';
        }
        if ($ethnicMinorityStatus !== 'Yes') {
            $ethnicGroupName = '';
        }

        $input = [
            'full_name' => trim((string) $this->request->getPost('full_name')),
            'phone' => $this->normalizeBangladeshiPhone((string) $this->request->getPost('phone')),
            'gender_identity' => trim((string) $this->request->getPost('gender_identity')),
            'date_of_birth' => trim((string) $this->request->getPost('date_of_birth')),
            'disability_status' => $disabilityStatus,
            'disability_type' => $disabilityType,
            'ethnic_minority_status' => $ethnicMinorityStatus,
            'ethnic_group_name' => $ethnicGroupName,
        ];

        if ($input['phone'] === '' || ! preg_match('/^(?:01[3-9]\d{8}|\+8801[3-9]\d{8})$/', $input['phone'])) {
            return redirect()->to(site_url('profile'))
                ->withInput($input)
                ->with('errors', ['phone' => 'Please enter a valid Bangladeshi mobile number.'])
                ->with('error', 'Please check the highlighted fields.');
        }

        if ($input['disability_status'] === 'Yes' && $input['disability_type'] === '') {
            return redirect()->to(site_url('profile'))
                ->withInput($input)
                ->with('errors', ['disability_type' => 'Please specify the type of disability.'])
                ->with('error', 'Please check the highlighted fields.');
        }

        $existingPhone = (new UserModel())
            ->where('phone', $input['phone'])
            ->where('id !=', $user['id'])
            ->first();

        if ($existingPhone) {
            return redirect()->to(site_url('profile'))
                ->withInput($input)
                ->with('errors', ['phone' => 'An account already exists with this phone number.'])
                ->with('error', 'Please check the highlighted fields.');
        }

        $db = db_connect();
        $userModel = new UserModel();

        try {
            $db->transStart();
            $userModel->update($user['id'], [
                'full_name' => $input['full_name'],
                'phone' => $input['phone'],
                'gender_identity' => $input['gender_identity'],
            'date_of_birth' => $input['date_of_birth'],
            'disability_status' => $input['disability_status'],
            'disability_type' => $input['disability_type'] !== '' ? $input['disability_type'] : null,
            'ethnic_minority_status' => $input['ethnic_minority_status'],
            'ethnic_group_name' => $input['ethnic_group_name'] !== '' ? $input['ethnic_group_name'] : null,
            ]);
            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Profile update transaction failed.');
            }
        } catch (\Throwable $e) {
            log_message('error', 'Profile update failed: {message}', ['message' => $e->getMessage()]);
            return redirect()->to(site_url('profile'))
                ->withInput($input)
                ->with('error', 'Unable to update your profile. Please try again.');
        }

        session()->set([
            'full_name' => $input['full_name'],
        ]);

        return redirect()->to(site_url('profile'))
            ->with('success', 'Your profile has been updated successfully.');
    }

    public function updatePassword()
    {
        $user = $this->currentUser();

        $currentPassword = trim((string) $this->request->getPost('current_password'));
        $newPassword = trim((string) $this->request->getPost('new_password'));
        $confirmNewPassword = trim((string) $this->request->getPost('confirm_new_password'));

        if ($currentPassword === '' && $newPassword === '' && $confirmNewPassword === '') {
            return redirect()->to(site_url('profile'));
        }

        $rules = [
            'current_password' => 'required',
            'new_password' => 'required|min_length[8]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).+$/]',
            'confirm_new_password' => 'required|matches[new_password]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to(site_url('profile'))
                ->with('errors', $this->validator->getErrors())
                ->with('error', 'Please check the highlighted fields.');
        }

        if (! password_verify($currentPassword, (string) $user['password_hash'])) {
            return redirect()->to(site_url('profile'))
                ->with('error', 'Current password is incorrect.');
        }

        if (password_verify($newPassword, (string) $user['password_hash'])) {
            return redirect()->to(site_url('profile'))
                ->with('error', 'New password must be different from the current password.');
        }

        try {
            (new UserModel())->update($user['id'], [
                'password_hash' => password_hash($newPassword, PASSWORD_DEFAULT),
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'Password update failed: {message}', ['message' => $e->getMessage()]);
            return redirect()->to(site_url('profile'))
                ->with('error', 'Unable to update your password. Please try again.');
        }

        return redirect()->to(site_url('profile'))
            ->with('success', 'Your password has been updated successfully.');
    }

    public function applications(): string
    {
        return $this->renderPage('applications/index', 'My Applications', $this->dashboardData('applications'));
    }

    private function renderPage(string $view, string $title, array $data): string
    {
        return view($view, array_merge([
            'title' => $title,
            'page' => $data['page'] ?? 'dashboard',
            'year' => date('Y'),
        ], $data));
    }

    private function dashboardData(string $page): array
    {
        $user = [
            'user_id' => (int) session('user_id'),
            'full_name' => (string) session('full_name'),
            'email' => (string) session('email'),
            'is_logged_in' => (bool) session('is_logged_in'),
        ];

        $now = date('Y-m-d H:i:s');
        $db = db_connect();
        $openCalls = [];
        $applicationHistory = [];

        try {
            if ($db->tableExists('application_rounds') && $db->tableExists('internship_applications')) {
                $openCalls = $db->table('application_rounds ar')
                    ->select('ar.id, ar.round_code, ar.title, ar.description, ar.opens_at, ar.closes_at, COUNT(ia.id) as applications_count')
                    ->join('internship_applications ia', 'ia.round_id = ar.id', 'left')
                    ->where('ar.status', 'Open')
                    ->where('ar.opens_at <=', $now)
                    ->where('ar.closes_at >=', $now)
                    ->where('NOT EXISTS (SELECT 1 FROM internship_applications ia2 WHERE ia2.round_id = ar.id AND ia2.user_id = ' . (int) $user['user_id'] . ')', null, false)
                    ->groupBy('ar.id')
                    ->orderBy('ar.closes_at', 'asc')
                    ->get()
                    ->getResultArray();

                $applicationHistory = $db->table('internship_applications ia')
                    ->select('ia.id, ia.status, ia.submitted_at, ar.round_code, ar.title as round_title')
                    ->join('application_rounds ar', 'ar.id = ia.round_id', 'left')
                    ->where('ia.user_id', $user['user_id'])
                    ->orderBy('ia.submitted_at', 'desc')
                    ->get()
                    ->getResultArray();
            }
        } catch (\Throwable $e) {
            $openCalls = [];
            $applicationHistory = [];
        }

        return [
            'page' => $page,
            'user' => $user,
            'summary' => [
                'profile_completion' => 20,
                'total_applications' => 0,
                'draft_applications' => 0,
                'submitted_applications' => 0,
            ],
            'checklist' => [
                ['label' => 'Basic information', 'done' => false],
                ['label' => 'Contact information', 'done' => false],
                ['label' => 'Institutional information', 'done' => false],
                ['label' => 'Supporting documents', 'done' => false],
                ['label' => 'Account verification', 'done' => false],
            ],
            'applications' => [],
            'openCalls' => array_map(function (array $round) use ($now) {
                $round['remaining_label'] = $this->deadlineLabel($round['closes_at'], $now);
                $round['effective_status'] = $now < $round['opens_at'] ? 'Upcoming' : 'Accepting Applications';
                return $round;
            }, $openCalls),
            'applicationHistory' => $applicationHistory,
            'announcements' => [
                'Application guidelines are now available',
                'Check the required documents before applying',
                'Contact support if you face technical difficulties',
            ],
            'downloads' => [
                ['title' => 'Project Operations Manual', 'url' => base_url('uploads/documents/project-operations-manual.pdf'), 'available' => is_file(FCPATH . 'uploads/documents/project-operations-manual.pdf')],
                ['title' => 'Application Guidelines', 'url' => base_url('uploads/documents/application-guidelines.pdf'), 'available' => is_file(FCPATH . 'uploads/documents/application-guidelines.pdf')],
                ['title' => 'Evaluation Guidelines', 'url' => base_url('uploads/documents/evaluation-guidelines.pdf'), 'available' => is_file(FCPATH . 'uploads/documents/evaluation-guidelines.pdf')],
            ],
        ];
    }

    private function currentUser(): array
    {
        $userId = (int) session('user_id');
        $user = (new UserModel())->find($userId);

        if (! $user) {
            redirect()->to(site_url('login'))->with('error', 'Your session has expired. Please refresh and try again.')->send();
            exit;
        }

        return $user;
    }

    private function normalizeBangladeshiPhone(string $phone): string
    {
        $phone = preg_replace('/[\s\-\(\)]/', '', trim($phone)) ?? '';

        if (preg_match('/^(\+8801[3-9]\d{8})$/', $phone)) {
            return $phone;
        }

        if (preg_match('/^(01[3-9]\d{8})$/', $phone)) {
            return $phone;
        }

        if (preg_match('/^(8801[3-9]\d{8})$/', $phone)) {
            return '+' . $phone;
        }

        return $phone;
    }

    private function deadlineLabel(string $closesAt, string $now): string
    {
        $remaining = strtotime($closesAt) - strtotime($now);
        if ($remaining <= 0) {
            return 'Deadline passed';
        }

        $days = (int) floor($remaining / 86400);
        if ($days >= 1) {
            return $days === 1 ? '1 day remaining' : $days . ' days remaining';
        }

        $hours = (int) floor($remaining / 3600);
        if ($hours >= 1) {
            return $hours === 1 ? '1 hour remaining' : $hours . ' hours remaining';
        }

        $minutes = max(1, (int) floor($remaining / 60));
        return $minutes === 1 ? '1 minute remaining' : $minutes . ' minutes remaining';
    }
}
