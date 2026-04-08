<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // "name" already exists, representing "nombre"
            $table->string('apellido')->nullable()->after('name');
            $table->string('telefono')->nullable()->after('apellido');
            $table->date('fecha_de_nacimiento')->nullable()->after('telefono');
            $table->string('rol')->default('paciente')->after('fecha_de_nacimiento'); // admin, recept, bio, paciente
            $table->string('estado')->default('Activo')->after('rol'); // Activo, Inactivo
            $table->unsignedBigInteger('id_empresa')->nullable()->after('estado');

            $table->foreign('id_empresa')->references('id_empresa')->on('empresas')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['id_empresa']);
            $table->dropColumn(['apellido', 'telefono', 'fecha_de_nacimiento', 'id_empresa']);
        });
    }
};
