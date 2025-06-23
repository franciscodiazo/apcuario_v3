<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('creditos', function (Blueprint $table) {
            if (!Schema::hasColumn('creditos', 'fecha')) {
                $table->date('fecha')->nullable()->after('estado');
            }
            if (!Schema::hasColumn('creditos', 'acuerdo')) {
                $table->string('acuerdo')->nullable()->after('fecha');
            }
            if (!Schema::hasColumn('creditos', 'detalle')) {
                $table->string('detalle')->nullable()->after('acuerdo');
            }
        });
    }
    public function down() {
        Schema::table('creditos', function (Blueprint $table) {
            if (Schema::hasColumn('creditos', 'fecha')) {
                $table->dropColumn('fecha');
            }
            if (Schema::hasColumn('creditos', 'acuerdo')) {
                $table->dropColumn('acuerdo');
            }
            if (Schema::hasColumn('creditos', 'detalle')) {
                $table->dropColumn('detalle');
            }
        });
    }
};
