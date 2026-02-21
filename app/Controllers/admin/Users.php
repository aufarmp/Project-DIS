<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Users extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        // 1. Tangkap inputan dari form pencarian dan filter
        $keyword = $this->request->getGet('keyword');
        $role    = $this->request->getGet('role');

        // 2. Jika ada keyword pencarian (berdasarkan username atau email)
        if (!empty($keyword)) {
            $this->userModel->groupStart()
                            ->like('username', $keyword)
                            ->orLike('email', $keyword)
                            ->groupEnd();
        }

        // 3. Jika ada filter role (Admin / User)
        if (!empty($role)) {
            $this->userModel->where('role', $role);
        }

        // Ambil data yang sudah difilter (jika tidak ada filter, otomatis ambil semua)
        $usersData = $this->userModel->findAll();

        $data = [
            'title'   => 'Kelola Pengguna - Admin',
            'users'   => $usersData,
            // Kirim kembali keyword dan role ke view agar inputan/pilihan tidak hilang setelah di-refresh
            'keyword' => $keyword,
            'role'    => $role 
        ];

        return view('admin/users/index', $data);
    }

    // EDIT
    // 1. Method untuk menampilkan halaman form edit
    public function edit($id)
    {
        $user = $this->userModel->find($id);

        // Keamanan: Jika user tidak ada ATAU role-nya adalah admin, kembalikan ke halaman index
        if (!$user || strtolower($user['role']) === 'admin') {
            return redirect()->to('/admin/users')->with('error', 'Akses ditolak: Tidak dapat mengedit data Admin.');
        }

        $data = [
            'title' => 'Edit Pengguna - Admin',
            'user'  => $user
        ];

        return view('admin/users/edit', $data);
    }

    // 2. Method untuk memproses data dari form edit
    public function update($id)
    {
        // Keamanan
        $user = $this->userModel->find($id);
        if (!$user || strtolower($user['role']) === 'admin') {
            return redirect()->to('/admin/users')->with('error', 'Akses ditolak: Tidak dapat mengubah data Admin.');
        }

        // Ambil nama primary key dari model
        $pkName = $this->userModel->primaryKey;
        // Ambil nama tabel dari model
        $tableName = $this->userModel->table;

        // Validasi input
        $rules = [
            'username' => 'required|min_length[3]',
            'email'    => "required|valid_email|is_unique[{$tableName}.email,{$pkName},{$id}]"
        ];

        $messages = [
            'email' => [
                'is_unique' => 'Alamat email ini sudah digunakan oleh pengguna lain.'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->userModel->update($id, [
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email')
        ]);

        return redirect()->to('/admin/users')->with('message', 'Data pengguna berhasil diperbarui.');
    }
}