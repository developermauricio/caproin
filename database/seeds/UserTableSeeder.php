<?php

use Illuminate\Database\Seeder;
use \Spatie\Permission\Models\Role;
use \Spatie\Permission\Models\Permission;
use \App\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name'=> 'Administrador']);
        Role::create(['name'=> 'Cliente']);
        Role::create(['name'=> 'Proveedor']);

//        $user = new User;
//        $user->name = 'admin';
//        $user->email = 'mauricio.gutierrez@creategicalatina.com';
//        $user->password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
//        $user->save();
    }
}
