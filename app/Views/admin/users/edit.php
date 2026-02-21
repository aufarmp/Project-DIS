<?= $this->extend('layout/admin_template') ?>

<?= $this->section('content') ?>

<div class="flex h-full flex-col px-6 py-8 z-10 overflow-y-auto">
    <div class="w-full max-w-3xl mx-auto flex flex-col gap-6">
        
        <div>
            <div class="mb-1 flex items-center gap-2 text-sm text-slate-400">
                <a href="<?= base_url('admin/dashboard') ?>" class="hover:text-primary transition-colors">Admin</a>
                <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                <a href="<?= base_url('admin/users') ?>" class="hover:text-primary transition-colors">Kelola Pengguna</a>
                <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                <span class="font-medium text-white">Edit Pengguna</span>
            </div>
            <h2 class="text-2xl font-bold text-white">Edit Data Pengguna</h2>
        </div>

        <div class="rounded-xl border border-white/10 bg-surface-dark/50 shadow-xl backdrop-blur-sm p-6 sm:p-8">
            
            <?php if (session()->has('errors')) : ?>
                <div class="mb-6 rounded-lg border border-red-500/20 bg-red-500/10 p-4">
                    <div class="flex items-center gap-2 text-red-400 mb-2 font-semibold">
                        <span class="material-symbols-outlined text-[20px]">error</span>
                        Terjadi Kesalahan:
                    </div>
                    <ul class="list-disc list-inside text-sm text-red-300">
                        <?php foreach (session('errors') as $error) : ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('admin/users/update/' . $user['user_id']) ?>" method="post" class="flex flex-col gap-6">
                <?= csrf_field() ?>
                
                <div>
                    <label for="username" class="mb-2 block text-sm font-medium text-slate-300">Username</label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <span class="material-symbols-outlined text-[20px]">person</span>
                        </div>
                        <input type="text" id="username" name="username" value="<?= old('username', $user['username']) ?>" required
                            class="block w-full rounded-lg border border-white/10 bg-surface-dark py-3 pl-11 pr-4 text-sm text-white placeholder-slate-500 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary transition-colors">
                    </div>
                </div>

                <div>
                    <label for="email" class="mb-2 block text-sm font-medium text-slate-300">Alamat Email</label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <span class="material-symbols-outlined text-[20px]">mail</span>
                        </div>
                        <input type="email" id="email" name="email" value="<?= old('email', $user['email']) ?>" required
                            class="block w-full rounded-lg border border-white/10 bg-surface-dark py-3 pl-11 pr-4 text-sm text-white placeholder-slate-500 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary transition-colors">
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-300">Role Akun</label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <span class="material-symbols-outlined text-[20px]">shield</span>
                        </div>
                        <input type="text" value="<?= esc(ucfirst($user['role'])) ?>" disabled
                            class="block w-full rounded-lg border border-white/5 bg-white/5 py-3 pl-11 pr-4 text-sm text-slate-400 cursor-not-allowed">
                    </div>
                    <p class="mt-2 text-xs text-slate-500 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">info</span>
                        Role pengguna tidak dapat diubah untuk alasan keamanan.
                    </p>
                </div>

                <div class="mt-4 flex items-center justify-end gap-3 border-t border-white/10 pt-6">
                    <a href="<?= base_url('admin/users') ?>" class="inline-flex items-center justify-center gap-2 rounded-lg border border-white/10 bg-transparent px-5 py-2.5 text-sm font-medium text-slate-300 transition-colors hover:bg-white/5 hover:text-white">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-primary/30 transition-all hover:-translate-y-0.5 hover:bg-primary-dark hover:shadow-primary/50">
                        <span class="material-symbols-outlined text-[20px]">save</span>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
        
    </div>
</div>

<?= $this->endSection() ?>