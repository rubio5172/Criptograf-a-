<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Solicitud;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class DocumentoSolicitudController extends Controller
{
    public function show(Solicitud $solicitud, string $documento): Response
    {
        $empresa = Auth::user()?->empresa;

        abort_unless(
            $empresa && $solicitud->vacante && (int) $solicitud->vacante->empresa_id === (int) $empresa->id,
            403
        );

        $campo = match ($documento) {
            'cv' => 'cv',
            'carta' => 'carta',
            'historial' => 'historial',
            default => abort(404),
        };

        $ruta = $solicitud->{$campo};

        if (!$ruta || !Storage::disk('public')->exists($ruta)) {
            abort(404, 'Documento no encontrado.');
        }

        return response()->file(
            Storage::disk('public')->path($ruta),
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . basename($ruta) . '"',
            ]
        );
    }
}
