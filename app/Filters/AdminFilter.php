<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // 1. Cek apakah user memiliki session 'isLoggedIn'
        if (!session()->get('isLoggedIn')) {
            // Jika belum login, tendang ke halaman login
            return redirect()->to('/login')->with('error', 'Akses ditolak. Silakan login terlebih dahulu.');
        }

        // 2. Cek apakah role user tersebut adalah 'admin'
        if (session()->get('role') !== 'admin') {
            // Jika dia login tapi sebagai user biasa, tendang ke halaman beranda/home
            return redirect()->to('/')->with('error', 'Akses ditolak. Halaman ini khusus Administrator.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}