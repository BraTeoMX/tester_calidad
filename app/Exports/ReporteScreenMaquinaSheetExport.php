<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReporteScreenMaquinaSheetExport implements FromView, WithTitle, ShouldAutoSize
{
    protected $nombreMaquina;
    protected $dataMaquina;
    protected $rangoFechasTexto;

    public function __construct(string $nombreMaquina, array $dataMaquina, string $rangoFechasTexto)
    {
        $this->nombreMaquina = $nombreMaquina;
        $this->dataMaquina = $dataMaquina;
        $this->rangoFechasTexto = $rangoFechasTexto;
    }

    public function view(): View
    {
        return view('ScreenPlanta2.exports.excel_maquina', [
            'nombreMaquina'    => $this->nombreMaquina,
            'registros'        => $this->dataMaquina['registros'] ?? [],
            'resumen'          => $this->dataMaquina['resumen'] ?? null,
            'rangoFechasTexto' => $this->rangoFechasTexto,
        ]);
    }

    public function title(): string
    {
        // Limpiar caracteres no permitidos en nombres de pestañas de Excel (\, /, ?, *, :, [, ])
        $cleanTitle = str_replace(['\\', '/', '?', '*', ':', '[', ']'], '', $this->nombreMaquina);
        // Truncar a máximo 31 caracteres
        return mb_substr($cleanTitle, 0, 31);
    }
}
