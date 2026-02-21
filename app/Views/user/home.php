<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="mx-auto max-w-[1200px] px-4 py-6 sm:px-6 lg:px-8">
    
    <section class="relative overflow-hidden rounded-2xl bg-background-card ring-1 ring-white/5 shadow-2xl mb-12">
        <div class="absolute inset-0 z-0">
            <div 
                class="h-full w-full bg-cover bg-center" 
                style="background-image: url('<?= base_url('assets/banner.jpg') ?>'); opacity: 0.4;">
            </div>
            <div class="absolute inset-0 bg-gradient-to-t from-background-card via-transparent to-transparent"></div>
        </div>
        
        <div class="relative z-10 px-6 py-12 md:px-12 md:py-20">
            <h1 class="text-4xl font-black text-white sm:text-6xl mb-4">
                Baca Komik <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-cyan-500 font-extrabold">Tanpa Batas</span>
            </h1>
            <p class="text-gray-300 text-lg mb-8 max-w-lg">Temukan petualangan baru setiap hari dengan koleksi terlengkap.</p>
            <button class="bg-primary hover:bg-primary-hover text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-primary/30 transition-all">
                Mulai Membaca
            </button>
        </div>
    </section>

    <section>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-white border-l-4 border-primary pl-4">Update Terbaru</h2>
            <a href="<?= base_url('popular') ?>" class="text-primary hover:text-white text-sm font-semibold">Lihat Semua</a>
        </div>

        <?php if (!empty($komik)) : ?>
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
                <?php foreach($komik as $k) : ?>
                <a href="<?= base_url('komik/' . $k->slug) ?>" class="group relative flex flex-col gap-3 rounded-xl bg-background-card p-3 ring-1 ring-white/5 hover:ring-primary/50 hover:-translate-y-1 transition-all">
                    <div class="relative aspect-[3/4] w-full overflow-hidden rounded-lg">
                        <img src="<?= base_url('assets/comics/' . esc($k->cover_image)) ?>" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110" alt="<?= esc($k->title) ?>">
                        <div class="absolute top-2 left-2 bg-primary text-white text-[10px] font-bold px-2 py-1 rounded uppercase shadow-sm">
                            <?= esc($k->status) ?>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-bold text-white group-hover:text-primary truncate transition-colors"><?= esc($k->title) ?></h3>
                        <div class="flex justify-between text-xs text-text-secondary mt-1">
                            <span>Ch. Terbaru</span>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>

        <?php else : ?>
            <div class="flex flex-col items-center justify-center rounded-2xl bg-background-card/50 py-16 text-center ring-1 ring-white/5">
                <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-white/5 text-slate-500">
                    <span class="material-symbols-outlined text-4xl">auto_stories</span>
                </div>
                <h3 class="mb-1 text-lg font-bold text-white">Belum Ada Komik</h3>
                <p class="text-sm text-text-secondary">Koleksi komik sedang dalam tahap persiapan oleh Admin.</p>
            </div>
        <?php endif; ?>
    </section>

</div>

<?= $this->endSection() ?>