<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterUsersTableForSignupFields extends Migration
{
    public function up(): void
    {
        if ($this->db->fieldExists('disability_type', 'users')) {
            $this->forge->modifyColumn('users', [
                'disability_type' => [
                    'name'       => 'disability_type',
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => true,
                ],
            ]);
        }

        if ($this->db->fieldExists('ethnic_group_name', 'users')) {
            $this->forge->modifyColumn('users', [
                'ethnic_group_name' => [
                    'name'       => 'ethnic_group_name',
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => true,
                ],
            ]);
        }
    }

    public function down(): void
    {
        if ($this->db->fieldExists('disability_type', 'users')) {
            $this->forge->modifyColumn('users', [
                'disability_type' => [
                    'name'       => 'disability_type',
                    'type'       => 'VARCHAR',
                    'constraint' => 120,
                    'null'       => true,
                ],
            ]);
        }

        if ($this->db->fieldExists('ethnic_group_name', 'users')) {
            $this->forge->modifyColumn('users', [
                'ethnic_group_name' => [
                    'name'       => 'ethnic_group_name',
                    'type'       => 'VARCHAR',
                    'constraint' => 150,
                    'null'       => true,
                ],
            ]);
        }
    }
}
