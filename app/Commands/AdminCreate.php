<?php

namespace App\Commands;

use App\Models\UserModel;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class AdminCreate extends BaseCommand
{
    protected $group = 'Admin';
    protected $name = 'admin:create';
    protected $description = 'Create a new administrator account.';

    public function run(array $params)
    {
        $userModel = new UserModel();

        $fullName = CLI::prompt('Full name', null, 'required|min_length[3]|max_length[150]');
        $email = strtolower(trim(CLI::prompt('Email address', null, 'required|valid_email|max_length[190]')));
        $phone = trim(CLI::prompt('Phone number (optional)', null, 'permit_empty|max_length[20]'));
        $password = CLI::prompt('Password');
        $confirm = CLI::prompt('Confirm password');

        if (strlen($password) < 8 || ! preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).+$/', $password)) {
            CLI::error('Password must be at least 8 characters and include uppercase, lowercase, number, and special character.');
            return;
        }

        if ($password !== $confirm) {
            CLI::error('Password confirmation does not match.');
            return;
        }

        if ($userModel->where('email', $email)->first()) {
            CLI::error('An account already exists with this email address.');
            return;
        }

        if ($phone !== '' && $userModel->where('phone', $phone)->first()) {
            CLI::error('An account already exists with this phone number.');
            return;
        }

        $data = [
            'full_name' => $fullName,
            'email' => $email,
            'phone' => $phone !== '' ? $phone : null,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'role' => 'admin',
            'is_active' => 1,
            'status' => 'active',
            'email_verified_at' => date('Y-m-d H:i:s'),
        ];

        $userModel->insert($data, true);
        CLI::write('Administrator account created successfully.', 'green');
    }
}
