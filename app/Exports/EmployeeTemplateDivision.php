<?php

namespace App\Exports;

use App\Models\Division;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class EmployeeTemplateDivision implements FromView, WithTitle, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        return view('employees.template-division', [
            'division' => Division::all()
        ]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Divisi';
    }
}
