<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lectura extends Model
{
    use HasFactory;

    protected $fillable = [
        'matricula',
        'numero_serie',
        'anio',
        'ciclo',
        'fecha',
        'lectura_actual',
        'lectura_anterior',
        'consumo_m3',
        'pagado',
        'metodo_pago',
        'fecha_pago',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'matricula', 'matricula');
    }

    public function medidor()
    {
        return $this->belongsTo(Medidors::class, 'numero_serie', 'numero_serie');
    }

    public function ultimasLecturas()
    {
        return $this->hasMany(Lectura::class, 'matricula', 'matricula')
            ->orderByDesc('anio')
            ->orderByDesc('ciclo')
            ->limit(3);
    }
}