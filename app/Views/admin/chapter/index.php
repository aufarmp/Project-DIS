<?= $this->extend('layout/admin_template') ?>

<?= $this->section('content') ?>

<div class="flex h-full flex-col px-6 py-6 z-10">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="mb-1 flex items-center gap-2 text-sm text-slate-400">
                <span>Admin</span>
                <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                <span class="font-medium text-white">Kelola Chapter</span>
            </div>
            <h2 class="text-2xl font-bold text-white">Pilih Komik untuk Dikelola</h2>
        </div>
    </div>

    <form action="<?= base_url('admin/chapter') ?>" method="get" class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="relative w-full max-w-md">
            <button type="submit" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-primary z-10 transition-colors" title="Cari">
                <span class="material-symbols-outlined text-[20px]">search</span>
            </button>
            <input type="text" name="keyword" value="<?= esc($keyword ?? '') ?>" placeholder="Cari judul komik..." class="w-full rounded-lg border border-white/10 bg-surface-dark py-2.5 pl-10 pr-4 text-sm text-white placeholder-slate-500 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
        </div>
    </form>

    <div class="flex-1 overflow-hidden rounded-xl border border-white/10 bg-surface-dark/50 shadow-xl backdrop-blur-sm flex flex-col">
        <div class="flex-1 overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead class="sticky top-0 bg-surface-dark z-10 border-b border-white/10">
                    <tr>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-400">Cover</th>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-400 w-1/3">Judul Komik</th>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-400 text-center">Total Chapter</th>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-400 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 text-sm">
                    <?php if (!empty($komik)) : ?>
                        <?php foreach ($komik as $k) : ?>
                            <tr class="group hover:bg-white/[0.02] transition-colors">
                                <td class="px-6 py-3">
                                    <div class="h-12 w-12 rounded-lg bg-cover bg-center shadow-md bg-background-dark ring-1 ring-white/10" 
                                        style="background-image: url('<?= base_url('assets/comics/' . esc($k->cover_image)) ?>');">
                                    </div>
                                </td>
                                
                                <td class="px-6 py-3">
                                    <div class="font-medium text-white text-base"><?= esc($k->title) ?></div>
                                    <div class="text-xs text-slate-500 mt-0.5">Slug: <?= esc($k->slug) ?></div>
                                    <?php 
                                        $statusColor = match($k->status) {
                                            'ongoing'   => 'text-primary',
                                            'completed' => 'text-green-400',
                                            'hiatus'    => 'text-yellow-400',
                                            default     => 'text-slate-400'
                                        };
                                    ?>
                                    <div class="text-xs <?= $statusColor ?> mt-1 font-medium">• <?= esc(ucfirst($k->status)) ?></div>
                                </td>
                                
                                <td class="px-6 py-3 text-center">
                                    <?php $count = $chapterCounts[$k->komik_id] ?? 0; ?>
                                    
                                    <div class="inline-flex items-center justify-center rounded-lg <?= $count > 0 ? 'bg-primary/10 border-primary/20 text-primary' : 'bg-white/5 border-white/10 text-slate-400' ?> border px-3 py-1">
                                        <span class="font-bold text-sm"><?= $count ?></span>
                                        <span class="ml-1 text-xs">Chapter</span>
                                    </div>
                                </td>
                                
                                <td class="px-6 py-3 text-right">
                                    <a href="<?= base_url('admin/chapter/list/' . $k->komik_id) ?>" class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-bold text-white shadow-lg shadow-primary/30 transition-all hover:-translate-y-0.5 hover:bg-primary-dark hover:shadow-primary/50">
                                        <span class="material-symbols-outlined text-[18px]">menu_book</span>
                                        Kelola Chapter
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                                <span class="material-symbols-outlined text-4xl mb-2 opacity-50">auto_stories</span>
                                <p>Belum ada komik yang tersedia.</p>
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

<?= $this->endSection() ?>