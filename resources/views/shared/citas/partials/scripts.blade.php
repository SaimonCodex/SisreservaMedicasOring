@push('scripts')
<script>
    const BASE_URL = '{{ url("") }}';
    let medicoActual = null;
    let tarifaBase = 0;
    let tarifaExtra = 0;

    // =========================================================================
    // TIPO DE CITA
    // =========================================================================
    function selectTipoCita(tipo) {
        console.log('selectTipoCita llamado con tipo:', tipo);
        
        try {
            // Verificar elementos críticos
            const inputTipo = document.getElementById('tipo_cita');
            const stepTipo = document.getElementById('step-tipo');
            const citaForm = document.getElementById('citaForm');
            const seccionConsulta = document.getElementById('seccion-consulta');
            
            if(!inputTipo) {
                console.error('Elemento tipo_cita no encontrado');
                throw new Error("Input tipo_cita no encontrado");
            }
            if(!stepTipo) {
                console.error('Elemento step-tipo no encontrado');
                throw new Error("Elemento step-tipo no encontrado");
            }
            if(!citaForm) {
                console.error('Elemento citaForm no encontrado');
                throw new Error("Elemento citaForm no encontrado");
            }
            if(!seccionConsulta) {
                console.error('Elemento seccion-consulta no encontrado');
                throw new Error("Elemento seccion-consulta no encontrado");
            }
            
            // Establecer tipo de cita
            inputTipo.value = tipo;
            console.log('Tipo de cita establecido:', tipo);
            
            // Ocultar paso de selección y mostrar formulario
            stepTipo.classList.add('hidden');
            citaForm.classList.remove('hidden');
            console.log('Formulario mostrado');
            
            // Mostrar sección correspondiente
            if (tipo === 'propia') {
                const seccionBuscar = document.getElementById('seccion-buscar-paciente');
                const seccionTerceros = document.getElementById('seccion-terceros');
                const resumenTipo = document.getElementById('resumen-tipo');
                
                if(seccionBuscar) seccionBuscar.classList.remove('hidden');
                if(seccionTerceros) seccionTerceros.classList.add('hidden');
                if(resumenTipo) resumenTipo.textContent = 'Cita Propia';
                
                console.log('Modo: Cita Propia');
            } else if (tipo === 'terceros') {
                const seccionBuscar = document.getElementById('seccion-buscar-paciente');
                const seccionTerceros = document.getElementById('seccion-terceros');
                const resumenTipo = document.getElementById('resumen-tipo');
                const resumenRepContainer = document.getElementById('resumen-representante-container');
                
                if(seccionBuscar) seccionBuscar.classList.add('hidden');
                if(seccionTerceros) seccionTerceros.classList.remove('hidden');
                if(resumenTipo) resumenTipo.textContent = 'Cita para Terceros';
                if(resumenRepContainer) resumenRepContainer.classList.remove('hidden');
                
                console.log('Modo: Cita para Terceros');
            }
            
            // Mostrar sección de consulta
            seccionConsulta.classList.remove('hidden');
            console.log('Sección de consulta mostrada');
            
        } catch (e) {
            console.error('Error en selectTipoCita:', e);
            alert('Error al seleccionar tipo de cita: ' + e.message + '\n\nPor favor, recargue la página e intente nuevamente.');
        }
    }

    // Event listeners para los botones de tipo de cita
    document.addEventListener('DOMContentLoaded', function() {
        const botonesTipoCita = document.querySelectorAll('[data-tipo-cita]');
        botonesTipoCita.forEach(boton => {
            boton.addEventListener('click', function() {
                const tipo = this.getAttribute('data-tipo-cita');
                selectTipoCita(tipo);
            });
        });
    });

    function resetForm() {
        document.getElementById('step-tipo').classList.remove('hidden');
        document.getElementById('citaForm').classList.add('hidden');
        document.getElementById('seccion-buscar-paciente').classList.add('hidden');
        document.getElementById('seccion-terceros').classList.add('hidden');
        document.getElementById('seccion-consulta').classList.add('hidden');
        document.getElementById('citaForm').reset();
        limpiarPacienteSeleccionado();
    }

    // =========================================================================
    // BUSCADOR DE PACIENTES (Citas Propias)
    // =========================================================================
    let searchTimeout = null;
    
    document.getElementById('buscar_paciente')?.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();
        const tipoCita = document.getElementById('tipo_cita').value;
        
        if (query.length < 2) {
            document.getElementById('resultados-busqueda').classList.add('hidden');
            return;
        }
        
        searchTimeout = setTimeout(() => buscarPacientes(query, tipoCita, 'resultados-busqueda'), 300);
    });

    async function buscarPacientes(query, tipoCita, containerId) {
        try {
            const response = await fetch(`${BASE_URL}/admin/buscar-paciente?q=${encodeURIComponent(query)}&tipo_cita=${tipoCita}`);
            const data = await response.json();
            
            const container = document.getElementById(containerId);
            container.innerHTML = '';
            
            if (data.results.length === 0) {
                container.innerHTML = '<div class="p-3 text-gray-500 text-sm">No se encontraron resultados</div>';
                container.classList.remove('hidden');
                return;
            }
            
            data.results.forEach(result => {
                const div = document.createElement('div');
                div.className = 'p-3 hover:bg-gray-100 cursor-pointer border-b flex items-center gap-3';
                
                const tipoLabel = result.tipo === 'paciente' ? 'Paciente' : 
                                  result.tipo === 'especial' ? 'Paciente Especial' : 'Representante';
                const tipoColor = result.tipo === 'paciente' ? 'bg-blue-100 text-blue-700' : 
                                  result.tipo === 'especial' ? 'bg-rose-100 text-rose-700' : 'bg-purple-100 text-purple-700';
                
                div.innerHTML = `
                    <div class="flex-1">
                        <p class="font-medium text-gray-900">${result.nombre}</p>
                        <p class="text-sm text-gray-500">${result.documento}</p>
                    </div>
                    <span class="text-xs px-2 py-1 rounded-full ${tipoColor}">${tipoLabel}</span>
                `;
                
                div.onclick = () => seleccionarPaciente(result, tipoCita);
                container.appendChild(div);
            });
            
            container.classList.remove('hidden');
        } catch (error) {
            console.error('Error buscando pacientes:', error);
        }
    }

    function seleccionarPaciente(paciente, tipoCita) {
        // Verificar tipo correcto
        if (tipoCita === 'propia' && paciente.tipo === 'especial') {
            document.getElementById('alerta-tipo-incorrecto').classList.remove('hidden');
            document.getElementById('alerta-tipo-mensaje').textContent = 
                'Este paciente es especial y requiere representante. Seleccione "Cita para Terceros" y búsquelo en la sección de Paciente Especial.';
            document.getElementById('resultados-busqueda').classList.add('hidden');
            return;
        }
        
        document.getElementById('alerta-tipo-incorrecto').classList.add('hidden');
        document.getElementById('resultados-busqueda').classList.add('hidden');
        
        // Marcar como existente
        document.getElementById('paciente_existente').value = '1';
        document.getElementById('paciente_id').value = paciente.id;
        
        // Mostrar seleccionado
        document.getElementById('paciente_seleccionado').classList.remove('hidden');
        document.getElementById('pac_iniciales').textContent = 
            (paciente.primer_nombre?.[0] || '') + (paciente.primer_apellido?.[0] || '');
        document.getElementById('pac_nombre_display').textContent = paciente.nombre;
        document.getElementById('pac_documento_display').textContent = paciente.documento;
        
        // Actualizar resumen
        document.getElementById('resumen-paciente').textContent = paciente.nombre;
        
        // Ocultar buscador
        document.getElementById('pac-buscador-container').classList.add('hidden');
        
        // Mostrar tarjeta de selección
        document.getElementById('paciente_seleccionado').classList.remove('hidden');
        
        // Deshabilitar checkbox "no registrado"
        document.getElementById('paciente_no_registrado').disabled = true;
        document.getElementById('paciente_no_registrado').checked = false;
        document.getElementById('datos-paciente-nuevo').classList.add('hidden');
    }

    function limpiarPacienteSeleccionado() {
        document.getElementById('paciente_existente').value = '0';
        document.getElementById('paciente_id').value = '';
        
        // Ocultar tarjeta
        document.getElementById('paciente_seleccionado').classList.add('hidden');
        
        // Mostrar buscador nuevamente
        document.getElementById('pac-buscador-container').classList.remove('hidden');
        
        // Habilitar checkbox
        document.getElementById('paciente_no_registrado').disabled = false;
        
        // Limpiar resumen
        document.getElementById('resumen-paciente').textContent = '-';
    }

    function togglePacienteNoRegistrado() {
        const checked = document.getElementById('paciente_no_registrado').checked;
        
        if (checked) {
            document.getElementById('datos-paciente-nuevo').classList.remove('hidden');
            document.getElementById('buscar_paciente').disabled = true;
            document.getElementById('paciente_existente').value = '0';
            // Actualizar resumen al escribir datos
            actualizarResumenPacienteNuevo();
        } else {
            document.getElementById('datos-paciente-nuevo').classList.add('hidden');
            document.getElementById('buscar_paciente').disabled = false;
            document.getElementById('resumen-paciente').textContent = '-';
        }
    }

    // Función para actualizar resumen cuando se llena paciente nuevo
    function actualizarResumenPacienteNuevo() {
        const nombre = document.getElementById('pac_primer_nombre')?.value || '';
        const apellido = document.getElementById('pac_primer_apellido')?.value || '';
        const nombreCompleto = (nombre + ' ' + apellido).trim();
        document.getElementById('resumen-paciente').textContent = nombreCompleto || 'Nuevo Paciente';
    }

    // Listeners para actualizar resumen
    document.addEventListener('DOMContentLoaded', function() {
        // Paciente Propio
        ['pac_primer_nombre', 'pac_primer_apellido'].forEach(id => {
            const el = document.getElementById(id);
            if(el) {
                el.addEventListener('input', function() {
                    const chk = document.getElementById('paciente_no_registrado');
                    if (chk && chk.checked) {
                        actualizarResumenPacienteNuevo();
                    }
                });
            }
        });

        // Representante
        ['rep_primer_nombre', 'rep_primer_apellido'].forEach(id => {
            const el = document.getElementById(id);
            if(el) {
                el.addEventListener('input', function() {
                    const chk = document.getElementById('representante_no_registrado');
                    if (chk && chk.checked) {
                        actualizarResumenRepresentanteNuevo();
                    }
                });
            }
        });

        // Paciente Especial
        ['pac_esp_primer_nombre', 'pac_esp_primer_apellido'].forEach(id => {
            const el = document.getElementById(id);
            if(el) {
                el.addEventListener('input', function() {
                    actualizarResumenPacEspecialNuevo();
                });
            }
        });
    });

    // =========================================================================
    // REPRESENTANTES (Terceros)
    // =========================================================================
    document.getElementById('buscar_representante')?.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();
        
        if (query.length < 2) {
            document.getElementById('resultados-representante').classList.add('hidden');
            return;
        }
        
        searchTimeout = setTimeout(() => buscarRepresentantes(query), 300);
    });

    async function buscarRepresentantes(query) {
        try {
            const response = await fetch(`${BASE_URL}/admin/buscar-paciente?q=${encodeURIComponent(query)}&tipo_cita=terceros`);
            const data = await response.json();
            
            const container = document.getElementById('resultados-representante');
            container.innerHTML = '';
            
            // Filtrar solo pacientes y representantes (no especiales)
            const results = data.results.filter(r => r.tipo === 'paciente' || r.tipo === 'representante');
            
            if (results.length === 0) {
                container.innerHTML = '<div class="p-3 text-gray-500 text-sm">No se encontraron representantes</div>';
                container.classList.remove('hidden');
                return;
            }
            
            results.forEach(result => {
                const div = document.createElement('div');
                div.className = 'p-3 hover:bg-gray-100 cursor-pointer border-b';
                div.innerHTML = `
                    <p class="font-medium text-gray-900">${result.nombre}</p>
                    <p class="text-sm text-gray-500">${result.documento}</p>
                `;
                div.onclick = () => seleccionarRepresentante(result);
                container.appendChild(div);
            });
            
            container.classList.remove('hidden');
        } catch (error) {
            console.error('Error buscando representantes:', error);
        }
    }

    function seleccionarRepresentante(rep) {
        document.getElementById('resultados-representante').classList.add('hidden');
        
        // Si seleccionamos un PACIENTE, debemos registrarlo como representante (copiar datos)
        if (rep.tipo === 'paciente') {
            // Activar modo "Nuevo Representante"
            const chkCheck = document.getElementById('representante_no_registrado');
            chkCheck.checked = true;
            chkCheck.disabled = false;
            
            // Mostrar formulario y deshabilitar buscador
            document.getElementById('datos-representante-nuevo').classList.remove('hidden');
            document.getElementById('buscar_representante').disabled = true;
            document.getElementById('buscar_representante').value = rep.nombre;
            
            // Setear flags
            document.getElementById('representante_existente').value = '0';
            document.getElementById('representante_id_hidden').value = '';
            
            // Llenar campos
            if(document.getElementById('rep_primer_nombre')) document.getElementById('rep_primer_nombre').value = rep.primer_nombre || '';
            if(document.getElementById('rep_segundo_nombre')) document.getElementById('rep_segundo_nombre').value = rep.segundo_nombre || '';
            if(document.getElementById('rep_primer_apellido')) document.getElementById('rep_primer_apellido').value = rep.primer_apellido || '';
            if(document.getElementById('rep_segundo_apellido')) document.getElementById('rep_segundo_apellido').value = rep.segundo_apellido || '';
            
            if(document.getElementById('rep_tipo_documento')) document.getElementById('rep_tipo_documento').value = rep.tipo_documento || 'V';
            if(document.getElementById('rep_numero_documento')) document.getElementById('rep_numero_documento').value = rep.numero_documento || '';
            
            if(document.getElementById('rep_fecha_nac')) document.getElementById('rep_fecha_nac').value = rep.fecha_nac || '';
            
            // Teléfono
            if(document.getElementById('rep_prefijo_tlf')) document.getElementById('rep_prefijo_tlf').value = rep.prefijo_tlf || '+58';
            if(document.getElementById('rep_numero_tlf')) document.getElementById('rep_numero_tlf').value = rep.numero_tlf || '';
            
            // Ubicación - Intentar setear y disparar cambios para cascada
            if(document.getElementById('rep_estado_id') && rep.estado_id) {
                document.getElementById('rep_estado_id').value = rep.estado_id;
                document.getElementById('rep_estado_id').dispatchEvent(new Event('change'));
                
                // Esperar un momento a que carguen y setear municipio si existe
                setTimeout(() => {
                    if (document.getElementById('rep_municipio_id') && rep.municipio_id) {
                        document.getElementById('rep_municipio_id').value = rep.municipio_id;
                        document.getElementById('rep_municipio_id').dispatchEvent(new Event('change'));
                        
                        setTimeout(() => {
                            if (document.getElementById('rep_parroquia_id') && rep.parroquia_id) {
                                document.getElementById('rep_parroquia_id').value = rep.parroquia_id;
                            }
                        }, 500);
                    }
                    if (document.getElementById('rep_ciudad_id') && rep.ciudad_id) {
                        document.getElementById('rep_ciudad_id').value = rep.ciudad_id;
                    }
                }, 500);
            }
            if(document.getElementById('rep_direccion_detallada')) document.getElementById('rep_direccion_detallada').value = rep.direccion_detallada || '';

            // Actualizar resumen
            actualizarResumenRepresentanteNuevo();
            
        } else {
            // Es un REPRESENTANTE existente
            document.getElementById('representante_existente').value = '1';
            document.getElementById('representante_id_hidden').value = rep.id;
            
            // Llenar datos de la tarjeta
            document.getElementById('rep_iniciales_display').textContent = 
                (rep.primer_nombre?.[0] || '') + (rep.primer_apellido?.[0] || '');
            document.getElementById('rep_nombre_display').textContent = rep.nombre;
            document.getElementById('rep_documento_display').textContent = rep.documento;
            
            // Actualizar resumen
            document.getElementById('resumen-representante').textContent = rep.nombre;
            
            // Ocultar buscador
            document.getElementById('rep-buscador-container').classList.add('hidden');
            
            // Mostrar tarjeta de selección
            document.getElementById('representante_seleccionado').classList.remove('hidden');
            
            // Deshabilitar y desmarcar checkbox "no registrado"
            const chkNoRegistrado = document.getElementById('representante_no_registrado');
            chkNoRegistrado.disabled = true;
            chkNoRegistrado.checked = false;
            
            // Ocultar formulario de nuevo representante si estaba visible
            document.getElementById('datos-representante-nuevo').classList.add('hidden');
            
            // Actualizar preview de ID paciente especial
            if(typeof actualizarPacEspPreviewDocumento === 'function') {
                actualizarPacEspPreviewDocumento();
            }
            
            // Cargar pacientes especiales del representante
            cargarPacientesEspecialesDelRepresentante(rep.id);
        }
    }

    function limpiarRepresentanteSeleccionado() {
        document.getElementById('representante_existente').value = '0';
        document.getElementById('representante_id_hidden').value = '';
        
        // Ocultar tarjeta
        document.getElementById('representante_seleccionado').classList.add('hidden');
        
        // Mostrar buscador nuevamente
        document.getElementById('rep-buscador-container').classList.remove('hidden');
        
        // Habilitar checkbox
        document.getElementById('representante_no_registrado').disabled = false;
        
        // Limpiar resumen
        document.getElementById('resumen-representante').textContent = '-';
        
        // Limpiar preview de ID paciente especial
        const preview = document.getElementById('pac-esp-documento-preview');
        if(preview) preview.textContent = '-';
        
        // Ocultar y limpiar select de pacientes especiales del representante
        const seccionSelectPacEsp = document.getElementById('seccion-select-pac-esp-representante');
        if (seccionSelectPacEsp) {
            seccionSelectPacEsp.classList.add('hidden');
            document.getElementById('select_pac_esp_representante').innerHTML = 
                '<option value="">Seleccionar paciente...</option>';
        }
        
        // Mostrar mensaje informativo nuevamente
        const mensajeInfo = document.getElementById('mensaje-seleccionar-representante');
        if (mensajeInfo) {
            mensajeInfo.classList.remove('hidden');
        }
    }
    
    // Cargar pacientes especiales del representante seleccionado
    async function cargarPacientesEspecialesDelRepresentante(representanteId) {
        const seccionSelect = document.getElementById('seccion-select-pac-esp-representante');
        const select = document.getElementById('select_pac_esp_representante');
        const mensajeInfo = document.getElementById('mensaje-seleccionar-representante');
        
        if (!seccionSelect || !select) return;
        
        try {
            const response = await fetch(`${BASE_URL}/ajax/citas/pacientes-especiales-por-representante/${representanteId}`);
            const pacientes = await response.json();
            
            // Ocultar mensaje informativo cuando hay representante
            if (mensajeInfo) {
                mensajeInfo.classList.add('hidden');
            }
            
            if (pacientes.length > 0) {
                // Limpiar y llenar el select
                select.innerHTML = '<option value="">Seleccionar paciente...</option>';
                pacientes.forEach(p => {
                    select.innerHTML += `<option value="${p.id}" data-nombre="${p.nombre_completo}" data-documento="${p.tipo_documento}-${p.numero_documento}" data-tipo="${p.tipo}">${p.nombre_completo} (${p.tipo})</option>`;
                });
                
                // Mostrar la sección del select
                seccionSelect.classList.remove('hidden');
            } else {
                // Ocultar select si no hay pacientes (el checkbox de "no registrado" sigue disponible)
                seccionSelect.classList.add('hidden');
            }
        } catch (error) {
            console.error('Error cargando pacientes especiales:', error);
            seccionSelect.classList.add('hidden');
        }
    }
    
    // Seleccionar paciente especial del dropdown del representante
    function seleccionarPacEspDeRepresentante(id) {
        if (!id) {
            // Nada seleccionado, limpiar si había algo
            limpiarPacEspecialSeleccionado();
            return;
        }
        
        const select = document.getElementById('select_pac_esp_representante');
        const selectedOption = select.options[select.selectedIndex];
        
        // Crear un objeto con los datos del paciente
        const paciente = {
            id: id,
            nombre: selectedOption.dataset.nombre,
            documento: selectedOption.dataset.documento,
            primer_nombre: selectedOption.dataset.nombre.split(' ')[0],
            primer_apellido: selectedOption.dataset.nombre.split(' ').pop()
        };
        
        // Usar la función existente de selección
        seleccionarPacienteEspecial(paciente);
    }

    function toggleRepresentanteNoRegistrado() {
        const checked = document.getElementById('representante_no_registrado').checked;
        document.getElementById('datos-representante-nuevo').classList.toggle('hidden', !checked);
        document.getElementById('buscar_representante').disabled = checked;
        if (checked) {
            actualizarResumenRepresentanteNuevo();
        } else {
            document.getElementById('resumen-representante').textContent = '-';
        }
    }

    // Función para actualizar resumen cuando se llena representante nuevo
    function actualizarResumenRepresentanteNuevo() {
        const nombre = document.getElementById('rep_primer_nombre')?.value || '';
        const apellido = document.getElementById('rep_primer_apellido')?.value || '';
        const nombreCompleto = (nombre + ' ' + apellido).trim();
        
        const elResumen = document.getElementById('resumen-representante');
        if (elResumen) {
            elResumen.textContent = nombreCompleto || 'Nuevo Representante';
        }
    }

    // Función para actualizar resumen cuando se llena paciente especial nuevo  
    function actualizarResumenPacEspecialNuevo() {
        const nombre = document.getElementById('pac_esp_primer_nombre')?.value || '';
        const apellido = document.getElementById('pac_esp_primer_apellido')?.value || '';
        const nombreCompleto = (nombre + ' ' + apellido).trim();
        const elTipo = document.querySelector('input[name="pac_esp_tipo"]:checked');
        const tipo = elTipo ? elTipo.value : '';
        
        const elResumen = document.getElementById('resumen-paciente');
        if (elResumen) {
             elResumen.textContent = nombreCompleto || 'Nuevo Paciente Especial';
        }

        const elResumenTipo = document.getElementById('resumen-tipo-paciente');
        if (tipo && elResumenTipo) {
            elResumenTipo.textContent = tipo;
        }
    }



    // =========================================================================
    // BUSCADOR PACIENTE ESPECIAL (Terceros) - Busca en pacientes_especiales
    // =========================================================================
    document.getElementById('buscar_pac_especial')?.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();
        
        if (query.length < 2) {
            document.getElementById('resultados-pac-especial').classList.add('hidden');
            return;
        }
        
        searchTimeout = setTimeout(() => buscarPacientesEspeciales(query), 300);
    });

    async function buscarPacientesEspeciales(query) {
        try {
            // Este endpoint devuelve solo pacientes especiales
            const response = await fetch(`${BASE_URL}/admin/buscar-paciente?q=${encodeURIComponent(query)}&tipo_cita=terceros`);
            const data = await response.json();
            
            const container = document.getElementById('resultados-pac-especial');
            container.innerHTML = '';
            
            // Filtrar SOLO pacientes especiales (tipo = 'especial')
            const results = data.results.filter(r => r.tipo === 'especial');
            
            if (results.length === 0) {
                container.innerHTML = '<div class="p-3 text-gray-500 text-sm">No se encontraron pacientes especiales</div>';
                container.classList.remove('hidden');
                return;
            }
            
            results.forEach(result => {
                const div = document.createElement('div');
                div.className = 'p-3 hover:bg-gray-100 cursor-pointer border-b';
                div.innerHTML = `
                    <p class="font-medium text-gray-900">${result.nombre}</p>
                    <p class="text-sm text-gray-500">${result.documento}</p>
                    <span class="text-xs text-rose-600">Paciente Especial</span>
                `;
                div.onclick = () => seleccionarPacienteEspecial(result);
                container.appendChild(div);
            });
            
            container.classList.remove('hidden');
        } catch (error) {
            console.error('Error buscando pacientes especiales:', error);
        }
    }

    function seleccionarPacienteEspecial(pac) {
        document.getElementById('resultados-pac-especial').classList.add('hidden');
        document.getElementById('paciente_especial_id').value = pac.id;
        
        document.getElementById('pac_especial_seleccionado').classList.remove('hidden');
        document.getElementById('pac_esp_iniciales').textContent = 
            (pac.primer_nombre?.[0] || '') + (pac.primer_apellido?.[0] || '');
        document.getElementById('pac_esp_nombre_display').textContent = pac.nombre;
        document.getElementById('pac_esp_documento_display').textContent = pac.documento;
        
        document.getElementById('resumen-paciente').textContent = pac.nombre;
        document.getElementById('buscar_pac_especial').value = '';
        document.getElementById('buscar_pac_especial').disabled = true;
        
        // Deshabilitar checkbox
        document.getElementById('pac_especial_no_registrado').disabled = true;
        document.getElementById('pac_especial_no_registrado').checked = false;
        document.getElementById('datos-paciente-especial-nuevo').classList.add('hidden');
    }

    function limpiarPacEspecialSeleccionado() {
        document.getElementById('paciente_especial_id').value = '';
        document.getElementById('pac_especial_seleccionado').classList.add('hidden');
        document.getElementById('buscar_pac_especial').disabled = false;
        document.getElementById('pac_especial_no_registrado').disabled = false;
        document.getElementById('resumen-paciente').textContent = '-';
    }

    function togglePacEspecialNoRegistrado() {
        const checked = document.getElementById('pac_especial_no_registrado').checked;
        document.getElementById('datos-paciente-especial-nuevo').classList.toggle('hidden', !checked);
        document.getElementById('buscar_pac_especial').disabled = checked;
    }

    // =========================================================================
    // REGISTRO DE USUARIOS - Con validación de correo único
    // =========================================================================
    function toggleRegistrarUsuario() {
        const checked = document.getElementById('chk_registrar_usuario').checked;
        document.getElementById('campos_registro_usuario').classList.toggle('hidden', !checked);
        document.getElementById('registrar_usuario').value = checked ? '1' : '0';
        
        if (checked) {
            generarContrasena('pac');
        }
    }

    function toggleRegistrarRepresentante() {
        const checked = document.getElementById('chk_registrar_representante').checked;
        document.getElementById('campos_registro_representante').classList.toggle('hidden', !checked);
        
        if (checked) {
            generarContrasena('rep');
        }
    }

    function generarContrasena(prefix) {
        const documento = document.getElementById(`${prefix}_numero_documento`)?.value.trim() || '';
        const nombre = document.getElementById(`${prefix}_primer_nombre`)?.value.trim() || '';
        const fechaNac = document.getElementById(`${prefix}_fecha_nac`)?.value || '';
        
        if (!documento || !nombre) {
            return; // Silencioso, se generará cuando se llenen los campos
        }
        
        const nombreCap = nombre.charAt(0).toUpperCase() + nombre.slice(1).toLowerCase();
        
        // Obtener año directamente del string para evitar problemas de timezone
        let año = new Date().getFullYear();
        if (fechaNac) {
            const parts = fechaNac.split('-');
            if (parts.length > 0) {
                año = parts[0];
            }
        }
        
        const password = `#${documento}${nombreCap}${año}`;
        
        // Asignar a ambos campos (visual y hidden)
        const displayEl = document.getElementById(`${prefix}_password_display`);
        const hiddenEl = document.getElementById(`${prefix}_password`);
        
        if (displayEl) displayEl.value = password;
        if (hiddenEl) hiddenEl.value = password;
        
        console.log(`Contraseña generada para ${prefix}:`, password);
    }

    // Auto-generar contraseña al cambiar campos relevantes
    ['pac_numero_documento', 'pac_primer_nombre', 'pac_fecha_nac'].forEach(id => {
        document.getElementById(id)?.addEventListener('input', () => {
            if (document.getElementById('chk_registrar_usuario')?.checked) {
                generarContrasena('pac');
            }
        });
    });

    ['rep_numero_documento', 'rep_primer_nombre'].forEach(id => {
        document.getElementById(id)?.addEventListener('input', () => {
            if (document.getElementById('chk_registrar_representante')?.checked) {
                generarContrasena('rep');
            }
        });
    });

    // Validar correo único
    async function validarCorreo(inputId) {
        const input = document.getElementById(inputId);
        if(!input) return false;
        
        const correo = input.value.trim();
        const errorSpan = input.nextElementSibling;
        
        if (!correo) {
             input.classList.remove('border-red-500');
             errorSpan.classList.add('hidden');
             return true;
        }
        
        // Validación formato básico
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(correo)) {
             return false; 
        }

        try {
            const response = await fetch(`${BASE_URL}/ajax/verificar-correo?correo=${encodeURIComponent(correo)}`);
            const data = await response.json();
            
            if (data.existe) {
                input.classList.add('border-red-500');
                errorSpan.textContent = 'Este correo ya está registrado en el sistema. Debe usar otro.';
                errorSpan.classList.remove('hidden');
                input.dataset.invalid = "true"; // Flag for submit
                return false;
            } else {
                input.classList.remove('border-red-500');
                errorSpan.classList.add('hidden');
                delete input.dataset.invalid;
                return true;
            }
        } catch (error) {
            console.error('Error verificando correo:', error);
            return false;
        }
    }

    let emailTimeout = null;
    ['pac_correo', 'rep_correo'].forEach(id => {
        // Input con debounce para UX
        document.getElementById(id)?.addEventListener('input', function() {
            clearTimeout(emailTimeout);
            emailTimeout = setTimeout(() => validarCorreo(id), 500);
        });
        // Blur para validación firme
        document.getElementById(id)?.addEventListener('blur', function() {
             validarCorreo(id);
        });
    });

    // Helper para encontrar el span de error
    function findErrorSpan(inputElement) {
        if (!inputElement) return null;
        let errorSpan = document.getElementById(inputElement.id + '_error');
        if (!errorSpan) {
             errorSpan = inputElement.nextElementSibling;
             if (!errorSpan || !errorSpan.classList.contains('error-message')) {
                 errorSpan = inputElement.parentElement.querySelector('.error-message');
             }
             if (!errorSpan && inputElement.parentElement) {
                  errorSpan = inputElement.parentElement.nextElementSibling;
                  if(errorSpan && !errorSpan.classList.contains('error-message')) {
                      let abuelo = inputElement.parentElement.parentElement;
                      if(abuelo) errorSpan = abuelo.querySelector('.error-message');
                  }
             }
        }
        return errorSpan;
    }

    // Helper para limpiar error visualmente al escribir
    function limpiarErrorVisual(inputId) {
        const input = document.getElementById(inputId);
        if(!input) return;
        
        input.classList.remove('border-red-500');
        const errorSpan = findErrorSpan(input);
        if(errorSpan) errorSpan.classList.add('hidden');
        
        delete input.dataset.documentoInvalido;
        delete input.dataset.invalid; // For email
        
        // Actualizar banner
        if (inputId.includes('correo')) actualizarBannerErrores('email');
        else actualizarBannerErrores('id');
    }

    // =========================================================================
    // VALIDACIÓN DE DOCUMENTO (tipo + numero) EN TIEMPO REAL
    // =========================================================================
    async function validarDocumento(tipoInputId, numeroInputId) {
        const tipoInput = document.getElementById(tipoInputId);
        const numeroInput = document.getElementById(numeroInputId);
        if(!tipoInput || !numeroInput) return true;
        
        const tipoDocumento = tipoInput.value.trim();
        const numeroDocumento = numeroInput.value.trim();
        
        const errorSpan = findErrorSpan(numeroInput);
        
        if (!numeroDocumento || numeroDocumento.length < 6) {
            // Limpiar error si el campo está vacío o muy corto
            numeroInput.classList.remove('border-red-500');
            if (errorSpan) errorSpan.classList.add('hidden');
            delete numeroInput.dataset.documentoInvalido;
            return true;
        }

        try {
            const response = await fetch(`${BASE_URL}/ajax/citas/verificar-documento?tipo_documento=${encodeURIComponent(tipoDocumento)}&numero_documento=${encodeURIComponent(numeroDocumento)}`);
            const data = await response.json();
            
            if (data.existe) {
                numeroInput.classList.add('border-red-500');
                if (errorSpan) {
                    errorSpan.textContent = 'La identificación ya se encuentra registrada en el sistema.';
                    errorSpan.classList.remove('hidden');
                }
                numeroInput.dataset.documentoInvalido = "true";
                return false;
            } else {
                numeroInput.classList.remove('border-red-500');
                if (errorSpan) errorSpan.classList.add('hidden');
                delete numeroInput.dataset.documentoInvalido;
                return true;
            }
        } catch (error) {
            console.error('Error verificando documento:', error);
            return true; // En caso de error, no bloquear
        }
    }

    // Listeners para validación de documento en tiempo real (Paciente Nuevo)
    let docTimeoutPac = null;
    document.getElementById('pac_numero_documento')?.addEventListener('input', function() {
        limpiarErrorVisual('pac_numero_documento');
        clearTimeout(docTimeoutPac);
        docTimeoutPac = setTimeout(() => validarDocumento('pac_tipo_documento', 'pac_numero_documento'), 500);
    });
    document.getElementById('pac_numero_documento')?.addEventListener('blur', function() {
        validarDocumento('pac_tipo_documento', 'pac_numero_documento');
    });
    document.getElementById('pac_tipo_documento')?.addEventListener('change', function() {
        limpiarErrorVisual('pac_numero_documento'); // Limpiar al cambiar tipo
        const numDoc = document.getElementById('pac_numero_documento')?.value;
        if (numDoc && numDoc.length >= 6) {
            validarDocumento('pac_tipo_documento', 'pac_numero_documento');
        }
    });

    // Listeners para validación de documento en tiempo real (Representante Nuevo)
    let docTimeoutRep = null;
    document.getElementById('rep_numero_documento')?.addEventListener('input', function() {
        limpiarErrorVisual('rep_numero_documento');
        clearTimeout(docTimeoutRep);
        docTimeoutRep = setTimeout(() => validarDocumento('rep_tipo_documento', 'rep_numero_documento'), 500);
    });
    document.getElementById('rep_numero_documento')?.addEventListener('blur', function() {
        validarDocumento('rep_tipo_documento', 'rep_numero_documento');
    });
    document.getElementById('rep_tipo_documento')?.addEventListener('change', function() {
        limpiarErrorVisual('rep_numero_documento');
        const numDoc = document.getElementById('rep_numero_documento')?.value;
        if (numDoc && numDoc.length >= 6) {
            validarDocumento('rep_tipo_documento', 'rep_numero_documento');
        }
    });

    // Listeners para validación de documento en tiempo real (Paciente Especial Nuevo)
    let docTimeoutPacEsp = null;
    document.getElementById('pac_esp_numero_documento')?.addEventListener('input', function() {
        limpiarErrorVisual('pac_esp_numero_documento');
        clearTimeout(docTimeoutPacEsp);
        docTimeoutPacEsp = setTimeout(() => validarDocumento('pac_esp_tipo_documento', 'pac_esp_numero_documento'), 500);
    });
    document.getElementById('pac_esp_numero_documento')?.addEventListener('blur', function() {
        validarDocumento('pac_esp_tipo_documento', 'pac_esp_numero_documento');
    });
    document.getElementById('pac_esp_tipo_documento')?.addEventListener('change', function() {
        limpiarErrorVisual('pac_esp_numero_documento');
        const numDoc = document.getElementById('pac_esp_numero_documento')?.value;
        if (numDoc && numDoc.length >= 6) {
            validarDocumento('pac_esp_tipo_documento', 'pac_esp_numero_documento');
        }
    });

    // Validación de Edad (18+)
    function validarMayorEdad(inputId) {
        const input = document.getElementById(inputId);
        if (!input || !input.value) return;

        const fechaNac = new Date(input.value);
        const hoy = new Date();
        let edad = hoy.getFullYear() - fechaNac.getFullYear();
        const m = hoy.getMonth() - fechaNac.getMonth();
        if (m < 0 || (m === 0 && hoy.getDate() < fechaNac.getDate())) {
            edad--;
        }

        const errorSpan = input.nextElementSibling;
        
        if (edad < 18) {
            errorSpan.textContent = 'Debe ser mayor de edad (+18) para este registro.';
            errorSpan.classList.remove('hidden');
            input.classList.add('border-red-500');
            input.value = ''; // Limpiar fecha inválida
            return false;
        } else {
            errorSpan.classList.add('hidden');
            input.classList.remove('border-red-500');
            return true;
        }
    }

    // Listeners para Fecha Nacimiento (Propia y Representante)
    ['pac_fecha_nac', 'rep_fecha_nac'].forEach(id => {
        document.getElementById(id)?.addEventListener('change', () => validarMayorEdad(id));
    });

    function copiarContrasena(inputId) {
        const input = document.getElementById(inputId);
        input.select();
        document.execCommand('copy');
        
        // Feedback visual
        const btn = input.nextElementSibling;
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-check-lg text-green-600"></i>';
        setTimeout(() => btn.innerHTML = originalHTML, 2000);
    }

    // =========================================================================
    // CARGA DE CONSULTORIOS/ESPECIALIDADES/MÉDICOS
    // =========================================================================
    // =========================================================================
    // CARGA DE CONSULTORIOS/ESPECIALIDADES/MÉDICOS
    // =========================================================================
    
    // Filtrar consultorios por estado (Cliente)
    document.getElementById('estado_id')?.addEventListener('change', function() {
        const estadoId = this.value;
        const consultorioSelect = document.getElementById('consultorio_id');
        const especialidadSelect = document.getElementById('especialidad_id');
        const medicoSelect = document.getElementById('medico_id');
        
        // Reset dependientes (sin borrar opciones)
        consultorioSelect.value = '';
        consultorioSelect.disabled = !estadoId;
        
        // Limpiar selects subordinados
        especialidadSelect.innerHTML = '<option value="">Seleccione consultorio primero...</option>';
        especialidadSelect.disabled = true;
        
        medicoSelect.innerHTML = '<option value="">Seleccione especialidad primero...</option>';
        medicoSelect.disabled = true;
        
        // Reset info medico
        document.getElementById('info-medico').classList.add('hidden');
        document.getElementById('fecha_cita').disabled = true;
        
        if (!estadoId) return;

        // Filtrar opciones
        let encontrados = 0;
        Array.from(consultorioSelect.options).forEach(option => {
            if (option.value === "") {
                option.text = "Seleccione consultorio..."; 
                return; 
            }
            
            const estadoOption = option.getAttribute('data-estado');
            if (estadoOption == estadoId) {
                // Remove style attribute to show (fixes visibility bug)
                option.removeAttribute('style');
                option.classList.remove('hidden'); 
                encontrados++;
            } else {
                // Apply inline style to force hide
                option.style.display = 'none';
                option.classList.add('hidden');
            }
        });
        
        if (encontrados === 0) {
            consultorioSelect.options[0].text = "No hay consultorios en este estado";
            consultorioSelect.disabled = true;
        } else {
             consultorioSelect.options[0].text = "Seleccione consultorio...";
             consultorioSelect.disabled = false;
        }
    });

    // Función cargarConsultorios eliminada - reemplazada por lógica cliente arriba

    async function cargarEspecialidades() {
        const consultorioId = document.getElementById('consultorio_id').value;
        const select = document.getElementById('especialidad_id');
        
        // Actualizar resumen
        const consultorioText = document.getElementById('consultorio_id').selectedOptions[0]?.text || '-';
        document.getElementById('resumen-consultorio').textContent = consultorioText;
        
        // Reset médico y fecha
        document.getElementById('medico_id').innerHTML = '<option value="">Seleccione especialidad primero...</option>';
        document.getElementById('medico_id').disabled = true;
        document.getElementById('fecha_cita').value = '';
        document.getElementById('fecha_cita').disabled = true;
        
        if (!consultorioId) {
            select.innerHTML = '<option value="">Seleccione consultorio primero...</option>';
            select.disabled = true;
            return;
        }
        
        select.innerHTML = '<option value="">Cargando...</option>';
        
        try {
            const response = await fetch(`${BASE_URL}/ajax/citas/especialidades-por-consultorio/${consultorioId}`);
            const especialidades = await response.json();
            
            select.innerHTML = '<option value="">Seleccione especialidad...</option>';
            especialidades.forEach(e => {
                select.innerHTML += `<option value="${e.id}">${e.nombre}</option>`;
            });
            select.disabled = false;
        } catch (error) {
            console.error('Error cargando especialidades:', error);
            select.innerHTML = '<option value="">Error al cargar</option>';
        }
    }

    async function cargarMedicos() {
        const consultorioId = document.getElementById('consultorio_id').value;
        const especialidadId = document.getElementById('especialidad_id').value;
        const select = document.getElementById('medico_id');
        
        // Actualizar resumen
        const especialidadText = document.getElementById('especialidad_id').selectedOptions[0]?.text || '-';
        document.getElementById('resumen-especialidad').textContent = especialidadText;
        
        // Reset fecha
        document.getElementById('fecha_cita').value = '';
        document.getElementById('fecha_cita').disabled = true;
        
        if (!consultorioId || !especialidadId) {
            select.innerHTML = '<option value="">Seleccione especialidad primero...</option>';
            select.disabled = true;
            return;
        }
        
        select.innerHTML = '<option value="">Cargando...</option>';
        
        try {
            const response = await fetch(`${BASE_URL}/ajax/citas/medicos?consultorio_id=${consultorioId}&especialidad_id=${especialidadId}`);
            const medicos = await response.json();
            
            select.innerHTML = '<option value="">Seleccione médico...</option>';
            medicos.forEach(m => {
                const tarifa = parseFloat(m.tarifa || 0).toFixed(2);
                const nombreCompleto = `Dr. ${m.primer_nombre} ${m.primer_apellido}`;
                select.innerHTML += `<option value="${m.id}" data-nombre="${nombreCompleto}" data-tarifa="${m.tarifa}" data-domicilio="${m.atiende_domicilio ? '1' : '0'}" data-extra="${m.tarifa_extra_domicilio || 0}">${nombreCompleto} - $${tarifa}</option>`;
            });
            select.disabled = false;
            
            if (medicos.length === 0) {
                select.innerHTML = '<option value="">No hay médicos disponibles</option>';
            }
        } catch (error) {
            console.error('Error cargando médicos:', error);
            select.innerHTML = '<option value="">Error al cargar</option>';
        }
    }

    function actualizarInfoMedico() {
        const select = document.getElementById('medico_id');
        const option = select.selectedOptions[0];
        
        if (!option || !option.value) {
            document.getElementById('info-medico').classList.add('hidden');
            document.getElementById('fecha_cita').disabled = true;
            return;
        }
        
        const nombre = option.dataset.nombre || option.text.split(' - $')[0]; 
        tarifaBase = parseFloat(option.dataset.tarifa) || 0;
        tarifaExtra = parseFloat(option.dataset.extra) || 0;
        const atiendeDomicilio = option.dataset.domicilio === '1';
        
        document.getElementById('info-medico').classList.remove('hidden');
        document.getElementById('medico-nombre').textContent = nombre;
        document.getElementById('medico-tarifa').textContent = `$${tarifaBase.toFixed(2)}`;
        
        document.getElementById('resumen-medico').textContent = nombre;
        actualizarTarifaTotal();
        
        // Habilitar fecha
        document.getElementById('fecha_cita').disabled = false;
        
        // Mostrar opción domicilio si aplica
        if (atiendeDomicilio) {
            document.getElementById('opcion-domicilio').classList.remove('hidden');
        } else {
            document.getElementById('opcion-domicilio').classList.add('hidden');
        }
    }

    async function cargarHorarios() {
        const medicoId = document.getElementById('medico_id').value;
        const fecha = document.getElementById('fecha_cita').value;
        const consultorioId = document.getElementById('consultorio_id').value;
        const container = document.getElementById('horarios-container');
        
        if (!medicoId || !fecha) {
            container.innerHTML = '<p class="col-span-4 text-center text-gray-500 text-sm py-4">Seleccione médico y fecha</p>';
            return;
        }
        
        try {
            const response = await fetch(`${BASE_URL}/ajax/citas/horarios-disponibles?medico_id=${medicoId}&fecha=${fecha}&consultorio_id=${consultorioId}`);
            const data = await response.json();
            
            container.innerHTML = '';
            
            if (!data.disponible || !data.horarios || data.horarios.length === 0) {
                container.innerHTML = '<p class="col-span-4 text-center text-gray-500 text-sm py-4">No hay horarios disponibles</p>';
                return;
            }
            
            data.horarios.forEach(h => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = h.ocupada 
                    ? 'p-2 text-sm rounded border bg-red-50 text-red-500 border-red-200 cursor-not-allowed opacity-75 line-through'
                    : 'p-2 text-sm rounded border hover:border-blue-500 hover:bg-blue-50 transition-colors hora-btn';
                btn.textContent = h.hora;
                btn.disabled = h.ocupada;
                
                if (!h.ocupada) {
                    btn.onclick = () => seleccionarHora(h.hora, btn);
                }
                
                container.appendChild(btn);
            });
        } catch (error) {
            console.error('Error cargando horarios:', error);
            container.innerHTML = '<p class="col-span-4 text-center text-red-500 text-sm py-4">Error cargando horarios</p>';
        }
    }

    function seleccionarHora(hora, btn) {
        document.querySelectorAll('.hora-btn').forEach(b => b.classList.remove('border-blue-500', 'bg-blue-100'));
        btn.classList.add('border-blue-500', 'bg-blue-100');
        document.getElementById('hora_inicio').value = hora;
        
        const fecha = document.getElementById('fecha_cita').value;
        document.getElementById('resumen-fecha').textContent = `${fecha} a las ${hora}`;
    }

    function actualizarTarifaTotal() {
        const tipoDomicilio = document.querySelector('input[name="tipo_consulta"]:checked')?.value === 'Domicilio';
        const total = tarifaBase + (tipoDomicilio ? tarifaExtra : 0);
        
        document.getElementById('resumen-tarifa').textContent = `$${total.toFixed(2)}`;
        
        if (tipoDomicilio && tarifaExtra > 0) {
            document.getElementById('resumen-tarifa-detalle').textContent = `(Base: $${tarifaBase.toFixed(2)} + Extra: $${tarifaExtra.toFixed(2)})`;
            document.getElementById('aviso-domicilio').classList.remove('hidden');
            document.getElementById('tarifa-extra-valor').textContent = `$${tarifaExtra.toFixed(2)}`;
        } else {
            document.getElementById('resumen-tarifa-detalle').textContent = '';
            document.getElementById('aviso-domicilio').classList.add('hidden');
        }
    }

    // Listener para tipo consulta
    document.querySelectorAll('input[name="tipo_consulta"]').forEach(radio => {
        radio.addEventListener('change', actualizarTarifaTotal);
    });

    // =========================================================================
    // CARGA DE UBICACIÓN (Para paciente nuevo)
    // =========================================================================
    async function cargarCiudadesPac() {
        const estadoId = document.getElementById('pac_estado_id').value;
        const select = document.getElementById('pac_ciudad_id');
        
        if (!estadoId) { select.disabled = true; return; }
        
        try {
            const response = await fetch(`${BASE_URL}/ubicacion/get-ciudades/${estadoId}`);
            const data = await response.json();
            
            select.innerHTML = '<option value="">Seleccione...</option>';
            data.forEach(c => select.innerHTML += `<option value="${c.id_ciudad}">${c.ciudad}</option>`);
            select.disabled = false;
        } catch (error) {
            console.error('Error cargando ciudades:', error);
        }
    }

    async function cargarMunicipiosPac() {
        const estadoId = document.getElementById('pac_estado_id').value;
        const select = document.getElementById('pac_municipio_id');
        
        if (!estadoId) { select.disabled = true; return; }
        
        try {
            const response = await fetch(`${BASE_URL}/ubicacion/get-municipios/${estadoId}`);
            const data = await response.json();
            
            select.innerHTML = '<option value="">Seleccione...</option>';
            data.forEach(m => select.innerHTML += `<option value="${m.id_municipio}">${m.municipio}</option>`);
            select.disabled = false;
        } catch (error) {
            console.error('Error cargando municipios:', error);
        }
    }

    async function cargarParroquiasPac() {
        const municipioId = document.getElementById('pac_municipio_id').value;
        const select = document.getElementById('pac_parroquia_id');
        
        if (!municipioId) { select.disabled = true; return; }
        
        try {
            const response = await fetch(`${BASE_URL}/ubicacion/get-parroquias/${municipioId}`);
            const data = await response.json();
            
            select.innerHTML = '<option value="">Seleccione...</option>';
            data.forEach(p => select.innerHTML += `<option value="${p.id_parroquia}">${p.parroquia}</option>`);
            select.disabled = false;
        } catch (error) {
            console.error('Error cargando parroquias:', error);
        }
    }

    // =========================================================================
    // CARGA DE UBICACIÓN (Para representante nuevo)
    // =========================================================================
    async function cargarCiudadesRep() {
        const estadoId = document.getElementById('rep_estado_id').value;
        const select = document.getElementById('rep_ciudad_id');
        
        if (!estadoId) { select.disabled = true; return; }
        
        try {
            const response = await fetch(`${BASE_URL}/ubicacion/get-ciudades/${estadoId}`);
            const data = await response.json();
            
            select.innerHTML = '<option value="">Seleccione...</option>';
            data.forEach(c => select.innerHTML += `<option value="${c.id_ciudad}">${c.ciudad}</option>`);
            select.disabled = false;
        } catch (error) {
            console.error('Error cargando ciudades representante:', error);
        }
    }

    async function cargarMunicipiosRep() {
        const estadoId = document.getElementById('rep_estado_id').value;
        const select = document.getElementById('rep_municipio_id');
        
        if (!estadoId) { select.disabled = true; return; }
        
        try {
            const response = await fetch(`${BASE_URL}/ubicacion/get-municipios/${estadoId}`);
            const data = await response.json();
            
            select.innerHTML = '<option value="">Seleccione...</option>';
            data.forEach(m => select.innerHTML += `<option value="${m.id_municipio}">${m.municipio}</option>`);
            select.disabled = false;
        } catch (error) {
            console.error('Error cargando municipios representante:', error);
        }
    }

    async function cargarParroquiasRep() {
        const municipioId = document.getElementById('rep_municipio_id').value;
        const select = document.getElementById('rep_parroquia_id');
        
        if (!municipioId) { select.disabled = true; return; }
        
        try {
            const response = await fetch(`${BASE_URL}/ubicacion/get-parroquias/${municipioId}`);
            const data = await response.json();
            
            select.innerHTML = '<option value="">Seleccione...</option>';
            data.forEach(p => select.innerHTML += `<option value="${p.id_parroquia}">${p.parroquia}</option>`);
            select.disabled = false;
        } catch (error) {
            console.error('Error cargando parroquias representante:', error);
        }
    }

    // =========================================================================
    // CARGA DE UBICACIÓN (Para Paciente Especial nuevo)
    // =========================================================================
    async function cargarCiudadesPacEsp() {
        const estadoId = document.getElementById('pac_esp_estado_id').value;
        const select = document.getElementById('pac_esp_ciudad_id');
        
        if (!estadoId) { select.disabled = true; return; }
        
        try {
            const response = await fetch(`${BASE_URL}/ubicacion/get-ciudades/${estadoId}`);
            const data = await response.json();
            
            select.innerHTML = '<option value="">Seleccione...</option>';
            data.forEach(c => select.innerHTML += `<option value="${c.id_ciudad}">${c.ciudad}</option>`);
            select.disabled = false;
        } catch (error) {
            console.error('Error cargando ciudades paciente especial:', error);
        }
    }

    async function cargarMunicipiosPacEsp() {
        const estadoId = document.getElementById('pac_esp_estado_id').value;
        const select = document.getElementById('pac_esp_municipio_id');
        
        if (!estadoId) { select.disabled = true; return; }
        
        try {
            const response = await fetch(`${BASE_URL}/ubicacion/get-municipios/${estadoId}`);
            const data = await response.json();
            
            select.innerHTML = '<option value="">Seleccione...</option>';
            data.forEach(m => select.innerHTML += `<option value="${m.id_municipio}">${m.municipio}</option>`);
            select.disabled = false;
        } catch (error) {
            console.error('Error cargando municipios paciente especial:', error);
        }
    }

    async function cargarParroquiasPacEsp() {
        const municipioId = document.getElementById('pac_esp_municipio_id').value;
        const select = document.getElementById('pac_esp_parroquia_id');
        
        if (!municipioId) { select.disabled = true; return; }
        
        try {
            const response = await fetch(`${BASE_URL}/ubicacion/get-parroquias/${municipioId}`);
            const data = await response.json();
            
            select.innerHTML = '<option value="">Seleccione...</option>';
            data.forEach(p => select.innerHTML += `<option value="${p.id_parroquia}">${p.parroquia}</option>`);
            select.disabled = false;
        } catch (error) {
            console.error('Error cargando parroquias paciente especial:', error);
        }
    }

    // =========================================================================
    // VALIDACIÓN
    // =========================================================================
    // =========================================================================
    // LÓGICA PACIENTE ESPECIAL (Admin)
    // =========================================================================
    function togglePacEspDocumento(tiene) {
        const campoDoc = document.getElementById('campo-documento-pac-esp');
        const infoGenerado = document.getElementById('pac-esp-doc-generado-info');
        
        if (tiene) {
            campoDoc.classList.remove('hidden');
            infoGenerado.classList.add('hidden');
        } else {
            campoDoc.classList.add('hidden');
            infoGenerado.classList.remove('hidden');
            actualizarPacEspPreviewDocumento();
        }
    }

    async function actualizarPacEspPreviewDocumento() {
        let tipoDoc = document.getElementById('rep_tipo_documento')?.value || 'V';
        let numDoc = document.getElementById('rep_numero_documento')?.value || '';
        
        // Si hay representante existente seleccionado, intentar tomar de ahí
        const repExistente = document.getElementById('representante_existente')?.value === '1';
        if (repExistente) {
             const docDisplay = document.getElementById('rep_documento_display')?.textContent; 
             // Formato esperado display: "V-12345678"
             if(docDisplay && docDisplay.includes('-')) {
                 const parts = docDisplay.split('-');
                 tipoDoc = parts[0];
                 numDoc = parts[1];
             }
        }

        const preview = document.getElementById('pac-esp-documento-preview');
        const hiddenInput = document.getElementById('pac_esp_numero_documento_generado');
        
        if (!numDoc || !preview) return;

        preview.textContent = 'Generando...';
        
        try {
            const response = await fetch(`${BASE_URL}/ajax/citas/get-next-sequence/${numDoc}`);
            if (!response.ok) throw new Error('Error en API');
            
            const data = await response.json();
            const fullId = tipoDoc + '-' + data.full_id;
            preview.textContent = fullId;
            if(hiddenInput) hiddenInput.value = fullId;
        } catch(e) {
            console.error('Error sequence:', e);
            const fallback = tipoDoc + '-' + numDoc + '-01';
            preview.textContent = fallback; 
            if(hiddenInput) hiddenInput.value = fallback;
        }
    }
    
    // Listeners para actualizar preview ID cuando cambia datos del rep (nuevo)
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('rep_numero_documento')?.addEventListener('input', actualizarPacEspPreviewDocumento);
        document.getElementById('rep_tipo_documento')?.addEventListener('change', actualizarPacEspPreviewDocumento);
    });

    // =========================================================================
    // VALIDACIÓN IMPORTANTE
    // =========================================================================
    function validarFormulario() {
        let valid = true;
        let hasEmailDup = false;
        let hasIdDup = false;
        
        // Reset validaciones visuales
        document.querySelectorAll('.border-red-500').forEach(el => el.classList.remove('border-red-500'));
        document.querySelectorAll('.error-message').forEach(el => {
            if(!el.id.includes('error-message')) el.classList.add('hidden');
        });
        
        // Reset banner
        const banner = document.getElementById('submit-error-banner');
        if(banner) {
            banner.textContent = '';
            banner.classList.add('hidden');
        }

        const tipoCita = document.getElementById('tipo_cita').value;

        // 1. Validar Cita Propia
        if (tipoCita === 'propia') {
            const existente = document.getElementById('paciente_existente').value === '1';
            const noRegistrado = document.getElementById('paciente_no_registrado').checked;
            
            if (!existente && !noRegistrado) {
                alert('Debe buscar un paciente o indicar que es Nuevo');
                return false;
            }
            
            if (noRegistrado) {
                const campos = ['pac_primer_nombre', 'pac_primer_apellido', 'pac_numero_documento', 'pac_fecha_nac', 'pac_genero', 'pac_estado_id'];
                if(!validarCampos(campos)) valid = false;
                
                // Documento duplicado
                const pacDocInput = document.getElementById('pac_numero_documento');
                if (pacDocInput && pacDocInput.dataset.documentoInvalido === "true") {
                    marcarError('pac_numero_documento', 'La identificación ya se encuentra registrada en el sistema.');
                    hasIdDup = true;
                    valid = false;
                }
                
                // Correo Usuario
                if (document.getElementById('chk_registrar_usuario')?.checked) {
                    const correo = document.getElementById('pac_correo');
                    if (correo) {
                        if (!correo.value) { marcarError('pac_correo', 'El correo es requerido'); valid = false; }
                        else if (correo.dataset.invalid === "true") { 
                            marcarError('pac_correo', 'Correo ya registrado'); 
                            hasEmailDup = true;
                            valid = false; 
                        }
                    }
                }
            }
        } 
        
        // 2. Validar Cita Terceros
        else if (tipoCita === 'terceros') {
            // A) Representante
            const repExistente = document.getElementById('representante_existente').value === '1';
            const repNoRegistrado = document.getElementById('representante_no_registrado').checked;
            
            if (!repExistente && !repNoRegistrado) {
                alert('Debe seleccionar un representante o crear uno nuevo');
                return false;
            }
            
            if (repNoRegistrado) {
                const camposRep = ['rep_primer_nombre', 'rep_primer_apellido', 'rep_numero_documento', 'rep_fecha_nac', 'rep_parentesco', 'rep_estado_id'];
                if(!validarCampos(camposRep)) valid = false;
                
                // Documento duplicado representante
                const repDocInput = document.getElementById('rep_numero_documento');
                if (repDocInput && repDocInput.dataset.documentoInvalido === "true") {
                    marcarError('rep_numero_documento', 'La identificación ya se encuentra registrada en el sistema.');
                    hasIdDup = true;
                    valid = false;
                }
                
                // Correo Representante
                if (document.getElementById('chk_registrar_representante')?.checked) {
                     const correoRep = document.getElementById('rep_correo');
                     if (correoRep) {
                         if (!correoRep.value) { marcarError('rep_correo', 'El correo es requerido'); valid = false; }
                         else if (correoRep.dataset.invalid === "true") { 
                             marcarError('rep_correo', 'Correo ya registrado'); 
                             hasEmailDup = true;
                             valid = false; 
                         }
                     }
                }
            }
            
            // B) Paciente Especial
            const pacEspExistente = document.getElementById('paciente_especial_id').value;
            const pacEspNoRegistrado = document.getElementById('pac_especial_no_registrado').checked;
            
            // También verificar si hay paciente seleccionado del select del representante
            const selectPacEspRep = document.getElementById('select_pac_esp_representante');
            const pacEspDelRepresentante = selectPacEspRep ? selectPacEspRep.value : '';
            
            if (!pacEspExistente && !pacEspNoRegistrado && !pacEspDelRepresentante) {
                alert('Debe seleccionar un paciente especial o crear uno nuevo');
                return false;
            }
            
            if (pacEspNoRegistrado) {
                // Tipo
                if(!document.querySelector('input[name="pac_esp_tipo"]:checked')) {
                    document.getElementById('pac_esp_tipo_error').classList.remove('hidden');
                    document.getElementById('pac_esp_tipo_error').textContent = 'Seleccione el tipo';
                    valid = false;
                }
                
                // Tiene Documento
                const tieneDoc = document.querySelector('input[name="pac_esp_tiene_documento"]:checked');
                if(!tieneDoc) {
                    document.getElementById('pac_esp_tiene_documento_error').classList.remove('hidden');
                    document.getElementById('pac_esp_tiene_documento_error').textContent = 'Seleccione si tiene documento';
                    valid = false;
                }
                
                // Campos comunes
                const camposPacEsp = ['pac_esp_primer_nombre', 'pac_esp_primer_apellido', 'pac_esp_fecha_nac', 'pac_esp_genero', 'pac_esp_estado_id'];
                if(!validarCampos(camposPacEsp)) valid = false;
                
                // Documento manual si aplica
                if (tieneDoc && tieneDoc.value === 'si') {
                    if(!validarCampos(['pac_esp_numero_documento'])) valid = false;
                    
                    // Documento duplicado paciente especial
                    const pacEspDocInput = document.getElementById('pac_esp_numero_documento');
                    if (pacEspDocInput && pacEspDocInput.dataset.documentoInvalido === "true") {
                        marcarError('pac_esp_numero_documento', 'La identificación ya se encuentra registrada en el sistema.');
                        hasIdDup = true;
                        valid = false;
                    }
                }
            }
        }
        
        // 3. Validar Médico/Fecha/Hora
        if (!document.getElementById('medico_id').value) { alert('Seleccione Médico'); valid = false; }
        if (!document.getElementById('hora_inicio').value) { alert('Seleccione Hora'); valid = false; }
        
        if (!valid) {
            // Mostrar Banner con errores específicos
            if (banner) {
                if (hasEmailDup && hasIdDup) {
                    banner.textContent = 'El correo y Numero de Identificacion ya estan Registrados en el Sistema';
                    banner.dataset.emailError = '1';
                    banner.dataset.idError = '1';
                    banner.classList.remove('hidden');
                } else if (hasEmailDup) {
                    banner.textContent = 'El Correo ya se Encuentra Registrado en el Sistema, Ingrese Otro.';
                    banner.dataset.emailError = '1';
                    banner.dataset.idError = '0';
                    banner.classList.remove('hidden');
                } else if (hasIdDup) {
                    banner.textContent = 'La identificacion ya se Encuentra Registrada en el Sistema, Ingrese Otra.';
                    banner.dataset.emailError = '0';
                    banner.dataset.idError = '1';
                    banner.classList.remove('hidden');
                } else {
                    banner.dataset.emailError = '0';
                    banner.dataset.idError = '0';
                    alert('Por favor verifique los errores en el formulario.');
                }
            } else {
                 alert('Por favor verifique los errores en el formulario.');
            }
        }
        
        return valid;
    }

    // =========================================================================
    // GESTIÓN DINÁMICA DEL BANNER DE ERRORES AL ESCRIBIR
    // =========================================================================
    
    function actualizarBannerErrores(tipoCorregido) {
        const banner = document.getElementById('submit-error-banner');
        if (!banner || banner.classList.contains('hidden')) return;
        
        // Obtener estado actual de errores del banner
        let hasEmailError = banner.dataset.emailError === '1';
        let hasIdError = banner.dataset.idError === '1';
        
        if (tipoCorregido === 'email') {
            hasEmailError = false;
            banner.dataset.emailError = '0';
        } else if (tipoCorregido === 'id') {
            hasIdError = false;
            banner.dataset.idError = '0';
        }
        
        // Decidir qué mostrar
        if (!hasEmailError && !hasIdError) {
            banner.textContent = '';
            banner.classList.add('hidden');
        } else if (hasEmailError) {
            banner.textContent = 'El Correo ya se Encuentra Registrado en el Sistema, Ingrese Otro.';
        } else if (hasIdError) {
            banner.textContent = 'La identificacion ya se Encuentra Registrada en el Sistema, Ingrese Otra.';
        }
    }

    // Agregar listeners a los campos propensos a error para ocultar banner
    // y para asegurarnos que se dispare la validación
    const camposEmailMonitor = ['pac_correo', 'rep_correo'];
    const camposIdMonitor = ['pac_numero_documento', 'rep_numero_documento', 'pac_esp_numero_documento'];
    
    camposEmailMonitor.forEach(id => {
        const el = document.getElementById(id);
        if(el) {
            el.addEventListener('input', () => actualizarBannerErrores('email'));
        }
    });
    
    camposIdMonitor.forEach(id => {
        const el = document.getElementById(id);
        if(el) {
            el.addEventListener('input', () => actualizarBannerErrores('id'));
        }
    });
    
    function validarCampos(ids) {
        let allValid = true;
        ids.forEach(id => {
            const el = document.getElementById(id);
            if(el && !el.value) {
                el.classList.add('border-red-500');
                const err = el.nextElementSibling;
                if(err && err.classList.contains('error-message')) {
                    err.textContent = 'Este campo es requerido';
                    err.classList.remove('hidden');
                }
                allValid = false;
            }
        });
        return allValid;
    }
    
    function marcarError(id, msg) {
        const el = document.getElementById(id);
        if(el) {
            el.classList.add('border-red-500');
            const err = el.nextElementSibling;
            if(err && err.classList.contains('error-message')) {
                err.textContent = msg;
                err.classList.remove('hidden');
            }
        }
    }
</script>
@endpush
