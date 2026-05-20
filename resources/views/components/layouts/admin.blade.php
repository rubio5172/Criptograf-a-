<!DOCTYPE html>
<html lang="es" class="h-full bg-gray-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Sistema de Gestión - Servicio Social' }}</title>

    <!-- Tailwind CSS v4 -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <!-- Estilos y Scripts globales de Livewire y Flux -->
    @livewireStyles
</head>

<body class="h-full flex flex-col font-sans antialiased text-gray-900">

    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center space-x-8">
                  
                    <div class="shrink-0 flex items-center">
                        <a href="{{ route('admin.dashboard')}}"><span class="text-lg font-bold tracking-tight text-gray-900">
                            Servicio<span class="text-blue-600">Social</span>
                        </span></a>
                    </div>
                    <!-- Enlaces de Navegación -->
                    <div class="hidden sm:flex sm:space-x-4">
                        <a href="{{ route('admin.carreras.crear') }}"
                            class="border-b-2 border-blue-600 px-1 pt-1 text-sm font-medium text-gray-900">
                            Carreras
                        </a>

                                                <a href="{{ route('admin.alumnos.crear') }}"
                            class="border-b-2 border-blue-600 px-1 pt-1 text-sm font-medium text-gray-900">
                            Alumnos
                        </a>

                                                <a href="{{ route('admin.carreras.crear') }}"
                            class="border-b-2 border-blue-600 px-1 pt-1 text-sm font-medium text-gray-900">
                            Empresas/Instituciones
                        </a>
                        
                    </div>
                </div>

              
                <div class="flex items-center text-xs text-gray-400 font-medium tracking-wider uppercase">
                    Criptografía G02
                </div>
            </div>
        </div>
    </nav>

    <!-- Contenedor del Contenido Principal -->
    <main class="flex-1 max-w-7xl w-full mx-auto p-4 sm:p-6 lg:p-8">
        <div class="animate-fade-in">
            <!-- AQUÍ LIVEWIRE INYECTA LA VISTA DEL COMPONENTE (CrearAlumno, EditarAlumno, etc.) -->
            {{ $slot }}
        </div>
    </main>

    <!-- Footer-->
    <footer class="bg-white border-t border-gray-100 py-4 text-center text-xs text-gray-400">
        &copy; {{ date('Y') }} - Facultad de Ingeniería UNAM. Todos los derechos reservados.
    </footer>

</body>

</html>
