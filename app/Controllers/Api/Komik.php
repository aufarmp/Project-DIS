<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class Komik extends ResourceController
{
    use ResponseTrait;

    protected $modelName = 'App\Models\KomikModel';
    protected $format    = 'json';

    // 1. GET: Menampilkan semua data komik, dengan fitur Search, Popular, & Genre (Endpoint: /api/komik)
    public function index()
    {
        $db = \Config\Database::connect(); // Butuh instance DB untuk query manual
        
        $keyword = $this->request->getVar('keyword'); // ?keyword=naruto
        $type    = $this->request->getVar('type');    // ?type=popular
        $genre   = $this->request->getVar('genre');   // ?genre=action

        $data = [];

        // Logika 1: Menampilkan Komik Populer
        if ($type === 'popular') {
            $data = $this->model->select('tb_komik.*, COUNT(tb_bookmarks.komik_id) as bookmark_count')
                                ->join('tb_bookmarks', 'tb_bookmarks.komik_id = tb_komik.komik_id', 'left')
                                ->groupBy('tb_komik.komik_id')
                                ->orderBy('bookmark_count', 'DESC')
                                ->orderBy('tb_komik.created_at', 'DESC')
                                ->limit(10) // Limit seperti di versi web
                                ->findAll();
        } 
        // Logika 2: Menampilkan Filter Berdasarkan Genre
        else if (!empty($genre)) {
            $data = $this->model->select('tb_komik.*')
                                ->join('tb_komik_genres', 'tb_komik_genres.komik_id = tb_komik.komik_id')
                                ->join('tb_genres', 'tb_genres.genre_id = tb_komik_genres.genre_id')
                                ->where('tb_genres.slug', $genre)
                                ->findAll();
        } 
        // Logika 3: Pencarian Berdasarkan Keyword (Search)
        else if ($keyword) {
            $data = $this->model->like('title', $keyword)->findAll();
        } 
        // Logika 4: Tampilkan Semua Komik secara default
        else {
            $data = $this->model->findAll();
        }
        
        if ($data) {
            return $this->respond([
                'status'  => 200,
                'message' => 'Data komik berhasil diambil',
                'data'    => $data
            ], 200);
        }

        return $this->failNotFound('Belum ada data komik yang sesuai.');
    }

    // [BARU] GET: Endpoint khusus untuk mengambil daftar seluruh Genre (Endpoint: /api/komik/genres)
    public function genres()
    {
        $db = \Config\Database::connect();
        $allGenres = $db->table('tb_genres')->get()->getResultArray();

        if ($allGenres) {
             return $this->respond([
                'status'  => 200,
                'message' => 'Daftar genre berhasil diambil',
                'data'    => $allGenres
            ], 200);
        }

        return $this->failNotFound('Belum ada data genre.');
    }

    // 2. GET: Menampilkan 1 komik spesifik (Endpoint: /api/komik/id)
    public function show($id = null)
    {
        $komik = $this->model->find($id);
        
        if ($komik) {
            $data = $komik->toArray();
            
            $data['authors'] = $this->model->getAuthors($id);
            $data['genres']  = $this->model->getGenres($id);
            
            $db = \Config\Database::connect();
            $data['chapters'] = $db->table('tb_chapters')
                                   ->where('komik_id', $id)
                                   ->orderBy('chapter_number', 'DESC')
                                   ->get()->getResultArray();

            // Logika Pengecekan Bookmark
            $userId = $this->request->getVar('user_id');
            $isBookmarked = false;

            if ($userId) {
                $cekBookmark = $db->table('tb_bookmarks')
                                  ->where(['user_id' => $userId, 'komik_id' => $id])
                                  ->get()->getRow();
                
                if ($cekBookmark) {
                    $isBookmarked = true;
                }
            }
            // Masukkan status bookmark ke dalam response data
            $data['is_bookmarked'] = $isBookmarked;

            return $this->respond([
                'status'  => 200,
                'message' => 'Detail komik berhasil ditemukan',
                'data'    => $data
            ], 200);
        }

        return $this->failNotFound('Komik dengan ID ' . $id . ' tidak ditemukan.');
    }

    // 3. POST: Menambahkan komik baru (Endpoint: /api/komik)
    public function create()
    {
        // Tangkap data dari body raw JSON
        $json = $this->request->getJSON();

        if (!$json) {
            return $this->fail('Format data harus berupa valid JSON', 400);
        }

        // Siapkan data sesuai struktur tabel
        $data = [
            'title'       => $json->title ?? null,
            'slug'        => isset($json->title) ? url_title($json->title, '-', true) : null,
            'description' => $json->description ?? null,
            'cover_image' => $json->cover_image ?? 'default.jpg',
            'status'      => $json->status ?? 'ongoing',
            'created_at'  => date('Y-m-d H:i:s')
        ];

        // Validasi wajib isi
        $rules = [
            'title'  => 'required',
            'status' => 'required|in_list[ongoing,completed,hiatus]'
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        // Simpan ke database
        $this->model->insert($data);
        $data['komik_id'] = $this->model->getInsertID();

        return $this->respondCreated([
            'status'  => 201,
            'message' => 'Komik berhasil ditambahkan via API',
            'data'    => $data
        ]);
    }

    // 4. PUT/PATCH: Mengubah data komik (Endpoint: /api/komik/id)
    public function update($id = null)
    {
        $cekData = $this->model->find($id);
        if (!$cekData) {
            return $this->failNotFound('Komik dengan ID ' . $id . ' tidak ditemukan untuk diupdate.');
        }

        // Tangkap data JSON dari request PUT
        $json = $this->request->getJSON();

        if (!$json) {
            return $this->fail('Format data harus berupa valid JSON', 400);
        }

        // Validasi untuk update agar data tidak hancur jika dikirim kosong
        $rules = [];
        if (isset($json->title)) {
            $rules['title'] = 'required';
        }
        if (isset($json->status)) {
            $rules['status'] = 'in_list[ongoing,completed,hiatus]';
        }

        if (!empty($rules) && !$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $data = [
            'title'       => $json->title ?? $cekData->title,
            'description' => $json->description ?? $cekData->description,
            'cover_image' => $json->cover_image ?? $cekData->cover_image,
            'status'      => $json->status ?? $cekData->status,
            'updated_at'  => date('Y-m-d H:i:s')
        ];

        // Update slug otomatis jika title ikut diubah
        if (isset($json->title)) {
            $data['slug'] = url_title($json->title, '-', true);
        }

        $this->model->update($id, $data);

        return $this->respond([
            'status'  => 200,
            'message' => 'Komik berhasil diperbarui via API',
            'data'    => $data
        ], 200);
    }

    // 5. DELETE: Menghapus data komik (Endpoint: /api/komik/id)
    public function delete($id = null)
    {
        $cekData = $this->model->find($id);
        
        if ($cekData) {
            $this->model->delete($id);
            return $this->respondDeleted([
                'status'  => 200,
                'message' => 'Komik dengan ID ' . $id . ' berhasil dihapus.'
            ]);
        }

        return $this->failNotFound('Komik dengan ID ' . $id . ' tidak ditemukan.');
    }
}