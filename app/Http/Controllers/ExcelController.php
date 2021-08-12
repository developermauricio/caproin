<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;

class ExcelController extends Controller
{
    public function download(Request $request) {
        $lines = collect($request->input('lines'));
        return (new FastExcel($lines))->download($request->input('name').'.xlsx');
    }
}
