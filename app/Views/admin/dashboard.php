<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Brewkaholic - Admin Dashboard</title>
    <meta name="description" content="Admin Dashboard">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="/favicon.ico">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Georgia', 'Times New Roman', serif;
            background-color: #1a1a1a;
            color: #d4a574;
            line-height: 1.6;
        }

        header {
            background-color: #1a1a1a;
            padding: 1.5rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid #3a3a3a;
        }

        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            letter-spacing: 3px;
            color: #d4a574;
            text-decoration: none;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 2rem;
        }

        nav ul li a {
            color: #d4a574;
            text-decoration: none;
            font-size: 0.95rem;
            letter-spacing: 1px;
            transition: color 0.3s;
        }

        nav ul li a:hover {
            color: #f4d4a4;
        }

        .logout-link {
            color: #d4a574;
            text-decoration: none;
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
            border: 1px solid #d4a574;
            transition: all 0.3s;
        }

        .logout-link:hover {
            background-color: #d4a574;
            color: #1a1a1a;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 3rem 2rem;
        }

        h1 {
            font-size: 2.5rem;
            font-weight: bold;
            letter-spacing: 3px;
            margin-bottom: 2rem;
            color: #d4a574;
        }

        .alert {
            background-color: #3a2a1a;
            border: 1px solid #d4a574;
            color: #d4a574;
            padding: 1rem;
            margin-bottom: 2rem;
            text-align: center;
        }

        .admin-actions {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .btn {
            background-color: transparent;
            border: 1px solid #d4a574;
            color: #d4a574;
            padding: 0.75rem 2rem;
            text-decoration: none;
            font-size: 0.95rem;
            letter-spacing: 1px;
            transition: all 0.3s;
            display: inline-block;
        }

        .btn:hover {
            background-color: #d4a574;
            color: #1a1a1a;
        }

        .btn-primary {
            background-color: #d4a574;
            color: #1a1a1a;
        }

        .btn-primary:hover {
            background-color: #f4d4a4;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #242424;
            border: 1px solid #3a3a3a;
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #3a3a3a;
        }

        th {
            background-color: #1a1a1a;
            color: #d4a574;
            font-weight: bold;
            letter-spacing: 1px;
        }

        td {
            color: #c4a574;
        }

        tr:hover {
            background-color: #2a2a2a;
        }

        .btn-small {
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
            margin-right: 0.5rem;
        }

        .btn-danger {
            border-color: #cc6666;
            color: #cc6666;
        }

        .btn-danger:hover {
            background-color: #cc6666;
            color: #1a1a1a;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 2rem;
        }

        .pagination a,
        .pagination span {
            padding: 0.5rem 1rem;
            border: 1px solid #d4a574;
            color: #d4a574;
            text-decoration: none;
            transition: all 0.3s;
        }

        .pagination a:hover {
            background-color: #d4a574;
            color: #1a1a1a;
        }

        .pagination .active {
            background-color: #d4a574;
            color: #1a1a1a;
            border-color: #d4a574;
        }

        .pagination .disabled {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }

        .pagination .disabled:hover {
            background-color: transparent;
            color: #d4a574;
        }

        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                gap: 1rem;
            }

            nav ul {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            table {
                font-size: 0.85rem;
            }

            th, td {
                padding: 0.75rem 0.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="header-container">
            <a href="/admin/dashboard" class="logo">BREWKAHOLIC</a>
            <nav>
                <ul>
                    <li><a href="/admin/dashboard">DASHBOARD</a></li>
                    <li><a href="/admin/users">USERS</a></li>
                    <li><a href="/admin/items">ITEMS</a></li>
                    <li><a href="/admin/announcements">ANNOUNCEMENTS</a></li>
                </ul>
            </nav>
            <div style="display: flex; align-items: center; gap: 1rem;">
                <?php if (session()->get('isLoggedIn')) : ?>
                    <span style="color: #d4a574; font-size: 0.9rem;">Admin: <?= esc(session()->get('username')) ?></span>
                <?php endif; ?>
                <a href="/logout" class="logout-link">LOGOUT</a>
            </div>
        </div>
    </header>

    <div class="container">
        <h1>ADMIN DASHBOARD</h1>

        <?php if (session()->getFlashdata('msg')) : ?>
            <div class="alert"><?= session()->getFlashdata('msg') ?></div>
        <?php endif; ?>

        <?php if (session()->get('isLoggedIn')) : ?>
            <div style="background-color: #242424; border: 1px solid #3a3a3a; padding: 1rem; margin-bottom: 2rem; color: #c4a574; font-size: 0.9rem;">
                <strong>Session Info:</strong> Logged in as <strong><?= esc(session()->get('username')) ?></strong> 
                (<?= esc(session()->get('email')) ?>) | 
                <?php 
                $loginTime = session()->get('login_time');
                if ($loginTime) {
                    echo 'Login: ' . date('Y-m-d H:i:s', $loginTime);
                }
                ?>
            </div>
        <?php endif; ?>

        <div class="admin-actions">
            <a href="/admin/users" class="btn">Manage Users</a>
            <a href="/admin/items" class="btn">Manage Items</a>
        </div>

        <h2 style="font-size: 1.5rem; margin-bottom: 1rem; color: #d4a574;">Registered Users<?= isset($totalUsers) ? ' (' . $totalUsers . ')' : '' ?></h2>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)) : ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 2rem;">No users found.</td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($users as $user) : ?>
                        <tr>
                            <td><?= $user->id ?></td>
                            <td><?= esc($user->username) ?></td>
                            <td><?= esc($user->email) ?></td>
                            <td><?= esc($user->role_name ?? 'No role') ?></td>
                            <td><?= date('Y-m-d H:i', strtotime($user->created_at)) ?></td>
                            <td>
                                <a href="/admin/users/edit/<?= $user->id ?>" class="btn btn-small">Edit</a>
                                <?php if ($user->id != session()->get('user_id')) : ?>
                                    <a href="/admin/users/delete/<?= $user->id ?>" class="btn btn-small btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <?php if (isset($totalUsers) && $totalUsers > 0) : ?>
            <div style="text-align: center; margin-top: 1rem; color: #c4a574; font-size: 0.9rem;">
                <?php 
                $start = (($currentPage - 1) * 10) + 1;
                $end = min($currentPage * 10, $totalUsers);
                ?>
                Showing <?= $start ?> to <?= $end ?> of <?= $totalUsers ?> users
            </div>
        <?php endif; ?>
        
        <?php if (isset($totalUsers) && $totalUsers > 0 && isset($totalPages)) : ?>
            <div class="pagination">
                <?php if ($currentPage > 1) : ?>
                    <a href="/admin/dashboard?page=<?= $currentPage - 1 ?>" class="btn">Previous</a>
                <?php else : ?>
                    <span class="btn disabled">Previous</span>
                <?php endif; ?>
                
                <?php
                $startPage = max(1, $currentPage - 2);
                $endPage = min($totalPages, $currentPage + 2);
                
                if ($startPage > 1) : ?>
                    <a href="/admin/dashboard?page=1" class="btn">1</a>
                    <?php if ($startPage > 2) : ?>
                        <span>...</span>
                    <?php endif; ?>
                <?php endif; ?>
                
                <?php for ($i = $startPage; $i <= $endPage; $i++) : ?>
                    <?php if ($i == $currentPage) : ?>
                        <span class="active"><?= $i ?></span>
                    <?php else : ?>
                        <a href="/admin/dashboard?page=<?= $i ?>" class="btn"><?= $i ?></a>
                    <?php endif; ?>
                <?php endfor; ?>
                
                <?php if ($endPage < $totalPages) : ?>
                    <?php if ($endPage < $totalPages - 1) : ?>
                        <span>...</span>
                    <?php endif; ?>
                    <a href="/admin/dashboard?page=<?= $totalPages ?>" class="btn"><?= $totalPages ?></a>
                <?php endif; ?>
                
                <?php if ($currentPage < $totalPages) : ?>
                    <a href="/admin/dashboard?page=<?= $currentPage + 1 ?>" class="btn">Next</a>
                <?php else : ?>
                    <span class="btn disabled">Next</span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
