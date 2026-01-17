<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MetodoPagoTableSeeder extends Seeder
{
    public function run(): void
    {
        $metodos = [
            [
                'nombre' => 'Transferencia Bancaria',
                'descripcion' => 'Transferencia Bancaria', 
                'codigo' => 'TRANSF',
                'requiere_confirmacion' => true
            ],
            [
                'nombre' => 'Zelle',
                'descripcion' => 'Zelle', 
                'codigo' => 'ZELLE',
                'requiere_confirmacion' => true
            ],
            [
                'nombre' => 'Efectivo',
                'descripcion' => 'Efectivo', 
                'codigo' => 'EFECT',
                'requiere_confirmacion' => false
            ],
            [
                'nombre' => 'Pago Móvil',
                'descripcion' => 'Pago Móvil', 
                'codigo' => 'PAGOMOVIL',
                'requiere_confirmacion' => true
            ],
            [
                'nombre' => 'Tarjeta de Crédito',
                'descripcion' => 'Tarjeta de Crédito', 
                'codigo' => 'TARJETA',
                'requiere_confirmacion' => false
            ],
        ];

        foreach ($metodos as $metodo) {
            DB::table('metodo_pago')->insert(array_merge($metodo, [
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
