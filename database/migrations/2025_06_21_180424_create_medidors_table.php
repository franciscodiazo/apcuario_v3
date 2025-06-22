<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medidors', function (Blueprint $table) {
            $table->id();
            $table->string('matricula');
            $table->string('numero_serie')->unique();
            $table->timestamps();

            $table->foreign('matricula')->references('matricula')->on('usuarios')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medidores');
    }
};