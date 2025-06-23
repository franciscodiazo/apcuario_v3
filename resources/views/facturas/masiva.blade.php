@extends('layouts.app')
@section('content')
<div class="w-full">
    @if(empty($soloFactura))
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
        <div>
            <label class="block text-xs font-bold text-aquarius-700 mb-1">Buscar</label>
            <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Matrícula, nombre o apellido" class="w-56 rounded border-aquarius-200 focus:ring-coral-400" />
        </div>
        <input type="hidden" name="pagina" value="{{ $pagina ?? 1 }}">
        <button class="px-6 py-2 rounded-lg bg-aquarius-700 text-white font-semibold shadow hover:bg-coral-600 transition">Filtrar</button>
        <button type="button" onclick="window.print()" class="px-6 py-2 rounded-lg bg-coral-500 text-white font-semibold shadow hover:bg-coral-700 transition ml-auto">Imprimir página</button>
    </form>
    <div class="mb-4 flex justify-between items-center no-print">
        <div class="text-xs text-aquarius-700">Mostrando página {{ $pagina }} de {{ $totalPaginas }}</div>
        <div class="flex gap-2">
            @for($i=1; $i<=$totalPaginas; $i++)
                <a href="?anio={{ $anio }}&ciclo={{ $ciclo }}&pagina={{ $i }}@if(request('buscar'))&buscar={{ urlencode(request('buscar')) }}@endif" class="px-3 py-1 rounded {{ $i==$pagina ? 'bg-coral-500 text-white' : 'bg-aquarius-100 text-aquarius-700' }} font-bold">{{ $i }}</a>
            @endfor
        </div>
    </div>
    <div class="mb-6 flex justify-end gap-4 no-print">
        <button onclick="exportarPDFPorBloques()" class="px-6 py-2 rounded-lg bg-green-600 text-white font-semibold shadow hover:bg-green-800 transition">Exportar a PDF (5 facturas)</button>
    </div>
    @else
    <div class="mb-6 flex justify-end gap-4 no-print">
        <a href="{{ route('cliente.factura.pdf', $lecturas[0]->id) }}" class="px-6 py-2 rounded-lg bg-green-600 text-white font-semibold shadow hover:bg-green-800 transition">Descargar</a>
    </div>
    <style>
    body, html {
        background: #f8fafc !important;
        min-width: 1024px;
        min-height: 768px;
    }
    .factura-servicio {
        margin: 2rem auto !important;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
        border-radius: 1.5rem;
        border: 1px solid #e0e7ef;
    }
    </style>
    @endif
    <!-- Modal de impresión individual -->
    <div id="modal-preview" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
        <div class="bg-white rounded-lg shadow-lg w-[21cm] h-[28cm] flex flex-col relative border-4 border-coral-500">
            <button onclick="closePreview()" class="absolute top-2 right-2 bg-coral-500 text-white rounded-full w-8 h-8 flex items-center justify-center">&times;</button>
            <div class="text-lg font-bold text-coral-700 text-center mt-2 mb-2">Impresión individual de factura</div>
            <div id="modal-factura-content" class="overflow-auto p-2" style="width:19.59cm; height:25.5cm; margin:auto; box-sizing:border-box; background:#fff;">
                <!-- Aquí se inyectan las facturas -->
            </div>
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
            $creditoPendiente = $lectura->usuario ? $lectura->usuario->creditos()->where('saldo', '>', 0)->sum('saldo') : 0;
            $creditos = $creditoPendiente;
            $totalPagar = max(0, ($costo + 5000) - $creditos);
            $totalPendientes = isset($pendientes) ? $pendientes->sum(function($p) use ($preciosPorAnio) {
                $basePend = $preciosPorAnio[$p->anio]->costo_base ?? 22000;
                return $basePend + 5000;
            }) : 0;
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
            <div class="bg-cyan-50 border border-cyan-200 rounded p-2 mb-2 flex flex-col gap-1">
                <div class="flex justify-between items-center">
                    <div>
                        <div class="text-xs text-aquarius-700">Valor factura actual</div>
                        <div class="text-lg font-bold text-coral-700">
                            @php
                                $adicionales = max(0, ($lectura->consumo_m3 ?? 0) - ($precios->costo_base ? 50 : 50));
                                $valor_factura = ($precios->costo_base ?? 0) + ($adicionales * ($precios->costo_adicional ?? 0));
                            @endphp
                            ${{ number_format($valor_factura, 0) }}
                        </div>
                    </div>
                    <div>
                        <div class="text-xs text-aquarius-700">Saldo pendiente</div>
                        <div class="font-bold text-green-600">${{ number_format($creditoPendiente, 0) }}</div>
                    </div>
                </div>
                <div class="flex justify-between items-center mt-1">
                    <div class="text-xs text-aquarius-700 font-bold">Total a pagar</div>
                    <div class="text-2xl font-bold text-coral-700">${{ number_format($valor_factura, 0) }}</div>
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
                        <tr>
                            <td class="p-1">Consumo básico</td>
                            <td class="p-1">Hasta 50 m³</td>
                            <td class="p-1 text-right">${{ number_format($precios->costo_base ?? 0, 0) }}</td>
                        </tr>
                        <tr>
                            <td class="p-1">Consumo adicional</td>
                            <td class="p-1">
                                @php
                                    $adicionales = max(0, ($lectura->consumo_m3 ?? 0) - ($precios->limite_base ?? 50));
                                @endphp
                                {{ $adicionales }} m³ x ${{ number_format($precios->costo_adicional ?? 0, 0) }}
                            </td>
                            <td class="p-1 text-right">
                                ${{ number_format($adicionales * ($precios->costo_adicional ?? 0), 0) }}
                            </td>
                        </tr>
                        <tr class="bg-cyan-100 font-bold">
                            <td class="p-1">Total a pagar</td>
                            <td class="p-1"></td>
                            <td class="p-1 text-right">
                                @php
                                    $valor_factura = ($precios->costo_base ?? 0) + ($adicionales * ($precios->costo_adicional ?? 0));
                                    $total_pagar = max(0, $valor_factura - $creditoPendiente);
                                @endphp
                                ${{ number_format($total_pagar, 0) }}
                            </td>
                        </tr>
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
                    <div class="text-xs">Valor: <span class="font-bold text-coral-700">${{ number_format($valor_factura, 0) }}</span></div>
                    <div class="text-xs">Fecha límite: <span class="font-bold text-red-600">{{ now()->addDays(15)->format('Y-m-d') }}</span></div>
                    <div class="w-16 h-8 bg-gray-200 flex items-center justify-center rounded">QR</div>
                </div>
            </div>
        </div>
        {{-- Botón de imprimir individual eliminado de la vista masiva --}}
        @endforeach
    </div>
</div>
<style>
@media print {
    @page { size: Letter; margin: 0.3cm 1.0cm 1.0cm 1.0cm; }
    body { background: #fff !important; }
    .no-print, nav, footer, form, .print\:hidden { display: none !important; }
    .factura-servicio {
        page-break-after: always !important;
        break-after: page !important;
        width: 21.59cm !important;
        height: 27.94cm !important;
        margin: 0 !important;
        padding: 0.3cm 1cm 1cm 1cm !important;
        box-sizing: border-box !important;
        background: #fff !important;
        border: none !important;
        box-shadow: none !important;
    }
    .print\:bg-white { background: #fff !important; }
    .print\:p-0 { padding: 0 !important; }
    .print\:break-after-page { page-break-after: always !important; }
    .print\:shadow-none { box-shadow: none !important; }
    .print\:border { border: none !important; }
    .print\:rounded-none { border-radius: 0 !important; }
    .print\:p-4 { padding: 1rem !important; }
    #facturas-area { display: block !important; }
    /* Refuerzo para barras y logo en impresión */
    .factura-servicio svg { display: inline !important; }
    .factura-servicio .w-6 { border: none !important; }
    /* Mantener colores en impresión */
    .factura-servicio, .factura-servicio * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        color-adjust: exact !important;
    }
}
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
function vistaPreviaBloque() {
    const facturas = Array.from(document.querySelectorAll('.factura-servicio'));
    const modal = document.getElementById('modal-preview');
    const content = document.getElementById('modal-factura-content');
    content.innerHTML = '';
    for (let i = 0; i < 5 && i < facturas.length; i++) {
        const clone = facturas[i].cloneNode(true);
        clone.style.margin = '0 auto 2cm auto';
        clone.style.width = '21.59cm';
        clone.style.height = '27.94cm';
        clone.style.padding = '1.5cm';
        clone.style.background = '#fff';
        clone.style.boxSizing = 'border-box';
        clone.style.pageBreakAfter = 'always';
        content.appendChild(clone);
    }
    modal.classList.remove('hidden');
}
function openPreview(btn) {
    // Botón individual: ahora permite imprimir individualmente
    const factura = btn.closest('.factura-servicio');
    const modal = document.getElementById('modal-preview');
    const content = document.getElementById('modal-factura-content');
    content.innerHTML = '';
    const clone = factura.cloneNode(true);
    clone.style.margin = '0 auto';
    clone.style.width = '21.59cm';
    clone.style.height = '27.94cm';
    clone.style.padding = '1.5cm';
    clone.style.background = '#fff';
    clone.style.boxSizing = 'border-box';
    content.appendChild(clone);
    // Agregar botón de exportar PDF individual si no existe
    let btnExport = document.getElementById('btn-exportar-individual');
    if (!btnExport) {
        btnExport = document.createElement('button');
        btnExport.id = 'btn-exportar-individual';
        btnExport.className = 'mt-2 mx-auto px-6 py-2 rounded-lg bg-green-600 text-white font-semibold shadow hover:bg-green-800 transition';
        btnExport.innerText = 'Exportar esta factura a PDF';
        btnExport.onclick = exportarPDFIndividual;
        content.parentNode.appendChild(btnExport);
    } else {
        btnExport.style.display = 'block';
    }
    modal.classList.remove('hidden');
}
function exportarPDFIndividual() {
    const content = document.getElementById('modal-factura-content');
    if (!content) return;
    const factura = content.firstElementChild;
    if (!factura) return;
    // Clonar y preparar para PDF
    const contenedor = document.createElement('div');
    const page = document.createElement('div');
    page.style.width = '21.59cm';
    page.style.height = '27.94cm';
    page.style.background = '#fff';
    page.style.boxSizing = 'border-box';
    page.style.overflow = 'hidden';
    page.style.pageBreakAfter = 'always';
    const clone = factura.cloneNode(true);
    clone.style.margin = '0';
    clone.style.width = '21.59cm';
    clone.style.height = '27.94cm';
    clone.style.padding = '0';
    clone.style.background = '#fff';
    clone.style.boxSizing = 'border-box';
    clone.style.pageBreakAfter = 'always';
    page.appendChild(clone);
    contenedor.appendChild(page);
    const opt = {
        margin:       0,
        filename:     `factura_individual.pdf`,
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2, useCORS: true },
        jsPDF:        { unit: 'cm', format: 'letter', orientation: 'portrait' },
        pagebreak:    { mode: ['css', 'legacy'] }
    };
    html2pdf().set(opt).from(contenedor).save();
}
function closePreview() {
    document.getElementById('modal-preview').classList.add('hidden');
    // Ocultar botón de exportar individual si existe
    let btnExport = document.getElementById('btn-exportar-individual');
    if (btnExport) btnExport.style.display = 'none';
}
function printPreviewFactura() {
    const printContents = document.getElementById('modal-factura-content').innerHTML;
    const originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
    window.location.reload();
}
function imprimirIndividualDesdeListado() {
    // Toma la primera factura visible y la muestra en el modal de impresión individual
    const factura = document.querySelector('.factura-servicio');
    if (!factura) return;
    const modal = document.getElementById('modal-preview');
    const content = document.getElementById('modal-factura-content');
    content.innerHTML = '';
    const clone = factura.cloneNode(true);
    clone.style.margin = '0 auto';
    clone.style.width = '21.59cm';
    clone.style.height = '27cm';
    clone.style.padding = '1.2cm 1cm 1cm 1cm';
    clone.style.background = '#fff';
    clone.style.boxSizing = 'border-box';
    content.appendChild(clone);
    modal.classList.remove('hidden');
}
function exportarPDFPorBloques() {
    const facturas = Array.from(document.querySelectorAll('.factura-servicio'));
    if (facturas.length === 0) return;
    let bloque = 0;
    for (let i = 0; i < facturas.length; i += 5) {
        const contenedor = document.createElement('div');
        contenedor.style.background = '#fff';
        contenedor.style.padding = '0';
        contenedor.style.margin = '0';
        contenedor.style.width = '21.59cm';
        contenedor.style.minHeight = '27cm';
        contenedor.style.boxSizing = 'border-box';
        // Cada factura en su propia página
        for (let j = i; j < i + 5 && j < facturas.length; j++) {
            const page = document.createElement('div');
            page.style.width = '21.59cm';
            page.style.height = '27cm';
            page.style.background = '#fff';
            page.style.boxSizing = 'border-box';
            page.style.overflow = 'hidden';
            page.style.pageBreakAfter = 'always';
            // Margen de 0.3cm arriba, 1cm laterales y 1cm abajo
            page.style.padding = '0.3cm 1cm 1cm 1cm';
            const clone = facturas[j].cloneNode(true);
            clone.style.margin = '0';
            clone.style.width = '19.59cm';
            clone.style.height = '25.7cm';
            clone.style.padding = '0';
            clone.style.background = '#fff';
            clone.style.boxSizing = 'border-box';
            clone.style.pageBreakAfter = 'always';
            page.appendChild(clone);
            contenedor.appendChild(page);
        }
        const opt = {
            margin:       0, // El margen real lo da el padding de page
            filename:     `facturas_bloque_${++bloque}.pdf`,
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2, useCORS: true },
            jsPDF:        { unit: 'cm', format: 'letter', orientation: 'portrait' },
            pagebreak:    { mode: ['css', 'legacy'] }
        };
        html2pdf().set(opt).from(contenedor).save();
    }
}
</script>
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
