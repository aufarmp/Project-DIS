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
        $loginId  = $this->request->getVar('login_id'); // Bisa username atau email
        $password = $this->request->getVar('password');

        // Cari user berdasarkan Username ATAU Email
        $user = $model->where('username', $loginId)
                      ->orWhere('email', $loginId)
                      ->first();

        if ($user) {
            // Verifikasi Password (Hash)
            if (password_verify($password, $user['password'])) {
                
                // Set Session Data
                $ses_data = [
                    'user_id'    => $user['user_id'],
                    'username'   => $user['username'],
                    'role'       => $user['role'],
                    'isLoggedIn' => TRUE
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
}