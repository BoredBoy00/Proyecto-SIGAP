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
                'name' => 'Admin', 'apellido' => 'Principal', 'telefono' => '1234567890', 'fecha_de_nacimiento' => '1980-01-01',
                'rol' => 'administrador', 'estado' => 'Activo', 'password' => bcrypt('password123'), 'cedula' => '10000000',
            ]
        );

        // Bioanalista
        User::updateOrCreate(
            ['email' => 'bioanalista@sigap.com'],
            [
                'name' => 'Luis', 'apellido' => 'Pérez', 'telefono' => '0987654321', 'fecha_de_nacimiento' => '1985-05-15',
                'rol' => 'bioanalista', 'estado' => 'Activo', 'password' => bcrypt('password123'), 'cedula' => '11000000',
            ]
        );

        // Recepcionista
        User::updateOrCreate(
            ['email' => 'recepcion@sigap.com'],
            [
                'name' => 'Ana', 'apellido' => 'Gómez', 'telefono' => '0987654322', 'fecha_de_nacimiento' => '1990-10-20',
                'rol' => 'recepcionista', 'estado' => 'Activo', 'password' => bcrypt('password123'), 'cedula' => '12000000',
            ]
        );

        // Analista
        User::updateOrCreate(
            ['email' => 'analista@sigap.com'],
            [
                'name' => 'Carlos', 'apellido' => 'Ruiz', 'telefono' => '0987654323', 'fecha_de_nacimiento' => '1992-02-10',
                'rol' => 'analista', 'estado' => 'Activo', 'password' => bcrypt('password123'), 'cedula' => '13000000',
            ]
        );
    }
}
