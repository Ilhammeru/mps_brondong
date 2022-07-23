<?php

namespace App\Exports;

use App\Models\Department;
use App\Models\Division;
use App\Models\Position;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class EmployeeTemplateExport implements FromView, WithMultipleSheets
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view():View
    {
        return view('employees.template', [
            'division' => Division::all(),
            'department' => Department::all(),
            'position' => Position::all()
        ]);
    }

    public function sheets(): array
    {
        $static = [
            new EmployeeTemplateSheet(),
            new EmployeeTemplateDivision(),
            new EmployeeTemplatePosition(),
            new EmployeeTemplateStatus()
        ];
        $sheets = [];
        for ($a  = 0; $a < count($static); $a++) {
            $sheets[] = $static[$a];
        }
        return $sheets;
    }
}
