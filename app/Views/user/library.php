<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="mx-auto max-w-[1200px] px-4 py-8 sm:px-6 lg:px-8">
    
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white">Library Saya</h1>
    </div>

    <div class="mb-6 flex gap-2 border-b border-white/10 pb-px">
        <a href="<?= base_url('user/bookmarks') ?>" class="border-b-2 px-4 py-2 text-sm font-medium transition-colors <?= (isset($active_tab) && $active_tab == 'bookmarks') ? 'border-primary text-primary' : 'border-transparent text-slate-400 hover:text-white' ?>">
            Komik Tersimpan
        </a>
        <a href="<?= base_url('user/history') ?>" class="border-b-2 px-4 py-2 text-sm font-medium transition-colors <?= (isset($active_tab) && $active_tab == 'history') ? 'border-primary text-primary' : 'border-transparent text-slate-400 hover:text-white' ?>">
            Riwayat Bacaan
        </a>
    </div>

    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6">
        <?php if (!empty($komik)) : ?>
            <?php foreach ($komik as $k) : ?>
                <a href="<?= base_url('komik/' . $k['slug']) ?>" class="group relative flex flex-col gap-3 rounded-xl bg-background-card p-3 ring-1 ring-white/5 transition-all hover:-translate-y-1 hover:ring-primary/50 hover:shadow-lg hover:shadow-primary/10">
                    <div class="relative aspect-[3/4] w-full overflow-hidden rounded-lg">
                        <img src="<?= base_url('assets/covers/' . $k['cover_image']) ?>" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-background-card/90 via-transparent to-transparent opacity-60"></div>
                    </div>
                    <div class="flex flex-col gap-1">
                        <h3 class="truncate text-base font-bold text-white group-hover:text-primary"><?= esc($k['title']) ?></h3>
                        
                        <?php if($active_tab == 'history') : ?>
                            <span class="text-xs font-medium text-primary">Terakhir: Ch. <?= esc($k['last_chapter_read'] ?? '1') ?></span>
                        <?php else : ?>
                            <span class="text-xs font-medium text-text-secondary">Tersimpan</span>
                        <?php endif; ?>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="col-span-full py-16 text-center">
                <span class="material-symbols-outlined text-5xl text-slate-500 mb-3 opacity-50">
                    <?= $active_tab == 'history' ? 'history_toggle_off' : 'bookmark_remove' ?>
                </span>
                <p class="text-slate-400">
                    <?= $active_tab == 'history' ? 'Anda belum membaca komik apapun.' : 'Belum ada komik yang Anda simpan.' ?>
                </p>
                <a href="<?= base_url('katalog') ?>" class="mt-4 inline-block text-sm text-primary hover:underline">Cari komik sekarang</a>
            </div>
        <?php endif; ?>
    </div>

</div>

<?= $this->endSection() ?>