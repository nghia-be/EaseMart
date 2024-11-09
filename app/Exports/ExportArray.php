<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Sheet;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportArray implements FromArray, ShouldAutoSize, WithHeadings, WithEvents
{
    use Exportable;
    private $headers;
    private $data;

    public function __construct(array $headers, array $data)
    {
        $this->headers = $headers;
        $this->data = $data;
    }

    /**
     * @return Builder
     */
    public function array(): array {
        return $this->data;
    }

    public function headings(): array {
        return $this->headers;
    }

    public function registerEvents(): array
    {
        return [
            // array callable, refering to a static method.
            AfterSheet::class => [self::class, 'afterSheet'],
        ];
    }

    public static function afterSheet(AfterSheet $event)
    {
        Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
            $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
        });
        $dataCellRange = 'A1:' . $event->sheet->getHighestColumn() . $event->sheet->getHighestRow();
        $event->sheet->styleCells($dataCellRange, [
            'borders'               => [
                'allBorders'        => [
                    'borderStyle'   => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color'         => ['argb' => '000000'],
                ]
            ]
        ]);

        $event->sheet->styleCells('A1:' . $event->sheet->getHighestColumn() . '1', [
            'fill'                  => [
                'fillType'          => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color'             => [
                    'argb'          => 'B6D7A8'
                ]
            ],
            'font'                  => [
                'bold'              => true
            ]
        ]);
    }
}
