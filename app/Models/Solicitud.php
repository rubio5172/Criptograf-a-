<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class Solicitud extends Model
{
    use HasFactory;

    public const HORAS_LIMITE_RESPUESTA = 48;

    protected $table = 'solicitudes';

    protected $fillable = [
        'alumno_id',
        'vacante_id',
        'estatus',
        'codigo_confirmacion',
        'confirmada_por_alumno',
        'fecha_limite_respuesta',
        'respondido_en',
        'cv',
        'carta',
        'historial',
        'comentario_empresa'
    ];

    protected $casts = [
        'confirmada_por_alumno' => 'boolean',
        'fecha_limite_respuesta' => 'datetime',
        'respondido_en' => 'datetime',
    ];

    public function vacante()
    {
        return $this->belongsTo(Vacante::class, 'vacante_id');
    }

    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'alumno_id');
    }

    public function scopeAceptadasPendientesDecision($query)
    {
        return $query->where('estatus', 'aceptado')
            ->where('confirmada_por_alumno', false);
    }

    public function scopeConfirmadas($query)
    {
        return $query->where('estatus', 'aceptado')
            ->where('confirmada_por_alumno', true);
    }

    public static function generarCodigoConfirmacion(): string
    {
        do {
            $codigo = strtoupper(Str::random(8));
        } while (static::where('codigo_confirmacion', $codigo)->exists());

        return $codigo;
    }

    public function puedeResponder(): bool
    {
        return $this->estatus === 'aceptado'
            && !$this->confirmada_por_alumno
            && $this->fecha_limite_respuesta
            && $this->fecha_limite_respuesta->isFuture();
    }

    public function expiroDecision(): bool
    {
        return $this->estatus === 'aceptado'
            && !$this->confirmada_por_alumno
            && $this->fecha_limite_respuesta
            && $this->fecha_limite_respuesta->isPast();
    }

    public static function expirarAceptacionesVencidas(): int
    {
        return static::aceptadasPendientesDecision()
            ->where('fecha_limite_respuesta', '<=', now())
            ->update([
                'estatus' => 'rechazado',
                'respondido_en' => now(),
            ]);
    }

    public static function fechaLimiteNuevaDecision(): Carbon
    {
        return now()->addHours(self::HORAS_LIMITE_RESPUESTA);
    }
}
