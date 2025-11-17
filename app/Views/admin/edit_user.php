<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Brewkaholic - Edit User</title>
    <meta name="description" content="Edit User">
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
        .max-w-\[1200px\] {
            max-width: 1200px !important;
        }
        .max-w-\[600px\] {
            max-width: 600px !important;
        }
    </style>
</head>
<body class="font-serif bg-bg-primary text-text-primary leading-relaxed min-h-screen p-8 md:p-4">
    <!-- Header -->
    <header class="bg-bg-primary py-6 mb-8 border-b border-border-color">
        <div class="max-w-[1200px] mx-auto px-8 flex justify-between items-center md:flex-col md:gap-4 md:px-4">
            <a href="/admin/dashboard" class="text-[1.8rem] font-bold tracking-[3px] text-accent no-underline">BREWKAHOLIC</a>
            <nav>
                <ul class="list-none flex gap-8 md:flex-col md:gap-4 md:text-center">
                    <li><a href="/admin/dashboard" class="text-accent no-underline text-sm tracking-[1px] transition-colors hover:text-accent-hover">DASHBOARD</a></li>
                    <li><a href="/admin/users" class="text-accent no-underline text-sm tracking-[1px] transition-colors hover:text-accent-hover">USERS</a></li>
                    <li><a href="/admin/items" class="text-accent no-underline text-sm tracking-[1px] transition-colors hover:text-accent-hover">ITEMS</a></li>
                    <li><a href="/admin/announcements" class="text-accent no-underline text-sm tracking-[1px] transition-colors hover:text-accent-hover">ANNOUNCEMENTS</a></li>
                </ul>
            </nav>
            <div class="flex items-center gap-4">
                <?php if (session()->get('isLoggedIn')) : ?>
                    <span class="text-accent text-sm">Admin: <?= esc(session()->get('username')) ?></span>
                <?php endif; ?>
                <a href="/logout" class="text-accent no-underline text-sm px-4 py-2 border border-accent transition-all hover:bg-accent hover:text-bg-primary">LOGOUT</a>
            </div>
        </div>
    </header>

    <div class="max-w-[600px] mx-auto bg-bg-secondary border border-border-color p-12 shadow-[0_10px_30px_rgba(0,0,0,0.5)] md:p-8">
        <h1 class="text-3xl font-bold tracking-[3px] mb-8 text-accent text-center">EDIT USER</h1>

        <?php if (isset($validation)): ?>
            <div class="bg-[#3a2a1a] border border-accent text-accent p-4 mb-6 text-sm">
                <?= $validation->listErrors() ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('msg')) : ?>
            <div class="bg-[#3a2a1a] border border-accent text-accent p-4 mb-6 text-sm"><?= session()->getFlashdata('msg') ?></div>
        <?php endif; ?>

        <form action="/admin/users/update/<?= $user->id ?>" method="post" class="flex flex-col gap-6">
            <?= csrf_field() ?>
            
            <div>
                <label for="username" class="text-accent text-sm tracking-[1px] mb-2 block">USERNAME</label>
                <input type="text" name="username" id="username" value="<?= esc($user->username) ?>" required class="w-full px-4 py-3 bg-bg-primary border border-border-color text-text-primary text-base font-serif transition-colors focus:outline-none focus:border-accent">
            </div>

            <div>
                <label for="email" class="text-accent text-sm tracking-[1px] mb-2 block">EMAIL</label>
                <input type="email" name="email" id="email" value="<?= esc($user->email) ?>" required class="w-full px-4 py-3 bg-bg-primary border border-border-color text-text-primary text-base font-serif transition-colors focus:outline-none focus:border-accent">
            </div>

            <div>
                <label for="role_id" class="text-accent text-sm tracking-[1px] mb-2 block">ROLE</label>
                <select name="role_id" id="role_id" required class="w-full px-4 py-3 bg-bg-primary border border-border-color text-text-primary text-base font-serif transition-colors focus:outline-none focus:border-accent">
                    <?php foreach ($roles as $role) : ?>
                        <option value="<?= $role->id ?>" <?= ($userRole && $userRole->role_id == $role->id) ? 'selected' : '' ?>>
                            <?= esc(ucfirst($role->name)) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="bg-bg-tertiary border border-accent p-4 text-accent text-sm -mt-2">
                <strong>Note:</strong> Password will be automatically reset to "123" when you update this user.
            </div>

            <div class="flex gap-4 mt-4">
                <button type="submit" class="bg-transparent border border-accent text-accent px-8 py-3 text-base tracking-[2px] cursor-pointer transition-all font-serif hover:bg-accent hover:text-bg-primary">UPDATE USER</button>
                <a href="/admin/users" class="bg-transparent border border-[#666] text-[#666] px-8 py-3 text-base tracking-[2px] cursor-pointer transition-all font-serif no-underline inline-block hover:bg-[#666] hover:text-bg-primary">CANCEL</a>
            </div>
        </form>
    </div>
</body>
</html>
