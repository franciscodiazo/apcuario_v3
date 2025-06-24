<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    // Seguridad: Solo permitir asignación masiva de campos seguros
    protected $fillable = [
        'name', 'email', 'password', 'telefono', 'direccion', 'estado', 'rol_id',
    ];

    // Seguridad: Ocultar atributos sensibles
    protected $hidden = [
        'password', 'remember_token',
    ];

    // Seguridad: Cast de atributos
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relación con roles
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

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