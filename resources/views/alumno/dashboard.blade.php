<x-layouts.alumno>
    <x-slot:title>
        Dashboard - Servicio Social - Alumno
    </x-slot:title>
<div class="space-y-6">

    <div class="border-b border-gray-100 pb-4">
        <h1 class="text-xl font-bold text-gray-900 tracking-tight">Panel del Estudiante</h1>
        <p class="text-xs text-gray-500">Estado actual de tus solicitudes de Servicio Social.</p>
        <p class="mt-1 text-sm font-medium text-gray-700">{{ $alumno->nombre }} {{ $alumno->ap_pat }} {{ $alumno->ap_mat }}</p>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-xs">
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Tu Información</h2>
                <p class="text-sm text-gray-500">Datos del alumno autenticado en el sistema.</p>
            </div>
            <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-amber-700">
                Alumno
            </span>
        </div>

        <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-500">Matrícula</p>
                <p class="mt-2 text-lg font-bold text-gray-900">{{ $alumno->matricula }}</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-500">Carrera</p>
                <p class="mt-2 text-sm font-semibold text-gray-900">{{ $alumno->carrera?->nombre ?? 'Sin carrera asignada' }}</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-500">Semestre</p>
                <p class="mt-2 text-lg font-bold text-gray-900">{{ $alumno->semestre ?? '-' }}</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-500">Promedio</p>
                <p class="mt-2 text-lg font-bold text-gray-900">{{ $alumno->promedio ?? '-' }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="rounded-2xl border border-blue-100 bg-blue-50 p-5">
            <span class="text-xs font-semibold uppercase tracking-[0.2em] text-blue-700">Solicitudes</span>
            <p class="mt-3 text-3xl font-bold text-blue-950">{{ $metricas['solicitudes_enviadas'] }}</p>
            <p class="mt-2 text-sm text-blue-800">Postulaciones enviadas por ti.</p>
        </div>
        <div class="rounded-2xl border border-amber-100 bg-amber-50 p-5">
            <span class="text-xs font-semibold uppercase tracking-[0.2em] text-amber-700">Por Decidir</span>
            <p class="mt-3 text-3xl font-bold text-amber-950">{{ $metricas['ofertas_pendientes'] }}</p>
            <p class="mt-2 text-sm text-amber-800">Ofertas aceptadas pendientes de tu respuesta.</p>
        </div>
        <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-5">
            <span class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700">Confirmadas</span>
            <p class="mt-3 text-3xl font-bold text-emerald-950">{{ $metricas['vacantes_confirmadas'] }}</p>
            <p class="mt-2 text-sm text-emerald-800">Vacantes que ya confirmaste.</p>
        </div>
        <div class="rounded-2xl border border-rose-100 bg-rose-50 p-5">
            <span class="text-xs font-semibold uppercase tracking-[0.2em] text-rose-700">Rechazadas</span>
            <p class="mt-3 text-3xl font-bold text-rose-950">{{ $metricas['rechazadas'] }}</p>
            <p class="mt-2 text-sm text-rose-800">Solicitudes cerradas sin asignación.</p>
        </div>
    </div>

    @if (session()->has('mensaje'))
        <div class="w-full p-4 bg-green-50 border border-green-200 text-green-800 text-sm rounded-lg shadow-xs">
            {{ session('mensaje') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="w-full p-4 bg-red-50 border border-red-200 text-red-800 text-sm rounded-lg shadow-xs">
            {{ session('error') }}
        </div>
    @endif

    @forelse($ofertasPendientes as $oferta)
        <div class="bg-linear-to-r from-amber-50 to-orange-50 border border-amber-200 rounded-2xl p-6 shadow-xs animate-fade-in mb-4">
            <div class="flex items-start gap-4">
                <div class="p-3 bg-amber-500 text-white rounded-xl text-xs font-bold uppercase tracking-[0.2em] shadow-md shadow-amber-500/20">
                    Pendiente
                </div>
                <div class="space-y-2 flex-1">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
                        <h2 class="text-lg font-bold text-amber-900 tracking-tight">Oferta pendiente de tu decisión</h2>
                        <span class="px-2.5 py-0.5 text-[10px] font-bold bg-amber-200 text-amber-800 rounded-md uppercase tracking-wider w-fit">48 horas</span>
                    </div>
                    <p class="text-sm text-amber-800 leading-relaxed">
                        La empresa <span class="font-bold text-amber-950">{{ $oferta->vacante->empresa_nombre }}</span> te aceptó para la vacante
                        <span class="font-bold text-amber-950">"{{ $oferta->vacante->titulo }}"</span>. Debes decidir si quieres quedarte con esta opción antes del vencimiento.
                    </p>

                    <div class="mt-4 pt-3 border-t border-amber-200/60 grid grid-cols-1 sm:grid-cols-4 gap-4 text-xs text-amber-900">
                        <div>
                            <span class="block font-semibold uppercase text-[10px] text-amber-700/80">Modalidad / Lugar</span>
                            <span class="font-medium">{{ $oferta->vacante->ubicacion }}</span>
                        </div>
                        <div>
                            <span class="block font-semibold uppercase text-[10px] text-amber-700/80">Horario Establecido</span>
                            <span class="font-medium">{{ $oferta->vacante->horario }}</span>
                        </div>
                        <div>
                            <span class="block font-semibold uppercase text-[10px] text-amber-700/80">Fecha Límite</span>
                            <span class="font-medium font-mono">
                                {{ $oferta->fecha_limite_respuesta?->format('d/m/Y H:i') }}
                            </span>
                        </div>
                        <div>
                            <span class="block font-semibold uppercase text-[10px] text-amber-700/80">Tiempo restante</span>
                            <span class="font-medium font-mono">
                                {{ $oferta->fecha_limite_respuesta?->diffForHumans() }}
                            </span>
                        </div>
                    </div>

                    <form action="{{ route('alumno.solicitudes.confirmar', $oferta->id) }}" method="POST" class="pt-4">
                        @csrf
                        <div class="max-w-sm">
                            <label class="block text-xs font-semibold uppercase tracking-[0.2em] text-amber-700 mb-1">Código de Confirmación</label>
                            <input
                                type="text"
                                name="codigo_confirmacion"
                                value="{{ old('codigo_confirmacion') }}"
                                placeholder="Ingresa tu código"
                                class="w-full rounded-lg border border-amber-300 bg-white px-3 py-2 text-sm text-gray-900 outline-hidden focus:border-amber-500">
                            @error('codigo_confirmacion')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-amber-800">Usa el código que se generó cuando realizaste esta postulación.</p>
                        </div>
                        <button type="submit" class="cursor-pointer bg-amber-600 hover:bg-amber-700 text-white font-semibold text-sm px-5 py-2.5 rounded-lg transition-colors shadow-xs">
                            Elegir Esta Vacante
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
    @endforelse

    @forelse($asignacionesConfirmadas as $asignacion)
        <div class="bg-linear-to-r from-green-50 to-emerald-50 border border-green-200 rounded-2xl p-6 shadow-xs animate-fade-in mb-4">
            <div class="flex items-start gap-4">
                <div class="p-3 bg-green-500 text-white rounded-xl text-xs font-bold uppercase tracking-[0.2em] shadow-md shadow-green-500/20">
                    Confirmado
                </div>
                <div class="space-y-1 flex-1">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
                        <h2 class="text-lg font-bold text-green-900 tracking-tight">Vacante confirmada</h2>
                        <span class="px-2.5 py-0.5 text-[10px] font-bold bg-green-200 text-green-800 rounded-md uppercase tracking-wider w-fit">Confirmado</span>
                    </div>
                    <p class="text-sm text-green-700 leading-relaxed">
                        Confirmaste oficialmente la vacante <span class="font-bold text-green-900">"{{ $asignacion->vacante->titulo }}"</span> de la organización <span class="font-bold text-green-900">{{ $asignacion->vacante->empresa_nombre }}</span>.
                    </p>

                    <div class="mt-4 pt-3 border-t border-green-200/60 grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs text-green-800">
                        <div>
                            <span class="block font-semibold uppercase text-[10px] text-green-600/80">Modalidad / Lugar</span>
                            <span class="font-medium text-green-900">{{ $asignacion->vacante->ubicacion }}</span>
                        </div>
                        <div>
                            <span class="block font-semibold uppercase text-[10px] text-green-600/80">Horario Establecido</span>
                            <span class="font-medium text-green-900">{{ $asignacion->vacante->horario }}</span>
                        </div>
                        <div>
                            <span class="block font-semibold uppercase text-[10px] text-green-600/80">Confirmada el</span>
                            <span class="font-medium text-green-900 font-mono">
                                {{ $asignacion->respondido_en?->format('d/m/Y H:i') ?? date('d/m/Y H:i') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
    @endforelse

    @if($ofertasPendientes->isEmpty() && $asignacionesConfirmadas->isEmpty())
        <div class="bg-white p-8 rounded-xl border border-gray-200 text-center text-gray-500 text-sm max-w-xl mx-auto shadow-2xs">
            <h3 class="font-bold text-gray-800 mb-1">Sin asignaciones de momento</h3>
            <p class="text-xs text-gray-400">Aún no cuentas con respuestas de aceptación en el sistema. ¡Sigue explorando el catálogo de vacantes disponibles!</p>
        </div>
    @endif

    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-xs">
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Actividad Reciente</h2>
                <p class="text-sm text-gray-500">Tus últimas postulaciones y su estatus actual.</p>
            </div>
            <a href="{{ route('alumno.vacantes.index') }}" wire:navigate class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 transition-colors">
                Explorar Vacantes
            </a>
        </div>

        <div class="mt-4 space-y-3">
            @forelse($solicitudesRecientes as $solicitud)
                <div class="rounded-xl border border-gray-200 p-4">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-900">{{ $solicitud->vacante?->titulo ?? 'Vacante eliminada' }}</p>
                            <p class="text-xs text-gray-500">{{ $solicitud->vacante?->empresa_nombre ?? 'Sin empresa' }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-mono text-gray-400">{{ $solicitud->created_at?->format('d/m/Y H:i') }}</span>
                            @if($solicitud->estatus === 'pendiente')
                                <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-800">Pendiente</span>
                            @elseif($solicitud->estatus === 'aceptado' && $solicitud->confirmada_por_alumno)
                                <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-800">Confirmada</span>
                            @elseif($solicitud->estatus === 'aceptado')
                                <span class="rounded-full bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-800">Por decidir</span>
                            @else
                                <span class="rounded-full bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-800">Rechazada</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-xl border border-dashed border-gray-200 p-6 text-center text-sm text-gray-400">
                    Todavía no tienes postulaciones registradas.
                </div>
            @endforelse
        </div>
    </div>

</div>
</x-layouts.alumno>
