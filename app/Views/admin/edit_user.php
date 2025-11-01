<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Brewkaholic - Edit User</title>
    <meta name="description" content="Edit User">
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
            min-height: 100vh;
            padding: 2rem;
        }

        header {
            background-color: #1a1a1a;
            padding: 1.5rem 0;
            margin-bottom: 2rem;
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

        .form-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #242424;
            border: 1px solid #3a3a3a;
            padding: 3rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }

        h1 {
            font-size: 2rem;
            font-weight: bold;
            letter-spacing: 3px;
            margin-bottom: 2rem;
            color: #d4a574;
            text-align: center;
        }

        .alert {
            background-color: #3a2a1a;
            border: 1px solid #d4a574;
            color: #d4a574;
            padding: 1rem;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }

        .alert ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .alert li {
            margin-bottom: 0.5rem;
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
        input[type="email"],
        select {
            width: 100%;
            padding: 0.75rem 1rem;
            background-color: #1a1a1a;
            border: 1px solid #3a3a3a;
            color: #d4a574;
            font-size: 1rem;
            font-family: inherit;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        select:focus {
            outline: none;
            border-color: #d4a574;
        }

        .password-notice {
            background-color: #2a2a2a;
            border: 1px solid #d4a574;
            padding: 1rem;
            color: #d4a574;
            font-size: 0.9rem;
            margin-top: -0.5rem;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        button[type="submit"],
        .btn {
            background-color: transparent;
            border: 1px solid #d4a574;
            color: #d4a574;
            padding: 0.75rem 2rem;
            font-size: 1rem;
            letter-spacing: 2px;
            cursor: pointer;
            transition: all 0.3s;
            font-family: inherit;
            text-decoration: none;
            display: inline-block;
        }

        button[type="submit"]:hover,
        .btn:hover {
            background-color: #d4a574;
            color: #1a1a1a;
        }

        .btn-secondary {
            border-color: #666;
            color: #666;
        }

        .btn-secondary:hover {
            background-color: #666;
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

            .form-container {
                padding: 2rem 1.5rem;
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

    <div class="form-container">
        <h1>EDIT USER</h1>

        <?php if (isset($validation)): ?>
            <div class="alert">
                <?= $validation->listErrors() ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('msg')) : ?>
            <div class="alert"><?= session()->getFlashdata('msg') ?></div>
        <?php endif; ?>

        <form action="/admin/users/update/<?= $user->id ?>" method="post">
            <?= csrf_field() ?>
            
            <div>
                <label for="username">USERNAME</label>
                <input type="text" name="username" id="username" value="<?= esc($user->username) ?>" required>
            </div>

            <div>
                <label for="email">EMAIL</label>
                <input type="email" name="email" id="email" value="<?= esc($user->email) ?>" required>
            </div>

            <div>
                <label for="role_id">ROLE</label>
                <select name="role_id" id="role_id" required>
                    <?php foreach ($roles as $role) : ?>
                        <option value="<?= $role->id ?>" <?= ($userRole && $userRole->role_id == $role->id) ? 'selected' : '' ?>>
                            <?= esc(ucfirst($role->name)) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="password-notice">
                <strong>Note:</strong> Password will be automatically reset to "123" when you update this user.
            </div>

            <div class="form-actions">
                <button type="submit">UPDATE USER</button>
                <a href="/admin/users" class="btn btn-secondary">CANCEL</a>
            </div>
        </form>
    </div>
</body>
</html>
