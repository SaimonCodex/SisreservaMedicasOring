<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ConsultoriosTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('es_VE');
        
        $consultorios = [
            // 1. Consultorio Central (ID 1)
            [
                'nombre' => 'Consultorio Central Caracas',
                'descripcion' => 'Consultorio principal en zona céntrica',
                'estado_id' => 1,
                'ciudad_id' => 1,
                'municipio_id' => 1,
                'parroquia_id' => 1,
                'direccion_detallada' => 'Av. Principal de El Rosal, Edificio Médico, Piso 3',
                'telefono' => '(0212) 555-1234',
                'email' => 'info@consultoriocentral.com',
                'horario_inicio' => '08:00:00',
                'horario_fin' => '18:00:00',
            ],
            // 2. Clínica Los Teques (ID 2)
            [
                'nombre' => 'Clínica Los Teques',
                'descripcion' => 'Atención especializada en Miranda',
                'estado_id' => 2,
                'ciudad_id' => 2,
                'municipio_id' => 2,
                'parroquia_id' => 3,
                'direccion_detallada' => 'Centro Comercial Los Altos, Local 15',
                'telefono' => '(0212) 555-5678',
                'email' => 'clinicateques@email.com',
                'horario_inicio' => '07:30:00',
                'horario_fin' => '17:30:00',
            ],
            // 3. (ID 3)
            [
                'nombre' => 'Centro Médico Maracay',
                'descripcion' => 'Especialistas en Aragua',
                'estado_id' => 3, // Asignación simulada
                'ciudad_id' => 3,
                'municipio_id' => 4,
                'parroquia_id' => 4,
                'direccion_detallada' => 'Av. Las Delicias, Torre Empresarial',
                'telefono' => '(0243) 555-9012',
                'email' => 'maracay@centromedico.com',
                'horario_inicio' => '08:00:00',
                'horario_fin' => '19:00:00',
            ],
            // 4. (ID 4)
            [
                'nombre' => 'Unidad Salud Valencia',
                'descripcion' => 'Tecnología de punta en Carabobo',
                'estado_id' => 4,
                'ciudad_id' => 4,
                'municipio_id' => 5,
                'parroquia_id' => 5,
                'direccion_detallada' => 'Urb. El Viñedo, Calle 139',
                'telefono' => '(0241) 555-3456',
                'email' => 'valencia@salud.com',
                'horario_inicio' => '07:00:00',
                'horario_fin' => '20:00:00',
            ],
            // 5. (ID 5)
            [
                'nombre' => 'Consultorios del Este',
                'descripcion' => 'Atención VIP en Caracas',
                'estado_id' => 1,
                'ciudad_id' => 1,
                'municipio_id' => 2,
                'parroquia_id' => 2,
                'direccion_detallada' => 'La Castellana, Edif. Premium',
                'telefono' => '(0212) 555-7890',
                'email' => 'este@consultorios.com',
                'horario_inicio' => '09:00:00',
                'horario_fin' => '18:00:00',
            ],
            // 6. (ID 6)
            [
                'nombre' => 'Centro Pediátrico Infantil',
                'descripcion' => 'Especializado en niños',
                'estado_id' => 1,
                'ciudad_id' => 1,
                'municipio_id' => 1,
                'parroquia_id' => 1,
                'direccion_detallada' => 'San Bernardino, Av. Panteón',
                'telefono' => '(0212) 555-1122',
                'email' => 'pediatria@centro.com',
                'horario_inicio' => '08:00:00',
                'horario_fin' => '16:00:00',
            ],
            // 7. (ID 7)
            [
                'nombre' => 'Unidad de Cardiología Integral',
                'descripcion' => 'Salud cardiovascular',
                'estado_id' => 2,
                'ciudad_id' => 2,
                'municipio_id' => 2,
                'parroquia_id' => 3,
                'direccion_detallada' => 'San Antonio de los Altos, Pueblo',
                'telefono' => '(0212) 555-3344',
                'email' => 'cardio@unidad.com',
                'horario_inicio' => '07:30:00',
                'horario_fin' => '17:00:00',
            ],
            // 8. (ID 8)
            [
                'nombre' => 'Centro de Diagnóstico Rápido',
                'descripcion' => 'Laboratorio y consultas express',
                'estado_id' => 1,
                'ciudad_id' => 1,
                'municipio_id' => 3,
                'parroquia_id' => 6,
                'direccion_detallada' => 'Chacao, Centro San Ignacio',
                'telefono' => '(0212) 555-5566',
                'email' => 'diagnostico@rapido.com',
                'horario_inicio' => '07:00:00',
                'horario_fin' => '21:00:00',
            ],
        ];

        foreach ($consultorios as $consultorio) {
            DB::table('consultorios')->insert(array_merge($consultorio, [
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
