<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait ScopedByConsultorio
{
    /**
     * Boot the trait and add a global scope
     */
    protected static function bootScopedByConsultorio()
    {
        static::addGlobalScope('consultorio_scope', function (Builder $builder) {
            $user = Auth::user();

            // Solo aplicar scope si hay usuario autenticado con administrador
            if (!$user || !$user->administrador) {
                return;
            }

            $admin = $user->administrador;

            // Si es Root, no aplicar restricciones
            if ($admin->tipo_admin === 'Root') {
                return;
            }

            // Obtener los IDs de los consultorios asignados
            $consultorioIds = $admin->consultorios->pluck('id')->toArray();

            // Si no tiene consultorios asignados, no mostrar nada
            if (empty($consultorioIds)) {
                $builder->whereRaw('1 = 0'); // Query que siempre devuelve vacío
                return;
            }

            // Aplicar filtro según el modelo
            $table = $builder->getModel()->getTable();

            // Para modelos con consultorio_id directo
            if (in_array($table, ['citas'])) {
                $builder->whereIn('consultorio_id', $consultorioIds);
            }
            
            // Para modelos relacionados a través de citas (FacturaPaciente)
            elseif ($table === 'facturas_pacientes') {
                $builder->whereHas('cita', function ($query) use ($consultorioIds) {
                    $query->whereIn('consultorio_id', $consultorioIds);
                });
            }

            // Para Pagos (a través de FacturaPaciente -> Cita)
            elseif ($table === 'pago') {
                $builder->whereHas('facturaPaciente.cita', function ($query) use ($consultorioIds) {
                    $query->whereIn('consultorio_id', $consultorioIds);
                });
            }
            
            // Para historias clínicas (a través del paciente y sus citas)
            elseif ($table === 'historia_clinica_base') {
                $builder->whereHas('paciente.citas', function ($query) use ($consultorioIds) {
                    $query->whereIn('consultorio_id', $consultorioIds);
                });
            }
            
            // Para evoluciones clínicas
            elseif ($table === 'evoluciones_clinicas') {
                $builder->whereHas('cita', function ($query) use ($consultorioIds) {
                    $query->whereIn('consultorio_id', $consultorioIds);
                });
            }
            
            // Para órdenes médicas
            elseif ($table === 'ordenes_medicas') {
                $builder->whereHas('cita', function ($query) use ($consultorioIds) {
                    $query->whereIn('consultorio_id', $consultorioIds);
                });
            }

            // Para médicos (que trabajan en los consultorios asignados)
            elseif ($table === 'medicos') {
                $builder->whereHas('consultorios', function ($query) use ($consultorioIds) {
                    $query->whereIn('consultorios.id', $consultorioIds); // pivot table usually implies joining on ID or foreign key
                    // Consultorio model uses 'id' primary key. Pivot is medico_consultorio.
                    // belongsToMany checks existence in the related table.
                    // whereHas('consultorios') checks if related consultorios match.
                    // 'consultorios' relation on Medico is belongsToMany Consultorio.
                    // The query inside whereHas is on Consultorio model? No, it's on the relation.
                    // Actually, for belongsToMany, whereHas query is on the related model (Consultorio).
                    // So we check if Consultorio.id is in $consultorioIds.
                });
            }

            // Para pacientes (que han tenido citas en los consultorios asignados)
            elseif ($table === 'pacientes') {
                $builder->whereHas('citas', function ($query) use ($consultorioIds) {
                    $query->whereIn('consultorio_id', $consultorioIds);
                });
            }

            // Para representantes (vinculados a pacientes especiales que están vinculados a pacientes -> citas -> consultorios, O directamente a pacientes especiales)
            // Lógica compleja: Un representante se asigna a un paciente especial.
            // Un paciente especial puede estar o no vinculado a un PACIENTE regular (user_id).
            // Si el paciente especial es un PACIENTE regular, podemos filtrar por sus citas.
            // Si el paciente especial NO tiene citas propias (es dependiente), ¿cómo filtramos?
            // ASUMAMOS: Un Admin Local puede ver representantes cuyos Pacientes Especiales están asociados a un Paciente que tiene citas en el consultorio.
            elseif ($table === 'representantes') {
                 $builder->whereHas('pacientesEspeciales.paciente.citas', function ($query) use ($consultorioIds) {
                    $query->whereIn('consultorio_id', $consultorioIds);
                });
            }
            // Para Pacientes Especiales (mismo filtro)
            elseif ($table === 'pacientes_especiales') {
                $builder->whereHas('paciente.citas', function ($query) use ($consultorioIds) {
                    $query->whereIn('consultorio_id', $consultorioIds);
                });
            }
        });
    }
}
