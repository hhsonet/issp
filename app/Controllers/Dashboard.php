<?php

namespace App\Controllers;

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
        ];

        if (! $this->validate($rules)) {
            return redirect()->to(site_url('profile'))
                ->withInput([
                    'full_name' => $this->request->getPost('full_name'),
                    'phone' => $this->request->getPost('phone'),
                    'gender_identity' => $this->request->getPost('gender_identity'),
                ])
                ->with('errors', $this->validator->getErrors())
                ->with('error', 'Please check the highlighted fields.');
        }

        $input = [
            'full_name' => trim((string) $this->request->getPost('full_name')),
            'phone' => $this->normalizeBangladeshiPhone((string) $this->request->getPost('phone')),
            'gender_identity' => trim((string) $this->request->getPost('gender_identity')),
        ];

        if ($input['phone'] === '' || ! preg_match('/^(?:01[3-9]\d{8}|\+8801[3-9]\d{8})$/', $input['phone'])) {
            return redirect()->to(site_url('profile'))
                ->withInput($input)
                ->with('errors', ['phone' => 'Please enter a valid Bangladeshi mobile number.'])
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
}
