<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Credito extends Model
{
    use HasFactory;
    protected $fillable = [
        'usuario_id', 'matricula', 'valor', 'saldo', 'estado', 'fecha', 'acuerdo', 'detalle'
    ];
    public function usuario() {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
