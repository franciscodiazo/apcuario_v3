<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Precio extends Model
{
    use HasFactory;
    protected $fillable = [
        'anio', 'costo_base', 'limite_base', 'costo_adicional'
    ];
}
