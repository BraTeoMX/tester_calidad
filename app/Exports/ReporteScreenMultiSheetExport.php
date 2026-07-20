<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ReporteScreenMultiSheetExport implements WithMultipleSheets
{
    protected $datosReporte;
    protected $rangoFechasTexto;

    public function __construct(array $datosReporte, string $rangoFechasTexto)
    {
        $this->datosReporte = $datosReporte;
        $this->rangoFechasTexto = $rangoFechasTexto;
    }

    public function sheets(): array
    {
        $sheets = [];

        // 1. Hoja de Resumen General
        if (!empty($this->datosReporte['resumenGeneral'])) {
            $sheets[] = new ReporteScreenResumenSheetExport(
                $this->datosReporte['resumenGeneral'],
                $this->rangoFechasTexto
            );
        }

        // 2. Hojas por Máquina
        if (!empty($this->datosReporte['reportePorMaquina'])) {
            foreach ($this->datosReporte['reportePorMaquina'] as $nombreMaquina => $dataMaquina) {
                $sheets[] = new ReporteScreenMaquinaSheetExport(
                    $nombreMaquina,
                    $dataMaquina,
                    $this->rangoFechasTexto
                );
            }
        }

        return $sheets;
    }
}
