<header class="sticky top-0 z-50 w-full border-b border-white/10 bg-background-dark/90 backdrop-blur-md shadow-lg">
    <div class="mx-auto flex h-16 max-w-[1200px] items-center justify-between px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-8">
            <a class="flex items-center gap-2 group" href="<?= base_url('/home') ?>">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-primary to-accent-purple text-white shadow-lg">
                    <span class="material-symbols-outlined text-[20px]">menu_book</span>
                </div>
                <span class="text-xl font-bold tracking-tight text-white group-hover:text-primary transition-colors">Comi.id</span>
            </a>
            <nav class="hidden md:flex items-center gap-6">
                <a href="<?= base_url('/') ?>" 
                class="text-sm font-medium transition-colors <?= url_is('/') || url_is('') ? 'text-primary' : 'text-text-secondary hover:text-white' ?>">
                Home
                </a>
                
                <a href="<?= base_url('popular') ?>" 
                class="text-sm font-medium transition-colors <?= url_is('popular*') ? 'text-primary' : 'text-text-secondary hover:text-white' ?>">
                Popular
                </a>
                
                <a href="<?= base_url('genres') ?>" 
                class="text-sm font-medium transition-colors <?= url_is('genres*') ? 'text-primary' : 'text-text-secondary hover:text-white' ?>">
                Genres
                </a>
            </nav>
        </div>
        
        <div class="flex items-center gap-4">
            <div class="hidden sm:flex relative group/search">
                <form action="<?= base_url('search') ?>" method="GET" class="hidden sm:flex relative group/search">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-text-secondary">
                        <span class="material-symbols-outlined text-[20px]">search</span>
                    </span>
                    <input name="q" type="text" required class="block w-full min-w-[200px] rounded-full border-none bg-background-card py-2 pl-10 pr-4 text-sm text-white placeholder-text-secondary focus:ring-2 focus:ring-primary outline-none transition-all" placeholder="Cari komik..."/>
                </form>
            </div>

            <?php if (session()->get('isLoggedIn')) : ?>
                
                <div class="relative group">
                    <button class="flex items-center gap-2 rounded-full ring-2 ring-transparent hover:ring-primary/50 transition-all p-1 pr-3 bg-background-card cursor-pointer">
                        <div class="h-8 w-8 rounded-full bg-gradient-to-br from-primary to-accent-purple flex items-center justify-center text-white font-bold text-sm shadow-md">
                            <?= strtoupper(substr(session()->get('username'), 0, 1)) ?>
                        </div>
                        <span class="text-sm font-medium text-white truncate max-w-[100px]">
                            <?= esc(session()->get('username')) ?>
                        </span>
                        <span class="material-symbols-outlined text-[18px] text-text-secondary group-hover:text-white transition-colors">expand_more</span>
                    </button>

                    <div class="absolute right-0 mt-2 w-56 origin-top-right rounded-xl bg-background-card shadow-2xl ring-1 ring-white/10 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50 overflow-hidden transform group-hover:translate-y-0 translate-y-2">
                        <div class="py-2">
                            
                            <?php if(session()->get('role') === 'admin') : ?>
                                <a href="<?= base_url('admin/dashboard') ?>" class="flex items-center gap-3 px-4 py-2 text-sm text-primary hover:bg-white/5 transition-colors font-semibold">
                                    <span class="material-symbols-outlined text-[20px]">admin_panel_settings</span>
                                    Dashboard Admin
                                </a>
                                <div class="border-t border-white/5 my-1"></div>
                            <?php endif; ?>
                            
                            <a href="<?= base_url('user/profile') ?>" class="flex items-center gap-3 px-4 py-2 text-sm text-slate-300 hover:bg-white/5 hover:text-white transition-colors">
                                <span class="material-symbols-outlined text-[20px]">person</span> Profil Saya
                            </a>

                            <a href="<?= base_url('user/library') ?>" class="flex items-center gap-3 px-4 py-2 text-sm text-slate-300 hover:bg-white/5 hover:text-white transition-colors">
                                <span class="material-symbols-outlined text-[20px]">book</span> Library Saya
                            </a>

                            <a href="<?= base_url('user/settings') ?>" class="flex items-center gap-3 px-4 py-2 text-sm text-slate-300 hover:bg-white/5 hover:text-white transition-colors">
                                <span class="material-symbols-outlined text-[20px]">settings</span> Pengaturan
                            </a>

                            <div class="border-t border-white/5 my-1"></div>
                            
                            <a href="<?= base_url('logout') ?>" class="flex items-center gap-3 px-4 py-2 text-sm text-red-400 hover:bg-red-500/10 hover:text-red-300 transition-colors">
                                <span class="material-symbols-outlined text-[20px]">logout</span> Logout
                            </a>
                        </div>
                    </div>
                </div>

            <?php else : ?>
                
                <a href="<?= base_url('login') ?>" class="rounded-full bg-white/10 px-5 py-2 text-sm font-medium text-white hover:bg-primary hover:shadow-lg hover:shadow-primary/30 transition-all">
                    Login
                </a>
                
            <?php endif; ?>
        </div>
    </div>
</header>