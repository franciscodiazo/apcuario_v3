<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('matricula')->unique();
            $table->string('documento')->unique();
            $table->string('apellidos');
            $table->string('nombres');
            $table->string('correo')->nullable();
            $table->string('estrato')->nullable();
            $table->string('celular')->nullable();
            $table->string('sector')->nullable();
            $table->integer('no_personas')->nullable();
            $table->string('direccion');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};