<?php

namespace App\Models;

use CodeIgniter\Model;

class InternshipApplicationModel extends Model
{
    protected $table            = 'internship_applications';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $allowedFields    = [
        'round_id',
        'user_id',
        'full_name',
        'gender_identity',
        'student_id',
        'university_id',
        'department_id',
        'current_cgpa',
        'total_credits',
        'earned_credits',
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
    ];
}
