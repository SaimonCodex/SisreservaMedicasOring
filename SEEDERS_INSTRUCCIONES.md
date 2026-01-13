# Instrucciones para Ejecutar Seeders

## Opción 1: Ejecutar Todos los Seeders Individualmente

```bash
# Métodos de Pago
php artisan db:seed --class=MetodoPagoSeeder

# Tasas de Dólar
php artisan db:seed --class=TasaDolarSeeder

# Configuración de Reparto (requiere que existan médicos y consultorios)
php artisan db:seed --class=ConfiguracionRepartoSeeder
```

## Opción 2: Agregar los Seeders al DatabaseSeeder

Edita el archivo `database/seeders/DatabaseSeeder.php` y agrega las siguientes líneas en el método `run()`:

```php
public function run(): void
{
    // ... otros seeders existentes ...
    
    // Seeders para el sistema de pagos
    $this->call([
        MetodoPagoSeeder::class,
        TasaDolarSeeder::class,
        ConfiguracionRepartoSeeder::class,
    ]);
}
```

Luego ejecuta:
```bash
php artisan db:seed
```

## Verificar Datos Insertados

```bash
# Ver métodos de pago
php artisan tinker
>>> App\Models\MetodoPago::all();

# Ver tasas
>>> App\Models\TasaDolar::where('status', true)->get();

# Ver configuración de reparto
>>> App\Models\ConfiguracionReparto::with('medico', 'consultorio')->get();
```

## Notas

- **MetodoPagoSeeder**: Inserta 6 métodos de pago comunes (Transferencia, Pago Móvil, Efectivo, Débito, Crédito, Zelle)
- **TasaDolarSeeder**: Inserta 3 tasas históricas, marcando la última como activa (status=true)
- **ConfiguracionRepartoSeeder**: Inserta configuraciones para los primeros 3 médicos y 2 consultorios (requiere que ya existan)

## Ajustar Tasa de Dólar

Para actualizar la tasa actual al valor real del BCV, edita `database/seeders/TasaDolarSeeder.php` línea 21 y cambia el valor.
