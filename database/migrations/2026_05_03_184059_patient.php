<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pacientes', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('nombre');
            $blueprint->string('apellido');
            $blueprint->string('cedula')->unique(); 
            $blueprint->date('fecha_nacimiento')->nullable();
            $blueprint->string('telefono')->nullable();
            $blueprint->string('email')->unique();
            $blueprint->timestamps(); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pacientes');
    }
};
