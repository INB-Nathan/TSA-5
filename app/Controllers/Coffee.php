<?php

namespace App\Controllers;

class Coffee extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        
        $items = $db->table('items')
            ->select('items.*, categories.name as category_name')
            ->join('categories', 'items.category_id = categories.id', 'left')
            ->orderBy('items.id', 'ASC')
            ->get()
            ->getResult();
        
        $categories = $db->table('categories')->get()->getResult();
        
        $announcements = [];
        try {
            $announcements = $db->table('announcements')
                ->orderBy('created_at', 'DESC')
                ->get()
                ->getResult();
        } catch (\Exception $e) {
            $announcements = [];
        }
        
        $data = [
            'items' => $items,
            'categories' => $categories,
            'announcements' => $announcements
        ];
        
        return view('coffee_view', $data);
    }
}
