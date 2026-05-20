<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Alumno;
use App\Models\Carrera;
use Livewire\Attributes\Layout;

class CrearAlumno extends Component
{
    use WithPagination; // Paginación asíncrona automática

    // Propiedades para capturar los datos del formulario
    public $alumno_id = null;
    public $carrera_id = '';
    public $nombre = '';
    public $ap_pat = '';
    public $ap_mat = '';
    public $matricula = '';
    public $telefono = '';
    public $semestre = '';
    public $promedio = '';

    // Estados de control para los Modales
    public $mostrarModalCrear = false;
    public $mostrarModalEditar = false;
    public $mostrarModalEliminar = false;

    // Reglas de validación dinámicas adaptadas a PostgreSQL
    protected function rules()
    {
        $reglaMatricula = 'required|string|max:20|unique:alumnos,matricula';
        
        if ($this->alumno_id) {
            $reglaMatricula .= ',' . $this->alumno_id;
        }

        return [
            'carrera_id' => 'required|exists:carreras,id',
            'nombre' => 'required|string|max:255',
            'ap_pat' => 'required|string|max:255',
            'ap_mat' => 'required|string|max:255',
            'matricula' => $reglaMatricula,
            'telefono' => 'nullable|string|max:15',
            'semestre' => 'nullable|integer|min:1|max:12',
            'promedio' => 'nullable|numeric|between:0,10.00',
        ];
    }

    protected $messages = [
        'carrera_id.required' => 'Debes seleccionar una carrera.',
        'nombre.required' => 'El nombre es obligatorio.',
        'ap_pat.required' => 'El apellido paterno es obligatorio.',
        'ap_mat.required' => 'El apellido materno es obligatorio.',
        'matricula.required' => 'La matrícula es obligatoria.',
        'matricula.unique' => 'Esta matrícula ya pertenece a otro alumno.',
        'semestre.min' => 'El semestre mínimo es 1.',
        'promedio.between' => 'El promedio debe estar entre 0 y 10.',
    ];

    public function updating()
    {
        $this->resetPage();
    }

    // CREAR 
    public function abrirModalCrear()
    {
        $this->resetValidation();
        $this->reset(['alumno_id', 'carrera_id', 'nombre', 'ap_pat', 'ap_mat', 'matricula', 'telefono', 'semestre', 'promedio']);
        $this->mostrarModalCrear = true;
    }

    public function guardar()
    {
        $this->alumno_id = null;
        $this->validate();

        Alumno::create([
            'carrera_id' => $this->carrera_id,
            'nombre' => $this->nombre,
            'ap_pat' => $this->ap_pat,
            'ap_mat' => $this->ap_mat,
            'matricula' => $this->matricula,
            'telefono' => $this->telefono ?: null,
            'semestre' => $this->semestre ?: null,
            'promedio' => $this->promedio ?: null,
        ]);

        $this->mostrarModalCrear = false;
        session()->flash('mensaje', '¡Alumno registrado correctamente!');
    }

    // EDITAR
    public function abrirModalEditar($id)
    {
        $this->resetValidation();
        $alumno = Alumno::findOrFail($id);

        $this->alumno_id = $alumno->id;
        $this->carrera_id = $alumno->carrera_id;
        $this->nombre = $alumno->nombre;
        $this->ap_pat = $alumno->ap_pat;
        $this->ap_mat = $alumno->ap_mat;
        $this->matricula = $alumno->matricula;
        $this->telefono = $alumno->telefono;
        $this->semestre = $alumno->semestre;
        $this->promedio = $alumno->promedio;

        $this->mostrarModalEditar = true;
    }

    public function actualizar()
    {
        $this->validate();

        $alumno = Alumno::findOrFail($this->alumno_id);
        $alumno->update([
            'carrera_id' => $this->carrera_id,
            'nombre' => $this->nombre,
            'ap_pat' => $this->ap_pat,
            'ap_mat' => $this->ap_mat,
            'matricula' => $this->matricula,
            'telefono' => $this->telefono ?: null,
            'semestre' => $this->semestre ?: null,
            'promedio' => $this->promedio ?: null,
        ]);

        $this->mostrarModalEditar = false;
        session()->flash('mensaje', '¡Datos del estudiante actualizados!');
    }

    // ELIMINAR
    public function confirmarEliminar($id)
    {
        $this->alumno_id = $id;
        $this->mostrarModalEliminar = true;
    }

    public function eliminar()
    {
        Alumno::destroy($this->alumno_id);
        $this->mostrarModalEliminar = false;
        session()->flash('mensaje', 'El alumno ha sido removido del sistema.');
    }

    #[Layout('components.layouts.admin')]
    public function render()
    {
        return view('livewire.admin.crear-alumno', [
            'alumnos' => Alumno::with('carrera')->latest()->paginate(10),
            'carreras' => Carrera::orderBy('nombre')->get()
        ]);
    }
}