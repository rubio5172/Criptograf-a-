<div class="max-w-4xl mx-auto space-y-6">
    <div>
        <a href="{{ route('alumno.vacantes.index') }}" wire:navigate
            class="text-sm text-gray-500 hover:text-blue-600 transition-colors font-medium flex items-center gap-1">
            ← Volver al catálogo
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-xs border border-gray-200 overflow-hidden">
        <div class="p-6 sm:p-8 border-b border-gray-100 bg-linear-to-r from-gray-50/50 to-white">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
                <div>
                    @if($vacante->estaDisponible())
                        <span class="px-2.5 py-1 bg-blue-50 text-blue-700 rounded-md text-xs font-semibold uppercase tracking-wider">Convocatoria Abierta</span>
                    @else
                        <span class="px-2.5 py-1 bg-red-50 text-red-700 rounded-md text-xs font-semibold uppercase tracking-wider">Convocatoria Cerrada</span>
                    @endif
                    <h1 class="text-2xl font-bold text-gray-900 mt-2 tracking-tight">{{ $vacante->titulo }}</h1>
                    <p class="text-gray-500 font-medium text-sm mt-1">{{ $vacante->empresa_nombre }}</p>
                </div>

                <div>
                    @if($vacante->estaDisponible())
                        <a href="{{ route('alumno.vacantes.postular', $vacante->id) }}" wire:navigate
                            class="inline-block text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm px-5 py-2.5 rounded-lg transition-colors shadow-xs">
                            Iniciar Postulación
                        </a>
                    @else
                        <span class="inline-block text-center bg-gray-100 text-gray-500 font-semibold text-sm px-5 py-2.5 rounded-lg">
                            Vacante no disponible
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="p-6 sm:p-8 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="md:col-span-2 space-y-6">
                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Descripción de Actividades
                    </h3>
                    <div
                        class="text-sm text-gray-700 leading-relaxed bg-gray-50/50 p-4 rounded-xl border border-gray-100 whitespace-pre-line">
                        {{ $vacante->descripcion }}
                    </div>
                </div>

                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Requisitos Solicitados
                    </h3>
                    <div
                        class="text-sm text-gray-700 leading-relaxed bg-gray-50/50 p-4 rounded-xl border border-gray-100 whitespace-pre-line">
                        {{ $vacante->requisitos }}
                    </div>
                </div>
            </div>

            <div class="space-y-4 bg-gray-50 p-6 rounded-xl border border-gray-100 h-fit">
                <h4 class="text-sm font-bold text-gray-900 border-b border-gray-200 pb-2">Datos Operativos</h4>

                <div>
                    <span class="block text-[10px] font-bold text-gray-400 uppercase">Horario</span>
                    <span class="text-sm text-gray-700 font-medium">{{ $vacante->horario }}</span>
                </div>

                <div>
                    <span class="block text-[10px] font-bold text-gray-400 uppercase">Ubicación / Modalidad</span>
                    <span class="text-sm text-gray-700 font-medium">{{ $vacante->ubicacion }}</span>
                </div>

                <div class="pt-2 border-t border-gray-200 grid grid-cols-2 gap-2">
                    <div>
                        <span class="block text-[10px] font-bold text-gray-400 uppercase">Lugares Finales</span>
                        <span class="text-sm text-gray-900 font-bold font-mono">{{ $vacante->cupo_maximo }} cupos</span>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold text-gray-400 uppercase">Registros Max.</span>
                        <span class="text-sm text-gray-900 font-bold font-mono">{{ $vacante->limite_registros }}
                            fichas</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
