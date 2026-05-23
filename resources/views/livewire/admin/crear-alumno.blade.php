<div class="space-y-6">
    <div class="flex justify-between items-center border-b border-gray-200 pb-4">
        <div>
            <h1 class="text-xl font-bold text-gray-900 tracking-tight">Control de Alumnos</h1>
            <p class="text-sm text-gray-500">Módulo general de registro para administración del Servicio Social.</p>
        </div>
        <button wire:click="abrirModalCrear" class="cursor-pointer bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm px-4 py-2 rounded-lg transition-colors shadow-xs">
            Registrar Alumno
        </button>
    </div>

    @if (session()->has('mensaje'))
        <div class="w-full p-4 bg-green-50 border border-green-200 text-green-800 text-sm rounded-lg shadow-xs">
            {{ session('mensaje') }}
        </div>
    @endif

    <div class="rounded-xl border border-blue-100 bg-blue-50 p-4">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-blue-700">Acceso del Alumno</p>
        <p class="mt-1 text-sm text-blue-900">El usuario y la contraseña inicial del alumno serán su número de cuenta o matrícula.</p>
    </div>

    <div class="bg-white rounded-xl shadow-xs border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Matrícula</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nombre Completo</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Carrera</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Sem.</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Prom.</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($alumnos as $alumno)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap font-mono text-sm text-gray-700 font-semibold">{{ $alumno->matricula }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $alumno->ap_pat }} {{ $alumno->ap_mat }}, {{ $alumno->nombre }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $alumno->carrera->nombre ?? 'Sin Carrera' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $alumno->semestre ?? '-' }}°</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-600">{{ $alumno->promedio ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                <button wire:click="abrirModalEditar({{ $alumno->id }})" class="cursor-pointer text-blue-600 hover:text-blue-800 font-semibold">Editar</button>
                                <button wire:click="confirmarEliminar({{ $alumno->id }})" class="cursor-pointer text-red-500 hover:text-red-700 font-semibold">Eliminar</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-gray-400 text-sm">
                                No hay alumnos registrados en este momento.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $alumnos->links() }}
        </div>
    </div>

    @if($mostrarModalCrear)
        <div class="fixed inset-0 bg-black/40 backdrop-blur-xs flex items-center justify-center z-50">
            <div class="bg-white rounded-xl p-6 max-w-lg w-full shadow-xl border border-gray-100 max-h-[90vh] overflow-y-auto">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Registrar Nuevo Alumno</h3>
                
                <form wire:submit.prevent="guardar" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 uppercase mb-1">Matrícula</label>
                            <input type="text" wire:model="matricula" class="w-full rounded-lg border border-gray-300 p-2 text-sm outline-hidden focus:border-blue-500">
                            @error('matricula') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 uppercase mb-1">Carrera</label>
                            <select wire:model="carrera_id" class="w-full rounded-lg border border-gray-300 p-2 text-sm outline-hidden focus:border-blue-500">
                                <option value="">-- Seleccionar --</option>
                                @foreach($carreras as $c)
                                    <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                                @endforeach
                            </select>
                            @error('carrera_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 uppercase mb-1">Nombre(s)</label>
                        <input type="text" wire:model="nombre" class="w-full rounded-lg border border-gray-300 p-2 text-sm outline-hidden">
                        @error('nombre') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 uppercase mb-1">Apellido Paterno</label>
                            <input type="text" wire:model="ap_pat" class="w-full rounded-lg border border-gray-300 p-2 text-sm outline-hidden">
                            @error('ap_pat') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 uppercase mb-1">Apellido Materno</label>
                            <input type="text" wire:model="ap_mat" class="w-full rounded-lg border border-gray-300 p-2 text-sm outline-hidden">
                            @error('ap_mat') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 uppercase mb-1">Teléfono</label>
                            <input type="text" wire:model="telefono" class="w-full rounded-lg border border-gray-300 p-2 text-sm outline-hidden">
                            @error('telefono') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 uppercase mb-1">Semestre</label>
                            <input type="number" wire:model="semestre" class="w-full rounded-lg border border-gray-300 p-2 text-sm outline-hidden">
                            @error('semestre') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 uppercase mb-1">Promedio</label>
                            <input type="number" step="0.01" wire:model="promedio" class="w-full rounded-lg border border-gray-300 p-2 text-sm outline-hidden">
                            @error('promedio') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end space-x-2 pt-4 border-t border-gray-100">
                        <button type="button" wire:click="$set('mostrarModalCrear', false)" class="cursor-pointer text-sm font-medium px-4 py-2 text-gray-500 hover:text-gray-700">Cancelar</button>
                        <button type="submit" class="cursor-pointer text-sm font-medium bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Guardar Alumno</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if($mostrarModalEditar)
        <div class="fixed inset-0 bg-black/40 backdrop-blur-xs flex items-center justify-center z-50">
            <div class="bg-white rounded-xl p-6 max-w-lg w-full shadow-xl border border-gray-100 max-h-[90vh] overflow-y-auto">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Modificar Datos del Alumno</h3>
                
                <form wire:submit.prevent="actualizar" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 uppercase mb-1">Matrícula</label>
                            <input type="text" wire:model="matricula" class="w-full rounded-lg border border-gray-300 p-2 text-sm outline-hidden">
                            @error('matricula') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 uppercase mb-1">Carrera</label>
                            <select wire:model="carrera_id" class="w-full rounded-lg border border-gray-300 p-2 text-sm outline-hidden">
                                <option value="">-- Seleccionar --</option>
                                @foreach($carreras as $c)
                                    <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                                @endforeach
                            </select>
                            @error('carrera_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 uppercase mb-1">Nombre(s)</label>
                        <input type="text" wire:model="nombre" class="w-full rounded-lg border border-gray-300 p-2 text-sm outline-hidden">
                        @error('nombre') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 uppercase mb-1">Apellido Paterno</label>
                            <input type="text" wire:model="ap_pat" class="w-full rounded-lg border border-gray-300 p-2 text-sm outline-hidden">
                            @error('ap_pat') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 uppercase mb-1">Apellido Materno</label>
                            <input type="text" wire:model="ap_mat" class="w-full rounded-lg border border-gray-300 p-2 text-sm outline-hidden">
                            @error('ap_mat') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 uppercase mb-1">Teléfono</label>
                            <input type="text" wire:model="telefono" class="w-full rounded-lg border border-gray-300 p-2 text-sm outline-hidden">
                            @error('telefono') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 uppercase mb-1">Semestre</label>
                            <input type="number" wire:model="semestre" class="w-full rounded-lg border border-gray-300 p-2 text-sm outline-hidden">
                            @error('semestre') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 uppercase mb-1">Promedio</label>
                            <input type="number" step="0.01" wire:model="promedio" class="w-full rounded-lg border border-gray-300 p-2 text-sm outline-hidden">
                            @error('promedio') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end space-x-2 pt-4 border-t border-gray-100">
                        <button type="button" wire:click="$set('mostrarModalEditar', false)" class="cursor-pointer text-sm font-medium px-4 py-2 text-gray-500 hover:text-gray-700">Cancelar</button>
                        <button type="submit" class="cursor-pointer text-sm font-medium bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Actualizar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if($mostrarModalEliminar)
        <div class="fixed inset-0 bg-black/40 backdrop-blur-xs flex items-center justify-center z-50">
            <div class="bg-white rounded-xl p-6 max-w-sm w-full shadow-xl text-center border border-gray-100">
                <div class="mx-auto w-12 h-12 bg-red-50 text-red-600 rounded-full flex items-center justify-center mb-4 text-xs font-bold uppercase tracking-[0.2em]">
                    Alerta
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-1">¿Remover Alumno?</h3>
                <p class="text-sm text-gray-500 mb-6">Esta acción borrará de manera permanente el registro del estudiante.</p>
                <div class="flex justify-center space-x-2">
                    <button type="button" wire:click="$set('mostrarModalEliminar', false)" class="cursor-pointer text-sm font-medium px-4 py-2 text-gray-500 hover:text-gray-700">Cancelar</button>
                    <button type="button" wire:click="eliminar" class="cursor-pointer text-sm font-medium bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">Sí, eliminar</button>
                </div>
            </div>
        </div>
    @endif
</div>
