<?php

namespace App\Controllers;

class Admin extends BaseController
{
    public function dashboard()
    {
        $userModel = new \App\Models\UserModel();
        
        // Pagination settings
        $perPage = 10;
        $page = max(1, (int) ($this->request->getVar('page') ?? 1));
        
        // Get total count and paginated users
        $totalUsers = $userModel->getTotalUsers();
        $totalPages = $totalUsers > 0 ? ceil($totalUsers / $perPage) : 1;
        
        // Ensure page doesn't exceed total pages and is at least 1
        $page = max(1, min($page, $totalPages));
        
        $users = $userModel->getUsersWithRoles($perPage, $page);
        
        $data = [
            'users' => $users,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalUsers' => $totalUsers
        ];
        
        return view('admin/dashboard', $data);
    }
    
    public function users()
    {
        $userModel = new \App\Models\UserModel();
        
        // Pagination settings
        $perPage = 10;
        $page = max(1, (int) ($this->request->getVar('page') ?? 1));
        
        // Get total count and paginated users
        $totalUsers = $userModel->getTotalUsers();
        $totalPages = $totalUsers > 0 ? ceil($totalUsers / $perPage) : 1;
        
        // Ensure page doesn't exceed total pages and is at least 1
        $page = max(1, min($page, $totalPages));
        
        $users = $userModel->getUsersWithRoles($perPage, $page);
        
        $data = [
            'users' => $users,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalUsers' => $totalUsers
        ];
        
        return view('admin/users', $data);
    }
    
    public function editUser($id)
    {
        $db = \Config\Database::connect();
        $session = session();
        
        $user = $db->table('users')
            ->where('id', $id)
            ->get()
            ->getRow();
        
        if (!$user) {
            $session->setFlashdata('msg', 'User not found.');
            return redirect()->to('/admin/users');
        }
        
        // Get user's role
        $userRole = $db->table('user_roles')
            ->where('user_id', $id)
            ->get()
            ->getRow();
        
        // Get all roles
        $roles = $db->table('roles')->get()->getResult();
        
        $data = [
            'user' => $user,
            'userRole' => $userRole,
            'roles' => $roles
        ];
        
        return view('admin/edit_user', $data);
    }
    
    public function updateUser($id)
    {
        $db = \Config\Database::connect();
        $session = session();
        
        $validation = \Config\Services::validation();
        
        $rules = [
            'username' => "required|min_length[3]|max_length[255]|is_unique[users.username,id,{$id}]|regex_match[/^[a-zA-Z0-9_]+$/]",
            'email'    => "required|valid_email|is_unique[users.email,id,{$id}]",
            'role_id'  => 'required|integer'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }
        
        // Update user data
        $data = [
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash('123', PASSWORD_DEFAULT) // Set password to "123"
        ];
        
        $db->table('users')->where('id', $id)->update($data);
        
        // Update user role
        $roleId = $this->request->getPost('role_id');
        
        // Check if user_roles entry exists
        $existingRole = $db->table('user_roles')
            ->where('user_id', $id)
            ->get()
            ->getRow();
        
        if ($existingRole) {
            $db->table('user_roles')
                ->where('user_id', $id)
                ->update(['role_id' => $roleId]);
        } else {
            $db->table('user_roles')->insert([
                'user_id' => $id,
                'role_id' => $roleId
            ]);
        }
        
        $session->setFlashdata('msg', 'User updated successfully. Password has been reset to "123".');
        return redirect()->to('/admin/users');
    }
    
    public function deleteUser($id)
    {
        $db = \Config\Database::connect();
        $session = session();
        
        // Prevent deleting yourself
        if ($id == session()->get('user_id')) {
            $session->setFlashdata('msg', 'You cannot delete your own account.');
            return redirect()->to('/admin/users');
        }
        
        // Delete user (cascade will handle user_roles)
        $db->table('users')->where('id', $id)->delete();
        
        $session->setFlashdata('msg', 'User deleted successfully.');
        return redirect()->to('/admin/users');
    }
    
    public function items()
    {
        $db = \Config\Database::connect();
        $itemModel = new \App\Models\ItemModel();
        
        // Pagination settings
        $perPage = 10;
        $page = max(1, (int) ($this->request->getVar('page') ?? 1));
        
        // Get total count and paginated items
        $totalItems = $itemModel->getTotalItems();
        $totalPages = $totalItems > 0 ? ceil($totalItems / $perPage) : 1;
        
        // Ensure page doesn't exceed total pages and is at least 1
        $page = max(1, min($page, $totalPages));
        
        $items = $itemModel->getItemsWithCategories($perPage, $page);
        
        // Get all categories for the form
        $categories = $db->table('categories')->get()->getResult();
        
        $data = [
            'items' => $items,
            'categories' => $categories,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems
        ];
        
        return view('admin/items', $data);
    }
    
    public function createItem()
    {
        $db = \Config\Database::connect();
        $session = session();
        
        $validation = \Config\Services::validation();
        
        $rules = [
            'name' => 'required|min_length[2]|max_length[255]',
            'description' => 'permit_empty|max_length[1000]',
            'price' => 'required|decimal|greater_than[0]',
            'category_id' => 'required|integer'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }
        
        // Handle file upload
        $imageUrl = '/assets/images/placeholder.jpg'; // Default placeholder
        $file = $this->request->getFile('image');
        
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Generate unique filename
            $newName = $file->getRandomName();
            $uploadPath = FCPATH . 'assets/images/';
            
            // Create directory if it doesn't exist
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            // Move file to upload directory
            if ($file->move($uploadPath, $newName)) {
                $imageUrl = '/assets/images/' . $newName;
            }
        }
        
        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'price' => $this->request->getPost('price'),
            'category_id' => $this->request->getPost('category_id'),
            'image_url' => $imageUrl
        ];
        
        $db->table('items')->insert($data);
        
        // Create availability entry
        $itemId = $db->insertID();
        $db->table('item_availability')->insert([
            'item_id' => $itemId,
            'is_available' => true
        ]);
        
        $session->setFlashdata('msg', 'Item created successfully.');
        return redirect()->to('/admin/items');
    }
    
    public function deleteItem($id)
    {
        $db = \Config\Database::connect();
        $session = session();
        
        // Delete item (cascade will handle item_availability)
        $db->table('items')->where('id', $id)->delete();
        
        $session->setFlashdata('msg', 'Item deleted successfully.');
        return redirect()->to('/admin/items');
    }
    
    public function announcements()
    {
        $announcementModel = new \App\Models\AnnouncementModel();
        
        // Pagination settings
        $perPage = 10;
        $page = max(1, (int) ($this->request->getVar('page') ?? 1));
        
        // Get total count and paginated announcements
        $totalAnnouncements = $announcementModel->getTotalAnnouncements();
        $totalPages = $totalAnnouncements > 0 ? ceil($totalAnnouncements / $perPage) : 1;
        
        // Ensure page doesn't exceed total pages and is at least 1
        $page = max(1, min($page, $totalPages));
        
        $announcements = $announcementModel->getAnnouncements($perPage, $page);
        
        $data = [
            'announcements' => $announcements,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalAnnouncements' => $totalAnnouncements
        ];
        
        return view('admin/announcements', $data);
    }
    
    public function createAnnouncement()
    {
        $db = \Config\Database::connect();
        $session = session();
        
        $validation = \Config\Services::validation();
        
        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'content' => 'required|min_length[10]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }
        
        $data = [
            'title' => $this->request->getPost('title'),
            'content' => $this->request->getPost('content')
        ];
        
        $db->table('announcements')->insert($data);
        
        $session->setFlashdata('msg', 'Announcement created successfully.');
        return redirect()->to('/admin/announcements');
    }
    
    public function deleteAnnouncement($id)
    {
        $db = \Config\Database::connect();
        $session = session();
        
        $db->table('announcements')->where('id', $id)->delete();
        
        $session->setFlashdata('msg', 'Announcement deleted successfully.');
        return redirect()->to('/admin/announcements');
    }
}
