<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ExpandUniversitiesForUGCSync extends Migration
{
    public function up(): void
    {
        $db = $this->db;
        $fields = [
            'short_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 190,
                'null'       => true,
                'after'      => 'name',
            ],
            'type' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'Public',
                'after'      => 'short_name',
            ],
            'website_url' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'type',
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'after'      => 'website_url',
            ],
            'source' => [
                'type'       => 'VARCHAR',
                'constraint' => 120,
                'default'    => 'UGC Bangladesh',
                'after'      => 'is_active',
            ],
            'source_url' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'source',
            ],
            'verified_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'source_url',
            ],
        ];

        if (! $db->fieldExists('short_name', 'universities')) {
            $this->forge->addColumn('universities', $fields);
        }

        if ($db->fieldExists('name', 'universities')) {
            $db->query('ALTER TABLE universities MODIFY name VARCHAR(190) NOT NULL');
        }

        $indexExists = false;
        try {
            $indexes = $db->query('SHOW INDEX FROM universities WHERE Key_name = "universities_type_index"')->getResultArray();
            $indexExists = ! empty($indexes);
        } catch (\Throwable) {
            $indexExists = false;
        }

        if (! $indexExists) {
            $db->query('CREATE INDEX universities_type_index ON universities (type)');
        }
    }

    public function down(): void
    {
        $db = $this->db;
        $indexExists = false;
        try {
            $indexes = $db->query('SHOW INDEX FROM universities WHERE Key_name = "universities_type_index"')->getResultArray();
            $indexExists = ! empty($indexes);
        } catch (\Throwable) {
            $indexExists = false;
        }

        if ($indexExists) {
            $db->query('DROP INDEX universities_type_index ON universities');
        }

        foreach (['verified_at', 'source_url', 'source', 'is_active', 'website_url', 'type', 'short_name'] as $field) {
            if ($db->fieldExists($field, 'universities')) {
                $this->forge->dropColumn('universities', $field);
            }
        }
    }
}
