<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;

class ExcelController extends Controller
{
    public function download(Request $request) {
        $lines = collect($request->input('lines'));

        $lines = $lines->map(function ($line){
            $line = collect($line)->map(function ($attribute){
                $type = gettype($attribute);
                if ($type === "integer" || $type === "string") {
                    return $attribute;
                }
                if (isset($attribute['date'])) {
                    return $attribute['date'];
                }
            });
            return $line->toArray();
        })->toArray();

        return (new FastExcel($lines))->download($request->input('name').'.xlsx');
    }
}
