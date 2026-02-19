<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class MachineExport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public  $data;
    public function __construct($datas)
    {
        $this->data = $datas;
    }
    public function  view(): View
    {
        $datas = $this->data;
        return view('pages.report.sales_report.machineExportReport', compact('datas'));
    }
}
