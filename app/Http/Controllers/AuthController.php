<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\PreguntaCatalogo;
use App\Models\RespuestaSeguridad;
use App\Models\HistorialPassword;
use App\Models\Role;
use App\Models\Medico;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'correo' => 'required|email|max:150',
            'password' => 'required|min:8'
        ], [
            'correo.required' => 'El correo electrónico es obligatorio',
            'correo.email' => 'Debe ingresar un correo electrónico válido',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Aplicar MD5 dos veces a la contraseña
        $passwordHash = md5(md5($request->password));

        $usuario = Usuario::where('correo', $request->correo)
                         ->where('password', $passwordHash)
                         ->where('status', true)
                         ->first();

        if (!$usuario) {
            return redirect()->back()->with('error', 'Credenciales inválidas o cuenta inactiva')->withInput();
        }

        // Iniciar sesión usando el guard web explícitamente
        Auth::guard('web')->login($usuario, true);

        // Guardar en historial de passwords
        HistorialPassword::create([
            'user_id' => $usuario->id,
            'password_hash' => $passwordHash,
            'status' => true
        ]);

        // Redirigir según el rol
        return $this->redirectByRole($usuario);
    }

    public function showRegister()
    {
        $preguntas = PreguntaCatalogo::where('status', true)->get();
        $roles = Role::whereIn('id', [2, 3])->where('status', true)->get(); // Solo Médico y Paciente
        $estados = \App\Models\Estado::where('status', true)->get();
        return view('auth.register', compact('preguntas', 'roles', 'estados'));
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rol_id' => 'required|in:2,3', // 2=Médico, 3=Paciente
            'primer_nombre' => 'required|max:100',
            'primer_apellido' => 'required|max:100',
            'correo' => 'required|email|unique:usuarios,correo|max:150',
            'password' => 'required|min:8|confirmed',
            'pregunta_seguridad' => 'required|exists:preguntas_catalogo,id',
            'respuesta_seguridad' => 'required|min:2',
            'tipo_documento' => 'required|in:V,E,P,J',
            'numero_documento' => 'required|max:20',
            'fecha_nac' => 'required|date|before:-18 years',
            'prefijo_tlf' => 'required|in:+58,+57,+1,+34',
            'numero_tlf' => 'required|max:15',
            'genero' => 'required|in:Masculino,Femenino,Otro'
        ], [
            'fecha_nac.before' => 'Debe ser mayor de 18 años',
            'password.confirmed' => 'Las contraseñas no coinciden'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Crear usuario con MD5 dos veces
        $usuario = Usuario::create([
            'rol_id' => $request->rol_id,
            'correo' => $request->correo,
            'password' => md5(md5($request->password)),
            'status' => true
        ]);

        // Crear respuesta de seguridad con MD5 dos veces
        RespuestaSeguridad::create([
            'user_id' => $usuario->id,
            'pregunta_id' => $request->pregunta_seguridad,
            'respuesta_hash' => md5(md5($request->respuesta_seguridad)),
            'status' => true
        ]);

        // Crear perfil según el rol
        if ($request->rol_id == 2) { // Médico
            $this->crearPerfilMedico($usuario->id, $request);
        } else { // Paciente
            $this->crearPerfilPaciente($usuario->id, $request);
        }

        // Enviar email de confirmación
        $this->enviarEmailConfirmacion($usuario);

        // Autenticar automáticamente
        Auth::login($usuario);

        return $this->redirectByRole($usuario);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Sesión cerrada exitosamente');
    }

    public function showRecovery()
    {
        return view('auth.recovery');
    }

    public function getSecurityQuestions(Request $request)
    {
        $identifier = $request->identifier;
        
        $usuario = Usuario::where('correo', $identifier)
                          ->orWhere('numero_documento', $identifier)
                          ->first();

        if (!$usuario) {
            return response()->json(['success' => false, 'message' => 'Usuario no encontrado'], 404);
        }

        // Obtener preguntas del usuario
        // Asumiendo que hay una relación o tabla pivote. 
        // Si usamos el modelo actual de RespuestaSeguridad que parece tener solo una pregunta_id
        // Necesitamos adaptar esto si cambiamos a 3 preguntas.
        // Por ahora, buscaré las respuestas de seguridad del usuario.
        
        $respuestas = RespuestaSeguridad::where('user_id', $usuario->id)
                                        ->with('pregunta')
                                        ->get();
                                        
        if ($respuestas->isEmpty()) {
             return response()->json(['success' => false, 'message' => 'El usuario no tiene preguntas de seguridad configuradas'], 400);
        }
        
        $questions = $respuestas->map(function($respuesta) {
            return [
                'id' => $respuesta->pregunta->id,
                'pregunta' => $respuesta->pregunta->pregunta
            ];
        });

        return response()->json([
            'success' => true,
            'user_id' => $usuario->id,
            'questions' => $questions
        ]);
    }

    public function verifySecurityAnswers(Request $request)
    {
        $userId = $request->user_id;
        $usuario = Usuario::find($userId);
        
        if (!$usuario) {
            return response()->json(['success' => false, 'message' => 'Usuario no válido'], 404);
        }

        // Verificar cada respuesta
        // El request trae answer_1, answer_2, answer_3 y sus correspondientes IDs en question_X_id
        
        $allCorrect = true;
        
        // Iterar sobre las respuestas esperadas
        // Esto asume que el frontend envía los índices 1, 2, 3
        for ($i = 1; $i <= 3; $i++) {
            $questionId = $request->input("question_{$i}_id");
            $userAnswer = $request->input("answer_{$i}");
            
            if (!$questionId || !$userAnswer) {
                continue; // O manejar error
            }
            
            $respuestaAlmacenada = RespuestaSeguridad::where('user_id', $usuario->id)
                                                    ->where('pregunta_id', $questionId)
                                                    ->first();
            
            if (!$respuestaAlmacenada) {
                $allCorrect = false;
                break;
            }
            
            // Verificar hash (MD5 doble según lo visto en el código existente)
            if ($respuestaAlmacenada->respuesta_hash !== md5(md5($userAnswer))) {
                $allCorrect = false;
                break;
            }
        }
        
        if ($allCorrect) {
            // Generar token para reset password
            $token = Str::random(64);
            \DB::table('password_resets')->updateOrInsert(
                ['email' => $usuario->correo],
                ['token' => $token, 'created_at' => now()]
            );
            
            return response()->json([
                'success' => true,
                'token' => $token,
                'email' => $usuario->correo
            ]);
        } else {
            return response()->json(['success' => false, 'message' => 'Respuestas incorrectas'], 400);
        }

    }

    public function showResetPassword($token)
    {
        $reset = \DB::table('password_resets')->where('token', $token)->first();
        
        if (!$reset) {
            return redirect()->route('login')->with('error', 'Token inválido o expirado');
        }

        return view('auth.reset-password', compact('token'));
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $reset = \DB::table('password_resets')
                    ->where('email', $request->email)
                    ->where('token', $request->token)
                    ->first();

        if (!$reset) {
            return redirect()->back()->with('error', 'Token inválido o expirado');
        }

        $usuario = Usuario::where('correo', $request->email)->first();
        
        if (!$usuario) {
            return redirect()->back()->with('error', 'Usuario no encontrado');
        }

        // Actualizar contraseña con MD5 dos veces
        $usuario->update(['password' => md5(md5($request->password))]);

        // Guardar en historial
        HistorialPassword::create([
            'user_id' => $usuario->id,
            'password_hash' => md5(md5($request->password)),
            'status' => true
        ]);

        // Eliminar token
        \DB::table('password_resets')->where('email', $request->email)->delete();

        // Enviar notificación
        $this->enviarEmailConfirmacionCambio($usuario);

        return redirect()->route('login')->with('success', 'Contraseña restablecida exitosamente');
    }

    private function crearPerfilMedico($userId, $request)
    {
        Medico::create([
            'user_id' => $userId,
            'primer_nombre' => $request->primer_nombre,
            'segundo_nombre' => $request->segundo_nombre ?? null,
            'primer_apellido' => $request->primer_apellido,
            'segundo_apellido' => $request->segundo_apellido ?? null,
            'tipo_documento' => $request->tipo_documento,
            'numero_documento' => $request->numero_documento,
            'fecha_nac' => $request->fecha_nac,
            'estado_id' => $request->estado_id ?? null,
            'ciudad_id' => $request->ciudad_id ?? null,
            'prefijo_tlf' => $request->prefijo_tlf,
            'numero_tlf' => $request->numero_tlf,
            'genero' => $request->genero,
            'status' => true
        ]);
    }

    private function crearPerfilPaciente($userId, $request)
    {
        Paciente::create([
            'user_id' => $userId,
            'primer_nombre' => $request->primer_nombre,
            'segundo_nombre' => $request->segundo_nombre ?? null,
            'primer_apellido' => $request->primer_apellido,
            'segundo_apellido' => $request->segundo_apellido ?? null,
            'tipo_documento' => $request->tipo_documento,
            'numero_documento' => $request->numero_documento,
            'fecha_nac' => $request->fecha_nac,
            'estado_id' => $request->estado_id ?? null,
            'ciudad_id' => $request->ciudad_id ?? null,
            'prefijo_tlf' => $request->prefijo_tlf,
            'numero_tlf' => $request->numero_tlf,
            'genero' => $request->genero,
            'ocupacion' => $request->ocupacion ?? null,
            'estado_civil' => $request->estado_civil ?? null,
            'status' => true
        ]);
    }

    private function redirectByRole($usuario)
    {
        switch ($usuario->rol_id) {
            case 1: // Admin
                return redirect()->route('admin.dashboard')->with('success', 'Bienvenido Administrador');
            case 2: // Médico
                return redirect()->route('medico.dashboard')->with('success', 'Bienvenido Doctor');
            case 3: // Paciente
                return redirect()->route('paciente.dashboard')->with('success', 'Bienvenido Paciente');
            default:
                return redirect()->route('home');
        }
    }

    private function enviarEmailConfirmacion($usuario)
    {
        try {
            Mail::send('emails.confirmacion', ['usuario' => $usuario], function($message) use ($usuario) {
                $message->to($usuario->correo)
                        ->subject('Confirmación de Registro - Sistema Médico');
            });
        } catch (\Exception $e) {
            \Log::error('Error enviando email de confirmación: ' . $e->getMessage());
        }
    }

    private function enviarEmailRecuperacion($usuario, $token)
    {
        try {
            $resetUrl = route('password.reset', $token);
            
            Mail::send('emails.recuperacion', [
                'usuario' => $usuario,
                'resetUrl' => $resetUrl
            ], function($message) use ($usuario) {
                $message->to($usuario->correo)
                        ->subject('Recuperación de Contraseña - Sistema Médico');
            });
        } catch (\Exception $e) {
            \Log::error('Error enviando email de recuperación: ' . $e->getMessage());
        }
    }

    private function enviarEmailConfirmacionCambio($usuario)
    {
        try {
            Mail::send('emails.confirmacion-cambio-password', ['usuario' => $usuario], function($message) use ($usuario) {
                $message->to($usuario->correo)
                        ->subject('Contraseña Cambiada - Sistema Médico');
            });
        } catch (\Exception $e) {
            \Log::error('Error enviando email de confirmación de cambio: ' . $e->getMessage());
        }
    }
}