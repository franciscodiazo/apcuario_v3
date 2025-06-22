@extends('layouts.app')

@section('content')
<div class="w-full">
    <h1 class="text-2xl font-display font-bold text-aquarius-800 mb-6 no-print">Facturación Masiva</h1>
    <form method="GET" class="mb-6 flex flex-wrap gap-4 items-end no-print">
        <div>
            <label class="block text-xs font-bold text-aquarius-700 mb-1">Año</label>
            <input type="number" name="anio" value="{{ $anio }}" class="w-24 rounded border-aquarius-200 text-center focus:ring-coral-400" min="2020" max="2100" />
        </div>
        <div>
            <label class="block text-xs font-bold text-aquarius-700 mb-1">Ciclo</label>
            <input type="number" name="ciclo" value="{{ $ciclo }}" class="w-24 rounded border-aquarius-200 text-center focus:ring-coral-400" min="1" max="6" />
        </div>
        <input type="hidden" name="pagina" value="{{ $pagina ?? 1 }}">
        <button class="px-6 py-2 rounded-lg bg-aquarius-700 text-white font-semibold shadow hover:bg-coral-600 transition">Filtrar</button>
        <button type="button" onclick="window.print()" class="px-6 py-2 rounded-lg bg-coral-500 text-white font-semibold shadow hover:bg-coral-700 transition ml-auto">Imprimir página</button>
    </form>
    <div class="mb-4 flex justify-between items-center no-print">
        <div class="text-xs text-aquarius-700">Mostrando página {{ $pagina }} de {{ $totalPaginas }}</div>
        <div class="flex gap-2">
            @for($i=1; $i<=$totalPaginas; $i++)
                <a href="?anio={{ $anio }}&ciclo={{ $ciclo }}&pagina={{ $i }}" class="px-3 py-1 rounded {{ $i==$pagina ? 'bg-coral-500 text-white' : 'bg-aquarius-100 text-aquarius-700' }} font-bold">{{ $i }}</a>
            @endfor
        </div>
    </div>
    <div class="print:bg-white print:p-0" id="facturas-area">
        @foreach($lecturas as $lectura)
        @php
            $precios = $preciosPorAnio[$lectura->anio] ?? null;
            $base = $precios->costo_base ?? 22000;
            $limite = $precios->limite_base ?? 50;
            $adicional = $precios->costo_adicional ?? 2500;
            $consumo = $lectura->consumo_m3;
            $costo = $consumo <= $limite ? $base : $base + ($consumo - $limite) * $adicional;
            $ultimas = $lectura->ultimasLecturas;
            $maxBar = 100;
            $maxConsumo = max(1, $ultimas->max('consumo_m3'));
            $escala = $maxConsumo > $maxBar ? $maxBar / $maxConsumo : 1;
        @endphp
        <div class="factura-servicio" style="width: 21.59cm; height: 27.94cm; margin: 0 auto 1.5cm auto; padding: 1.5cm; box-sizing: border-box; background: #fff; position: relative; page-break-after: always;">
            {{-- ENCABEZADO EMPRESA --}}
            <div class="flex justify-between items-center mb-2 border-b pb-2">
                <div class="flex items-center gap-2">
                    @php
                        if (function_exists('svgLogo')) {
                            echo '<span class="inline-block align-middle" style="vertical-align:middle;">' . svgLogo() . '</span>';
                        } else {
                            echo '<span class="inline-block align-middle font-bold text-coral-700 text-lg" style="vertical-align:middle;">AcuaPaltres</span>';
                        }
                    @endphp
                    <div>
                        <div class="font-bold text-lg text-aquarius-800 leading-tight">Acuarius S.A. E.S.P.</div>
                        <div class="text-xs text-aquarius-700">NIT 900.000.000-1</div>
                        <div class="text-xs text-aquarius-700">Cra 1 # 23-45, Acuaville, Colombia</div>
                        <div class="text-xs text-aquarius-700">Tel: (604) 123 4567 | contacto@acuarius.com</div>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-xs text-aquarius-700">Factura de Servicios Públicos Domiciliarios</div>
                    <div class="text-xs text-aquarius-700">Periodo: {{ $lectura->anio }}/{{ $lectura->ciclo }}</div>
                    <div class="text-xs text-aquarius-700">Fecha emisión: {{ now()->format('Y-m-d') }}</div>
                    <div class="text-xs text-aquarius-700">Fecha límite pago: <span class="font-bold text-red-600">{{ now()->addDays(15)->format('Y-m-d') }}</span></div>
                </div>
            </div>
            {{-- DATOS DEL CLIENTE --}}
            <div class="grid grid-cols-2 gap-4 mb-2 mt-2">
                <div>
                    <div class="text-xs text-aquarius-700">Cliente:</div>
                    <div class="font-bold">{{ $lectura->usuario ? $lectura->usuario->nombres . ' ' . $lectura->usuario->apellidos : '' }}</div>
                    <div class="text-xs text-aquarius-700">Dirección:</div>
                    <div class="font-bold">{{ $lectura->usuario->direccion ?? '' }}</div>
                    <div class="text-xs text-aquarius-700">Estrato:</div>
                    <div class="font-bold">{{ $lectura->usuario->estrato ?? 'No aplica' }}</div>
                    <div class="text-xs text-aquarius-700">N° Cuenta/Contrato:</div>
                    <div class="font-bold">{{ $lectura->matricula }}</div>
                </div>
                <div>
                    <div class="text-xs text-aquarius-700">CC/NIT:</div>
                    <div class="font-bold">{{ $lectura->usuario->documento ?? '---' }}</div>
                    <div class="text-xs text-aquarius-700">Estado de cuenta:</div>
                    <div class="font-bold">{{ $lectura->pagado ? 'Pagado' : 'Pendiente' }}</div>
                    <div class="text-xs text-aquarius-700">Código QR:</div>
                    <div class="w-16 h-16 bg-gray-200 flex items-center justify-center rounded">QR</div>
                </div>
            </div>
            {{-- RESUMEN DE COBRO --}}
            <div class="bg-cyan-50 border border-cyan-200 rounded p-2 mb-2 flex justify-between items-center">
                <div>
                    <div class="text-xs text-aquarius-700">Valor total a pagar</div>
                    <div class="text-2xl font-bold text-coral-700">${{ number_format($costo, 0) }}</div>
                </div>
                <div>
                    <div class="text-xs text-aquarius-700">Fecha límite de pago</div>
                    <div class="font-bold text-red-600">{{ now()->addDays(15)->format('Y-m-d') }}</div>
                </div>
            </div>
            {{-- DETALLE DE COBRO --}}
            <div class="mb-2">
                <div class="text-xs font-bold text-aquarius-700 mb-1">Detalle de cobro</div>
                <table class="w-full text-xs border border-blue-200 rounded mb-2">
                    <thead class="bg-blue-50">
                        <tr>
                            <th class="p-1 border-b border-blue-200">Concepto</th>
                            <th class="p-1 border-b border-blue-200">Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td class="p-1">Consumo agua</td><td class="p-1 text-right">${{ number_format($costo, 0) }}</td></tr>
                        <tr><td class="p-1">Cargo fijo</td><td class="p-1 text-right">${{ number_format(5000, 0) }}</td></tr>
                        <tr><td class="p-1">Subsidio/Contribución</td><td class="p-1 text-right">${{ number_format(0, 0) }}</td></tr>
                        <tr><td class="p-1">Otros cargos</td><td class="p-1 text-right">${{ number_format(0, 0) }}</td></tr>
                        <tr><td class="p-1">Saldo anterior</td><td class="p-1 text-right">${{ number_format(0, 0) }}</td></tr>
                        <tr><td class="p-1">Pagos recientes</td><td class="p-1 text-right">${{ number_format(0, 0) }}</td></tr>
                        <tr class="bg-cyan-100 font-bold"><td class="p-1">Total a pagar</td><td class="p-1 text-right">${{ number_format($costo+5000, 0) }}</td></tr>
                    </tbody>
                </table>
            </div>
            {{-- DETALLE DE CONSUMO Y GRÁFICO --}}
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <div class="text-xs text-aquarius-700">Lectura anterior:</div>
                    <div class="font-bold">{{ $lectura->lectura_anterior }}</div>
                </div>
                <div>
                    <div class="text-xs text-aquarius-700">Lectura actual:</div>
                    <div class="font-bold">{{ $lectura->lectura_actual }}</div>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <div class="text-xs text-aquarius-700">Consumo (m³):</div>
                    <div class="font-bold">{{ $lectura->consumo_m3 }}</div>
                </div>
                <div>
                    <div class="text-xs text-aquarius-700">Valor a pagar:</div>
                    <div class="font-bold text-coral-700 text-lg">${{ number_format($costo, 0) }}</div>
                </div>
            </div>
            <div class="mb-4">
                <div class="text-xs font-bold text-aquarius-700 mb-1">Histórico de Consumo (últimos 3 ciclos)</div>
                <div class="w-full h-24 flex items-end gap-2 bg-gradient-to-r from-cyan-100 via-blue-50 to-cyan-50 rounded p-2 border border-blue-200 print:bg-gradient-to-r print:from-cyan-100 print:via-blue-50 print:to-cyan-50 print:border-blue-400 print:rounded-none print:shadow-none" style="min-height: 100px;">
                    @foreach($ultimas as $u)
                        @php
                            // Escalado estricto: nunca más de 100px, mínimo visual 10px
                            $barHeight = max(10, min(100, round($u->consumo_m3 * $escala)));
                            // Color dinámico para barras (azul, verde, naranja)
                            $barColor = $u->consumo_m3 <= 15 ? '#00bcd4' : ($u->consumo_m3 <= 30 ? '#4ade80' : '#f59e42');
                        @endphp
                        <div class="flex-1 flex flex-col items-center">
                            <div class="w-6 print:border print:border-black" style="height:{{ $barHeight }}px; background:{{ $barColor }}; border-radius:4px 4px 0 0;"></div>
                            <div class="text-[10px] text-blue-900 mt-1 print:text-black font-semibold">{{ $u->anio }}/{{ $u->ciclo }}</div>
                            <div class="text-[10px] text-blue-900 font-bold print:text-black">{{ $u->consumo_m3 }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
            {{-- INFORMACIÓN LEGAL Y MENSAJES --}}
            <div class="mt-2 text-[11px] text-gray-700 bg-blue-50 rounded p-2 border border-blue-200">
                <div><span class="font-bold">Nota:</span> El no pago oportuno de esta factura puede generar suspensión del servicio y cobro de intereses de mora. Consulte sus derechos y deberes en www.acuarius.com/legal.</div>
                <div><span class="font-bold">Subsidios y contribuciones:</span> Según Ley 142/94, los estratos 1, 2 y 3 pueden recibir subsidios, y los estratos 5 y 6 pagan contribuciones.</div>
            </div>
            {{-- DESPRENDIBLE DE PAGO --}}
            <div class="mt-4 border-t pt-2">
                <div class="flex justify-between items-center">
                    <div class="text-xs font-bold">Desprendible para pago en bancos o corresponsales</div>
                    <div class="text-xs">Factura N°: {{ $lectura->id }}</div>
                </div>
                <div class="flex justify-between items-center mt-1">
                    <div class="text-xs">Cliente: <span class="font-bold">{{ $lectura->usuario ? $lectura->usuario->nombres . ' ' . $lectura->usuario->apellidos : '' }}</span></div>
                    <div class="text-xs">Valor: <span class="font-bold text-coral-700">${{ number_format($costo+5000, 0) }}</span></div>
                    <div class="text-xs">Fecha límite: <span class="font-bold text-red-600">{{ now()->addDays(15)->format('Y-m-d') }}</span></div>
                    <div class="w-16 h-8 bg-gray-200 flex items-center justify-center rounded">QR</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
<style>
@media print {
    @page { size: Letter; margin: 1.5cm; }
    body { background: #fff !important; }
    .no-print, nav, footer, form, .print\:hidden { display: none !important; }
    .factura-servicio { page-break-after: always !important; break-after: page !important; width: 21.59cm !important; height: 27.94cm !important; margin: 0 !important; padding: 1.5cm !important; box-sizing: border-box !important; }
    .print\:bg-white { background: #fff !important; }
    .print\:p-0 { padding: 0 !important; }
    .print\:break-after-page { page-break-after: always !important; }
    .print\:shadow-none { box-shadow: none !important; }
    .print\:border { border: 1px solid #000 !important; }
    .print\:rounded-none { border-radius: 0 !important; }
    .print\:p-4 { padding: 1rem !important; }
    #facturas-area { display: block !important; }
    /* Refuerzo para barras y logo en impresión */
    .factura-servicio svg { display: inline !important; }
    .factura-servicio .w-6 { border: 1px solid #000 !important; }
    /* Mantener colores en impresión */
    .factura-servicio, .factura-servicio * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        color-adjust: exact !important;
    }
}
</style>
@php
// Definir función svgLogo() SIEMPRE antes de cualquier uso
if (!function_exists('svgLogo')) {
    function svgLogo() {
        // Logo simple: gota de agua azul con borde blanco
        return '<svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg"><ellipse cx="20" cy="24" rx="10" ry="14" fill="#00bcd4" stroke="#fff" stroke-width="2"/><ellipse cx="20" cy="18" rx="4" ry="6" fill="#b2ebf2"/></svg>';
    }
}
@endphp
@endsection
