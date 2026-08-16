<?php

namespace App\Models;

use CodeIgniter\Model;

class ApplicationRoundModel extends Model
{
    protected $table            = 'application_rounds';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $allowedFields    = [
        'round_number',
        'title',
        'description',
        'opens_at',
        'closes_at',
        'status',
    ];
}
