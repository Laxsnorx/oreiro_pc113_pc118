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
        // For testing: hardcode student ID (must exist in your DB!)


        return Excel::download(new StudentExport(), 'grades.xlsx');
    }
    

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);
    
        Excel::import(new StudentsImport, $request->file('file'));
    
        return response()->json(['message' => 'Grades imported successfully!']);
    }
}

