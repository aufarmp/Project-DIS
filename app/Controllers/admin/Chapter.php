<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KomikModel;

class Chapter extends BaseController
{
    protected $komikModel;
    protected $db;

    public function __construct()
    {
        $this->komikModel = new KomikModel();
        $this->db         = \Config\Database::connect();
    }

    public function index()
    {
        $keyword = $this->request->getGet('keyword');

        $this->komikModel->orderBy('created_at', 'DESC');
        if (!empty($keyword)) {
            $this->komikModel->like('title', $keyword);
        }
        $komikData = $this->komikModel->findAll();

        $chapterCounts = [];
        foreach ($komikData as $k) {
            $chapterCounts[$k->komik_id] = $this->db->table('tb_chapters')
                                                    ->where('komik_id', $k->komik_id)
                                                    ->countAllResults();
        }

        $data = [
            'title'         => 'Kelola Chapter Komik',
            'komik'         => $komikData,
            'chapterCounts' => $chapterCounts,
            'keyword'       => $keyword
        ];

        return view('admin/chapter/index', $data);
    }

    public function addChapter($komik_id)
    {
        $komik = $this->komikModel->find($komik_id);
        if (!$komik) {
            return redirect()->to('/admin/chapter')->with('error', 'Komik tidak ditemukan.');
        }

        $data = [
            'title' => 'Tambah Chapter - ' . $komik->title,
            'komik' => $komik
        ];

        // Ubah pemanggilan view ke folder chapter
        return view('admin/chapter/add_chapter', $data); 
    }

    public function saveChapter($komik_id)
    {
        $komik = $this->komikModel->find($komik_id);
        if (!$komik) return redirect()->to('/admin/chapter');

        $rules = [
            'chapter_number' => 'required|numeric',
            'title'          => 'permit_empty|string',
            'pages'          => 'uploaded[pages]' 
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $chapterNumber = $this->request->getPost('chapter_number');
        $chapterTitle  = $this->request->getPost('title');
        
        $chapterSlug = 'chapter-' . str_replace('.', '-', $chapterNumber);
        
        $this->db->table('tb_chapters')->insert([
            'komik_id'       => $komik_id,
            'chapter_number' => $chapterNumber,
            'title'          => $chapterTitle,
            'slug'           => $chapterSlug,
            'created_at'     => date('Y-m-d H:i:s')
        ]);
        $chapterId = $this->db->insertID(); 

        $files = $this->request->getFileMultiple('pages');
        
        if ($files) {
            usort($files, function ($a, $b) {
                return strnatcasecmp($a->getClientName(), $b->getClientName());
            });
        }

        $komikSlug = $komik->slug;

        $folderPath = FCPATH . "assets/comics/{$komikSlug}/{$chapterSlug}";

        if (!is_dir($folderPath)) {
            mkdir($folderPath, 0777, true);
        }

        $pageNumber = 1;
        $pagesData = []; 

        foreach ($files as $file) {
            if ($file->isValid() && !$file->hasMoved()) {
                $ext = $file->getExtension();
                $fileName = "{$komikSlug}-{$chapterSlug}-page-{$pageNumber}.{$ext}";
                $file->move($folderPath, $fileName);
                $dbPath = "{$komikSlug}/{$chapterSlug}/{$fileName}";

                $pagesData[] = [
                    'chapter_id'  => $chapterId,
                    'page_number' => $pageNumber,
                    'image_url'   => $dbPath 
                ];

                $pageNumber++;
            }
        }

        if (!empty($pagesData)) {
            $this->db->table('tb_pages')->insertBatch($pagesData);
        }

        // Redirect kembali ke halaman Kelola Chapter
        return redirect()->to('/admin/chapter')->with('pesan', 'Chapter ' . $chapterNumber . ' untuk komik "' . $komik->title . '" berhasil ditambahkan dengan ' . ($pageNumber - 1) . ' halaman!');
    }

    // Menampilkan daftar chapter untuk 1 komik tertentu
    public function list($komik_id)
    {
        $komik = $this->komikModel->find($komik_id);
        if (!$komik) {
            return redirect()->to('/admin/chapter')->with('error', 'Komik tidak ditemukan.');
        }

        // Ambil semua chapter komik ini dan urutkan dari chapter terbesar ke terkecil
        $chapters = $this->db->table('tb_chapters')
                             ->where('komik_id', $komik_id)
                             ->orderBy('chapter_number', 'DESC')
                             ->get()->getResultObject();

        $data = [
            'title'    => 'Daftar Chapter - ' . $komik->title,
            'komik'    => $komik,
            'chapters' => $chapters
        ];

        return view('admin/chapter/list', $data);
    }

    // --- FITUR KELOLA PAGES ---
    // 1. Menampilkan Galeri Halaman
    public function edit($chapter_id)
    {
        // Ambil data chapter
        $chapter = $this->db->table('tb_chapters')->where('chapter_id', $chapter_id)->get()->getRowObject();
        if (!$chapter) return redirect()->to('/admin/chapter')->with('error', 'Chapter tidak ditemukan.');

        // Ambil data komik
        $komik = $this->komikModel->find($chapter->komik_id);

        // Ambil semua halaman diurutkan dari nomor terkecil ke terbesar
        $pages = $this->db->table('tb_pages')
                          ->where('chapter_id', $chapter_id)
                          ->orderBy('page_number', 'ASC')
                          ->get()->getResultObject();

        $data = [
            'title'   => 'Kelola Pages - Chapter ' . $chapter->chapter_number,
            'komik'   => $komik,
            'chapter' => $chapter,
            'pages'   => $pages
        ];

        return view('admin/chapter/edit', $data);
    }

    // 2. Mengganti 1 Gambar Spesifik
    public function updatePage($page_id)
    {
        $page = $this->db->table('tb_pages')->where('page_id', $page_id)->get()->getRowObject();
        if (!$page) return redirect()->back()->with('error', 'Halaman tidak ditemukan.');

        $chapter = $this->db->table('tb_chapters')->where('chapter_id', $page->chapter_id)->get()->getRowObject();
        $komik   = $this->komikModel->find($chapter->komik_id);

        $rules = [
            'new_page' => 'uploaded[new_page]|max_size[new_page,2048]|is_image[new_page]|ext_in[new_page,jpg,jpeg,png,webp]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', 'File tidak valid. Pastikan format JPG/PNG/WEBP dan maksimal 2MB.');
        }

        $file = $this->request->getFile('new_page');
        
        // A. Hapus file fisik gambar yang lama
        if (file_exists(FCPATH . 'assets/comics/' . $page->image_url)) {
            unlink(FCPATH . 'assets/comics/' . $page->image_url);
        }

        // B. Upload file baru
        $ext = $file->getExtension();
        // Tambahkan time() agar browser tidak menampilkan cache gambar lama
        $fileName = "{$komik->slug}-{$chapter->slug}-page-{$page->page_number}-rev-" . time() . ".{$ext}"; 
        $folderPath = FCPATH . "assets/comics/{$komik->slug}/{$chapter->slug}";
        
        $file->move($folderPath, $fileName);
        $dbPath = "{$komik->slug}/{$chapter->slug}/{$fileName}";

        // C. Update database
        $this->db->table('tb_pages')->where('page_id', $page_id)->update(['image_url' => $dbPath]);

        return redirect()->back()->with('pesan', 'Halaman ' . $page->page_number . ' berhasil diganti dengan gambar baru!');
    }

    // 3. Menghapus 1 Gambar Spesifik
    public function deletePage($page_id)
    {
        $page = $this->db->table('tb_pages')->where('page_id', $page_id)->get()->getRowObject();
        if (!$page) return redirect()->back()->with('error', 'Halaman tidak ditemukan.');

        // Hapus file fisik
        if (file_exists(FCPATH . 'assets/comics/' . $page->image_url)) {
            unlink(FCPATH . 'assets/comics/' . $page->image_url);
        }

        // Hapus dari database
        $this->db->table('tb_pages')->where('page_id', $page_id)->delete();

        return redirect()->back()->with('pesan', 'Halaman ' . $page->page_number . ' berhasil dihapus!');
    }

    // 4. Menambahkan Halaman Susulan ke Chapter yang Sudah Ada
    public function addPages($chapter_id)
    {
        $chapter = $this->db->table('tb_chapters')->where('chapter_id', $chapter_id)->get()->getRowObject();
        if (!$chapter) return redirect()->back()->with('error', 'Chapter tidak ditemukan.');

        $komik = $this->komikModel->find($chapter->komik_id);

        $rules = [
            'pages' => 'uploaded[pages]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', 'Pilih minimal 1 gambar untuk ditambahkan.');
        }

        $files = $this->request->getFileMultiple('pages');

        // Urutkan file secara natural (seperti perbaikan sebelumnya)
        if ($files) {
            usort($files, function ($a, $b) {
                return strnatcasecmp($a->getClientName(), $b->getClientName());
            });
        }

        // Cari nomor halaman (page_number) tertinggi saat ini di chapter ini
        $maxPageQuery = $this->db->table('tb_pages')
                                 ->where('chapter_id', $chapter_id)
                                 ->selectMax('page_number')
                                 ->get()->getRowObject();
        
        // Jika sudah ada halaman, lanjutkan dari angka terakhir. Jika kosong, mulai dari 1.
        $startPageNumber = ($maxPageQuery && $maxPageQuery->page_number) ? $maxPageQuery->page_number + 1 : 1;

        $komikSlug = $komik->slug;
        $chapterSlug = $chapter->slug;
        $folderPath = FCPATH . "assets/comics/{$komikSlug}/{$chapterSlug}";

        if (!is_dir($folderPath)) {
            mkdir($folderPath, 0777, true);
        }

        $pageNumber = $startPageNumber;
        $pagesData = []; 

        foreach ($files as $file) {
            if ($file->isValid() && !$file->hasMoved()) {
                $ext = $file->getExtension();
                $fileName = "{$komikSlug}-{$chapterSlug}-page-{$pageNumber}.{$ext}";
                
                $file->move($folderPath, $fileName);
                $dbPath = "{$komikSlug}/{$chapterSlug}/{$fileName}";

                $pagesData[] = [
                    'chapter_id'  => $chapter_id,
                    'page_number' => $pageNumber,
                    'image_url'   => $dbPath 
                ];

                $pageNumber++;
            }
        }

        if (!empty($pagesData)) {
            $this->db->table('tb_pages')->insertBatch($pagesData);
        }

        $totalAdded = $pageNumber - $startPageNumber;
        return redirect()->back()->with('pesan', "Berhasil menambahkan {$totalAdded} halaman baru di akhir chapter!");
    }
}