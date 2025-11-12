<?php

namespace App\Models;

use CodeIgniter\Model;

class ItemModel extends Model
{
    protected $table = 'items';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'description', 'price', 'category_id', 'image_url'];
    protected $useTimestamps = false;
    

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

    public function getTotalItems()
    {
        return $this->db->table('items')
            ->countAllResults();
    }
}
