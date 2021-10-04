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

    private function setDataFaker()
    {
        $purchaseOrders = factory(PurchaseOrder::class, 100)->create();

        foreach ($purchaseOrders as $purchaseOrder) {
            $cantidad = random_int(1, 4);
            factory(OrderDetail::class, $cantidad)->create([
                'purchase_order_id' => $purchaseOrder->id
            ]);

            $cantidad = random_int(3, 6);

            $histories = factory(PurchaseOrderStateHistory::class, $cantidad)->create([
                'purchase_order_id' => $purchaseOrder->id
            ]);

            $position = random_int(0, $cantidad - 1);
            $purchaseOrder->current_status_id = $histories[$position]->id;
            $purchaseOrder->save();
        }
    }
}
