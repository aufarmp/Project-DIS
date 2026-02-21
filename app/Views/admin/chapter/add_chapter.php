<?= $this->extend('layout/admin_template') ?>

<?= $this->section('content') ?>

<div class="flex h-full flex-col px-6 py-6 overflow-y-auto custom-scrollbar z-10">
    
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="mb-1 flex items-center gap-2 text-sm text-slate-400">
                <a href="<?= base_url('admin/dashboard') ?>" class="hover:text-white transition-colors">Admin</a>
                <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                <a href="<?= base_url('admin/chapter') ?>" class="hover:text-white transition-colors">Kelola Chapter</a>
                <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                <span class="font-medium text-white">Tambah Chapter</span>
            </div>
            <h2 class="text-2xl font-bold text-white">Upload Chapter Baru</h2>
            <p class="text-primary mt-1 text-sm font-medium">Komik: <?= esc($komik->title) ?></p>
        </div>
        
        <a href="<?= base_url('admin/chapter/list/' . $komik->komik_id) ?>" class="inline-flex items-center justify-center gap-2 rounded-lg bg-surface-dark border border-white/10 px-4 py-2.5 text-sm font-bold text-white transition-all hover:bg-white/5">
            <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            Kembali
        </a>
    </div>

    <?php if (session()->has('errors')) : ?>
        <div class="mb-6 rounded-lg bg-red-500/10 border border-red-500/20 p-4">
            <div class="flex items-center gap-2 text-red-400 font-bold mb-2">
                <span class="material-symbols-outlined">error</span>
                Terdapat Kesalahan Input:
            </div>
            <ul class="list-disc list-inside text-sm text-red-300">
                <?php foreach (session('errors') as $error) : ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach ?>
            </ul>
        </div>
    <?php endif ?>

    <div class="rounded-xl border border-white/10 bg-surface-dark/50 shadow-xl backdrop-blur-sm p-6 sm:p-8 max-w-4xl mx-auto w-full">
        <form action="<?= base_url('admin/chapter/save-chapter/' . $komik->komik_id) ?>" method="post" enctype="multipart/form-data" class="space-y-6">
            <?= csrf_field() ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="chapter_number" class="block text-sm font-medium text-slate-300 mb-2">Nomor Chapter <span class="text-red-400">*</span></label>
                    <input type="number" step="0.1" id="chapter_number" name="chapter_number" value="<?= old('chapter_number') ?>" required autofocus
                           class="w-full rounded-lg border border-white/10 bg-background-dark py-2.5 px-4 text-white focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary placeholder-slate-500" 
                           placeholder="Contoh: 1 atau 1.5">
                </div>
                
                <div>
                    <label for="title" class="block text-sm font-medium text-slate-300 mb-2">Judul Chapter <span class="text-xs text-slate-500 font-normal">(Opsional)</span></label>
                    <input type="text" id="title" name="title" value="<?= old('title') ?>"
                           class="w-full rounded-lg border border-white/10 bg-background-dark py-2.5 px-4 text-white focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary placeholder-slate-500" 
                           placeholder="Contoh: Pertemuan Pertama">
                </div>
            </div>

            <div class="rounded-lg border border-white/10 bg-background-dark p-6 mt-4">
                <div class="mb-4">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">collections</span>
                        Upload Gambar Panel (Pages) <span class="text-red-400">*</span>
                    </h3>
                    <p class="text-sm text-slate-400 mt-1">Anda bisa memilih banyak gambar sekaligus. Gambar akan diurutkan secara otomatis berdasarkan nama file yang Anda pilih.</p>
                </div>
                
                <div class="relative mt-2">
                    <input type="file" id="pages" name="pages[]" multiple accept="image/png, image/jpeg, image/webp" required
                           class="block w-full text-sm text-slate-400 file:mr-4 file:py-3 file:px-6 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-primary/20 file:text-primary hover:file:bg-primary/30 transition-all cursor-pointer border border-white/10 bg-surface-dark rounded-lg p-2">
                </div>
                
                <div class="mt-4 p-4 rounded bg-blue-500/10 border border-blue-500/20 text-sm text-blue-300 flex gap-3">
                    <span class="material-symbols-outlined text-blue-400 text-[20px]">info</span>
                    <div>
                        <strong class="text-blue-400">Tips Penamaan File:</strong> 
                        Pastikan nama gambar di komputermu sudah urut sebelum dipilih (contoh: <code class="bg-black/30 px-1 rounded text-white">01.jpg, 02.jpg, 03.jpg</code>) agar terbaca berurutan oleh sistem. Sistem akan otomatis mengganti namanya menyesuaikan standar server.
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-white/10 flex justify-end">
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary px-8 py-3 text-sm font-bold text-white shadow-lg shadow-primary/30 transition-all hover:-translate-y-0.5 hover:bg-primary-dark hover:shadow-primary/50">
                    <span class="material-symbols-outlined text-[20px]">cloud_upload</span>
                    Upload & Simpan Chapter
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>