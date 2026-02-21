<?php

namespace App\Controllers;

class User extends BaseController
{
    public function __construct()
    {
        // Helper session sudah otomatis dimuat jika diatur di BaseController, 
        // tapi kita pastikan dipanggil di sini jika butuh.
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

        // $tab akan berisi 'bookmarks' atau 'history' sesuai URL
        $data = [
            'title'      => 'Library Saya',
            'active_tab' => $tab, 
            'komik'      => []    
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

    // 4. Proses Update Profile (Hanya kerangka untuk nanti)
    public function updateProfile()
    {
        if (!$this->checkAuth()) return redirect()->to('/login');
        // Logika update ke database nanti di sini
        return redirect()->to('/user/profile')->with('pesan', 'Profil diupdate!');
    }
}