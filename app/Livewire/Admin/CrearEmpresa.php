<?php

namespace App\Livewire\Admin;

use App\Models\Empresa;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class CrearEmpresa extends Component
{
    use WithPagination;

    public $empresa_id = null;
    public $user_id = null;
    public $nombre = '';
    public $rfc = '';
    public $sector = '';
    public $telefono = '';
    public $direccion = '';
    public $contacto_nombre = '';
    public $contacto_email = '';
    public $password = '';
    public $password_confirmation = '';
    public $activa = true;

    public $mostrarModalCrear = false;
    public $mostrarModalEditar = false;
    public $mostrarModalEliminar = false;

    protected function rules()
    {
        $rfcNormalizado = strtoupper(trim($this->rfc));
        $reglaCorreo = 'required|email|max:255|unique:users,email';
        $reglaPassword = $this->empresa_id
            ? 'nullable|string|min:8|confirmed'
            : 'required|string|min:8|confirmed';

        if ($this->empresa_id) {
            $reglaCorreo .= ',' . $this->user_id;
        }

        return [
            'nombre' => 'required|string|min:3|max:255',
            'rfc' => [
                'required',
                'string',
                'min:12',
                'max:20',
                Rule::unique('empresas', 'rfc')->ignore($this->empresa_id),
                Rule::unique('users', 'username')->ignore($this->user_id)->where(function ($query) use ($rfcNormalizado) {
                    return $query->where('username', $rfcNormalizado);
                }),
            ],
            'sector' => 'required|string|min:3|max:150',
            'telefono' => 'required|string|min:10|max:20',
            'direccion' => 'required|string|min:10|max:255',
            'contacto_nombre' => 'required|string|min:5|max:255',
            'contacto_email' => $reglaCorreo,
            'password' => $reglaPassword,
            'activa' => 'required|boolean',
        ];
    }

    protected $messages = [
        'nombre.required' => 'El nombre de la empresa es obligatorio.',
        'rfc.required' => 'El RFC es obligatorio.',
        'rfc.unique' => 'Ese RFC ya está registrado.',
        'sector.required' => 'Debes indicar el sector de la empresa.',
        'telefono.required' => 'El teléfono es obligatorio.',
        'direccion.required' => 'La dirección es obligatoria.',
        'contacto_nombre.required' => 'El nombre del contacto es obligatorio.',
        'contacto_email.required' => 'El correo del contacto es obligatorio.',
        'contacto_email.unique' => 'Ese correo ya pertenece a otro usuario.',
        'password.required' => 'La contraseña es obligatoria al crear una empresa.',
        'password.confirmed' => 'La confirmación de contraseña no coincide.',
    ];

    public function updating()
    {
        $this->resetPage();
    }

    public function abrirModalCrear()
    {
        $this->resetValidation();
        $this->resetFormulario();
        $this->mostrarModalCrear = true;
    }

    public function guardar()
    {
        $this->empresa_id = null;
        $this->user_id = null;
        $this->rfc = strtoupper(trim($this->rfc));
        $this->validate();

        DB::transaction(function () {
            $user = User::create([
                'name' => $this->contacto_nombre,
                'email' => $this->contacto_email,
                'username' => strtoupper($this->rfc),
                'password' => $this->password,
                'role' => 'empresa',
            ]);

            Empresa::create([
                'user_id' => $user->id,
                'nombre' => $this->nombre,
                'rfc' => strtoupper($this->rfc),
                'sector' => $this->sector,
                'telefono' => $this->telefono,
                'direccion' => $this->direccion,
                'contacto_nombre' => $this->contacto_nombre,
                'contacto_email' => $this->contacto_email,
                'activa' => (bool) $this->activa,
            ]);
        });

        $this->mostrarModalCrear = false;
        session()->flash('mensaje', '¡Empresa registrada correctamente! Usuario: ' . strtoupper($this->rfc));
        $this->resetFormulario();
    }

    public function abrirModalEditar($id)
    {
        $this->resetValidation();
        $empresa = Empresa::with('user')->findOrFail($id);

        $this->empresa_id = $empresa->id;
        $this->user_id = $empresa->user_id;
        $this->nombre = $empresa->nombre;
        $this->rfc = $empresa->rfc;
        $this->sector = $empresa->sector;
        $this->telefono = $empresa->telefono;
        $this->direccion = $empresa->direccion;
        $this->contacto_nombre = $empresa->contacto_nombre;
        $this->contacto_email = $empresa->contacto_email;
        $this->password = '';
        $this->password_confirmation = '';
        $this->activa = (bool) $empresa->activa;

        $this->mostrarModalEditar = true;
    }

    public function actualizar()
    {
        $this->rfc = strtoupper(trim($this->rfc));
        $this->validate();

        DB::transaction(function () {
            $empresa = Empresa::findOrFail($this->empresa_id);
            $user = User::findOrFail($this->user_id);

            $userData = [
                'name' => $this->contacto_nombre,
                'email' => $this->contacto_email,
                'username' => strtoupper($this->rfc),
                'role' => 'empresa',
            ];

            if ($this->password !== '') {
                $userData['password'] = $this->password;
            }

            $user->update($userData);

            $empresa->update([
                'nombre' => $this->nombre,
                'rfc' => strtoupper($this->rfc),
                'sector' => $this->sector,
                'telefono' => $this->telefono,
                'direccion' => $this->direccion,
                'contacto_nombre' => $this->contacto_nombre,
                'contacto_email' => $this->contacto_email,
                'activa' => (bool) $this->activa,
            ]);
        });

        $this->mostrarModalEditar = false;
        session()->flash('mensaje', '¡Empresa actualizada correctamente! Usuario actual: ' . strtoupper($this->rfc));
        $this->resetFormulario();
    }

    public function confirmarEliminar($id)
    {
        $this->empresa_id = $id;
        $this->mostrarModalEliminar = true;
    }

    public function eliminar()
    {
        $empresa = Empresa::findOrFail($this->empresa_id);
        $empresa->delete();

        $this->mostrarModalEliminar = false;
        session()->flash('mensaje', 'La empresa ha sido eliminada del sistema.');
        $this->resetFormulario();
    }

    private function resetFormulario(): void
    {
        $this->reset([
            'empresa_id',
            'user_id',
            'nombre',
            'rfc',
            'sector',
            'telefono',
            'direccion',
            'contacto_nombre',
            'contacto_email',
            'password',
            'password_confirmation',
            'activa',
        ]);

        $this->activa = true;
    }

    #[Layout('components.layouts.admin')]
    public function render()
    {
        return view('livewire.admin.crear-empresa', [
            'empresas' => Empresa::with('user')->latest()->paginate(10),
        ]);
    }
}
