<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddApplicationListIndexes extends Migration
{
    public function up(): void
    {
        $table = 'internship_applications';
        foreach (['round_id', 'status', 'university_id', 'submitted_at', 'deleted_at'] as $field) {
            if (! $this->hasIndex($table, $field . '_idx')) {
                $this->db->query('ALTER TABLE `' . $table . '` ADD INDEX `' . $field . '_idx` (`' . $field . '`)');
            }
        }
    }

    public function down(): void
    {
        $table = 'internship_applications';
        foreach (['round_id_idx', 'status_idx', 'university_id_idx', 'submitted_at_idx', 'deleted_at_idx'] as $index) {
            if ($this->hasIndex($table, $index)) {
                $this->db->query('ALTER TABLE `' . $table . '` DROP INDEX `' . $index . '`');
            }
        }
    }

    private function hasIndex(string $table, string $index): bool
    {
        $rows = $this->db->query('SHOW INDEX FROM `' . $table . '`')->getResultArray();
        foreach ($rows as $row) {
            if (($row['Key_name'] ?? '') === $index) {
                return true;
            }
        }
        return false;
    }
}
