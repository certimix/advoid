<?php

namespace Database\Seeders;

use App\Models\Escritorio;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $escritorio = Escritorio::firstOrCreate(
            ['nome' => 'Escritório Advoid'],
            ['cidade' => 'Paripiranga', 'uf' => 'BA', 'ativo' => true]
        );

        User::firstOrCreate(
            ['email' => 'admin@advoid.test'],
            [
                'escritorio_id' => $escritorio->id,
                'name'          => 'Administrador',
                'password'      => Hash::make('password'),
                'cargo'         => 'admin',
                'ativo'         => true,
            ]
        );
    }
}
