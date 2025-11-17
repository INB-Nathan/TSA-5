<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Brewkaholic - Register</title>
    <meta name="description" content="Register for Brewkaholic">
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
        .max-w-\[500px\] {
            max-width: 500px !important;
        }
    </style>
</head>
<body class="font-serif bg-bg-primary text-text-primary leading-relaxed min-h-screen flex items-center justify-center p-8 md:p-4">
    <div class="bg-bg-secondary border border-border-color p-12 max-w-[500px] w-full shadow-[0_10px_30px_rgba(0,0,0,0.5)] md:p-8 sm:p-6">
        <div class="text-center text-3xl font-bold tracking-[5px] text-accent mb-8 sm:text-2xl sm:tracking-[3px]">BREWKAHOLIC</div>
        <h1 class="text-center text-3xl font-bold tracking-[3px] mb-8 text-accent sm:text-2xl sm:tracking-[2px]">REGISTER</h1>

        <?php if (isset($validation)): ?>
            <div class="bg-[#3a2a1a] border border-accent text-accent p-4 mb-6 text-sm">
                <?= $validation->listErrors() ?>
            </div>
        <?php endif; ?>

        <form action="/register/create" method="post" class="flex flex-col gap-6">
            <?= csrf_field() ?>
            <div>
                <label for="username" class="text-accent text-sm tracking-[1px] mb-2 block">USERNAME</label>
                <input type="text" name="username" id="username" placeholder="Enter your username" required class="w-full px-4 py-3 bg-bg-primary border border-border-color text-text-primary text-base font-serif transition-colors focus:outline-none focus:border-accent placeholder:text-[#666] sm:text-base">
            </div>

            <div>
                <label for="email" class="text-accent text-sm tracking-[1px] mb-2 block">EMAIL</label>
                <input type="email" name="email" id="email" placeholder="Enter your email" required class="w-full px-4 py-3 bg-bg-primary border border-border-color text-text-primary text-base font-serif transition-colors focus:outline-none focus:border-accent placeholder:text-[#666] sm:text-base">
            </div>

            <div>
                <label for="password" class="text-accent text-sm tracking-[1px] mb-2 block">PASSWORD</label>
                <input type="password" name="password" id="password" placeholder="Enter your password" required class="w-full px-4 py-3 bg-bg-primary border border-border-color text-text-primary text-base font-serif transition-colors focus:outline-none focus:border-accent placeholder:text-[#666] sm:text-base">
            </div>

            <div>
                <label for="confirm_password" class="text-accent text-sm tracking-[1px] mb-2 block">CONFIRM PASSWORD</label>
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm your password" required class="w-full px-4 py-3 bg-bg-primary border border-border-color text-text-primary text-base font-serif transition-colors focus:outline-none focus:border-accent placeholder:text-[#666] sm:text-base">
            </div>

            <button type="submit" class="bg-transparent border border-accent text-accent px-8 py-3 text-base tracking-[2px] cursor-pointer transition-all font-serif mt-2 hover:bg-accent hover:text-bg-primary sm:w-full">REGISTER</button>
        </form>

        <div class="text-center mt-8 pt-8 border-t border-border-color">
            <p class="text-text-secondary text-sm">Already have an account? <a href="/" class="text-accent no-underline tracking-[1px] transition-colors font-bold hover:text-accent-hover">Login here</a>.</p>
        </div>
    </div>
</body>
</html>
