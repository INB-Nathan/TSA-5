<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Brewkaholic - Register</title>
    <meta name="description" content="Register for Brewkaholic">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="/favicon.ico">
</head>
<body>

    <h1>Register</h1>

    <form action="/register/create" method="post">
        <?= csrf_field() ?>
        <label for="username">Username</label>
        <input type="text" name="username" id="username" required>

        <label for="email">Email</label>
        <input type="email" name="email" id="email" required>

        <label for="password">Password</label>
        <input type="password" name="password" id="password" required>

        <label for="confirm_password">Confirm Password</label>
        <input type="password" name="confirm_password" id="confirm_password" required>

        <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="/">Login here</a>.</p>

</body>
</html>
