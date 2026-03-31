<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class Auth extends ResourceController
{
    use ResponseTrait;

    protected $format = 'json';

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    // 1. POST: Endpoint untuk Login (Endpoint: /api/auth/login)
    public function login()
    {
        $json = $this->request->getJSON();

        if (!$json || !isset($json->email) || !isset($json->password)) {
            return $this->fail('Email dan password wajib diisi.', 400);
        }

        $email    = $json->email;
        $password = $json->password;

        // Cari user berdasarkan email (atau sesuaikan jika menggunakan username)
        $user = $this->db->table('tb_users')->where('email', $email)->get()->getRow();

        if (!$user) {
            return $this->failNotFound('Akun dengan email tersebut tidak ditemukan.');
        }

        // Verifikasi password
        if (!password_verify($password, $user->password)) {
            return $this->failUnauthorized('Password salah.');
        }

        // Jangan pernah mengirimkan password (walaupun sudah di-hash) ke frontend!
        unset($user->password);

        return $this->respond([
            'status'  => 200,
            'message' => 'Login berhasil',
            'data'    => $user // Flutter akan menyimpan user_id dari data ini
        ], 200);
    }

    // 2. POST: Endpoint untuk Register (Endpoint: /api/auth/register)
    public function register()
    {
        $json = $this->request->getJSON();

        if (!$json) {
            return $this->fail('Format data harus berupa valid JSON', 400);
        }

        // Validasi input
        $rules = [
            'username' => 'required|min_length[3]|max_length[100]|is_unique[tb_users.username]',
            'email'    => 'required|valid_email|is_unique[tb_users.email]',
            'password' => 'required|min_length[6]'
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        // Siapkan data untuk disimpan
        $data = [
            'username'   => $json->username,
            'email'      => $json->email,
            'password'   => password_hash($json->password, PASSWORD_DEFAULT),
            'role'       => 'user', // Default role untuk pendaftar baru
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->db->table('tb_users')->insert($data);
        
        // Ambil ID user yang baru saja mendaftar
        $data['user_id'] = $this->db->insertID();
        
        // Hapus password dari response demi keamanan
        unset($data['password']);

        return $this->respondCreated([
            'status'  => 201,
            'message' => 'Registrasi berhasil. Silakan login.',
            'data'    => $data
        ]);
    }
}