<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function index()
    {
        // Jika sudah login, lempar ke dashboard sesuai role
        if (session()->get('isLoggedIn')) {
            return redirect()->to(session()->get('role') == 'admin' ? '/admin/dashboard' : '/');
        }

        $data = ['title' => 'Login - Comi'];
        return view('auth/login', $data);
    }

    public function auth()
    {
        $session = session();
        $model   = new UserModel();
        
        // Ambil input
        $loginId  = $this->request->getVar('login_id');
        $password = $this->request->getVar('password');

        // Cari user berdasarkan Username / Email
        $user = $model->where('username', $loginId)
                      ->orWhere('email', $loginId)
                      ->first();

        if ($user) {
            // Verifikasi Password (Hash)
            if (password_verify($password, $user['password'])) {
                
                // Set Session Data - TAMBAHKAN EMAIL DI SINI
                $ses_data = [
                    'user_id'         => $user['user_id'],
                    'username'        => $user['username'],
                    'email'           => $user['email'],
                    'role'            => $user['role'],
                    'isLoggedIn'      => TRUE,
                    'profile_picture' => $user['profile_picture']
                ];
                $session->set($ses_data);
                
                // Redirect sesuai Role
                if ($user['role'] == 'admin') {
                    return redirect()->to('/admin/dashboard');
                } else {
                    return redirect()->to('/home');
                }

            } else {
                $session->setFlashdata('error', 'Password salah.');
                return redirect()->to('/login');
            }
        } else {
            $session->setFlashdata('error', 'Username/Email tidak ditemukan.');
            return redirect()->to('/login');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    // 1. Menampilkan Halaman Register
    public function register()
    {
        $data = [
            'title' => 'Daftar Akun - Comi.id'
        ];
        
        // Sesuaikan dengan letak folder view Anda (misal: auth/register atau user/register)
        return view('auth/register', $data); 
    }

    // 2. Memproses Data Input dari Form Register
    public function processRegister()
    {
        // Aturan validasi input
        $rules = [
            'username'     => 'required|min_length[3]|max_length[100]|is_unique[tb_users.username]',
            'email'        => 'required|valid_email|is_unique[tb_users.email]',
            'password'     => 'required|min_length[6]',
            'pass_confirm' => 'required|matches[password]'
        ];

        // Jika validasi gagal, kembalikan ke form register beserta pesan error
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Jika validasi berhasil, simpan ke database (tabel tb_users)
        $db = \Config\Database::connect();
        $db->table('tb_users')->insert([
            'username'   => $this->request->getPost('username'),
            'email'      => $this->request->getPost('email'),

            'password'   => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'       => 'user', // Default role untuk pendaftar baru
            'created_at' => date('Y-m-d H:i:s')
        ]);

        // Redirect ke halaman login dengan pesan sukses
        return redirect()->to('/login')->with('pesan', 'Registrasi berhasil! Silakan login dengan akun baru Anda.');
    }
}