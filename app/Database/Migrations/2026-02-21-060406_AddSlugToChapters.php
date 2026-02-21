<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSlugToChapters extends Migration
{
    public function up()
    {
        // Menambahkan kolom 'slug' ke tabel 'tb_chapters'
        $fields = [
            'slug' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'title' // Meletakkan kolom slug setelah kolom title
            ],
        ];
        
        $this->forge->addColumn('tb_chapters', $fields);
    }

    public function down()
    {
        // Menghapus kolom 'slug' jika migration di-rollback
        $this->forge->dropColumn('tb_chapters', 'slug');
    }
}