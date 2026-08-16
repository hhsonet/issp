<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPublicApplicationCodes extends Migration
{
    public function up(): void
    {
        $fields = [];

        if (! $this->db->fieldExists('application_code', 'internship_applications')) {
            $fields['application_code'] = [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'after'      => 'id',
            ];
        }

        if (! $this->db->fieldExists('edit_enabled', 'internship_applications')) {
            $fields['edit_enabled'] = [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'after'      => 'submitted_at',
            ];
        }

        if (! $this->db->fieldExists('edit_enabled_at', 'internship_applications')) {
            $fields['edit_enabled_at'] = [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'edit_enabled',
            ];
        }

        if (! $this->db->fieldExists('edit_enabled_by', 'internship_applications')) {
            $fields['edit_enabled_by'] = [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
                'after'    => 'edit_enabled_at',
            ];
        }

        if (! $this->db->fieldExists('deleted_at', 'internship_applications')) {
            $fields['deleted_at'] = [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'edit_enabled_by',
            ];
        }

        if ($fields !== []) {
            $this->forge->addColumn('internship_applications', $fields);
        }

        if (! $this->db->getIndexData('internship_applications')['application_code_unique'] ?? false) {
            $this->forge->addUniqueKey('application_code', 'application_code_unique');
        }

        $builder = $this->db->table('internship_applications');
        $rows = $builder->select('id, application_code')->where('application_code IS NULL', null, false)->get()->getResultArray();
        foreach ($rows as $row) {
            $code = 'APP-' . strtoupper(bin2hex(random_bytes(4)));
            while ($builder->where('application_code', $code)->countAllResults() > 0) {
                $code = 'APP-' . strtoupper(bin2hex(random_bytes(4)));
            }
            $builder->where('id', $row['id'])->update(['application_code' => $code]);
        }
    }

    public function down(): void
    {
        foreach (['deleted_at', 'edit_enabled_by', 'edit_enabled_at', 'edit_enabled', 'application_code'] as $field) {
            if ($this->db->fieldExists($field, 'internship_applications')) {
                $this->forge->dropColumn('internship_applications', $field);
            }
        }
    }
}
