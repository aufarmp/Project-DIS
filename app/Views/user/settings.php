<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white">Pengaturan Akun</h1>
    </div>

    <div class="space-y-6">
        
        <div class="rounded-2xl bg-background-card p-6 sm:p-8 ring-1 ring-white/5">
            <h2 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">lock</span> Keamanan Akun
            </h2>
            <form action="<?= base_url('user/settings/password') ?>" method="post" class="space-y-4">
                <?= csrf_field() ?>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1">Password Saat Ini</label>
                    <input type="password" name="old_password" class="w-full rounded-lg border border-white/10 bg-background-dark py-2.5 px-4 text-white focus:border-primary focus:ring-1 focus:ring-primary">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1">Password Baru</label>
                        <input type="password" name="new_password" class="w-full rounded-lg border border-white/10 bg-background-dark py-2.5 px-4 text-white focus:border-primary focus:ring-1 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1">Konfirmasi Password</label>
                        <input type="password" name="confirm_password" class="w-full rounded-lg border border-white/10 bg-background-dark py-2.5 px-4 text-white focus:border-primary focus:ring-1 focus:ring-primary">
                    </div>
                </div>
                <div class="pt-2 text-right">
                    <button type="submit" class="rounded-lg bg-white/10 px-4 py-2 text-sm font-bold text-white hover:bg-white/20 transition-all">Update Password</button>
                </div>
            </form>
        </div>

        <div class="rounded-2xl bg-red-950/20 p-6 sm:p-8 ring-1 ring-red-500/20">
            <h2 class="text-lg font-bold text-red-400 mb-2">Zona Berbahaya</h2>
            <p class="text-sm text-slate-400 mb-4">Setelah Anda menghapus akun, semua data riwayat bacaan dan bookmark akan hilang permanen.</p>
            <button class="rounded-lg bg-red-500/10 px-4 py-2 text-sm font-bold text-red-500 hover:bg-red-500 hover:text-white transition-all ring-1 ring-red-500/50">
                Hapus Akun Saya
            </button>
        </div>

    </div>
</div>

<?= $this->endSection() ?>