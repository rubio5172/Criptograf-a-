<x-layouts.admin>
    <x-slot:title>
        Dashboard - Servicio Social
    </x-slot:title>

    <!-- El contenido del dashboard se inyectará en la plantilla -->
    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Panel de Control</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="p-6 bg-blue-50 border border-blue-100 rounded-xl">
                <span class="text-sm font-medium text-blue-600 block mb-1">Total Alumnos</span>
               
            </div>
            <div class="p-6 bg-green-50 border border-green-100 rounded-xl">
                <span class="text-sm font-medium text-green-600 block mb-1">Vacantes Disponibles</span>

            </div>
        </div>
    </div>
</x-layouts.admin>