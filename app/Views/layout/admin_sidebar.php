<aside class="hidden md:flex flex-col w-64 h-full bg-surface-dark border-r border-white/5 flex-shrink-0 z-20 shadow-xl">
    <div class="flex items-center gap-3 px-6 py-6 border-b border-white/5">
        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-primary to-accent-purple flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-primary/20">
            <span class="material-symbols-outlined">menu_book</span>
        </div>
        <div class="flex flex-col">
            <h1 class="text-white text-lg font-bold leading-tight tracking-tight">Comi.id</h1>
            <p class="text-slate-400 text-xs font-medium">Admin Panel</p>
        </div>
    </div>

    <nav class="flex-1 flex flex-col gap-2 p-4 overflow-y-auto">
        <a href="<?= base_url('admin/dashboard') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg <?= current_url() == base_url('admin/dashboard') ? 'bg-primary/10 text-primary border border-primary/20' : 'text-slate-400 hover:text-white hover:bg-white/5' ?> transition-colors group">
            <span class="material-symbols-outlined <?= current_url() == base_url('admin/dashboard') ? 'fill-1' : 'group-hover:text-primary' ?> transition-colors">dashboard</span>
            <span class="text-sm font-medium">Dashboard</span>
        </a>

        <a href="<?= base_url('admin/komik') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-400 hover:text-white hover:bg-white/5 transition-colors group">
            <span class="material-symbols-outlined group-hover:text-primary transition-colors">auto_stories</span>
            <span class="text-sm font-medium">Kelola Komik</span>
        </a>

        <a href="<?= base_url('admin/chapter') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg <?= current_url() == base_url('admin/chapter') ? 'bg-primary/10 text-primary border border-primary/20' : 'text-slate-400 hover:text-white hover:bg-white/5' ?> transition-colors group">
            <span class="material-symbols-outlined <?= current_url() == base_url('admin/chapter') ? 'fill-1' : 'group-hover:text-primary' ?> transition-colors">menu_book</span>
            <span class="text-sm font-medium">Kelola Chapter</span>
        </a>

        <a href="<?= base_url('admin/users') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-400 hover:text-white hover:bg-white/5 transition-colors group">
            <span class="material-symbols-outlined group-hover:text-primary transition-colors">group</span>
            <span class="text-sm font-medium">Daftar Pengguna</span>
        </a>
    </nav>

    <div class="p-4 border-t border-white/5">
        <div class="flex items-center gap-3 px-3 py-2 rounded-lg bg-background-dark/50 border border-white/5">
            <div class="h-8 w-8 rounded-full bg-primary flex items-center justify-center text-white font-bold text-sm">
                <?= strtoupper(substr(session()->get('username'), 0, 1)) ?>
            </div>
            <div class="flex flex-col overflow-hidden">
                <span class="text-sm font-medium text-white truncate"><?= session()->get('username') ?></span>
                <span class="text-xs text-slate-400 truncate capitalize"><?= session()->get('role') ?></span>
            </div>
            <a href="<?= base_url('logout') ?>" class="ml-auto text-slate-400 hover:text-red-400 transition-colors" title="Logout">
                <span class="material-symbols-outlined text-[20px]">logout</span>
            </a>
        </div>
    </div>
</aside>