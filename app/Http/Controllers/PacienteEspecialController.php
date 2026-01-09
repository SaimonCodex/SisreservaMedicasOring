<?php

namespace App\Http\Controllers;

use App\Models\PacienteEspecial;
use App\Models\Paciente;
use App\Models\Representante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PacienteEspecialController extends Controller
{
    // =========================================================================
    // CRUD DE PACIENTES ESPECIALES
    // =========================================================================

    public function index()
    {
        $pacientesEspeciales = PacienteEspecial::with(['paciente.usuario', 'representantes'])
                                             ->where('status', true)
                                             ->paginate(10);
        
        return view('shared.pacientes-especiales.index', compact('pacientesEspeciales'));
    }

    public function create()
    {
        $pacientes = Paciente::where('status', true)->get();
        $representantes = Representante::where('status', true)->get();
        return view('shared.pacientes-especiales.create', compact('pacientes', 'representantes'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'paciente_id' => 'required|exists:pacientes,id|unique:pacientes_especiales,paciente_id',
            'tipo' => 'required|in:Menor de Edad,Discapacitado,Anciano,Incapacitado',
            'observaciones' => 'nullable|string',
            'representantes' => 'nullable|array',
            'representantes.*' => 'exists:representantes,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Verificar que el paciente no sea ya un paciente especial
        $existe = PacienteEspecial::where('paciente_id', $request->paciente_id)
                                 ->where('status', true)
                                 ->exists();

        if ($existe) {
            return redirect()->back()->with('error', 'Este paciente ya está registrado como paciente especial.')->withInput();
        }

        $pacienteEspecial = PacienteEspecial::create($request->except('representantes'));

        // Asignar representantes si se proporcionaron
        if ($request->has('representantes')) {
            $this->asignarRepresentantes($pacienteEspecial, $request->representantes);
        }

        return redirect()->route('pacientes-especiales.index')->with('success', 'Paciente especial creado exitosamente');
    }

    public function show($id)
    {
        $pacienteEspecial = PacienteEspecial::with([
            'paciente.usuario',
            'paciente.estado',
            'paciente.ciudad',
            'representantes'
        ])->findOrFail($id);

        $historialCitas = \App\Models\Cita::where('paciente_id', $pacienteEspecial->paciente_id)
                                         ->with(['medico', 'especialidad'])
                                         ->where('status', true)
                                         ->orderBy('fecha_cita', 'desc')
                                         ->get();

        $historialMedico = \App\Models\EvolucionClinica::where('paciente_id', $pacienteEspecial->paciente_id)
                                                      ->with(['medico', 'cita.especialidad'])
                                                      ->where('status', true)
                                                      ->orderBy('created_at', 'desc')
                                                      ->get();

        return view('shared.pacientes-especiales.show', compact('pacienteEspecial', 'historialCitas', 'historialMedico'));
    }

    public function edit($id)
    {
        $pacienteEspecial = PacienteEspecial::findOrFail($id);
        $pacientes = Paciente::where('status', true)->get();
        $representantes = Representante::where('status', true)->get();

        return view('shared.pacientes-especiales.edit', compact('pacienteEspecial', 'pacientes', 'representantes'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'paciente_id' => 'required|exists:pacientes,id|unique:pacientes_especiales,paciente_id,' . $id,
            'tipo' => 'required|in:Menor de Edad,Discapacitado,Anciano,Incapacitado',
            'observaciones' => 'nullable|string',
            'representantes' => 'nullable|array',
            'representantes.*' => 'exists:representantes,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $pacienteEspecial = PacienteEspecial::findOrFail($id);
        $pacienteEspecial->update($request->except('representantes'));

        // Sincronizar representantes
        if ($request->has('representantes')) {
            $this->asignarRepresentantes($pacienteEspecial, $request->representantes);
        } else {
            $pacienteEspecial->representantes()->detach();
        }

        return redirect()->route('pacientes-especiales.index')->with('success', 'Paciente especial actualizado exitosamente');
    }

    public function destroy($id)
    {
        $pacienteEspecial = PacienteEspecial::findOrFail($id);
        $pacienteEspecial->update(['status' => false]);

        return redirect()->route('pacientes-especiales.index')->with('success', 'Paciente especial desactivado exitosamente');
    }

    // =========================================================================
    // GESTIÓN DE REPRESENTANTES ASIGNADOS
    // =========================================================================

    public function asignarRepresentante(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'representante_id' => 'required|exists:representantes,id',
            'tipo_responsabilidad' => 'required|in:Principal,Suplente,Emergencia'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $pacienteEspecial = PacienteEspecial::findOrFail($id);

        // Verificar que no esté ya asignado
        $existe = $pacienteEspecial->representantes()
                                  ->where('representante_id', $request->representante_id)
                                  ->exists();

        if ($existe) {
            return redirect()->back()->with('error', 'Este representante ya está asignado al paciente.');
        }

        $pacienteEspecial->representantes()->attach($request->representante_id, [
            'tipo_responsabilidad' => $request->tipo_responsabilidad
        ]);

        return redirect()->back()->with('success', 'Representante asignado exitosamente');
    }

    public function removerRepresentante($id, $representanteId)
    {
        $pacienteEspecial = PacienteEspecial::findOrFail($id);
        $pacienteEspecial->representantes()->detach($representanteId);

        return redirect()->back()->with('success', 'Representante removido exitosamente');
    }

    public function actualizarResponsabilidad(Request $request, $id, $representanteId)
    {
        $validator = Validator::make($request->all(), [
            'tipo_responsabilidad' => 'required|in:Principal,Suplente,Emergencia'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $pacienteEspecial = PacienteEspecial::findOrFail($id);
        $pacienteEspecial->representantes()->updateExistingPivot($representanteId, [
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
            'tipo' => 'nullable|in:Menor de Edad,Discapacitado,Anciano,Incapacitado',
            'nombre_paciente' => 'nullable|string|max:100',
            'documento_paciente' => 'nullable|string|max:20',
            'con_representante' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $query = PacienteEspecial::with(['paciente.usuario', 'representantes'])
                               ->where('status', true);

        if ($request->tipo) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->nombre_paciente) {
            $query->whereHas('paciente', function($q) use ($request) {
                $q->where('primer_nombre', 'LIKE', '%' . $request->nombre_paciente . '%')
                  ->orWhere('primer_apellido', 'LIKE', '%' . $request->nombre_paciente . '%');
            });
        }

        if ($request->documento_paciente) {
            $query->whereHas('paciente', function($q) use ($request) {
                $q->where('numero_documento', 'LIKE', '%' . $request->documento_paciente . '%');
            });
        }

        if ($request->has('con_representante')) {
            if ($request->con_representante) {
                $query->has('representantes');
            } else {
                $query->doesntHave('representantes');
            }
        }

        $pacientesEspeciales = $query->paginate(10);

        return view('shared.pacientes-especiales.index', compact('pacientesEspeciales'))->with('filtros', $request->all());
    }

    // =========================================================================
    // REPORTES Y ESTADÍSTICAS
    // =========================================================================

    public function reporte()
    {
        $pacientesEspeciales = PacienteEspecial::with(['paciente', 'representantes'])
                                             ->where('status', true)
                                             ->get();

        $estadisticas = [
            'total' => $pacientesEspeciales->count(),
            'por_tipo' => $pacientesEspeciales->groupBy('tipo')->map->count(),
            'sin_representante' => $pacientesEspeciales->filter(function($paciente) {
                return $paciente->representantes->isEmpty();
            })->count(),
            'con_multirepresentantes' => $pacientesEspeciales->filter(function($paciente) {
                return $paciente->representantes->count() > 1;
            })->count()
        ];

        return view('shared.pacientes-especiales.reporte', compact('pacientesEspeciales', 'estadisticas'));
    }

    public function estadisticas()
    {
        $totalPacientesEspeciales = PacienteEspecial::where('status', true)->count();
        
        $porTipo = PacienteEspecial::select('tipo')
                                 ->selectRaw('COUNT(*) as total')
                                 ->where('status', true)
                                 ->groupBy('tipo')
                                 ->get();

        $conRepresentante = PacienteEspecial::has('representantes')->where('status', true)->count();
        $sinRepresentante = PacienteEspecial::doesntHave('representantes')->where('status', true)->count();

        $porRangoEdad = $this->calcularRangosEdad();

        return view('shared.pacientes-especiales.estadisticas', compact(
            'totalPacientesEspeciales',
            'porTipo',
            'conRepresentante',
            'sinRepresentante',
            'porRangoEdad'
        ));
    }

    // =========================================================================
    // MÉTODOS AUXILIARES
    // =========================================================================

    private function asignarRepresentantes($pacienteEspecial, $representantesIds)
    {
        $asignaciones = [];
        foreach ($representantesIds as $representanteId) {
            $asignaciones[$representanteId] = [
                'tipo_responsabilidad' => 'Principal',
                'status' => true
            ];
        }
        
        $pacienteEspecial->representantes()->sync($asignaciones);
    }

    private function calcularRangosEdad()
    {
        $rangos = [
            '0-12' => 0,
            '13-17' => 0,
            '18-59' => 0,
            '60+' => 0
        ];

        $pacientesEspeciales = PacienteEspecial::with('paciente')->where('status', true)->get();

        foreach ($pacientesEspeciales as $pacienteEspecial) {
            if ($pacienteEspecial->paciente->fecha_nac) {
                $edad = \Carbon\Carbon::parse($pacienteEspecial->paciente->fecha_nac)->age;
                
                if ($edad <= 12) {
                    $rangos['0-12']++;
                } elseif ($edad <= 17) {
                    $rangos['13-17']++;
                } elseif ($edad <= 59) {
                    $rangos['18-59']++;
                } else {
                    $rangos['60+']++;
                }
            }
        }

        return $rangos;
    }

    // =========================================================================
    // VALIDACIÓN DE NECESIDAD DE REPRESENTANTE
    // =========================================================================

    public function validarNecesidadRepresentante($pacienteId)
    {
        $paciente = Paciente::findOrFail($pacienteId);
        
        $necesitaRepresentante = false;
        $motivo = '';

        if ($paciente->fecha_nac) {
            $edad = \Carbon\Carbon::parse($paciente->fecha_nac)->age;
            
            if ($edad < 18) {
                $necesitaRepresentante = true;
                $motivo = 'Menor de edad (' . $edad . ' años)';
            }
        }

        // También se podrían agregar otras validaciones (discapacidad, etc.)

        return response()->json([
            'necesita_representante' => $necesitaRepresentante,
            'motivo' => $motivo,
            'edad' => $edad ?? null
        ]);
    }

    // =========================================================================
    // AUTOMATIZACIÓN DE REGISTRO
    // =========================================================================

    public function registrarAutomatico($pacienteId)
    {
        $paciente = Paciente::findOrFail($pacienteId);
        
        // Verificar si ya es paciente especial
        $existe = PacienteEspecial::where('paciente_id', $pacienteId)->exists();
        if ($existe) {
            return redirect()->back()->with('error', 'El paciente ya está registrado como paciente especial.');
        }

        // Determinar el tipo automáticamente basado en la edad
        $tipo = 'Incapacitado'; // Valor por defecto
        
        if ($paciente->fecha_nac) {
            $edad = \Carbon\Carbon::parse($paciente->fecha_nac)->age;
            
            if ($edad < 18) {
                $tipo = 'Menor de Edad';
            } elseif ($edad >= 60) {
                $tipo = 'Anciano';
            }
        }

        $pacienteEspecial = PacienteEspecial::create([
            'paciente_id' => $pacienteId,
            'tipo' => $tipo,
            'observaciones' => 'Registro automático por sistema',
            'status' => true
        ]);

        return redirect()->route('pacientes-especiales.show', $pacienteEspecial->id)
                       ->with('success', 'Paciente especial registrado automáticamente');
    }

    // =========================================================================
    // IMPORTAR/EXPORTAR
    // =========================================================================

    public function exportar()
    {
        $pacientesEspeciales = PacienteEspecial::with(['paciente.usuario', 'representantes'])
                                             ->where('status', true)
                                             ->get();

        $totalPacientes = $pacientesEspeciales->count();
        // Calculate fields based on first record or schema? For now hardcode or count columns
        $totalCampos = 7; // Default basic fields
        
        return view('shared.pacientes-especiales.exportar', compact('pacientesEspeciales', 'totalPacientes', 'totalCampos'));
    }

    public function procesarExportacion(Request $request)
    {
        // Logic to process export based on request->formato
        $pacientesEspeciales = PacienteEspecial::with(['paciente.usuario', 'representantes'])
                                             ->where('status', true)
                                             ->get();

        if ($request->formato == 'pdf') {
             $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('shared.pacientes-especiales.pdf_export', compact('pacientesEspeciales'));
             return $pdf->download('pacientes-especiales-' . date('Y-m-d') . '.pdf');
        }
        
        // Placeholder for Excel/CSV - In real app use Maatwebsite/Excel
        return redirect()->back()->with('success', 'Exportación iniciada (Simulación)');
    }

    public function importar()
    {
        return view('shared.pacientes-especiales.importar');
    }

    public function procesarImportacion(Request $request)
    {
        // Validation
        $request->validate([
            'archivo' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        // Logic to import
        return redirect()->back()->with('success', 'Importación completada exitosamente (Simulación)');
    }

    public function descargarPlantilla($formato)
    {
        // Logic to download template
        return redirect()->back()->with('success', 'Plantilla descargada (Simulación)');
    }

    public function generarReporte(Request $request)
    {
        $pacientesEspeciales = PacienteEspecial::with(['paciente.usuario', 'representantes'])
                                             ->where('status', true)
                                             ->get();
                                             
        if ($request->formato == 'pdf') {
             // We can reuse a simple list view for PDF report
             // For now we will use a basic view or simulating it
             $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('shared.pacientes-especiales.reporte_pdf', compact('pacientesEspeciales', 'request'));
             return $pdf->stream('reporte-pacientes-especiales-' . date('Y-m-d') . '.pdf');
        }

        return redirect()->back()->with('success', 'Reporte generado (Simulación)');
    }

    public function carnet($id)
    {
        $pacienteEspecial = PacienteEspecial::with(['paciente.usuario', 'representantes'])
                                          ->findOrFail($id);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('shared.pacientes-especiales.carnet', compact('pacienteEspecial'));
        
        return $pdf->download('carnet-paciente-especial-' . $pacienteEspecial->paciente->primer_nombre . '.pdf');
    }
}
