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
                'descripcion' => 'Transferencia Bancaria', 
                'codigo' => 'TRANSF',
                'requiere_confirmacion' => true
            ],
            [
                'descripcion' => 'Zelle', 
                'codigo' => 'ZELLE',
                'requiere_confirmacion' => true
            ],
            [
                'descripcion' => 'Efectivo', 
                'codigo' => 'EFECT',
                'requiere_confirmacion' => false
            ],
            [
                'descripcion' => 'Pago Móvil', 
                'codigo' => 'PAGOMOVIL',
                'requiere_confirmacion' => true
            ],
            [
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
