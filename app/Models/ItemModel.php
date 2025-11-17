<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Item Model
 * Handles item data operations with category information
 */
class ItemModel extends Model
{
    protected $table = 'items';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'description', 'price', 'category_id', 'image_url'];
    protected $useTimestamps = false;
    

    /**
     * Get paginated list of items with their category names
     * Joins items and categories tables to include category information
     * 
     * @param int $perPage Number of items per page (default: 10)
     * @param int $page Current page number (default: 1)
     * @return array Array of item objects with category_name property
     */
    public function getItemsWithCategories($perPage = 10, $page = 1)
    {
        $offset = ($page - 1) * $perPage;
        
        return $this->db->table('items')
            ->select('items.*, categories.name as category_name')
            ->join('categories', 'items.category_id = categories.id', 'left')
            ->orderBy('items.id', 'DESC')
            ->limit($perPage, $offset)
            ->get()
            ->getResult();
    }

    /**
     * Get total count of all items
     * 
     * @return int Total number of items
     */
    public function getTotalItems()
    {
        return $this->db->table('items')
            ->countAllResults();
    }
}
