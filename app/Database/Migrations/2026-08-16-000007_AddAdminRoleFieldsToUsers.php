<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAdminRoleFieldsToUsers extends Migration
{
    public function up(): void
    {
        if (! $this->db->fieldExists('role', 'users')) {
            $this->forge->addColumn('users', [
                'role' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 20,
                    'default'    => 'user',
                    'after'      => 'status',
                ],
            ]);
        }

        if (! $this->db->fieldExists('is_active', 'users')) {
            $this->forge->addColumn('users', [
                'is_active' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'default'    => 1,
                    'after'      => 'role',
                ],
            ]);
        }
    }

    public function down(): void
    {
        if ($this->db->fieldExists('is_active', 'users')) {
            $this->forge->dropColumn('users', 'is_active');
        }

        if ($this->db->fieldExists('role', 'users')) {
            $this->forge->dropColumn('users', 'role');
        }
    }
}
