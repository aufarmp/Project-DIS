<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class GenreSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['name' => 'Action', 'slug' => 'action'],
            ['name' => 'Adventure', 'slug' => 'adventure'],
            ['name' => 'Romance', 'slug' => 'romance'],
            ['name' => 'Fantasy', 'slug' => 'fantasy'],
            ['name' => 'Horror', 'slug' => 'horror'],
            ['name' => 'Comedy', 'slug' => 'comedy'],
            ['name' => 'Slice of Life', 'slug' => 'slice-of-life'],
            ['name' => 'Sci-Fi', 'slug' => 'sci-fi'],
            ['name' => 'Mystery', 'slug' => 'mystery'],
            ['name' => 'Thriller', 'slug' => 'thriller'],
            ['name' => 'Supernatural', 'slug' => 'supernatural'],
            ['name' => 'Historical', 'slug' => 'historical'],
            ['name' => 'Sports', 'slug' => 'sports'],
            ['name' => 'Mecha', 'slug' => 'mecha'],
            ['name' => 'Psychological', 'slug' => 'psychological'],
            ['name' => 'Shounen', 'slug' => 'shounen'],
            ['name' => 'Shoujo', 'slug' => 'shoujo'],
            ['name' => 'Seinen', 'slug' => 'seinen'],
            ['name' => 'Josei', 'slug' => 'josei'],
            ['name' => 'Isekai', 'slug' => 'isekai']
        ];

        $this->db->table('tb_genres')->insertBatch($data);
    }
}