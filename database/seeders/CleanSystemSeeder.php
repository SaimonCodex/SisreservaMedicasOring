<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CleanSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Este seeder inicializa el sistema con los datos mínimos necesarios para operar,
     * dejando solo al Administrador Principal (Root).
     */
    public function run(): void
    {
        // 1. Ejecutar Catalógos Fundamentales
        $this->call([
            RolesTableSeeder::class,
            EstadosTableSeeder::class,
            CiudadesTableSeeder::class,
            MunicipiosTableSeeder::class,
            ParroquiasTableSeeder::class,
            PreguntasCatalogoTableSeeder::class,
            MetodoPagoTableSeeder::class,
            EspecialidadesTableSeeder::class,
        ]);

        $now = now();

        // 2. Crear al Administrador Root (ID 1)
        // Usamos el mismo formato de contraseña (doble MD5) que usa el sistema actual
        $password = md5(md5('admin123'));

        DB::table('usuarios')->insertOrIgnore([
            'id' => 1,
            'rol_id' => 1, // Administrador
            'correo' => 'admin@clinica.com',
            'password' => $password,
            'status' => true,
            'email_verified_at' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('administradores')->insertOrIgnore([
            'user_id' => 1,
            'primer_nombre' => 'Admin',
            'primer_apellido' => 'Principal',
            'tipo_documento' => 'V',
            'numero_documento' => '12345678',
            'fecha_nac' => '1990-01-01',
            'estado_id' => 1,
            'ciudad_id' => 1,
            'municipio_id' => 1,
            'parroquia_id' => 1,
            'direccion_detallada' => 'Sede Central',
            'prefijo_tlf' => '+58',
            'numero_tlf' => '4120000000',
            'genero' => 'Masculino',
            'tipo_admin' => 'Root',
            'status' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // 3. Poblar Ubicaciones del Estado Lara (ID 5)
        // Ciudades
        $ciudadesLara = [
            ['id_ciudad' => 6, 'id_estado' => 5, 'ciudad' => 'Barquisimeto', 'capital' => true],
            ['id_ciudad' => 7, 'id_estado' => 5, 'ciudad' => 'Cabudare', 'capital' => false],
            ['id_ciudad' => 8, 'id_estado' => 5, 'ciudad' => 'Carora', 'capital' => false],
            ['id_ciudad' => 9, 'id_estado' => 5, 'ciudad' => 'Quíbor', 'capital' => false],
            ['id_ciudad' => 10, 'id_estado' => 5, 'ciudad' => 'El Tocuyo', 'capital' => false],
            ['id_ciudad' => 11, 'id_estado' => 5, 'ciudad' => 'Sanare', 'capital' => false],
            ['id_ciudad' => 12, 'id_estado' => 5, 'ciudad' => 'Sarare', 'capital' => false],
            ['id_ciudad' => 13, 'id_estado' => 5, 'ciudad' => 'Duaca', 'capital' => false],
            ['id_ciudad' => 14, 'id_estado' => 5, 'ciudad' => 'Siquisique', 'capital' => false],
        ];

        foreach ($ciudadesLara as $ciu) {
            DB::table('ciudades')->updateOrInsert(['id_ciudad' => $ciu['id_ciudad']], array_merge($ciu, ['status' => true, 'created_at' => $now, 'updated_at' => $now]));
        }

        // Municipios
        $municipiosLara = [
            ['id_municipio' => 6, 'id_estado' => 5, 'municipio' => 'Iribarren'],
            ['id_municipio' => 7, 'id_estado' => 5, 'municipio' => 'Palavecino'],
            ['id_municipio' => 8, 'id_estado' => 5, 'municipio' => 'Torres'],
            ['id_municipio' => 9, 'id_estado' => 5, 'municipio' => 'Jiménez'],
            ['id_municipio' => 10, 'id_estado' => 5, 'municipio' => 'Morán'],
            ['id_municipio' => 11, 'id_estado' => 5, 'municipio' => 'Crespo'],
            ['id_municipio' => 12, 'id_estado' => 5, 'municipio' => 'Andrés Eloy Blanco'],
            ['id_municipio' => 13, 'id_estado' => 5, 'municipio' => 'Urdaneta'],
            ['id_municipio' => 14, 'id_estado' => 5, 'municipio' => 'Simón Planas'],
        ];
        
        // Usar insertOrIgnore para evitar duplicados si ya existen por el seeder base
        foreach ($municipiosLara as $mun) {
            DB::table('municipios')->updateOrInsert(['id_municipio' => $mun['id_municipio']], array_merge($mun, ['status' => true, 'created_at' => $now, 'updated_at' => $now]));
        }

        // Parroquias (Selección principal)
        $parroquiasLara = [
            // Iribarren (Barquisimeto)
            ['id_parroquia' => 7, 'id_municipio' => 6, 'parroquia' => 'Catedral'],
            ['id_parroquia' => 8, 'id_municipio' => 6, 'parroquia' => 'Concepción'],
            ['id_parroquia' => 9, 'id_municipio' => 6, 'parroquia' => 'Santa Rosa'],
            ['id_parroquia' => 10, 'id_municipio' => 6, 'parroquia' => 'Unión'],
            // Palavecino (Cabudare)
            ['id_parroquia' => 11, 'id_municipio' => 7, 'parroquia' => 'Cabudare'],
            ['id_parroquia' => 12, 'id_municipio' => 7, 'parroquia' => 'José Gregorio Bastidas'],
            // Torres (Carora)
            ['id_parroquia' => 13, 'id_municipio' => 8, 'parroquia' => 'Trinidad Samuel'],
            // Jiménez (Quíbor)
            ['id_parroquia' => 14, 'id_municipio' => 9, 'parroquia' => 'Juan Bautista Rodríguez'],
            // Morán (El Tocuyo)
            ['id_parroquia' => 15, 'id_municipio' => 10, 'parroquia' => 'Bolívar'],
        ];

        foreach ($parroquiasLara as $par) {
            DB::table('parroquias')->updateOrInsert(['id_parroquia' => $par['id_parroquia']], array_merge($par, ['status' => true, 'created_at' => $now, 'updated_at' => $now]));
        }

        // 4. Crear Consultorios en Lara
        $consultorios = [
            // BARQUISIMETO (Iribarren)
            [
                'nombre' => 'Centro Médico San José - Barquisimeto',
                'descripcion' => 'Consultorio especializado en el centro de Lara',
                'estado_id' => 5, 'ciudad_id' => 6, 'municipio_id' => 6, 'parroquia_id' => 7,
                'direccion_detallada' => 'Av. Venezuela con Calle 25, Barquisimeto',
                'telefono' => '(0251) 231-4455', 'email' => 'sanjose@lara.com',
                'horario_inicio' => '08:00:00', 'horario_fin' => '17:00:00',
            ],
            [
                'nombre' => 'Unidad Médica del Este - Lara',
                'descripcion' => 'Atención integral en la zona este',
                'estado_id' => 5, 'ciudad_id' => 6, 'municipio_id' => 6, 'parroquia_id' => 9, // Santa Rosa
                'direccion_detallada' => 'Urb. Las Trinitarias, Barquisimeto',
                'telefono' => '(0251) 255-8899', 'email' => 'unidadeste@lara.com',
                'horario_inicio' => '07:30:00', 'horario_fin' => '18:30:00',
            ],
            [
                'nombre' => 'Clínica Acosta Ortiz',
                'descripcion' => 'Referencia histórica en salud larense',
                'estado_id' => 5, 'ciudad_id' => 6, 'municipio_id' => 6, 'parroquia_id' => 7,
                'direccion_detallada' => 'Carrera 19 con Calle 30',
                'telefono' => '(0251) 232-1111', 'email' => 'acostaortiz@salud.com',
                'horario_inicio' => '00:00:00', 'horario_fin' => '23:59:59',
            ],
            [
                'nombre' => 'Policlínica Barquisimeto',
                'descripcion' => 'Servicios médicos avanzados 24/7',
                'estado_id' => 5, 'ciudad_id' => 6, 'municipio_id' => 6, 'parroquia_id' => 9,
                'direccion_detallada' => 'Av. Lara con Argimiro Bracamonte',
                'telefono' => '(0251) 254-1234', 'email' => 'poli@barquisimeto.com',
                'horario_inicio' => '00:00:00', 'horario_fin' => '23:59:59',
            ],
            
            // CABUDARE (Palavecino)
            [
                'nombre' => 'Centro Clínico Cabudare',
                'descripcion' => 'Atención primaria para Palavecino',
                'estado_id' => 5, 'ciudad_id' => 6, 'municipio_id' => 7, 'parroquia_id' => 11,
                'direccion_detallada' => 'Av. La Mata, Cabudare Centro',
                'telefono' => '(0251) 261-5544', 'email' => 'cabudare@clinica.com',
                'horario_inicio' => '08:00:00', 'horario_fin' => '18:00:00',
            ],
            [
                'nombre' => 'Unidad Pediátrica Valle Hondo',
                'descripcion' => 'Especialistas en niños',
                'estado_id' => 5, 'ciudad_id' => 6, 'municipio_id' => 7, 'parroquia_id' => 12,
                'direccion_detallada' => 'Urb. Valle Hondo, Calle Principal',
                'telefono' => '(0251) 263-9988', 'email' => 'pediatria@vallehondo.com',
                'horario_inicio' => '09:00:00', 'horario_fin' => '16:00:00',
            ],

            // CARORA (Torres)
            [
                'nombre' => 'Clínica Carora',
                'descripcion' => 'Principal centro de salud en Torres',
                'estado_id' => 5, 'ciudad_id' => 6, 'municipio_id' => 8, 'parroquia_id' => 13,
                'direccion_detallada' => 'Av. Francisco de Miranda, Carora',
                'telefono' => '(0252) 421-3322', 'email' => 'contacto@clinicacarora.com',
                'horario_inicio' => '07:00:00', 'horario_fin' => '19:00:00',
            ],

            // QUÍBOR (Jiménez)
            [
                'nombre' => 'Centro Médico Quíbor',
                'descripcion' => 'Atención general y emergencias',
                'estado_id' => 5, 'ciudad_id' => 6, 'municipio_id' => 9, 'parroquia_id' => 14,
                'direccion_detallada' => 'Av. Rotaria, Quíbor',
                'telefono' => '(0253) 491-2255', 'email' => 'quibor@salud.com',
                'horario_inicio' => '08:00:00', 'horario_fin' => '17:00:00',
            ],
            
            // EL TOCUYO (Morán)
            [
                'nombre' => 'Hospital Privado El Tocuyo',
                'descripcion' => 'Servicios médicos para Morán',
                'estado_id' => 5, 'ciudad_id' => 6, 'municipio_id' => 10, 'parroquia_id' => 15,
                'direccion_detallada' => 'Av. Fraternidad, El Tocuyo',
                'telefono' => '(0253) 663-1122', 'email' => 'hospital@eltocuyo.com',
                'horario_inicio' => '00:00:00', 'horario_fin' => '23:59:59',
            ],
        ];

        foreach ($consultorios as $consultorio) {
            DB::table('consultorios')->insert(array_merge($consultorio, [
                'status' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }

        // 5. Asignar Especialidades a Consultorios
        $this->call(EspecialidadConsultorioTableSeeder::class);

        $this->command->info('Sistema limpiado y poblado con datos completos de Lara (Ubicaciones, Consultorios y Especialidades).');
    }
}
