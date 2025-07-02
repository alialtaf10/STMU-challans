<?php

// app/Http/Controllers/StudentController.php

namespace App\Http\Controllers;

use App\Imports\StudentsImport;
use App\Models\FeeType;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class StudentImportController extends Controller
{
    public function showImportForm()
    {
        $feeTypes = FeeType::where('status', 1)->get();
        return view('import.form', compact('feeTypes'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'program' => 'required|in:BSCS,BSAI,BSSE,BSCyS',
            'fee_type_id' => 'required|exists:fee_types,id',
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        try {
            Excel::import(
                new StudentsImport($request->fee_type_id, $request->program),
                $request->file('file')
            );

            return back()->with('success', 'Students imported successfully!');
        } catch (\Exception $e) {
            dd($e);
        }
    }
}