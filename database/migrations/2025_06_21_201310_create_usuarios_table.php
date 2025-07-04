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
    Schema::create('usuarios', function (Blueprint $table) {
        $table->id();
        $table->string('nome');
        $table->string('email')->unique();
        $table->string('senha');
        $table->enum('tipo', ['aluno', 'professor', 'administrador']);
        $table->string('curso')->nullable();         // Aluno
        $table->string('departamento')->nullable();  // Professor
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
