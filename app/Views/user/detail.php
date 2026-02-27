<?= $this->extend('layout/template') ?> 

<?= $this->section('content') ?>

<div class="min-h-screen bg-[#0f172a] text-white pb-12">
    
    <div class="relative w-full h-64 md:h-80 overflow-hidden">
        <div class="absolute inset-0 bg-cover bg-center opacity-30 blur-md scale-110" style="background-image: url('<?= base_url('assets/comics/' . esc($komik->cover_image)) ?>');"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-[#0f172a] via-[#0f172a]/80 to-transparent"></div>
    </div>

    <?php if (session()->getFlashdata('pesan')) : ?>
        <div class="fixed top-24 left-1/2 -translate-x-1/2 z-[100] w-full max-w-md px-4 animate-bounce">
            <div class="rounded-lg bg-green-500/90 backdrop-blur-sm border border-green-400 p-3 text-sm text-white text-center shadow-2xl font-medium">
                <?= session()->getFlashdata('pesan') ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 -mt-32 relative z-10">
        <div class="flex flex-col md:flex-row gap-8">
            
            <div class="w-full md:w-1/4 flex flex-col gap-4">
                <img src="<?= base_url('assets/comics/' . esc($komik->cover_image)) ?>" alt="<?= esc($komik->title) ?>" class="w-full rounded-xl shadow-2xl ring-1 ring-white/10 aspect-[3/4] object-cover">
                
                <?php if ($isLoggedIn): ?>
                    <a href="<?= base_url('baca/' . $komik->slug . '/' . (!empty($chapters) ? end($chapters)->slug : '')) ?>" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg text-center transition-colors shadow-lg shadow-blue-600/30">
                        Mulai Membaca
                    </a>
                    
                    <form action="<?= base_url('user/bookmark/toggle/' . $komik->komik_id) ?>" method="POST" class="w-full">
                        <?= csrf_field() ?>
                        
                        <?php if(isset($isBookmarked) && $isBookmarked): ?>
                            <button type="submit" class="w-full py-3 bg-red-500/10 border border-red-500/30 hover:bg-red-500/20 text-red-400 font-bold rounded-lg transition-colors flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-[20px]">bookmark_remove</span> Hapus Bookmark
                            </button>
                        <?php else: ?>
                            <button type="submit" class="w-full py-3 bg-[#1e293b] border border-white/10 hover:bg-white/5 text-white font-bold rounded-lg transition-colors flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-[20px]">bookmark_add</span> Bookmark
                            </button>
                        <?php endif; ?>
                    </form>

                <?php else: ?>
                    <button onclick="openLoginModal('membaca komik')" class="w-full py-3 bg-blue-600/80 hover:bg-blue-600 text-white font-bold rounded-lg text-center transition-colors shadow-lg shadow-blue-600/30">
                        Mulai Membaca
                    </button>
                    <button onclick="openLoginModal('menyimpan ke bookmark')" class="w-full py-3 bg-[#1e293b] border border-white/10 hover:bg-white/5 text-slate-300 font-bold rounded-lg transition-colors flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">lock</span> Bookmark
                    </button>
                <?php endif; ?>
            </div>

            <div class="w-full md:w-3/4 flex flex-col">
                <h1 class="text-3xl md:text-5xl font-extrabold text-white mb-2"><?= esc($komik->title) ?></h1>
                
                <div class="flex flex-wrap items-center gap-4 mb-6 text-sm">
                    <span class="px-3 py-1 bg-white/10 rounded-full font-medium text-blue-400 border border-blue-400/20">
                        <?= esc(ucfirst($komik->status)) ?>
                    </span>
                    
                    <?php if (!empty($authors)): ?>
                        <div class="flex items-center gap-2 text-slate-300">
                            <span class="material-symbols-outlined text-[18px]">person</span>
                            <?php 
                                $authorNames = array_map(function($a) { return $a->name; }, $authors);
                                echo esc(implode(', ', $authorNames)); 
                            ?>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if (!empty($genres)): ?>
                    <div class="flex flex-wrap gap-2 mb-8">
                        <?php foreach ($genres as $g): ?>
                            <span class="px-3 py-1 bg-white/5 border border-white/10 rounded-md text-xs text-slate-300">
                                <?= esc($g->name) ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <h3 class="text-lg font-bold text-white mb-2 border-b border-white/10 pb-2">Sinopsis</h3>
                <p class="text-justify text-slate-400 leading-relaxed mb-8">
                    <?= !empty($komik->description) ? nl2br(esc($komik->description)) : 'Belum ada deskripsi untuk komik ini.' ?>
                </p>

                <h3 class="text-lg font-bold text-white mb-4 border-b border-white/10 pb-2 mt-2">Daftar Chapter</h3>
                <div class="flex flex-col gap-2 max-h-96 overflow-y-auto custom-scrollbar pr-2">
                    <?php if (!empty($chapters)): ?>
                        <?php foreach ($chapters as $c): ?>
                            
                            <?php if ($isLoggedIn): ?>
                                <a href="<?= base_url('baca/' . $komik->slug . '/' . $c->slug) ?>" class="flex items-center justify-between p-4 bg-white/5 hover:bg-white/10 border border-white/5 rounded-lg transition-colors group">
                            <?php else: ?>
                                <button onclick="openLoginModal('membaca chapter ini')" class="flex items-center justify-between p-4 bg-white/5 hover:bg-white/10 border border-white/5 rounded-lg transition-colors group text-left w-full">
                            <?php endif; ?>
                            
                                <div>
                                    <div class="font-bold text-white group-hover:text-blue-400 transition-colors">Chapter <?= esc($c->chapter_number) ?></div>
                                    <div class="text-xs text-slate-500 mt-1"><?= !empty($c->title) ? esc($c->title) : '' ?></div>
                                </div>
                                <div class="text-xs text-slate-500">
                                    <?= date('d M Y', strtotime($c->created_at)) ?>
                                </div>
                                
                            <?= $isLoggedIn ? '</a>' : '</button>' ?>
                            
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="p-6 text-center text-slate-500 bg-white/5 rounded-lg border border-white/10">
                            Belum ada chapter yang dirilis.
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
</div>

<div id="loginModal" class="fixed inset-0 z-[100] hidden bg-[#0f172a]/80 backdrop-blur-sm flex items-center justify-center transition-opacity duration-300 opacity-0">
    <div class="bg-[#1e293b] border border-white/10 rounded-2xl shadow-2xl w-full max-w-md p-6 transform scale-95 transition-transform duration-300" id="loginModalContent">
        
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-full bg-blue-500/20 flex items-center justify-center text-blue-400 flex-shrink-0">
                <span class="material-symbols-outlined text-[24px]">lock</span>
            </div>
            <div>
                <h3 class="text-xl font-bold text-white">Akses Dibatasi</h3>
                <p class="text-sm text-slate-400 mt-1">Anda belum masuk ke akun.</p>
            </div>
        </div>
        
        <p class="text-slate-300 mb-8 text-sm leading-relaxed">
            Anda harus login terlebih dahulu untuk <strong id="modalActionText" class="text-white font-bold"></strong>. Apakah Anda ingin menuju halaman login sekarang?
        </p>
        
        <div class="flex gap-3 justify-end">
            <button type="button" onclick="closeLoginModal()" class="px-5 py-2.5 rounded-lg border border-white/10 text-slate-300 hover:bg-white/5 hover:text-white transition-colors text-sm font-medium">
                Batal
            </button>
            <a href="<?= base_url('login') ?>" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold shadow-lg shadow-blue-600/30 transition-all hover:-translate-y-0.5">
                Login Sekarang
                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </a>
        </div>
    </div>
</div>

<script>
    function openLoginModal(action) {
        const modal = document.getElementById('loginModal');
        const modalContent = document.getElementById('loginModalContent');
        
        document.getElementById('modalActionText').innerText = action;

        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modalContent.classList.remove('scale-95');
        }, 10);
    }

    function closeLoginModal() {
        const modal = document.getElementById('loginModal');
        const modalContent = document.getElementById('loginModalContent');
        
        modal.classList.add('opacity-0');
        modalContent.classList.add('scale-95');
        setTimeout(() => { modal.classList.add('hidden'); }, 300);
    }

    window.onclick = function(e) {
        const modal = document.getElementById('loginModal');
        if (e.target === modal) {
            closeLoginModal();
        }
    }
</script>

<?= $this->endSection() ?>