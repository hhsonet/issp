<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDemographicSurveyFieldsToUsers extends Migration
{
    public function up(): void
    {
        $fields = [
            'date_of_birth' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'phone',
            ],
            'gender_identity' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'after'      => 'date_of_birth',
            ],
            'disability_status' => [
                'type'       => 'VARCHAR',
                'constraint' => 3,
                'null'       => true,
                'after'      => 'gender_identity',
            ],
            'disability_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 120,
                'null'       => true,
                'after'      => 'disability_status',
            ],
            'ethnic_minority_status' => [
                'type'       => 'VARCHAR',
                'constraint' => 3,
                'null'       => true,
                'after'      => 'disability_type',
            ],
            'ethnic_group_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
                'after'      => 'ethnic_minority_status',
            ],
        ];

        $this->forge->addColumn('users', $fields);
    }

    public function down(): void
    {
        $this->forge->dropColumn('users', [
            'date_of_birth',
            'gender_identity',
            'disability_status',
            'disability_type',
            'ethnic_minority_status',
            'ethnic_group_name',
        ]);
    }
}
