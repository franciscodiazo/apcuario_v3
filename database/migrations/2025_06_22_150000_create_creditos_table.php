<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('creditos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id');
            $table->string('matricula');
            $table->decimal('valor', 12, 2);
            $table->decimal('saldo', 12, 2);
            $table->string('estado')->default('pendiente'); // pendiente, abonado, cancelado
            $table->timestamps();
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
        });
    }
    public function down() {
        Schema::dropIfExists('creditos');
    }
};
