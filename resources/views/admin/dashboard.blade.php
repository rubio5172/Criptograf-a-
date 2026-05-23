<x-layouts.admin>
    <x-slot:title>
        Dashboard - Servicio Social
    </x-slot:title>

    <div class="space-y-6">
        <div class="border-b border-gray-100 pb-4">
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Panel Administrativo</h1>
            <p class="text-sm text-gray-500">Vista general del sistema, crecimiento académico y operación de vacantes.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
            <div class="rounded-2xl border border-blue-100 bg-blue-50 p-5">
                <span class="text-xs font-semibold uppercase tracking-[0.2em] text-blue-700">Carreras</span>
                <p class="mt-3 text-3xl font-bold text-blue-950">{{ $metricas['carreras'] }}</p>
                <p class="mt-2 text-sm text-blue-800">Programas registrados en el sistema.</p>
            </div>
            <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-5">
                <span class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700">Alumnos</span>
                <p class="mt-3 text-3xl font-bold text-emerald-950">{{ $metricas['alumnos'] }}</p>
                <p class="mt-2 text-sm text-emerald-800">Perfiles estudiantiles activos.</p>
            </div>
            <div class="rounded-2xl border border-amber-100 bg-amber-50 p-5">
                <span class="text-xs font-semibold uppercase tracking-[0.2em] text-amber-700">Empresas</span>
                <p class="mt-3 text-3xl font-bold text-amber-950">{{ $metricas['empresas'] }}</p>
                <p class="mt-2 text-sm text-amber-800">Instituciones registradas con acceso.</p>
            </div>
            <div class="rounded-2xl border border-fuchsia-100 bg-fuchsia-50 p-5">
                <span class="text-xs font-semibold uppercase tracking-[0.2em] text-fuchsia-700">Solicitudes</span>
                <p class="mt-3 text-3xl font-bold text-fuchsia-950">{{ $metricas['solicitudes'] }}</p>
                <p class="mt-2 text-sm text-fuchsia-800">Postulaciones generadas por alumnos.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <div class="xl:col-span-2 rounded-2xl border border-gray-200 bg-white p-6 shadow-xs">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Vacantes Recientes</h2>
                        <p class="text-sm text-gray-500">Seguimiento rápido de publicaciones y avance de ocupación.</p>
                    </div>
                </div>

                <div class="mt-4 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-gray-500 uppercase tracking-wider text-xs">Vacante</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-500 uppercase tracking-wider text-xs">Registros</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-500 uppercase tracking-wider text-xs">Aceptados</th>
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
                                    <td class="px-4 py-3 text-gray-700">{{ $vacante->solicitudes_count }} / {{ $vacante->limite_registros }}</td>
                                    <td class="px-4 py-3 text-gray-700">{{ $vacante->solicitudes_aceptadas_count }} / {{ $vacante->cupo_maximo }}</td>
                                    <td class="px-4 py-3 text-gray-700">{{ $vacante->solicitudes_confirmadas_count }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-sm text-gray-400">Aún no hay vacantes registradas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-xs">
                <div class="border-b border-gray-100 pb-3">
                    <h2 class="text-lg font-bold text-gray-900">Estado de Solicitudes</h2>
                    <p class="text-sm text-gray-500">Semáforo operativo del proceso de asignación.</p>
                </div>

                <div class="mt-4 space-y-3">
                    <div class="flex items-center justify-between rounded-xl bg-amber-50 px-4 py-3">
                        <span class="text-sm font-medium text-amber-900">Pendientes</span>
                        <span class="text-lg font-bold text-amber-950">{{ $estadoSolicitudes['pendientes'] }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-xl bg-blue-50 px-4 py-3">
                        <span class="text-sm font-medium text-blue-900">Por confirmar</span>
                        <span class="text-lg font-bold text-blue-950">{{ $estadoSolicitudes['por_confirmar'] }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-xl bg-emerald-50 px-4 py-3">
                        <span class="text-sm font-medium text-emerald-900">Confirmadas</span>
                        <span class="text-lg font-bold text-emerald-950">{{ $estadoSolicitudes['confirmadas'] }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-xl bg-rose-50 px-4 py-3">
                        <span class="text-sm font-medium text-rose-900">Rechazadas</span>
                        <span class="text-lg font-bold text-rose-950">{{ $estadoSolicitudes['rechazadas'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-xs">
            <div class="border-b border-gray-100 pb-3">
                <h2 class="text-lg font-bold text-gray-900">Carreras con Más Alumnos</h2>
                <p class="text-sm text-gray-500">Referencia rápida de concentración académica.</p>
            </div>

            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-4">
                @forelse($carrerasConAlumnos as $carrera)
                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                        <p class="text-sm font-semibold text-gray-900">{{ $carrera->nombre }}</p>
                        <p class="mt-2 text-2xl font-bold text-gray-900">{{ $carrera->alumnos_count }}</p>
                        <p class="text-xs text-gray-500">alumnos registrados</p>
                    </div>
                @empty
                    <div class="md:col-span-2 xl:col-span-5 rounded-xl border border-dashed border-gray-200 p-6 text-center text-sm text-gray-400">
                        No hay carreras con alumnos registrados todavía.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts.admin>
