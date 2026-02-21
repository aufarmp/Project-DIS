<!DOCTYPE html>
<html class="dark" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title><?= $title ?? 'Comi' ?></title>
    
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@100..900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
    
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#256af4",
                        "primary-hover": "#1d54c4",
                        "accent-purple": "#a855f7",
                        "background-dark": "#16263B",    
                        "background-card": "#1E324F",     
                        "text-secondary": "#94a3b8",
                    },
                    fontFamily: {
                        "display": ["Be Vietnam Pro", "sans-serif"]
                    }
                },
            },
        }
    </script>
    <style>
        body { font-family: "Be Vietnam Pro", sans-serif; }
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #16263B; }
        ::-webkit-scrollbar-thumb { background: #5A6572; border-radius: 4px; }
    </style>
</head>
<body class="bg-background-dark text-white overflow-x-hidden min-h-screen flex flex-col">

    <?= $this->include('layout/navbar') ?>

    <main class="flex-grow">
        <?= $this->renderSection('content') ?>
    </main>

    <?= $this->include('layout/footer') ?>

</body>
</html>