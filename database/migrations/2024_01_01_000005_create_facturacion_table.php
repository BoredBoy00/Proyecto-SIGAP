<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facturacion', function (Blueprint $table) {
            $table->id('id_facturacion');
            $table->unsignedBigInteger('id_orden');
            $table->dateTime('fecha_emision');
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('monto_descuento', 10, 2)->default(0);
            $table->decimal('monto_total', 10, 2)->default(0);
            $table->string('tipo_pago')->nullable(); // Changed to match "tipo_pago" from diagram ER
            $table->string('estatus')->default('pendiente');
            
            $table->foreign('id_orden')->references('id_orden')->on('ordenes')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facturacion');
    }
};
