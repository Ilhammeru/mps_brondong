<?php

namespace App\Exports;

use App\Models\Position;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class EmployeeTemplatePosition implements FromView, WithTitle, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        return view('employees.template-position', [
            'position' => Position::all()
        ]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Jabatan';
    }
}
