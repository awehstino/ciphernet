<?php

namespace App\Models;

use CodeIgniter\Model;

class ProjectModel extends Model
{
    protected $table = 'projects';
    protected $primaryKey = 'project_id';
    protected $allowedFields = [
        'project_name',
        'project_desc',
        'isComplete',
        'pending',
        'inprogress',
        'execute_date',
        'developers',
    ];
    protected $useTimestamps = false;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Add validation rules if needed
    protected $validationRules = [
        'project_name' => 'required|max_length[255]',
        'project_desc' => 'required',
        'execute_date' => 'required|valid_date',
        // Add more validation rules as needed
    ];

    protected $validationMessages = [
        'project_name' => [
            'required' => 'Project name is required',
            'max_length' => 'Project name cannot exceed 255 characters',
        ],
        'project_desc' => [
            'required' => 'Project description is required',
        ],
        'execute_date' => [
            'required' => 'Execute date is required',
            'valid_date' => 'Execute date must be a valid date',
        ],
    ];
}
