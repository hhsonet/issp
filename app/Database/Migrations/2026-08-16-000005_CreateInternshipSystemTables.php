<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInternshipSystemTables extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 190,
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('name');
        $this->forge->createTable('universities', true);

        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'university_id' => [
                'type'       => 'BIGINT',
                'unsigned'   => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 190,
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('university_id');
        $this->forge->addForeignKey('university_id', 'universities', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addUniqueKey(['university_id', 'name']);
        $this->forge->createTable('departments', true);

        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'round_number' => [
                'type'       => 'INT',
                'unsigned'   => true,
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => 190,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'opens_at' => [
                'type' => 'DATETIME',
            ],
            'closes_at' => [
                'type' => 'DATETIME',
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'Draft',
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('round_number');
        $this->forge->createTable('application_rounds', true);

        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'round_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
            ],
            'user_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
            ],
            'full_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'gender_identity' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'student_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'university_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
            ],
            'department_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
            ],
            'current_cgpa' => [
                'type'       => 'DECIMAL',
                'constraint' => '4,2',
            ],
            'total_credits' => [
                'type'       => 'DECIMAL',
                'constraint' => '8,2',
            ],
            'earned_credits' => [
                'type'       => 'DECIMAL',
                'constraint' => '8,2',
            ],
            'internship_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'team_member_count' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
            ],
            'supervisor_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'supervisor_email' => [
                'type'       => 'VARCHAR',
                'constraint' => 190,
            ],
            'supervisor_university' => [
                'type'       => 'VARCHAR',
                'constraint' => 190,
            ],
            'supervisor_department' => [
                'type'       => 'VARCHAR',
                'constraint' => 190,
            ],
            'supervisor_designation' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'supervisor_phone' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'internship_start_date' => [
                'type' => 'DATE',
            ],
            'internship_end_date' => [
                'type' => 'DATE',
            ],
            'placement_organization_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 190,
            ],
            'organization_website_url' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'mentor_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'mentor_email' => [
                'type'       => 'VARCHAR',
                'constraint' => 190,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'Submitted',
            ],
            'submitted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('round_id');
        $this->forge->addKey('user_id');
        $this->forge->addKey('university_id');
        $this->forge->addKey('department_id');
        $this->forge->addUniqueKey(['round_id', 'user_id']);
        $this->forge->addForeignKey('round_id', 'application_rounds', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('university_id', 'universities', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('department_id', 'departments', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('internship_applications', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('internship_applications', true);
        $this->forge->dropTable('application_rounds', true);
        $this->forge->dropTable('departments', true);
        $this->forge->dropTable('universities', true);
    }
}
