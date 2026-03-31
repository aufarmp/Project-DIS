<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Cors implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Mengizinkan semua origin (domain/port) untuk mengakses API
        header('Access-Control-Allow-Origin: *');
        
        // Mengizinkan header tertentu yang sering dipakai (termasuk JSON)
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization");
        
        // Mengizinkan metode HTTP yang kita pakai di API
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        
        // Menangani "Preflight Request" dari browser (OPTIONS method)
        $method = $_SERVER['REQUEST_METHOD'] ?? '';
        if ($method == "OPTIONS") {
            // Jika browser hanya mengecek izin (OPTIONS), langsung hentikan dan beri status OK
            header("HTTP/1.1 200 OK");
            die();
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        
    }
}