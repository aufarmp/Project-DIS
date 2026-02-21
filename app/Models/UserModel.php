<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'tb_users';
    protected $primaryKey       = 'user_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array'; // Return sebagai array agar mudah dikelola di session
    protected $useSoftDeletes   = true;    // Mengaktifkan fitur deleted_at

    // Kolom yang diizinkan untuk diisi (Security)
    protected $allowedFields    = [
        'username', 'email', 'password', 'role', 'profile_picture'
    ];

    // Mengaktifkan otomatisasi pengisian created_at dan updated_at
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}