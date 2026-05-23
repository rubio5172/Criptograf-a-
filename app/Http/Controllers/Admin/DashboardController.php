<?php

namespace App\Http\Controllers\Admin;

use App\Models\Alumno;
use App\Models\Carrera;
use App\Models\Empresa;
use App\Models\Solicitud;
use App\Models\Vacante;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        Solicitud::expirarAceptacionesVencidas();

        $metricas = [
            'carreras' => Carrera::count(),
            'alumnos' => Alumno::count(),
            'empresas' => Empresa::count(),
            'solicitudes' => Solicitud::count(),
        ];

        $estadoSolicitudes = [
            'pendientes' => Solicitud::where('estatus', 'pendiente')->count(),
            'por_confirmar' => Solicitud::aceptadasPendientesDecision()->count(),
            'confirmadas' => Solicitud::confirmadas()->count(),
            'rechazadas' => Solicitud::where('estatus', 'rechazado')->count(),
        ];

        $vacantesRecientes = Vacante::withCount([
            'solicitudes',
            'solicitudesAceptadas as solicitudes_aceptadas_count',
            'solicitudesConfirmadas as solicitudes_confirmadas_count',
        ])
            ->latest()
            ->take(5)
            ->get();

        $carrerasConAlumnos = Carrera::withCount('alumnos')
            ->orderByDesc('alumnos_count')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'metricas',
            'estadoSolicitudes',
            'vacantesRecientes',
            'carrerasConAlumnos'
        ));
    }
}
