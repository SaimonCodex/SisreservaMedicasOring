<!-- SIDEBAR RESUMEN -->
<div class="space-y-6">
    <div class="card p-6 sticky top-6">
        <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i class="bi bi-receipt text-blue-600"></i>
            Resumen de la Cita
        </h3>
        
        <div class="space-y-3 text-sm">
            <div class="p-3 bg-gray-50 rounded-lg">
                <p class="text-xs text-gray-500">Tipo de Cita</p>
                <p class="font-semibold" id="resumen-tipo">-</p>
            </div>
            <div class="p-3 bg-blue-50 rounded-lg">
                <p class="text-xs text-gray-500">Paciente</p>
                <p class="font-semibold" id="resumen-paciente">-</p>
            </div>
            <div id="resumen-representante-container" class="p-3 bg-purple-50 rounded-lg hidden">
                <p class="text-xs text-gray-500">Representante</p>
                <p class="font-semibold" id="resumen-representante">-</p>
            </div>
            <div class="p-3 bg-amber-50 rounded-lg">
                <p class="text-xs text-gray-500">Consultorio</p>
                <p class="font-semibold" id="resumen-consultorio">-</p>
            </div>
            <div class="p-3 bg-purple-50 rounded-lg">
                <p class="text-xs text-gray-500">Especialidad</p>
                <p class="font-semibold" id="resumen-especialidad">-</p>
            </div>
            <div class="p-3 bg-emerald-50 rounded-lg">
                <p class="text-xs text-gray-500">Médico</p>
                <p class="font-semibold" id="resumen-medico">-</p>
            </div>
            <div class="p-3 bg-sky-50 rounded-lg">
                <p class="text-xs text-gray-500">Fecha y Hora</p>
                <p class="font-semibold" id="resumen-fecha">-</p>
            </div>
            <div class="p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border border-green-200">
                <p class="text-xs text-gray-500">Tarifa Total</p>
                <p class="text-2xl font-bold text-green-700" id="resumen-tarifa">$0.00</p>
                <span class="text-xs text-gray-500" id="resumen-tarifa-detalle"></span>
            </div>
        </div>
        
        <div class="mt-6 space-y-3">
            <button type="submit" class="btn btn-success w-full text-lg py-3">
                <i class="bi bi-check-lg"></i> Confirmar Cita
            </button>
            <button type="button" onclick="resetForm()" class="btn btn-outline w-full">
                <i class="bi bi-arrow-left"></i> Cancelar
            </button>
        </div>

        <!-- Banner de errores de validación -->
        <div id="submit-error-banner" class="hidden p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg mt-4 text-center font-bold text-sm"></div>
    </div>
    
    <!-- Estado -->
    <div class="card p-6 bg-medical-50 border-medical-200">
        <h4 class="font-bold text-gray-900 mb-3">Estado Inicial</h4>
        <select name="estado_cita" class="form-select">
            <option value="Programada">Programada (Pendiente)</option>
            <option value="Confirmada">Confirmada</option>
        </select>
    </div>
</div>
