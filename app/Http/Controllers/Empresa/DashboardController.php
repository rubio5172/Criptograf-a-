<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Solicitud;
use App\Models\Vacante;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        Solicitud::expirarAceptacionesVencidas();

        $empresa = Auth::user()?->empresa;

        abort_unless($empresa instanceof Empresa, 403);

        $metricas = [
            'vacantes_publicadas' => Vacante::where('empresa_id', $empresa->id)->count(),
            'vacantes_abiertas' => Vacante::where('empresa_id', $empresa->id)->abiertas()->count(),
            'postulaciones_pendientes' => Solicitud::whereHas('vacante', fn ($query) => $query->where('empresa_id', $empresa->id))
                ->where('estatus', 'pendiente')
                ->count(),
            'aceptadas_por_confirmar' => Solicitud::whereHas('vacante', fn ($query) => $query->where('empresa_id', $empresa->id))
                ->aceptadasPendientesDecision()
                ->count(),
            'confirmadas' => Solicitud::whereHas('vacante', fn ($query) => $query->where('empresa_id', $empresa->id))
                ->confirmadas()
                ->count(),
        ];

        $vacantesRecientes = Vacante::withCount([
            'solicitudes',
            'solicitudesAceptadas as solicitudes_aceptadas_count',
            'solicitudesConfirmadas as solicitudes_confirmadas_count',
        ])
            ->where('empresa_id', $empresa->id)
            ->latest()
            ->take(5)
            ->get();

        $solicitudesRecientes = Solicitud::with('vacante')
            ->whereHas('vacante', fn ($query) => $query->where('empresa_id', $empresa->id))
            ->latest()
            ->take(6)
            ->get();

        return view('empresas.dashboard', compact(
            'metricas',
            'vacantesRecientes',
            'solicitudesRecientes'
        ));
    }
}
