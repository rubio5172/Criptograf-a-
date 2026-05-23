<?php

namespace App\Livewire\Alumno;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Vacante;
use App\Models\Solicitud;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

class PostularVacante extends Component
{
    use WithFileUploads;

    public $vacante;
    public $vacante_id;

    public $file_cv;
    public $file_carta;
    public $file_historial;

    public function mount($id)
    {
        Solicitud::expirarAceptacionesVencidas();

        $this->vacante_id = $id;
        $this->vacante = Vacante::withCount([
            'solicitudes',
            'solicitudesAceptadas as solicitudes_aceptadas_count',
            'solicitudesConfirmadas as solicitudes_confirmadas_count',
        ])->findOrFail($id);
    }

    public function registrarSolicitud()
    {
        Solicitud::expirarAceptacionesVencidas();

        $alumno = Auth::user()?->alumno;

        abort_unless($alumno, 403);

        $this->vacante = Vacante::withCount([
            'solicitudes',
            'solicitudesAceptadas as solicitudes_aceptadas_count',
            'solicitudesConfirmadas as solicitudes_confirmadas_count',
        ])->findOrFail($this->vacante_id);

        if ($this->vacante->estaCerrada()) {
            session()->flash('error', 'Lo sentimos, esta vacante ya no se encuentra disponible para nuevas postulaciones.');
            return;
        }

        $yaConfirmoOtraVacante = Solicitud::confirmadas()
            ->where('alumno_id', $alumno->id)
            ->exists();

        if ($yaConfirmoOtraVacante) {
            session()->flash('error', 'Ya confirmaste una vacante y no puedes postularte a otra.');
            return;
        }

        $this->validate([
            'file_cv' => 'required|file|mimes:pdf|max:2048',
            'file_carta' => 'required|file|mimes:pdf|max:2048',
            'file_historial' => 'required|file|mimes:pdf|max:2048',
        ], [
            'file_cv.required' => 'El Currículum es obligatorio.',
            'file_carta.required' => 'La Carta de Presentación es obligatoria.',
            'file_historial.required' => 'El Historial Académico es obligatorio.',
            'file_cv.mimes' => 'El archivo debe ser un formato .pdf',
            'file_carta.mimes'=>'El archivo debe de ser formato .pdf',
            'file_historial.mimes'=>'El archivo debe de ser extension .pdf'
        ]);

        $pathCv = $this->file_cv->store('solicitudes/cvs', 'public');
        $pathCarta = $this->file_carta->store('solicitudes/cartas', 'public');
        $pathHistorial = $this->file_historial->store('solicitudes/historiales', 'public');
        $codigo = Solicitud::generarCodigoConfirmacion();

        Solicitud::create([
            'alumno_id' => $alumno->id,
            'vacante_id' => $this->vacante->id,
            'estatus' => 'pendiente',
            'codigo_confirmacion' => $codigo,
            'cv' => $pathCv,
            'carta' => $pathCarta,
            'historial' => $pathHistorial,
            'comentario_empresa' => null,
        ]);

        session()->flash('mensaje', '¡Tu postulación ha sido enviada con éxito! Guarda este código para confirmar si eres aceptado: ' . $codigo);

        return $this->redirect(route('alumno.vacantes.index'), navigate: true);
    }

    #[Layout('components.layouts.alumno')]
    public function render()
    {
        return view('livewire.alumno.postular-vacante');
    }
}
