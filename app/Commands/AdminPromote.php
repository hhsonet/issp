<?php

namespace App\Commands;

use App\Models\UserModel;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class AdminPromote extends BaseCommand
{
    protected $group = 'Admin';
    protected $name = 'admin:promote';
    protected $description = 'Promote an existing user to administrator.';

    public function run(array $params)
    {
        $email = strtolower(trim($params[0] ?? ''));
        if ($email === '') {
            CLI::error('Please provide an email address.');
            return;
        }

        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();
        if (! $user) {
            CLI::error('User not found.');
            return;
        }

        $confirm = strtoupper(trim(CLI::prompt('Promote this user to administrator? Type YES to continue')));
        if ($confirm !== 'YES') {
            CLI::write('Promotion cancelled.');
            return;
        }

        $userModel->update($user['id'], ['role' => 'admin', 'is_active' => 1]);
        CLI::write('User promoted to administrator successfully.', 'green');
    }
}
