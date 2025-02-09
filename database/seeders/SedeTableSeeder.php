<?php

namespace Database\Seeders;

use App\Models\Sede;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SedeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Sede::create([
            "nombre" => "LA PAZ",
        ]);
        Sede::create([
            "nombre" => "COCHABAMBA",
        ]);
        Sede::create([
            "nombre" => "SANTA CRUZ",
        ]);
        Sede::create([
            "nombre" => "CHUQUISACA",
        ]);
        Sede::create([
            "nombre" => "ORURO",
        ]);
        Sede::create([
            "nombre" => "POTOSÍ",
        ]);
        Sede::create([
            "nombre" => "TARIJA",
        ]);
        Sede::create([
            "nombre" => "BENI",
        ]);
        Sede::create([
            "nombre" => "PANDO",
        ]);
    }
}
