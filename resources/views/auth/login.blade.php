<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-100">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso al Sistema - Servicio Social</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="min-h-full bg-slate-100 text-slate-900">
    <div class="flex min-h-screen items-center justify-center px-4 py-10 sm:px-6 lg:px-8">
        <div class="grid w-full max-w-5xl overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-[0_20px_60px_rgba(15,23,42,0.10)] lg:grid-cols-[1.05fr_0.95fr]">
            <section class="hidden border-r border-slate-200 bg-slate-900 px-10 py-12 text-white lg:flex lg:flex-col lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-300">Servicio Social</p>
                    <h1 class="mt-6 text-4xl font-bold tracking-tight">Acceso institucional al sistema.</h1>
                    <p class="mt-4 max-w-md text-sm leading-7 text-slate-300">
                        Plataforma para la gestión de vacantes, seguimiento de postulaciones y control administrativo del servicio social.
                    </p>
                </div>

                <div class="space-y-4">
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-300">Alumno</p>
                        <p class="mt-2 text-sm text-white">Usuario y contraseña inicial: matrícula o número de cuenta.</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-300">Empresa</p>
                        <p class="mt-2 text-sm text-white">Usuario: RFC. Contraseña: la asignada por administración.</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-300">Administrador</p>
                        <p class="mt-2 text-sm text-white">Acceso al control general de alumnos, empresas, carreras y vacantes.</p>
                    </div>
                </div>
            </section>

            <section class="px-6 py-8 sm:px-10 sm:py-12 lg:px-12 lg:py-14">
                <div class="mx-auto max-w-md">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500">Inicio de sesión</p>
                        <h2 class="mt-3 text-3xl font-bold tracking-tight text-slate-950">Bienvenido</h2>
                        <p class="mt-3 text-sm leading-6 text-slate-500">
                            Ingresa tus credenciales para acceder al panel correspondiente a tu perfil.
                        </p>
                    </div>

                    @if (session('error'))
                        <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.attempt') }}" class="mt-8 space-y-5">
                        @csrf

                        <div>
                            <label for="username" class="block text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Usuario</label>
                            <input
                                id="username"
                                name="username"
                                type="text"
                                value="{{ old('username') }}"
                                class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-hidden transition focus:border-slate-900"
                                placeholder="Matrícula, RFC o usuario admin">
                            @error('username')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Contraseña</label>
                            <input
                                id="password"
                                name="password"
                                type="password"
                                class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-hidden transition focus:border-slate-900"
                                placeholder="Ingresa tu contraseña">
                            @error('password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between gap-4">
                            <label class="flex items-center gap-3 text-sm text-slate-600">
                                <input type="checkbox" name="remember" value="1" class="rounded border-slate-300 text-slate-900 focus:ring-slate-400">
                                <span>Mantener sesión activa</span>
                            </label>
                        </div>

                        <button type="submit" class="w-full rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
                            Ingresar
                        </button>
                    </form>

                    <div class="mt-8 rounded-2xl border border-slate-200 bg-slate-50 p-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Referencia de acceso</p>
                        <div class="mt-3 space-y-2 text-sm text-slate-600">
                            <p><span class="font-semibold text-slate-800">Alumno:</span> matrícula o número de cuenta.</p>
                            <p><span class="font-semibold text-slate-800">Empresa:</span> RFC.</p>
                            <p><span class="font-semibold text-slate-800">Administrador:</span> usuario asignado por el sistema.</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</body>

</html>
