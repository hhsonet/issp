<?php

namespace App\Models;

use CodeIgniter\Model;

class InternshipApplicationModel extends Model
{
    protected $table            = 'internship_applications';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;
    protected $useSoftDeletes   = true;
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $deletedField     = 'deleted_at';
    protected $allowedFields    = [
        'application_code',
        'round_id',
        'user_id',
        'full_name',
        'gender_identity',
        'student_id',
        'university_id',
        'department_id',
        'department',
        'other_department',
        'current_cgpa',
        'total_credits',
        'earned_credits',
        'credit_completion_percentage',
        'information_declaration',
        'declared_at',
        'internship_type',
        'team_member_count',
        'supervisor_name',
        'supervisor_email',
        'supervisor_university',
        'supervisor_department',
        'supervisor_designation',
        'supervisor_phone',
        'internship_start_date',
        'internship_end_date',
        'placement_organization_name',
        'organization_website_url',
        'mentor_name',
        'mentor_email',
        'status',
        'submitted_at',
        'edit_enabled',
        'edit_enabled_at',
        'edit_enabled_by',
        'deleted_at',
    ];

    protected $beforeInsert = ['ensureApplicationCode'];

    protected function ensureApplicationCode(array $data): array
    {
        if (! empty($data['data']['application_code'])) {
            return $data;
        }

        $data['data']['application_code'] = $this->generateApplicationCode();
        return $data;
    }

    public function generateApplicationCode(): string
    {
        return 'APP-' . strtoupper(bin2hex(random_bytes(4)));
    }
}
