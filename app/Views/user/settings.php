<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

    <div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white">Pengaturan Akun</h1>
    </div>

    <?php if (session()->getFlashdata('pesan')) : ?>
        <div class="mb-6 flex items-center gap-3 rounded-xl bg-green-500/10 border border-green-500/20 p-4 text-sm text-green-400">
            <span class="material-symbols-outlined text-lg">check_circle</span>
            <p class="font-medium"><?= session()->getFlashdata('pesan') ?></p>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="mb-6 flex items-center gap-3 rounded-xl bg-red-500/10 border border-red-500/20 p-4 text-sm text-red-400">
            <span class="material-symbols-outlined text-lg">error</span>
            <p class="font-medium"><?= session()->getFlashdata('error') ?></p>
        </div>
    <?php endif; ?>

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
            
            <form id="deleteForm" action="<?= base_url('user/settings/delete') ?>" method="post">
                <?= csrf_field() ?>
            </form>

            <button type="button" onclick="openDeleteModal()" class="rounded-lg bg-red-500/10 px-4 py-2 text-sm font-bold text-red-500 hover:bg-red-500 hover:text-white transition-all ring-1 ring-red-500/50">
                Hapus Akun Saya
            </button>
        </div>
    </div>
</div>

<div id="deleteModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 opacity-0 invisible transition-all duration-300">
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
    
    <div id="modalBox" class="relative w-full max-w-md scale-90 opacity-0 transition-all duration-300 rounded-2xl bg-background-card p-6 shadow-2xl ring-1 ring-white/10 text-center">
        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-red-500/10 text-red-500">
            <span class="material-symbols-outlined text-4xl">warning</span>
        </div>
        
        <h3 class="text-xl font-bold text-white mb-2">Hapus Akun Permanen?</h3>
        <p class="text-sm text-slate-400 mb-6">Tindakan ini tidak dapat dibatalkan. Seluruh koleksi komik dan riwayat bacaan Anda akan dihapus selamanya.</p>
        
        <div class="flex flex-col sm:flex-row gap-3">
            <button onclick="closeDeleteModal()" class="flex-1 rounded-xl bg-white/5 py-3 text-sm font-bold text-slate-300 hover:bg-white/10 transition-colors">
                Batal
            </button>
            <button onclick="confirmDelete()" class="flex-1 rounded-xl bg-red-600 py-3 text-sm font-bold text-white hover:bg-red-700 shadow-lg shadow-red-600/20 transition-all">
                Ya, Hapus Akun
            </button>
        </div>
    </div>
</div>

<script>
    const modal = document.getElementById('deleteModal');
    const modalBox = document.getElementById('modalBox');
    const deleteForm = document.getElementById('deleteForm');

    function openDeleteModal() {
        // Hilangkan class invisible
        modal.classList.remove('invisible', 'opacity-0');
        modal.classList.add('opacity-100');
        
        // Animasi zoom-in pada box
        modalBox.classList.remove('scale-90', 'opacity-0');
        modalBox.classList.add('scale-100', 'opacity-100');
    }

    function closeDeleteModal() {
        // Balikkan animasi zoom-out
        modalBox.classList.remove('scale-100', 'opacity-100');
        modalBox.classList.add('scale-90', 'opacity-0');
        
        // Sembunyikan modal setelah animasi selesai (300ms)
        setTimeout(() => {
            modal.classList.add('invisible', 'opacity-0');
            modal.classList.remove('opacity-100');
        }, 300);
    }

    function confirmDelete() {
        // Kirimkan form yang sudah kita siapkan
        deleteForm.submit();
    }
</script>

<?= $this->endSection() ?>