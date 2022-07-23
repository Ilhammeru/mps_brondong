<?php

namespace App\Exports;

use App\Models\Division;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class EmployeeTemplateSheet implements FromView, WithTitle, ShouldAutoSize, WithColumnWidths
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        return view('employees.template');
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Master';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 30,
            'C' => 20,
            'D' => 15,
            'E' => 20            
        ];
    }
}
