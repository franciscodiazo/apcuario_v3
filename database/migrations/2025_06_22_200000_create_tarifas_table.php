<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTarifasTable extends Migration
{
    public function up()
    {
        Schema::create('tarifas', function (Blueprint $table) {
            $table->id();
            $table->year('anio')->unique();
            $table->integer('basico'); // Valor básico anual en pesos
            $table->integer('adicional_m3'); // Valor adicional por m3 en pesos
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tarifas');
    }
}
