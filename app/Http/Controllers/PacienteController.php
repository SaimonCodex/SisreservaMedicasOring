<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\Usuario;
use App\Models\Estado;
use App\Models\Ciudad;
use App\Models\Municipio;
use App\Models\Parroquia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PacienteController extends Controller
{
    public function dashboard()
    {
        $paciente = auth()->user()->paciente;
        $citasProximas = \App\Models\Cita::where('paciente_id', $paciente->id)
                                       ->where('fecha_cita', '>=', today())
                                       ->where('status', true)
                                       ->orderBy('fecha_cita')
                                       ->limit(5)
                                       ->get();

        $historialCitas = \App\Models\Cita::where('paciente_id', $paciente->id)
                                         ->where('fecha_cita', '<', today())
                                         ->where('status', true)
                                         ->orderBy('fecha_cita', 'desc')
                                         ->limit(10)
                                         ->get();

        return view('paciente.dashboard', compact('citasProximas', 'historialCitas'));
    }

    public function index()
    {
        $pacientes = Paciente::with(['usuario', 'estado'])->where('status', true)->get();
        return view('shared.pacientes.index', compact('pacientes'));
    }

    public function create()
    {
        $usuarios = Usuario::where('status', true)->where('rol_id', 3)
                          ->whereNotIn('id', function($query) {
                              $query->select('user_id')->from('pacientes')->whereNotNull('user_id');
                          })->get();

        $estados = Estado::where('status', true)->get();
        return view('shared.pacientes.create', compact('usuarios', 'estados'));
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
            'prefijo_tlf' => 'nullable|in:+58,+57,+1,+34',
            'numero_tlf' => 'nullable|max:15',
            'genero' => 'nullable|max:20',
            'ocupacion' => 'nullable|max:150',
            'estado_civil' => 'nullable|max:50',
            // User credentials
            'correo' => 'required|email|unique:usuarios,correo',
            'password' => 'required|min:8|confirmed'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
                // 1. Create User
                $usuario = Usuario::create([
                    'rol_id' => 3, // Paciente
                    'correo' => $request->correo,
                    'password' => $request->password,
                    'status' => $request->has('status')
                ]);

                // 2. Create Paciente Profile
                $pacienteData = $request->except(['correo', 'password', 'password_confirmation', 'status']);
                $pacienteData['user_id'] = $usuario->id;
                $pacienteData['status'] = $request->has('status');

                Paciente::create($pacienteData);
            });

            return redirect()->route('pacientes.index')->with('success', 'Paciente creado exitosamente');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al crear el paciente: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $paciente = Paciente::with(['usuario', 'estado', 'ciudad', 'municipio', 'parroquia', 'historiaClinicaBase'])->findOrFail($id);
        return view('shared.pacientes.show', compact('paciente'));
    }

    public function edit($id)
    {
        $paciente = Paciente::findOrFail($id);
        $usuarios = Usuario::where('status', true)->where('rol_id', 3)->get();
        $estados = Estado::where('status', true)->get();
        $ciudades = Ciudad::where('status', true)->get();
        $municipios = Municipio::where('status', true)->get();
        $parroquias = Parroquia::where('status', true)->get();

        return view('shared.pacientes.edit', compact('paciente', 'usuarios', 'estados', 'ciudades', 'municipios', 'parroquias'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'nullable|exists:usuarios,id|unique:pacientes,user_id,' . $id,
            'primer_nombre' => 'required|max:100',
            'primer_apellido' => 'required|max:100',
            'tipo_documento' => 'nullable|in:V,E,P,J',
            'numero_documento' => 'nullable|max:20',
            'fecha_nac' => 'nullable|date',
            'estado_id' => 'nullable|exists:estados,id_estado',
            'ciudad_id' => 'nullable|exists:ciudades,id_ciudad',
            'municipio_id' => 'nullable|exists:municipios,id_municipio',
            'parroquia_id' => 'nullable|exists:parroquias,id_parroquia',
            'prefijo_tlf' => 'nullable|in:+58,+57,+1,+34',
            'numero_tlf' => 'nullable|max:15',
            'genero' => 'nullable|max:20',
            'ocupacion' => 'nullable|max:150',
            'estado_civil' => 'nullable|max:50'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $paciente = Paciente::findOrFail($id);
        $paciente->update($request->all());

        return redirect()->route('pacientes.index')->with('success', 'Paciente actualizado exitosamente');
    }

    public function destroy($id)
    {
        $paciente = Paciente::findOrFail($id);
        $paciente->update(['status' => false]);

        return redirect()->route('pacientes.index')->with('success', 'Paciente desactivado exitosamente');
    }

    public function historiaClinica($id)
    {
        $paciente = Paciente::with('historiaClinicaBase')->findOrFail($id);
        return view('pacientes.historia-clinica', compact('paciente'));
    }

    public function actualizarHistoriaClinica(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'tipo_sangre' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'alergias' => 'nullable|string',
            'alergias_medicamentos' => 'nullable|string',
            'antecedentes_familiares' => 'nullable|string',
            'antecedentes_personales' => 'nullable|string',
            'enfermedades_cronicas' => 'nullable|string',
            'medicamentos_actuales' => 'nullable|string',
            'cirugias_previas' => 'nullable|string',
            'habitos' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $paciente = Paciente::findOrFail($id);
        $historia = $paciente->historiaClinicaBase;

        if ($historia) {
            $historia->update($request->all());
        } else {
            \App\Models\HistoriaClinicaBase::create(array_merge(
                ['paciente_id' => $id],
                $request->all()
            ));
        }

        return redirect()->back()->with('success', 'Historia clínica actualizada exitosamente');
    }
}
