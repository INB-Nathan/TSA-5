<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Brewkaholic - Login</title>
    <meta name="description" content="Login to Brewkaholic">
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
                    },
                    fontFamily: {
                        'serif': ['Georgia', 'Times New Roman', 'serif'],
                    },
                }
            }
        }
    </script>
    <style>
        /* Ensure proper layout constraints */
        html, body {
            max-width: 100%;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }
        body {
            width: 100%;
        }
        .max-w-\[450px\] {
            max-width: 450px !important;
        }
    </style>
</head>
<body class="font-serif bg-bg-primary text-text-primary leading-relaxed min-h-screen flex items-center justify-center p-8 md:p-4">
    <div class="bg-bg-secondary border border-border-color p-12 max-w-[450px] w-full shadow-[0_10px_30px_rgba(0,0,0,0.5)] md:p-8 sm:p-6">
        <div class="text-center text-3xl font-bold tracking-[5px] text-accent mb-8 sm:text-2xl sm:tracking-[3px]">BREWKAHOLIC</div>
        <h1 class="text-center text-3xl font-bold tracking-[3px] mb-8 text-accent sm:text-2xl sm:tracking-[2px]">LOGIN</h1>

        <?php if (session()->getFlashdata('msg')) : ?>
            <div class="bg-[#3a2a1a] border border-accent text-accent p-4 mb-6 text-center text-sm"><?= session()->getFlashdata('msg') ?></div>
        <?php endif; ?>

        <form action="/login/authenticate" method="post" class="flex flex-col gap-6">
            <?= csrf_field() ?>
            <div>
                <label for="username" class="text-accent text-sm tracking-[1px] mb-2 block">USERNAME</label>
                <input type="text" name="username" id="username" placeholder="Enter your username" required class="w-full px-4 py-3 bg-bg-primary border border-border-color text-text-primary text-base font-serif transition-colors focus:outline-none focus:border-accent placeholder:text-[#666] sm:text-base">
            </div>

            <div>
                <label for="password" class="text-accent text-sm tracking-[1px] mb-2 block">PASSWORD</label>
                <input type="password" name="password" id="password" placeholder="Enter your password" required class="w-full px-4 py-3 bg-bg-primary border border-border-color text-text-primary text-base font-serif transition-colors focus:outline-none focus:border-accent placeholder:text-[#666] sm:text-base">
            </div>

            <button type="submit" class="bg-transparent border border-accent text-accent px-8 py-3 text-base tracking-[2px] cursor-pointer transition-all font-serif mt-2 hover:bg-accent hover:text-bg-primary sm:w-full">LOGIN</button>
        </form>

        <div class="text-center mt-8 pt-8 border-t border-border-color">
            <p class="text-text-secondary text-sm mb-4">Don't have an account? <a href="/register" class="text-accent no-underline tracking-[1px] transition-colors font-bold hover:text-accent-hover">Register here</a>.</p>
            <p class="text-text-secondary text-sm">
                <a href="#" id="forgotPasswordLink" class="text-accent no-underline tracking-[1px] transition-colors hover:text-accent-hover">Forgot your password?</a>
            </p>
        </div>

        <!-- Forgot Password Form -->
        <div id="forgotPasswordForm" class="hidden mt-8 pt-8 border-t border-border-color">
            <h2 class="text-xl font-bold tracking-[2px] mb-4 text-accent text-center">RESET PASSWORD</h2>
            <p class="text-text-secondary text-sm mb-6 text-center">Enter your email address and we'll send you a password reset link.</p>
            
            <?php if (session()->getFlashdata('error')) : ?>
                <div class="bg-[#3a2a1a] border border-danger text-danger p-4 mb-6 text-center text-sm"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <form action="/request-password-reset" method="post" class="flex flex-col gap-6">
                <?= csrf_field() ?>
                <div>
                    <label for="email" class="text-accent text-sm tracking-[1px] mb-2 block">EMAIL ADDRESS</label>
                    <input type="email" name="email" id="email" placeholder="Enter your email" required class="w-full px-4 py-3 bg-bg-primary border border-border-color text-text-primary text-base font-serif transition-colors focus:outline-none focus:border-accent placeholder:text-[#666] sm:text-base">
                </div>

                <button type="submit" class="bg-transparent border border-accent text-accent px-8 py-3 text-base tracking-[2px] cursor-pointer transition-all font-serif hover:bg-accent hover:text-bg-primary sm:w-full">SEND RESET LINK</button>
            </form>
        </div>
    </div>

    <script>
        // Toggle forgot password form
        document.getElementById('forgotPasswordLink').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('forgotPasswordForm').classList.toggle('hidden');
        });
    </script>
</body>
</html>
