<?php
namespace App\Http\Controllers;

use App\Models\Lectura;
use App\Models\Usuario;
use Illuminate\Http\Request;

class LecturaController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Lectura::with(['usuario', 'medidor']);

        // Búsqueda
        if ($request->filled('buscar')) {
            $buscar = $request->input('buscar');
            $query->where(function($q) use ($buscar) {
                $q->where('matricula', 'like', "%$buscar%")
                  ->orWhereHas('usuario', function($qu) use ($buscar) {
                      $qu->where('nombres', 'like', "%$buscar%")
                         ->orWhere('apellidos', 'like', "%$buscar%")
                         ->orWhere('matricula', 'like', "%$buscar%")
                         ->orWhere('documento', 'like', "%$buscar%") ;
                  });
            });
        }

        // Ordenamiento
        $orden = $request->input('orden', 'anio');
        $direccion = $request->input('direccion', 'desc');
        $ordenesPermitidos = ['anio', 'ciclo', 'matricula'];
        if (!in_array($orden, $ordenesPermitidos)) $orden = 'anio';
        if (!in_array($direccion, ['asc', 'desc'])) $direccion = 'desc';
        $query->orderBy($orden, $direccion);
        if ($orden !== 'anio') {
            $query->orderBy('anio', 'desc');
        }

        $lecturas = $query->paginate(10)->appends($request->all());

        return view('lecturas.index', compact('lecturas', 'orden', 'direccion'));
    }

    public function create()
    {
        $usuarios = Usuario::all();
        return view('lecturas.create', compact('usuarios'));
    }

    // Endpoint para AJAX: obtener la última lectura de una matrícula
public function ultimaLectura(Request $request)
{
    $lectura = \App\Models\Lectura::where('matricula', $request->matricula)
        ->orderByDesc('anio')
        ->orderByDesc('ciclo')
        ->first();

    return response()->json([
        'anio' => $lectura ? $lectura->anio : null,
        'ciclo' => $lectura ? $lectura->ciclo : null,
        'lectura_actual' => $lectura ? $lectura->lectura_actual : 0,
        'fecha' => $lectura ? $lectura->fecha : null,
    ]);
}

    public function store(Request $request)
    {
        $request->validate([
            'matricula' => 'required|exists:usuarios,matricula',
            'anio' => 'required|integer',
            'ciclo' => 'required|integer|min:1|max:6',
            'fecha' => 'required|date',
            'lectura_actual' => 'required|integer|min:0',
        ]);

        // Buscar la última lectura anterior
        $lecturaAnterior = \App\Models\Lectura::where('matricula', $request->matricula)
            ->orderByDesc('anio')
            ->orderByDesc('ciclo')
            ->first();

        $lectura_anterior = $lecturaAnterior ? $lecturaAnterior->lectura_actual : 0;

        // Calcular el siguiente ciclo y año
        $anio = $lecturaAnterior ? $lecturaAnterior->anio : $request->anio;
        $ciclo = $lecturaAnterior ? $lecturaAnterior->ciclo : 0;
        if ($ciclo < 6) {
            $ciclo = $ciclo + 1;
        } else {
            $ciclo = 1;
            $anio = $anio + 1;
        }

        $consumo = $request->lectura_actual - $lectura_anterior;

        \App\Models\Lectura::create([
            'matricula' => $request->matricula,
            'numero_serie' => $request->numero_serie,
            'anio' => $anio,
            'ciclo' => $ciclo,
            'fecha' => $request->fecha,
            'lectura_actual' => $request->lectura_actual,
            'lectura_anterior' => $lectura_anterior,
            'consumo_m3' => $consumo,
        ]);

        return redirect()->route('lecturas.index')->with('success', 'Lectura registrada correctamente.');
    }
    
    public function show($id)
    {
       $lectura = Lectura::findOrFail($id);   
        return view('lecturas.show', compact('lectura'));
    
    }
    public function movil(Request $request)
    {
        $usuario = null;
        $ultimaLectura = null;
        if ($request->filled('matricula')) {
            $usuario = \App\Models\Usuario::where('matricula', $request->matricula)
                ->orWhere('nombres', 'like', '%'.$request->matricula.'%')
                ->orWhere('apellidos', 'like', '%'.$request->matricula.'%')
                ->first();
            if ($usuario) {
                $ultimaLectura = \App\Models\Lectura::where('matricula', $usuario->matricula)
                    ->orderByDesc('anio')
                    ->orderByDesc('ciclo')
                    ->first();
            }
        }
        return view('lecturas.movil', compact('usuario', 'ultimaLectura'));
    }

    public function movilStore(Request $request)
    {
        $request->validate([
            'matricula' => 'required',
            'lectura_actual' => 'required|numeric|min:0',
            'fecha' => 'required|date',
            'anio' => 'required|integer',
            'ciclo' => 'required|integer|min:1|max:6',
            'lectura_anterior' => 'nullable|numeric|min:0',
        ]);
        $lectura_anterior = $request->lectura_anterior ?? 0;
        $consumo = $request->lectura_actual - $lectura_anterior;
        // Buscar tarifa del año correspondiente
        $tarifa = \App\Models\Tarifa::where('anio', $request->anio)->first();
        $valor_pagar = 0;
        if ($tarifa) {
            $valor_pagar = $tarifa->basico + ($consumo > 0 ? $consumo * $tarifa->adicional_m3 : 0);
        }
        \App\Models\Lectura::create([
            'matricula' => $request->matricula,
            'anio' => $request->anio,
            'ciclo' => $request->ciclo,
            'fecha' => $request->fecha,
            'lectura_actual' => $request->lectura_actual,
            'lectura_anterior' => $lectura_anterior,
            'consumo_m3' => $consumo,
            // Si quieres guardar el valor a pagar, agrega el campo en la migración y modelo
            // 'valor_pagar' => $valor_pagar,
        ]);
        return redirect()->route('lecturas.movil')->with('success', 'Lectura registrada correctamente. Valor a pagar: $'.number_format($valor_pagar,0,',','.'));
    }
}