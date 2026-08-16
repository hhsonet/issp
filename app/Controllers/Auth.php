<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    private const GENDER_IDENTITIES = ['Woman', 'Man', 'Gender Diverse Individuals'];
    private const YES_NO = ['Yes', 'No'];

    public function login()
    {
        if (session()->get('is_logged_in') === true) {
            return redirect()->to(site_url('dashboard'));
        }
        return view('auth/login', [
            'title' => 'Sign in',
            'errors' => session('errors') ?? [],
        ]);
    }

    public function signup()
    {
        if (session()->get('is_logged_in') === true) {
            return redirect()->to(site_url('dashboard'));
        }
        return view('auth/signup', [
            'title' => 'Create account',
            'errors' => session('errors') ?? [],
        ]);
    }

    public function attemptLogin()
    {
        if (! $this->validate($this->loginRules())) {
            return redirect()->to(site_url('login'))
                ->withInput(['email' => $this->request->getPost('email')])
                ->with('errors', $this->validator->getErrors())
                ->with('error', 'Please check the highlighted fields.');
        }

        $email = strtolower(trim((string) $this->request->getPost('email')));
        $password = (string) $this->request->getPost('password');
        $user = (new UserModel())->where('email', $email)->first();

        if (! $user || ! password_verify($password, $user['password_hash']) || ! in_array($user['status'] ?? '', ['active'], true) || ! empty($user['deleted_at'])) {
            return redirect()->back()
                ->withInput(['email' => $email])
                ->with('error', 'Invalid email address or password.');
        }

        session()->regenerate();
        session()->set([
            'is_logged_in' => true,
            'user_id' => $user['id'],
            'full_name' => $user['full_name'],
            'email' => $user['email'],
        ]);

        $userModel = new UserModel();
        $db = db_connect();
        if ($db->fieldExists('last_login_at', 'users')) {
            $userModel->update($user['id'], ['last_login_at' => date('Y-m-d H:i:s')]);
        }

        session()->remove('return_to');

        return redirect()->to(site_url('dashboard'))
            ->with('success', 'Signed in successfully.');
    }

    public function attemptSignup()
    {
        $input = $this->normalizeSignupInput();
        if (! $this->validate($this->signupRules($input))) {
            return redirect()->to(site_url('signup'))
                ->withInput($this->preserveSignupInput($input))
                ->with('errors', $this->validator->getErrors())
                ->with('error', 'Please check the highlighted fields.');
        }

        if ($input['date_of_birth'] !== '' && strtotime($input['date_of_birth']) > strtotime(date('Y-m-d'))) {
            return redirect()->to(site_url('signup'))
                ->withInput($this->preserveSignupInput($input))
                ->with('errors', ['date_of_birth' => 'Please enter a valid date of birth.'])
                ->with('error', 'Please check the highlighted fields.');
        }

        $userModel = new UserModel();

        try {
            $db = db_connect();
            $db->transStart();

            if ($userModel->where('email', $input['email'])->first()) {
                $db->transRollback();
                return redirect()->to(site_url('signup'))->withInput($this->preserveSignupInput($input))->with('error', 'An account already exists with this email address.');
            }

            if ($userModel->where('phone', $input['phone'])->first()) {
                $db->transRollback();
                return redirect()->to(site_url('signup'))->withInput($this->preserveSignupInput($input))->with('error', 'An account already exists with this phone number.');
            }

            $data = [
                'full_name' => trim($input['full_name']),
                'email' => $input['email'],
                'phone' => $input['phone'],
                'password_hash' => password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT),
                'status' => 'active',
                'email_verified_at' => null,
            ];

            if ($db->fieldExists('gender', 'users')) {
                $data['gender'] = strtolower(str_replace(' ', '_', $input['gender_identity']));
            }

            if ($db->fieldExists('gender_identity', 'users')) {
                $data['gender_identity'] = $input['gender_identity'];
            }

            if ($db->fieldExists('date_of_birth', 'users')) {
                $data['date_of_birth'] = $input['date_of_birth'];
            }

            if ($db->fieldExists('disability_status', 'users')) {
                $data['disability_status'] = $input['disability_status'];
            }

            if ($db->fieldExists('disability_type', 'users')) {
                $data['disability_type'] = $input['disability_status'] === 'Yes' ? $input['disability_type'] : null;
            }

            if ($db->fieldExists('ethnic_minority_status', 'users')) {
                $data['ethnic_minority_status'] = $input['ethnic_minority_status'];
            }

            if ($db->fieldExists('ethnic_group_name', 'users')) {
                $data['ethnic_group_name'] = $input['ethnic_minority_status'] === 'Yes' ? $input['ethnic_group_name'] : null;
            }

            $userModel->insert($data, true);
            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Signup transaction failed.');
            }
        } catch (\Throwable $e) {
            $message = $this->signupExceptionMessage($e);
            if ($message === 'An account already exists with this email address.' || $message === 'An account already exists with this phone number.') {
                return redirect()->to(site_url('signup'))->withInput($this->preserveSignupInput($input))->with('error', $message);
            }
            log_message('error', 'Signup failed: {message}', ['message' => $e->getMessage()]);
            return redirect()->to(site_url('signup'))->withInput($this->preserveSignupInput($input))->with('error', 'Unable to create your account. Please try again.');
        }

        return redirect()->to(site_url('login'))->with('success', 'Your account has been created successfully. Please sign in.');
    }

    public function forgotPassword(): string
    {
        return view('auth/forgot_password', ['title' => 'Reset password', 'errors' => session('errors') ?? [], 'success' => session('success')]);
    }

    public function sendResetLink()
    {
        if (! $this->validate(['email' => 'required|valid_email|max_length[150]'])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        return redirect()->back()->with('success', 'If an account exists for that email, reset instructions will be sent.');
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        $session->start();
        return redirect()->to(site_url('login'))->with('success', 'You have been signed out successfully.');
    }

    private function loginRules(): array
    {
        return [
            'email' => 'required|valid_email|max_length[150]',
            'password' => 'required|min_length[8]|max_length[255]',
        ];
    }

    private function signupRules(array $input = []): array
    {
        $disabilityTypeRules = 'permit_empty|max_length[255]';
        if (($input['disability_status'] ?? '') === 'Yes') {
            $disabilityTypeRules = 'required|max_length[255]';
        }

        $ethnicGroupRules = 'permit_empty|max_length[255]';
        if (($input['ethnic_minority_status'] ?? '') === 'Yes') {
            $ethnicGroupRules = 'required|max_length[255]';
        }

        return [
            'full_name' => 'required|trim|min_length[3]|max_length[150]',
            'email' => 'required|valid_email|max_length[190]',
            'phone' => 'required|regex_match[/^(?:01[3-9]\d{8}|\+8801[3-9]\d{8})$/]|max_length[20]',
            'gender_identity' => 'required|in_list[' . implode(',', self::GENDER_IDENTITIES) . ']',
            'date_of_birth' => 'required|valid_date[Y-m-d]',
            'disability_status' => 'required|in_list[' . implode(',', self::YES_NO) . ']',
            'disability_type' => $disabilityTypeRules,
            'ethnic_minority_status' => 'required|in_list[' . implode(',', self::YES_NO) . ']',
            'ethnic_group_name' => $ethnicGroupRules,
            'password' => 'required|min_length[8]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).+$/]',
            'confirm_password' => 'required|matches[password]',
        ];
    }

    private function normalizeSignupInput(): array
    {
        $email = strtolower(trim((string) $this->request->getPost('email')));
        $phone = $this->normalizeBangladeshiPhone((string) $this->request->getPost('phone'));
        $disabilityStatus = trim((string) $this->request->getPost('disability_status'));
        $ethnicMinorityStatus = trim((string) $this->request->getPost('ethnic_minority_status'));
        $genderIdentity = trim((string) $this->request->getPost('gender_identity'));
        $dateOfBirth = trim((string) $this->request->getPost('date_of_birth'));
        $disabilityType = trim((string) $this->request->getPost('disability_type'));
        $ethnicGroupName = trim((string) $this->request->getPost('ethnic_group_name'));

        if ($disabilityStatus !== 'Yes') {
            $disabilityType = '';
        }

        if ($ethnicMinorityStatus !== 'Yes') {
            $ethnicGroupName = '';
        }

        return [
            'full_name' => trim((string) $this->request->getPost('full_name')),
            'email' => $email,
            'phone' => $phone,
            'gender_identity' => $genderIdentity,
            'date_of_birth' => $dateOfBirth,
            'disability_status' => $disabilityStatus,
            'disability_type' => $disabilityType,
            'ethnic_minority_status' => $ethnicMinorityStatus,
            'ethnic_group_name' => $ethnicGroupName,
        ];
    }

    private function preserveSignupInput(array $input): array
    {
        return [
            'full_name' => $input['full_name'],
            'email' => $input['email'],
            'phone' => $input['phone'],
            'gender_identity' => $input['gender_identity'],
            'date_of_birth' => $input['date_of_birth'],
            'disability_status' => $input['disability_status'],
            'disability_type' => $input['disability_type'],
            'ethnic_minority_status' => $input['ethnic_minority_status'],
            'ethnic_group_name' => $input['ethnic_group_name'],
        ];
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

    private function signupExceptionMessage(\Throwable $e): string
    {
        if ((int) $e->getCode() === 1062 || str_contains(strtolower($e->getMessage()), 'duplicate')) {
            $message = strtolower($e->getMessage());
            if (str_contains($message, 'email')) {
                return 'An account already exists with this email address.';
            }

            if (str_contains($message, 'phone')) {
                return 'An account already exists with this phone number.';
            }
        }

        return 'Unable to create your account. Please try again.';
    }

}
