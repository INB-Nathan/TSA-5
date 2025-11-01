<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Brewkaholic - Register</title>
    <meta name="description" content="Register for Brewkaholic">
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
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .register-container {
            background-color: #242424;
            border: 1px solid #3a3a3a;
            padding: 3rem;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }

        .logo {
            text-align: center;
            font-size: 2rem;
            font-weight: bold;
            letter-spacing: 5px;
            color: #d4a574;
            margin-bottom: 2rem;
        }

        h1 {
            text-align: center;
            font-size: 2rem;
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

        .alert li:last-child {
            margin-bottom: 0;
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
        input[type="password"] {
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
        input[type="password"]:focus {
            outline: none;
            border-color: #d4a574;
        }

        input[type="text"]::placeholder,
        input[type="email"]::placeholder,
        input[type="password"]::placeholder {
            color: #666;
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
            margin-top: 0.5rem;
        }

        button[type="submit"]:hover {
            background-color: #d4a574;
            color: #1a1a1a;
        }

        .login-link {
            text-align: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #3a3a3a;
        }

        .login-link p {
            color: #c4a574;
            font-size: 0.95rem;
        }

        .login-link a {
            color: #d4a574;
            text-decoration: none;
            letter-spacing: 1px;
            transition: color 0.3s;
            font-weight: bold;
        }

        .login-link a:hover {
            color: #f4d4a4;
        }

        @media (max-width: 480px) {
            .register-container {
                padding: 2rem 1.5rem;
            }

            .logo {
                font-size: 1.5rem;
            }

            h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="logo">BREWKAHOLIC</div>
        <h1>REGISTER</h1>

        <?php if (isset($validation)): ?>
            <div class="alert">
                <?= $validation->listErrors() ?>
            </div>
        <?php endif; ?>

        <form action="/register/create" method="post">
            <?= csrf_field() ?>
            <div>
                <label for="username">USERNAME</label>
                <input type="text" name="username" id="username" placeholder="Enter your username" required>
            </div>

            <div>
                <label for="email">EMAIL</label>
                <input type="email" name="email" id="email" placeholder="Enter your email" required>
            </div>

            <div>
                <label for="password">PASSWORD</label>
                <input type="password" name="password" id="password" placeholder="Enter your password" required>
            </div>

            <div>
                <label for="confirm_password">CONFIRM PASSWORD</label>
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm your password" required>
            </div>

            <button type="submit">REGISTER</button>
        </form>

        <div class="login-link">
            <p>Already have an account? <a href="/">Login here</a>.</p>
        </div>
    </div>
</body>
</html>
