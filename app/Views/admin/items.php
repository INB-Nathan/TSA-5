<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Brewkaholic - Manage Items</title>
    <meta name="description" content="Manage Items">
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

    <div class="max-w-[1200px] mx-auto py-12 px-8 md:py-8 md:px-4">
        <h1 class="text-4xl font-bold tracking-[3px] mb-8 text-accent md:text-3xl sm:text-[1.75rem]">MANAGE ITEMS</h1>

        <?php if (session()->getFlashdata('msg')) : ?>
            <div class="bg-[#3a2a1a] border border-accent text-accent p-4 mb-8 text-center"><?= session()->getFlashdata('msg') ?></div>
        <?php endif; ?>

        <!-- Create Item Form -->
        <div class="bg-bg-secondary border border-border-color p-8 mb-12 md:p-6 sm:p-4">
            <h2 class="text-2xl mb-6 text-accent md:text-xl">CREATE NEW ITEM</h2>
            <?php if (isset($validation)): ?>
                <div class="bg-[#3a2a1a] border border-accent text-accent p-4 mb-6 text-center">
                    <?= $validation->listErrors() ?>
                </div>
            <?php endif; ?>

            <form action="/admin/items/create" method="post" enctype="multipart/form-data" class="grid grid-cols-[repeat(auto-fit,minmax(250px,1fr))] gap-6 md:grid-cols-1" onsubmit="disableHiddenFields(this)">
                <?= csrf_field() ?>
                
                <div class="flex flex-col">
                    <label for="name" class="text-accent text-sm tracking-[1px] mb-2">ITEM NAME</label>
                    <input type="text" name="name" id="name" required class="px-4 py-3 bg-bg-primary border border-border-color text-text-primary text-base font-serif transition-colors focus:outline-none focus:border-accent">
                </div>

                <div class="flex flex-col">
                    <label for="price" class="text-accent text-sm tracking-[1px] mb-2">PRICE (₱)</label>
                    <input type="number" name="price" id="price" step="1" min="0" required class="px-4 py-3 bg-bg-primary border border-border-color text-text-primary text-base font-serif transition-colors focus:outline-none focus:border-accent">
                </div>

                <div class="flex flex-col">
                    <label for="category_id" class="text-accent text-sm tracking-[1px] mb-2">CATEGORY</label>
                    <select name="category_id" id="category_id" required class="px-4 py-3 bg-bg-primary border border-border-color text-text-primary text-base font-serif transition-colors focus:outline-none focus:border-accent">
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $category) : ?>
                            <option value="<?= $category->id ?>"><?= esc($category->name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="flex flex-col">
                    <label for="image" class="text-accent text-sm tracking-[1px] mb-2">IMAGE (Optional)</label>
                    <input type="file" name="image" id="image" accept="image/*" class="px-2 py-2 bg-bg-primary border border-border-color text-text-primary text-base font-serif transition-colors focus:outline-none focus:border-accent cursor-pointer file:bg-accent file:text-bg-primary file:border-0 file:px-4 file:py-2 file:mr-4 file:cursor-pointer file:font-serif">
                </div>

                <div class="flex flex-col col-span-full">
                    <label for="description" class="text-accent text-sm tracking-[1px] mb-2">DESCRIPTION</label>
                    <textarea name="description" id="description" placeholder="Enter item description" class="px-4 py-3 bg-bg-primary border border-border-color text-text-primary text-base font-serif transition-colors focus:outline-none focus:border-accent resize-y min-h-[100px]"></textarea>
                </div>

                <!-- Watermark Options -->
                <div class="col-span-full bg-bg-primary p-6 border border-border-color rounded">
                    <h3 class="text-accent text-lg mb-4">WATERMARK OPTIONS</h3>
                    
                    <div class="mb-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="enable_watermark" id="enable_watermark" value="1" onchange="toggleWatermarkOptions()">
                            <span>Enable Watermark</span>
                        </label>
                    </div>

                    <div id="watermarkOptions" class="hidden">
                        <div class="mb-4">
                            <label for="watermark_type" class="text-accent text-sm tracking-[1px] mb-2 block">WATERMARK TYPE</label>
                            <select name="watermark_type" id="watermark_type" onchange="toggleWatermarkType()" class="px-4 py-3 bg-bg-primary border border-border-color text-text-primary text-base font-serif transition-colors focus:outline-none focus:border-accent w-full">
                                <option value="text">Text Watermark</option>
                                <option value="image">Image Watermark</option>
                            </select>
                        </div>

                        <!-- Text Watermark Options -->
                        <div id="textWatermarkOptions">
                            <div class="mb-4">
                                <label for="watermark_text" class="text-accent text-sm tracking-[1px] mb-2 block">WATERMARK TEXT</label>
                                <input type="text" name="watermark_text" id="watermark_text" value="BrewKaholic" placeholder="Enter watermark text" class="px-4 py-3 bg-bg-primary border border-border-color text-text-primary text-base font-serif transition-colors focus:outline-none focus:border-accent w-full">
                            </div>

                            <div class="mb-4">
                                <label for="watermark_position" class="text-accent text-sm tracking-[1px] mb-2 block">POSITION</label>
                                <select name="watermark_position" id="watermark_position" class="px-4 py-3 bg-bg-primary border border-border-color text-text-primary text-base font-serif transition-colors focus:outline-none focus:border-accent w-full">
                                    <option value="bottom-right">Bottom Right</option>
                                    <option value="bottom-left">Bottom Left</option>
                                    <option value="top-right">Top Right</option>
                                    <option value="top-left">Top Left</option>
                                    <option value="center">Center</option>
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-4 md:grid-cols-1">
                                <div class="flex flex-col">
                                    <label for="watermark_font_size" class="text-accent text-sm tracking-[1px] mb-2">FONT SIZE</label>
                                    <input type="number" name="watermark_font_size" id="watermark_font_size" value="16" min="10" max="72" class="px-4 py-3 bg-bg-primary border border-border-color text-text-primary text-base font-serif transition-colors focus:outline-none focus:border-accent">
                                </div>

                                <div class="flex flex-col">
                                    <label for="watermark_opacity" class="text-accent text-sm tracking-[1px] mb-2">OPACITY (%)</label>
                                    <input type="number" name="watermark_opacity" id="watermark_opacity" value="50" min="0" max="100" class="px-4 py-3 bg-bg-primary border border-border-color text-text-primary text-base font-serif transition-colors focus:outline-none focus:border-accent">
                                </div>
                            </div>

                            <div class="flex flex-col">
                                <label for="watermark_color" class="text-accent text-sm tracking-[1px] mb-2">TEXT COLOR</label>
                                <input type="color" name="watermark_color" id="watermark_color" value="#FFFFFF" class="px-4 py-3 bg-bg-primary border border-border-color text-text-primary text-base font-serif transition-colors focus:outline-none focus:border-accent h-12">
                            </div>
                        </div>

                        <!-- Image Watermark Options -->
                        <div id="imageWatermarkOptions" class="hidden">
                            <div class="flex flex-col mb-4">
                                <label for="watermark_image" class="text-accent text-sm tracking-[1px] mb-2">WATERMARK IMAGE</label>
                                <input type="file" name="watermark_image" id="watermark_image" accept="image/*" class="px-2 py-2 bg-bg-primary border border-border-color text-text-primary text-base font-serif transition-colors focus:outline-none focus:border-accent cursor-pointer file:bg-accent file:text-bg-primary file:border-0 file:px-4 file:py-2 file:mr-4 file:cursor-pointer file:font-serif">
                                <small class="text-[#888] text-xs block mt-2">
                                    Upload a PNG image with transparency for best results
                                </small>
                            </div>

                            <div class="mb-4">
                                <label for="watermark_position_image" class="text-accent text-sm tracking-[1px] mb-2 block">POSITION</label>
                                <select name="watermark_position_image" id="watermark_position_image" class="px-4 py-3 bg-bg-primary border border-border-color text-text-primary text-base font-serif transition-colors focus:outline-none focus:border-accent w-full">
                                    <option value="bottom-right">Bottom Right</option>
                                    <option value="bottom-left">Bottom Left</option>
                                    <option value="top-right">Top Right</option>
                                    <option value="top-left">Top Left</option>
                                    <option value="center">Center</option>
                                </select>
                            </div>

                            <div class="flex flex-col">
                                <label for="watermark_opacity_image" class="text-accent text-sm tracking-[1px] mb-2">OPACITY (%)</label>
                                <input type="number" name="watermark_opacity_image" id="watermark_opacity_image" value="50" min="0" max="100" class="px-4 py-3 bg-bg-primary border border-border-color text-text-primary text-base font-serif transition-colors focus:outline-none focus:border-accent">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-span-full">
                    <button type="submit" class="bg-accent border border-accent text-bg-primary px-8 py-3 text-base tracking-[2px] cursor-pointer transition-all font-serif font-bold hover:bg-accent-hover">CREATE ITEM</button>
                </div>
            </form>
        </div>

        <!-- Items List -->
        <h2 class="text-2xl mb-4 text-accent">Existing Items<?= isset($totalItems) ? ' (' . $totalItems . ')' : '' ?></h2>
        
        <div class="overflow-x-auto -webkit-overflow-scrolling-touch md:hidden">
            <table class="w-full border-collapse bg-bg-secondary border border-border-color">
                <thead>
                    <tr>
                        <th class="p-4 text-left border-b border-border-color bg-bg-primary text-accent font-bold tracking-[1px]">ID</th>
                        <th class="p-4 text-left border-b border-border-color bg-bg-primary text-accent font-bold tracking-[1px]">Name</th>
                        <th class="p-4 text-left border-b border-border-color bg-bg-primary text-accent font-bold tracking-[1px]">Description</th>
                        <th class="p-4 text-left border-b border-border-color bg-bg-primary text-accent font-bold tracking-[1px]">Price</th>
                        <th class="p-4 text-left border-b border-border-color bg-bg-primary text-accent font-bold tracking-[1px]">Category</th>
                        <th class="p-4 text-left border-b border-border-color bg-bg-primary text-accent font-bold tracking-[1px]">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($items)) : ?>
                        <tr>
                            <td colspan="6" class="text-center py-8">No items found.</td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($items as $item) : ?>
                            <tr class="hover:bg-bg-tertiary">
                                <td class="p-4 text-left border-b border-border-color text-text-secondary"><?= $item->id ?></td>
                                <td class="p-4 text-left border-b border-border-color text-text-secondary"><?= esc($item->name) ?></td>
                                <td class="p-4 text-left border-b border-border-color text-text-secondary"><?= esc($item->description ?? 'No description') ?></td>
                                <td class="p-4 text-left border-b border-border-color text-text-secondary">₱<?= number_format($item->price, 2) ?></td>
                                <td class="p-4 text-left border-b border-border-color text-text-secondary"><?= esc($item->category_name ?? 'No category') ?></td>
                                <td class="p-4 text-left border-b border-border-color text-text-secondary">
                                    <a href="/admin/items/delete/<?= $item->id ?>" class="bg-transparent border border-danger text-danger px-4 py-2 no-underline text-xs tracking-[1px] transition-all inline-block mr-2 hover:bg-danger hover:text-bg-primary" onclick="return confirm('Are you sure you want to delete this item?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <?php if (!empty($items)) : ?>
            <?php foreach ($items as $item) : ?>
                <div class="hidden md:block bg-bg-secondary border border-border-color p-6 mb-4 sm:p-4">
                    <div class="flex justify-between py-3 border-b border-border-color">
                        <span class="font-bold text-accent">ID:</span>
                        <span class="text-text-secondary text-right break-words"><?= $item->id ?></span>
                    </div>
                    <div class="flex justify-between py-3 border-b border-border-color">
                        <span class="font-bold text-accent">Name:</span>
                        <span class="text-text-secondary text-right break-words"><?= esc($item->name) ?></span>
                    </div>
                    <div class="flex justify-between py-3 border-b border-border-color">
                        <span class="font-bold text-accent">Description:</span>
                        <span class="text-text-secondary text-right break-words"><?= esc($item->description ?? 'No description') ?></span>
                    </div>
                    <div class="flex justify-between py-3 border-b border-border-color">
                        <span class="font-bold text-accent">Price:</span>
                        <span class="text-text-secondary text-right break-words">₱<?= number_format($item->price, 2) ?></span>
                    </div>
                    <div class="flex justify-between py-3 border-b border-border-color">
                        <span class="font-bold text-accent">Category:</span>
                        <span class="text-text-secondary text-right break-words"><?= esc($item->category_name ?? 'No category') ?></span>
                    </div>
                    <div class="flex gap-2 mt-4">
                        <a href="/admin/items/delete/<?= $item->id ?>" class="bg-transparent border border-danger text-danger px-4 py-2 no-underline text-xs tracking-[1px] transition-all inline-block hover:bg-danger hover:text-bg-primary" onclick="return confirm('Are you sure you want to delete this item?')">Delete</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if (isset($totalItems) && $totalItems > 0) : ?>
            <div class="text-center mt-4 text-text-secondary text-sm">
                <?php 
                $start = (($currentPage - 1) * 10) + 1;
                $end = min($currentPage * 10, $totalItems);
                ?>
                Showing <?= $start ?> to <?= $end ?> of <?= $totalItems ?> items
            </div>
        <?php endif; ?>
        
        <?php if (isset($totalItems) && $totalItems > 0 && isset($totalPages)) : ?>
            <div class="flex justify-center gap-2 mt-8 flex-wrap md:gap-1">
                <?php if ($currentPage > 1) : ?>
                    <a href="/admin/items?page=<?= $currentPage - 1 ?>" class="px-4 py-2 border border-accent text-accent no-underline transition-all hover:bg-accent hover:text-bg-primary md:px-3 md:py-1.5 md:text-xs">Previous</a>
                <?php else : ?>
                    <span class="px-4 py-2 border border-accent text-accent opacity-50 cursor-not-allowed pointer-events-none md:px-3 md:py-1.5 md:text-xs">Previous</span>
                <?php endif; ?>
                
                <?php
                $startPage = max(1, $currentPage - 2);
                $endPage = min($totalPages, $currentPage + 2);
                
                if ($startPage > 1) : ?>
                    <a href="/admin/items?page=1" class="px-4 py-2 border border-accent text-accent no-underline transition-all hover:bg-accent hover:text-bg-primary md:px-3 md:py-1.5 md:text-xs">1</a>
                    <?php if ($startPage > 2) : ?>
                        <span>...</span>
                    <?php endif; ?>
                <?php endif; ?>
                
                <?php for ($i = $startPage; $i <= $endPage; $i++) : ?>
                    <?php if ($i == $currentPage) : ?>
                        <span class="px-4 py-2 border border-accent bg-accent text-bg-primary md:px-3 md:py-1.5 md:text-xs"><?= $i ?></span>
                    <?php else : ?>
                        <a href="/admin/items?page=<?= $i ?>" class="px-4 py-2 border border-accent text-accent no-underline transition-all hover:bg-accent hover:text-bg-primary md:px-3 md:py-1.5 md:text-xs"><?= $i ?></a>
                    <?php endif; ?>
                <?php endfor; ?>
                
                <?php if ($endPage < $totalPages) : ?>
                    <?php if ($endPage < $totalPages - 1) : ?>
                        <span>...</span>
                    <?php endif; ?>
                    <a href="/admin/items?page=<?= $totalPages ?>" class="px-4 py-2 border border-accent text-accent no-underline transition-all hover:bg-accent hover:text-bg-primary md:px-3 md:py-1.5 md:text-xs"><?= $totalPages ?></a>
                <?php endif; ?>
                
                <?php if ($currentPage < $totalPages) : ?>
                    <a href="/admin/items?page=<?= $currentPage + 1 ?>" class="px-4 py-2 border border-accent text-accent no-underline transition-all hover:bg-accent hover:text-bg-primary md:px-3 md:py-1.5 md:text-xs">Next</a>
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

        // Watermark options toggle
        function toggleWatermarkOptions() {
            const checkbox = document.getElementById('enable_watermark');
            const options = document.getElementById('watermarkOptions');
            if (checkbox.checked) {
                options.classList.remove('hidden');
                // Ensure correct fields are enabled based on watermark type
                toggleWatermarkType();
            } else {
                options.classList.add('hidden');
                // Disable all watermark fields when watermark is disabled
                options.querySelectorAll('input, select, textarea').forEach(field => {
                    field.disabled = true;
                });
            }
        }

        // Initialize: disable hidden watermark fields on page load
        document.addEventListener('DOMContentLoaded', function() {
            const imageOptions = document.getElementById('imageWatermarkOptions');
            if (imageOptions && imageOptions.classList.contains('hidden')) {
                imageOptions.querySelectorAll('input, select').forEach(field => {
                    field.disabled = true;
                });
            }
            const watermarkOptions = document.getElementById('watermarkOptions');
            if (watermarkOptions && watermarkOptions.classList.contains('hidden')) {
                watermarkOptions.querySelectorAll('input, select, textarea').forEach(field => {
                    field.disabled = true;
                });
            }
        });

        function toggleWatermarkType() {
            const type = document.getElementById('watermark_type').value;
            const textOptions = document.getElementById('textWatermarkOptions');
            const imageOptions = document.getElementById('imageWatermarkOptions');
            
            if (type === 'text') {
                textOptions.classList.remove('hidden');
                imageOptions.classList.add('hidden');
                // Disable hidden fields to prevent them from being submitted
                imageOptions.querySelectorAll('input, select').forEach(field => {
                    field.disabled = true;
                });
                textOptions.querySelectorAll('input, select').forEach(field => {
                    field.disabled = false;
                });
            } else {
                textOptions.classList.add('hidden');
                imageOptions.classList.remove('hidden');
                // Disable hidden fields to prevent them from being submitted
                textOptions.querySelectorAll('input, select').forEach(field => {
                    field.disabled = true;
                });
                imageOptions.querySelectorAll('input, select').forEach(field => {
                    field.disabled = false;
                });
            }
        }

        // Disable all hidden fields before form submission to prevent arrays in POST data
        function disableHiddenFields(form) {
            const hiddenSections = form.querySelectorAll('.hidden');
            hiddenSections.forEach(section => {
                section.querySelectorAll('input, select, textarea').forEach(field => {
                    field.disabled = true;
                });
            });
            return true;
        }
    </script>
</body>
</html>
