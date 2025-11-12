<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Brewkaholic - Account Settings</title>
    <meta name="description" content="Account Settings">
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

        .container {
            max-width: 800px;
            margin: 0 auto;
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

        .alert.error {
            border-color: #cc6666;
            color: #cc6666;
        }

        .section {
            background-color: #242424;
            border: 1px solid #3a3a3a;
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .section h2 {
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

        input[type="password"],
        input[type="text"] {
            width: 100%;
            padding: 0.75rem 1rem;
            background-color: #1a1a1a;
            border: 1px solid #3a3a3a;
            color: #d4a574;
            font-size: 1rem;
            font-family: inherit;
            transition: border-color 0.3s;
        }

        input[type="password"]:focus,
        input[type="text"]:focus {
            outline: none;
            border-color: #d4a574;
        }

        button[type="submit"] {
            background-color: transparent;
            border: 1px solid #d4a574;
            color: #d4a574;
            padding: 0.75rem 2rem;
            font-size: 1rem;
            letter-spacing: 2px;
            cursor: pointer;
            transition: all 0.3s;
            font-family: inherit;
            align-self: flex-start;
        }

        button[type="submit"]:hover {
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

        .user-info {
            color: #c4a574;
            margin-bottom: 1rem;
        }

        .user-info strong {
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
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="header-container">
            <a href="/coffee" class="logo">BREWKAHOLIC</a>
            <nav>
                <ul>
                    <li><a href="/coffee">HOME</a></li>
                    <li><a href="#menu">MENU</a></li>
                    <li><a href="#announcement">ANNOUNCEMENT</a></li>
                    <li><a href="/account">ACCOUNT</a></li>
                </ul>
            </nav>
            <div style="display: flex; align-items: center; gap: 1rem;">
                <?php if (session()->get('isLoggedIn')) : ?>
                    <span style="color: #d4a574; font-size: 0.9rem;"><?= esc(session()->get('username')) ?></span>
                <?php endif; ?>
                <a href="/logout" class="logout-link">LOGOUT</a>
            </div>
        </div>
    </header>

    <div class="container">
        <h1>ACCOUNT SETTINGS</h1>

        <?php if (session()->getFlashdata('msg')) : ?>
            <div class="alert"><?= session()->getFlashdata('msg') ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert error"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <!-- User Information -->
        <div class="section">
            <h2>USER INFORMATION</h2>
            <div class="user-info">
                <p><strong>Username:</strong> <?= esc($user->username) ?></p>
                <p><strong>Email:</strong> <?= esc($user->email) ?></p>
                <p><strong>Member Since:</strong> <?= date('F j, Y', strtotime($user->created_at)) ?></p>
                <?php if (session()->get('isLoggedIn')) : ?>
                    <?php 
                    $loginTime = session()->get('login_time');
                    $lastActivity = session()->get('last_activity');
                    ?>
                    <p><strong>Current Session:</strong></p>
                    <ul style="margin-left: 1.5rem; margin-top: 0.5rem;">
                        <?php if ($loginTime) : ?>
                            <li>Logged in: <?= date('F j, Y g:i A', $loginTime) ?></li>
                        <?php endif; ?>
                        <?php if ($lastActivity) : ?>
                            <li>Last activity: <?= date('F j, Y g:i A', $lastActivity) ?></li>
                            <?php 
                            $timeRemaining = 7200 - (time() - $lastActivity);
                            $hours = floor($timeRemaining / 3600);
                            $minutes = floor(($timeRemaining % 3600) / 60);
                            ?>
                            <li>Session expires in: <?= $hours ?>h <?= $minutes ?>m</li>
                        <?php endif; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>

        <!-- Change Password -->
        <div class="section">
            <h2>CHANGE PASSWORD</h2>
            <?php if (isset($validation)): ?>
                <div class="alert">
                    <?= $validation->listErrors() ?>
                </div>
            <?php endif; ?>

            <form action="/account/change-password" method="post">
                <?= csrf_field() ?>
                
                <div>
                    <label for="current_password">CURRENT PASSWORD</label>
                    <input type="password" name="current_password" id="current_password" required>
                </div>

                <div>
                    <label for="password">NEW PASSWORD</label>
                    <input type="password" name="password" id="password" required>
                    <small style="color: #888; font-size: 0.85rem; margin-top: 0.5rem; display: block;">
                        Must be at least 8 characters with uppercase, lowercase, number, and special character
                    </small>
                </div>

                <div>
                    <label for="confirm_password">CONFIRM NEW PASSWORD</label>
                    <input type="password" name="confirm_password" id="confirm_password" required>
                </div>

                <button type="submit">CHANGE PASSWORD</button>
            </form>
        </div>

        <!-- Delete Account -->
        <div class="section">
            <h2>DELETE ACCOUNT</h2>
            <p style="color: #c4a574; margin-bottom: 1.5rem;">
                Warning: This action cannot be undone. All your data will be permanently deleted.
            </p>

            <form action="/account/delete" method="post" onsubmit="return confirm('Are you absolutely sure you want to delete your account? This action cannot be undone.');">
                <?= csrf_field() ?>
                
                <div>
                    <label for="sudo_password">ENTER YOUR PASSWORD TO CONFIRM</label>
                    <input type="password" name="sudo_password" id="sudo_password" required>
                </div>

                <button type="submit" class="btn-danger">DELETE MY ACCOUNT</button>
            </form>
        </div>
    </div>
</body>
</html>
