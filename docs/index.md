# Brewkaholic Documentation

Welcome to the documentation for the Brewkaholic application.

This document provides a comprehensive overview of the application, its features, and technical implementation.

## Table of Contents

- [Introduction](#introduction)
- [Features](#features)
- [Database Schema](#database-schema)
- [Controllers](#controllers)
- [Routes](#routes)
- [Filters](#filters)
- [Views](#views)
- [Security](#security)
- [File Structure](#file-structure)

## Introduction

Brewkaholic is a Technical Summative Assessment for Web System Development in FEU-TECH. It is a transactional coffee shop website built with CodeIgniter 4 where customers can browse and view available coffee items, pastries, and announcements. The application provides separate interfaces for customers and administrators with role-based access control.

## Features

### Customer Features
- **Browse Menu**: View available coffee items organized by categories (Hot Coffees, Cold Coffees, Pastries)
- **Category Filtering**: Filter menu items by category using interactive buttons
- **View Announcements**: See site-wide announcements posted by administrators
- **Account Management**: 
  - View account information
  - Change password with strong validation
  - Delete own account with password confirmation (sudo function)

### Admin Features
- **Dashboard**: Overview of registered users and quick access to management features
- **User Management**: 
  - View all registered users
  - Edit user information (username, email, role)
  - Reset user passwords to "123" (automatic when editing)
  - Delete users
- **Item Management**: 
  - Create new menu items with image upload
  - View all items in a table
  - Delete items
  - Optional image upload (defaults to placeholder if not provided)
- **Announcement Management**: 
  - Create announcements with title and content
  - View all announcements
  - Delete announcements

### Security Features
- Session-based authentication
- Role-based access control (Admin/Customer)
- Password hashing using PHP's `password_hash()` with `PASSWORD_DEFAULT`
- Input validation on registration and login forms
- CSRF protection on forms
- Protected routes using filters

## Database Schema

### Tables

#### `users`
Stores user account information.
- `id` (INT, PRIMARY KEY, AUTO_INCREMENT)
- `username` (VARCHAR(255), UNIQUE, NOT NULL)
- `password` (VARCHAR(255), NOT NULL) - Hashed using password_hash()
- `email` (VARCHAR(255), UNIQUE, NOT NULL)
- `created_at` (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)

#### `roles`
Defines user roles.
- `id` (INT, PRIMARY KEY, AUTO_INCREMENT)
- `name` (VARCHAR(255), UNIQUE, NOT NULL)
- Default roles: 'admin' (id=1), 'customer' (id=2)

#### `user_roles`
Many-to-many relationship between users and roles.
- `user_id` (INT, FOREIGN KEY → users.id)
- `role_id` (INT, FOREIGN KEY → roles.id)
- PRIMARY KEY (user_id, role_id)

#### `categories`
Menu item categories.
- `id` (INT, PRIMARY KEY, AUTO_INCREMENT)
- `name` (VARCHAR(255), UNIQUE, NOT NULL)
- `description` (TEXT)
- Default categories: 'Hot Coffees', 'Cold Coffees', 'Pastries'

#### `items`
Menu items/products.
- `id` (INT, PRIMARY KEY, AUTO_INCREMENT)
- `name` (VARCHAR(255), NOT NULL)
- `description` (TEXT)
- `price` (DECIMAL(10, 2), NOT NULL)
- `category_id` (INT, FOREIGN KEY → categories.id, NULL allowed)
- `image_url` (VARCHAR(255)) - Relative path to image file

#### `item_availability`
Tracks item availability status.
- `id` (INT, PRIMARY KEY, AUTO_INCREMENT)
- `item_id` (INT, FOREIGN KEY → items.id)
- `is_available` (BOOLEAN, DEFAULT TRUE)
- `updated_at` (TIMESTAMP, AUTO UPDATE)

#### `announcements`
Site-wide announcements.
- `id` (INT, PRIMARY KEY, AUTO_INCREMENT)
- `title` (VARCHAR(255), NOT NULL)
- `content` (TEXT, NOT NULL)
- `created_at` (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)
- `updated_at` (TIMESTAMP, AUTO UPDATE)

## Input Validation

### Registration Form
- **Username**: Required, alphanumeric and underscores only
- **Email**: Required, valid email format, unique
- **Password**: Required, minimum 8 characters, must contain:
  - At least one uppercase letter
  - At least one lowercase letter
  - At least one number
  - At least one special character
- **Password Confirmation**: Required, must match password

### Login Form
- **Username**: Required
- **Password**: Required

### Announcement Form
- **Title**: Required, 3-255 characters
- **Content**: Required, minimum 10 characters

## Controllers

### `Login` (`app/Controllers/Login.php`)
Handles user authentication.

**Methods:**
- `index()` - Displays login form
- `authenticate()` - Validates credentials and creates session
  - Verifies username and password
  - Retrieves user role from `user_roles` table
  - Sets session variables: `user_id`, `username`, `role_id`, `isLoggedIn`
  - Redirects admin (role_id=1) to `/admin/dashboard`
  - Redirects customer (role_id=2) to `/coffee`
- `logout()` - Destroys session and redirects to login

### `Register` (`app/Controllers/Register.php`)
Handles user registration.

**Methods:**
- `index()` - Displays registration form
- `create()` - Processes registration
  - Validates input (username, email, password)
  - Password requirements: 8+ chars, uppercase, lowercase, number, special character
  - Creates user account with hashed password
  - Assigns default customer role (role_id=2)
  - Redirects to login page

### `Coffee` (`app/Controllers/Coffee.php`)
Customer-facing menu page.

**Methods:**
- `index()` - Displays coffee menu
  - Fetches items with category information
  - Fetches all categories for filtering
  - Fetches announcements
  - Passes data to `coffee_view`

### `Admin` (`app/Controllers/Admin.php`)
Admin panel functionality.

**Methods:**
- `dashboard()` - Admin dashboard overview
  - Lists all registered users with roles
- `users()` - User management page
  - Displays table of all users
- `editUser($id)` - Edit user form
  - Pre-populates form with user data
- `updateUser($id)` - Updates user information
  - Updates username, email
  - Resets password to "123" (hashed)
  - Updates user role
- `deleteUser($id)` - Deletes user account
- `items()` - Item management page
  - Displays form to create items
  - Lists all existing items
- `createItem()` - Creates new menu item
  - Handles optional image upload
  - Stores image in `public/assets/images/`
  - Creates item availability record
- `deleteItem($id)` - Deletes menu item
- `announcements()` - Announcement management page
  - Lists all announcements
- `createAnnouncement()` - Creates new announcement
  - Validates title (3-255 chars) and content (10+ chars)
- `deleteAnnouncement($id)` - Deletes announcement

### `Account` (`app/Controllers/Account.php`)
Customer account management.

**Methods:**
- `index()` - Account settings page
  - Displays user information
  - Shows password change form
  - Shows account deletion form
- `changePassword()` - Changes user password
  - Verifies current password
  - Validates new password (same rules as registration)
  - Updates password hash
- `deleteAccount()` - Deletes user's own account
  - Requires password confirmation (sudo function)
  - Destroys session after deletion

## Routes

### Public Routes
```
GET  /                          → Login::index
POST /login/authenticate       → Login::authenticate
GET  /register                 → Register::index
POST /register/create          → Register::create
GET  /logout                   → Login::logout
```

### Protected Routes (Auth Required)
```
GET  /coffee                   → Coffee::index (filter: auth)
GET  /account                  → Account::index (filter: auth)
POST /account/change-password  → Account::changePassword (filter: auth)
POST /account/delete            → Account::deleteAccount (filter: auth)
```

### Admin Routes (Admin Only)
```
GET  /admin/dashboard                    → Admin::dashboard (filter: admin)
GET  /admin/users                        → Admin::users (filter: admin)
GET  /admin/users/edit/{id}              → Admin::editUser (filter: admin)
POST /admin/users/update/{id}            → Admin::updateUser (filter: admin)
GET  /admin/users/delete/{id}            → Admin::deleteUser (filter: admin)
GET  /admin/items                        → Admin::items (filter: admin)
POST /admin/items/create                 → Admin::createItem (filter: admin)
GET  /admin/items/delete/{id}            → Admin::deleteItem (filter: admin)
GET  /admin/announcements                → Admin::announcements (filter: admin)
POST /admin/announcements/create          → Admin::createAnnouncement (filter: admin)
GET  /admin/announcements/delete/{id}     → Admin::deleteAnnouncement (filter: admin)
```

## Filters

### `AuthFilter` (`app/Filters/AuthFilter.php`)
Protects routes requiring authentication.

**Functionality:**
- Checks if `isLoggedIn` session variable is set
- Redirects to login page if not authenticated
- Used on: `/coffee`, `/account/*`

### `AdminFilter` (`app/Filters/AdminFilter.php`)
Protects admin-only routes.

**Functionality:**
- Checks if user is logged in
- Verifies `role_id == 1` (admin)
- Redirects to login if not authenticated
- Redirects to `/coffee` with error message if not admin
- Used on: `/admin/*`

**Configuration:**
Filters are registered in `app/Config/Filters.php`:
```php
'aliases' => [
    'auth' => \App\Filters\AuthFilter::class,
    'admin' => \App\Filters\AdminFilter::class,
]
```

## Views

### Customer Views

#### `login.php`
Login form with dark theme styling.
- Username and password fields
- Link to registration page
- Error message display

#### `register.php`
Registration form with validation.
- Username (alphanumeric + underscores)
- Email
- Password with requirements display
- Password confirmation
- Link to login page

#### `coffee_view.php`
Main customer menu page.
- Hero section with background image
- Announcements section (if announcements exist)
- Category filter buttons
- Dynamic menu items display
- Responsive flexbox layout
- Dark theme styling

#### `account/index.php`
Account settings page.
- User information display
- Password change form
- Account deletion form with sudo confirmation

### Admin Views

#### `admin/dashboard.php`
Admin dashboard.
- User statistics
- Quick links to management pages
- Table of registered users

#### `admin/users.php`
User management interface.
- Table listing all users
- Edit and Delete actions per user

#### `admin/edit_user.php`
User editing form.
- Pre-populated username and email fields
- Role selection dropdown
- Notice about password reset

#### `admin/items.php`
Item management interface.
- Form to create new items
  - Name, description, price fields
  - Category dropdown
  - Image file upload (optional)
- Table listing all items with delete action

#### `admin/announcements.php`
Announcement management interface.
- Form to create announcements
  - Title and content fields
- List of existing announcements with delete action


## Security

### Authentication
- Session-based authentication
- Passwords hashed using `password_hash()` with `PASSWORD_DEFAULT`
- Session variables: `user_id`, `username`, `role_id`, `isLoggedIn`

### Authorization
- Role-based access control
- Admin role: `role_id = 1`
- Customer role: `role_id = 2`
- Protected routes using filters

### Input Validation
- Registration form: Username, email, password strength
- Login form: Username and password required
- Password requirements: 8+ chars, uppercase, lowercase, number, special character
- Announcement form: Title and content length validation

### Security Considerations
- CSRF protection on forms
- SQL injection protection via Query Builder
- XSS protection via `esc()` function in views
- Password hashing prevents plain text storage
- File uploads stored in `public/assets/images/`

## File Structure

```
brewkaholic/
├── app/
│   ├── Config/
│   │   ├── Database.php          # Database configuration
│   │   ├── Filters.php           # Filter registration
│   │   └── Routes.php            # Route definitions
│   ├── Controllers/
│   │   ├── Account.php            # Account management
│   │   ├── Admin.php             # Admin panel
│   │   ├── Coffee.php            # Customer menu
│   │   ├── Login.php              # Authentication
│   │   └── Register.php          # User registration
│   ├── Filters/
│   │   ├── AdminFilter.php       # Admin access control
│   │   └── AuthFilter.php        # Authentication check
│   └── Views/
│       ├── login.php              # Login form
│       ├── register.php           # Registration form
│       ├── coffee_view.php        # Customer menu page
│       ├── account/
│       │   └── index.php          # Account settings
│       └── admin/
│           ├── dashboard.php      # Admin dashboard
│           ├── users.php          # User management
│           ├── edit_user.php      # Edit user form
│           ├── items.php          # Item management
│           └── announcements.php  # Announcement management
├── public/
│   ├── assets/
│   │   └── images/               # Uploaded images
│   └── index.php                 # Entry point
├── docs/
│   └── index.md                  # This documentation
└── schema.sql                    # Database schema

```


### Image Uploads
- Images are uploaded to `public/assets/images/`
- No security validation is currently applied
- Default placeholder: `/assets/images/placeholder.jpg`
- Unique filenames generated using `getRandomName()`


