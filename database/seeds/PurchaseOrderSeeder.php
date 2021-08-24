<?php

use App\OrderType;
use App\StateOrder;
use Illuminate\Database\Seeder;

class PurchaseOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(OrderType::class)->createMany([
            ['name' => 'Productos'],
            ['name' => 'Servicios'],
            ['name' => 'Representación'],
        ]);

        factory(StateOrder::class)->createMany([
            ['name' => 'Inicio'],
            ['name' => 'Adquisición'],
            ['name' => 'Producción'],
            ['name' => 'Reparación'],
            ['name' => 'Importación de insumos'],
            ['name' => 'Remisión'],
            ['name' => 'En proceso de Importación'],
            ['name' => 'En proceso de alistamiento'],
            ['name' => 'Despachado'],
            ['name' => 'Recibido por el cliente'],
            ['name' => 'Facturado'],
            ['name' => 'Pagado'],
        ]);
    }
}
