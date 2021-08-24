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
            ['description' => '', 'name' => 'Productos'],
            ['description' => '', 'name' => 'Servicios'],
            ['description' => '', 'name' => 'Representación'],
        ]);

        factory(StateOrder::class)->createMany([
            ['description' => '', 'name' => 'Inicio'],
            ['description' => '', 'name' => 'Adquisición'],
            ['description' => '', 'name' => 'Producción'],
            ['description' => '', 'name' => 'Reparación'],
            ['description' => '', 'name' => 'Importación de insumos'],
            ['description' => '', 'name' => 'Remisión'],
            ['description' => '', 'name' => 'En proceso de Importación'],
            ['description' => '', 'name' => 'En proceso de alistamiento'],
            ['description' => '', 'name' => 'Despachado'],
            ['description' => '', 'name' => 'Recibido por el cliente'],
            ['description' => '', 'name' => 'Facturado'],
            ['description' => '', 'name' => 'Pagado'],
        ]);
    }
}
