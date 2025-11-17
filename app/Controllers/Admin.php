<?php

namespace App\Controllers;

/**
 * Admin Controller
 * Handles all admin operations: user management, item management, and announcements
 */
class Admin extends BaseController
{
    /**
     * Display admin dashboard
     * Shows paginated list of users with their roles (10 per page)
     * 
     * @return string
     */
    public function dashboard()
    {
        $userModel = new \App\Models\UserModel();
        
        $perPage = 10;
        $page = max(1, (int) ($this->request->getVar('page') ?? 1));
        
        $totalUsers = $userModel->getTotalUsers();
        $totalPages = $totalUsers > 0 ? ceil($totalUsers / $perPage) : 1;
        
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
    
    /**
     * Display users management page
     * Shows paginated list of all users with their roles (10 per page)
     * 
     * @return string
     */
    public function users()
    {
        $userModel = new \App\Models\UserModel();
        
        $perPage = 10;
        $page = max(1, (int) ($this->request->getVar('page') ?? 1));
        
        $totalUsers = $userModel->getTotalUsers();
        $totalPages = $totalUsers > 0 ? ceil($totalUsers / $perPage) : 1;
        
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
    
    /**
     * Display user edit form
     * Loads user data, their current role, and all available roles for editing
     * 
     * @param int $id User ID
     * @return \CodeIgniter\HTTP\RedirectResponse|string
     */
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
    
    /**
     * Update user information
     * Validates username/email uniqueness, updates user data, and resets password to "123"
     * Also updates or creates user role assignment
     * 
     * @param int $id User ID
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
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
        
        $data = [
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash('123', PASSWORD_DEFAULT) // Set password to "123"
        ];
        
        $db->table('users')->where('id', $id)->update($data);
        
        $roleId = $this->request->getPost('role_id');
        
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
    
    /**
     * Delete a user
     * Prevents admin from deleting their own account. Deletes user record from database.
     * 
     * @param int $id User ID
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function deleteUser($id)
    {
        $db = \Config\Database::connect();
        $session = session();
        
        if ($id == session()->get('user_id')) {
            $session->setFlashdata('msg', 'You cannot delete your own account.');
            return redirect()->to('/admin/users');
        }
        
        $db->table('users')->where('id', $id)->delete();
        
        $session->setFlashdata('msg', 'User deleted successfully.');
        return redirect()->to('/admin/users');
    }
    
    /**
     * Display items management page
     * Shows paginated list of items with categories (10 per page) and all categories for form
     * 
     * @return string
     */
    public function items()
    {
        $db = \Config\Database::connect();
        $itemModel = new \App\Models\ItemModel();
        
        $perPage = 10;
        $page = max(1, (int) ($this->request->getVar('page') ?? 1));
        
        $totalItems = $itemModel->getTotalItems();
        $totalPages = $totalItems > 0 ? ceil($totalItems / $perPage) : 1;
        
        $page = max(1, min($page, $totalPages));
        
        $items = $itemModel->getItemsWithCategories($perPage, $page);
        
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
    
    /**
     * Create a new item
     * 
     * Handles complex form processing:
     * - Normalizes POST data (converts arrays to strings to prevent validation errors)
     * - Validates item data and optional image upload
     * - Processes image with resize, watermark (text or image), and thumbnail creation
     * - Creates item record and sets availability to true
     * 
     * Watermark options: Supports text watermark (with customizable text, position, font size, color, opacity)
     * or image watermark (with position and opacity). Handles duplicate form field names gracefully.
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function createItem()
    {
        $db = \Config\Database::connect();
        $session = session();
        
        // Normalize POST data - convert arrays to strings to prevent trim() errors
        // This happens when form fields have duplicate names (even if one is hidden)
        $postData = $this->request->getPost();
        $normalizedData = [];
        foreach ($postData as $key => $value) {
            if (is_array($value)) {
                // If it's an array, take the first non-empty value or empty string
                $normalizedData[$key] = !empty($value) ? (string) reset($value) : '';
            } else {
                $normalizedData[$key] = $value;
            }
        }
        // Update the request with normalized data
        foreach ($normalizedData as $key => $value) {
            $_POST[$key] = $value;
        }
        
        $validation = \Config\Services::validation();
        
        $rules = [
            'name' => 'required|min_length[2]|max_length[255]',
            'description' => 'permit_empty|max_length[1000]',
            'price' => 'required|decimal|greater_than[0]',
            'category_id' => 'required|integer',
            'image' => [
                'rules' => 'permit_empty|uploaded[image]|max_size[image,2048]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/gif,image/webp]',
                'errors' => [
                    'max_size' => 'Image size must be less than 2MB.',
                    'is_image' => 'File must be an image.',
                    'mime_in' => 'Image must be JPG, PNG, GIF, or WEBP format.'
                ]
            ]
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }
        
        $imageUrl = '/assets/images/placeholder.jpg';
        $file = $this->request->getFile('image');
        
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $uploadPath = FCPATH . 'assets/images/';
            
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            // Use ImageService for image manipulation
            $imageService = new \App\Libraries\ImageService();
            
            // Get watermark options from form
            $enableWatermark = $this->request->getPost('enable_watermark') === '1';
            $watermarkType = $this->request->getPost('watermark_type') ?? 'text';
            $watermarkText = $this->request->getPost('watermark_text') ?? 'BrewKaholic';
            
            // Helper function to safely get string value (handle arrays)
            $getStringValue = function($value, $default = '') {
                if (is_array($value)) {
                    return (string) ($value[0] ?? $default);
                }
                return is_string($value) ? $value : (string) $default;
            };
            
            // Get position and opacity based on watermark type
            if ($watermarkType === 'image') {
                $watermarkPosition = $getStringValue($this->request->getPost('watermark_position_image'), 'bottom-right');
                $watermarkOpacity = (int) $getStringValue($this->request->getPost('watermark_opacity_image'), 50);
            } else {
                $watermarkPosition = $getStringValue($this->request->getPost('watermark_position'), 'bottom-right');
                $watermarkOpacity = (int) $getStringValue($this->request->getPost('watermark_opacity'), 50);
            }
            
            $watermarkFontSize = (int) $getStringValue($this->request->getPost('watermark_font_size'), 16);
            $watermarkColor = $getStringValue($this->request->getPost('watermark_color'), '#FFFFFF');
            
            // Process image with watermark options
            $processOptions = [
                'maxWidth' => 800,
                'maxHeight' => 800,
                'thumbWidth' => 200,
                'thumbHeight' => 200,
                'quality' => 85,
                'thumbQuality' => 80,
                'createThumbnail' => true,
                'watermark' => $enableWatermark,
                'watermarkType' => $watermarkType,
                'watermarkText' => $watermarkText,
                'watermarkPosition' => $watermarkPosition,
                'watermarkOpacity' => $watermarkOpacity,
                'watermarkFontSize' => $watermarkFontSize,
                'watermarkColor' => $watermarkColor
            ];
            
            // Handle image watermark if selected
            if ($enableWatermark && $watermarkType === 'image') {
                $watermarkFile = $this->request->getFile('watermark_image');
                if ($watermarkFile && $watermarkFile->isValid() && !$watermarkFile->hasMoved()) {
                    $watermarkPath = FCPATH . 'assets/images/watermarks/';
                    if (!is_dir($watermarkPath)) {
                        mkdir($watermarkPath, 0755, true);
                    }
                    $watermarkName = $watermarkFile->getRandomName();
                    if ($watermarkFile->move($watermarkPath, $watermarkName)) {
                        $processOptions['watermarkImage'] = $watermarkPath . $watermarkName;
                    }
                }
            }
            
            $result = $imageService->processUploadedImage($file, $uploadPath, $processOptions);
            
            if ($result && isset($result['original'])) {
                $imageUrl = '/assets/images/' . $result['original'];
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
        
        $itemId = $db->insertID();
        $db->table('item_availability')->insert([
            'item_id' => $itemId,
            'is_available' => true
        ]);
        
        $session->setFlashdata('msg', 'Item created successfully.');
        return redirect()->to('/admin/items');
    }
    
    /**
     * Delete an item
     * 
     * @param int $id Item ID
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function deleteItem($id)
    {
        $db = \Config\Database::connect();
        $session = session();
        
        $db->table('items')->where('id', $id)->delete();
        
        $session->setFlashdata('msg', 'Item deleted successfully.');
        return redirect()->to('/admin/items');
    }
    
    /**
     * Display announcements management page
     * Shows paginated list of announcements (10 per page)
     * 
     * @return string
     */
    public function announcements()
    {
        $announcementModel = new \App\Models\AnnouncementModel();
        
        $perPage = 10;
        $page = max(1, (int) ($this->request->getVar('page') ?? 1));
        
        $totalAnnouncements = $announcementModel->getTotalAnnouncements();
        $totalPages = $totalAnnouncements > 0 ? ceil($totalAnnouncements / $perPage) : 1;
        
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
    
    /**
     * Create a new announcement
     * Validates title (min 3 chars) and content (min 10 chars), then creates announcement record
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
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
    
    /**
     * Delete an announcement
     * 
     * @param int $id Announcement ID
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function deleteAnnouncement($id)
    {
        $db = \Config\Database::connect();
        $session = session();
        
        $db->table('announcements')->where('id', $id)->delete();
        
        $session->setFlashdata('msg', 'Announcement deleted successfully.');
        return redirect()->to('/admin/announcements');
    }
}
