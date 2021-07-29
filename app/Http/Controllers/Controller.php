<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Company;
use App\Models\Country;
use App\Models\CustomerCategory;
use App\Models\CustomerPosition;
use App\Models\CustomerType;
use App\Models\IdentificationType;
use App\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getCountries(){
        $getCountries = Country::all();
        return response()->json(['data' => $getCountries]);
    }

    public function getCities($country)
    {
        $getCities = City::where('country_code', $country)->get();
        return response()->json(['data' => $getCities]);
    }

    public function validateEmail($email)
    {
        $check = User::whereEmail($email)->first();
        if ($check) {
            return response()->json('El correo electrónico ya ha sido registrado, por favor ingrese otro', 200);
        }
    }

    public function validateEmailCompany($email)
    {
        $check = Company::whereEmail($email)->first();
        if ($check) {
            return response()->json('El correo electrónico ya ha sido registrado, por favor ingrese otro', 200);
        }
    }

    public function getIdentificationType()
    {
        $getIdentificationType = IdentificationType::all();
        return response()->json(['data' => $getIdentificationType]);
    }

    public function customerCategory(){
        $getCustomerType = CustomerCategory::all();
        return response()->json(['data' => $getCustomerType]);
    }

    public function customerPosition(){
        $getCustomerPosition = CustomerPosition::all();
        return response()->json(['data' => $getCustomerPosition]);
    }
}
