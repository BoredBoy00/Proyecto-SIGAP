<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalles', function (Blueprint $table) {
            $table->id('id_detalles');
            $table->unsignedBigInteger('id_orden');
            $table->unsignedBigInteger('id_examen');
            
            $table->foreign('id_orden')->references('id_orden')->on('ordenes')->onDelete('cascade');
            $table->foreign('id_examen')->references('id_examen')->on('examenes')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalles');
    }
};
