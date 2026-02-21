<?= $this->extend('layout/admin_template') ?>

<?= $this->section('content') ?>

<div class="flex h-full flex-col px-6 py-6 z-10">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="mb-1 flex items-center gap-2 text-sm text-slate-400">
                <span>Admin</span>
                <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                <span class="font-medium text-white">Kelola Pengguna</span>
            </div>
            <h2 class="text-2xl font-bold text-white">Daftar Pengguna</h2>
        </div>
    </div>

    <form action="<?= base_url('admin/users') ?>" method="get" class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="relative w-full max-w-md">
            <button type="submit" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-primary transition-colors z-10" title="Cari">
                <span class="material-symbols-outlined text-[20px]">search</span>
            </button>
            <input type="text" name="keyword" value="<?= esc($keyword ?? '') ?>" placeholder="Cari username atau email (Tekan Enter)..." class="w-full rounded-lg border border-white/10 bg-surface-dark py-2.5 pl-10 pr-4 text-sm text-white placeholder-slate-500 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
        </div>
        
        <div class="flex gap-2">
            <select name="role" onchange="this.form.submit()" class="rounded-lg border border-white/10 bg-surface-dark py-2.5 pl-4 pr-8 text-sm text-slate-300 focus:border-primary focus:outline-none appearance-none">
                <option value="" <?= empty($role) ? 'selected' : '' ?>>Semua Role</option>
                <option value="admin" <?= (isset($role) && $role === 'admin') ? 'selected' : '' ?>>Admin</option>
                <option value="user" <?= (isset($role) && $role === 'user') ? 'selected' : '' ?>>User</option>
            </select>
        </div>
    </form>

    <div class="flex-1 overflow-hidden rounded-xl border border-white/10 bg-surface-dark/50 shadow-xl backdrop-blur-sm flex flex-col">
        <div class="flex-1 overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead class="sticky top-0 bg-surface-dark z-10 border-b border-white/10">
                    <tr>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-400">Profil</th>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-400">Akun</th>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-400">Role</th>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-400">Tgl Daftar</th>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-400 text-right">Aksi</th>
                    </tr>
                </thead>
                
                <tbody class="divide-y divide-white/5 text-sm">
                    <?php if (empty($users)) : ?>
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                <span class="material-symbols-outlined text-4xl mb-2 opacity-50">group_off</span>
                                <p>Belum ada data pengguna di Database atau tidak ada yang cocok dengan pencarianmu.</p>
                            </td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($users as $user) : ?>
                            <tr class="group hover:bg-white/[0.02] transition-colors">
                                
                                <td class="px-6 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-primary/10 text-primary ring-1 ring-primary/20">
                                            <span class="material-symbols-outlined text-[18px]">person</span>
                                        </div>
                                        <span class="font-medium text-white text-base"><?= esc($user['username']); ?></span>
                                    </div>
                                </td>
                                
                                <td class="px-6 py-3 text-slate-300">
                                    <?= esc($user['email']); ?>
                                </td>
                                
                                <td class="px-6 py-3">
                                    <?php 
                                        $roleColor = match(strtolower($user['role'])) {
                                            'admin' => 'text-primary border-primary/20 bg-primary/10',
                                            default => 'text-slate-400 border-slate-500/20 bg-slate-500/10'
                                        };
                                    ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border <?= $roleColor ?>">
                                        <?= esc(ucfirst($user['role'])); ?>
                                    </span>
                                </td>
                                
                                <td class="px-6 py-3 text-slate-400">
                                    <?= esc($user['created_at']); ?>
                                </td>
                                
                               <td class="px-6 py-3 text-right">
                                    <?php if (strtolower($user['role']) !== 'admin') : ?>
                                        <a href="/admin/users/edit/<?= $user['user_id']; ?>" class="inline-block p-2 rounded-lg hover:bg-white/10 text-slate-400 hover:text-white transition-colors" title="Edit">
                                            <span class="material-symbols-outlined text-[18px]">edit</span>
                                        </a>
                                    <?php else : ?>
                                        <span class="text-xs text-slate-500 italic px-2 py-1">Tidak dapat diedit</span>
                                    <?php endif; ?>
                                </td>
                                
                            </tr>
                        <?php endforeach; ?>
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