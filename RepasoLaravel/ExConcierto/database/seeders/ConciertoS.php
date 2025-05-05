<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConciertoS extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('conciertos')->insert([
            'titulo'=>'La Momia',
            'fecha'=>'2025-03-04',
            'aforo'=>100,
            'precioEntrada'=>10
        ]);
        DB::table('conciertos')->insert([
            'titulo'=>'Jumaji',
            'fecha'=>'2025-03-05',
            'aforo'=>100,
            'precioEntrada'=>15
        ]);
    }
}
