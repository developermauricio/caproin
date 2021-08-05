<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BranchOfficesController extends Controller
{
    public function index(){
        return view('admin.branchOffice.list-brach-offices');
    }
}
