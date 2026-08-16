<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterApplicationRoundsForAdminCalls extends Migration
{
    public function up(): void
    {
        $db = db_connect();
        $driver = strtolower((string) $db->DBDriver);

        if (! $db->tableExists('application_rounds')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'BIGINT',
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'round_number' => [
                    'type'       => 'INT',
                    'unsigned'   => true,
                    'null'       => true,
                ],
                'round_code' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 6,
                    'null'       => true,
                ],
                'title' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
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
                'created_by' => [
                    'type'     => 'BIGINT',
                    'unsigned' => true,
                    'null'     => true,
                ],
                'updated_by' => [
                    'type'     => 'BIGINT',
                    'unsigned' => true,
                    'null'     => true,
                ],
                'created_at' => ['type' => 'DATETIME', 'null' => true],
                'updated_at' => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addUniqueKey('round_code');
            $this->forge->createTable('application_rounds', true);
        }

        if (! $db->fieldExists('round_code', 'application_rounds')) {
            $fields = [
                'round_code' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 6,
                    'after'      => 'id',
                    'null'       => true,
                ],
            ];
            $this->forge->addColumn('application_rounds', $fields);
        }

        if (! $db->fieldExists('created_by', 'application_rounds')) {
            $this->forge->addColumn('application_rounds', [
                'created_by' => [
                    'type'     => 'BIGINT',
                    'unsigned' => true,
                    'null'     => true,
                    'after'    => 'status',
                ],
            ]);
        }

        if (! $db->fieldExists('updated_by', 'application_rounds')) {
            $this->forge->addColumn('application_rounds', [
                'updated_by' => [
                    'type'     => 'BIGINT',
                    'unsigned' => true,
                    'null'     => true,
                    'after'    => 'created_by',
                ],
            ]);
        }

        if (! str_contains($driver, 'sqlite')) {
            $index = $db->query("SELECT COUNT(*) AS count FROM information_schema.statistics WHERE table_schema = DATABASE() AND table_name = 'application_rounds' AND index_name = 'round_code_unique'")->getRowArray();
            if ((int) ($index['count'] ?? 0) === 0) {
                $db->query('ALTER TABLE application_rounds ADD UNIQUE KEY round_code_unique (round_code)');
            }
        }
    }

    public function down(): void
    {
        $db = db_connect();

        if ($db->fieldExists('round_code', 'application_rounds')) {
            $this->forge->dropColumn('application_rounds', 'round_code');
        }
        if ($db->fieldExists('created_by', 'application_rounds')) {
            $this->forge->dropColumn('application_rounds', 'created_by');
        }
        if ($db->fieldExists('updated_by', 'application_rounds')) {
            $this->forge->dropColumn('application_rounds', 'updated_by');
        }
    }
}
