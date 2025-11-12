<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Brewkaholic - Manage Items</title>
    <meta name="description" content="Manage Items">
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

        .form-section {
            background-color: #242424;
            border: 1px solid #3a3a3a;
            padding: 2rem;
            margin-bottom: 3rem;
        }

        .form-section h2 {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            color: #d4a574;
        }

        form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        label {
            color: #d4a574;
            font-size: 0.95rem;
            letter-spacing: 1px;
            margin-bottom: 0.5rem;
        }

        input[type="text"],
        input[type="number"],
        input[type="file"],
        textarea,
        select {
            padding: 0.75rem 1rem;
            background-color: #1a1a1a;
            border: 1px solid #3a3a3a;
            color: #d4a574;
            font-size: 1rem;
            font-family: inherit;
            transition: border-color 0.3s;
        }

        input[type="file"] {
            padding: 0.5rem;
            cursor: pointer;
        }

        input[type="file"]::file-selector-button {
            background-color: #d4a574;
            color: #1a1a1a;
            border: none;
            padding: 0.5rem 1rem;
            margin-right: 1rem;
            cursor: pointer;
            font-family: inherit;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        input[type="text"]:focus,
        input[type="number"]:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: #d4a574;
        }

        button[type="submit"] {
            background-color: #d4a574;
            border: 1px solid #d4a574;
            color: #1a1a1a;
            padding: 0.75rem 2rem;
            font-size: 1rem;
            letter-spacing: 2px;
            cursor: pointer;
            transition: all 0.3s;
            font-family: inherit;
            font-weight: bold;
        }

        button[type="submit"]:hover {
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

        .btn {
            background-color: transparent;
            border: 1px solid #d4a574;
            color: #d4a574;
            padding: 0.5rem 1rem;
            text-decoration: none;
            font-size: 0.85rem;
            letter-spacing: 1px;
            transition: all 0.3s;
            display: inline-block;
            margin-right: 0.5rem;
        }

        .btn:hover {
            background-color: #d4a574;
            color: #1a1a1a;
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

            form {
                grid-template-columns: 1fr;
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
        <h1>MANAGE ITEMS</h1>

        <?php if (session()->getFlashdata('msg')) : ?>
            <div class="alert"><?= session()->getFlashdata('msg') ?></div>
        <?php endif; ?>

        <!-- Create Item Form -->
        <div class="form-section">
            <h2>CREATE NEW ITEM</h2>
            <?php if (isset($validation)): ?>
                <div class="alert">
                    <?= $validation->listErrors() ?>
                </div>
            <?php endif; ?>

            <form action="/admin/items/create" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                
                <div class="form-group">
                    <label for="name">ITEM NAME</label>
                    <input type="text" name="name" id="name" required>
                </div>

                <div class="form-group">
                    <label for="price">PRICE (₱)</label>
                    <input type="number" name="price" id="price" step="1" min="0" required>
                </div>

                <div class="form-group">
                    <label for="category_id">CATEGORY</label>
                    <select name="category_id" id="category_id" required>
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $category) : ?>
                            <option value="<?= $category->id ?>"><?= esc($category->name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="image">IMAGE (Optional)</label>
                    <input type="file" name="image" id="image" accept="image/*">
                </div>

                <div class="form-group full-width">
                    <label for="description">DESCRIPTION</label>
                    <textarea name="description" id="description" placeholder="Enter item description"></textarea>
                </div>

                <div class="form-group full-width">
                    <button type="submit">CREATE ITEM</button>
                </div>
            </form>
        </div>

        <!-- Items List -->
        <h2 style="font-size: 1.5rem; margin-bottom: 1rem; color: #d4a574;">Existing Items<?= isset($totalItems) ? ' (' . $totalItems . ')' : '' ?></h2>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($items)) : ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 2rem;">No items found.</td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($items as $item) : ?>
                        <tr>
                            <td><?= $item->id ?></td>
                            <td><?= esc($item->name) ?></td>
                            <td><?= esc($item->description ?? 'No description') ?></td>
                            <td>₱<?= number_format($item->price, 2) ?></td>
                            <td><?= esc($item->category_name ?? 'No category') ?></td>
                            <td>
                                <a href="/admin/items/delete/<?= $item->id ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <?php if (isset($totalItems) && $totalItems > 0) : ?>
            <div style="text-align: center; margin-top: 1rem; color: #c4a574; font-size: 0.9rem;">
                <?php 
                $start = (($currentPage - 1) * 10) + 1;
                $end = min($currentPage * 10, $totalItems);
                ?>
                Showing <?= $start ?> to <?= $end ?> of <?= $totalItems ?> items
            </div>
        <?php endif; ?>
        
        <?php if (isset($totalItems) && $totalItems > 0 && isset($totalPages)) : ?>
            <div class="pagination">
                <?php if ($currentPage > 1) : ?>
                    <a href="/admin/items?page=<?= $currentPage - 1 ?>" class="btn">Previous</a>
                <?php else : ?>
                    <span class="btn disabled">Previous</span>
                <?php endif; ?>
                
                <?php
                $startPage = max(1, $currentPage - 2);
                $endPage = min($totalPages, $currentPage + 2);
                
                if ($startPage > 1) : ?>
                    <a href="/admin/items?page=1" class="btn">1</a>
                    <?php if ($startPage > 2) : ?>
                        <span>...</span>
                    <?php endif; ?>
                <?php endif; ?>
                
                <?php for ($i = $startPage; $i <= $endPage; $i++) : ?>
                    <?php if ($i == $currentPage) : ?>
                        <span class="active"><?= $i ?></span>
                    <?php else : ?>
                        <a href="/admin/items?page=<?= $i ?>" class="btn"><?= $i ?></a>
                    <?php endif; ?>
                <?php endfor; ?>
                
                <?php if ($endPage < $totalPages) : ?>
                    <?php if ($endPage < $totalPages - 1) : ?>
                        <span>...</span>
                    <?php endif; ?>
                    <a href="/admin/items?page=<?= $totalPages ?>" class="btn"><?= $totalPages ?></a>
                <?php endif; ?>
                
                <?php if ($currentPage < $totalPages) : ?>
                    <a href="/admin/items?page=<?= $currentPage + 1 ?>" class="btn">Next</a>
                <?php else : ?>
                    <span class="btn disabled">Next</span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
