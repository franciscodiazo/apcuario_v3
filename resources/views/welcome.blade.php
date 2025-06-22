<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Acuarius - Acueducto Rural</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <!-- Tailwind CDN para estilos si no tienes compilación local -->
        <script src="https://cdn.tailwindcss.com"></script>
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
        <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 not-has-[nav]:hidden">
            @if (Route::has('login'))
                <nav class="flex items-center justify-end gap-4">
                    @auth
                        <a
                            href="{{ url('/dashboard') }}"
                            class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal"
                        >
                            Dashboard
                        </a>
                    @else
                        <a
                            href="{{ route('login') }}"
                            class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal"
                        >
                            Iniciar sesión
                        </a>
                        @if (Route::has('register'))
                            <a
                                href="{{ route('register') }}"
                                class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                                Registrarse
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>
        <div class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow starting:opacity-0">
            <main class="flex max-w-[335px] w-full flex-col-reverse lg:max-w-4xl lg:flex-row">
                <div class="text-[13px] leading-[20px] flex-1 p-6 pb-12 lg:p-20 bg-white dark:bg-[#161615] dark:text-[#EDEDEC] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-bl-lg rounded-br-lg lg:rounded-tl-lg lg:rounded-br-none">
                    <h1 class="mb-1 font-medium text-2xl">Acuarius</h1>
                    <h2 class="mb-2 font-medium text-lg">Sistema de Lectura de Consumo de Agua para Acueducto Rural</h2>
                    <p class="mb-4 text-[#706f6c] dark:text-[#A1A09A]">
                        Bienvenido a <b>Acuarius</b>, la aplicación para la gestión de usuarios, medidores y lecturas de consumo de agua en acueductos rurales.
                    </p>
                    <h3 class="mb-2 font-medium">Accesos rápidos</h3>
                    <ul class="mb-4 list-disc list-inside">
                        <li>
                            <a href="{{ route('usuarios.index') }}" class="text-blue-600 underline hover:text-blue-800">Usuarios</a>
                        </li>
                        <li>
                            <a href="{{ route('medidores.index') }}" class="text-blue-600 underline hover:text-blue-800">Medidores</a>
                        </li>
                        <li>
                            <a href="{{ route('lecturas.index') }}" class="text-blue-600 underline hover:text-blue-800">Lecturas</a>
                        </li>
                        <li>
                            <a href="{{ route('usuarios.listado') }}" class="text-coral-700 underline hover:text-coral-900">Listado de Usuarios</a>
                        </li>
                        <li>
                            <a href="{{ route('facturas.masiva') }}" class="text-coral-700 underline hover:text-coral-900">Facturación Masiva</a>
                        </li>
                    </ul>
                    <hr class="my-4">
                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                        Desarrollado con Laravel.
                    </p>
                </div>
                <div class="bg-[#fff2f2] dark:bg-[#1D0002] relative lg:-ml-px -mb-px lg:mb-0 rounded-t-lg lg:rounded-t-none lg:rounded-r-lg aspect-[335/376] lg:aspect-auto w-full lg:w-[438px] shrink-0 overflow-hidden">
                    {{-- Laravel Logo --}}
                    {{-- ...logo SVGs aquí, igual que antes... --}}
                </div>
            </main>
        </div>
        @if (Route::has('login'))
            <div class="h-14.5 hidden lg:block"></div>
        @endif
    </body>
</html>