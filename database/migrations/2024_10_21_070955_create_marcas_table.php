<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marcas', function (Blueprint $table) {
            $table->id();
            $table->string('marca', 50);  // Verifica que el nombre de la columna sea 'marca' y no 'nombre'
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marcas');
    }
};