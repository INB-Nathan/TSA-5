<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Brewkaholic - Account Settings</title>
    <meta name="description" content="Account Settings">
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
        .max-w-\[1200px\] {
            max-width: 1200px !important;
        }
        .max-w-\[800px\] {
            max-width: 800px !important;
        }
    </style>
</head>
<body class="font-serif bg-bg-primary text-text-primary leading-relaxed min-h-screen p-8 md:p-4">
    <!-- Header -->
    <header class="bg-bg-primary py-6 mb-8 border-b border-border-color">
        <div class="max-w-[1200px] mx-auto px-8 flex justify-between items-center relative md:px-4">
            <a href="/coffee" class="text-[1.8rem] font-bold tracking-[3px] text-accent no-underline md:text-xl">BREWKAHOLIC</a>
            <button class="md:hidden bg-transparent border border-accent text-accent px-4 py-2 text-2xl z-[1001] cursor-pointer" id="mobileMenuToggle" aria-label="Toggle menu">☰</button>
            <nav id="mainNav" class="md:flex md:items-center md:static fixed top-0 right-[-100%] w-[280px] h-screen bg-bg-secondary border-l border-border-color transition-[right] duration-300 ease-in-out z-[1000] p-8 pt-20 overflow-y-auto md:overflow-visible md:h-auto md:w-auto md:border-0 md:p-0">
                <ul class="list-none flex gap-8 m-0 p-0 md:flex-row flex-col md:gap-8 gap-6 md:items-center items-start">
                    <li><a href="/coffee" onclick="closeMobileMenu()" class="text-accent no-underline text-sm tracking-[1px] transition-colors hover:text-accent-hover md:text-base md:py-2 md:block md:w-full">HOME</a></li>
                    <li><a href="/coffee#menu" onclick="closeMobileMenu()" class="text-accent no-underline text-sm tracking-[1px] transition-colors hover:text-accent-hover md:text-base md:py-2 md:block md:w-full">MENU</a></li>
                    <li><a href="/coffee#announcement" onclick="closeMobileMenu()" class="text-accent no-underline text-sm tracking-[1px] transition-colors hover:text-accent-hover md:text-base md:py-2 md:block md:w-full">ANNOUNCEMENT</a></li>
                    <li><a href="/account" onclick="closeMobileMenu()" class="text-accent no-underline text-sm tracking-[1px] transition-colors hover:text-accent-hover md:text-base md:py-2 md:block md:w-full">ACCOUNT</a></li>
                </ul>
            </nav>
            <div class="flex items-center gap-4 md:gap-2">
                <?php if (session()->get('isLoggedIn')) : ?>
                    <span class="text-accent text-sm hidden md:hidden"><?= esc(session()->get('username')) ?></span>
                <?php endif; ?>
                <a href="/logout" class="text-accent no-underline text-sm px-4 py-2 border border-accent transition-all hover:bg-accent hover:text-bg-primary md:px-3 md:py-2 md:text-xs">LOGOUT</a>
            </div>
        </div>
    </header>

    <div class="max-w-[800px] mx-auto md:p-0">
        <h1 class="text-4xl font-bold tracking-[3px] mb-8 text-accent md:text-3xl sm:text-[1.75rem]">ACCOUNT SETTINGS</h1>

        <?php if (session()->getFlashdata('msg')) : ?>
            <div class="bg-[#3a2a1a] border border-accent text-accent p-4 mb-8 text-center"><?= session()->getFlashdata('msg') ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')) : ?>
            <div class="bg-[#3a2a1a] border border-danger text-danger p-4 mb-8 text-center"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <!-- User Information -->
        <div class="bg-bg-secondary border border-border-color p-8 mb-8 md:p-6 sm:p-4">
            <h2 class="text-2xl mb-6 text-accent md:text-xl">USER INFORMATION</h2>
            <div class="text-text-secondary mb-4">
                <p><strong class="text-accent">Username:</strong> <?= esc($user->username) ?></p>
                <p><strong class="text-accent">Email:</strong> <?= esc($user->email) ?></p>
                <p><strong class="text-accent">Member Since:</strong> <?= date('F j, Y', strtotime($user->created_at)) ?></p>
                <?php if (session()->get('isLoggedIn')) : ?>
                    <?php 
                    $loginTime = session()->get('login_time');
                    $lastActivity = session()->get('last_activity');
                    ?>
                    <p><strong class="text-accent">Current Session:</strong></p>
                    <ul class="ml-6 mt-2">
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
        <div class="bg-bg-secondary border border-border-color p-8 mb-8 md:p-6 sm:p-4">
            <h2 class="text-2xl mb-6 text-accent md:text-xl">CHANGE PASSWORD</h2>
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
            <?php if (isset($validation)): ?>
                <div class="bg-[#3a2a1a] border border-accent text-accent p-4 mb-6">
                    <?= $validation->listErrors() ?>
                </div>
            <?php endif; ?>

            <form action="/account/change-password" method="post" class="flex flex-col gap-6">
                <?= csrf_field() ?>
                
                <div>
                    <label for="current_password" class="text-accent text-sm tracking-[1px] mb-2 block">CURRENT PASSWORD</label>
                    <input type="password" name="current_password" id="current_password" required class="w-full px-4 py-3 bg-bg-primary border border-border-color text-text-primary text-base font-serif transition-colors focus:outline-none focus:border-accent">
                </div>

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

                <button type="submit" class="bg-transparent border border-accent text-accent px-8 py-3 text-base tracking-[2px] cursor-pointer transition-all font-serif self-start hover:bg-accent hover:text-bg-primary">CHANGE PASSWORD</button>
            </form>
        </div>

        <!-- Delete Account -->
        <div class="bg-bg-secondary border border-border-color p-8 md:p-6 sm:p-4">
            <h2 class="text-2xl mb-6 text-accent md:text-xl">DELETE ACCOUNT</h2>
            <p class="text-text-secondary mb-6">
                Warning: This action cannot be undone. All your data will be permanently deleted.
            </p>

            <form action="/account/delete" method="post" onsubmit="return confirm('Are you absolutely sure you want to delete your account? This action cannot be undone.');" class="flex flex-col gap-6">
                <?= csrf_field() ?>
                
                <div>
                    <label for="sudo_password" class="text-accent text-sm tracking-[1px] mb-2 block">ENTER YOUR PASSWORD TO CONFIRM</label>
                    <input type="password" name="sudo_password" id="sudo_password" required class="w-full px-4 py-3 bg-bg-primary border border-border-color text-text-primary text-base font-serif transition-colors focus:outline-none focus:border-accent">
                </div>

                <button type="submit" class="bg-transparent border border-danger text-danger px-8 py-3 text-base tracking-[2px] cursor-pointer transition-all font-serif self-start hover:bg-danger hover:text-bg-primary">DELETE MY ACCOUNT</button>
            </form>
        </div>
    </div>

    <script>
        // Mobile menu toggle
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        const mainNav = document.getElementById('mainNav');

        function toggleMobileMenu() {
            mainNav.classList.toggle('right-0');
            mainNav.classList.toggle('right-[-100%]');
        }

        function closeMobileMenu() {
            mainNav.classList.remove('right-0');
            mainNav.classList.add('right-[-100%]');
        }

        if (mobileMenuToggle) {
            mobileMenuToggle.addEventListener('click', toggleMobileMenu);
        }

        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('header') && mainNav.classList.contains('right-0')) {
                closeMobileMenu();
            }
        });
    </script>
</body>
</html>
