<?php

namespace App\Exports;

use Maatwebsite\Excel\Excel;
use App\Repositories\AttendanceRepository;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class AttendanceExport implements
FromCollection,
WithHeadings,
WithMapping,
ShouldAutoSize,
Responsable,
WithColumnFormatting,
WithStyles,
WithCustomStartCell
{

    use Exportable;

    private $fileName = "attendance.xlsx";

    private $writerType = Excel::XLSX;

    private $headers = [
        'Content-Type' => 'text/csv',
    ];

    private $dni;

    private $from;

    private $to;

    public function __construct(string $dni, string $from, string $to)
    {
        $this->dni = $dni;
        $this->from = $from;
        $this->to = $to;
    }

    public function collection()
    {
        return (new AttendanceRepository())->fetchByEntity($this->dni, $this->from, $this->to, 1);
    }

    public function headings(): array
    {
        return [
            "Fecha",
            "Ingreso nro",
            "Hora de ingreso",
            "Estado",
            "Justificación",
        ];
    }

    public function map($row): array
    {
        return [
            $row->created_at->formatLocalized("%d de %B"),
            $row->entry_time,
            $row->state,
            $row->priority,
            empty($row->justification) ? "" : "Envió justificación",
        ];
    }

    public function styles($sheet)
    {
        return [
            2 => ["font" => ["bold" => true]],
        ];
    }

    public function columnFormats(): array
    {
        return [
            "D" => NumberFormat::FORMAT_DATE_TIME1,
        ];
    }

    public function startCell(): string
    {
        return "B2";
    }
}
