<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Announcement Model
 * Handles announcement data operations
 */
class AnnouncementModel extends Model
{
    protected $table = 'announcements';
    protected $primaryKey = 'id';
    protected $allowedFields = ['title', 'content'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    /**
     * Get paginated list of announcements
     * Ordered by creation date (newest first)
     * 
     * @param int $perPage Number of announcements per page (default: 10)
     * @param int $page Current page number (default: 1)
     * @return array Array of announcement objects
     */
    public function getAnnouncements($perPage = 10, $page = 1)
    {
        $offset = ($page - 1) * $perPage;
        
        return $this->db->table('announcements')
            ->orderBy('created_at', 'DESC')
            ->limit($perPage, $offset)
            ->get()
            ->getResult();
    }
    

    /**
     * Get total count of all announcements
     * 
     * @return int Total number of announcements
     */
    public function getTotalAnnouncements()
    {
        return $this->db->table('announcements')
            ->countAllResults();
    }
}
