<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MetodoPagoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $metodos = [
            [
                'nombre' => 'Transferencia Bancaria',
                'descripcion' => 'Pago mediante transferencia bancaria',
                'codigo' => 'TRANSFER',
                'requiere_confirmacion' => true,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Pago Móvil',
                'descripcion' => 'Pago mediante pago móvil',
                'codigo' => 'MOBILE',
                'requiere_confirmacion' => true,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Efectivo',
                'descripcion' => 'Pago en efectivo',
                'codigo' => 'CASH',
                'requiere_confirmacion' => false,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Tarjeta de Débito',
                'descripcion' => 'Pago con tarjeta de débito',
                'codigo' => 'DEBIT',
                'requiere_confirmacion' => false,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Tarjeta de Crédito',
                'descripcion' => 'Pago con tarjeta de crédito',
                'codigo' => 'CREDIT',
                'requiere_confirmacion' => false,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Zelle',
                'descripcion' => 'Pago mediante Zelle',
                'codigo' => 'ZELLE',
                'requiere_confirmacion' => true,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('metodo_pago')->insert($metodos);
    }
}
