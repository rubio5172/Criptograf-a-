<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vacante extends Model
{
    protected $table = 'vacantes';

    protected $fillable = [
        'empresa_id',
        'empresa_nombre',
        'titulo',
        'descripcion',
        'requisitos',
        'horario',
        'ubicacion',
        'cupo_maximo',
        'limite_registros',
        'activa',
        'cerrada_manualmente',
    ];

    protected $casts = [
        'activa' => 'boolean',
        'cerrada_manualmente' => 'boolean',
    ];

    public function solicitudes()
    {
        return $this->hasMany(Solicitud::class, 'vacante_id');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function solicitudesAceptadas()
    {
        return $this->hasMany(Solicitud::class, 'vacante_id')
            ->where('estatus', 'aceptado');
    }

    public function solicitudesConfirmadas()
    {
        return $this->hasMany(Solicitud::class, 'vacante_id')
            ->where('estatus', 'aceptado')
            ->where('confirmada_por_alumno', true);
    }

    public function scopeAbiertas($query)
    {
        return $query->where('activa', true)
            ->where('cerrada_manualmente', false);
    }

    public function getSolicitudesRegistradasAttribute(): int
    {
        if (array_key_exists('solicitudes_count', $this->attributes)) {
            return (int) $this->attributes['solicitudes_count'];
        }

        return $this->solicitudes()->count();
    }

    public function getSolicitudesAceptadasCountAttribute(): int
    {
        if (array_key_exists('solicitudes_aceptadas_count', $this->attributes)) {
            return (int) $this->attributes['solicitudes_aceptadas_count'];
        }

        return $this->solicitudesAceptadas()->count();
    }

    public function getSolicitudesConfirmadasCountAttribute(): int
    {
        if (array_key_exists('solicitudes_confirmadas_count', $this->attributes)) {
            return (int) $this->attributes['solicitudes_confirmadas_count'];
        }

        return $this->solicitudesConfirmadas()->count();
    }

    public function estaCerradaManualmente(): bool
    {
        return (bool) ($this->cerrada_manualmente || !$this->activa);
    }

    public function estaCerrada(): bool
    {
        return $this->estaCerradaManualmente()
            || $this->solicitudes_registradas >= $this->limite_registros
            || $this->solicitudes_aceptadas_count >= $this->cupo_maximo;
    }

    public function estaDisponible(): bool
    {
        return !$this->estaCerrada();
    }
}
