<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AuthorSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['name' => 'Eiichiro Oda'],
            ['name' => 'Masashi Kishimoto'],
            ['name' => 'ONE'],
            ['name' => 'Yusuke Murata'],
            ['name' => 'Saka Mikami'],
            ['name' => 'Katarina'],
            ['name' => 'Ryosuke Fuji'],
            ['name' => 'Kohei Horikoshi'],
            ['name' => 'Gege Akutami'],
            ['name' => 'Kei Urana'],
            ['name' => 'Tite Kubo'],
            ['name' => 'Hajime Isayama'],
            ['name' => 'Yuki Tabata'],
            ['name' => 'Koyoharu Gotouge'],
            ['name' => 'Haro Aso']
        ];

        $this->db->table('tb_authors')->insertBatch($data);
    }
}