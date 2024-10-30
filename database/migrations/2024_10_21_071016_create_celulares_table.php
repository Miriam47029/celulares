<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('celulares', function (Blueprint $table) {
            $table->id();
            $table->string('modelo',50);
            $table->string('descripcion',150);
            $table->decimal('precio',8, 2);
            $table->foreignId('marca_id')->constrained('marcas')->onUpdate('cascade')->onDelete('restrict');
            $table->integer('camara');
            $table->string('foto',100);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('celulares');
    }
};
