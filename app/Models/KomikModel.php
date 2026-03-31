<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Komik;

class KomikModel extends Model
{
    protected $table            = 'tb_komik';
    protected $primaryKey       = 'komik_id';
    protected $useAutoIncrement = true;
    protected $returnType       = Komik::class;
    protected $useSoftDeletes   = true; 
    
    // Kolom yang boleh diisi lewat form
    protected $allowedFields    = [
        'title', 'slug', 'description', 'cover_image', 'status'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Ambil Author beserta Role-nya (Story/Art)
    public function getAuthors($komik_id)
    {
        return $this->db->table('tb_komik_authors')
            ->join('tb_authors', 'tb_authors.author_id = tb_komik_authors.author_id')
            ->where('tb_komik_authors.komik_id', $komik_id)
            ->get()->getResultArray();
    }

    // Ambil Genre
    public function getGenres($komik_id)
    {
        return $this->db->table('tb_komik_genres')
            ->join('tb_genres', 'tb_genres.genre_id = tb_komik_genres.genre_id')
            ->where('tb_komik_genres.komik_id', $komik_id)
            ->get()->getResultArray();
    }
}