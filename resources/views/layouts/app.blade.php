{{-- filepath: resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Acuarius')</title>
    <!-- Tailwind CDN con configuración personalizada -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              aquarius: {
                50: '#e0f7fa',
                100: '#b2ebf2',
                200: '#80deea',
                300: '#4dd0e1',
                400: '#26c6da',
                500: '#00bcd4',
                600: '#00acc1',
                700: '#0097a7',
                800: '#00838f',
                900: '#006064',
              },
              sand: {
                50: '#fdf6e3',
                100: '#f5e9c8',
                200: '#f0d9a6',
                300: '#eac97e',
                400: '#e2b95a',
                500: '#d9a441',
                600: '#b98a34',
                700: '#8c6827',
                800: '#6b4e1b',
                900: '#4a350f',
              },
              coral: {
                50: '#fff0f0',
                100: '#ffd6d6',
                200: '#ffb3b3',
                300: '#ff8a8a',
                400: '#ff5e5e',
                500: '#ff2e2e',
                600: '#e60000',
                700: '#b30000',
                800: '#800000',
                900: '#4d0000',
              },
            },
            fontFamily: {
              display: ['Poppins', 'ui-sans-serif', 'system-ui'],
              body: ['Inter', 'ui-sans-serif', 'system-ui'],
            },
          },
        },
      }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Poppins:wght@700&display=swap" rel="stylesheet">
    <style>
      body { background: linear-gradient(120deg, #e0f7fa 0%, #fdf6e3 100%); }
      .glass {
        background: rgba(255,255,255,0.85);
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.10);
        backdrop-filter: blur(8px);
        border-radius: 1.5rem;
      }
      /* Menú desplegable al pasar el mouse */
      .group:hover .group-hover\:opacity-100 { opacity: 1 !important; pointer-events: auto !important; }
      .group:hover .group-hover\:pointer-events-auto { pointer-events: auto !important; }
      .group:focus-within .group-hover\:opacity-100 { opacity: 1 !important; pointer-events: auto !important; }
      .group:focus-within .group-hover\:pointer-events-auto { pointer-events: auto !important; }
      .group .group-hover\:opacity-100 { transition: opacity 0.2s; }
    </style>
</head>
<body class="min-h-screen flex flex-col font-body text-aquarius-900 bg-gradient-to-br from-aquarius-50 to-sand-50">
    <nav class="bg-aquarius-700/90 text-white px-8 py-4 flex justify-between items-center shadow-lg rounded-b-2xl">
        <div class="font-display text-2xl tracking-widest flex items-center gap-2">
            <svg xmlns='http://www.w3.org/2000/svg' class='h-7 w-7 text-coral-400' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6' /></svg>
            Acuarius
        </div>
        <div class="space-x-2 md:space-x-6 text-base font-semibold">
            <div class="flex flex-wrap gap-1 md:gap-2 text-xs md:text-sm">
                <a href="{{ route('dashboard') }}" class="px-2 md:px-3 py-2 rounded-lg transition hover:bg-aquarius-500/30 hover:text-coral-500">Dashboard</a>
                <a href="{{ route('usuarios.index') }}" class="px-2 md:px-3 py-2 rounded-lg transition hover:bg-aquarius-500/30 hover:text-coral-500">Usuarios</a>
                <a href="{{ route('usuarios.listado') }}" class="px-2 md:px-3 py-2 rounded-lg transition hover:bg-cyan-100 hover:text-cyan-700">Registrar Lecturas</a>
                <a href="{{ route('lecturas.index') }}" class="px-2 md:px-3 py-2 rounded-lg transition hover:bg-blue-100 hover:text-blue-700">Lecturas</a>
                <a href="{{ route('facturas.masiva') }}" class="px-2 md:px-3 py-2 rounded-lg transition hover:bg-coral-100 hover:text-coral-700">Facturación</a>
                <a href="{{ route('consumos.index') }}" class="px-2 md:px-3 py-2 rounded-lg transition hover:bg-green-100 hover:text-green-700">Cuotas/Pagos</a>
                <div class="relative group inline-block">
                    <button class="px-2 md:px-3 py-2 rounded-lg transition hover:bg-green-100 hover:text-green-700 focus:outline-none">Créditos ▾</button>
                    <div class="absolute left-0 mt-1 w-40 bg-white border border-aquarius-200 rounded shadow-lg opacity-0 group-hover:opacity-100 group-hover:pointer-events-auto pointer-events-none z-20">
                        <a href="{{ route('creditos.index') }}" class="block px-4 py-2 text-aquarius-900 hover:bg-cyan-50">Por usuario</a>
                        <a href="{{ route('creditos.general') }}" class="block px-4 py-2 text-aquarius-900 hover:bg-cyan-50">General</a>
                    </div>
                </div>
                <div class="relative group inline-block">
                    <button class="px-2 md:px-3 py-2 rounded-lg transition hover:bg-blue-100 hover:text-blue-700 focus:outline-none">Reportes ▾</button>
                    <div class="absolute left-0 mt-1 w-48 bg-white border border-aquarius-200 rounded shadow-lg opacity-0 group-hover:opacity-100 group-hover:pointer-events-auto pointer-events-none z-20">
                        <a href="{{ route('reportes.index') }}" class="block px-4 py-2 text-aquarius-900 hover:bg-cyan-50">Reportes</a>
                        <a href="{{ route('reportes.anual') }}" class="block px-4 py-2 text-aquarius-900 hover:bg-blue-50">Reporte Anual</a>
                    </div>
                </div>
                <div class="relative group inline-block">
                    <button class="px-2 md:px-3 py-2 rounded-lg transition hover:bg-yellow-100 hover:text-yellow-700 focus:outline-none">Tarifas ▾</button>
                    <div class="absolute left-0 mt-1 w-44 bg-white border border-aquarius-200 rounded shadow-lg opacity-0 group-hover:opacity-100 group-hover:pointer-events-auto pointer-events-none z-20">
                        <a href="{{ route('tarifas.index') }}" class="block px-4 py-2 text-aquarius-900 hover:bg-yellow-50">Ver Tarifas</a>
                        <a href="{{ route('tarifas.create') }}" class="block px-4 py-2 text-aquarius-900 hover:bg-yellow-50">Agregar Tarifa</a>
                    </div>
                </div>
                <a href="{{ route('lecturas.movil') }}" class="px-2 md:px-3 py-2 rounded-lg transition hover:bg-blue-200 hover:text-blue-900">Lectura móvil</a>
            </div>
        </div>
    </nav>
    <main class="flex-1 flex justify-center items-start py-10 px-2 bg-transparent">
        <div class="w-full max-w-5xl glass p-8 shadow-xl border border-aquarius-100">
            @yield('content')
        </div>
    </main>
    <footer class="text-center text-xs text-aquarius-700 py-6 font-body tracking-wide">
        &copy; {{ date('Y') }} Acuarius. Todos los derechos reservados.
    </footer>
</body>
</html>