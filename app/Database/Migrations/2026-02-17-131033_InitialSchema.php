<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class InitialSchema extends Migration
{
    public function up()
    {
        // 1. Tabel User
        $this->forge->addField([
            'user_id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'username'         => ['type' => 'VARCHAR', 'constraint' => 100],
            'email'            => ['type' => 'VARCHAR', 'constraint' => 100, 'unique' => true],
            'password'         => ['type' => 'VARCHAR', 'constraint' => 255],
            'role'             => ['type' => 'ENUM', 'constraint' => ['admin', 'user'], 'default' => 'user'],
            'profile_picture'  => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at'       => ['type' => 'DATETIME', 'null' => true],
            'updated_at'       => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'       => ['type' => 'DATETIME', 'null' => true], // Soft Delete
        ]);
        $this->forge->addKey('user_id', true);
        $this->forge->createTable('tb_users');

        // 2. Tabel Author (BARU)
        $this->forge->addField([
            'author_id'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'name'        => ['type' => 'VARCHAR', 'constraint' => 100],
        ]);
        $this->forge->addKey('author_id', true);
        $this->forge->createTable('tb_authors');

        // 3. Tabel Genre
        $this->forge->addField([
            'genre_id'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'name'        => ['type' => 'VARCHAR', 'constraint' => 100],
            'slug'        => ['type' => 'VARCHAR', 'constraint' => 100], // SEO Friendly
        ]);
        $this->forge->addKey('genre_id', true);
        $this->forge->createTable('tb_genres');

        // 4. Tabel Komik (Core Project)
        $this->forge->addField([
            'komik_id'        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'title'           => ['type' => 'VARCHAR', 'constraint' => 255],
            'slug'            => ['type' => 'VARCHAR', 'constraint' => 255], // SEO Friendly
            'description'     => ['type' => 'TEXT', 'null' => true],
            'cover_image'     => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'status'          => ['type' => 'ENUM', 'constraint' => ['ongoing', 'completed', 'hiatus'], 'default' => 'ongoing'],
            'created_at'      => ['type' => 'DATETIME', 'null' => true],
            'updated_at'      => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'      => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('komik_id', true);
        $this->forge->createTable('tb_komik');

        // 5. Tabel Pivot: Komik_Authors (BARU - Relasi Many-to-Many)
        $this->forge->addField([
            'komik_id'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'author_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'role'      => ['type' => 'ENUM', 'constraint' => ['story', 'art', 'all'], 'default' => 'all'], // Upgrade: Bisa bedakan penulis cerita & gambar
        ]);
        $this->forge->addForeignKey('komik_id', 'tb_komik', 'komik_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('author_id', 'tb_authors', 'author_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tb_komik_authors');

        // 6. Tabel Pivot: Komik_Genres (Relasi Many-to-Many)
        $this->forge->addField([
            'komik_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'genre_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
        ]);
        $this->forge->addForeignKey('komik_id', 'tb_komik', 'komik_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('genre_id', 'tb_genres', 'genre_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tb_komik_genres');

        // 7. Tabel Chapters
        $this->forge->addField([
            'chapter_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'komik_id'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'chapter_number' => ['type' => 'FLOAT', 'constraint' => 10], // Float agar bisa chapter 1.5 (extra)
            'title'          => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'release_date'   => ['type' => 'DATE', 'null' => true],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('chapter_id', true);
        $this->forge->addForeignKey('komik_id', 'tb_komik', 'komik_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tb_chapters');

        // 8. Tabel Pages (Isi Chapter)
        $this->forge->addField([
            'page_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'chapter_id'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'page_number' => ['type' => 'INT', 'constraint' => 5],
            'image_url'   => ['type' => 'VARCHAR', 'constraint' => 255],
        ]);
        $this->forge->addKey('page_id', true);
        $this->forge->addForeignKey('chapter_id', 'tb_chapters', 'chapter_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tb_pages');

        // 9. Tabel Bookmarks (User menyimpan komik)
        $this->forge->addField([
            'user_id'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'komik_id'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addForeignKey('user_id', 'tb_users', 'user_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('komik_id', 'tb_komik', 'komik_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tb_bookmarks');

        // 10. Tabel Reading History (Terakhir baca)
        $this->forge->addField([
            'history_id'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'chapter_id'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'last_read_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('history_id', true);
        $this->forge->addForeignKey('user_id', 'tb_users', 'user_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('chapter_id', 'tb_chapters', 'chapter_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tb_reading_history');
    }

    public function down()
    {
        // Hapus urutan terbalik (Child dulu baru Parent)
        $this->forge->dropTable('tb_reading_history');
        $this->forge->dropTable('tb_bookmarks');
        $this->forge->dropTable('tb_pages');
        $this->forge->dropTable('tb_chapters');
        $this->forge->dropTable('tb_komik_genres');
        $this->forge->dropTable('tb_komik_authors');
        $this->forge->dropTable('tb_komik');
        $this->forge->dropTable('tb_genres');
        $this->forge->dropTable('tb_authors');
        $this->forge->dropTable('tb_users');
    }
}