<div class="space-y-6">
    <div class="border-b border-gray-200 pb-4">
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Vacantes Disponibles</h1>
        <p class="text-sm text-gray-500">Explora las opciones de Servicio Social activas en la Facultad de Ingeniería.</p>
    </div>

    @if (session()->has('mensaje'))
        <div class="rounded-2xl border border-green-200 bg-green-50 p-4 shadow-xs">
            <p class="text-sm font-semibold text-green-900">{{ session('mensaje') }}</p>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="rounded-2xl border border-red-200 bg-red-50 p-4 shadow-xs">
            <p class="text-sm font-semibold text-red-900">{{ session('error') }}</p>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-xs border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Puesto / Empresa</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ubicación</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Horario</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Disponibilidad</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Detalles</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($vacantes as $vacante)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900">{{ $vacante->titulo }}</div>
                                <div class="text-xs text-gray-500">{{ $vacante->empresa_nombre }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $vacante->ubicacion }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $vacante->horario }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($vacante->estaCerrada())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Sin cupo</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Disponible</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('alumno.vacantes.detalle', $vacante->id) }}" wire:navigate class="inline-flex items-center bg-blue-50 hover:bg-blue-600 text-blue-700 hover:text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors border border-blue-100 shadow-2xs">
                                    Ver Detalle →
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-12 text-gray-400 text-sm">No hay vacantes publicadas en este momento.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">{{ $vacantes->links() }}</div>
    </div>
</div>
