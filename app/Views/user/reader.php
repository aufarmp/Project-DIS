<?= $this->extend('layout/reader_template') ?>

<?= $this->section('content') ?>

<div class="bg-[#0b1121] min-h-screen pt-6 pb-12">
    
    <div class="max-w-3xl mx-auto px-4 mb-6 flex items-center justify-between">
        <a href="<?= base_url('komik/' . $komik->slug) ?>" class="flex items-center gap-2 text-slate-400 hover:text-white transition-colors bg-white/5 px-3 py-1.5 rounded-lg border border-white/10">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span>
            <span class="font-medium text-sm hidden sm:block">Detail</span>
        </a>
        <div class="text-center flex-1 px-4">
            <h1 class="text-white font-bold text-lg truncate"><?= esc($komik->title) ?></h1>
            <p class="text-blue-400 font-semibold text-sm">Chapter <?= esc($chapter->chapter_number) ?></p>
        </div>
        <div>
            <div class="w-20"></div> 
        </div>
    </div>

    <div class="max-w-2xl mx-auto bg-black flex flex-col items-center shadow-2xl rounded-sm overflow-hidden min-h-[50vh]">
        <?php if (!empty($pages)): ?>
            <?php foreach ($pages as $p): ?>
                
                <img src="<?= base_url('assets/comics/' . $p->image_url) ?>" 
                    class="w-full h-auto object-contain block m-0 p-0 select-none" 
                    loading="lazy" 
                    alt="Page">
                     
            <?php endforeach; ?>
        <?php else: ?>
            <div class="flex flex-col items-center justify-center py-32 text-slate-500 w-full gap-4 bg-[#0f172a]">
                <span class="material-symbols-outlined text-4xl opacity-50">broken_image</span>
                <p>Halaman komik belum diunggah untuk chapter ini.</p>
            </div>
        <?php endif; ?>
    </div>

    <div class="max-w-2xl mx-auto mt-8 flex items-center justify-between gap-3 px-2">
        
        <?php if ($prevChapter): ?>
            <a href="<?= base_url('baca/' . $komik->slug . '/' . $prevChapter->slug) ?>" class="flex-1 py-3 bg-white/5 hover:bg-white/10 text-white rounded-xl flex items-center justify-center gap-2 transition-colors border border-white/10 text-sm font-semibold group">
                <span class="material-symbols-outlined text-[20px] group-hover:-translate-x-1 transition-transform">chevron_left</span> 
                <span class="hidden sm:inline">Chapter</span> <?= $prevChapter->chapter_number ?>
            </a>
        <?php else: ?>
            <div class="flex-1 py-3 bg-white/5 text-slate-600 rounded-xl flex items-center justify-center gap-2 border border-white/5 cursor-not-allowed text-sm">
                <span class="material-symbols-outlined text-[20px]">chevron_left</span> Mentok
            </div>
        <?php endif; ?>

        <a href="<?= base_url('komik/' . $komik->slug) ?>" class="p-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition-colors shadow-lg shadow-blue-600/30 flex items-center justify-center" title="Daftar Chapter">
            <span class="material-symbols-outlined">format_list_bulleted</span>
        </a>

        <?php if ($nextChapter): ?>
            <a href="<?= base_url('baca/' . $komik->slug . '/' . $nextChapter->slug) ?>" class="flex-1 py-3 bg-blue-600/10 hover:bg-blue-600/20 text-blue-400 rounded-xl flex items-center justify-center gap-2 transition-colors border border-blue-500/20 text-sm font-semibold group">
                <span class="hidden sm:inline">Chapter</span> <?= $nextChapter->chapter_number ?> 
                <span class="material-symbols-outlined text-[20px] group-hover:translate-x-1 transition-transform">chevron_right</span>
            </a>
        <?php else: ?>
            <div class="flex-1 py-3 bg-white/5 text-slate-600 rounded-xl flex items-center justify-center gap-2 border border-white/5 cursor-not-allowed text-sm">
                Mentok <span class="material-symbols-outlined text-[20px]">chevron_right</span>
            </div>
        <?php endif; ?>

    </div>
</div>

</div> <button id="btnScrollTop" onclick="scrollToTop()" class="fixed bottom-6 right-6 sm:bottom-10 sm:right-10 p-3 bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-lg shadow-blue-600/40 transition-all duration-300 opacity-0 invisible translate-y-4 z-50 flex items-center justify-center group" title="Kembali ke Atas">
    <span class="material-symbols-outlined text-[24px] group-hover:-translate-y-1 transition-transform">arrow_upward</span>
</button>

<script>
    const btnScrollTop = document.getElementById('btnScrollTop');

    // Memantau setiap kali user melakukan scroll
    window.addEventListener('scroll', () => {
        // Jika layar sudah di-scroll ke bawah lebih dari 500px, munculkan tombol
        if (window.scrollY > 500) {
            btnScrollTop.classList.remove('opacity-0', 'invisible', 'translate-y-4');
            btnScrollTop.classList.add('opacity-100', 'visible', 'translate-y-0');
        } else {
            // Sembunyikan kembali jika user sudah kembali ke paling atas
            btnScrollTop.classList.add('opacity-0', 'invisible', 'translate-y-4');
            btnScrollTop.classList.remove('opacity-100', 'visible', 'translate-y-0');
        }
    });

    // Fungsi untuk menggulir ke atas dengan mulus (smooth)
    function scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }
</script>

<?= $this->endSection() ?>