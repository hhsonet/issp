<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = [
        'full_name',
        'email',
        'phone',
        'password_hash',
        'gender',
        'gender_identity',
        'date_of_birth',
        'disability_status',
        'disability_type',
        'ethnic_minority_status',
        'ethnic_group_name',
        'status',
        'role',
        'is_active',
        'email_verified_at',
        'last_login_at',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $validationRules = [];
}
