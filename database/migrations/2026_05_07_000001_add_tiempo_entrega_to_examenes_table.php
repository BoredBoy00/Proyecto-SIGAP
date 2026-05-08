<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('examenes', function (Blueprint $table) {
            $table->string('tiempo_entrega')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('examenes', function (Blueprint $table) {
            $table->dropColumn('tiempo_entrega');
        });
    }
};
