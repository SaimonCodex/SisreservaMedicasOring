<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Paciente;

class ValidationController extends Controller
{
    public function checkEmail(Request $request)
    {
        $exists = Usuario::where('correo', $request->email)->exists();
        return response()->json(['exists' => $exists]);
    }

    public function checkDocument(Request $request)
    {
        $exists = Paciente::where('tipo_documento', $request->tipo)
                         ->where('numero_documento', $request->numero)
                         ->exists();
        return response()->json(['exists' => $exists]);
    }
}
