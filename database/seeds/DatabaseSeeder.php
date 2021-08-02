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
        Storage::makeDirectory('users');

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
          CREAMOS EL ADMINISTRADOR DEL SISTEMA
        =============================================*/
        $admin = factory(\App\User::class)->create([
            'name' => 'Admin',
            'last_name' => 'Sistema',
            'email' => 'admin@admin.co',
        ]);
        $admin->roles()->attach([1]);

        /*=============================================
           CREANDO LOS TIPO DE CONTRATO DEL EMPLEADO
        =============================================*/
        factory(\App\Models\TypeEmployee::class)->create(['name' => 'Contratista']);
        factory(\App\Models\TypeEmployee::class)->create(['name' => 'Empleado']);

        /*=============================================
           CREANDO LAS SUCURSALES
        =============================================*/
        factory(\App\Models\BranchOffice::class)->create(['name' => 'Sucursal 1']);
        factory(\App\Models\BranchOffice::class)->create(['name' => 'Sucursal 2']);
        factory(\App\Models\BranchOffice::class)->create(['name' => 'Sucursal 3']);

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
           CREANDO LOS TIPO DE PROVEEDORES DEL EMPLEADO
        =============================================*/
        factory(\App\Models\TypeProvider::class)->create(['name' => 'Fabricante']);
        factory(\App\Models\TypeProvider::class)->create(['name' => 'Servicio']);

        /*=============================================
            CREAMOS 10 PROVEEDORES
        =============================================*/
        factory(\App\User::class, 10)->create()
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
        factory(\App\Models\TypeProduct::class)->create(['name' => 'Tipo 1']);
        factory(\App\Models\TypeProduct::class)->create(['name' => 'Tipo 2']);
        factory(\App\Models\TypeProduct::class)->create(['name' => 'Tipo 3']);

        /*=============================================
            PRODUCTOS
        =============================================*/
        factory(\App\Models\Product::class, 50)->create();
        /*=============================================
            CREAMOS 20 CLIENTES COMO PERSONA NATURAL
        =============================================*/
//        factory(\App\User::class, 20)->create()
//            ->each(function (\App\User $u) {
//                $u->roles()->attach([2]);
//                factory(\App\Models\Customer::class, 1)->create(['user_id' => $u->id, 'customer_type_id' => 2])
//                    ->each(function (\App\Models\Customer $cn) use ($u) {
//                    });
//            });
    }
}
