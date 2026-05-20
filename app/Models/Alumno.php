<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    use HasFactory;

    protected $table = 'alumnos';

    protected $fillable = [
        'carrera_id',
        'nombre',
        'ap_pat',
        'ap_mat',
        'matricula',
        'telefono',
        'semestre',
        'promedio',
    ];

    //Un Alumno pertenece a una Carrera
    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'carrera_id');
    }
}