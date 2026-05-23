<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $table = 'empresas';

    protected $fillable = [
        'user_id',
        'nombre',
        'rfc',
        'sector',
        'telefono',
        'direccion',
        'contacto_nombre',
        'contacto_email',
        'activa',
    ];

    protected $casts = [
        'activa' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function vacantes()
    {
        return $this->hasMany(Vacante::class, 'empresa_id');
    }
}
