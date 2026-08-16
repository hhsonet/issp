<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSharedDepartmentFieldsToApplications extends Migration
{
    public function up(): void
    {
        $fields = [];

        if (! $this->db->fieldExists('department', 'internship_applications')) {
            $fields['department'] = [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
                'after'      => 'department_id',
            ];
        }

        if (! $this->db->fieldExists('other_department', 'internship_applications')) {
            $fields['other_department'] = [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
                'after'      => 'department',
            ];
        }

        if ($fields !== []) {
            $this->forge->addColumn('internship_applications', $fields);
        }
    }

    public function down(): void
    {
        foreach (['other_department', 'department'] as $field) {
            if ($this->db->fieldExists($field, 'internship_applications')) {
                $this->forge->dropColumn('internship_applications', $field);
            }
        }
    }
}
