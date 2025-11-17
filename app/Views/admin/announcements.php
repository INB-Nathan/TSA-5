<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Brewkaholic - Manage Announcements</title>
    <meta name="description" content="Manage Announcements">
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
    </style>
</head>
<body class="font-serif bg-bg-primary text-text-primary leading-relaxed">
    <!-- Header -->
    <header class="bg-bg-primary py-6 sticky top-0 z-[1000] border-b border-border-color">
        <div class="max-w-[1200px] mx-auto px-8 flex justify-between items-center relative md:px-4">
            <a href="/admin/dashboard" class="text-[1.8rem] font-bold tracking-[3px] text-accent no-underline md:text-xl">BREWKAHOLIC</a>
            <button class="md:hidden bg-transparent border border-accent text-accent px-4 py-2 text-2xl z-[1001] cursor-pointer" id="mobileMenuToggle" aria-label="Toggle menu">☰</button>
            <nav id="mainNav" class="md:flex md:items-center md:static fixed top-0 right-[-100%] w-[280px] h-screen bg-bg-secondary border-l border-border-color transition-[right] duration-300 ease-in-out z-[1000] p-8 pt-20 overflow-y-auto md:overflow-visible md:h-auto md:w-auto md:border-0 md:p-0">
                <ul class="list-none flex gap-8 m-0 p-0 md:flex-row flex-col md:gap-8 gap-6 md:items-center items-start">
                    <li><a href="/admin/dashboard" onclick="closeMobileMenu()" class="text-accent no-underline text-sm tracking-[1px] transition-colors hover:text-accent-hover md:text-base md:py-2 md:block md:w-full">DASHBOARD</a></li>
                    <li><a href="/admin/users" onclick="closeMobileMenu()" class="text-accent no-underline text-sm tracking-[1px] transition-colors hover:text-accent-hover md:text-base md:py-2 md:block md:w-full">USERS</a></li>
                    <li><a href="/admin/items" onclick="closeMobileMenu()" class="text-accent no-underline text-sm tracking-[1px] transition-colors hover:text-accent-hover md:text-base md:py-2 md:block md:w-full">ITEMS</a></li>
                    <li><a href="/admin/announcements" onclick="closeMobileMenu()" class="text-accent no-underline text-sm tracking-[1px] transition-colors hover:text-accent-hover md:text-base md:py-2 md:block md:w-full">ANNOUNCEMENTS</a></li>
                </ul>
            </nav>
            <div class="flex items-center gap-4 md:gap-2">
                <?php if (session()->get('isLoggedIn')) : ?>
                    <span class="text-accent text-sm hidden md:hidden">Admin: <?= esc(session()->get('username')) ?></span>
                <?php endif; ?>
                <a href="/logout" class="text-accent no-underline text-sm px-4 py-2 border border-accent transition-all hover:bg-accent hover:text-bg-primary md:px-3 md:py-2 md:text-xs">LOGOUT</a>
            </div>
        </div>
    </header>

    <div class="max-w-[1200px] mx-auto py-12 px-8">
        <h1 class="text-4xl font-bold tracking-[3px] mb-8 text-accent">MANAGE ANNOUNCEMENTS</h1>

        <?php if (session()->getFlashdata('msg')) : ?>
            <div class="bg-[#3a2a1a] border border-accent text-accent p-4 mb-8 text-center"><?= session()->getFlashdata('msg') ?></div>
        <?php endif; ?>

        <!-- Create Announcement Form -->
        <div class="bg-bg-secondary border border-border-color p-8 mb-12">
            <h2 class="text-2xl mb-6 text-accent">CREATE NEW ANNOUNCEMENT</h2>
            <?php if (isset($validation)): ?>
                <div class="bg-[#3a2a1a] border border-accent text-accent p-4 mb-6 text-center">
                    <?= $validation->listErrors() ?>
                </div>
            <?php endif; ?>

            <form action="/admin/announcements/create" method="post" class="flex flex-col gap-6">
                <?= csrf_field() ?>
                
                <div>
                    <label for="title" class="text-accent text-sm tracking-[1px] mb-2 block">TITLE</label>
                    <input type="text" name="title" id="title" required class="w-full px-4 py-3 bg-bg-primary border border-border-color text-text-primary text-base font-serif transition-colors focus:outline-none focus:border-accent">
                </div>

                <div>
                    <label for="content" class="text-accent text-sm tracking-[1px] mb-2 block">CONTENT</label>
                    <textarea name="content" id="content" required class="w-full px-4 py-3 bg-bg-primary border border-border-color text-text-primary text-base font-serif transition-colors focus:outline-none focus:border-accent resize-y min-h-[150px]"></textarea>
                </div>

                <button type="submit" class="bg-accent border border-accent text-bg-primary px-8 py-3 text-base tracking-[2px] cursor-pointer transition-all font-serif font-bold self-start hover:bg-accent-hover">CREATE ANNOUNCEMENT</button>
            </form>
        </div>

        <!-- Announcements List -->
        <h2 class="text-2xl mb-4 text-accent">Existing Announcements<?= isset($totalAnnouncements) ? ' (' . $totalAnnouncements . ')' : '' ?></h2>
        
        <div class="flex flex-col gap-6">
            <?php if (empty($announcements)) : ?>
                <div class="text-center py-8 text-text-secondary">
                    <p>No announcements found.</p>
                </div>
            <?php else : ?>
                <?php foreach ($announcements as $announcement) : ?>
                    <div class="bg-bg-secondary border border-border-color p-6">
                        <h3 class="text-accent mb-2 text-xl"><?= esc($announcement->title) ?></h3>
                        <div class="text-[#888] text-xs mb-4">Posted: <?= date('F j, Y g:i A', strtotime($announcement->created_at)) ?></div>
                        <p class="text-text-secondary mb-4 whitespace-pre-wrap"><?= esc($announcement->content) ?></p>
                        <a href="/admin/announcements/delete/<?= $announcement->id ?>" class="bg-transparent border border-danger text-danger px-4 py-2 no-underline text-xs tracking-[1px] transition-all inline-block mr-2 hover:bg-danger hover:text-bg-primary" onclick="return confirm('Are you sure you want to delete this announcement?')">Delete</a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php if (isset($totalAnnouncements) && $totalAnnouncements > 0) : ?>
            <div class="text-center mt-4 text-text-secondary text-sm">
                <?php 
                $start = (($currentPage - 1) * 10) + 1;
                $end = min($currentPage * 10, $totalAnnouncements);
                ?>
                Showing <?= $start ?> to <?= $end ?> of <?= $totalAnnouncements ?> announcements
            </div>
        <?php endif; ?>
        
        <?php if (isset($totalAnnouncements) && $totalAnnouncements > 0 && isset($totalPages)) : ?>
            <div class="flex justify-center gap-2 mt-8 flex-wrap">
                <?php if ($currentPage > 1) : ?>
                    <a href="/admin/announcements?page=<?= $currentPage - 1 ?>" class="px-4 py-2 border border-accent text-accent no-underline transition-all hover:bg-accent hover:text-bg-primary md:px-3 md:py-1.5 md:text-xs">Previous</a>
                <?php else : ?>
                    <span class="px-4 py-2 border border-accent text-accent opacity-50 cursor-not-allowed pointer-events-none md:px-3 md:py-1.5 md:text-xs">Previous</span>
                <?php endif; ?>
                
                <?php
                $startPage = max(1, $currentPage - 2);
                $endPage = min($totalPages, $currentPage + 2);
                
                if ($startPage > 1) : ?>
                    <a href="/admin/announcements?page=1" class="px-4 py-2 border border-accent text-accent no-underline transition-all hover:bg-accent hover:text-bg-primary md:px-3 md:py-1.5 md:text-xs">1</a>
                    <?php if ($startPage > 2) : ?>
                        <span>...</span>
                    <?php endif; ?>
                <?php endif; ?>
                
                <?php for ($i = $startPage; $i <= $endPage; $i++) : ?>
                    <?php if ($i == $currentPage) : ?>
                        <span class="px-4 py-2 border border-accent bg-accent text-bg-primary md:px-3 md:py-1.5 md:text-xs"><?= $i ?></span>
                    <?php else : ?>
                        <a href="/admin/announcements?page=<?= $i ?>" class="px-4 py-2 border border-accent text-accent no-underline transition-all hover:bg-accent hover:text-bg-primary md:px-3 md:py-1.5 md:text-xs"><?= $i ?></a>
                    <?php endif; ?>
                <?php endfor; ?>
                
                <?php if ($endPage < $totalPages) : ?>
                    <?php if ($endPage < $totalPages - 1) : ?>
                        <span>...</span>
                    <?php endif; ?>
                    <a href="/admin/announcements?page=<?= $totalPages ?>" class="px-4 py-2 border border-accent text-accent no-underline transition-all hover:bg-accent hover:text-bg-primary md:px-3 md:py-1.5 md:text-xs"><?= $totalPages ?></a>
                <?php endif; ?>
                
                <?php if ($currentPage < $totalPages) : ?>
                    <a href="/admin/announcements?page=<?= $currentPage + 1 ?>" class="px-4 py-2 border border-accent text-accent no-underline transition-all hover:bg-accent hover:text-bg-primary md:px-3 md:py-1.5 md:text-xs">Next</a>
                <?php else : ?>
                    <span class="px-4 py-2 border border-accent text-accent opacity-50 cursor-not-allowed pointer-events-none md:px-3 md:py-1.5 md:text-xs">Next</span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
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
