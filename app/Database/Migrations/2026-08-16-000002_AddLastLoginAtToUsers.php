<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLastLoginAtToUsers extends Migration
{
    public function up(): void
    {
        $fields = [
            'last_login_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'updated_at',
            ],
        ];

        $this->forge->addColumn('users', $fields);
    }

    public function down(): void
    {
        $this->forge->dropColumn('users', 'last_login_at');
    }
}
