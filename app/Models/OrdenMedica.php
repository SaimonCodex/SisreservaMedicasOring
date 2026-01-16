<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenMedica extends Model
{
    use HasFactory, \App\Traits\ScopedByConsultorio;

    protected $table = 'ordenes_medicas';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'cita_id',
        'paciente_id',
        'paciente_especial_id',
        'representante_id',
        'medico_id',
        'especialidad_id',
        'codigo_orden',
        'tipo_orden',
        'descripcion_detallada',
        'indicaciones',
        'resultados',
        'fecha_emision',
        'fecha_vigencia',
        'estado_orden',
        'diagnostico_principal',
        'firma_digital',
        'fecha_procesamiento',
        'status'
    ];

    protected $casts = [
        'fecha_emision' => 'date',
        'fecha_vigencia' => 'date',
        'fecha_procesamiento' => 'datetime',
        'status' => 'boolean',
    ];

    /**
     * Boot del modelo para generar código único
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($orden) {
            if (empty($orden->codigo_orden)) {
                $orden->codigo_orden = self::generarCodigoOrden();
            }
        });
    }

    /**
     * Generar código único de orden (ORD-YYYY-NNNN)
     */
    public static function generarCodigoOrden()
    {
        $year = date('Y');
        $ultimaOrden = self::whereYear('created_at', $year)
                          ->orderBy('id', 'desc')
                          ->first();
        
        $numero = $ultimaOrden ? (intval(substr($ultimaOrden->codigo_orden, -4)) + 1) : 1;
        return sprintf("ORD-%s-%04d", $year, $numero);
    }

    // ========================================
    // RELACIONES PRINCIPALES
    // ========================================

    public function cita()
    {
        return $this->belongsTo(Cita::class, 'cita_id');
    }

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }

    public function pacienteEspecial()
    {
        return $this->belongsTo(PacienteEspecial::class, 'paciente_especial_id');
    }

    public function representante()
    {
        return $this->belongsTo(Representante::class, 'representante_id');
    }

    public function medico()
    {
        return $this->belongsTo(Medico::class, 'medico_id');
    }

    public function especialidad()
    {
        return $this->belongsTo(Especialidad::class, 'especialidad_id');
    }

    // ========================================
    // RELACIONES CON DETALLES
    // ========================================

    /**
     * Medicamentos de la orden (Receta)
     */
    public function medicamentos()
    {
        return $this->hasMany(OrdenMedicamento::class, 'orden_id');
    }

    /**
     * Exámenes de laboratorio
     */
    public function examenes()
    {
        return $this->hasMany(OrdenExamen::class, 'orden_id');
    }

    /**
     * Estudios de imagenología
     */
    public function imagenes()
    {
        return $this->hasMany(OrdenImagen::class, 'orden_id');
    }

    /**
     * Referencias a especialistas
     */
    public function referencias()
    {
        return $this->hasMany(OrdenReferencia::class, 'orden_id');
    }

    /**
     * Solicitudes de acceso a esta orden
     */
    public function solicitudes()
    {
        return $this->hasMany(SolicitudOrden::class, 'orden_id');
    }

    // ========================================
    // MÉTODOS DE ACCESO Y CONFIDENCIALIDAD
    // ========================================

    /**
     * Verificar si un médico tiene acceso a esta orden
     */
    public function tieneAcceso($medicoId)
    {
        // El médico propietario siempre tiene acceso
        if ($this->medico_id == $medicoId) {
            return true;
        }

        // Verificar si tiene solicitud de acceso aprobada y vigente
        return SolicitudOrden::tieneAccesoActivo($medicoId, $this->id);
    }

    /**
     * Verificar si es el propietario
     */
    public function esPropietario($medicoId)
    {
        return $this->medico_id == $medicoId;
    }

    // ========================================
    // MÉTODOS AUXILIARES
    // ========================================

    /**
     * Obtener nombre completo del paciente (normal o especial)
     */
    public function getNombrePacienteAttribute()
    {
        if ($this->pacienteEspecial) {
            $pe = $this->pacienteEspecial;
            return $pe->paciente 
                ? "{$pe->paciente->primer_nombre} {$pe->paciente->primer_apellido}" 
                : "Paciente Especial #{$pe->id}";
        }
        
        if ($this->paciente) {
            return "{$this->paciente->primer_nombre} {$this->paciente->primer_apellido}";
        }
        
        return 'Sin paciente';
    }

    /**
     * Obtener total de ítems en la orden
     */
    public function getTotalItemsAttribute()
    {
        return $this->medicamentos()->count() 
             + $this->examenes()->count() 
             + $this->imagenes()->count() 
             + $this->referencias()->count();
    }

    /**
     * Verificar si la orden tiene contenido según su tipo
     */
    public function tieneContenido()
    {
        switch ($this->tipo_orden) {
            case 'Receta':
                return $this->medicamentos()->exists();
            case 'Laboratorio':
                return $this->examenes()->exists();
            case 'Imagenologia':
                return $this->imagenes()->exists();
            case 'Referencia':
                return $this->referencias()->exists();
            default:
                return $this->total_items > 0;
        }
    }

    /**
     * Obtener color según tipo de orden
     */
    public function getColorTipoAttribute()
    {
        switch ($this->tipo_orden) {
            case 'Receta':
                return 'success';
            case 'Laboratorio':
                return 'info';
            case 'Imagenologia':
                return 'warning';
            case 'Referencia':
                return 'purple';
            case 'Interconsulta':
                return 'indigo';
            case 'Procedimiento':
                return 'danger';
            default:
                return 'secondary';
        }
    }

    /**
     * Obtener icono según tipo de orden
     */
    public function getIconoTipoAttribute()
    {
        switch ($this->tipo_orden) {
            case 'Receta':
                return 'bi-capsule';
            case 'Laboratorio':
                return 'bi-droplet';
            case 'Imagenologia':
                return 'bi-x-ray';
            case 'Referencia':
                return 'bi-person-badge';
            case 'Interconsulta':
                return 'bi-people';
            case 'Procedimiento':
                return 'bi-hospital';
            default:
                return 'bi-file-medical';
        }
    }
}
