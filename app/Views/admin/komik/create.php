<?= $this->extend('layout/admin_template') ?>

<?= $this->section('content') ?>

<div class="flex h-full flex-col px-6 py-6 overflow-y-auto custom-scrollbar z-10">
    
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="mb-1 flex items-center gap-2 text-sm text-slate-400">
                <a href="<?= base_url('admin/dashboard') ?>" class="hover:text-white transition-colors">Admin</a>
                <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                <a href="<?= base_url('admin/komik') ?>" class="hover:text-white transition-colors">Kelola Komik</a>
                <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                <span class="font-medium text-white">Tambah Komik</span>
            </div>
            <h2 class="text-2xl font-bold text-white">Tambah Komik Baru</h2>
        </div>
        
        <a href="<?= base_url('admin/komik') ?>" class="inline-flex items-center justify-center gap-2 rounded-lg bg-surface-dark border border-white/10 px-4 py-2.5 text-sm font-bold text-white transition-all hover:bg-white/5">
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

    <div class="rounded-xl border border-white/10 bg-surface-dark/50 shadow-xl backdrop-blur-sm p-6 sm:p-8">
        <form action="<?= base_url('admin/komik/save') ?>" method="post" enctype="multipart/form-data" class="space-y-6">
            <?= csrf_field() ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-slate-300 mb-2">Judul Komik <span class="text-red-400">*</span></label>
                    <input type="text" id="title" name="title" value="<?= old('title') ?>" required autofocus
                           class="w-full rounded-lg border border-white/10 bg-background-dark py-2.5 px-4 text-white focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary placeholder-slate-500" 
                           placeholder="Contoh: Solo Leveling">
                </div>
                
                <div>
                    <label for="status" class="block text-sm font-medium text-slate-300 mb-2">Status Rilis <span class="text-red-400">*</span></label>
                    <select id="status" name="status" required
                            class="w-full rounded-lg border border-white/10 bg-background-dark py-2.5 px-4 text-white focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                        <option value="ongoing" <?= old('status') == 'ongoing' ? 'selected' : '' ?>>Ongoing (Sedang Berjalan)</option>
                        <option value="completed" <?= old('status') == 'completed' ? 'selected' : '' ?>>Completed (Tamat)</option>
                        <option value="hiatus" <?= old('status') == 'hiatus' ? 'selected' : '' ?>>Hiatus (Ditunda)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="author_story" class="block text-sm font-medium text-slate-300 mb-2">Penulis Cerita (Story) <span class="text-red-400">*</span></label>
                    <select id="author_story" name="author_story" required
                            class="w-full rounded-lg border border-white/10 bg-background-dark py-2.5 px-4 text-white focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                        <option value="" disabled <?= old('author_story') ? '' : 'selected' ?>>-- Pilih Penulis --</option>
                        <?php foreach($authors as $a) : ?>
                            <option value="<?= $a['author_id'] ?>" <?= old('author_story') == $a['author_id'] ? 'selected' : '' ?>>
                                <?= esc($a['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="author_art" class="block text-sm font-medium text-slate-300 mb-2">Ilustrator (Art) <span class="text-xs text-slate-500 font-normal">(Opsional)</span></label>
                    <select id="author_art" name="author_art"
                            class="w-full rounded-lg border border-white/10 bg-background-dark py-2.5 px-4 text-white focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                        <option value="" <?= old('author_art') ? '' : 'selected' ?>>-- Sama dengan Penulis (Atau Pilih) --</option>
                        <?php foreach($authors as $a) : ?>
                            <option value="<?= $a['author_id'] ?>" <?= old('author_art') == $a['author_id'] ? 'selected' : '' ?>>
                                <?= esc($a['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div>
                <label for="cover_image" class="block text-sm font-medium text-slate-300 mb-2">Cover Image <span class="text-red-400">*</span></label>
                <input type="file" id="cover_image" name="cover_image" accept="image/png, image/jpeg, image/jpg" required
                       class="block w-full text-sm text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary/20 file:text-primary hover:file:bg-primary/30 transition-all cursor-pointer border border-white/10 bg-background-dark rounded-lg">
                <p class="mt-1 text-xs text-slate-500">Format: JPG/PNG. Maksimal 2MB. Rasio portrait (3:4) disarankan.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Pilih Genre (Bisa lebih dari satu) <span class="text-red-400">*</span></label>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 rounded-lg border border-white/10 bg-background-dark p-4">
                    <?php foreach($genres as $g) : ?>
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" name="genres[]" value="<?= $g['genre_id'] ?>" 
                                   class="h-4 w-4 rounded border-white/20 bg-surface-dark text-primary focus:ring-primary focus:ring-offset-background-dark cursor-pointer">
                            <span class="text-sm text-slate-300 group-hover:text-white transition-colors"><?= esc($g['name']) ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-slate-300 mb-2">Sinopsis / Deskripsi</label>
                <textarea id="description" name="description" rows="5"
                          class="w-full rounded-lg border border-white/10 bg-background-dark py-3 px-4 text-white focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary placeholder-slate-500" 
                          placeholder="Ceritakan ringkasan komik ini..."><?= old('description') ?></textarea>
            </div>

            <div class="pt-4 border-t border-white/10 flex justify-end">
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary px-8 py-3 text-sm font-bold text-white shadow-lg shadow-primary/30 transition-all hover:-translate-y-0.5 hover:bg-primary-dark hover:shadow-primary/50">
                    <span class="material-symbols-outlined text-[20px]">save</span>
                    Simpan Komik
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>