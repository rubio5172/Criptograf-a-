<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <a href="{{ route('alumno.vacantes.detalle', $vacante->id) }}" wire:navigate class="text-sm text-gray-500 hover:text-blue-600 font-medium flex items-center gap-1">
            ← Volver al detalle
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-xs border border-gray-200 p-6 sm:p-8">
        <div class="mb-6 border-b border-gray-100 pb-4">
            <h2 class="text-xl font-bold text-gray-900 tracking-tight">Formulario de Postulación</h2>
            <p class="text-sm text-gray-500">Subir documentos requeridos para: <span class="font-semibold text-blue-600">{{ $vacante->titulo }}</span></p>
        </div>

        @if (session()->has('error'))
            <div class="p-4 mb-4 bg-red-50 border border-red-200 text-red-800 text-sm rounded-lg shadow-2xs">{{ session('error') }}</div>
        @endif

        @if($vacante->estaDisponible())
            <form wire:submit.prevent="registrarSolicitud" class="space-y-5">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">1. Currículum Vitae (PDF)</label>
                    <input type="file" wire:model="file_cv" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                    @error('file_cv') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">2. Carta de Presentación (PDF)</label>
                    <input type="file" wire:model="file_carta" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                    @error('file_carta') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">3. Historial Académico Reciente (PDF)</label>
                    <input type="file" wire:model="file_historial" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                    @error('file_historial') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div wire:loading wire:target="file_cv, file_carta, file_historial" class="text-xs text-blue-600 font-medium animate-pulse">
                    ⏳ Procesando y subiendo documentos al servidor, por favor espera...
                </div>

                <div class="pt-4 border-t border-gray-100 flex justify-end">
                    <button type="submit" wire:loading.attr="disabled" class="w-full sm:w-auto cursor-pointer bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm px-6 py-2.5 rounded-lg transition-colors shadow-xs">
                        Confirmar y Enviar Solicitud
                    </button>
                </div>
            </form>
        @else
            <div class="p-4 bg-amber-50 border border-amber-200 text-amber-800 text-sm rounded-lg">
                Esta vacante fue cerrada y ya no admite nuevas postulaciones.
            </div>
        @endif
    </div>
</div>
