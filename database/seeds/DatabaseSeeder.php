<?php

use Illuminate\Database\Seeder;

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
        factory(\App\Models\IdentificationType::class)->create(['name' => 'Cédula de Pasaporte']);

        /*=============================================
           CREANDO CARGOS O POSICIÓN EMPRESA
        =============================================*/
        factory(\App\Models\CustomerPosition::class)->create(['name' => 'Gerente']);
        factory(\App\Models\CustomerPosition::class)->create(['name' => 'Presidente']);
        factory(\App\Models\CustomerPosition::class)->create(['name' => 'CEO']);
        factory(\App\Models\CustomerPosition::class)->create(['name' => 'Otro']);

        /*=============================================
           CREANDO TIPOS DE CLIENTES
        =============================================*/
        factory(\App\Models\CustomerType::class)->create(['name' => 'Persona Natural']);
        factory(\App\Models\CustomerType::class)->create(['name' => 'Juridica']);
        factory(\App\Models\CustomerType::class)->create(['name' => 'Otro']);

        /*=============================================
           CREANDO CATEGORIAS CLIENTES
        =============================================*/
        factory(\App\Models\CustomerCategory::class)->create(['name' => 'Alimentos']);
        factory(\App\Models\CustomerCategory::class)->create(['name' => 'Restaurantes']);
        factory(\App\Models\CustomerCategory::class)->create(['name' => 'Comunicación']);
        factory(\App\Models\CustomerCategory::class)->create(['name' => 'Educación']);
        factory(\App\Models\CustomerCategory::class)->create(['name' => 'Financiera']);

        /*=============================================
          CREAMOS EL ADMINISTRADOR DEL SISTEMA
        =============================================*/
        factory(\App\User::class, 1)->create([
            'name' => 'Admin',
            'last_name' => 'Sistema',
            'email' => 'admin@admin.co',
        ])->each(function (\App\User $u) {
            $u->roles()->attach([1]);
        });

        /*=============================================
            CREAMOS 20 CLIENTES COMO PERSONA NATURAL
        =============================================*/
        factory(\App\User::class, 20)->create()
            ->each(function (\App\User $u) {
                $u->roles()->attach([2]);
                factory(\App\Models\Customer::class, 1)->create(['user_id' => $u->id, 'customer_type_id' => 2])
                    ->each(function (\App\Models\Customer $cn) use ($u) {
                    });
            });
    }
}
