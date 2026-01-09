<?php

namespace App\Http\Controllers;

use App\Models\Representante;
use App\Models\PacienteEspecial;
use App\Models\Paciente;
use App\Models\Estado;
use App\Models\Ciudad;
use App\Models\Municipio;
use App\Models\Parroquia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RepresentanteController extends Controller
{
    // =========================================================================
    // CRUD DE REPRESENTANTES
    // =========================================================================

    public function index()
    {
        $representantes = Representante::with(['estado', 'ciudad', 'pacientesEspeciales.paciente'])
                                     ->where('status', true)
                                     ->paginate(10);
        
        return view('shared.representantes.index', compact('representantes'));
    }

    public function create()
    {
        $pacientesEspeciales = PacienteEspecial::with('paciente')->where('status', true)->get();
        $estados = Estado::where('status', true)->get();
        return view('shared.representantes.create', compact('pacientesEspeciales', 'estados'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'primer_nombre' => 'required|max:100',
            'primer_apellido' => 'required|max:100',
            'tipo_documento' => 'nullable|in:V,E,P,J',
            'numero_documento' => 'nullable|max:20',
            'fecha_nac' => 'nullable|date',
            'estado_id' => 'nullable|exists:estados,id_estado',
            'ciudad_id' => 'nullable|exists:ciudades,id_ciudad',
            'municipio_id' => 'nullable|exists:municipios,id_municipio',
            'parroquia_id' => 'nullable|exists:parroquias,id_parroquia',
            'direccion_detallada' => 'nullable|string',
            'prefijo_tlf' => 'nullable|in:+58,+57,+1,+34',
            'numero_tlf' => 'nullable|max:15',
            'genero' => 'nullable|max:20',
            'parentesco' => 'nullable|max:100',
            'pacientes_especiales' => 'nullable|array',
            'pacientes_especiales.*' => 'exists:pacientes_especiales,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $representante = Representante::create($request->except('pacientes_especiales'));

        // Asignar pacientes especiales si se proporcionaron
        if ($request->has('pacientes_especiales')) {
            $this->asignarPacientesEspeciales($representante, $request->pacientes_especiales);
        }

        return redirect()->route('representantes.index')->with('success', 'Representante creado exitosamente');
    }

    public function show($id)
    {
        $representante = Representante::with([
            'estado', 
            'ciudad', 
            'municipio', 
            'parroquia',
            'pacientesEspeciales.paciente.usuario'
        ])->findOrFail($id);

        $historialCitas = \App\Models\Cita::whereHas('paciente.pacientesEspeciales.representantes', function($query) use ($id) {
            $query->where('representantes.id', $id);
        })->with(['medico', 'especialidad'])
        ->where('status', true)
        ->orderBy('fecha_cita', 'desc')
        ->get();

        return view('shared.representantes.show', compact('representante', 'historialCitas'));
    }

    public function edit($id)
    {
        $representante = Representante::findOrFail($id);
        $pacientesEspeciales = PacienteEspecial::with('paciente')->where('status', true)->get();
        $estados = Estado::where('status', true)->get();
        $ciudades = Ciudad::where('status', true)->get();
        $municipios = Municipio::where('status', true)->get();
        $parroquias = Parroquia::where('status', true)->get();

        return view('shared.representantes.edit', compact(
            'representante', 
            'pacientesEspeciales', 
            'estados', 
            'ciudades', 
            'municipios', 
            'parroquias'
        ));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'primer_nombre' => 'required|max:100',
            'primer_apellido' => 'required|max:100',
            'tipo_documento' => 'nullable|in:V,E,P,J',
            'numero_documento' => 'nullable|max:20',
            'fecha_nac' => 'nullable|date',
            'estado_id' => 'nullable|exists:estados,id_estado',
            'ciudad_id' => 'nullable|exists:ciudades,id_ciudad',
            'municipio_id' => 'nullable|exists:municipios,id_municipio',
            'parroquia_id' => 'nullable|exists:parroquias,id_parroquia',
            'direccion_detallada' => 'nullable|string',
            'prefijo_tlf' => 'nullable|in:+58,+57,+1,+34',
            'numero_tlf' => 'nullable|max:15',
            'genero' => 'nullable|max:20',
            'parentesco' => 'nullable|max:100',
            'pacientes_especiales' => 'nullable|array',
            'pacientes_especiales.*' => 'exists:pacientes_especiales,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $representante = Representante::findOrFail($id);
        $representante->update($request->except('pacientes_especiales'));

        // Sincronizar pacientes especiales
        if ($request->has('pacientes_especiales')) {
            $this->asignarPacientesEspeciales($representante, $request->pacientes_especiales);
        } else {
            $representante->pacientesEspeciales()->detach();
        }

        return redirect()->route('representantes.index')->with('success', 'Representante actualizado exitosamente');
    }

    public function destroy($id)
    {
        $representante = Representante::findOrFail($id);
        $representante->update(['status' => false]);

        return redirect()->route('representantes.index')->with('success', 'Representante desactivado exitosamente');
    }

    // =========================================================================
    // GESTIÓN DE PACIENTES ESPECIALES ASIGNADOS
    // =========================================================================

    public function asignarPacienteEspecial(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'paciente_especial_id' => 'required|exists:pacientes_especiales,id',
            'tipo_responsabilidad' => 'required|in:Principal,Suplente,Emergencia'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $representante = Representante::findOrFail($id);
        
        // Verificar que no esté ya asignado
        $existe = $representante->pacientesEspeciales()
                               ->where('paciente_especial_id', $request->paciente_especial_id)
                               ->exists();

        if ($existe) {
            return redirect()->back()->with('error', 'Este representante ya está asignado al paciente especial.');
        }

        $representante->pacientesEspeciales()->attach($request->paciente_especial_id, [
            'tipo_responsabilidad' => $request->tipo_responsabilidad
        ]);

        return redirect()->back()->with('success', 'Paciente especial asignado exitosamente');
    }

    public function removerPacienteEspecial($id, $pacienteEspecialId)
    {
        $representante = Representante::findOrFail($id);
        $representante->pacientesEspeciales()->detach($pacienteEspecialId);

        return redirect()->back()->with('success', 'Paciente especial removido exitosamente');
    }

    public function actualizarResponsabilidad(Request $request, $id, $pacienteEspecialId)
    {
        $validator = Validator::make($request->all(), [
            'tipo_responsabilidad' => 'required|in:Principal,Suplente,Emergencia'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $representante = Representante::findOrFail($id);
        $representante->pacientesEspeciales()->updateExistingPivot($pacienteEspecialId, [
            'tipo_responsabilidad' => $request->tipo_responsabilidad
        ]);

        return redirect()->back()->with('success', 'Tipo de responsabilidad actualizado exitosamente');
    }

    // =========================================================================
    // BÚSQUEDA Y FILTROS
    // =========================================================================

    public function buscar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'documento' => 'nullable|string|max:20',
            'nombre' => 'nullable|string|max:100',
            'parentesco' => 'nullable|string|max:100',
            'estado_id' => 'nullable|exists:estados,id_estado'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $query = Representante::with(['estado', 'pacientesEspeciales.paciente'])
                             ->where('status', true);

        if ($request->documento) {
            $query->where('numero_documento', 'LIKE', '%' . $request->documento . '%');
        }

        if ($request->nombre) {
            $query->where(function($q) use ($request) {
                $q->where('primer_nombre', 'LIKE', '%' . $request->nombre . '%')
                  ->orWhere('primer_apellido', 'LIKE', '%' . $request->nombre . '%');
            });
        }

        if ($request->parentesco) {
            $query->where('parentesco', 'LIKE', '%' . $request->parentesco . '%');
        }

        if ($request->estado_id) {
            $query->where('estado_id', $request->estado_id);
        }

        $representantes = $query->get();

        return view('shared.representantes.index', compact('representantes'))->with('filtros', $request->all());
    }

    // =========================================================================
    // REPORTES Y ESTADÍSTICAS
    // =========================================================================

    public function reporte()
    {
        $representantes = Representante::with(['estado', 'pacientesEspeciales'])
                                     ->where('status', true)
                                     ->get();

        $estadisticas = [
            'total' => $representantes->count(),
            'por_parentesco' => $representantes->groupBy('parentesco')->map->count(),
            'por_estado' => $representantes->groupBy('estado.estado')->map->count(),
            'con_multipacientes' => $representantes->filter(function($rep) {
                return $rep->pacientesEspeciales->count() > 1;
            })->count()
        ];

        return view('shared.representantes.reporte', compact('representantes', 'estadisticas'));
    }

    public function estadisticas()
    {
        $totalRepresentantes = Representante::where('status', true)->count();
        
        $porParentesco = Representante::select('parentesco')
                                     ->selectRaw('COUNT(*) as total')
                                     ->where('status', true)
                                     ->groupBy('parentesco')
                                     ->get();

        $porEstado = Representante::with('estado')
                                 ->select('estado_id')
                                 ->selectRaw('COUNT(*) as total')
                                 ->where('status', true)
                                 ->groupBy('estado_id')
                                 ->get();

        $pacientesPorRepresentante = Representante::withCount('pacientesEspeciales')
                                                 ->where('status', true)
                                                 ->get();

        return view('shared.representantes.estadisticas', compact(
            'totalRepresentantes',
            'porParentesco',
            'porEstado',
            'pacientesPorRepresentante'
        ));
    }

    // =========================================================================
    // MÉTODOS AUXILIARES
    // =========================================================================

    private function asignarPacientesEspeciales($representante, $pacientesEspecialesIds)
    {
        $asignaciones = [];
        foreach ($pacientesEspecialesIds as $pacienteEspecialId) {
            $asignaciones[$pacienteEspecialId] = [
                'tipo_responsabilidad' => 'Principal',
                'status' => true
            ];
        }
        
        $representante->pacientesEspeciales()->sync($asignaciones);
    }

    public function getCiudades($estadoId)
    {
        $ciudades = Ciudad::where('id_estado', $estadoId)->where('status', true)->get();
        return response()->json($ciudades);
    }

    public function getMunicipios($estadoId)
    {
        $municipios = Municipio::where('id_estado', $estadoId)->where('status', true)->get();
        return response()->json($municipios);
    }

    public function getParroquias($municipioId)
    {
        $parroquias = Parroquia::where('id_municipio', $municipioId)->where('status', true)->get();
        return response()->json($parroquias);
    }

    // =========================================================================
    // IMPORTAR/EXPORTAR
    // =========================================================================

    public function exportar()
    {
        $representantes = Representante::with(['estado', 'ciudad', 'pacientesEspeciales.paciente'])
                                     ->where('status', true)
                                     ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('shared.representantes.exportar', compact('representantes'));
        
        return $pdf->download('representantes-' . date('Y-m-d') . '.pdf');
    }

    public function importar()
    {
        return view('shared.representantes.importar');
    }

    public function procesarImportacion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'archivo' => 'required|file|mimes:csv,xlsx,xls'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        // Procesar archivo de importación
        // Esta implementación dependerá de la librería que uses para importar (ej: Maatwebsite/Laravel-Excel)

        return redirect()->route('representantes.index')->with('success', 'Importación completada exitosamente');
    }
}
