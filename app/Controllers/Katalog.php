<?php

namespace App\Controllers;

use App\Models\KomikModel;

class Katalog extends BaseController
{
    protected $komikModel;

    public function __construct()
    {
        $this->komikModel = new KomikModel();
    }

    // 1. Halaman Pencarian
    public function search()
    {
        $keyword = $this->request->getVar('q'); // Mengambil data dari ?q=keyword
        
        // Nanti logika pencarian database ditaruh di sini
        // $komik = $this->komikModel->like('title', $keyword)->findAll();

        $data = [
            'title'   => 'Cari Komik',
            'keyword' => $keyword,
            'komik'   => [] // Kosongkan sementara sampai CRUD komik jalan
        ];

        return view('user/search', $data);
    }

    // 2. Halaman Populer
    public function popular()
    {
        $data = [
            'title'           => 'Komik Terpopuler',
            'page_title'      => 'Top 100 Populer',
            'is_popular_page' => true,
            'komik'           => [] // Data dummy/kosong
        ];

        return view('user/catalog', $data);
    }

    // 3. Halaman Genres
    public function genres()
    {
        $data = [
            'title'         => 'Eksplorasi Genre',
            'page_title'    => 'Jelajahi Berdasarkan Genre',
            'is_genre_page' => true,
            'komik'         => [] // Data dummy/kosong
        ];

        return view('user/catalog', $data);
    }
}