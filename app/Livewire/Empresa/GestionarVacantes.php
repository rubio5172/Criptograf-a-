<?php

namespace App\Livewire\Empresa;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Empresa;
use App\Models\Solicitud;
use App\Models\Vacante;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

class GestionarVacantes extends Component
{
    use WithPagination;

    public $vacante_id = null;
    public $empresa_nombre = '';
    public $titulo = '';
    public $descripcion = '';
    public $requisitos = '';
    public $horario = '';
    public $ubicacion = '';
    public $cupo_maximo = '';
    public $limite_registros = '';

    public $postulaciones_vacante = [];
    public $vacante_seleccionada_titulo = '';

    public $mostrarModalCrear = false;
    public $mostrarModalEditar = false;
    public $mostrarModalEliminar = false;
    public $mostrarModalEstado = false;
    public $mostrarModalPostulantes = false;
    public $vacante_estado_activa = true;

    public $solicitud_seleccionada = null;
    public $pdf_url = '';
    public $mostrarVisorPdf = false;

    private function empresaAutenticada(): Empresa
    {
        $empresa = Auth::user()?->empresa;

        abort_unless($empresa instanceof Empresa, 403);

        return $empresa;
    }

    private function obtenerVacantePropia(int $vacanteId): Vacante
    {
        return Vacante::where('empresa_id', $this->empresaAutenticada()->id)
            ->findOrFail($vacanteId);
    }

    private function obtenerSolicitudPropia(int $solicitudId): Solicitud
    {
        return Solicitud::whereHas('vacante', function ($query) {
            $query->where('empresa_id', $this->empresaAutenticada()->id);
        })->findOrFail($solicitudId);
    }

    public function verPostulaciones($id)
    {
        Solicitud::expirarAceptacionesVencidas();

        $vacante = $this->obtenerVacantePropia($id);
        $this->vacante_id = $vacante->id;
        $this->vacante_seleccionada_titulo = $vacante->titulo;
        $this->mostrarModalPostulantes = true;
    }

    public function aceptarSolicitud($solicitudId)
    {
        Solicitud::expirarAceptacionesVencidas();

        $solicitud = $this->obtenerSolicitudPropia($solicitudId)->load('vacante');
        $vacante = $solicitud->vacante->loadCount([
            'solicitudes',
            'solicitudesAceptadas as solicitudes_aceptadas_count',
            'solicitudesConfirmadas as solicitudes_confirmadas_count',
        ]);

        if ($vacante->estaCerrada()) {
            session()->flash('mensaje', 'La vacante ya alcanzó el límite de alumnos aceptados y fue cerrada.');
            return;
        }

        $alumnoYaConfirmado = Solicitud::confirmadas()
            ->where('alumno_id', $solicitud->alumno_id)
            ->where('id', '!=', $solicitud->id)
            ->exists();

        if ($alumnoYaConfirmado) {
            session()->flash('mensaje', 'Este alumno ya confirmó otra vacante y no puede ser aceptado nuevamente.');
            return;
        }

        $solicitud->update([
            'estatus' => 'aceptado',
            'confirmada_por_alumno' => false,
            'fecha_limite_respuesta' => Solicitud::fechaLimiteNuevaDecision(),
            'respondido_en' => null,
        ]);

        $vacante->refresh()->loadCount([
            'solicitudes',
            'solicitudesAceptadas as solicitudes_aceptadas_count',
            'solicitudesConfirmadas as solicitudes_confirmadas_count',
        ]);

        if ($vacante->solicitudes_aceptadas_count >= $vacante->cupo_maximo) {
            session()->flash('mensaje', 'Se aceptó al alumno y la vacante alcanzó su cupo máximo de aceptados.');
            return;
        }

        session()->flash('mensaje', 'El alumno fue aceptado y tendrá 48 horas para confirmar esta vacante.');
    }

    public function rechazarSolicitud($solicitudId)
    {
        $solicitud = $this->obtenerSolicitudPropia($solicitudId);
        $solicitud->update(['estatus' => 'rechazado']);

        session()->flash('mensaje', 'La solicitud ha sido rechazada.');
    }

    public function abrirPdf($solicitudId, $documento)
    {
        $this->pdf_url = route('empresa.solicitudes.documentos.show', [
            'solicitud' => $solicitudId,
            'documento' => $documento,
        ]);
        $this->mostrarVisorPdf = true;
    }



    protected function rules()
    {
        return [
            'empresa_nombre' => 'required|string|max:255',
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'requisitos' => 'required|string',
            'horario' => 'required|string|max:100',
            'ubicacion' => 'required|string|max:255',
            'cupo_maximo' => 'required|integer|min:1',
            'limite_registros' => 'required|integer|min:' . ($this->cupo_maximo ?: 1),
        ];
    }


    public function abrirModalCrear()
    {
        $this->resetValidation();
        $this->reset(['vacante_id', 'empresa_nombre', 'titulo', 'descripcion', 'requisitos', 'horario', 'ubicacion', 'cupo_maximo', 'limite_registros']);
        $this->empresa_nombre = Auth::user()?->empresa?->nombre ?? '';
        $this->mostrarModalCrear = true;
    }

    public function guardar()
    {
        $this->vacante_id = null;
        $this->validate();
        $empresa = $this->empresaAutenticada();

        Vacante::create([
            'empresa_id' => $empresa->id,
            'empresa_nombre' => $empresa->nombre,
            'titulo' => $this->titulo,
            'descripcion' => $this->descripcion,
            'requisitos' => $this->requisitos,
            'horario' => $this->horario,
            'ubicacion' => $this->ubicacion,
            'cupo_maximo' => $this->cupo_maximo,
            'limite_registros' => $this->limite_registros,
        ]);

        $this->mostrarModalCrear = false;
        session()->flash('mensaje', '¡Vacante publicada con éxito!');
    }

    public function abrirModalEditar($id)
    {
        $this->resetValidation();
        $vacante = $this->obtenerVacantePropia($id);
        $empresa = $this->empresaAutenticada();
        $this->vacante_id = $vacante->id;
        $this->empresa_nombre = $empresa->nombre;
        $this->titulo = $vacante->titulo;
        $this->descripcion = $vacante->descripcion;
        $this->requisitos = $vacante->requisitos;
        $this->horario = $vacante->horario;
        $this->ubicacion = $vacante->ubicacion;
        $this->cupo_maximo = $vacante->cupo_maximo;
        $this->limite_registros = $vacante->limite_registros;
        $this->mostrarModalEditar = true;
    }


    public function actualizar()
    {
        $this->validate();
        $empresa = $this->empresaAutenticada();
        $vacante = $this->obtenerVacantePropia($this->vacante_id);
        $vacante->update([
            'empresa_nombre' => $empresa->nombre,
            'titulo' => $this->titulo,
            'descripcion' => $this->descripcion,
            'requisitos' => $this->requisitos,
            'horario' => $this->horario,
            'ubicacion' => $this->ubicacion,
            'cupo_maximo' => $this->cupo_maximo,
            'limite_registros' => $this->limite_registros,
        ]);
        $this->mostrarModalEditar = false;
        session()->flash('mensaje', '¡Vacante actualizada correctamente!');
    }

    public function confirmarEliminar($id)
    {
        $this->vacante_id = $this->obtenerVacantePropia($id)->id;
        $this->mostrarModalEliminar = true;
    }

    public function confirmarCambioEstado($id)
    {
        $vacante = $this->obtenerVacantePropia($id);
        $this->vacante_id = $vacante->id;
        $this->vacante_estado_activa = !$vacante->estaCerradaManualmente();
        $this->mostrarModalEstado = true;
    }

    public function cambiarEstado()
    {
        $vacante = $this->obtenerVacantePropia($this->vacante_id);
        $vacante->update([
            'activa' => $vacante->estaCerradaManualmente(),
            'cerrada_manualmente' => !$vacante->cerrada_manualmente,
        ]);

        $this->mostrarModalEstado = false;

        session()->flash(
            'mensaje',
            $vacante->activa
                ? 'La vacante fue reabierta correctamente.'
                : 'La vacante fue cerrada correctamente.'
        );
    }

    public function eliminar()
    {
        $this->obtenerVacantePropia($this->vacante_id)->delete();
        $this->mostrarModalEliminar = false;
        session()->flash('mensaje', 'La vacante ha sido eliminada.');
    }
    #[Layout('components.layouts.empresa')]
    public function render()
    {
        Solicitud::expirarAceptacionesVencidas();
        $empresa = $this->empresaAutenticada();

        return view('livewire.empresa.gestionar-vacantes', [
            'vacantes' => Vacante::withCount([
                'solicitudes',
                'solicitudesAceptadas as solicitudes_aceptadas_count',
                'solicitudesConfirmadas as solicitudes_confirmadas_count',
            ])->where('empresa_id', $empresa->id)->latest()->paginate(10),
            'solicitudes_reales' => $this->mostrarModalPostulantes && $this->vacante_id
                ? Solicitud::whereHas('vacante', fn ($query) => $query
                    ->where('empresa_id', $empresa->id)
                    ->where('id', $this->vacante_id))
                    ->get()
                : []
        ]);
    }
}
