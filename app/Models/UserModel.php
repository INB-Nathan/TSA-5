<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['username', 'password', 'email', 'created_at'];
    protected $useTimestamps = false;
    
    /**
     * Get users with their roles (for pagination)
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
     * Get total count of users
     */
    public function getTotalUsers()
    {
        return $this->db->table('users')
            ->countAllResults();
    }
}
