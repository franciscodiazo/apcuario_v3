<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('lecturas', function (Blueprint $table) {
            $table->boolean('pagado')->default(false)->after('consumo_m3');
            $table->string('metodo_pago')->nullable()->after('pagado');
            $table->timestamp('fecha_pago')->nullable()->after('metodo_pago');
        });
    }
    public function down()
    {
        Schema::table('lecturas', function (Blueprint $table) {
            $table->dropColumn('pagado');
            $table->dropColumn('metodo_pago');
            $table->dropColumn('fecha_pago');
        });
    }
};
