<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="mx-auto max-w-[1200px] px-4 py-8 sm:px-6 lg:px-8">
    
    <div class="mb-8 flex items-center gap-3">
        <div class="h-8 w-1.5 rounded-full bg-gradient-to-b from-primary to-accent-purple"></div>
        <h1 class="text-3xl font-bold tracking-tight text-white"><?= esc($page_title ?? 'Katalog Komik') ?></h1>
    </div>

    <?php if(isset($is_genre_page) && $is_genre_page) : ?>
        <div class="mb-8 flex flex-wrap gap-2">
            <a href="<?= base_url('genres') ?>" 
            class="rounded-full px-4 py-1.5 text-sm transition-colors <?= empty($selected_genre) ? 'bg-primary font-semibold text-white shadow-lg shadow-primary/30' : 'bg-white/5 border border-white/10 font-medium text-slate-300 hover:bg-white/10' ?>">
                Semua
            </a>

            <?php if (!empty($genres)) : ?>
                <?php foreach ($genres as $g) : ?>
                    <a href="<?= base_url('genres?g=' . $g->slug) ?>" 
                    class="rounded-full px-4 py-1.5 text-sm transition-colors <?= ($selected_genre == $g->slug) ? 'bg-primary font-semibold text-white shadow-lg shadow-primary/30' : 'bg-white/5 border border-white/10 font-medium text-slate-300 hover:bg-white/10' ?>">
                        <?= esc($g->name) ?>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?php endif; ?>

    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
        <?php if (!empty($komik)) : ?>
            <?php foreach ($komik as $index => $k) : ?>
                <a href="<?= base_url('komik/' . $k->slug) ?>" class="group relative flex flex-col gap-3 rounded-xl bg-background-card p-3 ring-1 ring-white/5 transition-all hover:-translate-y-1 hover:ring-primary/50 hover:shadow-lg hover:shadow-primary/10">
                    <div class="relative aspect-[3/4] w-full overflow-hidden rounded-lg">
                        <img src="<?= base_url('assets/comics/' . $k->cover_image) ?>" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110" alt="<?= esc($k->title) ?>">
                        
                        <?php if(isset($is_popular_page) && $is_popular_page) : ?>
                            <div class="absolute top-0 left-0 flex h-8 w-8 items-center justify-center rounded-br-lg bg-black/80 font-black text-white backdrop-blur-md">
                                <?= $index + 1 ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-background-card/90 via-transparent to-transparent opacity-60"></div>
                    </div>
                    <div class="flex flex-col gap-1">
                        <h3 class="truncate text-base font-bold text-white transition-colors group-hover:text-primary"><?= esc($k->title) ?></h3>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="col-span-full text-center text-slate-400 py-10">Belum ada komik di kategori ini.</p>
        <?php endif; ?>
    </div>

</div>

<?= $this->endSection() ?>