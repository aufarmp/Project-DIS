<?= $this->extend('layout/admin_template') ?>

<?= $this->section('content') ?>

<div class="flex h-full flex-col px-6 py-6 z-10 overflow-y-auto">
    
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="mb-1 flex items-center gap-2 text-sm text-slate-400">
                <a href="<?= base_url('admin/chapter') ?>" class="hover:text-white transition-colors">Kelola Chapter</a>
                <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                <a href="<?= base_url('admin/chapter/list/' . $komik->komik_id) ?>" class="hover:text-white transition-colors">Daftar Chapter</a>
                <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                <span class="font-medium text-white">Kelola Pages</span>
            </div>
            <h2 class="text-2xl font-bold text-white">Chapter <?= esc($chapter->chapter_number) ?>: <?= esc($chapter->title) ?></h2>
            <p class="text-primary mt-1 text-sm font-medium">Komik: <?= esc($komik->title) ?></p>
        </div>
        
        <div class="flex gap-3">
            <a href="<?= base_url('admin/chapter/list/' . $komik->komik_id) ?>" class="inline-flex items-center justify-center gap-2 rounded-lg bg-surface-dark border border-white/10 px-4 py-2.5 text-sm font-bold text-white transition-all hover:bg-white/5">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                Kembali
            </a>
            
            <button type="button" onclick="openAddPagesModal('<?= $chapter->chapter_id ?>')" class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary px-4 py-2.5 text-sm font-bold text-white shadow-lg shadow-primary/30 transition-all hover:-translate-y-0.5 hover:bg-primary-dark hover:shadow-primary/50">
                <span class="material-symbols-outlined text-[20px]">add_photo_alternate</span>
                Tambah Pages
            </button>
        </div>
    </div>

    <?php if (session()->getFlashdata('pesan')) : ?>
        <div class="mb-6 rounded-lg border border-green-500/20 bg-green-500/10 p-4 shadow-lg backdrop-blur-sm flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-green-500/20 text-green-400">
                    <span class="material-symbols-outlined text-[20px]">check_circle</span>
                </div>
                <p class="text-sm font-medium text-green-400"><?= session()->getFlashdata('pesan') ?></p>
            </div>
            <button type="button" onclick="this.parentElement.style.display='none'" class="text-green-400/70 hover:text-green-400 transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="mb-6 rounded-lg border border-red-500/20 bg-red-500/10 p-4 shadow-lg backdrop-blur-sm flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-red-500/20 text-red-400">
                    <span class="material-symbols-outlined text-[20px]">error</span>
                </div>
                <p class="text-sm font-medium text-red-400"><?= session()->getFlashdata('error') ?></p>
            </div>
            <button type="button" onclick="this.parentElement.style.display='none'" class="text-red-400/70 hover:text-red-400 transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
    <?php endif; ?>

    <div class="rounded-xl border border-white/10 bg-surface-dark/50 shadow-xl backdrop-blur-sm p-6">
        <?php if (!empty($pages)) : ?>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 gap-6">
                <?php foreach ($pages as $p) : ?>
                    <div class="group relative rounded-lg border border-white/10 bg-background-dark overflow-hidden flex flex-col">
                        
                        <div class="aspect-[3/4] w-full bg-cover bg-top relative" style="background-image: url('<?= base_url('assets/comics/' . esc($p->image_url)) ?>');">
                            
                            <div class="absolute inset-0 bg-background-dark/80 backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center gap-3 p-4">
                                
                                <button type="button" onclick="openReplaceModal('<?= $p->page_id ?>', '<?= $p->page_number ?>')" class="w-full flex items-center justify-center gap-2 rounded bg-primary px-3 py-2 text-xs font-bold text-white hover:bg-primary-dark transition-colors">
                                    <span class="material-symbols-outlined text-[16px]">imagesmode</span>
                                    Ganti Gambar
                                </button>
                                
                                <button type="button" onclick="openDeletePageModal('<?= $p->page_id ?>', '<?= $p->page_number ?>')" class="w-full flex items-center justify-center gap-2 rounded bg-red-500/20 border border-red-500/30 px-3 py-2 text-xs font-bold text-red-400 hover:bg-red-500 hover:text-white transition-colors">
                                    <span class="material-symbols-outlined text-[16px]">delete</span>
                                    Hapus Page
                                </button>

                            </div>
                        </div>

                        <div class="p-3 border-t border-white/10 flex justify-between items-center bg-surface-dark">
                            <span class="text-sm font-bold text-white">Hal. <?= esc($p->page_number) ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <div class="text-center py-12">
                <span class="material-symbols-outlined text-4xl mb-2 text-slate-500">hide_image</span>
                <p class="text-slate-400">Belum ada gambar yang diupload untuk chapter ini.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<div id="replaceModal" class="fixed inset-0 z-[100] hidden bg-background-dark/80 backdrop-blur-sm flex items-center justify-center transition-opacity duration-300 opacity-0">
    <div class="bg-surface-dark border border-white/10 rounded-2xl shadow-2xl w-full max-w-md p-6 transform scale-95 transition-transform duration-300" id="replaceModalContent">
        
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-full bg-primary/20 flex items-center justify-center text-primary flex-shrink-0">
                <span class="material-symbols-outlined text-[24px]">flip_camera_ios</span>
            </div>
            <div>
                <h3 class="text-xl font-bold text-white">Ganti Gambar Halaman <span id="modalPageNumberText"></span></h3>
                <p class="text-sm text-slate-400 mt-1">Pilih gambar baru untuk menimpa halaman ini.</p>
            </div>
        </div>
        
        <form id="replaceForm" method="post" enctype="multipart/form-data" action="">
            <?= csrf_field() ?> 
            <input type="file" name="new_page" accept="image/png, image/jpeg, image/jpg, image/webp" required
                   class="block w-full text-sm text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary/20 file:text-primary hover:file:bg-primary/30 transition-all cursor-pointer border border-white/10 bg-background-dark rounded-lg mb-6">
            
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeReplaceModal()" class="px-5 py-2.5 rounded-lg border border-white/10 text-slate-300 hover:bg-white/5 hover:text-white transition-colors text-sm font-medium">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-lg bg-primary hover:bg-primary-dark text-white text-sm font-bold shadow-lg shadow-primary/30 transition-all hover:-translate-y-0.5">
                    Upload & Ganti
                </button>
            </div>
        </form>
    </div>
</div>

<div id="deletePageModal" class="fixed inset-0 z-[100] hidden bg-background-dark/80 backdrop-blur-sm flex items-center justify-center transition-opacity duration-300 opacity-0">
    <div class="bg-surface-dark border border-white/10 rounded-2xl shadow-2xl w-full max-w-md p-6 transform scale-95 transition-transform duration-300" id="deletePageModalContent">
        
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-full bg-red-500/20 flex items-center justify-center text-red-500 flex-shrink-0">
                <span class="material-symbols-outlined text-[24px]">delete_forever</span>
            </div>
            <div>
                <h3 class="text-xl font-bold text-white">Hapus Halaman <span id="deleteModalPageNumberText"></span></h3>
                <p class="text-sm text-slate-400 mt-1">Tindakan ini permanen.</p>
            </div>
        </div>
        
        <p class="text-slate-300 mb-6 text-sm">
            Apakah Anda yakin ingin menghapus gambar halaman ini dari sistem secara permanen?
        </p>
        
        <div class="flex gap-3 justify-end">
            <button type="button" onclick="closeDeletePageModal()" class="px-5 py-2.5 rounded-lg border border-white/10 text-slate-300 hover:bg-white/5 hover:text-white transition-colors text-sm font-medium">
                Batal
            </button>
            <form id="deletePageForm" method="post" action="">
                <?= csrf_field() ?> 
                <button type="submit" class="px-5 py-2.5 rounded-lg bg-red-500 hover:bg-red-600 text-white text-sm font-bold shadow-lg shadow-red-500/30 transition-all hover:-translate-y-0.5">
                    Ya, Hapus
                </button>
            </form>
        </div>
    </div>
</div> <div id="addPagesModal" class="fixed inset-0 z-[100] hidden bg-background-dark/80 backdrop-blur-sm flex items-center justify-center transition-opacity duration-300 opacity-0">
    <div class="bg-surface-dark border border-white/10 rounded-2xl shadow-2xl w-full max-w-md p-6 transform scale-95 transition-transform duration-300" id="addPagesModalContent">
        
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-full bg-primary/20 flex items-center justify-center text-primary flex-shrink-0">
                <span class="material-symbols-outlined text-[24px]">library_add</span>
            </div>
            <div>
                <h3 class="text-xl font-bold text-white">Tambah Halaman</h3>
                <p class="text-sm text-slate-400 mt-1">Halaman akan ditambahkan di urutan paling akhir.</p>
            </div>
        </div>
        
        <form id="addPagesForm" method="post" enctype="multipart/form-data" action="">
            <?= csrf_field() ?> 
            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-300 mb-2">Pilih Gambar (Bisa lebih dari 1)</label>
                <input type="file" name="pages[]" multiple accept="image/png, image/jpeg, image/jpg, image/webp" required
                    class="block w-full text-sm text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary/20 file:text-primary hover:file:bg-primary/30 transition-all cursor-pointer border border-white/10 bg-background-dark rounded-lg">
                <p class="text-xs text-slate-500 mt-2">Gambar akan diurutkan otomatis sesuai nama file.</p>
            </div>
            
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeAddPagesModal()" class="px-5 py-2.5 rounded-lg border border-white/10 text-slate-300 hover:bg-white/5 hover:text-white transition-colors text-sm font-medium">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-lg bg-primary hover:bg-primary-dark text-white text-sm font-bold shadow-lg shadow-primary/30 transition-all hover:-translate-y-0.5">
                    Upload & Tambahkan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // --- Logika Modal Replace ---
    function openReplaceModal(pageId, pageNumber) {
        const modal = document.getElementById('replaceModal');
        const modalContent = document.getElementById('replaceModalContent');
        const form = document.getElementById('replaceForm');
        
        form.action = '<?= base_url('admin/chapter/update-page/') ?>' + pageId;
        document.getElementById('modalPageNumberText').innerText = pageNumber;
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modalContent.classList.remove('scale-95');
        }, 10);
    }

    function closeReplaceModal() {
        const modal = document.getElementById('replaceModal');
        const modalContent = document.getElementById('replaceModalContent');
        modal.classList.add('opacity-0');
        modalContent.classList.add('scale-95');
        setTimeout(() => { modal.classList.add('hidden'); }, 300);
    }

    // --- Logika Modal Delete ---
    function openDeletePageModal(pageId, pageNumber) {
        const modal = document.getElementById('deletePageModal');
        const modalContent = document.getElementById('deletePageModalContent');
        const form = document.getElementById('deletePageForm');
        
        form.action = '<?= base_url('admin/chapter/delete-page/') ?>' + pageId;
        document.getElementById('deleteModalPageNumberText').innerText = pageNumber;
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modalContent.classList.remove('scale-95');
        }, 10);
    }

    function closeDeletePageModal() {
        const modal = document.getElementById('deletePageModal');
        const modalContent = document.getElementById('deletePageModalContent');
        modal.classList.add('opacity-0');
        modalContent.classList.add('scale-95');
        setTimeout(() => { modal.classList.add('hidden'); }, 300);
    }
    
    // --- Logika Modal Tambah Pages Susulan ---
    function openAddPagesModal(chapterId) {
        const modal = document.getElementById('addPagesModal');
        const modalContent = document.getElementById('addPagesModalContent');
        const form = document.getElementById('addPagesForm');
        
        form.action = '<?= base_url('admin/chapter/add-pages/') ?>' + chapterId;
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modalContent.classList.remove('scale-95');
        }, 10);
    }

    function closeAddPagesModal() {
        const modal = document.getElementById('addPagesModal');
        const modalContent = document.getElementById('addPagesModalContent');
        
        modal.classList.add('opacity-0');
        modalContent.classList.add('scale-95');
        setTimeout(() => { modal.classList.add('hidden'); }, 300);
    }

    // --- SATU window.onclick UNTUK SEMUA MODAL ---
    window.onclick = function(e) {
        const replaceModal = document.getElementById('replaceModal');
        const deleteModal  = document.getElementById('deletePageModal');
        const addModal     = document.getElementById('addPagesModal'); 
        
        if (e.target === replaceModal) closeReplaceModal();
        if (e.target === deleteModal)  closeDeletePageModal();
        if (e.target === addModal)     closeAddPagesModal(); 
    }
</script>

<?= $this->endSection() ?>