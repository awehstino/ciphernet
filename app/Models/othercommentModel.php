<?php

namespace App\Models;

use CodeIgniter\Model;

class otherCommentModel extends Model
{
    protected $table = 'othercomments';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'dev_id',
        'learning',
        'comment_text', 
        'created_at', 
        'updated_at',
        'user_location',
        'user_state',
        'user_city',
        'user_country',
        'timeSpend',
    ];
    protected $useTimestamps = false;
    protected $createdField = 'created_at';
    // protected $updatedField = 'updated_at';
    protected $useSoftDeletes = false;

    // Method to get comments by project ID with user details
    public function getCommentsByProject($project_id)
    {
        return $this->select('comments.*, users.fullname')
                    ->join('users', 'users.user_id = comments.user_id')
                    ->where('comments.project_id', $project_id)
                    ->orderBy('comments.created_at', 'DESC')
                    ->findAll();
    }
}
