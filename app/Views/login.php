<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Brewkaholic - Login</title>
    <meta name="description" content="Login to Brewkaholic">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="/favicon.ico">
</head>
<body>

    <h1>Login</h1>

    <?php if (session()->getFlashdata('msg')) : ?>
        <div><?= session()->getFlashdata('msg') ?></div>
    <?php endif; ?>

    <form action="/login/authenticate" method="post">
        <?= csrf_field() ?>
        <label for="username">Username</label>
        <input type="text" name="username" id="username" required>

        <label for="password">Password</label>
        <input type="password" name="password" id="password" required>

        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="/register">Register here</a>.</p>

</body>
</html>
