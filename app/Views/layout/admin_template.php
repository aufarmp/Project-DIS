<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title><?= $title ?? 'Admin Panel - Comi' ?></title>
    
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#256af4",         // Biru ciri khas Comi
                        "primary-dark": "#1d54c4",    
                        "accent-purple": "#a855f7",
                        "background-dark": "#16263B", // Navy gelap (Sesuai request)
                        "surface-dark": "#1E324F",    // Navy lebih terang untuk Card/Sidebar
                    },
                    fontFamily: {
                        display: ["Be Vietnam Pro", "sans-serif"],
                    }
                },
            },
        }
    </script>
    <style>
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #16263B; }
        ::-webkit-scrollbar-thumb { background: #1E324F; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #256af4; }
    </style>
</head>
<body class="bg-background-dark font-display text-slate-100 antialiased overflow-hidden">
    <div class="flex h-screen w-full overflow-hidden">
        
        <?= $this->include('layout/admin_sidebar') ?>

        <main class="flex-1 flex flex-col h-full overflow-hidden relative">
            <div class="absolute top-0 left-0 w-full h-96 bg-primary/5 rounded-full blur-[100px] pointer-events-none -translate-y-1/2"></div>
            
            <?= $this->renderSection('content') ?>
        </main>

    </div>
</body>
</html>