<?= $this->extend('layout/admin_template') ?>

<?= $this->section('content') ?>

<div class="flex h-full flex-col px-6 py-6 z-10">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="mb-1 flex items-center gap-2 text-sm text-slate-400">
                <span>Admin</span>
                <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                <span class="font-medium text-white">Kelola Komik</span>
            </div>
            <h2 class="text-2xl font-bold text-white">Daftar Komik</h2>
        </div>
        
        <a href="<?= base_url('admin/komik/create') ?>" class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary px-4 py-2.5 text-sm font-bold text-white shadow-lg shadow-primary/30 transition-all hover:-translate-y-0.5 hover:bg-primary-dark hover:shadow-primary/50">
            <span class="material-symbols-outlined text-[20px]">add</span>
            Tambah Komik Baru
        </a>
    </div>

    <form action="<?= base_url('admin/komik') ?>" method="get" class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="relative w-full max-w-md">
            <button type="submit" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-primary z-10 transition-colors" title="Cari">
                <span class="material-symbols-outlined text-[20px]">search</span>
            </button>
            <input type="text" name="keyword" value="<?= esc($keyword ?? '') ?>" placeholder="Cari judul komik (Tekan Enter)..." class="w-full rounded-lg border border-white/10 bg-surface-dark py-2.5 pl-10 pr-4 text-sm text-white placeholder-slate-500 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
        </div>
        <div class="flex gap-2">
            <select name="status" onchange="this.form.submit()" class="rounded-lg border border-white/10 bg-surface-dark py-2.5 pl-4 pr-8 text-sm text-slate-300 focus:border-primary focus:outline-none appearance-none cursor-pointer hover:bg-white/5 transition-colors">
                <option value="">Semua Status</option>
                <option value="ongoing" <?= (isset($status) && $status == 'ongoing') ? 'selected' : '' ?>>Ongoing</option>
                <option value="completed" <?= (isset($status) && $status == 'completed') ? 'selected' : '' ?>>Completed</option>
                <option value="hiatus" <?= (isset($status) && $status == 'hiatus') ? 'selected' : '' ?>>Hiatus</option>
            </select>
        </div>
    </form>

    <?php if (session()->getFlashdata('pesan')) : ?>
        <div class="mb-4 rounded-lg border border-green-500/20 bg-green-500/10 p-4 shadow-lg backdrop-blur-sm flex items-center justify-between">
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
        <div class="mb-4 rounded-lg border border-red-500/20 bg-red-500/10 p-4 shadow-lg backdrop-blur-sm flex items-center justify-between">
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
    
    <div class="flex-1 overflow-hidden rounded-xl border border-white/10 bg-surface-dark/50 shadow-xl backdrop-blur-sm flex flex-col">
        <div class="flex-1 overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead class="sticky top-0 bg-surface-dark z-10 border-b border-white/10">
                    <tr>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-400">Cover</th>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-400 w-1/3">Judul</th>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-400">Author</th>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-400">Status</th>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-400 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 text-sm">
                    <?php if (!empty($komik)) : ?>
                        <?php foreach ($komik as $k) : ?>
                            <tr class="group hover:bg-white/[0.02] transition-colors">
                                
                                <td class="px-6 py-3">
                                    <div class="h-12 w-12 rounded-lg bg-cover bg-center shadow-md bg-background-dark ring-1 ring-white/10 transition-transform group-hover:scale-110" 
                                        style="background-image: url('<?= base_url('assets/comics/' . esc($k->cover_image)) ?>');">
                                    </div>
                                </td>
                                
                                <td class="px-6 py-3">
                                    <div class="font-medium text-white text-base"><?= esc($k->title) ?></div>
                                    <div class="text-xs text-slate-500 mt-0.5">Slug: <?= esc($k->slug) ?></div>
                                </td>
                                
                               <td class="px-6 py-3">
                                    <?php 
                                        $authors = $komikAuthors[$k->komik_id] ?? []; 
                                    ?>
                                    
                                    <?php if (!empty($authors)) : ?>
                                        <div class="group/author relative inline-block cursor-pointer z-20">
                                            <span class="inline-flex items-center gap-1 rounded bg-primary/10 px-2.5 py-1 text-xs font-semibold text-primary transition-colors group-hover/author:bg-primary group-hover/author:text-white border border-primary/20">
                                                Lihat Detail <span class="material-symbols-outlined text-[14px]">expand_more</span>
                                            </span>
                                            
                                            <div class="invisible absolute left-0 top-full mt-2 w-48 -translate-y-2 rounded-lg border border-white/10 bg-surface-dark p-2 opacity-0 shadow-xl transition-all duration-200 group-hover/author:visible group-hover/author:translate-y-0 group-hover/author:opacity-100">
                                                <ul class="flex flex-col text-sm">
                                                    <?php foreach($authors as $a) : ?>
                                                        <li class="flex flex-col border-b border-white/5 p-2 last:border-0 hover:bg-white/5 rounded transition-colors">
                                                            <span class="font-bold text-white"><?= esc($a->name) ?></span>
                                                            <?php 
                                                                $roleLabel = match($a->role) {
                                                                    'story' => 'Penulis Cerita',
                                                                    'art'   => 'Ilustrator',
                                                                    'all'   => 'Story & Art',
                                                                    default => $a->role
                                                                };
                                                            ?>
                                                            <span class="text-xs text-slate-400"><?= esc($roleLabel) ?></span>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        </div>
                                    <?php else : ?>
                                        <span class="text-xs italic text-slate-500">Belum ada data</span>
                                    <?php endif; ?>
                                </td>
                                
                                <td class="px-6 py-3">
                                    <?php 
                                        $statusColor = match($k->status) {
                                            'ongoing'   => 'text-primary border-primary/20 bg-primary/10',
                                            'completed' => 'text-green-400 border-green-500/20 bg-green-500/10',
                                            'hiatus'    => 'text-yellow-400 border-yellow-500/20 bg-yellow-500/10',
                                            default     => 'text-slate-400 border-slate-500/20 bg-slate-500/10'
                                        };
                                    ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border <?= $statusColor ?>">
                                        <?= esc(ucfirst($k->status)) ?>
                                    </span>
                                </td>
                                
                                <td class="px-6 py-3 text-right">
                                    <a href="<?= base_url('admin/komik/edit/' . $k->komik_id) ?>" class="inline-block p-2 rounded-lg hover:bg-white/10 text-slate-400 hover:text-white transition-colors" title="Edit">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </a>
                                    
                                    <button type="button" onclick="openDeleteModal('<?= $k->komik_id ?>', '<?= esc(addslashes($k->title)) ?>')" class="p-2 rounded-lg hover:bg-red-500/10 text-slate-400 hover:text-red-400 transition-colors inline-block" title="Hapus">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </td>
                                
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                <span class="material-symbols-outlined text-4xl mb-2 opacity-50">auto_stories</span>
                                <p>Belum ada komik yang sesuai dengan pencarian Anda.</p>
                                <p class="text-xs mt-1">Klik "Tambah Komik Baru" untuk memulai atau ganti kata kunci.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="flex items-center justify-between border-t border-white/10 bg-surface-dark px-6 py-3">
            <span class="text-xs text-slate-400">Menampilkan halaman 1</span>
            <div class="flex gap-2">
                <button class="rounded-lg border border-white/10 px-3 py-1.5 text-xs text-slate-400 hover:bg-white/5">Prev</button>
                <button class="rounded-lg border border-white/10 px-3 py-1.5 text-xs text-slate-400 hover:bg-white/5">Next</button>
            </div>
        </div>
    </div>
</div>

<div id="deleteModal" class="fixed inset-0 z-[100] hidden bg-background-dark/80 backdrop-blur-sm flex items-center justify-center transition-opacity duration-300 opacity-0">
    <div class="bg-surface-dark border border-white/10 rounded-2xl shadow-2xl w-full max-w-md p-6 transform scale-95 transition-transform duration-300" id="deleteModalContent">
        
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-full bg-red-500/20 flex items-center justify-center text-red-500 flex-shrink-0">
                <span class="material-symbols-outlined text-[24px]">warning</span>
            </div>
            <div>
                <h3 class="text-xl font-bold text-white">Konfirmasi Hapus</h3>
                <p class="text-sm text-slate-400 mt-1">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
        </div>
        
        <p class="text-slate-300 mb-6 text-sm">
            Apakah Anda yakin ingin menghapus komik <strong id="deleteComicTitle" class="text-white text-base"></strong> secara permanen? Semua file gambar dan relasi data akan ikut terhapus dari server.
        </p>
        
        <div class="flex gap-3 justify-end">
            <button type="button" onclick="closeDeleteModal()" class="px-5 py-2.5 rounded-lg border border-white/10 text-slate-300 hover:bg-white/5 hover:text-white transition-colors text-sm font-medium">
                Batal
            </button>
            
            <form id="deleteForm" method="post" action="">
                <?= csrf_field() ?> 
                <button type="submit" class="px-5 py-2.5 rounded-lg bg-red-500 hover:bg-red-600 text-white text-sm font-bold shadow-lg shadow-red-500/30 transition-all hover:-translate-y-0.5">
                    Ya, Hapus Komik
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function openDeleteModal(id, title) {
        const modal = document.getElementById('deleteModal');
        const modalContent = document.getElementById('deleteModalContent');
        const form = document.getElementById('deleteForm');
        const titleSpan = document.getElementById('deleteComicTitle');
        
        // Atur URL form ke rute hapus
        form.action = '<?= base_url('admin/komik/delete/') ?>' + id;
        
        // Tampilkan judul komik di dalam modal
        titleSpan.textContent = '"' + title + '"';
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modalContent.classList.remove('scale-95');
        }, 10);
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        const modalContent = document.getElementById('deleteModalContent');
        
        modal.classList.add('opacity-0');
        modalContent.classList.add('scale-95');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    // Tutup modal jika mengklik area luar
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });
</script>

<?= $this->endSection() ?>