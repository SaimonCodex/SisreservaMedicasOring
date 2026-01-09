<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Medico;
use App\Models\Paciente;
use App\Models\Especialidad;
use App\Models\Consultorio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class CitaController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = Cita::with(['paciente', 'medico', 'especialidad', 'consultorio']);

        if ($user->rol_id == 2) { // Médico
            $medico = $user->medico;
            $query->where('medico_id', $medico->id);
        } elseif ($user->rol_id == 3) { // Paciente
            $paciente = $user->paciente;
            $query->where('paciente_id', $paciente->id);
        }

        $citas = $query->where('status', true)
                       ->orderBy('fecha_cita', 'desc')
                       ->get();

        return view('shared.citas.index', compact('citas'));
    }

    public function create()
    {
        $medicos = Medico::with('especialidades')->where('status', true)->get();
        $pacientes = Paciente::where('status', true)->get();
        $especialidades = Especialidad::where('status', true)->get();
        $consultorios = Consultorio::where('status', true)->get();

        return view('shared.citas.create', compact('medicos', 'pacientes', 'especialidades', 'consultorios'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'paciente_id' => 'required|exists:pacientes,id',
            'medico_id' => 'required|exists:medicos,id',
            'especialidad_id' => 'required|exists:especialidades,id',
            'consultorio_id' => 'nullable|exists:consultorios,id',
            'fecha_cita' => 'required|date|after_or_equal:today',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'tipo_consulta' => 'required|in:Presencial,Telemedicina,Domicilio',
            'observaciones' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Verificar disponibilidad del médico
        $citaExistente = Cita::where('medico_id', $request->medico_id)
                            ->where('fecha_cita', $request->fecha_cita)
                            ->where('hora_inicio', '<', $request->hora_fin)
                            ->where('hora_fin', '>', $request->hora_inicio)
                            ->where('status', true)
                            ->exists();

        if ($citaExistente) {
            return redirect()->back()->with('error', 'El médico no está disponible en ese horario')->withInput();
        }

        $cita = Cita::create(array_merge($request->all(), [
            'duracion_minutos' => $this->calcularDuracion($request->hora_inicio, $request->hora_fin)
        ]));

        // Enviar notificación
        $this->enviarNotificacionCita($cita);

        return redirect()->route('citas.index')->with('success', 'Cita creada exitosamente');
    }

    public function show($id)
    {
        $cita = Cita::with(['paciente', 'medico', 'especialidad', 'consultorio', 'evolucionClinica'])->findOrFail($id);
        return view('shared.citas.show', compact('cita'));
    }

    public function edit($id)
    {
        $cita = Cita::findOrFail($id);
        $medicos = Medico::with('especialidades')->where('status', true)->get();
        $pacientes = Paciente::where('status', true)->get();
        $especialidades = Especialidad::where('status', true)->get();
        $consultorios = Consultorio::where('status', true)->get();

        return view('shared.citas.edit', compact('cita', 'medicos', 'pacientes', 'especialidades', 'consultorios'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'paciente_id' => 'required|exists:pacientes,id',
            'medico_id' => 'required|exists:medicos,id',
            'especialidad_id' => 'required|exists:especialidades,id',
            'consultorio_id' => 'nullable|exists:consultorios,id',
            'fecha_cita' => 'required|date',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'tipo_consulta' => 'required|in:Presencial,Telemedicina,Domicilio',
            'estado_cita' => 'required|in:Programada,Confirmada,En Progreso,Completada,Cancelada,No Asistió',
            'observaciones' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $cita = Cita::findOrFail($id);
        
        // Verificar disponibilidad (excluyendo la cita actual)
        $citaExistente = Cita::where('medico_id', $request->medico_id)
                            ->where('fecha_cita', $request->fecha_cita)
                            ->where('hora_inicio', '<', $request->hora_fin)
                            ->where('hora_fin', '>', $request->hora_inicio)
                            ->where('id', '!=', $id)
                            ->where('status', true)
                            ->exists();

        if ($citaExistente) {
            return redirect()->back()->with('error', 'El médico no está disponible en ese horario')->withInput();
        }

        $cita->update(array_merge($request->all(), [
            'duracion_minutos' => $this->calcularDuracion($request->hora_inicio, $request->hora_fin)
        ]));

        return redirect()->route('citas.index')->with('success', 'Cita actualizada exitosamente');
    }

    public function destroy($id)
    {
        $cita = Cita::findOrFail($id);
        $cita->update(['status' => false]);

        return redirect()->route('citas.index')->with('success', 'Cita cancelada exitosamente');
    }

    public function cambiarEstado(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'estado_cita' => 'required|in:Programada,Confirmada,En Progreso,Completada,Cancelada,No Asistió'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $cita = Cita::findOrFail($id);
        $cita->update(['estado_cita' => $request->estado_cita]);

        return redirect()->back()->with('success', 'Estado de cita actualizado exitosamente');
    }

    public function buscarDisponibilidad(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'medico_id' => 'required|exists:medicos,id',
            'fecha' => 'required|date|after_or_equal:today',
            'especialidad_id' => 'nullable|exists:especialidades,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $medico = Medico::find($request->medico_id);
        $horarios = \App\Models\MedicoConsultorio::where('medico_id', $request->medico_id)
                                               ->where('dia_semana', $this->obtenerDiaSemana($request->fecha))
                                               ->where('status', true)
                                               ->get();

        // Obtener citas existentes para esa fecha
        $citasOcupadas = Cita::where('medico_id', $request->medico_id)
                            ->where('fecha_cita', $request->fecha)
                            ->where('status', true)
                            ->get()
                            ->map(function($cita) {
                                return [
                                    'inicio' => $cita->hora_inicio,
                                    'fin' => $cita->hora_fin
                                ];
                            });

        return response()->json([
            'horarios' => $horarios,
            'citas_ocupadas' => $citasOcupadas
        ]);
    }

    private function calcularDuracion($horaInicio, $horaFin)
    {
        $inicio = \Carbon\Carbon::createFromFormat('H:i', $horaInicio);
        $fin = \Carbon\Carbon::createFromFormat('H:i', $horaFin);
        return $inicio->diffInMinutes($fin);
    }

    private function obtenerDiaSemana($fecha)
    {
        $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        return $dias[date('w', strtotime($fecha))];
    }

    private function enviarNotificacionCita($cita)
    {
        try {
            Mail::send('emails.cita', ['cita' => $cita], function($message) use ($cita) {
                $message->to($cita->paciente->usuario->correo)
                        ->subject('Confirmación de Cita - Sistema Médico');
            });
        } catch (\Exception $e) {
            \Log::error('Error enviando notificación de cita: ' . $e->getMessage());
        }
    }
}
