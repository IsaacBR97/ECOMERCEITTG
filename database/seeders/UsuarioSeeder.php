<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        Usuario::create([
            'nombre'=>"contador",
            'rol'=>"Contador",
            'password'=>Hash::make('elcontador'),
           'cash'=>0
        ]);
        Usuario::create([
            'nombre'=>"cliente",
            'rol'=>"Cliente",
            'password'=>Hash::make('12345678'),
           'cash'=>0
        ]);
    }
}
