<div class="space-y-6">
    <div class="flex justify-between items-center border-b border-gray-200 pb-4">
        <div>
            <h1 class="text-xl font-bold text-gray-900 tracking-tight">Panel de Vacantes</h1>
            <p class="text-sm text-gray-500">Publica nuevas ofertas de Servicio Social y controla sus características generales.</p>
        </div>
        <button wire:click="abrirModalCrear" class="cursor-pointer bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm px-4 py-2 rounded-lg transition-colors shadow-xs">
            Publicar Vacante
        </button>
    </div>

    @if (session()->has('mensaje'))
        <div class="w-full p-4 bg-green-50 border border-green-200 text-green-800 text-sm rounded-lg shadow-xs">
            {{ session('mensaje') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-xs border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Vacante / Empresa</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Registros Recibidos</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Cupo Máximo</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Postulantes</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($vacantes as $vacante)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-gray-900">{{ $vacante->titulo }}</div>
                                <div class="text-xs text-gray-500">{{ $vacante->empresa_nombre }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm font-mono font-semibold text-gray-700">
                                {{ $vacante->solicitudes_count ?? 0 }} / {{ $vacante->limite_registros }}
                            </td>
                            <td class="px-6 py-4 text-sm font-mono text-gray-600">
                                 {{ $vacante->solicitudes_aceptadas_count ?? 0 }} / {{ $vacante->cupo_maximo }} lugares
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($vacante->estaCerradaManualmente())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Cerrada por empresa</span>
                                @elseif(($vacante->solicitudes_aceptadas_count ?? 0) >= $vacante->cupo_maximo)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Cupo cubierto</span>
                                @elseif($vacante->estaCerrada())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Cerrada por registros</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Abierta</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button wire:click="verPostulaciones({{ $vacante->id }})" class="cursor-pointer bg-gray-100 hover:bg-blue-50 hover:text-blue-600 text-gray-700 px-3 py-1.5 rounded-md text-xs font-semibold transition-colors border border-gray-200 flex items-center gap-1">
                                    Ver Alumnos ({{ $vacante->solicitudes_count ?? 0 }})
                                </button>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                <button wire:click="abrirModalEditar({{ $vacante->id }})" class="cursor-pointer text-blue-600 hover:text-blue-800 font-semibold">Editar</button>
                                <button wire:click="confirmarCambioEstado({{ $vacante->id }})" class="cursor-pointer {{ !$vacante->estaCerradaManualmente() ? 'text-amber-600 hover:text-amber-800' : 'text-green-600 hover:text-green-800' }} font-semibold">
                                    {{ !$vacante->estaCerradaManualmente() ? 'Cerrar' : 'Reabrir' }}
                                </button>
                                <button wire:click="confirmarEliminar({{ $vacante->id }})" class="cursor-pointer text-red-500 hover:text-red-700 font-semibold">Eliminar</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-gray-400 text-sm">No se han publicado vacantes todavía.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">{{ $vacantes->links() }}</div>
    </div>

    @if($mostrarModalPostulantes)
        <div class="fixed inset-0 bg-black/40 backdrop-blur-xs flex items-center justify-center z-50">
            <div class="bg-white rounded-xl p-6 max-w-4xl w-full shadow-xl border border-gray-100 max-h-[85vh] overflow-y-auto">
                <div class="flex justify-between items-start border-b border-gray-100 pb-3 mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Alumnos Inscritos</h3>
                        <p class="text-xs text-gray-500">Aspirantes para la vacante: <span class="font-semibold text-blue-600">{{ $vacante_seleccionada_titulo }}</span></p>
                    </div>
                    <button wire:click="$set('mostrarModalPostulantes', false)" class="text-gray-400 hover:text-gray-600 font-bold text-lg cursor-pointer">&times;</button>
                </div>

                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left font-semibold text-gray-600">Alumno</th>
                                <th class="px-4 py-2 text-center font-semibold text-gray-600">Documentos (Ver en Sistema)</th>
                                <th class="px-4 py-2 text-center font-semibold text-gray-600">Estatus Actual</th>
                                <th class="px-4 py-2 text-right font-semibold text-gray-600">Acciones de Match</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($solicitudes_reales as $solicitud)
                                <tr class="hover:bg-gray-50/50">
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-gray-900">Estudiante Registrado</div>
                                        <div class="text-xs text-gray-400 font-mono">ID Registro: #{{ $solicitud->id }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-center space-x-1 whitespace-nowrap">
                                        <button type="button" wire:click="abrirPdf({{ $solicitud->id }}, 'cv')" class="cursor-pointer px-2.5 py-1 bg-blue-50 text-blue-700 hover:bg-blue-100 text-xs rounded-md font-semibold transition-colors">Ver CV</button>
                                        <button type="button" wire:click="abrirPdf({{ $solicitud->id }}, 'carta')" class="cursor-pointer px-2.5 py-1 bg-amber-50 text-amber-700 hover:bg-amber-100 text-xs rounded-md font-semibold transition-colors">Ver Carta</button>
                                        <button type="button" wire:click="abrirPdf({{ $solicitud->id }}, 'historial')" class="cursor-pointer px-2.5 py-1 bg-purple-50 text-purple-700 hover:bg-purple-100 text-xs rounded-md font-semibold transition-colors">Ver Historial</button>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if($solicitud->estatus == 'pendiente')
                                            <span class="px-2 py-0.5 text-xs font-medium bg-amber-100 text-amber-800 rounded-full">Pendiente</span>
                                        @elseif($solicitud->estatus == 'aceptado')
                                            @if($solicitud->confirmada_por_alumno)
                                                <span class="px-2 py-0.5 text-xs font-medium bg-green-100 text-green-800 rounded-full">Confirmado</span>
                                            @else
                                                <span class="px-2 py-0.5 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">En espera del alumno</span>
                                            @endif
                                        @else
                                            <span class="px-2 py-0.5 text-xs font-medium bg-red-100 text-red-800 rounded-full">Rechazado</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right space-x-2 whitespace-nowrap">
                                        @if($solicitud->estatus == 'pendiente' && ($vacantes->firstWhere('id', $vacante_id)?->solicitudes_aceptadas_count ?? 0) < ($vacantes->firstWhere('id', $vacante_id)?->cupo_maximo ?? PHP_INT_MAX))
                                            <button type="button" wire:click="aceptarSolicitud({{ $solicitud->id }})" class="cursor-pointer bg-green-600 hover:bg-green-700 text-white text-xs font-bold px-3 py-1 rounded-md transition-colors shadow-2xs">Aceptar</button>
                                            <button type="button" wire:click="rechazarSolicitud({{ $solicitud->id }})" class="cursor-pointer bg-red-100 hover:bg-red-200 text-red-700 text-xs font-bold px-3 py-1 rounded-md transition-colors">Rechazar</button>
                                        @elseif($solicitud->estatus == 'pendiente')
                                            <span class="text-xs text-red-400 italic">Cupo lleno</span>
                                        @elseif($solicitud->estatus == 'aceptado' && !$solicitud->confirmada_por_alumno)
                                            <span class="text-xs text-blue-500 italic">
                                                Respuesta límite:
                                                {{ $solicitud->fecha_limite_respuesta?->format('d/m H:i') }}
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-400 italic">Evaluado</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-8 text-gray-400 text-xs">Ningún alumno se ha postulado a esta oferta todavía.</td>
                                endforeach
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-end pt-4 mt-4 border-t border-gray-100">
                    <button type="button" wire:click="$set('mostrarModalPostulantes', false)" class="cursor-pointer bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-medium px-4 py-2 rounded-lg">
                        Cerrar Ventana
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if($mostrarVisorPdf)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-xs flex items-center justify-center z-[60]">
            <div class="bg-white rounded-xl p-4 max-w-5xl w-full h-[90vh] shadow-2xl flex flex-col">
                <div class="flex justify-between items-center border-b border-gray-100 pb-2 mb-2">
                    <span class="text-sm font-bold text-gray-700">Previsualización de Documento Oficial (Sin descargas)</span>
                    <button type="button" wire:click="$set('mostrarVisorPdf', false)" class="text-gray-400 hover:text-gray-600 font-bold text-xl cursor-pointer">&times;</button>
                </div>
                <div class="flex-1 bg-gray-100 rounded-lg overflow-hidden">
                    <iframe src="{{ $pdf_url }}" class="w-full h-full border-0"></iframe>
                </div>
            </div>
        </div>
    @endif

    @if($mostrarModalCrear)
        <div class="fixed inset-0 bg-black/40 backdrop-blur-xs flex items-center justify-center z-50">
            <div class="bg-white rounded-xl p-6 max-w-xl w-full shadow-xl border border-gray-100 max-h-[90vh] overflow-y-auto">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Nueva Vacante de Servicio Social</h3>
                
                <form wire:submit.prevent="guardar" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 uppercase mb-1">Nombre de la Empresa</label>
                            <input type="text" wire:model="empresa_nombre" readonly class="w-full rounded-lg border border-gray-300 bg-gray-50 p-2 text-sm text-gray-500 outline-hidden">
                            @error('empresa_nombre') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 uppercase mb-1">Título de la Vacante</label>
                            <input type="text" wire:model="titulo" placeholder="Ej. Desarrollador" class="w-full rounded-lg border border-gray-300 p-2 text-sm outline-hidden">
                            @error('titulo') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 uppercase mb-1">Descripción</label>
                        <textarea wire:model="descripcion" rows="2" class="w-full rounded-lg border border-gray-300 p-2 text-sm outline-hidden"></textarea>
                        @error('descripcion') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 uppercase mb-1">Requisitos</label>
                        <textarea wire:model="requisitos" rows="2" class="w-full rounded-lg border border-gray-300 p-2 text-sm outline-hidden"></textarea>
                        @error('requisitos') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 uppercase mb-1">Horario</label>
                            <input type="text" wire:model="horario" class="w-full rounded-lg border border-gray-300 p-2 text-sm outline-hidden">
                            @error('horario') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 uppercase mb-1">Ubicación</label>
                            <input type="text" wire:model="ubicacion" class="w-full rounded-lg border border-gray-300 p-2 text-sm outline-hidden">
                            @error('ubicacion') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 p-4 rounded-xl border border-gray-200">
                        <div>
                            <label class="block text-xs font-bold text-gray-800 uppercase mb-1">Cupo Máximo Aceptado</label>
                            <input type="number" wire:model="cupo_maximo" class="w-full bg-white rounded-lg border border-gray-300 p-2 text-sm outline-hidden">
                            @error('cupo_maximo') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-800 uppercase mb-1">Límite de Registros (CVs)</label>
                            <input type="number" wire:model="limite_registros" class="w-full bg-white rounded-lg border border-gray-300 p-2 text-sm outline-hidden">
                            @error('limite_registros') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="flex justify-end space-x-2 pt-4 border-t border-gray-100">
                        <button type="button" wire:click="$set('mostrarModalCrear', false)" class="cursor-pointer text-sm font-medium px-4 py-2 text-gray-500 hover:text-gray-700">Cancelar</button>
                        <button type="submit" class="cursor-pointer text-sm font-medium bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Lanzar Convocatoria</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if($mostrarModalEditar)
        <div class="fixed inset-0 bg-black/40 backdrop-blur-xs flex items-center justify-center z-50">
            <div class="bg-white rounded-xl p-6 max-w-xl w-full shadow-xl border border-gray-100 max-h-[90vh] overflow-y-auto">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Modificar Datos de la Vacante</h3>
                
                <form wire:submit.prevent="actualizar" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 uppercase mb-1">Nombre de la Empresa</label>
                            <input type="text" wire:model="empresa_nombre" readonly class="w-full rounded-lg border border-gray-300 bg-gray-50 p-2 text-sm text-gray-500 outline-hidden">
                            @error('empresa_nombre') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 uppercase mb-1">Título de la Vacante</label>
                            <input type="text" wire:model="titulo" class="w-full rounded-lg border border-gray-300 p-2 text-sm outline-hidden">
                            @error('titulo') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 uppercase mb-1">Descripción</label>
                        <textarea wire:model="descripcion" rows="3" class="w-full rounded-lg border border-gray-300 p-2 text-sm outline-hidden"></textarea>
                        @error('descripcion') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 uppercase mb-1">Requisitos</label>
                        <textarea wire:model="requisitos" rows="2" class="w-full rounded-lg border border-gray-300 p-2 text-sm outline-hidden"></textarea>
                        @error('requisitos') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 uppercase mb-1">Horario</label>
                            <input type="text" wire:model="horario" class="w-full rounded-lg border border-gray-300 p-2 text-sm outline-hidden">
                            @error('horario') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 uppercase mb-1">Ubicación</label>
                            <input type="text" wire:model="ubicacion" class="w-full rounded-lg border border-gray-300 p-2 text-sm outline-hidden">
                            @error('ubicacion') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 p-4 rounded-xl border border-gray-200">
                        <div>
                            <label class="block text-xs font-bold text-gray-800 uppercase mb-1">Cupo Máximo Aceptado</label>
                            <input type="number" wire:model="cupo_maximo" class="w-full bg-white rounded-lg border border-gray-300 p-2 text-sm outline-hidden">
                            @error('cupo_maximo') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-800 uppercase mb-1">Límite de Registros (CVs)</label>
                            <input type="number" wire:model="limite_registros" class="w-full bg-white rounded-lg border border-gray-300 p-2 text-sm outline-hidden">
                            @error('limite_registros') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
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
                <div class="mx-auto w-12 h-12 bg-red-50 text-red-600 rounded-full flex items-center justify-center mb-4 text-xs font-bold uppercase tracking-[0.2em]">Alerta</div>
                <h3 class="text-lg font-bold text-gray-900 mb-1">¿Remover Vacante?</h3>
                <p class="text-sm text-gray-500 mb-6">Se borrará la oferta del sistema de forma permanente.</p>
                <div class="flex justify-center space-x-2">
                    <button type="button" wire:click="$set('mostrarModalEliminar', false)" class="cursor-pointer text-sm font-medium px-4 py-2 text-gray-500 hover:text-gray-700">Cancelar</button>
                    <button type="button" wire:click="eliminar" class="cursor-pointer text-sm font-medium bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">Eliminar por Completo</button>
                </div>
            </div>
        </div>
    @endif

    @if($mostrarModalEstado)
        <div class="fixed inset-0 bg-black/40 backdrop-blur-xs flex items-center justify-center z-50">
            <div class="bg-white rounded-xl p-6 max-w-sm w-full shadow-xl text-center border border-gray-100">
                <div class="mx-auto w-12 h-12 {{ $vacante_estado_activa ? 'bg-amber-50 text-amber-700' : 'bg-green-50 text-green-700' }} rounded-full flex items-center justify-center mb-4 text-xs font-bold uppercase tracking-[0.2em]">
                    {{ $vacante_estado_activa ? 'Cerrar' : 'Abrir' }}
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-1">
                    {{ $vacante_estado_activa ? '¿Cerrar vacante?' : '¿Reabrir vacante?' }}
                </h3>
                <p class="text-sm text-gray-500 mb-6">
                    {{ $vacante_estado_activa ? 'La vacante dejará de estar disponible para nuevas postulaciones.' : 'La vacante volverá a mostrarse como disponible para los alumnos.' }}
                </p>
                <div class="flex justify-center space-x-2">
                    <button type="button" wire:click="$set('mostrarModalEstado', false)" class="cursor-pointer text-sm font-medium px-4 py-2 text-gray-500 hover:text-gray-700">Cancelar</button>
                    <button type="button" wire:click="cambiarEstado" class="cursor-pointer text-sm font-medium {{ $vacante_estado_activa ? 'bg-amber-600 hover:bg-amber-700' : 'bg-green-600 hover:bg-green-700' }} text-white px-4 py-2 rounded-lg">
                        {{ $vacante_estado_activa ? 'Sí, cerrar' : 'Sí, reabrir' }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
