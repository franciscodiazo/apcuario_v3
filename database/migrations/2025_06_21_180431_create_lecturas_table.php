<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLecturasTable extends Migration
{
    public function up()
    {
        Schema::create('lecturas', function (Blueprint $table) {
            $table->id();
            $table->string('matricula');
            $table->string('numero_serie')->nullable();
            $table->year('anio');
            $table->unsignedTinyInteger('ciclo');
            $table->date('fecha');
            $table->integer('lectura_actual');      // <-- Campo faltante
            $table->integer('lectura_anterior')->nullable(); // <-- Campo faltante
            $table->integer('consumo_m3');
            $table->timestamps();

            $table->foreign('matricula')->references('matricula')->on('usuarios')->onDelete('cascade');
            $table->foreign('numero_serie')->references('numero_serie')->on('medidors')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('lecturas');
    }
}