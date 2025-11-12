<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Brewkaholic - Coffee</title>
    <meta name="description" content="Discover the finest coffee at Brewkaholic">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="/favicon.ico">
    <style>
        :root {
            --bg-primary: #1a1a1a;
            --bg-secondary: #242424;
            --bg-tertiary: #2a2a2a;
            --border-color: #3a3a3a;
            --text-primary: #d4a574;
            --text-secondary: #c4a574;
            --text-muted: #666;
            --accent-color: #d4a574;
            --accent-hover: #f4d4a4;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Georgia', 'Times New Roman', serif;
            background-color: var(--bg-primary);
            color: var(--text-primary);
            line-height: 1.6;
        }

        /* Header */
        header {
            background-color: var(--bg-primary);
            padding: 1.5rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid var(--border-color);
            transition: background-color 0.3s, border-color 0.3s;
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
            color: var(--accent-color);
            text-decoration: none;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 2rem;
        }

        nav ul li a {
            color: var(--accent-color);
            text-decoration: none;
            font-size: 0.95rem;
            letter-spacing: 1px;
            transition: color 0.3s;
        }

        nav ul li a:hover {
            color: var(--accent-hover);
        }

        .logout-link {
            color: var(--accent-color);
            text-decoration: none;
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
            border: 1px solid var(--accent-color);
            transition: all 0.3s;
        }

        .logout-link:hover {
            background-color: var(--accent-color);
            color: var(--bg-primary);
        }

        /* Hero Section */
        .hero {
            background-color: #1a1a1a;
            background-image: url('/assets/images/hero-page.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            padding: 6rem 2rem;
            text-align: center;
            position: relative;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(26, 26, 26, 0.6);
            z-index: 1;
        }

        .hero-content {
            max-width: 800px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: bold;
            letter-spacing: 5px;
            margin-bottom: 2rem;
            color: #ffffff;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .hero p {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #ffffff;
            max-width: 600px;
            margin: 0 auto;
            font-weight: bold;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
        }

        /* Coffee Menu Section */
        .menu-section {
            padding: 5rem 2rem;
            background-color: var(--bg-secondary);
            transition: background-color 0.3s;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            letter-spacing: 3px;
            margin-bottom: 3rem;
            color: var(--accent-color);
        }

        .categories {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-bottom: 4rem;
            flex-wrap: wrap;
        }

        .category-btn {
            background: transparent;
            border: 1px solid var(--accent-color);
            color: var(--accent-color);
            padding: 0.75rem 2rem;
            cursor: pointer;
            font-size: 1rem;
            letter-spacing: 1px;
            transition: all 0.3s;
        }

        .category-btn:hover,
        .category-btn.active {
            background-color: var(--accent-color);
            color: var(--bg-primary);
        }

        .coffee-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 2.5rem;
            margin-top: 3rem;
        }

        .coffee-card {
            background-color: var(--bg-primary);
            border: 1px solid var(--border-color);
            padding: 1.5rem;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s, opacity 0.3s, background-color 0.3s, border-color 0.3s;
            flex: 0 0 280px;
            max-width: 280px;
        }

        .coffee-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(139, 105, 20, 0.2);
        }

        .coffee-card.hidden {
            display: none;
        }

        .coffee-image {
            width: 100%;
            height: 250px;
            background: linear-gradient(135deg, var(--bg-tertiary) 0%, var(--bg-primary) 100%);
            background-size: cover;
            background-position: center;
            margin-bottom: 1.5rem;
            border: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            font-size: 0.9rem;
            position: relative;
            overflow: hidden;
        }

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
            color: var(--text-muted);
            opacity: 0.7;
        }

        .coffee-name {
            font-size: 1.3rem;
            color: var(--accent-color);
            letter-spacing: 1px;
            margin: 0 0 0.5rem 0;
        }

        .coffee-price {
            font-size: 1.5rem;
            color: var(--accent-color);
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .coffee-description {
            font-size: 0.95rem;
            color: var(--text-secondary);
            margin-bottom: 1rem;
            line-height: 1.6;
        }

        /* Footer */
        footer {
            background-color: var(--bg-primary);
            padding: 3rem 2rem;
            text-align: center;
            border-top: 1px solid var(--border-color);
            transition: background-color 0.3s, border-color 0.3s;
        }

        footer p {
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        /* Announcements Section */
        .announcements-section {
            padding: 4rem 2rem;
            background-color: var(--bg-secondary);
            transition: background-color 0.3s;
        }

        .announcements-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .announcements-title {
            text-align: center;
            font-size: 2rem;
            letter-spacing: 3px;
            margin-bottom: 3rem;
            color: var(--accent-color);
        }

        .announcements-list {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .announcement-card {
            background-color: var(--bg-primary);
            border: 1px solid var(--border-color);
            padding: 2rem;
            transition: background-color 0.3s, border-color 0.3s;
        }

        .announcement-card h3 {
            color: var(--accent-color);
            margin-bottom: 0.5rem;
            font-size: 1.5rem;
        }

        .announcement-card .meta {
            color: var(--text-muted);
            font-size: 0.85rem;
            margin-bottom: 1rem;
        }

        .announcement-card p {
            color: var(--text-secondary);
            line-height: 1.8;
            white-space: pre-wrap;
        }

        /* Responsive */
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

            .hero h1 {
                font-size: 2.5rem;
            }

            .coffee-grid {
                grid-template-columns: 1fr;
            }

            .categories {
                flex-direction: column;
                align-items: center;
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
                    <li><a href="#announcement">ANNOUNCEMENT</a></li>
                    <li><a href="#menu">MENU</a></li>
                    <li><a href="/account">ACCOUNT</a></li>
                </ul>
            </nav>
            <div style="display: flex; align-items: center; gap: 1rem;">
                <?php if (session()->get('isLoggedIn')) : ?>
                    <span style="color: #d4a574; font-size: 0.9rem;">Welcome, <?= esc(session()->get('username')) ?></span>
                <?php endif; ?>
                <a href="/logout" class="logout-link">LOGOUT</a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>OUR STORY</h1>
            <?php if (session()->get('isLoggedIn')) : ?>
                <p style="margin-bottom: 1rem;">
                    Welcome back, <strong><?= esc(session()->get('username')) ?></strong>!
                </p>
            <?php endif; ?>
            <p>
                At Brewkaholic, we believe that every cup of coffee tells a story. Our passion for exceptional coffee 
                drives us to source the finest beans from around the world, carefully roasted to perfection. 
                Join us on a journey of flavor, tradition, and the perfect brew.
            </p>
        </div>
    </section>

    <!-- Announcements Section -->
    <section class="announcements-section" id="announcement">
        <div class="announcements-container">
            <h2 class="announcements-title">ANNOUNCEMENTS</h2>
            <div class="announcements-list">
                <?php if (empty($announcements)) : ?>
                    <div class="announcement-card" style="text-align: center; padding: 3rem;">
                        <p style="color: var(--text-muted); font-size: 1.1rem;">No announcements at this time. Check back soon!</p>
                    </div>
                <?php else : ?>
                    <?php foreach ($announcements as $announcement) : ?>
                        <div class="announcement-card">
                            <h3><?= esc($announcement->title) ?></h3>
                            <div class="meta">Posted: <?= date('F j, Y g:i A', strtotime($announcement->created_at)) ?></div>
                            <p><?= esc($announcement->content) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Coffee Menu Section -->
    <section class="menu-section" id="menu">
        <div class="container">
            <h2 class="section-title">OUR MENU</h2>
            
            <div class="categories">
                <button class="category-btn active" data-filter="all">ALL</button>
                <?php foreach ($categories as $category) : ?>
                    <button class="category-btn" data-filter="<?= strtolower(str_replace(' ', '-', $category->name)) ?>">
                        <?= strtoupper($category->name) ?>
                    </button>
                <?php endforeach; ?>
            </div>

            <div class="coffee-grid">
                <?php if (empty($items)) : ?>
                    <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: var(--text-secondary);">
                        <p>No items available at the moment.</p>
                    </div>
                <?php else : ?>
                    <?php foreach ($items as $item) : ?>
                        <?php 
                        $categorySlug = $item->category_name ? strtolower(str_replace(' ', '-', $item->category_name)) : 'uncategorized';
                        ?>
                        <div class="coffee-card" data-category="<?= $categorySlug ?>">
                            <?php if ($item->image_url) : ?>
                                <div class="coffee-image" style="background-image: url('<?= esc($item->image_url) ?>');"></div>
                            <?php else : ?>
                                <div class="coffee-image"></div>
                            <?php endif; ?>
                            <h3 class="coffee-name"><?= esc($item->name) ?></h3>
                            <div class="coffee-price">₱<?= number_format($item->price, 2) ?></div>
                            <p class="coffee-description"><?= esc($item->description ?? 'No description available.') ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 Brewkaholic. All rights reserved.</p>
    </footer>

    <script>
        // Category filter functionality
        document.querySelectorAll('.category-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active class from all buttons
                document.querySelectorAll('.category-btn').forEach(b => b.classList.remove('active'));
                // Add active class to clicked button
                this.classList.add('active');
                
                // Get the selected category filter
                const selectedFilter = this.getAttribute('data-filter');
                
                // Get all coffee cards
                const coffeeCards = document.querySelectorAll('.coffee-card');
                
                // Filter the cards based on selected category
                coffeeCards.forEach(card => {
                    const cardCategory = card.getAttribute('data-category');
                    
                    if (selectedFilter === 'all') {
                        // Show all cards
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
ml>
