<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'username'   => 'admin',
                'email'      => 'admin@comic.id',
                'password'   => password_hash('Admin123', PASSWORD_DEFAULT),
                'role'       => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username'   => 'user',
                'email'      => 'user@comic.id',
                'password'   => password_hash('User123', PASSWORD_DEFAULT),
                'role'       => 'user',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        ];

        // Insert Batch
        $this->db->table('tb_users')->insertBatch($data);
    }
}