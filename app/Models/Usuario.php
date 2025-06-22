<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;

    protected $fillable = [
        'matricula',
        'documento',
        'apellidos',
        'nombres',
        'correo',
        'estrato',
        'celular',
        'sector',
        'no_personas',
        'direccion',
    ];

    public function medidores()
    {
        return $this->hasMany(Medidors::class, 'matricula', 'matricula');
    }

    public function lecturas()
    {
        return $this->hasMany(Lectura::class, 'matricula', 'matricula');
    }

    // Relación en Usuario para créditos
    public function creditos() {
        return $this->hasMany(\App\Models\Credito::class);
    }
}