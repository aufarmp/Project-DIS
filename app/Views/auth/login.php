<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="relative flex min-h-[calc(100vh-130px)] items-center justify-center overflow-hidden py-12 px-4 sm:px-6 lg:px-8">
    
    <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1614726365723-49fa26027a68?q=80&w=2574&auto=format&fit=crop')] bg-cover bg-center opacity-5 mix-blend-overlay pointer-events-none"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-background-dark via-background-dark/90 to-transparent pointer-events-none"></div>

    <div class="relative z-10 w-full max-w-md space-y-8 rounded-2xl bg-background-card/80 p-8 sm:p-10 shadow-2xl ring-1 ring-white/10 backdrop-blur-xl">
        
        <div class="text-center">
            <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-primary to-accent-purple text-white shadow-lg shadow-primary/30">
                <span class="material-symbols-outlined text-2xl">lock</span>
            </div>
            <h2 class="text-3xl font-bold tracking-tight text-white">Welcome Back</h2>
            <p class="mt-2 text-sm text-text-secondary">
                Lanjutkan petualangan membaca Anda di <span class="font-semibold text-primary">Comi</span>
            </p>
        </div>

        <?php if (session()->getFlashdata('error')) : ?>
            <div class="rounded-lg bg-red-500/10 border border-red-500/50 p-3 text-sm text-red-200 text-center">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <form class="mt-8 space-y-6" action="<?= base_url('login/auth') ?>" method="POST">
            <?= csrf_field() ?>
            
            <div class="space-y-4">
                <div>
                    <label for="login_id" class="mb-1 block text-sm font-medium text-gray-300">Email atau Username</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <span class="material-symbols-outlined text-gray-400 text-[20px]">person</span>
                        </div>
                        <input id="login_id" name="login_id" type="text" required class="block w-full rounded-lg border border-white/10 bg-background-dark/50 py-3 pl-10 text-white placeholder-gray-500 focus:border-primary focus:ring-primary sm:text-sm" placeholder="Username atau Email">
                    </div>
                </div>

                <div>
                    <div class="mb-1 flex items-center justify-between">
                        <label for="password" class="block text-sm font-medium text-gray-300">Password</label>
                        <a href="#" class="text-sm font-medium text-primary hover:text-accent-purple transition-colors">Lupa password?</a>
                    </div>
                    <div class="relative rounded-md shadow-sm">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <span class="material-symbols-outlined text-gray-400 text-[20px]">key</span>
                        </div>
                        <input id="password" name="password" type="password" required class="block w-full rounded-lg border border-white/10 bg-background-dark/50 py-3 pl-10 text-white placeholder-gray-500 focus:border-primary focus:ring-primary sm:text-sm" placeholder="Masukkan password Anda">
                    </div>
                </div>
            </div>

            <div>
                <button type="submit" class="group relative flex w-full justify-center rounded-lg bg-primary px-4 py-3 text-sm font-bold text-white shadow-lg shadow-primary/30 transition-all hover:-translate-y-0.5 hover:bg-primary-hover hover:shadow-primary/50 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-background-dark">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <span class="material-symbols-outlined text-blue-300 group-hover:text-blue-100">login</span>
                    </span>
                    Masuk Sekarang
                </button>
            </div>
        </form>

        <p class="text-center text-sm text-gray-400">
            Belum punya akun? 
            <a href="<?= base_url('register') ?>" class="font-semibold text-primary hover:text-accent-purple transition-colors">Daftar Gratis</a>
        </p>
    </div>
</div>

<?= $this->endSection() ?>