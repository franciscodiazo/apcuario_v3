@extends('layouts.app')
@section('content')
@php
    $adicionales = max(0, ($lectura->consumo_m3 ?? 0) - 50);
    $valor_factura = ($precios->basico ?? 0) + ($adicionales * ($precios->adicional_m3 ?? 0));
    $saldo = max(0, $lectura->usuario->creditos()->where('saldo', '>', 0)->sum('saldo') ?? 0);
@endphp
<div class="flex justify-center my-4 no-print">
    <button id="descargar-pdf" class="flex items-center gap-2 px-8 py-3 rounded-xl bg-green-700 text-white font-bold hover:bg-green-900 transition text-base focus:outline-none focus:ring-2 focus:ring-green-400">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
        Descargar PDF
    </button>
</div>
<div class="factura-carta mx-auto my-0 bg-white rounded-xl shadow-sm" id="factura-carta-pdf">
    {{-- ENCABEZADO --}}
    <div class="flex justify-between items-center border-b-4 border-blue-200 px-10 pt-8 pb-4 bg-blue-50 rounded-t-2xl print:rounded-none print:bg-white">
        <div class="flex items-center gap-6">
            <img src="/logo_epm_celsia.png" alt="Logo" class="h-20 w-auto drop-shadow-lg print:drop-shadow-none">
            <div>
                <div class="font-bold text-3xl text-blue-900 leading-tight tracking-wide">Acuarius S.A. E.S.P.</div>
                <div class="text-xs text-blue-700">NIT 900.000.000-1</div>
                <div class="text-xs text-blue-700">Cra 1 # 23-45, Acuaville, Colombia</div>
                <div class="text-xs text-blue-700">Tel: (604) 123 4567 | contacto@acuarius.com</div>
            </div>
        </div>
        <div class="text-right">
            <div class="text-xs text-blue-700 font-bold uppercase tracking-wider">Factura de Servicios Públicos</div>
            <div class="text-xs text-blue-700">Periodo: <span class="font-bold">{{ $lectura->anio ?? '----' }}/{{ $lectura->ciclo ?? '--' }}</span></div>
            <div class="text-xs text-blue-700">Fecha emisión: {{ now()->format('Y-m-d') }}</div>
            <div class="text-xs text-blue-700">Fecha límite pago: <span class="font-bold text-red-600">{{ now()->addDays(15)->format('Y-m-d') }}</span></div>
        </div>
    </div>
    {{-- DATOS DEL CLIENTE Y SERVICIO --}}
    <div class="flex flex-col md:flex-row gap-6 px-10 py-6">
        <div class="flex-1 bg-white rounded-xl shadow-sm p-5 flex flex-col gap-2 min-w-[260px]">
            <div class="text-xs text-blue-700 font-semibold mb-1">Datos del cliente</div>
            <div class="flex flex-col gap-1">
                <div><span class="text-xs text-blue-700">Cliente:</span> <span class="font-bold text-blue-900">{{ $lectura->usuario->nombres ?? '' }} {{ $lectura->usuario->apellidos ?? '' }}</span></div>
                <div><span class="text-xs text-blue-700">Dirección:</span> <span class="font-bold">{{ $lectura->usuario->direccion ?? '' }}</span></div>
                <div><span class="text-xs text-blue-700">Estrato:</span> <span class="font-bold">{{ $lectura->usuario->estrato ?? 'No aplica' }}</span></div>
                <div><span class="text-xs text-blue-700">N° Cuenta/Contrato:</span> <span class="font-bold">{{ $lectura->matricula ?? '' }}</span></div>
                <div><span class="text-xs text-blue-700">Saldo pendiente:</span> <span class="font-bold text-coral-700">${{ number_format($saldo, 0) }}</span></div>
            </div>
        </div>
        <div class="flex-1 bg-blue-50 rounded-xl shadow-sm p-5 flex flex-col gap-2 min-w-[260px]">
            <div class="text-xs text-blue-700 font-semibold mb-1">Datos del servicio</div>
            <div class="flex flex-col gap-1">
                <div><span class="text-xs text-blue-700">CC/NIT:</span> <span class="font-bold">{{ $lectura->usuario->documento ?? '---' }}</span></div>
                <div><span class="text-xs text-blue-700">Estado de cuenta:</span> <span class="font-bold">{{ $lectura->pagado ? 'Pagado' : 'Pendiente' }}</span></div>
                <div><span class="text-xs text-blue-700">Consumo (m³):</span> <span class="font-bold text-blue-900">{{ $lectura->consumo_m3 ?? 0 }} m³</span></div>
                <div><span class="text-xs text-blue-700">Lectura anterior:</span> <span class="font-bold">{{ $lectura->lectura_anterior ?? '-' }}</span></div>
                <div><span class="text-xs text-blue-700">Lectura actual:</span> <span class="font-bold">{{ $lectura->lectura_actual ?? '-' }}</span></div>
                <div class="flex items-center gap-2 mt-2">
                    <span class="text-xs text-blue-700">Código QR:</span>
                    <div class="w-12 h-12 bg-gray-200 flex items-center justify-center rounded shadow-inner">
                        <span class="text-xs text-blue-400">QR</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- DETALLE DE COBRO Y GRÁFICO --}}
    <div class="flex flex-col md:flex-row gap-6 px-10 py-6 items-start">
        <div class="flex-1">
            <div class="text-xs font-bold text-blue-700 mb-2">Detalle de cobro</div>
            <table class="w-full text-xs rounded-xl mb-2 shadow-sm bg-white">
                <thead class="bg-blue-50">
                    <tr>
                        <th class="p-2">Concepto</th>
                        <th class="p-2">Detalle</th>
                        <th class="p-2">Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="p-2">Consumo básico</td>
                        <td class="p-2">Hasta 50 m³</td>
                        <td class="p-2 text-right">${{ number_format($precios->basico ?? 0, 0) }}</td>
                    </tr>
                    <tr>
                        <td class="p-2">Consumo adicional</td>
                        <td class="p-2">{{ $adicionales }} m³ x ${{ number_format($precios->adicional_m3 ?? 0, 0) }}</td>
                        <td class="p-2 text-right">${{ number_format($adicionales * ($precios->adicional_m3 ?? 0), 0) }}</td>
                    </tr>
                    <tr class="bg-blue-50 font-bold">
                        <td class="p-2">Total a pagar</td>
                        <td class="p-2"></td>
                        <td class="p-2 text-right">${{ number_format($valor_factura, 0) }}</td>
                    </tr>
                </tbody>
            </table>
            <div class="text-xs text-blue-700 mt-2">
                <span class="font-bold">Consumo total:</span> {{ $lectura->consumo_m3 ?? 0 }} m³. <span class="font-bold">Básico:</span> 50 m³. <span class="font-bold">Adicional:</span> {{ $adicionales }} m³ x ${{ number_format($precios->adicional_m3 ?? 0, 0) }}.
            </div>
        </div>
        <div class="w-full md:w-56 flex-shrink-0 bg-white rounded-xl p-3 shadow-sm mt-6 md:mt-0">
            <div class="text-xs font-bold text-blue-700 mb-2">Histórico de Consumo (últimos 3 ciclos)</div>
            <div class="w-full flex items-end gap-2" style="height: 90px; min-height: 90px; max-height: 90px;">
                @php
                    $maxConsumo = $ultimas->max('consumo_m3') > 0 ? $ultimas->max('consumo_m3') : 1;
                @endphp
                @foreach($ultimas as $u)
                    @php
                        $barHeight = max(12, round(($u->consumo_m3 / $maxConsumo) * 70));
                        $barHeight = min($barHeight, 70); // nunca más de 70px
                        $barColor = '#a5b4fc';
                    @endphp
                    <div class="flex-1 flex flex-col items-center justify-end" style="height: 100%;">
                        <div class="w-6 rounded-t-lg" style="height:{{ $barHeight }}px; max-height:70px; background:{{ $barColor }};"></div>
                        <div class="text-[10px] text-blue-900 mt-1 font-semibold">{{ $u->anio }}/{{ $u->ciclo }}</div>
                        <div class="text-[10px] text-blue-900 font-bold">{{ $u->consumo_m3 }} m³</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    {{-- RESUMEN DE COBRO --}}
    <div class="bg-blue-50 px-10 py-4 flex flex-wrap justify-between items-center gap-8 rounded-b-xl">
        <div>
            <div class="text-xs text-blue-700">Valor factura actual</div>
            <div class="text-xl font-bold text-coral-700">${{ number_format($valor_factura, 0) }}</div>
        </div>
        <div>
            <div class="text-xs text-blue-700">Saldo pendiente</div>
            <div class="font-bold text-coral-700 text-base">${{ number_format($saldo, 0) }}</div>
        </div>
        <div>
            <div class="text-xs text-blue-700 font-bold">Total a pagar</div>
            <div class="text-2xl font-bold text-green-700">${{ number_format($valor_factura, 0) }}</div>
        </div>
    </div>
    {{-- INFORMACIÓN LEGAL Y DESPRENDIBLE --}}
    <div class="px-10 pb-4">
        <div class="mt-2 text-[11px] text-gray-700 bg-blue-50 rounded p-2">
            <div><span class="font-bold">Nota:</span> El no pago oportuno de esta factura puede generar suspensión del servicio y cobro de intereses de mora. Consulte sus derechos y deberes en www.acuarius.com/legal.</div>
            <div><span class="font-bold">Subsidios y contribuciones:</span> Según Ley 142/94, los estratos 1, 2 y 3 pueden recibir subsidios, y los estratos 5 y 6 pagan contribuciones.</div>
        </div>
    </div>
    {{-- DESPRENDIBLE DE PAGO --}}
    <div class="px-10 pb-8">
        <div class="mt-8 border-t border-dashed border-gray-400 pt-3 flex flex-col gap-2">
            <div class="flex justify-between items-center">
                <div class="text-xs font-bold">Desprendible para pago en bancos o corresponsales <span class="ml-2 text-gray-400">&#9986;</span></div>
                <div class="text-xs">Factura N°: {{ $lectura->id }}</div>
            </div>
            <div class="flex justify-between items-center mt-1">
                <div class="text-xs">Cliente: <span class="font-bold">{{ $lectura->usuario->nombres ?? '' }} {{ $lectura->usuario->apellidos ?? '' }}</span></div>
                <div class="text-xs">Valor: <span class="font-bold text-coral-700">${{ number_format($valor_factura, 0) }}</span></div>
                <div class="text-xs">Fecha límite: <span class="font-bold text-red-600">{{ now()->addDays(15)->format('Y-m-d') }}</span></div>
                <div class="w-16 h-8 bg-gray-200 flex items-center justify-center rounded">
                    <span class="text-xs text-blue-400">QR</span>
                </div>
            </div>
        </div>
    </div>
    {{-- DESPRENDIBLE DE PAGO INFERIOR --}}
    <div class="mt-8 pt-3 border-t border-dashed border-gray-400 flex flex-col gap-2" style="position: absolute; left: 0; right: 0; bottom: 0; width: 100%; background: #fff; padding: 0.7cm 0.7cm 0.7cm 0.7cm;">
        <div class="flex items-center gap-2 mb-1">
            <span class="text-gray-400 text-lg">&#9986;</span>
            <span class="text-xs text-gray-500">Desprendible para pago en bancos o corresponsales</span>
        </div>
        <div class="flex flex-wrap justify-between items-center text-xs font-medium gap-2">
            <div>Factura N°: <span class="font-bold">{{ $lectura->id }}</span></div>
            <div>Cliente: <span class="font-bold">{{ $lectura->usuario->nombres ?? '' }} {{ $lectura->usuario->apellidos ?? '' }}</span></div>
            <div>CC/NIT: <span class="font-bold">{{ $lectura->usuario->documento ?? '---' }}</span></div>
            <div>Dirección: <span class="font-bold">{{ $lectura->usuario->direccion ?? '' }}</span></div>
            <div>Estrato: <span class="font-bold">{{ $lectura->usuario->estrato ?? 'No aplica' }}</span></div>
            <div>N° Cuenta/Contrato: <span class="font-bold">{{ $lectura->matricula ?? '' }}</span></div>
            <div>Consumo: <span class="font-bold">{{ $lectura->consumo_m3 ?? 0 }} m³</span></div>
            <div>Lectura anterior: <span class="font-bold">{{ $lectura->lectura_anterior ?? '-' }}</span></div>
            <div>Lectura actual: <span class="font-bold">{{ $lectura->lectura_actual ?? '-' }}</span></div>
            <div>Valor: <span class="font-bold text-coral-700">${{ number_format($valor_factura, 0) }}</span></div>
            <div>Fecha límite: <span class="font-bold text-red-600">{{ now()->addDays(15)->format('Y-m-d') }}</span></div>
            <div class="w-12 h-12 bg-gray-200 flex items-center justify-center rounded ml-2">
                <span class="text-xs text-blue-400">QR</span>
            </div>
        </div>
    </div>
</div>
<style>
    html, body {
        background: #eaf1fa;
        min-height: 100vh;
        margin: 0;
        padding: 0;
    }
    .factura-carta {
        width: 21.59cm;
        min-width: 21.59cm;
        max-width: 21.59cm;
        height: 27.94cm;
        min-height: 27.94cm;
        max-height: 27.94cm;
        box-sizing: border-box;
        padding: 0.7cm 0.7cm 0.7cm 0.7cm;
        background: #fff;
        border-radius: 0.75rem;
        box-shadow: 0 2px 8px 0 rgba(0,0,0,0.07);
        overflow: hidden;
        position: relative;
        margin: 0 auto;
    }
    @media print {
        @page { size: Letter; margin: 0; }
        html, body {
            width: 100vw !important;
            height: 100vh !important;
            margin: 0 !important;
            padding: 0 !important;
            background: #fff !important;
            overflow: hidden !important;
        }
        .factura-carta {
            width: 100vw !important;
            height: 100vh !important;
            min-width: 0 !important;
            min-height: 0 !important;
            max-width: none !important;
            max-height: none !important;
            margin: 0 !important;
            padding: 0.7cm !important;
            box-shadow: none !important;
            border-radius: 0.75rem !important;
            background: #fff !important;
            page-break-after: avoid !important;
            page-break-before: avoid !important;
            page-break-inside: avoid !important;
        }
        /* SOLO FONDOS SÓLIDOS EN IMPRESIÓN */
        .bg-blue-50, .bg-gradient-to-r, .bg-gradient-to-br { background-color: #eff6ff !important; background-image: none !important; }
        .bg-white { background-color: #fff !important; }
        .shadow, .shadow-sm, .drop-shadow-lg { box-shadow: none !important; filter: none !important; }
        .rounded-xl, .rounded-t-2xl, .rounded, .rounded-t-lg { border-radius: 0.75rem !important; }
    }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
    document.getElementById('descargar-pdf').addEventListener('click', function() {
        const element = document.getElementById('factura-carta-pdf');
        const opt = {
            margin:       [0, 0],
            filename:     'factura_{{ $lectura->id ?? 'descarga' }}.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2, useCORS: true, backgroundColor: null },
            jsPDF:        { unit: 'cm', format: 'letter', orientation: 'portrait' }
        };
        html2pdf().set(opt).from(element).save();
    });
</script>
@endsection
