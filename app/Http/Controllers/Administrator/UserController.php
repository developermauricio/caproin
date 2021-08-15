<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Mail\User\NewUser;
use App\Models\BranchOffice;
use App\Models\Employee;
use App\Models\TypeEmployee;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(){
        return view('admin.user.list-users');
    }

    public function getApiUsers()
    {
        $users = User::role(['Logistica', 'Asistente Sucursal', 'Gerencia', 'Vendedor', 'Finanzas'])->with('roles')->with('identificationType', 'employes.branchOffices', 'employes.typeEmploye')->get();
        return datatables()->of($users)->toJson();
    }

    public function getBranchOfficeType(){
        $getBranchOffices = BranchOffice::all();
        return response()->json(['data' => $getBranchOffices]);
    }

    public function getRol(){
        $getRol = Role::whereNotIn('name', ['Cliente', 'Proveedor'])->get();
        return response()->json(['data' => $getRol]);
    }

    public function getTypeUser(){
        $getTypeEmploye = TypeEmployee::all();
        return response()->json(['data' => $getTypeEmploye]);
    }

    public function storeApiUser(Request $request)
    {
        $ramdon = Str::random(10);
        $password = Str::random(8);
        $pass = bcrypt($password);

        $name = $request->name;
        $last_name = $request->last_name;
        $email = $request->email;
        $phone = $request->phone;
        $branch_office = json_decode($request->branch_office);
        $type_user = json_decode($request->type_user);
        $role = json_decode($request->role);


        $slug = Str::slug($name . '-' . $ramdon, '-');

        $user = User::create([
            "name" => $name,
            "last_name" => $last_name,
            "email" => $email,
            "password" => $pass,
            "slug" => $slug,
            "picture" => '/images/user-profile.png',
            "phone" => $phone,
        ]);
        $user->roles()->attach([$role->id]);

        $employe = Employee::create([
            'user_id' => $user->id,
            'type_employee_id' => $type_user->id,
            'branch_offices_id' => $branch_office->id,
        ]);
        Mail::to($user->email)->send(new NewUser($user->name, $password, $user->email));
        return response()->json('Registro Exitoso!');
    }

    public function getApiDataUser($id)
    {
        $user = User::where('id', $id)->with('roles')->with('identificationType', 'employes.branchOffices', 'employes.typeEmploye')->first();
        return response()->json(['data' => $user]);
    }

    public function updateApiUser(Request $request)
    {

        $ramdon = Str::random(10);

        $name = $request->name;
        $last_name = $request->last_name;
        $email = $request->email;
        $phone = $request->phone;
        $user_id = $request->id_user;
        $branch_office = json_decode($request->branch_office);
        $type_user = json_decode($request->type_user);
        $role = json_decode($request->role);
        $state = json_decode($request->state);


        $slug = Str::slug($name . '-' . $ramdon, '-');

        $user = User::where('id', $user_id)->update([
            "name" => $name,
            "last_name" => $last_name,
            "email" => $email,
            "slug" => $slug,
            "picture" => '/images/user-profile.png',
            "phone" => $phone,
            "state" => $state->id,
        ]);
        $userRole = User::find($user_id);
        $userRole->syncRoles($role->name);

        $employe = Employee::where('user_id', $user_id)->update([
            'user_id' => $userRole->id,
            'type_employee_id' => $type_user->id,
            'branch_offices_id' => $branch_office->id,
        ]);
    }

}
