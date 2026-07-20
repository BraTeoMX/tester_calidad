<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReporteScreenResumenSheetExport implements FromView, WithTitle, ShouldAutoSize
{
    protected $resumenGeneral;
    protected $rangoFechasTexto;

    public function __construct(array $resumenGeneral, string $rangoFechasTexto)
    {
        $this->resumenGeneral = $resumenGeneral;
        $this->rangoFechasTexto = $rangoFechasTexto;
    }

    public function view(): View
    {
        return view('ScreenPlanta2.exports.excel_resumen', [
            'resumenGeneral'   => $this->resumenGeneral,
            'rangoFechasTexto' => $this->rangoFechasTexto,
        ]);
    }

    public function title(): string
    {
        return 'Resumen General';
    }
}
