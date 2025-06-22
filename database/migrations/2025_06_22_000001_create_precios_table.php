<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('precios', function (Blueprint $table) {
            $table->id();
            $table->integer('anio');
            $table->decimal('costo_base', 10, 2)->default(22000);
            $table->integer('limite_base')->default(50);
            $table->decimal('costo_adicional', 10, 2)->default(2500);
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('precios');
    }
};
