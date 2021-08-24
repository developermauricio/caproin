<?php

use App\Models\OrderDetail;
use App\Models\OrderType;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderStateHistory;
use App\Models\StateOrder;
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

        $this->setDataFaker();
    }

    private function setDataFaker() {
        $purchaseOrders = factory(PurchaseOrder::class, 10)->create();

        foreach ($purchaseOrders as $purchaseOrder) {
            factory(OrderDetail::class)->create([
                'purchase_order_id' => $purchaseOrder->id
            ]);

            factory(PurchaseOrderStateHistory::class, 3)->create([
                'purchase_order_id' => $purchaseOrder->id
            ]);
        }
    }
}
