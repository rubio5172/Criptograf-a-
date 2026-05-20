<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Carrera;
use Livewire\Attributes\Layout;

class CrearCarrera extends Component
{
    // Propiedades para el formulario (Solo usamos ID y Nombre)
    public $carrera_id = null;
    public $nombre = '';

    // Propiedades para controlar los Modales
    public $mostrarModalCrear = false;
    public $mostrarModalEditar = false;
    public $mostrarModalEliminar = false;

    // Reglas de validación adaptadas al campo Nombre
    protected function rules()
    {
        $reglaNombre = 'required|string|min:5|max:100|unique:carreras,nombre';

        // Si estamos EDITANDO (el ID ya existe en la base de datos), le decimos que ignore ese ID
        if ($this->carrera_id) {
            $reglaNombre .= ',' . $this->carrera_id;
        }

        return [
            'nombre' => $reglaNombre,
        ];
    }

    protected $messages = [
        'nombre.required' => 'El nombre de la carrera es obligatorio.',
        'nombre.min' => 'El nombre debe tener al menos 5 caracteres.',
        'nombre.unique' => 'Esta carrera ya se encuentra registrada en el sistema.',
    ];
//Crear
    public function abrirModalCrear()
    {
        $this->resetValidation();
        $this->reset(['carrera_id', 'nombre']);
        $this->mostrarModalCrear = true;
    }

    public function guardar()
    {
        $this->carrera_id = null; 
        $this->validate();

        Carrera::create([
            'nombre' => $this->nombre,
        ]);

        $this->mostrarModalCrear = false;
        session()->flash('mensaje', '¡Carrera creada correctamente!');
        $this->reset(['nombre']);
    }

    // --- ACCIÓN: EDITAR ---
    public function abrirModalEditar($id)
    {
        $this->resetValidation();
        $carrera = Carrera::findOrFail($id);
        
        $this->carrera_id = $carrera->id;
        $this->nombre = $carrera->nombre;

        $this->mostrarModalEditar = true;
    }

    public function actualizar()
    {
        $this->validate();

        $carrera = Carrera::findOrFail($this->carrera_id);
        $carrera->update([
            'nombre' => $this->nombre,
        ]);

        $this->mostrarModalEditar = false;
        session()->flash('mensaje', '¡Carrera actualizada con éxito!');
        $this->reset(['carrera_id', 'nombre']);
    }

    // --- ACCIÓN: ELIMINAR ---
    public function confirmarEliminar($id)
    {
        $this->carrera_id = $id;
        $this->mostrarModalEliminar = true;
    }

    public function eliminar()
    {
        $carrera = Carrera::findOrFail($this->carrera_id);
        $carrera->delete();

        $this->mostrarModalEliminar = false;
        session()->flash('mensaje', 'Carrera eliminada del sistema.');
        $this->reset(['carrera_id', 'nombre']);
    }

    #[Layout('components.layouts.admin')]
    public function render()
    {
        return view('livewire.admin.crear-carrera', [
            'carreras' => Carrera::latest()->get()
        ]);
    }
}