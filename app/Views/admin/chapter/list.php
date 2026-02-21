<?= $this->extend('layout/admin_template') ?>

<?= $this->section('content') ?>

<div class="flex h-full flex-col px-6 py-6 z-10 overflow-y-auto">
    
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="mb-1 flex items-center gap-2 text-sm text-slate-400">
                <a href="<?= base_url('admin/dashboard') ?>" class="hover:text-white transition-colors">Admin</a>
                <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                <a href="<?= base_url('admin/chapter') ?>" class="hover:text-white transition-colors">Kelola Chapter</a>
                <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                <span class="font-medium text-white">Daftar Chapter</span>
            </div>
            <h2 class="text-2xl font-bold text-white"><?= esc($komik->title) ?></h2>
        </div>
        
        <div class="flex gap-3">
            <a href="<?= base_url('admin/chapter') ?>" class="inline-flex items-center justify-center gap-2 rounded-lg bg-surface-dark border border-white/10 px-4 py-2.5 text-sm font-bold text-white transition-all hover:bg-white/5">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                Kembali
            </a>
            <a href="<?= base_url('admin/chapter/add-chapter/' . $komik->komik_id) ?>" class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary px-4 py-2.5 text-sm font-bold text-white shadow-lg shadow-primary/30 transition-all hover:-translate-y-0.5 hover:bg-primary-dark hover:shadow-primary/50">
                <span class="material-symbols-outlined text-[20px]">add</span>
                Tambah Chapter Baru
            </a>
        </div>
    </div>

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

    <div class="flex-1 overflow-hidden rounded-xl border border-white/10 bg-surface-dark/50 shadow-xl backdrop-blur-sm flex flex-col mt-4">
        <div class="flex-1 overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead class="sticky top-0 bg-surface-dark z-10 border-b border-white/10">
                    <tr>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-400">Chapter</th>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-400 w-1/3">Judul Chapter</th>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-400">Tgl Upload</th>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-400 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 text-sm">
                    <?php if (!empty($chapters)) : ?>
                        <?php foreach ($chapters as $c) : ?>
                            <tr class="group hover:bg-white/[0.02] transition-colors">
                                <td class="px-6 py-4 font-bold text-white text-base">
                                    Chapter <?= esc($c->chapter_number) ?>
                                </td>
                                
                                <td class="px-6 py-4 text-slate-300">
                                    <?= !empty($c->title) ? esc($c->title) : '<span class="text-slate-500 italic">Tanpa Judul</span>' ?>
                                </td>
                                
                                <td class="px-6 py-4 text-slate-400 text-xs">
                                    <?= date('d M Y, H:i', strtotime($c->created_at)) ?>
                                </td>
                                
                                <td class="px-6 py-4 text-right">
                                    <a href="<?= base_url('admin/chapter/edit/' . $c->chapter_id) ?>" class="inline-flex items-center gap-2 rounded-lg bg-surface-dark border border-white/10 px-3 py-2 text-xs font-bold text-slate-300 transition-all hover:bg-primary hover:text-white hover:border-primary">
                                        <span class="material-symbols-outlined text-[16px]">edit_document</span>
                                        Kelola Pages
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                                <span class="material-symbols-outlined text-4xl mb-2 opacity-50">auto_stories</span>
                                <p>Komik ini belum memiliki chapter.</p>
                                <p class="text-xs mt-1">Klik "Tambah Chapter Baru" di pojok kanan atas untuk memulai.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>