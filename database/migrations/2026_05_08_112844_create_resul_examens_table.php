<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resul_examens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_orden')->nullable();
            $table->string('nombre_paciente');
            $table->string('nombre_examen');
            $table->text('observaciones')->nullable();
            $table->string('imagen_path')->nullable(); // ruta al archivo adjunto
            $table->string('bioanalista')->nullable();
            $table->timestamp('fecha_resultado')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resul_examens');
    }
};
