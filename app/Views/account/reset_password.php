<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Brewkaholic - Reset Password</title>
    <meta name="description" content="Reset Password">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="/favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'bg-primary': '#1a1a1a',
                        'bg-secondary': '#242424',
                        'border-color': '#3a3a3a',
                        'text-primary': '#d4a574',
                        'text-secondary': '#c4a574',
                        'accent': '#d4a574',
                        'accent-hover': '#f4d4a4',
                        'danger': '#cc6666',
                    },
                    fontFamily: {
                        'serif': ['Georgia', 'Times New Roman', 'serif'],
                    },
                }
            }
        }
    </script>
    <style>
        html, body {
            max-width: 100%;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }
        body {
            width: 100%;
        }
        .max-w-\[500px\] {
            max-width: 500px !important;
        }
    </style>
</head>
<body class="font-serif bg-bg-primary text-text-primary leading-relaxed min-h-screen p-8 md:p-4">
    <!-- Header -->
    <header class="bg-bg-primary py-6 mb-8 border-b border-border-color">
        <div class="max-w-[1200px] mx-auto px-8 flex justify-between items-center relative md:px-4">
            <a href="/coffee" class="text-[1.8rem] font-bold tracking-[3px] text-accent no-underline md:text-xl">BREWKAHOLIC</a>
        </div>
    </header>

    <div class="max-w-[500px] mx-auto md:p-0">
        <h1 class="text-4xl font-bold tracking-[3px] mb-8 text-accent md:text-3xl sm:text-[1.75rem]">RESET PASSWORD</h1>

        <?php if (session()->getFlashdata('msg')) : ?>
            <div class="bg-[#3a2a1a] border border-accent text-accent p-4 mb-8 text-center"><?= session()->getFlashdata('msg') ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')) : ?>
            <div class="bg-[#3a2a1a] border border-danger text-danger p-4 mb-8 text-center"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <div class="bg-bg-secondary border border-border-color p-8 mb-8 md:p-6 sm:p-4">
            <p class="text-text-secondary mb-6">
                Enter your new password below. Make sure it meets the security requirements.
            </p>

            <?php 
            $validationErrors = session()->getFlashdata('validation_errors');
            if ($validationErrors): ?>
                <div class="bg-[#3a2a1a] border border-accent text-accent p-4 mb-6">
                    <ul class="list-disc list-inside">
                        <?php foreach ($validationErrors as $field => $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="/reset-password" method="post" class="flex flex-col gap-6">
                <?= csrf_field() ?>
                <input type="hidden" name="token" value="<?= esc($token) ?>">
                
                <div>
                    <label for="password" class="text-accent text-sm tracking-[1px] mb-2 block">NEW PASSWORD</label>
                    <input type="password" name="password" id="password" required class="w-full px-4 py-3 bg-bg-primary border border-border-color text-text-primary text-base font-serif transition-colors focus:outline-none focus:border-accent">
                    <small class="text-[#888] text-xs mt-2 block">
                        Must be at least 8 characters with uppercase, lowercase, number, and special character
                    </small>
                </div>

                <div>
                    <label for="confirm_password" class="text-accent text-sm tracking-[1px] mb-2 block">CONFIRM NEW PASSWORD</label>
                    <input type="password" name="confirm_password" id="confirm_password" required class="w-full px-4 py-3 bg-bg-primary border border-border-color text-text-primary text-base font-serif transition-colors focus:outline-none focus:border-accent">
                </div>

                <button type="submit" class="bg-transparent border border-accent text-accent px-8 py-3 text-base tracking-[2px] cursor-pointer transition-all font-serif self-start hover:bg-accent hover:text-bg-primary">RESET PASSWORD</button>
            </form>
        </div>

        <div class="text-center">
            <a href="/" class="text-accent no-underline text-sm hover:text-accent-hover">Back to Login</a>
        </div>
    </div>
</body>
</html>
