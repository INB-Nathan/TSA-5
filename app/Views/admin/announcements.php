<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Brewkaholic - Manage Announcements</title>
    <meta name="description" content="Manage Announcements">
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
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        label {
            color: #d4a574;
            font-size: 0.95rem;
            letter-spacing: 1px;
            margin-bottom: 0.5rem;
            display: block;
        }

        input[type="text"],
        textarea {
            padding: 0.75rem 1rem;
            background-color: #1a1a1a;
            border: 1px solid #3a3a3a;
            color: #d4a574;
            font-size: 1rem;
            font-family: inherit;
            transition: border-color 0.3s;
            width: 100%;
        }

        textarea {
            resize: vertical;
            min-height: 150px;
        }

        input[type="text"]:focus,
        textarea:focus {
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
            align-self: flex-start;
        }

        button[type="submit"]:hover {
            background-color: #f4d4a4;
        }

        .announcements-list {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .announcement-card {
            background-color: #242424;
            border: 1px solid #3a3a3a;
            padding: 1.5rem;
        }

        .announcement-card h3 {
            color: #d4a574;
            margin-bottom: 0.5rem;
            font-size: 1.3rem;
        }

        .announcement-card p {
            color: #c4a574;
            margin-bottom: 1rem;
            white-space: pre-wrap;
        }

        .announcement-card .meta {
            color: #888;
            font-size: 0.85rem;
            margin-bottom: 1rem;
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
            <a href="/logout" class="logout-link">LOGOUT</a>
        </div>
    </header>

    <div class="container">
        <h1>MANAGE ANNOUNCEMENTS</h1>

        <?php if (session()->getFlashdata('msg')) : ?>
            <div class="alert"><?= session()->getFlashdata('msg') ?></div>
        <?php endif; ?>

        <!-- Create Announcement Form -->
        <div class="form-section">
            <h2>CREATE NEW ANNOUNCEMENT</h2>
            <?php if (isset($validation)): ?>
                <div class="alert">
                    <?= $validation->listErrors() ?>
                </div>
            <?php endif; ?>

            <form action="/admin/announcements/create" method="post">
                <?= csrf_field() ?>
                
                <div>
                    <label for="title">TITLE</label>
                    <input type="text" name="title" id="title" required>
                </div>

                <div>
                    <label for="content">CONTENT</label>
                    <textarea name="content" id="content" required></textarea>
                </div>

                <button type="submit">CREATE ANNOUNCEMENT</button>
            </form>
        </div>

        <!-- Announcements List -->
        <h2 style="font-size: 1.5rem; margin-bottom: 1rem; color: #d4a574;">Existing Announcements (<?= count($announcements) ?>)</h2>
        
        <div class="announcements-list">
            <?php if (empty($announcements)) : ?>
                <div style="text-align: center; padding: 2rem; color: #c4a574;">
                    <p>No announcements found.</p>
                </div>
            <?php else : ?>
                <?php foreach ($announcements as $announcement) : ?>
                    <div class="announcement-card">
                        <h3><?= esc($announcement->title) ?></h3>
                        <div class="meta">Posted: <?= date('F j, Y g:i A', strtotime($announcement->created_at)) ?></div>
                        <p><?= esc($announcement->content) ?></p>
                        <a href="/admin/announcements/delete/<?= $announcement->id ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this announcement?')">Delete</a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
