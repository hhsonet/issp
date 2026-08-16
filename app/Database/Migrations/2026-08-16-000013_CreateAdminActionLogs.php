<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAdminActionLogs extends Migration
{
    public function up(): void
    {
        if ($this->db->tableExists('admin_action_logs')) {
            return;
        }

        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'admin_user_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],
            'action' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'entity_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'entity_public_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'reason' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'metadata' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('admin_user_id');
        $this->forge->addKey('entity_public_code');
        $this->forge->createTable('admin_action_logs', true);
    }

    public function down(): void
    {
        if ($this->db->tableExists('admin_action_logs')) {
            $this->forge->dropTable('admin_action_logs', true);
        }
    }
}
