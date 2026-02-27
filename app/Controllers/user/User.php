<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;

class User extends BaseController
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    // Pengecekan Login (Fungsi Bantuan Internal)
    private function checkAuth()
    {
        if (!session()->get('isLoggedIn')) {
            session()->setFlashdata('error', 'Silakan login terlebih dahulu.');
            return false;
        }
        return true;
    }

    // 1. Halaman Profil
    public function profile()
    {
        if (!$this->checkAuth()) return redirect()->to('/login');

        $data = ['title' => 'Profil Saya - Comi'];
        return view('user/profile', $data);
    }

    // 2. Halaman Library (Tersimpan & Riwayat)
    public function library($tab = 'bookmarks')
    {
        if (!$this->checkAuth()) return redirect()->to('/login');

        $userId = session()->get('user_id');
        $komik  = [];

        // Logika untuk Tab Bookmarks
        if ($tab === 'bookmarks') {
            // Ambil data komik yang di-bookmark oleh user ini
            $komik = $this->db->table('tb_bookmarks')
                              ->select('tb_komik.*') // Ambil data komiknya saja agar seragam dengan view catalog
                              ->join('tb_komik', 'tb_komik.komik_id = tb_bookmarks.komik_id')
                              ->where('tb_bookmarks.user_id', $userId)
                              ->orderBy('tb_bookmarks.created_at', 'DESC') // Urutkan dari yang terbaru disimpan
                              ->get()->getResultObject();
        } 
        // Logika untuk Tab Riwayat Bacaan
        elseif ($tab === 'history') {
            $komik = $this->db->table('tb_reading_history')
                              ->select('tb_komik.*, tb_chapters.chapter_number as last_chapter_read') 
                              ->join('tb_chapters', 'tb_chapters.chapter_id = tb_reading_history.chapter_id')
                              ->join('tb_komik', 'tb_komik.komik_id = tb_chapters.komik_id')
                              ->where('tb_reading_history.user_id', $userId)
                              ->groupBy('tb_komik.komik_id') 
                              ->orderBy('MAX(tb_reading_history.last_read_at)', 'DESC') 
                              ->get()->getResultObject();
        }

        $data = [
            'title'      => 'Library Saya - Comi.id',
            'active_tab' => $tab, 
            'komik'      => $komik    
        ];

        return view('user/library', $data);
    }

    // 3. Halaman Pengaturan
    public function settings()
    {
        if (!$this->checkAuth()) return redirect()->to('/login');

        $data = ['title' => 'Pengaturan Akun'];
        return view('user/settings', $data);
    }
    
    // 4. Proses Update Profil
    public function updateProfile()
    {
        if (!$this->checkAuth()) return redirect()->to('/login');

        $userId = session()->get('user_id');

        $rules = [
            'username' => "required|min_length[3]|max_length[100]|is_unique[tb_users.username,user_id,{$userId}]",
            'profile_picture' => 'max_size[profile_picture,2048]|is_image[profile_picture]|mime_in[profile_picture,image/png,image/jpg,image/jpeg,image/webp]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        $dataUpdate = [
            'username'   => $this->request->getPost('username'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $userLama = $this->db->table('tb_users')->where('user_id', $userId)->get()->getRowObject();
        $fileFoto = $this->request->getFile('profile_picture');

        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            if (!empty($userLama->profile_picture) && file_exists(FCPATH . 'users/' . $userLama->profile_picture)) {
                unlink(FCPATH . 'users/' . $userLama->profile_picture);
            }

            $namaFotoBaru = $fileFoto->getRandomName();
            $fileFoto->move(FCPATH . 'users', $namaFotoBaru);
            $dataUpdate['profile_picture'] = $namaFotoBaru;
        }

        $this->db->table('tb_users')->where('user_id', $userId)->update($dataUpdate);

        session()->set('username', $dataUpdate['username']);
        if (isset($dataUpdate['profile_picture'])) {
            session()->set('profile_picture', $dataUpdate['profile_picture']);
        }

        return redirect()->back()->with('pesan', 'Profil Anda berhasil diperbarui!');
    }

    // 5. Proses Tambah/Hapus Bookmark
    public function toggleBookmark($komik_id)
    {
        if (!$this->checkAuth()) return redirect()->to('/login');

        $userId = session()->get('user_id');

        // Cek apakah komik ini sudah ada di bookmark user
        $cekBookmark = $this->db->table('tb_bookmarks')
                                ->where(['user_id' => $userId, 'komik_id' => $komik_id])
                                ->get()->getRow();

        if ($cekBookmark) {
            // Jika SUDAH ADA, maka HAPUS (Unbookmark)
            $this->db->table('tb_bookmarks')
                     ->where(['user_id' => $userId, 'komik_id' => $komik_id])
                     ->delete();
            return redirect()->back()->with('pesan', 'Komik dihapus dari Library.');
        } else {
            // Jika BELUM ADA, maka TAMBAHKAN (Bookmark)
            $this->db->table('tb_bookmarks')->insert([
                'user_id'    => $userId,
                'komik_id'   => $komik_id,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            return redirect()->back()->with('pesan', 'Komik berhasil ditambahkan ke Library!');
        }
    }

    // 6. Update Password
    public function updatePassword()
    {
        if (!$this->checkAuth()) return redirect()->to('/login');

        $rules = [
            'old_password'     => 'required',
            'new_password'     => 'required|min_length[6]',
            'confirm_password' => 'required|matches[new_password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', 'Validasi gagal. Pastikan password baru cocok dan minimal 6 karakter.');
        }

        $userId = session()->get('user_id');
        $user   = $this->db->table('tb_users')->where('user_id', $userId)->get()->getRow();

        // Verifikasi password lama
        if (!password_verify($this->request->getPost('old_password'), $user->password)) {
            return redirect()->back()->with('error', 'Password saat ini salah.');
        }

        // Update password baru
        $this->db->table('tb_users')->where('user_id', $userId)->update([
            'password'   => password_hash($this->request->getPost('new_password'), PASSWORD_DEFAULT),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('pesan', 'Password berhasil diperbarui!');
    }

    // 7. Hapus Akun
    public function deleteAccount()
    {
        if (!$this->checkAuth()) return redirect()->to('/login');

        $userId   = session()->get('user_id');
        $userLama = $this->db->table('tb_users')->where('user_id', $userId)->get()->getRow();

        // Hapus file foto profil jika ada
        if (!empty($userLama->profile_picture) && file_exists(FCPATH . 'users/' . $userLama->profile_picture)) {
            unlink(FCPATH . 'users/' . $userLama->profile_picture);
        }

        // Hapus data dari database (karena foreign key, pastikan bookmark & history ikut terhapus atau gunakan Cascade)
        $this->db->table('tb_users')->where('user_id', $userId)->delete();

        // Hancurkan session dan pindah ke login
        session()->destroy();
        return redirect()->to('/login')->with('pesan', 'Akun Anda telah dihapus secara permanen.');
    }
}