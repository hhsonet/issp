<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEligibilityFieldsToInternshipApplications extends Migration
{
    public function up(): void
    {
        $fields = [];

        if (! $this->db->fieldExists('credit_completion_percentage', 'internship_applications')) {
            $fields['credit_completion_percentage'] = [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'null'       => true,
                'after'      => 'earned_credits',
            ];
        }

        if (! $this->db->fieldExists('information_declaration', 'internship_applications')) {
            $fields['information_declaration'] = [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => true,
                'after'      => 'credit_completion_percentage',
            ];
        }

        if (! $this->db->fieldExists('declared_at', 'internship_applications')) {
            $fields['declared_at'] = [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'information_declaration',
            ];
        }

        if ($fields !== []) {
            $this->forge->addColumn('internship_applications', $fields);
        }
    }

    public function down(): void
    {
        foreach (['declared_at', 'information_declaration', 'credit_completion_percentage'] as $field) {
            if ($this->db->fieldExists($field, 'internship_applications')) {
                $this->forge->dropColumn('internship_applications', $field);
            }
        }
    }
}
