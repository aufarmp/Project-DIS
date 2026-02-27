<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\KomikModel;

class Komik extends ResourceController
{
    use ResponseTrait;

    protected $modelName = 'App\Models\KomikModel';
    protected $format    = 'json';

    // 1. GET: Menampilkan semua data komik (Endpoint: /api/komik)
    public function index()
    {
        $data = $this->model->findAll();
        
        if ($data) {
            return $this->respond([
                'status'  => 200,
                'message' => 'Data komik berhasil diambil',
                'data'    => $data
            ], 200);
        }

        return $this->failNotFound('Belum ada data komik.');
    }

    // 2. GET: Menampilkan 1 komik spesifik (Endpoint: /api/komik/id)
    public function show($id = null)
    {
        $data = $this->model->find($id);
        
        if ($data) {
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
        // Tangkap data dari body raw JSON di Postman
        $json = $this->request->getJSON();

        if (!$json) {
            return $this->fail('Format data harus berupa valid JSON', 400);
        }

        // Siapkan data sesuai struktur tabel tb_komik
        $data = [
            'title'       => $json->title ?? null,
            'slug'        => isset($json->title) ? url_title($json->title, '-', true) : null,
            'description' => $json->description ?? null,
            'cover_image' => $json->cover_image ?? 'default.jpg', // Berikan default jika kosong
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