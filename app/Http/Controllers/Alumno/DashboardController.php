<?php

namespace App\Http\Controllers\Alumno;

use App\Http\Controllers\Controller;
use App\Models\Solicitud;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        Solicitud::expirarAceptacionesVencidas();

        $alumno = Auth::user()?->alumno;

        abort_unless($alumno, 403);

        $solicitudes = Solicitud::with('vacante')
            ->where('alumno_id', $alumno->id)
            ->latest()
            ->get();

        $ofertasPendientes = Solicitud::with('vacante')
            ->where('alumno_id', $alumno->id)
            ->aceptadasPendientesDecision()
            ->orderBy('fecha_limite_respuesta')
            ->get();

        $asignacionesConfirmadas = Solicitud::with('vacante')
            ->where('alumno_id', $alumno->id)
            ->confirmadas()
            ->get();

        $metricas = [
            'solicitudes_enviadas' => $solicitudes->count(),
            'ofertas_pendientes' => $ofertasPendientes->count(),
            'vacantes_confirmadas' => $asignacionesConfirmadas->count(),
            'rechazadas' => $solicitudes->where('estatus', 'rechazado')->count(),
        ];

        $solicitudesRecientes = $solicitudes->take(5);

        return view('alumno.dashboard', compact(
            'alumno',
            'ofertasPendientes',
            'asignacionesConfirmadas',
            'metricas',
            'solicitudesRecientes'
        ));
    }

    public function confirmarVacante(Request $request, int $solicitudId): RedirectResponse
    {
        Solicitud::expirarAceptacionesVencidas();

        $alumno = Auth::user()?->alumno;

        abort_unless($alumno, 403);

        $request->validate([
            'codigo_confirmacion' => 'required|string',
        ], [
            'codigo_confirmacion.required' => 'Debes ingresar tu codigo de confirmacion para aceptar la vacante.',
        ]);

        $solicitud = Solicitud::with('vacante')
            ->where('alumno_id', $alumno->id)
            ->findOrFail($solicitudId);

        if (trim((string) $request->codigo_confirmacion) !== $solicitud->codigo_confirmacion) {
            return back()->with('error', 'El codigo de confirmacion no coincide con el de esta postulacion.');
        }

        if (!$solicitud->puedeResponder()) {
            return back()->with('error', 'La oferta ya no está disponible para confirmar.');
        }

        DB::transaction(function () use ($solicitud) {
            Solicitud::where('id', $solicitud->id)->update([
                'confirmada_por_alumno' => true,
                'respondido_en' => now(),
            ]);

            Solicitud::where('alumno_id', $solicitud->alumno_id)
                ->where('id', '!=', $solicitud->id)
                ->where('estatus', 'aceptado')
                ->update([
                    'estatus' => 'rechazado',
                    'confirmada_por_alumno' => false,
                    'respondido_en' => now(),
                ]);
        });

        return back()->with('mensaje', 'Confirmaste tu vacante correctamente. Las demás ofertas quedaron canceladas.');
    }
}
