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
      @media print {
        aside, nav, .glass, .bg-aquarius-800, .bg-white\/80, .shadow-lg, .shadow-sm, .border, .border-aquarius-100, .border-aquarius-700 {
          display: none !important;
        }
        main, .print-area {
          display: block !important;
          position: static !important;
          box-shadow: none !important;
          background: white !important;
          border: none !important;
        }
        body { background: white !important; }
      }
    </style>
</head>
<body class="min-h-screen flex flex-col font-body text-aquarius-900 bg-gradient-to-br from-aquarius-50 to-sand-50">
    {{-- Seguridad: Prevenir clickjacking --}} 
    <script>
      if (window.top !== window.self) { window.top.location = window.self.location; }
    </script>
    <div x-data="{ open: true }" class="flex min-h-screen">
        <!-- Sidebar -->
        <aside :class="open ? 'w-64' : 'w-16'" class="transition-all duration-300 bg-aquarius-800 text-white flex flex-col shadow-lg z-30">
            <div class="flex items-center justify-between px-4 py-4 border-b border-aquarius-700">
                <span class="font-display text-xl tracking-widest" x-show="open">Acuarius</span>
                <button @click="open = !open" class="focus:outline-none">
                    <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                    <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <div class="flex-1 flex flex-col gap-2 mt-4" x-show="open">
                @auth
                    @php $user = Auth::user(); @endphp
                    {{-- Seguridad: No mostrar datos sensibles --}}
                    <div class="px-4 py-2 text-xs bg-aquarius-900/80 rounded mb-2">{{ $user->name ? e($user->name) : (e($user->email) ?? 'Usuario') }}<br><span class="text-aquarius-200">({{ e($user->roles->pluck('name')->implode(', ')) }})</span></div>
                    @if(method_exists($user, 'hasRole') && ($user->hasRole('admin') || $user->hasRole('operador')))
                        <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded transition hover:bg-aquarius-600 flex items-center gap-2"><svg class='w-5 h-5' fill='none' stroke='currentColor' stroke-width='2' viewBox='0 0 24 24'><path d='M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6'/></svg>Dashboard</a>
                        <a href="{{ route('usuarios.index') }}" class="block px-4 py-2 rounded transition hover:bg-aquarius-600 flex items-center gap-2"><svg class='w-5 h-5' fill='none' stroke='currentColor' stroke-width='2' viewBox='0 0 24 24'><path d='M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z'/></svg>Usuarios</a>
                        <a href="{{ route('usuarios.listado') }}" class="block px-4 py-2 rounded transition hover:bg-cyan-700 flex items-center gap-2"><svg class='w-5 h-5' fill='none' stroke='currentColor' stroke-width='2' viewBox='0 0 24 24'><path d='M12 4v16m8-8H4'/></svg>Registrar Lecturas</a>
                        <a href="{{ route('lecturas.index') }}" class="block px-4 py-2 rounded transition hover:bg-blue-700 flex items-center gap-2"><svg class='w-5 h-5' fill='none' stroke='currentColor' stroke-width='2' viewBox='0 0 24 24'><path d='M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01'/></svg>Lecturas</a>
                        <a href="{{ route('facturas.masiva') }}" class="block px-4 py-2 rounded transition hover:bg-coral-700 flex items-center gap-2"><svg class='w-5 h-5' fill='none' stroke='currentColor' stroke-width='2' viewBox='0 0 24 24'><path d='M9 17v-2a2 2 0 012-2h2a2 2 0 012 2v2m-6 4h6a2 2 0 002-2V7a2 2 0 00-2-2h-2l-2-2h-2a2 2 0 00-2 2v14a2 2 0 002 2z'/></svg>Facturación</a>
                        <a href="{{ route('consumos.index') }}" class="block px-4 py-2 rounded transition hover:bg-green-700 flex items-center gap-2"><svg class='w-5 h-5' fill='none' stroke='currentColor' stroke-width='2' viewBox='0 0 24 24'><path d='M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zm0 0V4m0 8v8'/></svg>Cuotas/Pagos</a>
                        <a href="{{ route('creditos.index') }}" class="block px-4 py-2 rounded transition hover:bg-green-700 flex items-center gap-2"><svg class='w-5 h-5' fill='none' stroke='currentColor' stroke-width='2' viewBox='0 0 24 24'><path d='M17 9V7a5 5 0 00-10 0v2a2 2 0 00-2 2v5a2 2 0 002 2h10a2 2 0 002-2v-5a2 2 0 00-2-2z'/></svg>Créditos</a>
                        <a href="{{ route('creditos.general') }}" class="block px-4 py-2 rounded transition hover:bg-green-700 flex items-center gap-2"><svg class='w-5 h-5' fill='none' stroke='currentColor' stroke-width='2' viewBox='0 0 24 24'><path d='M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zm0 0V4m0 8v8'/></svg>Créditos General</a>
                        <a href="{{ route('reportes.index') }}" class="block px-4 py-2 rounded transition hover:bg-blue-700 flex items-center gap-2"><svg class='w-5 h-5' fill='none' stroke='currentColor' stroke-width='2' viewBox='0 0 24 24'><path d='M9 17v-2a2 2 0 012-2h2a2 2 0 012 2v2m-6 4h6a2 2 0 002-2V7a2 2 0 00-2-2h-2l-2-2h-2a2 2 0 00-2 2v14a2 2 0 002 2z'/></svg>Reportes</a>
                        <a href="{{ route('reportes.anual') }}" class="block px-4 py-2 rounded transition hover:bg-blue-700 flex items-center gap-2"><svg class='w-5 h-5' fill='none' stroke='currentColor' stroke-width='2' viewBox='0 0 24 24'><path d='M3 3h18v18H3V3z'/></svg>Reporte Anual</a>
                        <a href="{{ route('tarifas.index') }}" class="block px-4 py-2 rounded transition hover:bg-yellow-700 flex items-center gap-2"><svg class='w-5 h-5' fill='none' stroke='currentColor' stroke-width='2' viewBox='0 0 24 24'><path d='M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zm0 0V4m0 8v8'/></svg>Tarifas</a>
                        <a href="{{ route('tarifas.create') }}" class="block px-4 py-2 rounded transition hover:bg-yellow-700 flex items-center gap-2"><svg class='w-5 h-5' fill='none' stroke='currentColor' stroke-width='2' viewBox='0 0 24 24'><path d='M12 4v16m8-8H4'/></svg>Agregar Tarifa</a>
                        <a href="{{ route('lecturas.movil') }}" class="block px-4 py-2 rounded transition hover:bg-blue-800 flex items-center gap-2"><svg class='w-5 h-5' fill='none' stroke='currentColor' stroke-width='2' viewBox='0 0 24 24'><path d='M3 10h18M3 6h18M3 14h18M3 18h18'/></svg>Lectura móvil</a>
                    @endif
                    @if(method_exists($user, 'hasRole') && $user->hasRole('cliente'))
                        <a href="{{ route('cliente.panel') }}" class="block px-4 py-2 rounded transition hover:bg-aquarius-600 flex items-center gap-2"><svg class='w-5 h-5' fill='none' stroke='currentColor' stroke-width='2' viewBox='0 0 24 24'><path d='M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z'/></svg>Panel Cliente</a>
                        <a href="{{ route('cliente.factura.ver', ['lecturaId' => 1]) }}" class="block px-4 py-2 rounded transition hover:bg-coral-700 flex items-center gap-2"><svg class='w-5 h-5' fill='none' stroke='currentColor' stroke-width='2' viewBox='0 0 24 24'><path d='M9 17v-2a2 2 0 012-2h2a2 2 0 012 2v2m-6 4h6a2 2 0 002-2V7a2 2 0 00-2-2h-2l-2-2h-2a2 2 0 00-2 2v14a2 2 0 002 2z'/></svg>Mis Facturas</a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="block px-4 py-2 rounded transition hover:bg-blue-700 flex items-center gap-2">
                        <svg class='w-5 h-5' fill='none' stroke='currentColor' stroke-width='2' viewBox='0 0 24 24'><path d='M5 12h14M12 5l7 7-7 7'/></svg>
                        Iniciar sesión
                    </a>
                    <a href="{{ route('consulta.factura.form') }}" class="block px-4 py-2 rounded transition hover:bg-yellow-700 flex items-center gap-2">
                        <svg class='w-5 h-5' fill='none' stroke='currentColor' stroke-width='2' viewBox='0 0 24 24'><path d='M5 12h14M12 5l7 7-7 7'/></svg>
                        Consulta cliente
                    </a>
                @endauth
            </div>
        </aside>
        <!-- Main content -->
        <div class="flex-1 flex flex-col">
            <!-- Navbar superior -->
            <nav class="w-full flex items-center justify-end bg-white/80 px-6 py-3 shadow-sm border-b border-aquarius-100">
                @auth
                    @php $user = Auth::user(); @endphp
                    <span class="mr-4 font-bold text-aquarius-900">{{ $user->name ? e($user->name) : (e($user->email) ?? 'Usuario') }} ({{ e($user->roles->pluck('name')->implode(', ')) }})</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="px-3 py-1 rounded bg-red-600 text-white hover:bg-red-700 transition">Salir</button>
                    </form>
                @endauth
            </nav>
            <main class="flex-1 flex justify-center items-start py-10 px-2 bg-transparent">
                <div class="w-full max-w-5xl glass p-8 shadow-xl border border-aquarius-100">
                    {{-- Seguridad: Mensajes de error y éxito --}}
                    @if(session('success'))
                        <div class="mb-4 p-3 rounded bg-green-100 text-green-800 border border-green-200">{{ e(session('success')) }}</div>
                    @endif
                    @if(session('error'))
                        <div class="mb-4 p-3 rounded bg-red-100 text-red-800 border border-red-200">{{ e(session('error')) }}</div>
                    @endif
                    @yield('content')
                </div>
            </main>
            <footer class="text-center text-xs text-aquarius-700 py-6 font-body tracking-wide">
                &copy; {{ date('Y') }} Acuarius. Todos los derechos reservados.
            </footer>
        </div>
    </div>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>