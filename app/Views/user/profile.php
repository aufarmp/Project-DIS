<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white">Profil Saya</h1>
        <p class="text-sm text-text-secondary mt-1">Kelola informasi data diri dan akun Anda.</p>
    </div>

    <div class="rounded-2xl bg-background-card p-6 sm:p-8 ring-1 ring-white/5 shadow-xl">
        <form action="<?= base_url('user/profile/update') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div class="mb-8 flex items-center gap-6">
                <div class="relative h-24 w-24 rounded-full bg-gradient-to-br from-primary to-accent-purple flex items-center justify-center text-3xl font-bold text-white shadow-lg overflow-hidden">
                    <?php if(session()->get('profile_picture')) : ?>
                        <img src="<?= base_url('assets/profiles/' . session()->get('profile_picture')) ?>" class="h-full w-full object-cover">
                    <?php else: ?>
                        <?= strtoupper(substr(session()->get('username'), 0, 1)) ?>
                    <?php endif; ?>
                </div>
                <div>
                    <label class="block text-sm font-medium text-white mb-2">Ubah Foto Profil</label>
                    <input type="file" name="profile_picture" class="block w-full text-sm text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/20 file:text-primary hover:file:bg-primary/30 transition-all cursor-pointer">
                    <p class="mt-1 text-xs text-slate-500">PNG, JPG maksimal 2MB.</p>
                </div>
            </div>

            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1">Username</label>
                    <input type="text" name="username" value="<?= esc(session()->get('username')) ?>" class="w-full rounded-lg border border-white/10 bg-background-dark py-2.5 px-4 text-white focus:border-primary focus:ring-1 focus:ring-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1">Email</label>
                    <input type="email" name="email" value="<?= esc(session()->get('email') ?? 'user@example.com') ?>" class="w-full rounded-lg border border-white/10 bg-background-dark/50 py-2.5 px-4 text-slate-400 cursor-not-allowed" readonly>
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit" class="rounded-lg bg-primary px-6 py-2.5 text-sm font-bold text-white hover:bg-primary-hover shadow-lg shadow-primary/30 transition-all hover:-translate-y-0.5">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>