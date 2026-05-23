<?php

namespace App\Livewire\Alumno;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Solicitud;
use App\Models\Vacante;
use Livewire\Attributes\Layout;

class CatalogoVacantes extends Component
{
    use WithPagination;

    #[Layout('components.layouts.alumno')]
    public function render()
    {
        Solicitud::expirarAceptacionesVencidas();

        return view('livewire.alumno.catalogo-vacantes', [
            'vacantes' => Vacante::abiertas()
                ->withCount([
                    'solicitudes',
                    'solicitudesAceptadas as solicitudes_aceptadas_count',
                    'solicitudesConfirmadas as solicitudes_confirmadas_count',
                ])
                ->latest()
                ->paginate(10)
        ]);
    }
}
