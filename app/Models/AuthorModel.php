<?php

namespace App\Models;

use CodeIgniter\Model;

class AuthorModel extends Model
{
    protected $table            = 'tb_authors';
    protected $primaryKey       = 'author_id';
    protected $allowedFields    = ['name'];
    protected $useTimestamps    = false;
}