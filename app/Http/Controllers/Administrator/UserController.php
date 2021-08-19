<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Mail\User\NewUser;
use App\Models\BranchOffice;
use App\Models\Employee;
use App\Models\TypeEmployee;
use App\Traits\MessagesException;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Rap2hpoutre\FastExcel\FastExcel;

class UserController extends Controller
{
    use MessagesException;

    public function index()
    {
        return view('admin.user.list-users');
    }

    public function getApiUsers()
    {
        $users = User::role(['Logistica', 'Asistente Sucursal', 'Gerencia', 'Vendedor', 'Finanzas'])->with('roles')->with('identificationType', 'employes.branchOffices', 'employes.typeEmploye')->get();
        return datatables()->of($users)->toJson();
    }

    public function getBranchOfficeType()
    {
        $getBranchOffices = BranchOffice::all();
        return response()->json(['data' => $getBranchOffices]);
    }

    public function getRol()
    {
        $getRol = Role::whereNotIn('name', ['Cliente', 'Proveedor'])->get();
        return response()->json(['data' => $getRol]);
    }

    public function getTypeUser()
    {
        $getTypeEmploye = TypeEmployee::all();
        return response()->json(['data' => $getTypeEmploye]);
    }

    public function storeApiUser(Request $request)
    {
        $name = $request->name;
        $last_name = $request->last_name;
        $email = $request->email;
        $phone = $request->phone;
        $branch_office = json_decode($request->branch_office);
        $type_user = json_decode($request->type_user);
        $role = json_decode($request->role);

        $errors = $this->createUser(
            $name,
            $last_name,
            $email,
            $phone,
            [$role->id],
            $type_user->id,
            $branch_office->id
        );

        if (!$errors) {
            return response()->json('Registro Exitoso!');
        }
    }


    private function createUser(
        $name,
        $last_name,
        $email,
        $phone,
        $arrayRolesId,
        $type_employee_id,
        $branch_offices_id
    ) {
        $password = Str::random(8);
        $pass = bcrypt($password);

        DB::beginTransaction();
        try {
            $ramdon = Str::random(10);
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
            $user->roles()->attach($arrayRolesId);
            $employe = Employee::create([
                'user_id' => $user->id,
                'type_employee_id' => $type_employee_id,
                'branch_offices_id' => $branch_offices_id,
            ]);
            Mail::to($user->email)->send(new NewUser($user->name, $password, $user->email));
            DB::commit();
            return null;
        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception;
        }
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

    public function importDataUser(Request $request)
    {
        $file = $request->file('archive');
        $total = 0;
        $lines = collect();
        (new FastExcel())->import($file, function ($line) use (&$lines, &$total) {
            $total += 1;
            $name = $line['nombres'];
            $last_name = $line['apellidos'];
            $email = $line["correo"];
            $phone = $line["teléfono"];
            $roles = explode(',', $line["roles"]);
            $type_user_id = $line["tipo usuario"];
            $branch_office_id = $line["oficina"];

            $exception = $this->createUser(
                $name,
                $last_name,
                $email,
                $phone,
                $roles,
                $type_user_id,
                $branch_office_id
            );

            if ($exception) {
                $line['error'] = $this->parseException($exception);
                $lines->add($line);
            }
        });


        if (!isset($lines[0])) {
            return redirect()->back()->with('status', "Transacción realizada existosamente");
        } else {
            $errors = $lines->count();
            $success = $total - $errors;
            if ($success > 0) {
                return back()
                    ->with('error', $errors . " datos no se importaron correctamente")
                    ->with('status', $success . " datos se importaron correctamente")
                    ->with('lines', $lines);
            } else {
                return back()
                    ->with('error', "Ningún dato se ha importado correctamente")
                    ->with('lines', $lines);
            }
        }
    }
}
