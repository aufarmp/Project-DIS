<?php

namespace App\Controllers\User;

use App\Models\KomikModel;
use App\Controllers\BaseController;

class Katalog extends BaseController
{
    protected $komikModel;
    protected $db;

    public function __construct()
    {
        $this->komikModel = new KomikModel();
        $this->db         = \Config\Database::connect();
    }

    // 1. Halaman Pencarian
    public function search()
    {
        $keyword = $this->request->getVar('q'); 
        
        $komik = [];
        if (!empty($keyword)) {
            $komik = $this->komikModel->like('title', $keyword)->findAll();
        }

        $data = [
            'title'   => 'Cari Komik',
            'keyword' => $keyword,
            'komik'   => $komik
        ];

        return view('user/search', $data);
    }

    // 2. Halaman Populer
    public function popular()
    {        
        // Ambil data komik beserta jumlah bookmark-nya, urutkan dari yang paling banyak di-bookmark
        $komikPopuler = $this->komikModel->select('tb_komik.*, COUNT(tb_bookmarks.komik_id) as bookmark_count')
                                         ->join('tb_bookmarks', 'tb_bookmarks.komik_id = tb_komik.komik_id', 'left')
                                         ->groupBy('tb_komik.komik_id')
                                         ->orderBy('bookmark_count', 'DESC')
                                         ->orderBy('tb_komik.created_at', 'DESC')
                                         ->limit(10)
                                         ->find();

        $data = [
            'title'           => 'Komik Terpopuler',
            'page_title'      => 'Top 10 Populer',
            'is_popular_page' => true,
            'komik'           => $komikPopuler 
        ];

        return view('user/catalog', $data);
    }

    // 3. Halaman Genres
    public function genres()
    {
        // Ambil semua daftar genre untuk ditampilkan sebagai tombol filter (misal: Action, Romance)
        $allGenres = $this->db->table('tb_genres')->get()->getResultObject();
        
        // Cek apakah user sedang menekan filter genre tertentu di URL (misal: ?g=action)
        $selectedGenreSlug = $this->request->getVar('g');
        
        if (!empty($selectedGenreSlug)) {
            // Jika memilih genre, ambil komik yang cocok melalui relasi tabel pivot tb_komik_genres
            $komik = $this->komikModel->select('tb_komik.*')
                                      ->join('tb_komik_genres', 'tb_komik_genres.komik_id = tb_komik.komik_id')
                                      ->join('tb_genres', 'tb_genres.genre_id = tb_komik_genres.genre_id')
                                      ->where('tb_genres.slug', $selectedGenreSlug)
                                      ->findAll();
        } else {
            // Jika tidak ada genre yang diklik, tampilkan semua komik
            $komik = $this->komikModel->findAll();
        }

        $data = [
            'title'          => 'Eksplorasi Genre',
            'page_title'     => 'Jelajahi Berdasarkan Genre',
            'is_genre_page'  => true,
            'genres'         => $allGenres,        // Kirim list genre ke view
            'selected_genre' => $selectedGenreSlug, // Kirim genre yang sedang aktif
            'komik'          => $komik
        ];

        return view('user/catalog', $data);
    }

    // 4. Halaman Detail Komik (beserta logika penahan akses untuk non-login user)
    public function detail($slug)
    {
        $komik = $this->komikModel->where('slug', $slug)->first();

        if (!$komik) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Komik tidak ditemukan.");
        }

        // Ambil data Author
        $authors = $this->db->table('tb_komik_authors')
                            ->select('tb_authors.name, tb_komik_authors.role')
                            ->join('tb_authors', 'tb_authors.author_id = tb_komik_authors.author_id')
                            ->where('komik_id', $komik->komik_id)
                            ->get()->getResultObject();

        // Ambil data Genre
        $genres = $this->db->table('tb_komik_genres')
                           ->select('tb_genres.name')
                           ->join('tb_genres', 'tb_genres.genre_id = tb_komik_genres.genre_id')
                           ->where('komik_id', $komik->komik_id)
                           ->get()->getResultObject();

        // Ambil Daftar Chapter
        $chapters = $this->db->table('tb_chapters')
                             ->where('komik_id', $komik->komik_id)
                             ->orderBy('chapter_number', 'DESC')
                             ->get()->getResultObject();

        // Cek Status Login User
        $isLoggedIn = session()->get('isLoggedIn') ?? false;
        
        // --- TAMBAHAN LOGIKA BOOKMARK ---
        $isBookmarked = false;
        if ($isLoggedIn) {
            $userId = session()->get('user_id');
            $cekBookmark = $this->db->table('tb_bookmarks')
                                    ->where(['user_id' => $userId, 'komik_id' => $komik->komik_id])
                                    ->get()->getRow();
            if ($cekBookmark) {
                $isBookmarked = true; // Jika data ditemukan, berarti sudah di-bookmark
            }
        }

        $data = [
            'title'        => $komik->title . ' - Comi.id',
            'komik'        => $komik,
            'authors'      => $authors,
            'genres'       => $genres,
            'chapters'     => $chapters,
            'isLoggedIn'   => $isLoggedIn,
            'isBookmarked' => $isBookmarked
        ];

        return view('user/detail', $data); 
    }

    // 5. Halaman Membaca Komik (Reader)
    public function read($komikSlug, $chapterSlug)
    {
        // 1. Pengecekan Login Ekstra (Amankan backend)
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu untuk membaca.');
        }

        // 2. Ambil data komik
        $komik = $this->komikModel->where('slug', $komikSlug)->first();
        if (!$komik) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Komik tidak ditemukan.");

        // 3. Ambil data chapter saat ini
        $chapter = $this->db->table('tb_chapters')
                            ->where('komik_id', $komik->komik_id)
                            ->where('slug', $chapterSlug)
                            ->get()->getRow();
        if (!$chapter) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Chapter tidak ditemukan.");

        // 4. Ambil halaman gambar dari tb_pages
        $pages = $this->db->table('tb_pages')
                          ->where('chapter_id', $chapter->chapter_id)
                          // Ubah 'page_number' sesuai nama kolom urutan halaman di tabel Anda jika berbeda
                          ->orderBy('page_number', 'ASC') 
                          ->get()->getResultObject();

        // 5. MENCATAT RIWAYAT BACAAN (HISTORY)
        $userId = session()->get('user_id');
        $cekHistory = $this->db->table('tb_reading_history')
                               ->where(['user_id' => $userId, 'chapter_id' => $chapter->chapter_id])
                               ->get()->getRow();

        if ($cekHistory) {
            // Jika pernah baca, update waktu terakhir bacanya
            $this->db->table('tb_reading_history')
                     ->where('history_id', $cekHistory->history_id)
                     ->update(['last_read_at' => date('Y-m-d H:i:s')]);
        } else {
            // Jika baru pertama kali baca chapter ini, tambahkan ke riwayat
            $this->db->table('tb_reading_history')->insert([
                'user_id'      => $userId,
                'chapter_id'   => $chapter->chapter_id,
                'last_read_at' => date('Y-m-d H:i:s')
            ]);
        }

        // 6. Logika Tombol Prev & Next Chapter
        $prevChapter = $this->db->table('tb_chapters')
                                ->where('komik_id', $komik->komik_id)
                                ->where('chapter_number <', $chapter->chapter_number)
                                ->orderBy('chapter_number', 'DESC')
                                ->limit(1)->get()->getRow();

        $nextChapter = $this->db->table('tb_chapters')
                                ->where('komik_id', $komik->komik_id)
                                ->where('chapter_number >', $chapter->chapter_number)
                                ->orderBy('chapter_number', 'ASC')
                                ->limit(1)->get()->getRow();

        $data = [
            'title'       => 'Baca ' . $komik->title . ' - Chapter ' . $chapter->chapter_number,
            'komik'       => $komik,
            'chapter'     => $chapter,
            'pages'       => $pages,
            'prevChapter' => $prevChapter,
            'nextChapter' => $nextChapter
        ];

        return view('user/reader', $data);
    }
}