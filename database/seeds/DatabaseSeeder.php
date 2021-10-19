<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // DB::statement('DEFAULT CHARACTER SET utf8;');

        Storage::deleteDirectory('users');
        Storage::deleteDirectory('archives');
        Storage::makeDirectory('users');
        Storage::makeDirectory('archives');

        $this->call(UserTableSeeder::class);

        /*=============================================
           CREANDO TIPOS DE MONEDA
        =============================================*/
        factory(\App\Models\Currency::class)->create(['code' => 'USD']);
        factory(\App\Models\Currency::class)->create(['code' => 'COP']);

        /*=============================================
           CREANDO TIPOS DE INDENTIFICACIÓN
        =============================================*/
        factory(\App\Models\IdentificationType::class)->create(['name' => 'Cédula de Ciudadania']);
        factory(\App\Models\IdentificationType::class)->create(['name' => 'Cédula de Extranjeria']);
        factory(\App\Models\IdentificationType::class)->create(['name' => 'Pasaporte']);
        factory(\App\Models\IdentificationType::class)->create(['name' => 'Nit']);
        factory(\App\Models\IdentificationType::class)->create(['name' => 'TIN', 'description' => "Taxpayer Identification Number"]);


        /*=============================================
           CREANDO TIPOS DE CLIENTES
        =============================================*/
        factory(\App\Models\CustomerType::class)->create(['name' => 'Persona Natural']);
        factory(\App\Models\CustomerType::class)->create(['name' => 'Juridica']);


        /*=============================================
           CREANDO LOS TIPO DE CONTRATO DEL EMPLEADO
        =============================================*/
        factory(\App\Models\TypeEmployee::class)->create(['name' => 'Contratista']);
        factory(\App\Models\TypeEmployee::class)->create(['name' => 'Empleado']);

        /*=============================================
           CREANDO LAS SUCURSALES
        =============================================*/
//        factory(\App\Models\BranchOffice::class)->create(['name' => 'Cali', 'code' => '218291831212']);
//        factory(\App\Models\BranchOffice::class)->create(['name' => 'Bogotá', 'code' => '54646514']);
//        factory(\App\Models\BranchOffice::class)->create(['name' => 'Medellín', 'code' => '64847961']);

        /*=============================================
            CREAMOS 6 EMPLEADOS ASISTENTE SUCURSAL
        =============================================*/
//        factory(\App\User::class, 6)->create()
//            ->each(function (\App\User $u) {
//                $u->roles()->attach([2]);
//                factory(\App\Models\Employee::class, 1)->create(
//                    [
//                        'user_id' => $u->id,
//                        'type_employee_id' => \App\Models\TypeEmployee::all()->random()->id,
//                        'branch_offices_id' => \App\Models\BranchOffice::all()->random()->id,
//                    ]
//                );
//            });

        /*=============================================
            CREAMOS 6 EMPLEADOS GERENCIA
        =============================================*/
//        factory(\App\User::class, 6)->create()
//            ->each(function (\App\User $u) {
//                $u->roles()->attach([3]);
//                factory(\App\Models\Employee::class, 1)->create(
//                    [
//                        'user_id' => $u->id,
//                        'type_employee_id' => \App\Models\TypeEmployee::all()->random()->id,
//                        'branch_offices_id' => \App\Models\BranchOffice::all()->random()->id,
//                    ]
//                );
//            });

        /*=============================================
            CREAMOS 6 VENDEDORES
        =============================================*/
//        factory(\App\User::class, 6)->create()
//            ->each(function (\App\User $u) {
//                $u->roles()->attach([4]);
//                factory(\App\Models\Employee::class, 1)->create(
//                    [
//                        'user_id' => $u->id,
//                        'type_employee_id' => \App\Models\TypeEmployee::all()->random()->id,
//                        'branch_offices_id' => \App\Models\BranchOffice::all()->random()->id,
//                    ]
//                );
//            });

        /*=============================================
            CREAMOS 6 LOGISTICA
        =============================================*/
//        factory(\App\User::class, 6)->create()
//            ->each(function (\App\User $u) {
//                $u->roles()->attach([5]);
//                factory(\App\Models\Employee::class, 1)->create(
//                    [
//                        'user_id' => $u->id,
//                        'type_employee_id' => \App\Models\TypeEmployee::all()->random()->id,
//                        'branch_offices_id' => \App\Models\BranchOffice::all()->random()->id,
//                    ]
//                );
//            });

        /*=============================================
            CREAMOS 6 FINANZAS
        =============================================*/
//        factory(\App\User::class, 6)->create()
//            ->each(function (\App\User $u) {
//                $u->roles()->attach([6]);
//                factory(\App\Models\Employee::class, 1)->create(
//                    [
//                        'user_id' => $u->id,
//                        'type_employee_id' => \App\Models\TypeEmployee::all()->random()->id,
//                        'branch_offices_id' => \App\Models\BranchOffice::all()->random()->id,
//                    ]
//                );
//            });

        /*=============================================
           CREANDO LOS TIPO DE PROVEEDORES DEL EMPLEADO
        =============================================*/
        factory(\App\Models\TypeProvider::class)->create(['name' => 'Fabricante']);
        factory(\App\Models\TypeProvider::class)->create(['name' => 'Servicio']);
        factory(\App\Models\TypeProvider::class)->create(['name' => 'Internacional']);
        factory(\App\Models\TypeProvider::class)->create(['name' => 'Nacional']);

        /*=============================================
            CREAMOS 10 PROVEEDORES
        =============================================*/
//        factory(\App\User::class, 500)->create()
//            ->each(function (\App\User $u) {
//                $u->roles()->attach([8]);
//                factory(\App\Models\Provider::class, 1)->create(
//                    [
//                        'user_id' => $u->id,
//                        'type_providers_id' => \App\Models\TypeProvider::all()->random()->id,
//                    ]
//                );
//            });

        /*=============================================
            CREAMOS 5 ZONAS
        =============================================*/
//        factory(\App\Models\Zone::class)->create(['name' => 'Zona 1']);
//        factory(\App\Models\Zone::class)->create(['name' => 'Zona 2']);
//        factory(\App\Models\Zone::class)->create(['name' => 'Zona 3']);
//        factory(\App\Models\Zone::class)->create(['name' => 'Zona 4']);
//        factory(\App\Models\Zone::class)->create(['name' => 'Zona 5']);

        /*=============================================
            CREAMOS 5 TRANSPORTISTAS
        =============================================*/
//        factory(\App\Models\Conveyor::class)->create(['name' => 'Transportador 1']);
//        factory(\App\Models\Conveyor::class)->create(['name' => 'Transportador 2']);
//        factory(\App\Models\Conveyor::class)->create(['name' => 'Transportador 3']);
//        factory(\App\Models\Conveyor::class)->create(['name' => 'Transportador 4']);
//        factory(\App\Models\Conveyor::class)->create(['name' => 'Transportador 5']);

        /*=============================================
            TIPOS DE PRODUCTO PRODUCTO
        =============================================*/
        factory(\App\Models\TypeProduct::class)->create(['name' => 'Producto']);
        factory(\App\Models\TypeProduct::class)->create(['name' => 'Servicio']);
        factory(\App\Models\TypeProduct::class)->create(['name' => 'Representación']);

        /*=============================================
            PRODUCTOS
        =============================================*/
//        $products = factory(\App\Models\Product::class, 50)->create();
//
//        $products->each(function ($product) {
//            factory(App\Models\ProductPrice::class)->createMany([
//                [
//                    'product_id' => $product->id,
//                    'currency_id' => 1
//                ],
//                [
//                    'product_id' => $product->id,
//                    'currency_id' => 2
//                ]
//            ]);
//        });

        /*=============================================
            CREAMOS 10 CLIENTES
        =============================================*/
//        $this->createUsers();
//        factory(\App\User::class, 9)->create()
//            ->each(function (\App\User $u) {
//                $u->roles()->attach([7]);
//                factory(\App\Models\Customer::class, 1)->create(
//                    [
//                        'user_id' => $u->id,
//                    ]
//                );
//            });

        /*=============================================
            CREAMOS 1 SEDE
        =============================================*/
//        factory(\App\User::class, 1)->create()
//            ->each(function (\App\User $u) {
//                $u->roles()->attach([7]);
//                factory(\App\Models\Customer::class, 1)->create(
//                    [
//                        'user_id' => $u->id,
//                        'principal' => '1',
//                        'sub_sede_of' => 5,
//                    ]
//                );
//            });

        /*=============================================
          CREAMOS EL ADMINISTRADOR DEL SISTEMA
        =============================================*/
        $pass = bcrypt('pD&YdHZS9xtKSEfjYy');
        factory(\App\User::class, 1)->create(
            [
                'name' => 'Admin',
                'last_name' => 'Sistema',
                'email' => 'admin@admin.co',
                'password' => $pass
            ]
        )->each(function (\App\User $u) {
            $u->roles()->attach([1]);
        });

//        factory(\App\User::class, 1)->create(
//            [
//                'name' => 'Admin',
//                'last_name' => 'Sistema',
//                'email' => 'admin@admin.co',
//            ]
//        )->each(function (\App\User $u) {
//            $u->roles()->attach([1]);
//            factory(\App\Models\Employee::class, 1)->create(
//                [
//                    'user_id' => $u->id,
//                    'type_employee_id' => \App\Models\TypeEmployee::all()->random()->id,
//                    'branch_offices_id' => \App\Models\BranchOffice::all()->random()->id,
//                ]
//            );
//        });

        /*=============================================
           CREANDO TIPOS DE FACTURA
        =============================================*/
        factory(\App\Models\TypeInvoice::class)->create(['name' => 'Normal']);
        factory(\App\Models\TypeInvoice::class)->create(['name' => 'Comisión']);
        factory(\App\Models\TypeInvoice::class)->create(['name' => 'Fabricante']);

        /*=============================================
           CREANDO TIPOS DE PAGOS
        =============================================*/
        factory(\App\Models\PaymentType::class)->create(['name' => 'Pago Completo']);
        factory(\App\Models\PaymentType::class)->create(['name' => 'Pago Parcial']);

        /*=============================================
           CREANDO ESTADOS DE FACTURA
        =============================================*/
        factory(\App\Models\StateInvoice::class)->create(['name' => 'Por Pagar']);
        factory(\App\Models\StateInvoice::class)->create(['name' => 'Pagado']);
        factory(\App\Models\StateInvoice::class)->create(['name' => 'Retrasado']);

        /*=============================================
           CREANDO 20 ACUERDOS COMERCIALES
        =============================================*/
//        factory(\App\Models\TradeAgreement::class, 20)->create();
//
//        factory(\App\Models\Invoice::class, 100)->create();

        $this->call(PurchaseOrderSeeder::class);
    }

//    private function createUsers()
//    {
//        $users = [
//            2 => "asistente@gmail.com",
//            3 => "gerencia@gmail.com",
//            4 => "vendedor@gmail.com",
//            5 => "logistica@gmail.com",
//            6 => "finanzas@gmail.com",
//            7 => "cliente@gmail.com",
//            8 => "proveedor@gmail.com",
//        ];
//
//        foreach ($users as $rol => $email) {
//            $user = factory(\App\User::class)->create([
//                'email' => $email
//            ]);
//            $user->roles()->attach([$rol]);
//            if ($rol === 7) {
//                factory(\App\Models\Customer::class, 1)->create([
//                    'user_id' => $user->id,
//                ]);
//            }else {
//                factory(\App\Models\Employee::class, 1)->create([
//                    'user_id' => $user->id,
//                ]);
//            }
//        }
//    }
}
