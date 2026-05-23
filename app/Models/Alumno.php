<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    use HasFactory;

    protected $table = 'alumnos';

    protected $fillable = [
        'user_id',
        'carrera_id',
        'nombre',
        'ap_pat',
        'ap_mat',
        'matricula',
        'telefono',
        'semestre',
        'promedio',
    ];

    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'carrera_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function solicitudes()
    {
        return $this->hasMany(Solicitud::class, 'alumno_id');
    }
}
