<?php

namespace EscolaLms\Files\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database Seeders.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PermissionTableSeeder::class);
    }
}