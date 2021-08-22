<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Mail\User\NewUser;
use App\Models\BranchOffice;
use App\Models\Employee;
use App\Models\TypeEmployee;
use App\Traits\MessagesException;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Rap2hpoutre\FastExcel\FastExcel;

class UserController extends Controller
{
    use MessagesException;

    private $roles = null;

    public function __construct()
    {
        $this->roles = Role::whereIn('name', ['Logistica', 'Asistente Sucursal', 'Gerencia', 'Vendedor', 'Finanzas'])->get();
    }

    public function index()
    {
        $branchOffices = BranchOffice::all(['id', 'name', 'code']);
        $employeeTypes = TypeEmployee::all(['id', 'name']);
        $roles = $this->roles;
        return view('admin.user.list-users', compact(['branchOffices', 'employeeTypes', 'roles']));
    }

    public function getApiUsers()
    {
        $users = User::role(['Logistica', 'Asistente Sucursal', 'Gerencia', 'Vendedor', 'Finanzas', 'Administrador'])->with('roles')->with('identificationType', 'employes.branchOffices', 'employes.typeEmploye')->get();
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
//        $name = $request->name;
//        $last_name = $request->last_name;
//        $email = $request->email;
//        $phone = $request->phone;
//        $branch_office = json_decode($request->branch_office);
//        $type_user = json_decode($request->type_user);
//        $role = json_decode($request->role);
//
//        $errors = $this->createUser(
//            $name,
//            $last_name,
//            $email,
//            $phone,
//            [$role->id],
//            $type_user->id,
//            $branch_office->id
//        );
//
//        if (!$errors) {
//            return response()->json('Registro Exitoso!');
//        }
    }

    private function getRoles($roles)
    {
        $roles = collect($roles);
        return $this->roles->filter(function ($role) use ($roles) {
            return $roles->contains($role->id);
        })->map(function ($role) {
            return $role->id;
        });
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
            $roles = $this->getRoles($arrayRolesId);
            if (!isset($roles[0])) {
                DB::rollBack();
                return new \Exception("Rol invalido", "-1");
            }
            $user->roles()->attach($roles);
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
        try {
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
                    $line['error'] = $this->parseException($exception, $line);
                    $lines->add($line);
                }
            });
        } catch (\Throwable $th) {
            $line['error'] = "El excel es invalido (".$th->getMessage().")";
            $lines->add($line);
        }


        if (!isset($lines[0])) {
            return redirect()->back()->with('status', "Transacción realizada existosamente");
        } else {
            $errors = $lines->count();
            $success = $total - $errors;
            if ($success > 0) {
                return back()
                    ->with('error', $errors . " datos no se importaron correctamente. Quizás ya estan registrados o el correo electrónico y identificación ya se encuentra registrado. Asegurate que los datos del reporte o tabla, no esten registrados o no esten duplicados.")
                    ->with('status', $success . " datos se importaron correctamente")
                    ->with('lines', $lines);
            } else {
                return back()
                    ->with('error', "Ningún dato se ha importado correctamente. Quizás ya estan registrados o el correo electrónico y identificación ya se encuentra registrado. Asegurate que los datos del reporte o tabla, no esten registrados o no esten duplicados.")
                    ->with('lines', $lines);
            }
        }
    }

    public function updateApiPasswordUser(Request $request)
    {

        $this->validate($request, [
            'password' => ['required', 'confirmed', 'min:8', 'regex:/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{8,}/'],
        ], [
            'regex' => 'La contraseña debe contener por lo menos un número, una mayuscula, una minuscula y un caracter especial'
        ]);


        $pass = bcrypt($request->password);
        $user = User::where('id', $request->user_id)->update([
            'password' => $pass
        ]);

        return response()->json('Contraseña actualizada correctamente');
    }
}
