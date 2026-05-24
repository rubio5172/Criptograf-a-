<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Alumno;
use App\Models\Carrera;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;

class CrearAlumno extends Component
{
    use WithPagination;

    public $alumno_id = null;
    public $carrera_id = '';
    public $nombre = '';
    public $ap_pat = '';
    public $ap_mat = '';
    public $matricula = '';
    public $telefono = '';
    public $semestre = '';
    public $promedio = '';

    public $mostrarModalCrear = false;
    public $mostrarModalEditar = false;
    public $mostrarModalEliminar = false;

    protected function rules()
    {
        return [
            'carrera_id' => ['required', 'exists:carreras,id'],
            'nombre'     => ['required', 'string', 'max:255'],
            'ap_pat'     => ['required', 'string', 'max:255'],
            'ap_mat'     => ['required', 'string', 'max:255'],
            
            // 💡 Sintaxis corregida y unificada para la matrícula (Evita el choque en el validador)
            'matricula'  => [
                'required', 
                'integer', 
                'digits_between:9,20', 
                Rule::unique('alumnos', 'matricula')->ignore($this->alumno_id),
                Rule::unique('users', 'username')->ignore(Alumno::find($this->alumno_id)?->user_id)
            ],
            
            'telefono'   => ['nullable', 'string', 'max:15'],
            'semestre'   => ['nullable', 'integer', 'min:1', 'max:12'],
            'promedio'   => ['nullable', 'numeric', 'between:0,10.00'],
        ];
    }

    protected $messages = [
        'carrera_id.required' => 'Debes seleccionar una carrera.',
        'nombre.required'     => 'El nombre es obligatorio.',
        'ap_pat.required'     => 'El apellido profesional paterno es obligatorio.',
        'ap_mat.required'     => 'El apellido materno es obligatorio.',
        'matricula.required'  => 'La matrícula es obligatoria.',
        'matricula.integer'   => 'La matrícula debe ser un valor numérico entero.',
        'matricula.digits_between' => 'La matrícula debe tener entre 9 y 20 dígitos.',
        'matricula.unique'    => 'Esta matrícula o usuario ya pertenece a otro alumno registrado.',
        'semestre.min'        => 'El semestre mínimo es 1.',
        'semestre.max'        => 'El semestre máximo permitido es 12.',
        'promedio.between'    => 'El promedio debe estar en el rango de 0 a 10.',
    ];

    public function updating()
    {
        $this->resetPage();
    }

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

        DB::transaction(function () {
            $username = (string) $this->matricula;

            $user = User::create([
                'name'     => trim($this->nombre . ' ' . $this->ap_pat . ' ' . $this->ap_mat),
                'email'    => 'alumno' . $username . '@serviciosocial.local',
                'username' => $username,
                'password' => $username,
                'role'     => 'alumno',
            ]);

            Alumno::create([
                'user_id'    => $user->id,
                'carrera_id' => $this->carrera_id,
                'nombre'     => $this->nombre,
                'ap_pat'     => $this->ap_pat,
                'ap_mat'     => $this->ap_mat,
                'matricula'  => $this->matricula,
                'telefono'   => $this->telefono ?: null,
                'semestre'   => $this->semestre ?: null,
                'promedio'   => $this->promedio ?: null,
            ]);
        });

        $this->mostrarModalCrear = false;
        session()->flash('mensaje', '¡Alumno registrado correctamente! Usuario y contraseña inicial: ' . $this->matricula);
    }

    public function abrirModalEditar($id)
    {
        $this->resetValidation();
        $alumno = Alumno::findOrFail($id);

        $this->alumno_id  = $alumno->id;
        $this->carrera_id = $alumno->carrera_id;
        $this->nombre     = $alumno->nombre;
        $this->ap_pat     = $alumno->ap_pat;
        $this->ap_mat     = $alumno->ap_mat;
        $this->matricula  = $alumno->matricula;
        $this->telefono   = $alumno->telefono;
        $this->semestre   = $alumno->semestre;
        $this->promedio   = $alumno->promedio;

        $this->mostrarModalEditar = true;
    }

    public function actualizar()
    {
        $this->validate();

        DB::transaction(function () {
            $alumno   = Alumno::findOrFail($this->alumno_id);
            $user     = User::findOrFail($alumno->user_id);
            $username = (string) $this->matricula;

            $user->update([
                'name'     => trim($this->nombre . ' ' . $this->ap_pat . ' ' . $this->ap_mat),
                'email'    => 'alumno' . $username . '@serviciosocial.local',
                'username' => $username,
                'password' => $username,
                'role'     => 'alumno',
            ]);

            $alumno->update([
                'carrera_id' => $this->carrera_id,
                'nombre'     => $this->nombre,
                'ap_pat'     => $this->ap_pat,
                'ap_mat'     => $this->ap_mat,
                'matricula'  => $this->matricula,
                'telefono'   => $this->telefono ?: null,
                'semestre'   => $this->semestre ?: null,
                'promedio'   => $this->promedio ?: null,
            ]);
    });

        $this->mostrarModalEditar = false;
        session()->flash('mensaje', '¡Datos del estudiante actualizados! Usuario y contraseña actual: ' . $this->matricula);
    }

    public function confirmarEliminar($id)
    {
        $this->alumno_id = $id;
        $this->mostrarModalEliminar = true;
    }

    public function eliminar()
    {
        $alumno = Alumno::findOrFail($this->alumno_id);
        $user   = User::find($alumno->user_id);

        $alumno->delete();

        if ($user) {
            $user->delete();
        }

        $this->mostrarModalEliminar = false;
        session()->flash('mensaje', 'El alumno ha sido removido del sistema.');
    }

    #[Layout('components.layouts.admin')]
    public function render()
    {
        return view('livewire.admin.crear-alumno', [
            'alumnos'  => Alumno::with('carrera')->latest()->paginate(10),
            'carreras' => Carrera::orderBy('nombre')->get()
        ]);
    }
}