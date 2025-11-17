<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Brewkaholic - Coffee</title>
    <meta name="description" content="Discover the finest coffee at Brewkaholic">
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
                        'bg-tertiary': '#2a2a2a',
                        'border-color': '#3a3a3a',
                        'text-primary': '#d4a574',
                        'text-secondary': '#c4a574',
                        'text-muted': '#666',
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
        /* Ensure containers have max-width */
        .max-w-\[1200px\] {
            max-width: 1200px !important;
        }
        .max-w-\[800px\] {
            max-width: 800px !important;
        }
        .max-w-\[450px\] {
            max-width: 450px !important;
        }
        .max-w-\[500px\] {
            max-width: 500px !important;
        }
        .max-w-\[600px\] {
            max-width: 600px !important;
        }
        /* Ensure coffee cards maintain their width for three-per-row layout */
        .coffee-card {
            flex: 0 0 280px !important;
            max-width: 280px !important;
            min-width: 280px !important;
            width: 280px !important;
        }
        @media (max-width: 768px) {
            .coffee-card {
                flex: 0 0 100% !important;
                max-width: 100% !important;
                min-width: 100% !important;
                width: 100% !important;
            }
        }
    </style>
    <style>
        .coffee-image:not([style*="background-image"])::before {
            content: '☕';
            font-size: 3rem;
            opacity: 0.3;
        }
        .coffee-image:not([style*="background-image"])::after {
            content: 'Image Placeholder';
            position: absolute;
            bottom: 10px;
            font-size: 0.75rem;
            color: #666;
            opacity: 0.7;
        }
    </style>
</head>
<body class="font-serif bg-bg-primary text-text-primary leading-relaxed">
    <!-- Header -->
    <header class="bg-bg-primary py-4 sticky top-0 z-[1000] border-b border-border-color transition-colors">
        <div class="max-w-[1200px] mx-auto px-8 flex justify-between items-center relative">
            <a href="/coffee" class="text-[1.8rem] font-bold tracking-[3px] text-accent no-underline z-[1001]">BREWKAHOLIC</a>
            <button class="md:hidden bg-transparent border border-accent text-accent px-4 py-2 text-2xl z-[1001] cursor-pointer" id="mobileMenuToggle" aria-label="Toggle menu">☰</button>
            <nav id="mainNav" class="md:flex md:items-center md:static fixed top-0 right-[-100%] w-[280px] h-screen bg-bg-secondary border-l border-border-color transition-[right] duration-300 ease-in-out z-[1000] p-8 pt-20 overflow-y-auto md:overflow-visible md:h-auto md:w-auto md:border-0 md:p-0">
                <ul class="list-none flex gap-8 m-0 p-0 md:flex-row flex-col md:gap-8 gap-6 md:items-center items-start">
                    <li><a href="/coffee" onclick="closeMobileMenu()" class="text-accent no-underline text-[0.95rem] tracking-[1px] transition-colors hover:text-accent-hover">HOME</a></li>
                    <li><a href="#announcement" onclick="closeMobileMenu()" class="text-accent no-underline text-[0.95rem] tracking-[1px] transition-colors hover:text-accent-hover">ANNOUNCEMENT</a></li>
                    <li><a href="#menu" onclick="closeMobileMenu()" class="text-accent no-underline text-[0.95rem] tracking-[1px] transition-colors hover:text-accent-hover">MENU</a></li>
                    <li><a href="/account" onclick="closeMobileMenu()" class="text-accent no-underline text-[0.95rem] tracking-[1px] transition-colors hover:text-accent-hover">ACCOUNT</a></li>
                </ul>
            </nav>
            <div class="flex items-center gap-4">
                <?php if (session()->get('isLoggedIn')) : ?>
                    <span class="text-accent text-sm hidden md:inline">Welcome, <?= esc(session()->get('username')) ?></span>
                <?php endif; ?>
                <a href="/logout" class="text-accent no-underline text-sm px-4 py-2 border border-accent transition-all hover:bg-accent hover:text-bg-primary">LOGOUT</a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="bg-bg-primary bg-cover bg-center bg-no-repeat py-24 px-8 text-center relative" style="background-image: url('/assets/images/hero-page.jpg');">
        <div class="absolute inset-0 bg-[rgba(26,26,26,0.6)] z-[1]"></div>
        <div class="max-w-[800px] mx-auto relative z-[2]">
            <h1 class="text-[3.5rem] font-bold tracking-[5px] mb-8 text-white drop-shadow-[2px_2px_4px_rgba(0,0,0,0.5)] md:text-[2rem] md:tracking-[2px] sm:text-[1.75rem]">OUR STORY</h1>
            <?php if (session()->get('isLoggedIn')) : ?>
                <p class="mb-4 text-lg leading-relaxed text-white max-w-[600px] mx-auto font-bold drop-shadow-[1px_1px_3px_rgba(0,0,0,0.5)] md:text-base">
                    Welcome back, <strong><?= esc(session()->get('username')) ?></strong>!
                </p>
            <?php endif; ?>
            <p class="text-lg leading-relaxed text-white max-w-[600px] mx-auto font-bold drop-shadow-[1px_1px_3px_rgba(0,0,0,0.5)] md:text-base">
                At Brewkaholic, we believe that every cup of coffee tells a story. Our passion for exceptional coffee 
                drives us to source the finest beans from around the world, carefully roasted to perfection. 
                Join us on a journey of flavor, tradition, and the perfect brew.
            </p>
        </div>
    </section>

    <!-- Announcements Section -->
    <section class="py-16 px-8 bg-bg-secondary transition-colors" id="announcement">
        <div class="max-w-[1200px] mx-auto">
            <h2 class="text-center text-4xl tracking-[3px] mb-12 text-accent md:text-3xl md:mb-8 sm:text-[1.75rem]">ANNOUNCEMENTS</h2>
            <div class="flex flex-col gap-8">
                <?php if (empty($announcements)) : ?>
                    <div class="bg-bg-primary border border-border-color p-12 text-center transition-colors">
                        <p class="text-text-muted text-lg">No announcements at this time. Check back soon!</p>
                    </div>
                <?php else : ?>
                    <?php foreach ($announcements as $announcement) : ?>
                        <div class="bg-bg-primary border border-border-color p-8 transition-colors md:p-6">
                            <h3 class="text-accent mb-2 text-2xl md:text-xl"><?= esc($announcement->title) ?></h3>
                            <div class="text-text-muted text-sm mb-4">Posted: <?= date('F j, Y g:i A', strtotime($announcement->created_at)) ?></div>
                            <p class="text-text-secondary leading-relaxed whitespace-pre-wrap"><?= esc($announcement->content) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Coffee Menu Section -->
    <section class="py-20 px-8 bg-bg-secondary transition-colors md:py-12 md:px-4" id="menu">
        <div class="max-w-[1200px] mx-auto">
            <h2 class="text-center text-4xl tracking-[3px] mb-12 text-accent md:text-3xl md:mb-8 sm:text-[1.75rem]">OUR MENU</h2>
            
            <div class="flex justify-center gap-8 mb-16 flex-wrap md:mb-8 md:gap-4">
                <button class="category-btn bg-accent text-bg-primary border border-accent px-8 py-3 cursor-pointer text-base tracking-[1px] transition-all hover:bg-accent hover:text-bg-primary active:bg-accent active:text-bg-primary md:px-6 md:py-2.5 md:text-sm" data-filter="all">ALL</button>
                <?php foreach ($categories as $category) : ?>
                    <button class="category-btn bg-transparent border border-accent text-accent px-8 py-3 cursor-pointer text-base tracking-[1px] transition-all hover:bg-accent hover:text-bg-primary md:px-6 md:py-2.5 md:text-sm" data-filter="<?= strtolower(str_replace(' ', '-', $category->name)) ?>">
                        <?= strtoupper($category->name) ?>
                    </button>
                <?php endforeach; ?>
            </div>

            <div class="flex flex-wrap justify-center gap-10 mt-12 md:gap-6">
                <?php if (empty($items)) : ?>
                    <div class="col-span-full text-center py-12 text-text-secondary">
                        <p>No items available at the moment.</p>
                    </div>
                <?php else : ?>
                    <?php foreach ($items as $item) : ?>
                        <?php 
                        $categorySlug = $item->category_name ? strtolower(str_replace(' ', '-', $item->category_name)) : 'uncategorized';
                        ?>
                        <div class="coffee-card bg-bg-primary border border-border-color p-6 text-center transition-all flex-[0_0_280px] max-w-[280px] hover:-translate-y-1 hover:shadow-[0_10px_30px_rgba(139,105,20,0.2)] md:flex-[0_0_100%] md:max-w-full" data-category="<?= $categorySlug ?>">
                            <?php if ($item->image_url) : ?>
                                <div class="coffee-image w-full h-[250px] bg-gradient-to-br from-bg-tertiary to-bg-primary bg-cover bg-center mb-6 border border-border-color flex items-center justify-center text-text-muted text-sm relative overflow-hidden md:h-[200px]" style="background-image: url('<?= esc($item->image_url) ?>');"></div>
                            <?php else : ?>
                                <div class="coffee-image w-full h-[250px] bg-gradient-to-br from-bg-tertiary to-bg-primary bg-cover bg-center mb-6 border border-border-color flex items-center justify-center text-text-muted text-sm relative overflow-hidden md:h-[200px]"></div>
                            <?php endif; ?>
                            <h3 class="text-xl text-accent tracking-[1px] m-0 mb-2 md:text-lg"><?= esc($item->name) ?></h3>
                            <div class="text-2xl text-accent font-bold mb-4 md:text-xl">₱<?= number_format($item->price, 2) ?></div>
                            <p class="text-sm text-text-secondary mb-4 leading-relaxed"><?= esc($item->description ?? 'No description available.') ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-bg-primary py-12 px-8 text-center border-t border-border-color transition-colors md:py-8 md:px-4">
        <p class="text-text-muted text-sm">&copy; 2025 Brewkaholic. All rights reserved.</p>
    </footer>

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

        // Category filter functionality
        document.querySelectorAll('.category-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active class from all buttons
                document.querySelectorAll('.category-btn').forEach(b => {
                    b.classList.remove('bg-accent', 'text-bg-primary');
                    b.classList.add('bg-transparent', 'text-accent');
                });
                // Add active class to clicked button
                this.classList.add('bg-accent', 'text-bg-primary');
                this.classList.remove('bg-transparent', 'text-accent');
                
                // Get the selected category filter
                const selectedFilter = this.getAttribute('data-filter');
                
                // Get all coffee cards
                const coffeeCards = document.querySelectorAll('.coffee-card');
                
                // Filter the cards based on selected category
                coffeeCards.forEach(card => {
                    const cardCategory = card.getAttribute('data-category');
                    
                    if (selectedFilter === 'all') {
                        card.classList.remove('hidden');
                    } else {
                        if (cardCategory === selectedFilter) {
                            card.classList.remove('hidden');
                        } else {
                            card.classList.add('hidden');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>
