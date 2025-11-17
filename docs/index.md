# Brewkaholic Documentation

Welcome to the documentation for the Brewkaholic application.

This document provides a comprehensive overview of the application, its features, and technical implementation.

## Table of Contents

- [Introduction](#introduction)
- [Features](#features)
- [Database Schema](#database-schema)
  - [Setup](#setup)
  - [Complete Schema Structure](#complete-schema-structure)
  - [Tables](#tables)
- [Controllers](#controllers)
- [Routes](#routes)
- [Filters](#filters)
- [Libraries](#libraries)
- [Models](#models)
- [Password Reset Feature](#password-reset-feature)
- [Views](#views)
- [Security](#security)
- [Pagination](#pagination)
- [Session Management](#session-management)
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
- **Password Reset**: 
  - Request password reset via email from login page
  - Secure token-based password reset (1-hour expiration)
  - Email notifications for password changes

### Admin Features
- **Dashboard**: Overview of registered users and quick access to management features
  - Paginated user list (10 users per page)
  - Session information display
- **User Management**: 
  - View all registered users with pagination (10 per page)
  - Edit user information (username, email, role)
  - Reset user passwords to "123" (automatic when editing)
  - Delete users
- **Item Management**: 
  - Create new menu items with image upload
  - View all items in a paginated table (10 per page)
  - Delete items
  - Optional image upload (defaults to placeholder if not provided)
- **Announcement Management**: 
  - Create announcements with title and content
  - View all announcements with pagination (10 per page)
  - Delete announcements

### Security Features
- Session-based authentication with timeout management
- Session ID regeneration on login/logout
- Activity tracking and automatic session expiration (2 hours)
- Role-based access control (Admin/Customer)
- Password hashing using PHP's `password_hash()` with `PASSWORD_DEFAULT`
- Input validation on registration and login forms
- CSRF protection on forms
- Protected routes using filters
- Security logging for login/logout events
- Password reset with secure token generation (64-char hex, 1-hour expiration)
- Email enumeration prevention (generic success messages)
- Single-use password reset tokens

## Database Schema

### Setup

The complete database schema is defined in `schema.sql`. This file creates all tables, indexes, and initial data including password reset functionality.

**To set up the database:**
```bash
mysql -u your_username -p < schema.sql
```

**Or manually in MySQL:**
```sql
-- Execute the entire schema.sql file
```

### Complete Schema Structure

The `schema.sql` file includes:

**Database:**
- `CREATE DATABASE IF NOT EXISTS brewkaholic`

**Tables:**
- `roles` - User roles (admin, customer)
- `users` - User accounts with password reset support
- `user_roles` - Many-to-many relationship between users and roles
- `categories` - Menu item categories
- `items` - Menu items/products
- `item_availability` - Item availability tracking
- `announcements` - Site-wide announcements

**Initial Data:**
- Roles: 'admin' (id=1), 'customer' (id=2)
- Categories: 'Hot Coffees', 'Cold Coffees', 'Pastries'

**Indexes:**
- `idx_reset_token` on `users(reset_token)` - For fast password reset token lookups
- Primary keys and foreign keys as defined in table schemas

### Tables

#### `users`
Stores user account information.
- `id` (INT, PRIMARY KEY, AUTO_INCREMENT)
- `username` (VARCHAR(255), UNIQUE, NOT NULL)
- `password` (VARCHAR(255), NOT NULL) - Hashed using password_hash()
- `email` (VARCHAR(255), UNIQUE, NOT NULL)
- `created_at` (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)
- `reset_token` (VARCHAR(64), NULL) - Password reset token (indexed)
- `reset_token_expires` (DATETIME, NULL) - Token expiration datetime

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
Handles user authentication with comprehensive session management.

**Methods:**
- `index()` - Displays login form
  - Redirects already logged-in users to appropriate page
- `authenticate()` - Validates credentials and creates session
  - Validates input (username and password required)
  - Verifies username and password
  - Regenerates session ID for security
  - Retrieves user role from `user_roles` table
  - Sets session variables: `user_id`, `username`, `email`, `role_id`, `isLoggedIn`, `last_activity`, `login_time`
  - Logs successful login attempts
  - Redirects admin (role_id=1) to `/admin/dashboard`
  - Redirects customer (role_id=2) to `/coffee`
- `logout()` - Securely destroys session
  - Logs logout event
  - Removes all session data
  - Regenerates session ID
  - Destroys session completely
  - Sets flash message and redirects to login

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
Admin panel functionality with pagination support.

**Methods:**
- `dashboard()` - Admin dashboard overview
  - Lists paginated registered users (10 per page)
  - Uses `UserModel` for data retrieval
  - Calculates pagination parameters
- `users()` - User management page
  - Displays paginated table of all users (10 per page)
  - Uses `UserModel::getUsersWithRoles()` and `UserModel::getTotalUsers()`
  - Handles page parameter from query string
- `editUser($id)` - Edit user form
  - Pre-populates form with user data
- `updateUser($id)` - Updates user information
  - Updates username, email
  - Resets password to "123" (hashed)
  - Updates user role
- `deleteUser($id)` - Deletes user account
- `items()` - Item management page
  - Displays form to create items
  - Lists paginated existing items (10 per page)
  - Uses `ItemModel` for data retrieval
  - Fetches categories for dropdown
- `createItem()` - Creates new menu item
  - Handles optional image upload
  - Stores image in `public/assets/images/`
  - Creates item availability record
- `deleteItem($id)` - Deletes menu item
- `announcements()` - Announcement management page
  - Lists paginated announcements (10 per page)
  - Uses `AnnouncementModel` for data retrieval
- `createAnnouncement()` - Creates new announcement
  - Validates title (3-255 chars) and content (10+ chars)
- `deleteAnnouncement($id)` - Deletes announcement

### `Account` (`app/Controllers/Account.php`)
Customer account management and password reset functionality.

**Methods:**
- `index()` - Account settings page
  - Displays user information
  - Shows password change form
  - Shows account deletion form
- `changePassword()` - Changes user password
  - Verifies current password
  - Validates new password (same rules as registration: min 8 chars, uppercase, lowercase, number, special character)
  - Updates password hash in database
  - Sends password change notification email
  - Redirects to account page with success/error messages
- `requestPasswordReset()` - Request password reset (public access)
  - Accepts email address from POST data (no authentication required)
  - Validates email format
  - Generates secure random token (64 hex characters)
  - Stores token and expiration (1 hour) in database
  - Sends password reset email with reset link
  - Always shows success message (prevents email enumeration attacks)
  - Works from login page's "Forgot Password" form
- `resetPassword($token)` - Reset password using token
  - **GET Request**: Validates token and expiration, displays reset password form if valid
  - **POST Request**: Validates token, new password requirements, updates password, clears reset token
  - Token expiration: 1 hour from generation
  - Sends password reset confirmation email on success
  - Redirects to login page with success/error messages
- `deleteAccount()` - Deletes user's own account
  - Requires password confirmation (sudo function)
  - Verifies password before deletion
  - Destroys session after deletion
  - Redirects to login page

## Routes

### Public Routes
```
GET  /                          → Login::index
POST /login/authenticate       → Login::authenticate
GET  /register                 → Register::index
POST /register/create          → Register::create
GET  /logout                   → Login::logout

# Password Reset Routes (Public Access)
GET  /request-password-reset   → Redirects to login (form embedded there)
POST /request-password-reset   → Account::requestPasswordReset
GET  /reset-password/{token}   → Account::resetPassword (displays form)
POST /reset-password           → Account::resetPassword (processes reset)
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
Protects routes requiring authentication with session timeout handling.

**Class Documentation:**
- Protects routes requiring user authentication
- Checks if user is logged in and validates session timeout (2 hours)
- Redirects to login page if not authenticated or session expired

**Methods:**
- `before($request, $arguments)` - Execute before request processing
  - Validates user authentication and session timeout
  - Session timeout: 2 hours (7200 seconds)
  - Updates `last_activity` timestamp on successful validation
  - Redirects to login if not authenticated or session expired
- `after($request, $response, $arguments)` - Execute after request processing
  - Currently unused - reserved for future functionality

**Functionality:**
- Checks if `isLoggedIn` session variable is set
- Validates session timeout (2 hours inactivity)
- Updates `last_activity` timestamp on each request
- Destroys expired sessions and redirects to login
- Redirects to login page if not authenticated
- Used on: `/coffee`, `/account/*`

### `AdminFilter` (`app/Filters/AdminFilter.php`)
Protects admin-only routes with session timeout handling.

**Class Documentation:**
- Protects admin routes requiring admin privileges
- Extends AuthFilter functionality by also checking if user has admin role (role_id = 1)
- Validates authentication, session timeout (2 hours), and admin role
- Redirects non-admin users to coffee page

**Methods:**
- `before($request, $arguments)` - Execute before request processing
  - Validates user authentication, session timeout, and admin role
  - Session timeout: 2 hours (7200 seconds)
  - Admin role check: role_id must equal 1
  - Updates `last_activity` timestamp on successful validation
  - Redirects to login if not authenticated, or coffee page if not admin
- `after($request, $response, $arguments)` - Execute after request processing
  - Currently unused - reserved for future functionality

**Functionality:**
- Checks if user is logged in
- Validates session timeout (2 hours inactivity)
- Updates `last_activity` timestamp on each request
- Verifies `role_id == 1` (admin)
- Destroys expired sessions and redirects to login
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
- Session ID regeneration on login/logout (prevents session fixation)
- Session timeout: 2 hours of inactivity
- Activity tracking: `last_activity` updated on each request
- Passwords hashed using `password_hash()` with `PASSWORD_DEFAULT`
- Session variables: `user_id`, `username`, `email`, `role_id`, `isLoggedIn`, `last_activity`, `login_time`
- Security logging for login/logout events

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

## Pagination

Pagination has been implemented across all admin management pages to improve performance and user experience when dealing with large datasets. The pagination system displays 10 items per page and provides navigation controls.

### Implementation Overview

The pagination system uses a manual implementation approach with CodeIgniter 4's Query Builder, utilizing `limit()` and `offset()` methods. This approach provides fine-grained control over pagination behavior and ensures consistent display across all admin pages.

### Pages with Pagination

The following admin pages implement pagination:

1. **Admin Dashboard** (`/admin/dashboard`) - Registered users list
2. **User Management** (`/admin/users`) - Users table
3. **Item Management** (`/admin/items`) - Items table
4. **Announcement Management** (`/admin/announcements`) - Announcements list

### Technical Implementation

#### Models

Three models have been created to encapsulate pagination logic:

**`UserModel`** (`app/Models/UserModel.php`)
- `getUsersWithRoles($perPage, $page)` - Retrieves paginated users with role information
- `getTotalUsers()` - Returns total count of users

**`ItemModel`** (`app/Models/ItemModel.php`)
- `getItemsWithCategories($perPage, $page)` - Retrieves paginated items with category information
- `getTotalItems()` - Returns total count of items

**`AnnouncementModel`** (`app/Models/AnnouncementModel.php`)
- `getAnnouncements($perPage, $page)` - Retrieves paginated announcements
- `getTotalAnnouncements()` - Returns total count of announcements

#### Controller Logic

Each controller method implementing pagination follows this pattern:

```php
public function users()
{
    $userModel = new UserModel();
    
    // Pagination settings
    $perPage = 10;
    $page = max(1, (int) ($this->request->getVar('page') ?? 1));
    
    // Get total count and calculate total pages
    $totalUsers = $userModel->getTotalUsers();
    $totalPages = $totalUsers > 0 ? ceil($totalUsers / $perPage) : 1;
    $page = max(1, min($page, $totalPages)); // Ensure page is within bounds
    
    // Fetch paginated data
    $users = $userModel->getUsersWithRoles($perPage, $page);
    
    // Pass data to view
    $data = [
        'users' => $users,
        'currentPage' => $page,
        'totalPages' => $totalPages,
        'totalUsers' => $totalUsers,
    ];
    
    return view('admin/users', $data);
}
```

#### View Implementation

Pagination controls are rendered using custom PHP logic in each view:

**Features:**
- Previous/Next buttons with disabled state when at boundaries
- Page number links with active state highlighting
- Ellipsis (...) for large page ranges
- "Showing X to Y of Z items" counter
- Always visible pagination (even with fewer than 10 items)

**Example Pagination HTML Structure:**
```php
<div class="pagination">
    <!-- Previous Button -->
    <?php if ($currentPage > 1) : ?>
        <a href="/admin/users?page=<?= $currentPage - 1 ?>" class="btn">Previous</a>
    <?php else : ?>
        <span class="btn disabled">Previous</span>
    <?php endif; ?>
    
    <!-- Page Numbers -->
    <?php for ($i = $startPage; $i <= $endPage; $i++) : ?>
        <?php if ($i == $currentPage) : ?>
            <span class="active"><?= $i ?></span>
        <?php else : ?>
            <a href="/admin/users?page=<?= $i ?>" class="btn"><?= $i ?></a>
        <?php endif; ?>
    <?php endfor; ?>
    
    <!-- Next Button -->
    <?php if ($currentPage < $totalPages) : ?>
        <a href="/admin/users?page=<?= $currentPage + 1 ?>" class="btn">Next</a>
    <?php else : ?>
        <span class="btn disabled">Next</span>
    <?php endif; ?>
</div>
```

### Pagination Behavior

1. **Items Per Page**: Fixed at 10 items per page across all paginated pages
2. **Always Visible**: Pagination controls are always displayed, even when there are fewer than 10 items
3. **Disabled States**: 
   - "Previous" button is disabled on the first page
   - "Next" button is disabled on the last page
4. **Page Range Display**: Shows up to 5 page numbers (current page ± 2) with ellipsis for larger ranges
5. **URL Parameters**: Uses `?page=N` query parameter for navigation

### Styling

Pagination controls use custom CSS that matches the application's dark theme:

- `.pagination` - Container with flexbox layout
- `.pagination .btn` - Clickable page buttons
- `.pagination .active` - Current page indicator
- `.pagination .disabled` - Disabled button state (non-clickable)

### Benefits

- **Performance**: Reduces database load by limiting queries to 10 records per page
- **User Experience**: Easier navigation through large datasets
- **Consistency**: Uniform pagination behavior across all admin pages
- **Scalability**: Handles growing datasets efficiently

## Session Management

The application implements comprehensive session management for user authentication, security, and user-specific content display. The session system includes timeout handling, activity tracking, and security best practices.

### Session Configuration

**File**: `app/Config/Session.php`

**Key Settings:**
- **Driver**: FileHandler (sessions stored in `writepath/session/`)
- **Cookie Name**: `ci_session`
- **Expiration**: 7200 seconds (2 hours)
- **Time to Update**: 300 seconds (5 minutes) - Session ID regeneration interval
- **Regenerate Destroy**: false (old session data cleaned by garbage collector)

### Session Data Structure

When a user logs in, the following session variables are set:

```php
[
    'user_id' => int,           // User's database ID
    'username' => string,        // User's username
    'email' => string,          // User's email address
    'role_id' => int,           // User's role (1=admin, 2=customer)
    'isLoggedIn' => bool,       // Authentication flag
    'last_activity' => int,     // Unix timestamp of last activity
    'login_time' => int         // Unix timestamp of login
]
```

### Login Process

**File**: `app/Controllers/Login.php`

**`authenticate()` Method:**

1. **Input Validation**: Validates that both username and password are provided
2. **Credential Verification**: Checks username and password against database
3. **Session Regeneration**: Regenerates session ID to prevent session fixation attacks
4. **Role Retrieval**: Fetches user's role from `user_roles` table
5. **Session Data Setting**: Stores user information and timestamps
6. **Activity Logging**: Logs successful login attempts
7. **Redirect**: 
   - Admin (role_id=1) → `/admin/dashboard`
   - Customer (role_id=2) → `/coffee`

**Security Features:**
- Session ID regeneration on login (`$session->regenerate(true)`)
- Password verification using `password_verify()`
- Failed login attempt logging

### Logout Process

**File**: `app/Controllers/Login.php`

**`logout()` Method:**

1. **Logging**: Logs logout event (if username available)
2. **Session Data Removal**: Removes all session variables
3. **Session Regeneration**: Regenerates session ID
4. **Session Destruction**: Completely destroys the session
5. **Flash Message**: Sets success message
6. **Redirect**: Redirects to login page

**Security Features:**
- Complete session cleanup
- Session ID regeneration to prevent session hijacking
- Proper session destruction

### Session Timeout

**Implementation**: `app/Filters/AuthFilter.php` and `app/Filters/AdminFilter.php`

**Timeout Duration**: 2 hours (7200 seconds)

**Process:**
1. On each authenticated request, filters check `last_activity` timestamp
2. If `(current_time - last_activity) > 7200`, session is expired
3. Session is destroyed and user is redirected to login with expiration message
4. If session is valid, `last_activity` is updated to current time

**Benefits:**
- Automatic logout after inactivity
- Prevents indefinite session persistence
- Security against abandoned sessions

### Activity Tracking

**Last Activity Update:**
- Updated on every authenticated request via filters
- Stored as Unix timestamp in `last_activity` session variable
- Used for timeout calculation

**Login Time Tracking:**
- Set once during login
- Stored in `login_time` session variable
- Used for displaying session information to users

### Authentication Filters

#### AuthFilter

**File**: `app/Filters/AuthFilter.php`

**Functionality:**
- Checks if user is logged in (`isLoggedIn` session variable)
- Validates session timeout
- Updates `last_activity` timestamp
- Redirects to login if not authenticated or session expired

**Applied To**: Customer routes (`/coffee`, `/account/*`)

#### AdminFilter

**File**: `app/Filters/AdminFilter.php`

**Functionality:**
- Checks if user is logged in
- Validates session timeout
- Verifies admin role (`role_id == 1`)
- Updates `last_activity` timestamp
- Redirects appropriately based on authentication/authorization status

**Applied To**: Admin routes (`/admin/*`)

### User-Specific Content

Session data is used throughout the application to display user-specific content:

#### Customer Views

**Coffee View** (`app/Views/coffee_view.php`):
- Username displayed in header: "Welcome, [username]"
- Personalized welcome message in hero section

**Account Page** (`app/Views/account/index.php`):
- Session information display:
  - Login time
  - Last activity time
  - Session expiration countdown (hours and minutes remaining)

#### Admin Views

**All Admin Pages**:
- Username displayed in header: "Admin: [username]"
- Admin dashboard shows session info panel with:
  - Logged in username
  - Email address
  - Login timestamp

### Session Security Features

1. **Session ID Regeneration**
   - On login: Prevents session fixation attacks
   - On logout: Prevents session hijacking
   - Automatic: Every 5 minutes (configurable)

2. **Session Timeout**
   - 2-hour inactivity timeout
   - Automatic logout and cleanup
   - Prevents abandoned sessions

3. **Activity Tracking**
   - Last activity timestamp updated on each request
   - Used for timeout calculation
   - Displayed to users for transparency

4. **Secure Session Storage**
   - Sessions stored server-side (file system)
   - Session ID only stored in secure cookie
   - No sensitive data in cookies

5. **Access Control**
   - Protected routes require valid session
   - Role-based access control using session data
   - Automatic redirects for unauthorized access

### Redirect Protection

**Login Page** (`app/Controllers/Login.php`):
- If user is already logged in, redirects to appropriate page:
  - Admin → `/admin/dashboard`
  - Customer → `/coffee`

**Register Page** (`app/Controllers/Register.php`):
- If user is already logged in, redirects to appropriate page
- Prevents logged-in users from accessing registration

### Session Information Display

Users can view their session information on the Account page:

- **Login Time**: When the current session started
- **Last Activity**: Most recent activity timestamp
- **Time Remaining**: Calculated time until session expiration

This provides transparency and helps users understand their session status.

### Logging

**Login Events:**
- Successful logins: Logged with username and user ID
- Failed login attempts: Logged with attempted username

**Logout Events:**
- Logged with username and user ID (if available)

**Log Location**: CodeIgniter logs (typically `writepath/logs/`)

### Best Practices Implemented

1. ✅ Session ID regeneration on login/logout
2. ✅ Session timeout enforcement
3. ✅ Activity tracking
4. ✅ Secure session storage
5. ✅ Proper session cleanup on logout
6. ✅ Role-based access control
7. ✅ Input validation
8. ✅ Security logging
9. ✅ User-friendly session information display
10. ✅ Protection against session fixation attacks

## Libraries

### `EmailService` (`app/Libraries/EmailService.php`)
Handles all email operations: welcome emails, password resets, notifications, and custom emails.

**Class Documentation:**
- Centralized email service using CodeIgniter's email library
- Uses email configuration from `app/Config/Email.php`
- Supports HTML email templates via view files

**Methods:**
- `__construct()` - Initialize email service with CodeIgniter email configuration
- `sendWelcomeEmail($to, $username)` - Send welcome email to new user
  - Uses 'emails/welcome' view template
  - Personalizes with username and site name
- `sendPasswordResetEmail($to, $resetToken, $username)` - Send password reset email with reset link
  - Uses 'emails/password_reset' view template
  - Generates reset link: `/reset-password/{token}`
  - Token expires in 1 hour
- `sendNotificationEmail($to, $subject, $message, $data = [])` - Send generic notification email
  - Uses 'emails/notification' view template with custom message
  - Supports additional data array for template variables
- `sendEmail($to, $subject, $message, $isHtml = true)` - Send custom email (plain text or HTML)
  - Direct email sending without template
  - Supports both HTML and plain text formats
- `getError()` - Get last email error message
  - Returns debug information from CodeIgniter email service

**Email Templates:**
- `app/Views/emails/welcome.php` - Welcome email template
- `app/Views/emails/password_reset.php` - Password reset email template
- `app/Views/emails/notification.php` - Generic notification template

### `ImageService` (`app/Libraries/ImageService.php`)
Handles image processing: resizing, thumbnail creation, watermarking (text and image), and dimension retrieval.

**Class Documentation:**
- Image manipulation service using CodeIgniter's image library
- Supports resizing, watermarking, and thumbnail generation
- Handles both text and image watermarks with customizable options

**Methods:**
- `__construct()` - Initialize image service with CodeIgniter image library
- `resizeImage($sourcePath, $destinationPath, $maxWidth, $maxHeight, $quality)` - Process and resize uploaded image
  - Resizes image maintaining aspect ratio
  - Default max dimensions: 800x800px
  - Default quality: 85
- `createThumbnail($sourcePath, $destinationPath, $width, $height, $position, $quality)` - Create a thumbnail from an image
  - Creates square thumbnail with fit positioning
  - Default size: 200x200px
  - Default position: center
  - Default quality: 80
- `processUploadedImage($file, $uploadPath, $options)` - Process uploaded image file with resize, watermark, and thumbnail creation
  - Comprehensive image processing pipeline
  - Options include: maxWidth, maxHeight, thumbWidth, thumbHeight, quality, watermark settings
  - Returns array with 'original' and 'thumbnail' paths
- `addTextWatermark($sourcePath, $destinationPath, $text, $position, $fontSize, $color, $opacity, $quality)` - Add text watermark to an image
  - Handles color normalization (converts arrays to hex strings if needed)
  - Maps position strings to CodeIgniter alignment constants
  - Supports positions: bottom-right, bottom-left, top-right, top-left, center
  - Applies text watermark with shadow effect
- `addImageWatermark($sourcePath, $destinationPath, $watermarkPath, $position, $opacity, $quality)` - Add image watermark to an image
  - Calculates watermark position coordinates based on source image dimensions
  - Uses 20px padding from edges
  - Falls back to bottom-right if invalid position provided
- `hexToRgb($hex)` - Convert hex color to RGB array (protected method)
  - Converts hex color code to RGB array format
- `getImageDimensions($imagePath)` - Get image dimensions
  - Returns array with 'width' and 'height'
  - Returns false on failure

**Watermark Features:**
- **Text Watermark**: Customizable text, position, font size, color, opacity
- **Image Watermark**: Upload custom watermark image with position and opacity control
- **Position Options**: bottom-right, bottom-left, top-right, top-left, center
- **Color Support**: Hex color codes (e.g., '#FFFFFF') or RGB arrays

## Models

### `UserModel` (`app/Models/UserModel.php`)
Handles user data operations with role information.

**Class Documentation:**
- User model extending CodeIgniter Model class
- Provides pagination support for user listings
- Includes role information via joins

**Methods:**
- `getUsersWithRoles($perPage = 10, $page = 1)` - Get paginated list of users with their role names
  - Joins users, user_roles, and roles tables to include role information
  - Returns array of user objects with role_name property
  - Ordered by creation date (newest first)
- `getTotalUsers()` - Get total count of all users
  - Returns integer count of all users in database

**Properties:**
- `$table = 'users'` - Database table name
- `$primaryKey = 'id'` - Primary key field
- `$allowedFields = ['username', 'password', 'email', 'created_at']` - Mass assignment allowed fields
- `$useTimestamps = false` - Disables automatic timestamp management

### `ItemModel` (`app/Models/ItemModel.php`)
Handles item data operations with category information.

**Class Documentation:**
- Item model extending CodeIgniter Model class
- Provides pagination support for item listings
- Includes category information via joins

**Methods:**
- `getItemsWithCategories($perPage = 10, $page = 1)` - Get paginated list of items with their category names
  - Joins items and categories tables to include category information
  - Returns array of item objects with category_name property
  - Ordered by item ID (newest first)
- `getTotalItems()` - Get total count of all items
  - Returns integer count of all items in database

**Properties:**
- `$table = 'items'` - Database table name
- `$primaryKey = 'id'` - Primary key field
- `$allowedFields = ['name', 'description', 'price', 'category_id', 'image_url']` - Mass assignment allowed fields
- `$useTimestamps = false` - Disables automatic timestamp management

### `AnnouncementModel` (`app/Models/AnnouncementModel.php`)
Handles announcement data operations.

**Class Documentation:**
- Announcement model extending CodeIgniter Model class
- Provides pagination support for announcement listings
- Uses automatic timestamp management

**Methods:**
- `getAnnouncements($perPage = 10, $page = 1)` - Get paginated list of announcements
  - Ordered by creation date (newest first)
  - Returns array of announcement objects
- `getTotalAnnouncements()` - Get total count of all announcements
  - Returns integer count of all announcements in database

**Properties:**
- `$table = 'announcements'` - Database table name
- `$primaryKey = 'id'` - Primary key field
- `$allowedFields = ['title', 'content']` - Mass assignment allowed fields
- `$useTimestamps = true` - Enables automatic timestamp management
- `$createdField = 'created_at'` - Created timestamp field
- `$updatedField = 'updated_at'` - Updated timestamp field

## Password Reset Feature

The application includes a comprehensive password reset system that allows users to reset their passwords via email without requiring authentication.

### Overview

**Flow:**
1. User clicks "Forgot your password?" link on login page
2. User enters email address in expandable form
3. System generates secure reset token and sends email
4. User clicks reset link in email
5. User enters new password on reset form
6. Password is updated and user can login

### Database Schema

**Password Reset Columns in `users` Table:**
- `reset_token` (VARCHAR(64), NULL) - Stores the reset token
- `reset_token_expires` (DATETIME, NULL) - Stores expiration datetime
- Index: `idx_reset_token` on `reset_token` column for faster lookups

**Note:** These columns are included in the main `schema.sql` file. No separate migration is needed.

### Security Features

1. **Email Enumeration Prevention**
   - Always shows generic success message regardless of whether email exists
   - Prevents attackers from discovering valid email addresses

2. **Token Security**
   - 64-character hexadecimal token (32 random bytes)
   - Cryptographically secure random generation using `random_bytes()`
   - 1-hour expiration time
   - Single-use tokens (cleared after successful reset)

3. **Token Validation**
   - Validates token existence in database
   - Checks token expiration before allowing reset
   - Redirects to login if token invalid/expired

4. **Password Requirements**
   - Same strong password rules as registration
   - Minimum 8 characters
   - Must contain: uppercase, lowercase, number, special character

### Implementation Details

**Request Password Reset:**
- Route: `POST /request-password-reset`
- Controller: `Account::requestPasswordReset()`
- Public access (no authentication required)
- Form embedded in login page (expandable)

**Reset Password:**
- Route: `GET /reset-password/{token}` - Display form
- Route: `POST /reset-password` - Process reset
- Controller: `Account::resetPassword($token)`
- Public access (no authentication required)
- Validates token on both GET and POST requests

**Email Template:**
- File: `app/Views/emails/password_reset.php`
- Includes reset link with token
- Shows expiration warning (1 hour)
- Security notice about unauthorized requests

### User Experience

**Login Page:**
- "Forgot your password?" link below registration link
- Expandable form appears when link clicked
- Form includes email input and submit button
- JavaScript toggles form visibility

**Reset Form:**
- Clean, focused interface
- Password requirements displayed
- Validation errors shown inline
- Success/error messages via flashdata

**Email:**
- Professional HTML email template
- Clear call-to-action button
- Fallback text link
- Security warnings included

## File Structure

```
brewkaholic/
├── app/
│   ├── Config/
│   │   ├── Database.php          # Database configuration
│   │   ├── Email.php             # Email configuration
│   │   ├── Filters.php           # Filter registration
│   │   ├── Routes.php            # Route definitions
│   │   └── Session.php           # Session configuration
│   ├── Controllers/
│   │   ├── Account.php            # Account management + password reset
│   │   ├── Admin.php             # Admin panel (with pagination)
│   │   ├── BaseController.php    # Base controller class
│   │   ├── Coffee.php            # Customer menu
│   │   ├── Login.php              # Authentication (with session management)
│   │   └── Register.php          # User registration
│   ├── Filters/
│   │   ├── AdminFilter.php       # Admin access control (with session timeout)
│   │   └── AuthFilter.php        # Authentication check (with session timeout)
│   ├── Libraries/
│   │   ├── EmailService.php      # Email sending service
│   │   └── ImageService.php      # Image processing service
│   ├── Models/
│   │   ├── AnnouncementModel.php # Announcement pagination
│   │   ├── ItemModel.php         # Item pagination
│   │   └── UserModel.php         # User pagination
│   └── Views/
│       ├── login.php              # Login form (with password reset)
│       ├── register.php           # Registration form
│       ├── coffee_view.php        # Customer menu page
│       ├── account/
│       │   ├── index.php          # Account settings
│       │   ├── request_password_reset.php  # Password reset request (unused)
│       │   └── reset_password.php  # Password reset form
│       ├── admin/
│       │   ├── dashboard.php      # Admin dashboard
│       │   ├── users.php          # User management
│       │   ├── edit_user.php      # Edit user form
│       │   ├── items.php          # Item management
│       │   └── announcements.php  # Announcement management
│       └── emails/
│           ├── welcome.php        # Welcome email template
│           ├── password_reset.php # Password reset email template
│           └── notification.php   # Notification email template
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


