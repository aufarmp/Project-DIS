<?= $this->extend('layout/admin_template') ?>

<?= $this->section('content') ?>

<header class="flex-shrink-0 flex flex-col gap-6 p-6 pb-2 z-10">
    <div class="flex justify-between items-center">
        <div class="flex items-center gap-2 text-sm text-slate-400">
            <span>Admin</span>
            <span class="material-symbols-outlined text-[16px]">chevron_right</span>
            <span class="text-white font-medium">Dashboard</span>
        </div>
    </div>

    <div class="rounded-2xl overflow-hidden relative min-h-[180px] flex items-end group shadow-2xl">
        <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-105" style="background-image: url('https://images.unsplash.com/photo-1614726365723-49fa26027a68?q=80&w=2574&auto=format&fit=crop'); opacity: 0.3;"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-background-dark via-background-dark/80 to-transparent"></div>
        
        <div class="relative p-6 w-full flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-white mb-1">Comic Management</h2>
                <p class="text-slate-400 max-w-xl">Kelola perpustakaan komik, update chapter, dan pantau aktivitas pengguna.</p>
            </div>
            <div class="hidden md:flex gap-3">
                <div class="px-4 py-2 rounded-lg bg-surface-dark/80 backdrop-blur-sm border border-white/10 flex flex-col">
                    <span class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Total Komik</span>
                    <span class="text-xl font-bold text-white"><?= $total_komik ?? '0' ?></span>
                </div>
                <div class="px-4 py-2 rounded-lg bg-surface-dark/80 backdrop-blur-sm border border-white/10 flex flex-col">
                    <span class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Total Pengguna</span>
                    <span class="text-xl font-bold text-primary"><?= $total_user ?? '0' ?></span>
                </div>
            </div>
        </div>
    </div>
</header>

<form action="<?= base_url('admin/dashboard') ?>" method="get" class="px-6 py-4 flex flex-col md:flex-row gap-4 items-stretch md:items-center justify-between z-10">
    <div class="relative flex-1 max-w-lg">
        <button type="submit" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-primary z-10 transition-colors" title="Cari">
            <span class="material-symbols-outlined text-[20px]">search</span>
        </button>
        <input name="keyword" value="<?= esc($keyword ?? '') ?>" class="w-full bg-surface-dark border border-white/10 rounded-lg py-2.5 pl-10 pr-4 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all shadow-sm" placeholder="Cari judul komik (Tekan Enter)..." type="text"/>
    </div>
    
    <div class="flex gap-3">
        <select name="status" onchange="this.form.submit()" class="rounded-lg border border-white/10 bg-surface-dark py-2.5 pl-4 pr-8 text-sm text-slate-300 focus:border-primary focus:outline-none appearance-none cursor-pointer hover:bg-white/5 transition-colors">
            <option value="">Semua Status</option>
            <option value="ongoing" <?= (isset($status) && $status == 'ongoing') ? 'selected' : '' ?>>Ongoing</option>
            <option value="completed" <?= (isset($status) && $status == 'completed') ? 'selected' : '' ?>>Completed</option>
            <option value="hiatus" <?= (isset($status) && $status == 'hiatus') ? 'selected' : '' ?>>Hiatus</option>
        </select>
    </div>
</form>

<div class="flex-1 px-6 pb-6 overflow-hidden min-h-0 z-0">
    <div class="h-full bg-surface-dark/50 backdrop-blur-sm border border-white/10 rounded-2xl overflow-hidden flex flex-col shadow-xl">
        <div class="overflow-x-auto flex-1">
            <table class="w-full text-left border-collapse">
                <thead class="sticky top-0 bg-surface-dark z-10">
                    <tr>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-400 uppercase border-b border-white/5">Cover</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-400 uppercase border-b border-white/5 w-1/3">Judul</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-400 uppercase border-b border-white/5">Author</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-400 uppercase border-b border-white/5 text-right">Status</th>
                    </tr>
                </thead>
                
                <tbody class="divide-y divide-white/5 text-sm">
                    <?php if(!empty($komik_terbaru)) : ?>
                        <?php foreach($komik_terbaru as $k) : ?>
                            <tr class="group hover:bg-white/[0.02] transition-colors">
                                <td class="px-6 py-3">
                                    <div class="h-12 w-12 rounded-lg bg-cover bg-center shadow-md bg-background-dark ring-1 ring-white/10"
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
                                
                                <td class="px-6 py-3 text-right">
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
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-slate-500 italic">Belum ada komik terbaru.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>