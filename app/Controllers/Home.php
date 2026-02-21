<?php

namespace App\Controllers;

use App\Models\KomikModel; // Wajib dipanggil

class Home extends BaseController
{
    public function index()
    {
        $komikModel = new KomikModel();
        
        $data = [
            'title' => 'Home - Comi',
            'komik' => $komikModel->orderBy('created_at', 'DESC')->findAll(10) 
        ];
        
        return view('user/home', $data);
    }
}