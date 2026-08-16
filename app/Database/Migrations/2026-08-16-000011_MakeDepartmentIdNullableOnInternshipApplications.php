<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MakeDepartmentIdNullableOnInternshipApplications extends Migration
{
    public function up(): void
    {
        if ($this->db->fieldExists('department_id', 'internship_applications')) {
            $this->forge->modifyColumn('internship_applications', [
                'department_id' => [
                    'type'     => 'BIGINT',
                    'unsigned' => true,
                    'null'     => true,
                ],
            ]);
        }
    }

    public function down(): void
    {
        if ($this->db->fieldExists('department_id', 'internship_applications')) {
            $this->forge->modifyColumn('internship_applications', [
                'department_id' => [
                    'type'     => 'BIGINT',
                    'unsigned' => true,
                    'null'     => false,
                ],
            ]);
        }
    }
}
