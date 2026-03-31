<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class User extends ResourceController
{
    use ResponseTrait;
    protected $format = 'json';

    // 1. POST: Toggle Bookmark (Tambah/Hapus Bookmark)
    // Endpoint: /api/user/bookmark
    public function toggleBookmark()
    {
        $json = $this->request->getJSON();
        $userId = $json->user_id ?? null;
        $komikId = $json->komik_id ?? null;

        if (!$userId || !$komikId) {
            return $this->fail('Data user_id dan komik_id tidak lengkap', 400);
        }

        $db = \Config\Database::connect();
        $builder = $db->table('tb_bookmarks');
        
        // Cek apakah komik sudah dibookmark
        $cek = $builder->where(['user_id' => $userId, 'komik_id' => $komikId])->get()->getRow();

        if ($cek) {
            // Jika sudah ada, maka HAPUS (Un-bookmark)
            $builder->where(['user_id' => $userId, 'komik_id' => $komikId])->delete();
            return $this->respond([
                'status' => 200, 
                'message' => 'Komik dihapus dari Library', 
                'is_bookmarked' => false
            ]);
        } else {
            // Jika belum ada, maka TAMBAH (Bookmark)
            // PASTIKAN created_at diisi agar MySQL tidak menolak insert
            $builder->insert([
                'user_id' => $userId,
                'komik_id' => $komikId,
                'created_at' => date('Y-m-d H:i:s') 
            ]);
            return $this->respond([
                'status' => 200, 
                'message' => 'Komik ditambahkan ke Library', 
                'is_bookmarked' => true
            ]);
        }
    }

    // 2. GET: Mengambil daftar komik yang dibookmark
    // Endpoint: /api/user/library/(:num)
    public function library($userId = null)
    {
        if (!$userId) return $this->fail('User ID diperlukan');

        $db = \Config\Database::connect();
        
        // Kita join tb_bookmarks dengan tb_komik agar Flutter mendapatkan gambar cover dan judul
        $data = $db->table('tb_bookmarks')
                   ->select('tb_komik.*')
                   ->join('tb_komik', 'tb_komik.komik_id = tb_bookmarks.komik_id')
                   ->where('tb_bookmarks.user_id', $userId)
                   ->orderBy('tb_bookmarks.created_at', 'DESC')
                   ->get()->getResultArray();

        return $this->respond([
            'status' => 200,
            'message' => 'Library berhasil diambil',
            'data' => $data
        ]);
    }

    // 3. GET: Mengambil riwayat bacaan user
    // Endpoint: /api/user/history/(:num)
    public function history($userId = null)
    {
        if (!$userId) return $this->fail('User ID diperlukan');

        $db = \Config\Database::connect();
        
        // Join 3 tabel: History, Chapter (untuk nomor chapter), Komik (untuk cover & judul)
        $data = $db->table('tb_reading_history')
                   ->select('
                        tb_reading_history.*, 
                        tb_komik.title as komik_title, 
                        tb_komik.cover_image, 
                        tb_chapters.chapter_number
                   ')
                   ->join('tb_chapters', 'tb_chapters.chapter_id = tb_reading_history.chapter_id')
                   ->join('tb_komik', 'tb_komik.komik_id = tb_chapters.komik_id')
                   ->where('tb_reading_history.user_id', $userId)
                   ->orderBy('tb_reading_history.last_read_at', 'DESC')
                   ->get()->getResultArray();

        return $this->respond([
            'status' => 200,
            'message' => 'Riwayat bacaan berhasil diambil',
            'data' => $data
        ]);
    }
}