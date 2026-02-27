<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KomikModel; // Wajib dipanggil
use App\Models\UserModel;  // Wajib dipanggil

class Dashboard extends BaseController
{
    protected $komikModel;
    protected $userModel;
    protected $db;

    // Fungsi ini akan dijalankan pertama kali saat Dashboard dipanggil
    public function __construct()
    {
        $this->komikModel = new KomikModel();
        $this->userModel  = new UserModel();
        $this->db         = \Config\Database::connect();
    }

    public function index()
    {
        // 1. Tangkap parameter pencarian dan status
        $keyword = $this->request->getGet('keyword');
        $status  = $this->request->getGet('status');

        // 2. Terapkan filter ke Query Builder
        $this->komikModel->orderBy('created_at', 'DESC');
        
        if (!empty($keyword)) {
            $this->komikModel->like('title', $keyword);
        }
        if (!empty($status)) {
            $this->komikModel->where('status', $status);
        }

        // Ambil data setelah difilter (Bisa juga dilimit jika ini dashboard, misal limit(10))
        $komikTerbaru = $this->komikModel->findAll();

        // 3. Looping untuk mengambil Author seperti di halaman Kelola Komik
        $komikAuthors = [];
        foreach ($komikTerbaru as $k) {
            $authors = $this->db->table('tb_komik_authors')
                                ->join('tb_authors', 'tb_authors.author_id = tb_komik_authors.author_id')
                                ->where('komik_id', $k->komik_id)
                                ->get()->getResultObject();
            $komikAuthors[$k->komik_id] = $authors;
        }

$data = [
            'title'         => 'Dashboard Admin',
            'total_komik'   => $this->komikModel->countAllResults(), 
            'total_user'    => $this->userModel->countAllResults(),
            
            'komik_terbaru' => $komikTerbaru,
            'komikAuthors'  => $komikAuthors, 
            'keyword'       => $keyword,
            'status'        => $status
        ];

        return view('admin/dashboard', $data);
    }
}