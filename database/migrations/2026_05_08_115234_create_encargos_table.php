<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('encargos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_orden')->nullable();
            $table->string('nombre_paciente');
            $table->string('examenes'); // JSON o texto con lista de exámenes
            $table->string('estado')->default('Pendiente'); // Pendiente, En proceso, Completado
            $table->string('creado_por')->nullable(); // recepcionista que creó la factura
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('encargos');
    }
};
