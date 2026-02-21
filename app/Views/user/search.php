<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="mx-auto max-w-[1200px] px-4 py-8 sm:px-6 lg:px-8">
    
    <div class="mb-8 border-b border-white/5 pb-4">
        <h1 class="text-2xl font-bold text-white">
            Hasil Pencarian untuk: <span class="text-primary">"<?= esc($keyword ?? '') ?>"</span>
        </h1>
        <p class="mt-1 text-sm text-text-secondary">
            Ditemukan <?= count($komik ?? []) ?> hasil.
        </p>
    </div>

    <?php if (empty($komik)) : ?>
        
        <div class="flex flex-col items-center justify-center rounded-2xl bg-background-card/50 py-20 text-center ring-1 ring-white/5">
            <div class="mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-white/5 text-slate-500">
                <span class="material-symbols-outlined text-5xl">search_off</span>
            </div>
            <h2 class="mb-2 text-xl font-bold text-white">Oops! Komik tidak ditemukan</h2>
            <p class="mb-6 max-w-md text-sm text-text-secondary">
                Kami tidak dapat menemukan judul yang cocok dengan kata kunci tersebut. Coba gunakan kata kunci lain atau periksa ejaan Anda.
            </p>
            <a href="<?= base_url('/') ?>" class="inline-flex items-center gap-2 rounded-full bg-primary/10 px-6 py-2.5 text-sm font-bold text-primary transition-all hover:bg-primary hover:text-white">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                Kembali ke Beranda
            </a>
        </div>

    <?php else : ?>

        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
            <?php foreach ($komik as $k) : ?>
                <a href="<?= base_url('komik/' . $k['slug']) ?>" class="group relative flex flex-col gap-3 rounded-xl bg-background-card p-3 ring-1 ring-white/5 transition-all hover:-translate-y-1 hover:ring-primary/50 hover:shadow-lg hover:shadow-primary/10">
                    <div class="relative aspect-[3/4] w-full overflow-hidden rounded-lg">
                        <img src="<?= base_url('assets/covers/' . $k['cover_image']) ?>" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110" alt="<?= esc($k['title']) ?>">
                        <div class="absolute top-2 left-2 z-10 rounded bg-primary px-2 py-0.5 text-[10px] font-bold uppercase text-white shadow-sm">
                            <?= esc($k['status']) ?>
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-background-card/90 via-transparent to-transparent opacity-60"></div>
                    </div>
                    <div class="flex flex-col gap-1">
                        <h3 class="truncate text-base font-bold text-white transition-colors group-hover:text-primary"><?= esc($k['title']) ?></h3>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-medium text-text-secondary"><?= esc($k['genre_name'] ?? 'General') ?></span>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>

</div>

<?= $this->endSection() ?>