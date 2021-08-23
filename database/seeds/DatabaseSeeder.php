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
           CREANDO TIPOS DE INDENTIFICACIÓN
        =============================================*/
        factory(\App\Models\IdentificationType::class)->create(['name' => 'Cédula de Ciudadania']);
        factory(\App\Models\IdentificationType::class)->create(['name' => 'Cédula de Extranjeria']);
        factory(\App\Models\IdentificationType::class)->create(['name' => 'Pasaporte']);
        factory(\App\Models\IdentificationType::class)->create(['name' => 'Nit']);


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
        factory(\App\Models\BranchOffice::class)->create(['name' => 'Cali', 'code' => '218291831212']);
        factory(\App\Models\BranchOffice::class)->create(['name' => 'Bogotá', 'code' => '54646514']);
        factory(\App\Models\BranchOffice::class)->create(['name' => 'Medellín', 'code' => '64847961']);

        /*=============================================
            CREAMOS 6 EMPLEADOS ASISTENTE SUCURSAL
        =============================================*/
        factory(\App\User::class, 6)->create()
        ->each(function (\App\User $u){
            $u->roles()->attach([2]);
            factory(\App\Models\Employee::class, 1)->create(
                [
                    'user_id' => $u->id,
                    'type_employee_id' => \App\Models\TypeEmployee::all()->random()->id,
                    'branch_offices_id' => \App\Models\BranchOffice::all()->random()->id,
                ]
            );
        });

        /*=============================================
            CREAMOS 6 EMPLEADOS GERENCIA
        =============================================*/
        factory(\App\User::class, 6)->create()
            ->each(function (\App\User $u){
                $u->roles()->attach([3]);
                factory(\App\Models\Employee::class, 1)->create(
                    [
                        'user_id' => $u->id,
                        'type_employee_id' => \App\Models\TypeEmployee::all()->random()->id,
                        'branch_offices_id' => \App\Models\BranchOffice::all()->random()->id,
                    ]
                );
            });

        /*=============================================
            CREAMOS 6 VENDEDORES
        =============================================*/
        factory(\App\User::class, 6)->create()
            ->each(function (\App\User $u){
                $u->roles()->attach([4]);
                factory(\App\Models\Employee::class, 1)->create(
                    [
                        'user_id' => $u->id,
                        'type_employee_id' => \App\Models\TypeEmployee::all()->random()->id,
                        'branch_offices_id' => \App\Models\BranchOffice::all()->random()->id,
                    ]
                );
            });

        /*=============================================
            CREAMOS 6 LOGISTICA
        =============================================*/
        factory(\App\User::class, 6)->create()
            ->each(function (\App\User $u){
                $u->roles()->attach([5]);
                factory(\App\Models\Employee::class, 1)->create(
                    [
                        'user_id' => $u->id,
                        'type_employee_id' => \App\Models\TypeEmployee::all()->random()->id,
                        'branch_offices_id' => \App\Models\BranchOffice::all()->random()->id,
                    ]
                );
            });

        /*=============================================
            CREAMOS 6 FINANZAS
        =============================================*/
        factory(\App\User::class, 6)->create()
            ->each(function (\App\User $u){
                $u->roles()->attach([6]);
                factory(\App\Models\Employee::class, 1)->create(
                    [
                        'user_id' => $u->id,
                        'type_employee_id' => \App\Models\TypeEmployee::all()->random()->id,
                        'branch_offices_id' => \App\Models\BranchOffice::all()->random()->id,
                    ]
                );
            });

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
        factory(\App\User::class, 500)->create()
            ->each(function (\App\User $u){
                $u->roles()->attach([8]);
                factory(\App\Models\Provider::class, 1)->create(
                    [
                        'user_id' => $u->id,
                        'type_providers_id' => \App\Models\TypeProvider::all()->random()->id,
                    ]
                );
            });

        /*=============================================
            CREAMOS 5 ZONAS
        =============================================*/
        factory(\App\Models\Zone::class)->create(['name' => 'Zona 1']);
        factory(\App\Models\Zone::class)->create(['name' => 'Zona 2']);
        factory(\App\Models\Zone::class)->create(['name' => 'Zona 3']);
        factory(\App\Models\Zone::class)->create(['name' => 'Zona 4']);
        factory(\App\Models\Zone::class)->create(['name' => 'Zona 5']);

        /*=============================================
            CREAMOS 5 TRANSPORTISTAS
        =============================================*/
        factory(\App\Models\Conveyor::class)->create(['name' => 'Transportador 1']);
        factory(\App\Models\Conveyor::class)->create(['name' => 'Transportador 2']);
        factory(\App\Models\Conveyor::class)->create(['name' => 'Transportador 3']);
        factory(\App\Models\Conveyor::class)->create(['name' => 'Transportador 4']);
        factory(\App\Models\Conveyor::class)->create(['name' => 'Transportador 5']);

        /*=============================================
            TIPO MONEDA
        =============================================*/
        factory(\App\Models\TypeCoin::class)->create(['name' => 'COP']);
        factory(\App\Models\TypeCoin::class)->create(['name' => 'USD']);
        factory(\App\Models\TypeCoin::class)->create(['name' => 'EURO']);

        /*=============================================
            TIPOS DE PRODUCTO PRODUCTO
        =============================================*/
        factory(\App\Models\TypeProduct::class)->create(['name' => 'Producto']);
        factory(\App\Models\TypeProduct::class)->create(['name' => 'Servicio']);
        factory(\App\Models\TypeProduct::class)->create(['name' => 'Representación']);

        /*=============================================
            PRODUCTOS
        =============================================*/
        factory(\App\Models\Product::class, 50)->create();

        /*=============================================
            CREAMOS 10 CLIENTES
        =============================================*/
        factory(\App\User::class, 10)->create()
            ->each(function (\App\User $u){
                $u->roles()->attach([7]);
                factory(\App\Models\Customer::class, 1)->create(
                    [
                        'user_id' => $u->id,
                    ]
                );
            });

        /*=============================================
          CREAMOS EL ADMINISTRADOR DEL SISTEMA
        =============================================*/

        factory(\App\User::class, 1)->create(
            [
                'name' => 'Admin',
                'last_name' => 'Sistema',
                'email' => 'admin@admin.co',
            ]
        )->each(function (\App\User $u){
            $u->roles()->attach([1]);
            factory(\App\Models\Employee::class, 1)->create(
                [
                    'user_id' => $u->id,
                    'type_employee_id' => \App\Models\TypeEmployee::all()->random()->id,
                    'branch_offices_id' => \App\Models\BranchOffice::all()->random()->id,
                ]
            );
        });

        /*=============================================
           CREANDO TIPOS DE FACTURA
        =============================================*/
        factory(\App\Models\TypeInvoice::class)->create(['name' => 'Normal']);
        factory(\App\Models\TypeInvoice::class)->create(['name' => 'Comisión']);

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
           CREANDO TIPOS DE MONEDA
        =============================================*/
        factory(\App\Models\Currency::class)->create(['code' => 'USD']);
        factory(\App\Models\Currency::class)->create(['code' => 'COP']);

        /*=============================================
           CREANDO 20 ACUERDOS COMERCIALES
        =============================================*/
        factory(\App\Models\TradeAgreement::class, 20)->create();

        factory(\App\Models\Invoice::class, 20)->create();
    }
}
