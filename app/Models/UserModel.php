<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * User Model
 * Handles user data operations with role information
 */
class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['username', 'password', 'email', 'created_at'];
    protected $useTimestamps = false;
    

    /**
     * Get paginated list of users with their role names
     * Joins users, user_roles, and roles tables to include role information
     * 
     * @param int $perPage Number of users per page (default: 10)
     * @param int $page Current page number (default: 1)
     * @return array Array of user objects with role_name property
     */
    public function getUsersWithRoles($perPage = 10, $page = 1)
    {
        $offset = ($page - 1) * $perPage;
        
        return $this->db->table('users')
            ->select('users.*, roles.name as role_name')
            ->join('user_roles', 'users.id = user_roles.user_id', 'left')
            ->join('roles', 'user_roles.role_id = roles.id', 'left')
            ->orderBy('users.created_at', 'DESC')
            ->limit($perPage, $offset)
            ->get()
            ->getResult();
    }
    

    /**
     * Get total count of all users
     * 
     * @return int Total number of users
     */
    public function getTotalUsers()
    {
        return $this->db->table('users')
            ->countAllResults();
    }
}
