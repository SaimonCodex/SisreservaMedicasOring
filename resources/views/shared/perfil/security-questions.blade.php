@extends(auth()->user()->rol_id == 1 ? 'layouts.admin' : (auth()->user()->rol_id == 2 ? 'layouts.medico' : 'layouts.paciente'))

@section('title', 'Preguntas de Seguridad')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ auth()->user()->rol_id == 1 ? route('admin.perfil.edit') : (auth()->user()->rol_id == 2 ? route('medico.perfil.edit') : route('paciente.perfil.edit')) }}" 
               class="text-slate-400 hover:text-slate-600 transition-colors">
                <i class="bi bi-arrow-left text-xl"></i>
            </a>
            <h1 class="text-3xl font-bold text-slate-900">Preguntas de Seguridad</h1>
        </div>
        <p class="text-slate-600 ml-11">Gestiona tus preguntas de seguridad para recuperación de contraseña</p>
    </div>

    <!-- Security Alert -->
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg mb-6">
        <div class="flex items-start">
            <i class="bi bi-info-circle-fill text-blue-500 mt-0.5 text-xl"></i>
            <div class="ml-3">
                <h3 class="text-sm font-semibold text-blue-800">Medida de Seguridad</h3>
                <p class="text-sm text-blue-700 mt-1">
                    Para proteger tu cuenta, debes ingresar tu <strong>contraseña actual</strong> antes de cambiar tus preguntas de seguridad.
                </p>
            </div>
        </div>
    </div>

    <!-- Current Questions (if any) -->
    @if($currentQuestions->count() > 0)
    <div class="bg-slate-50 rounded-xl p-6 mb-6">
        <h3 class="text-lg font-semibold text-slate-900 mb-4 flex items-center gap-2">
            <i class="bi bi-shield-check text-green-600"></i>
            Preguntas Actuales
        </h3>
        <ul class="space-y-2">
            @foreach($currentQuestions as $index => $current)
                <li class="flex items-center gap-2 text-slate-700">
                    <i class="bi bi-check-circle-fill text-green-500 text-sm"></i>
                    <span class="font-medium">{{ $index + 1 }}.</span>
                    <span>{{ $current->pregunta->pregunta }}</span>
                </li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Form -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <form method="POST" action="{{ auth()->user()->rol_id == 1 ? route('admin.security-questions.update') : (auth()->user()->rol_id == 2 ? route('medico.security-questions.update') : route('paciente.security-questions.update')) }}" id="securityQuestionsForm">
            @csrf

            <!-- Password Verification -->
            <div class="mb-8 pb-8 border-b border-slate-200">
                <label for="current_password" class="block text-sm font-semibold text-slate-700 mb-2">
                    <i class="bi bi-lock-fill text-slate-400"></i> Contraseña Actual *
                </label>
                <div class="relative">
                    <input 
                        type="password" 
                        name="current_password" 
                        id="current_password"
                        class="block w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all pr-12 @error('current_password') border-red-300 @enderror"
                        placeholder="Ingresa tu contraseña actual"
                        required
                    >
                    <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-none p-1" onclick="togglePasswordVisibility('current_password', this)">
                        <i class="bi bi-eye-slash-fill text-lg"></i>
                    </button>
                </div>
                @error('current_password')
                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        {{ $message }}
                    </p>
                @enderror
                <p class="mt-2 text-xs text-slate-500">
                    <i class="bi bi-shield-lock"></i> Por seguridad, verifica tu contraseña antes de continuar.
                </p>
            </div>

            <!-- Security Questions -->
            <div class="space-y-6">
            @for($i = 1; $i <= 3; $i++)
            <div class="bg-slate-50 rounded-xl p-5 border border-slate-100 transition-all hover:shadow-md duration-300 security-question-card" data-index="{{ $i }}">
                <div class="flex items-center gap-3 mb-4">
                    <span class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-600 text-white font-bold text-sm shadow-sm decoration-clone">{{ $i }}</span>
                    <h4 class="text-md font-bold text-slate-800">Pregunta de Seguridad {{ $i }}</h4>
                </div>

                <!-- Question Select -->
                <div class="mb-4">
                    <label for="question_{{ $i }}" class="block text-sm font-medium text-slate-700 mb-2">
                        Selecciona una pregunta *
                    </label>
                    <div class="relative">
                        <select 
                            name="question_{{ $i }}" 
                            id="question_{{ $i }}"
                            class="block w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all appearance-none bg-whiteQuestion question-select @error('question_'.$i) border-red-300 @enderror"
                            required
                            onchange="updateQuestionOptions()"
                        >
                            <option value="">-- Selecciona una pregunta --</option>
                            @foreach($preguntasCatalogo as $pregunta)
                                <option value="{{ $pregunta->id }}" {{ old('question_'.$i) == $pregunta->id ? 'selected' : '' }}>
                                    {{ $pregunta->pregunta }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-500">
                            <i class="bi bi-chevron-down text-xs"></i>
                        </div>
                    </div>
                    @error('question_'.$i)
                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                            <i class="bi bi-exclamation-circle-fill"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Answer Input -->
                <div class="relative group">
                    <label for="answer_{{ $i }}" class="block text-sm font-medium text-slate-700 mb-2">
                        Tu respuesta *
                    </label>
                    <div class="relative">
                        <input 
                            type="text" 
                            name="answer_{{ $i }}" 
                            id="answer_{{ $i }}"
                            class="block w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all pl-10 @error('answer_'.$i) border-red-300 @enderror"
                            placeholder="Escribe tu respuesta aquí..."
                            value="{{ old('answer_'.$i) }}"
                            required
                            minlength="3"
                            autocomplete="off"
                        >
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400 group-focus-within:text-blue-500 transition-colors">
                            <i class="bi bi-pencil-fill"></i>
                        </div>
                    </div>
                    @error('answer_'.$i)
                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                            <i class="bi bi-exclamation-circle-fill"></i>
                            {{ $message }}
                        </p>
                    @enderror
                    <div class="mt-2 flex items-center justify-between">
                         <p class="text-xs text-slate-500">
                            <i class="bi bi-info-circle"></i> Mínimo 3 caracteres.
                        </p>
                        <span class="text-xs font-medium px-2 py-0.5 rounded bg-slate-200 text-slate-600 hidden check-indicator">
                            <i class="bi bi-check2"></i> Válido
                        </span>
                    </div>
                </div>
            </div>
            @endfor
            </div>

            <!-- Validation Warning (Global) -->
            <div id="validationWarning" class="hidden mt-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg animate-fade-in-down">
                <div class="flex items-start">
                    <i class="bi bi-exclamation-triangle-fill text-red-500 mt-0.5 text-xl"></i>
                    <div class="ml-3">
                        <h3 class="text-sm font-semibold text-red-800">Atención</h3>
                        <p class="text-sm text-red-700 mt-1" id="validationMessage"></p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 pt-8 mt-4 border-t border-slate-100">
                <button 
                    type="submit"
                    id="submitBtn"
                    class="flex-1 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white font-bold py-3.5 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2 group disabled:opacity-70 disabled:cursor-not-allowed disabled:transform-none"
                >
                    <span class="btn-text">Guardar Preguntas de Seguridad</span>
                    <i class="bi bi-shield-check group-hover:scale-110 transition-transform btn-icon"></i>
                    <div class="btn-spinner hidden w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                </button>
                <a 
                    href="{{ auth()->user()->rol_id == 1 ? route('admin.perfil.edit') : (auth()->user()->rol_id == 2 ? route('medico.perfil.edit') : route('paciente.perfil.edit')) }}"
                    class="sm:w-auto w-full px-6 py-3.5 border-2 border-slate-300 text-slate-700 font-semibold rounded-xl hover:bg-slate-50 hover:text-slate-900 transition-all duration-200 flex items-center justify-center gap-2"
                >
                    <i class="bi bi-x-lg"></i>
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Toggle Password Visibility
    function togglePasswordVisibility(inputId, button) {
        const input = document.getElementById(inputId);
        const icon = button.querySelector('i');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye-slash-fill');
            icon.classList.add('bi-eye-fill', 'text-blue-600');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye-fill', 'text-blue-600');
            icon.classList.add('bi-eye-slash-fill');
        }
    }

    // Dynamic Question Options Logic
    function updateQuestionOptions() {
        const selects = document.querySelectorAll('.question-select');
        const selectedValues = Array.from(selects).map(select => select.value).filter(val => val !== "");

        selects.forEach(select => {
            const currentVal = select.value;
            const options = select.querySelectorAll('option');

            options.forEach(option => {
                if (option.value === "") return; // Skip placeholder

                // Si la opción está seleccionada en OTRO select, deshabilitarla
                // Pero si es la opción actualmente seleccionada en ESTE select, mantenerla habilitada
                const isSelectedElsewhere = selectedValues.includes(option.value) && option.value !== currentVal;

                if (isSelectedElsewhere) {
                    option.disabled = true;
                    // option.classList.add('bg-slate-100', 'text-slate-400'); // Optional styling
                    // option.text = option.text + ' (Seleccionada)';
                } else {
                    option.disabled = false;
                    // option.classList.remove('bg-slate-100', 'text-slate-400');
                    // option.text = option.text.replace(' (Seleccionada)', '');
                }
            });
        });
        
        validateFormLocal();
    }

    // Live Validation for Answers
    document.querySelectorAll('input[name^="answer_"]').forEach(input => {
        input.addEventListener('input', function() {
            const indicator = this.closest('.group').querySelector('.check-indicator');
            if (this.value.trim().length >= 3) {
                this.classList.remove('border-red-300', 'focus:border-red-500');
                this.classList.add('border-green-300', 'focus:border-green-500');
                indicator.classList.remove('hidden');
                indicator.classList.add('inline-flex', 'bg-green-100', 'text-green-700');
                indicator.innerHTML = '<i class="bi bi-check-circle-fill mr-1"></i> Válido';
            } else {
                this.classList.remove('border-green-300', 'focus:border-green-500');
                // Don't add red immediately while typing, only if invalid on blur or submit
                indicator.classList.add('hidden');
            }
        });
    });

    // Form Submission
    const form = document.getElementById('securityQuestionsForm');
    const submitBtn = document.getElementById('submitBtn');
    const warningDiv = document.getElementById('validationWarning');
    const warningMsg = document.getElementById('validationMessage');

    function validateFormLocal() {
        // Just clear global warning when user interacts
        if (!warningDiv.classList.contains('hidden')) {
            warningDiv.classList.add('hidden');
        }
    }

    if (form) {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            let firstError = null;

            // 1. Password Check
            const pwd = document.getElementById('current_password');
            if (!pwd.value) {
                isValid = false;
                if(!firstError) firstError = pwd;
            }

            // 2. Answers Length Check
            document.querySelectorAll('input[name^="answer_"]').forEach(input => {
                if (input.value.trim().length < 3) {
                    isValid = false;
                    input.classList.add('border-red-500', 'animate-shake');
                    setTimeout(() => input.classList.remove('animate-shake'), 500);
                    if (!firstError) firstError = input;
                }
            });

            // 3. Unique Questions Check
            const selects = document.querySelectorAll('.question-select');
            const values = new Set();
            let duplicateFound = false;
            
            selects.forEach(select => {
                if (!select.value) {
                   isValid = false;
                   select.classList.add('border-red-500');
                   if (!firstError) firstError = select;
                } else {
                    if (values.has(select.value)) duplicateFound = true;
                    values.add(select.value);
                }
            });

            if (duplicateFound) {
                isValid = false;
                warningMsg.textContent = 'Por favor, selecciona 3 preguntas diferentes.';
                warningDiv.classList.remove('hidden');
                if (!firstError) firstError = selects[0];
            }

            if (!isValid) {
                e.preventDefault();
                if (firstError) {
                    firstError.focus();
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            } else {
                // Show Loading State
                submitBtn.disabled = true;
                submitBtn.querySelector('.btn-text').textContent = 'Guardando...';
                submitBtn.querySelector('.btn-icon').classList.add('hidden');
                submitBtn.querySelector('.btn-spinner').classList.remove('hidden');
            }
        });
    }

    // Initialize state on load (in case of validation errors redirect)
    document.addEventListener('DOMContentLoaded', () => {
        updateQuestionOptions();
    });

    @if(session('success'))
        // Optional: Call your global toast function here if available
        // showToast('success', '{{ session('success') }}');
    @endif
</script>
@endpush
@endsection
