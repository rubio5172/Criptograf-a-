<!DOCTYPE html>
<html lang="es" class="h-full bg-gray-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Panel Empresa - Servicio Social' }}</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    @livewireStyles
</head>

<body class="h-full flex flex-col font-sans antialiased text-gray-900">
    @php
        $links = [
            ['label' => 'Resumen', 'route' => 'empresa.dashboard', 'active' => 'empresa.dashboard'],
            ['label' => 'Gestionar Vacantes', 'route' => 'empresa.vacantes.index', 'active' => 'empresa.vacantes.*'],
        ];
    @endphp

    <nav class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex min-h-16 flex-col gap-3 py-3 sm:flex-row sm:items-center sm:justify-between sm:py-0">
                <div class="flex items-center gap-6">
                    <a href="{{ route('empresa.dashboard') }}" class="shrink-0">
                        <span class="text-lg font-bold tracking-tight text-gray-900">
                            Servicio<span class="text-blue-600">Social</span>
                        </span>
                    </a>

                    <div class="flex flex-wrap gap-2">
                        @foreach($links as $link)
                            @php
                                $active = request()->routeIs($link['active']);
                            @endphp
                            <a href="{{ route($link['route']) }}"
                                class="{{ $active ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }} rounded-lg px-3 py-2 text-sm font-medium transition-colors">
                                {{ $link['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center gap-3 text-xs">
                    <span class="rounded-full bg-emerald-50 px-3 py-1 font-semibold uppercase tracking-[0.2em] text-emerald-700">
                        Empresa
                    </span>
                    <span class="font-medium text-gray-500">{{ auth()->user()?->empresa?->nombre ?? auth()->user()?->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="cursor-pointer rounded-lg border border-gray-200 px-3 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-gray-500 transition hover:border-gray-300 hover:text-gray-700">
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-1 max-w-7xl w-full mx-auto p-4 sm:p-6 lg:p-8">
        <div class="animate-fade-in">
            {{ $slot }}
        </div>
    </main>

    <footer class="bg-white border-t border-gray-100 py-4 text-center text-xs text-gray-400">
        &copy; {{ date('Y') }} - Facultad de Ingeniería UNAM. Todos los derechos reservados.
    </footer>
</body>

</html>
