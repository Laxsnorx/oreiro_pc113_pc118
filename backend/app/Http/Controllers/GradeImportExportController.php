<?php

// app/Http/Controllers/GradeImportExportController.php

// app/Http/Controllers/GradeImportExportController.php

namespace App\Http\Controllers;

use App\Imports\StudentsImport;
use App\Exports\StudentExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class GradeImportExportController extends Controller
{
    public function export(Request $request)
    {
        try {
            return Excel::download(new StudentExport(), 'grades.xlsx');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Export failed: ' . $e->getMessage()], 500);
        }
    }

    public function import(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:xlsx,xls,csv',
            ]);

            Excel::import(new StudentsImport, $request->file('file'));

            return response()->json(['message' => 'Grades imported successfully!']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            return response()->json(['errors' => $e->failures()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Import failed: ' . $e->getMessage()], 500);
        }
    }
}

