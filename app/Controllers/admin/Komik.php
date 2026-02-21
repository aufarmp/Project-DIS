<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KomikModel;
use App\Models\AuthorModel;

class Komik extends BaseController
{
    protected $komikModel;
    protected $authorModel;
    protected $db;

    public function __construct()
    {
        $this->komikModel  = new KomikModel();
        $this->authorModel = new AuthorModel();
        $this->db          = \Config\Database::connect();
    }

    public function index()
    {
        // 1. Tangkap parameter filter
        $keyword = $this->request->getGet('keyword');
        $status  = $this->request->getGet('status');

        $this->komikModel->orderBy('created_at', 'DESC');

        // 2. Eksekusi filter pencarian dan status
        if (!empty($keyword)) {
            $this->komikModel->like('title', $keyword);
        }
        if (!empty($status)) {
            $this->komikModel->where('status', $status);
        }

        // 3. Ambil data komik
        $komikData = $this->komikModel->findAll();

        $komikAuthors = [];

        // 4. Looping untuk mencari author
        foreach ($komikData as $k) {
            $authors = $this->db->table('tb_komik_authors')
                                ->join('tb_authors', 'tb_authors.author_id = tb_komik_authors.author_id')
                                ->where('komik_id', $k->komik_id)
                                ->get()->getResultObject();
                                
            $komikAuthors[$k->komik_id] = $authors;
        }

        $data = [
            'title'        => 'Kelola Komik - Admin',
            'komik'        => $komikData,
            'komikAuthors' => $komikAuthors,
            // Lempar parameter kembali ke view
            'keyword'      => $keyword,
            'status'       => $status
        ];

        return view('admin/komik/index', $data);
    }

    public function create()
    {
        $data = [
            'title'   => 'Tambah Komik Baru',
            'authors' => $this->authorModel->findAll(),
            'genres'  => $this->db->table('tb_genres')->get()->getResultArray() 
        ];
        return view('admin/komik/create', $data);
    }

    public function save()
    {
        // A. Validasi (is_image bisa digunakan lagi karena FileInfo sudah aktif)
        $aturan = [
            'title' => [
                'rules'  => 'required|is_unique[tb_komik.title]',
                'errors' => [
                    'required'  => 'Judul komik harus diisi.',
                    'is_unique' => 'Judul komik sudah terdaftar.'
                ]
            ],
            'cover_image' => [
                'rules'  => 'uploaded[cover_image]|max_size[cover_image,2048]|is_image[cover_image]|ext_in[cover_image,jpg,jpeg,png]',
                'errors' => [
                    'uploaded' => 'Pilih gambar cover terlebih dahulu.',
                    'max_size' => 'Ukuran gambar maksimal 2MB.',
                    'is_image' => 'File yang dipilih bukan gambar.',
                    'ext_in'   => 'Format gambar harus JPG/PNG.'
                ]
            ]
        ];

        if (!$this->validate($aturan)) {
            return redirect()->to('/admin/komik/create')->withInput()->with('errors', $this->validator->getErrors());
        }

        // B. Buat Slug (Untuk nama Folder & Database)
        $judul = $this->request->getVar('title');
        $slug  = url_title($judul, '-', true);

        // C. Upload Gambar & Buat Folder Dinamis (Sesuai request Anda sebelumnya)
        $fileCover = $this->request->getFile('cover_image');
        $folderPath = FCPATH . 'assets/comics/' . $slug;

        if (!is_dir($folderPath)) {
            mkdir($folderPath, 0777, true);
        }

        $ext = $fileCover->getExtension();
        $namaCover = $slug . '_cover.' . $ext;
        $fileCover->move('assets/comics/' . $slug, $namaCover);
        $pathDatabase = $slug . '/' . $namaCover;

        // D. Simpan ke Tabel Utama (tb_komik)
        $this->komikModel->insert([
            'title'       => $judul,
            'slug'        => $slug,
            'description' => $this->request->getVar('description'),
            'cover_image' => $pathDatabase, 
            'status'      => $this->request->getVar('status')
        ]);

        $komikId = $this->komikModel->getInsertID();

        // E. Simpan Relasi Author (Mendukung 2 Author / Form Baru)
        $authorStory = $this->request->getVar('author_story');
        $authorArt   = $this->request->getVar('author_art');

        if ($authorStory) {
            $roleStory = ($authorStory == $authorArt || empty($authorArt)) ? 'all' : 'story';
            $this->db->table('tb_komik_authors')->insert([
                'komik_id'  => $komikId,
                'author_id' => $authorStory,
                'role'      => $roleStory
            ]);
        }

        if (!empty($authorArt) && $authorArt != $authorStory) {
            $this->db->table('tb_komik_authors')->insert([
                'komik_id'  => $komikId,
                'author_id' => $authorArt,
                'role'      => 'art'
            ]);
        }

        // F. Simpan Relasi Genre
        $genres = $this->request->getVar('genres'); 
        if ($genres) {
            $genreData = [];
            foreach ($genres as $genreId) {
                $genreData[] = [
                    'komik_id' => $komikId,
                    'genre_id' => $genreId
                ];
            }
            $this->db->table('tb_komik_genres')->insertBatch($genreData);
        }

        session()->setFlashdata('pesan', 'Komik "' . $judul . '" berhasil ditambahkan beserta foldernya!');
        return redirect()->to('/admin/komik');
    }

    // Menampilkan form edit
    public function edit($id)
    {
        $komikData = $this->komikModel->find($id);

        if (!$komikData) {
            return redirect()->to('/admin/komik')->with('error', 'Komik tidak ditemukan.');
        }

        // A. Ambil Author yang sedang terpilih dari tb_komik_authors
        $currentAuthorsDb = $this->db->table('tb_komik_authors')->where('komik_id', $id)->get()->getResultArray();
        $currentStoryId = '';
        $currentArtId   = '';
        
        foreach($currentAuthorsDb as $ca) {
            if ($ca['role'] == 'story') $currentStoryId = $ca['author_id'];
            if ($ca['role'] == 'art')   $currentArtId   = $ca['author_id'];
            if ($ca['role'] == 'all') {
                $currentStoryId = $ca['author_id'];
                $currentArtId   = $ca['author_id'];
            }
        }

        // B. Ambil Genre yang sedang terpilih dari tb_komik_genres
        $currentGenresDb = $this->db->table('tb_komik_genres')->where('komik_id', $id)->get()->getResultArray();
        $currentGenres   = array_column($currentGenresDb, 'genre_id'); // Menghasilkan array simpel misal [1, 3, 5]

        $data = [
            'title'          => 'Edit Komik',
            'komik'          => $komikData,
            'authors'        => $this->authorModel->findAll(),
            'genres'         => $this->db->table('tb_genres')->get()->getResultArray(),
            'currentStoryId' => $currentStoryId,
            'currentArtId'   => $currentArtId,
            'currentGenres'  => $currentGenres
        ];

        return view('admin/komik/edit', $data);
    }

    // Memproses data update
    public function update($id)
    {
        // Pastikan komik ada
        $komikLama = $this->komikModel->find($id);
        if (!$komikLama) {
            return redirect()->to('/admin/komik')->with('error', 'Komik tidak ditemukan.');
        }

        $pkName = $this->komikModel->primaryKey;
        $tableName = $this->komikModel->table;

        // A. Validasi
        $aturan = [
            'title' => [
                'rules'  => "required|is_unique[{$tableName}.title,{$pkName},{$id}]",
                'errors' => [
                    'required'  => 'Judul komik harus diisi.',
                    'is_unique' => 'Judul komik sudah terdaftar.'
                ]
            ],
            // cover_image TIDAK diwajibkan upload, hanya dicek jika diupload
            'cover_image' => [
                'rules'  => 'max_size[cover_image,2048]|is_image[cover_image]|ext_in[cover_image,jpg,jpeg,png]',
                'errors' => [
                    'max_size' => 'Ukuran gambar maksimal 2MB.',
                    'is_image' => 'File yang dipilih bukan gambar.',
                    'ext_in'   => 'Format gambar harus JPG/PNG.'
                ]
            ]
        ];

        if (!$this->validate($aturan)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // B. Siapkan Variabel Dasar
        $judul = $this->request->getPost('title');
        $slug  = url_title($judul, '-', true);
        
        // C. Manajemen Folder & File Image
        $fileCover = $this->request->getFile('cover_image');
        
        if ($fileCover->getError() == 4) {
            // TIDAK ADA GAMBAR BARU: Tetap gunakan path yang lama dari database
            $pathDatabase = $komikLama->cover_image; 
        } else {
            // ADA GAMBAR BARU DARI ADMIN
            $folderPath = FCPATH . 'assets/comics/' . $slug;

            // Buat folder jika belum ada (misal slug/judul diganti total)
            if (!is_dir($folderPath)) {
                mkdir($folderPath, 0777, true);
            }

            // Hapus gambar fisik yang lama (jika ada)
            if ($komikLama->cover_image && file_exists(FCPATH . 'assets/comics/' . $komikLama->cover_image)) {
                unlink(FCPATH . 'assets/comics/' . $komikLama->cover_image);
            }

            // Simpan gambar baru
            $ext = $fileCover->getExtension();
            $namaCover = $slug . '_cover_' . time() . '.' . $ext; // Tambah time() agar nama unik (menghindari cache browser)
            $fileCover->move('assets/comics/' . $slug, $namaCover);
            
            $pathDatabase = $slug . '/' . $namaCover;
        }

        // D. Update Tabel Utama (tb_komik)
        $this->komikModel->update($id, [
            'title'       => $judul,
            'slug'        => $slug,
            'description' => $this->request->getPost('description'),
            'cover_image' => $pathDatabase, 
            'status'      => $this->request->getPost('status')
        ]);

        // E. Perbarui Relasi Author (Sapu bersih lalu insert ulang)
        $this->db->table('tb_komik_authors')->where('komik_id', $id)->delete();
        
        $authorStory = $this->request->getPost('author_story');
        $authorArt   = $this->request->getPost('author_art');

        if ($authorStory) {
            $roleStory = ($authorStory == $authorArt || empty($authorArt)) ? 'all' : 'story';
            $this->db->table('tb_komik_authors')->insert([
                'komik_id'  => $id,
                'author_id' => $authorStory,
                'role'      => $roleStory
            ]);
        }
        if (!empty($authorArt) && $authorArt != $authorStory) {
            $this->db->table('tb_komik_authors')->insert([
                'komik_id'  => $id,
                'author_id' => $authorArt,
                'role'      => 'art'
            ]);
        }

        // F. Perbarui Relasi Genre (Sapu bersih lalu insert ulang)
        $this->db->table('tb_komik_genres')->where('komik_id', $id)->delete();
        
        $genres = $this->request->getPost('genres'); 
        if ($genres) {
            $genreData = [];
            foreach ($genres as $genreId) {
                $genreData[] = [
                    'komik_id' => $id,
                    'genre_id' => $genreId
                ];
            }
            $this->db->table('tb_komik_genres')->insertBatch($genreData);
        }

        session()->setFlashdata('pesan', 'Komik "' . $judul . '" beserta relasinya berhasil diperbarui!');
        return redirect()->to('/admin/komik');
    }

    // Method untuk menghapus komik
    public function delete($id)
    {
        // 1. Cari data komik berdasarkan ID
        $komik = $this->komikModel->find($id);

        if (!$komik) {
            return redirect()->to('/admin/komik')->with('error', 'Komik tidak ditemukan.');
        }

        // 2. Hapus file gambar cover dan foldernya (Merapikan penyimpanan)
        if ($komik->cover_image && $komik->cover_image != 'default.jpg') {
            $imagePath = FCPATH . 'assets/comics/' . $komik->cover_image;
            $folderPath = FCPATH . 'assets/comics/' . $komik->slug;

            // Hapus file gambarnya
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            
            // Coba hapus foldernya (hanya akan berhasil jika foldernya sudah kosong)
            if (is_dir($folderPath) && count(scandir($folderPath)) == 2) { 
                rmdir($folderPath);
            }
        }

        // 3. Hapus relasi komik di tabel author dan genre
        $this->db->table('tb_komik_authors')->where('komik_id', $id)->delete();
        $this->db->table('tb_komik_genres')->where('komik_id', $id)->delete();

        // 4. Hapus data komik dari database secara permanen (Hard Delete)
        $this->komikModel->delete($id, true);

        // Set pesan sukses dan kembalikan ke halaman daftar
        session()->setFlashdata('pesan', 'Komik "' . $komik->title . '" berhasil dihapus secara permanen beserta filenya.');
        return redirect()->to('/admin/komik');
    }

}