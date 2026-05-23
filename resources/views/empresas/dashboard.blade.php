<x-layouts.empresa>
    <x-slot:title>
        Dashboard - Servicio Social
    </x-slot:title>

    <div class="space-y-6">
        <div class="border-b border-gray-100 pb-4">
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Panel de Empresa</h1>
            <p class="text-sm text-gray-500">Seguimiento operativo de vacantes, postulaciones y confirmaciones.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-4">
            <div class="rounded-2xl border border-blue-100 bg-blue-50 p-5">
                <span class="text-xs font-semibold uppercase tracking-[0.2em] text-blue-700">Vacantes</span>
                <p class="mt-3 text-3xl font-bold text-blue-950">{{ $metricas['vacantes_publicadas'] }}</p>
                <p class="mt-2 text-sm text-blue-800">Total publicadas por tu empresa.</p>
            </div>
            <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-5">
                <span class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700">Abiertas</span>
                <p class="mt-3 text-3xl font-bold text-emerald-950">{{ $metricas['vacantes_abiertas'] }}</p>
                <p class="mt-2 text-sm text-emerald-800">Aún disponibles para postulación.</p>
            </div>
            <div class="rounded-2xl border border-amber-100 bg-amber-50 p-5">
                <span class="text-xs font-semibold uppercase tracking-[0.2em] text-amber-700">Pendientes</span>
                <p class="mt-3 text-3xl font-bold text-amber-950">{{ $metricas['postulaciones_pendientes'] }}</p>
                <p class="mt-2 text-sm text-amber-800">Postulaciones por revisar.</p>
            </div>
            <div class="rounded-2xl border border-cyan-100 bg-cyan-50 p-5">
                <span class="text-xs font-semibold uppercase tracking-[0.2em] text-cyan-700">Por Confirmar</span>
                <p class="mt-3 text-3xl font-bold text-cyan-950">{{ $metricas['aceptadas_por_confirmar'] }}</p>
                <p class="mt-2 text-sm text-cyan-800">Aceptaciones esperando decisión del alumno.</p>
            </div>
            <div class="rounded-2xl border border-fuchsia-100 bg-fuchsia-50 p-5">
                <span class="text-xs font-semibold uppercase tracking-[0.2em] text-fuchsia-700">Confirmadas</span>
                <p class="mt-3 text-3xl font-bold text-fuchsia-950">{{ $metricas['confirmadas'] }}</p>
                <p class="mt-2 text-sm text-fuchsia-800">Asignaciones cerradas por alumno.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <div class="xl:col-span-2 rounded-2xl border border-gray-200 bg-white p-6 shadow-xs">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Vacantes Más Recientes</h2>
                        <p class="text-sm text-gray-500">Consulta rápida del avance de las vacantes de tu empresa.</p>
                    </div>
                    <a href="{{ route('empresa.vacantes.index') }}" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 transition-colors">
                        Ir a Vacantes
                    </a>
                </div>

                <div class="mt-4 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-gray-500 uppercase tracking-wider text-xs">Vacante</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-500 uppercase tracking-wider text-xs">Estado</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-500 uppercase tracking-wider text-xs">Registros</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-500 uppercase tracking-wider text-xs">Confirmados</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($vacantesRecientes as $vacante)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="font-semibold text-gray-900">{{ $vacante->titulo }}</div>
                                        <div class="text-xs text-gray-500">{{ $vacante->empresa_nombre }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($vacante->estaDisponible())
                                            <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-800">Abierta</span>
                                        @elseif($vacante->estaCerradaManualmente())
                                            <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-800">Cerrada manualmente</span>
                                        @else
                                            <span class="inline-flex rounded-full bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-800">Cerrada por límite</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-gray-700">{{ $vacante->solicitudes_count }} / {{ $vacante->limite_registros }}</td>
                                    <td class="px-4 py-3 text-gray-700">{{ $vacante->solicitudes_confirmadas_count }} / {{ $vacante->cupo_maximo }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-sm text-gray-400">No hay vacantes registradas todavía.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-xs">
                <div class="border-b border-gray-100 pb-3">
                    <h2 class="text-lg font-bold text-gray-900">Actividad Reciente</h2>
                    <p class="text-sm text-gray-500">Últimas postulaciones recibidas en tus vacantes.</p>
                </div>

                <div class="mt-4 space-y-3">
                    @forelse($solicitudesRecientes as $solicitud)
                        <div class="rounded-xl border border-gray-200 p-4">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $solicitud->vacante?->titulo ?? 'Vacante eliminada' }}</p>
                                    <p class="text-xs text-gray-500">{{ $solicitud->created_at?->format('d/m/Y H:i') }}</p>
                                </div>
                                @if($solicitud->estatus === 'pendiente')
                                    <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-800">Pendiente</span>
                                @elseif($solicitud->estatus === 'aceptado' && $solicitud->confirmada_por_alumno)
                                    <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-800">Confirmada</span>
                                @elseif($solicitud->estatus === 'aceptado')
                                    <span class="rounded-full bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-800">Por confirmar</span>
                                @else
                                    <span class="rounded-full bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-800">Rechazada</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="rounded-xl border border-dashed border-gray-200 p-6 text-center text-sm text-gray-400">
                            Aún no hay postulaciones recientes.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-layouts.empresa>
