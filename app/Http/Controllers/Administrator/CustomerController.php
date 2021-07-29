<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerType;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(){
        return view('admin.customer.list-customers');
    }

    public function indexCreateCustomer(){
        return view('admin.customer.create-customer');
    }
    public function getApiCustomersType(){
        $getCustomerType = CustomerType::all();
        return response()->json(['data' => $getCustomerType]);
    }
    public function getApiCustomers(){
        $customer = Customer::with('user.country', 'user.city', 'user.identificationType', 'customerPosition', 'customerCategory', 'customerType')->get();
        return datatables()->of($customer)->toJson();
    }
}
