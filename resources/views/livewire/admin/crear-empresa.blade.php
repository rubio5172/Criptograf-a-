<div class="space-y-6">
    <div class="flex justify-between items-center border-b border-gray-200 pb-4">
        <div>
            <h1 class="text-xl font-bold text-gray-900 tracking-tight">Control de Empresas</h1>
            <p class="text-sm text-gray-500">Administra las empresas registradas y sus accesos al sistema.</p>
        </div>
        <button wire:click="abrirModalCrear" class="cursor-pointer bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm px-4 py-2 rounded-lg transition-colors shadow-xs">
            Registrar Empresa
        </button>
    </div>

    @if (session()->has('mensaje'))
        <div class="w-full p-4 bg-green-50 border border-green-200 text-green-800 text-sm rounded-lg shadow-xs">
            {{ session('mensaje') }}
        </div>
    @endif

    <div class="rounded-xl border border-blue-100 bg-blue-50 p-4">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-blue-700">Acceso de la Empresa</p>
        <p class="mt-1 text-sm text-blue-900">El usuario de acceso será el RFC. La contraseña inicial la define el administrador y después la empresa podrá conservarla o cambiarla.</p>
    </div>

    <div class="bg-white rounded-xl shadow-xs border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Empresa</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">RFC</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Sector</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Contacto</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($empresas as $empresa)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-gray-900">{{ $empresa->nombre }}</div>
                                <div class="text-xs text-gray-500">{{ $empresa->direccion }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-700">{{ $empresa->rfc }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $empresa->sector }}</td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $empresa->contacto_nombre }}</div>
                                <div class="text-xs text-gray-500">{{ $empresa->contacto_email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($empresa->activa)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Activa</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Inactiva</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                <button wire:click="abrirModalEditar({{ $empresa->id }})" class="cursor-pointer text-blue-600 hover:text-blue-800 font-semibold">Editar</button>
                                <button wire:click="confirmarEliminar({{ $empresa->id }})" class="cursor-pointer text-red-500 hover:text-red-700 font-semibold">Eliminar</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-gray-400 text-sm">
                                No hay empresas registradas en este momento.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $empresas->links() }}
        </div>
    </div>

    @if($mostrarModalCrear)
        <div class="fixed inset-0 bg-black/40 backdrop-blur-xs flex items-center justify-center z-50">
            <div class="bg-white rounded-xl p-6 max-w-2xl w-full shadow-xl border border-gray-100 max-h-[90vh] overflow-y-auto">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Registrar Nueva Empresa</h3>

                <form wire:submit.prevent="guardar" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 uppercase mb-1">Nombre de la Empresa</label>
                            <input type="text" wire:model="nombre" class="w-full rounded-lg border border-gray-300 p-2 text-sm outline-hidden">
                            @error('nombre') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 uppercase mb-1">RFC</label>
                            <input type="text" wire:model="rfc" class="w-full rounded-lg border border-gray-300 p-2 text-sm outline-hidden">
                            <p class="mt-1 text-xs text-gray-500">Este RFC se usará como usuario de acceso.</p>
                            @error('rfc') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 uppercase mb-1">Sector</label>
                            <input type="text" wire:model="sector" class="w-full rounded-lg border border-gray-300 p-2 text-sm outline-hidden">
                            @error('sector') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 uppercase mb-1">Teléfono</label>
                            <input type="text" wire:model="telefono" class="w-full rounded-lg border border-gray-300 p-2 text-sm outline-hidden">
                            @error('telefono') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 uppercase mb-1">Dirección</label>
                        <input type="text" wire:model="direccion" class="w-full rounded-lg border border-gray-300 p-2 text-sm outline-hidden">
                        @error('direccion') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 uppercase mb-1">Nombre del Contacto</label>
                            <input type="text" wire:model="contacto_nombre" class="w-full rounded-lg border border-gray-300 p-2 text-sm outline-hidden">
                            @error('contacto_nombre') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 uppercase mb-1">Correo de Acceso</label>
                            <input type="email" wire:model="contacto_email" class="w-full rounded-lg border border-gray-300 p-2 text-sm outline-hidden">
                            @error('contacto_email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 p-4 rounded-xl border border-gray-200">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 uppercase mb-1">Contraseña</label>
                            <input type="password" wire:model="password" class="w-full rounded-lg border border-gray-300 p-2 text-sm outline-hidden">
                            @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 uppercase mb-1">Confirmar Contraseña</label>
                            <input type="password" wire:model="password_confirmation" class="w-full rounded-lg border border-gray-300 p-2 text-sm outline-hidden">
                        </div>
                    </div>

                    <div class="flex justify-end space-x-2 pt-4 border-t border-gray-100">
                        <button type="button" wire:click="$set('mostrarModalCrear', false)" class="cursor-pointer text-sm font-medium px-4 py-2 text-gray-500 hover:text-gray-700">Cancelar</button>
                        <button type="submit" class="cursor-pointer text-sm font-medium bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Guardar Empresa</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if($mostrarModalEditar)
        <div class="fixed inset-0 bg-black/40 backdrop-blur-xs flex items-center justify-center z-50">
            <div class="bg-white rounded-xl p-6 max-w-2xl w-full shadow-xl border border-gray-100 max-h-[90vh] overflow-y-auto">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Modificar Empresa</h3>

                <form wire:submit.prevent="actualizar" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 uppercase mb-1">Nombre de la Empresa</label>
                            <input type="text" wire:model="nombre" class="w-full rounded-lg border border-gray-300 p-2 text-sm outline-hidden">
                            @error('nombre') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 uppercase mb-1">RFC</label>
                            <input type="text" wire:model="rfc" class="w-full rounded-lg border border-gray-300 p-2 text-sm outline-hidden">
                            <p class="mt-1 text-xs text-gray-500">El usuario de acceso se actualizará con este RFC.</p>
                            @error('rfc') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 uppercase mb-1">Sector</label>
                            <input type="text" wire:model="sector" class="w-full rounded-lg border border-gray-300 p-2 text-sm outline-hidden">
                            @error('sector') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 uppercase mb-1">Teléfono</label>
                            <input type="text" wire:model="telefono" class="w-full rounded-lg border border-gray-300 p-2 text-sm outline-hidden">
                            @error('telefono') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 uppercase mb-1">Dirección</label>
                        <input type="text" wire:model="direccion" class="w-full rounded-lg border border-gray-300 p-2 text-sm outline-hidden">
                        @error('direccion') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 uppercase mb-1">Nombre del Contacto</label>
                            <input type="text" wire:model="contacto_nombre" class="w-full rounded-lg border border-gray-300 p-2 text-sm outline-hidden">
                            @error('contacto_nombre') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 uppercase mb-1">Correo de Acceso</label>
                            <input type="email" wire:model="contacto_email" class="w-full rounded-lg border border-gray-300 p-2 text-sm outline-hidden">
                            @error('contacto_email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 p-4 rounded-xl border border-gray-200">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 uppercase mb-1">Nueva Contraseña</label>
                            <input type="password" wire:model="password" class="w-full rounded-lg border border-gray-300 p-2 text-sm outline-hidden">
                            @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 uppercase mb-1">Confirmar Nueva Contraseña</label>
                            <input type="password" wire:model="password_confirmation" class="w-full rounded-lg border border-gray-300 p-2 text-sm outline-hidden">
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-xs text-gray-500">Si dejas la contraseña vacía, el acceso actual de la empresa se conservará.</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 rounded-xl border border-gray-200 bg-gray-50 px-4 py-3">
                        <input id="activa" type="checkbox" wire:model="activa" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <label for="activa" class="text-sm text-gray-700">Empresa activa en el sistema</label>
                    </div>

                    <div class="flex justify-end space-x-2 pt-4 border-t border-gray-100">
                        <button type="button" wire:click="$set('mostrarModalEditar', false)" class="cursor-pointer text-sm font-medium px-4 py-2 text-gray-500 hover:text-gray-700">Cancelar</button>
                        <button type="submit" class="cursor-pointer text-sm font-medium bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Guardar Cambios</button>
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
                <h3 class="text-lg font-bold text-gray-900 mb-1">¿Eliminar Empresa?</h3>
                <p class="text-sm text-gray-500 mb-6">La empresa y su usuario de acceso serán eliminados del sistema.</p>
                <div class="flex justify-center space-x-2">
                    <button type="button" wire:click="$set('mostrarModalEliminar', false)" class="cursor-pointer text-sm font-medium px-4 py-2 text-gray-500 hover:text-gray-700">Cancelar</button>
                    <button type="button" wire:click="eliminar" class="cursor-pointer text-sm font-medium bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">Sí, eliminar</button>
                </div>
            </div>
        </div>
    @endif
</div>
