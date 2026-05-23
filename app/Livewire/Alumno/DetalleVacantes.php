<?php

namespace App\Livewire\Alumno;

use Livewire\Component;
use App\Models\Solicitud;
use App\Models\Vacante;
use Livewire\Attributes\Layout;

class DetalleVacantes extends Component
{
    public $vacante;

    public $id;

    public function mount($id)
    {
        Solicitud::expirarAceptacionesVencidas();

        $this->vacante = Vacante::withCount([
            'solicitudes',
            'solicitudesAceptadas as solicitudes_aceptadas_count',
            'solicitudesConfirmadas as solicitudes_confirmadas_count',
        ])->findOrFail($id);
    }

    #[Layout('components.layouts.alumno')]
    public function render()
    {
        return view('livewire.alumno.detalle-vacantes');
    }
}
