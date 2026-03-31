<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class Chapter extends ResourceController
{
    use ResponseTrait;

    protected $format = 'json';

    public function show($id = null)
    {
        $db = \Config\Database::connect();

        // 1. Ambil data chapter saat ini
        $chapter = $db->table('tb_chapters')->where('chapter_id', $id)->get()->getRowObject();
        if (!$chapter) {
            return $this->failNotFound('Chapter tidak ditemukan.');
        }

        // 2. Ambil data komik (opsional, berguna agar Flutter bisa menampilkan judul komik di AppBar)
        $komik = $db->table('tb_komik')->where('komik_id', $chapter->komik_id)->get()->getRowObject();

        // 3. Ambil halaman gambar dari tb_pages
        $pages = $db->table('tb_pages')
                    ->where('chapter_id', $id)
                    ->orderBy('page_number', 'ASC')
                    ->get()->getResultObject();

        // 4. MENCATAT RIWAYAT BACAAN (Jika user sedang login di Flutter)
        // Flutter akan mengirim parameter '?user_id=1' di URL saat nge-fetch API ini
        $userId = $this->request->getVar('user_id');
        
        if ($userId) {
            $cekHistory = $db->table('tb_reading_history')
                             ->where(['user_id' => $userId, 'chapter_id' => $id])
                             ->get()->getRow();

            if ($cekHistory) {
                // Update waktu terakhir baca
                $db->table('tb_reading_history')
                   ->where('history_id', $cekHistory->history_id)
                   ->update(['last_read_at' => date('Y-m-d H:i:s')]);
            } else {
                // Tambahkan riwayat baru
                $db->table('tb_reading_history')->insert([
                    'user_id'      => $userId,
                    'chapter_id'   => $id,
                    'last_read_at' => date('Y-m-d H:i:s')
                ]);
            }
        }

        // 5. Logika Tombol Prev & Next Chapter
        $prevChapter = $db->table('tb_chapters')
                          ->where('komik_id', $chapter->komik_id)
                          ->where('chapter_number <', $chapter->chapter_number)
                          ->orderBy('chapter_number', 'DESC')
                          ->limit(1)->get()->getRowObject();

        $nextChapter = $db->table('tb_chapters')
                          ->where('komik_id', $chapter->komik_id)
                          ->where('chapter_number >', $chapter->chapter_number)
                          ->orderBy('chapter_number', 'ASC')
                          ->limit(1)->get()->getRowObject();

        return $this->respond([
            'status'  => 200,
            'message' => 'Data chapter berhasil diambil',
            'data'    => [
                'komik'       => $komik,
                'chapter'     => $chapter,
                'pages'       => $pages,
                'prevChapter' => $prevChapter,
                'nextChapter' => $nextChapter
            ]
        ], 200);
    }
}