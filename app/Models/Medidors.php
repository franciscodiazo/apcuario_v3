<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medidors extends Model
{
    use HasFactory;

    protected $table = 'medidors'; // Debe coincidir con el nombre de la tabla

    protected $fillable = [
        'matricula',
        'numero_serie',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'matricula', 'matricula');
    }
}