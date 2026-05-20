<div class="space-y-6">
    <div class="flex justify-between items-center border-b border-gray-200 pb-4">
        <div>
            <h1 class="text-xl font-bold text-gray-900 tracking-tight">Catálogo de Carreras</h1>
            <p class="text-sm text-gray-500">Gestiona las carreras disponibles en la Facultad de Ingeniería.</p>
        </div>
        <button wire:click="abrirModalCrear" class="cursor-pointer bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm px-4 py-2 rounded-lg transition-colors shadow-xs">
            Nueva Carrera
        </button>
    </div>

    @if (session()->has('mensaje'))
        <div class="w-full p-4 bg-green-50 border border-green-200 text-green-800 text-sm rounded-lg shadow-xs">
            {{ session('mensaje') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-xs border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-24">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nombre de la Carrera</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider w-40">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($carreras as $carrera)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap font-mono text-sm text-gray-400">#{{ $carrera->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $carrera->nombre }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                            <button wire:click="abrirModalEditar({{ $carrera->id }})" class="cursor-pointer text-blue-600 hover:text-blue-800 font-semibold">Editar</button>
                            <button wire:click="confirmarEliminar({{ $carrera->id }})" class="cursor-pointer text-red-500 hover:text-red-700 font-semibold">Eliminar</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center py-12 text-gray-400 text-sm">
                            No hay carreras registradas en este momento.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($mostrarModalCrear)
        <div class="fixed inset-0 bg-black/40 backdrop-blur-xs flex items-center justify-center z-50 animate-fade-in">
            <div class="bg-white rounded-xl p-6 max-w-md w-full shadow-xl border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-1">Registrar Carrera</h3>
                <p class="text-xs text-gray-500 mb-4">Ingresa el nombre oficial de la nueva carrera.</p>
                
                <form wire:submit.prevent="guardar" class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 uppercase mb-1">Nombre de la Carrera</label>
                        <input type="text" wire:model="nombre" placeholder="Ej. Ingeniería en Computación" class="w-full rounded-lg border border-gray-300 p-2 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-hidden">
                        @error('nombre') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex justify-end space-x-2 pt-2">
                        <button type="button" wire:click="$set('mostrarModalCrear', false)" class="cursor-pointer text-sm font-medium px-4 py-2 text-gray-500 hover:text-gray-700">Cancelar</button>
                        <button type="submit" class="cursor-pointer text-sm font-medium bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Guardar Registro</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if($mostrarModalEditar)
        <div class="fixed inset-0 bg-black/40 backdrop-blur-xs flex items-center justify-center z-50">
            <div class="bg-white rounded-xl p-6 max-w-md w-full shadow-xl border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-1">Modificar Carrera</h3>
                <p class="text-xs text-gray-500 mb-4">Cambia el nombre de la carrera seleccionada.</p>
                
                <form wire:submit.prevent="actualizar" class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 uppercase mb-1">Nombre de la Carrera</label>
                        <input type="text" wire:model="nombre" class="w-full rounded-lg border border-gray-300 p-2 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-hidden">
                        @error('nombre') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex justify-end space-x-2 pt-2">
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
                <div class="mx-auto w-12 h-12 bg-red-50 text-red-500 rounded-full flex items-center justify-center mb-4 text-xl">
                    ⚠️
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-1">¿Eliminar registro?</h3>
                <p class="text-sm text-gray-500 mb-6">Esta acción borrará la carrera del sistema de forma permanente.</p>
                <div class="flex justify-center space-x-2">
                    <button type="button" wire:click="$set('mostrarModalEliminar', false)" class="cursor-pointer text-sm font-medium px-4 py-2 text-gray-500 hover:text-gray-700">Cancelar</button>
                    <button type="button" wire:click="eliminar" class="cursor-pointer text-sm font-medium bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">Sí, eliminar</button>
                </div>
            </div>
        </div>
    @endif
</div>