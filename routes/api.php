<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CitaController;
use App\Http\Controllers\Api\MedicoController;
use App\Http\Controllers\Api\PacienteController;

// Rutas públicas
Route::post('/login', [AuthController::class, 'login'])->name('api.login');
Route::post('/register', [AuthController::class, 'register'])->name('api.register');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('api.password.email');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('api.password.update');

// Catálogos públicos
Route::get('/especialidades', [MedicoController::class, 'getEspecialidades'])->name('api.especialidades');
Route::get('/consultorios', [MedicoController::class, 'getConsultorios'])->name('api.consultorios');
Route::get('/medicos/buscar', [MedicoController::class, 'buscarMedicos'])->name('api.medicos.buscar');
Route::get('/medicos/{medico}', [MedicoController::class, 'showPublic'])->name('api.medicos.public');

// Rutas protegidas
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');
    Route::get('/user', function (Request $request) {
        return $request->user()->load(['paciente', 'medico', 'administrador']);
    })->name('api.user');
    
    // Citas
    Route::get('/citas', [CitaController::class, 'index'])->name('api.citas.index');
    Route::post('/citas', [CitaController::class, 'store'])->name('api.citas.store');
    Route::get('/citas/{cita}', [CitaController::class, 'show'])->name('api.citas.show');
    Route::put('/citas/{cita}', [CitaController::class, 'update'])->name('api.citas.update');
    Route::delete('/citas/{cita}', [CitaController::class, 'destroy'])->name('api.citas.destroy');
    
    // Médicos (solo para médicos autenticados)
    Route::middleware(['role:medico'])->group(function () {
        Route::get('/medicos/mis-citas', [MedicoController::class, 'misCitas'])->name('api.medicos.mis-citas');
        Route::get('/medicos/mis-pacientes', [MedicoController::class, 'misPacientes'])->name('api.medicos.mis-pacientes');
    });
    
    // Pacientes (solo para pacientes autenticados)
    Route::middleware(['role:paciente'])->group(function () {
        Route::get('/pacientes/mis-citas', [PacienteController::class, 'misCitas'])->name('api.pacientes.mis-citas');
        Route::get('/pacientes/mis-facturas', [PacienteController::class, 'misFacturas'])->name('api.pacientes.mis-facturas');
    });
});

// Health check
Route::get('/health', function () {
    return response()->json([
        'status' => 'OK',
        'timestamp' => now(),
        'environment' => app()->environment()
    ]);
});

// Rutas para citas (acceso sin autenticación para AJAX del frontend)
Route::get('/citas/medicos-por-especialidad/{especialidadId}', [\App\Http\Controllers\CitaController::class, 'getMedicosPorEspecialidad']);
Route::get('/citas/consultorios-por-medico/{medicoId}', [\App\Http\Controllers\CitaController::class, 'getConsultoriosPorMedico']);
