<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Administrador
        User::updateOrCreate(
            ['email' => 'admin@sigap.com'],
            [
                'name' => 'Administrador',
                'apellido' => 'SIGAP',
                'telefono' => '1234567890',
                'fecha_de_nacimiento' => '1985-05-15',
                'rol' => 'Administrador',
                'estado' => 'Activo',
                'password' => bcrypt('password123'), // Contraseña: password123
            ]
        );

        // Recepcionista
        User::updateOrCreate(
            ['email' => 'recepcion@sigap.com'],
            [
                'name' => 'Carlos',
                'apellido' => 'García',
                'telefono' => '0987654321',
                'fecha_de_nacimiento' => '1990-10-20',
                'rol' => 'Recepcionista',
                'estado' => 'Activo',
                'password' => bcrypt('password123'), // Contraseña: password123
            ]
        );
    }
}
