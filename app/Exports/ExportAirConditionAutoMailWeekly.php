<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportAirConditionAutoMailWeekly implements FromArray, WithHeadings, WithTitle,WithColumnWidths,WithStyles
{
    use Exportable;

    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'SDT khách hàng',
            'Tên khách hàng',
            'Mã đơn hàng',
            'Loại thanh toán',
            'Trạng thái đơn hàng',
            'Số tiền',
            'Ngày đặt đơn',
            'Ngày hoàn thành',
            'SDT người giới thiệu',
            'Mã người giới thiệu',
            'email',
            'Tên đầy đủ',
            'Đơn vị đầy đủ',
            'Lọc theo chi nhánh'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 25,
            'C' => 20,
            'D' => 10,
            'E' => 10,
            'F' => 10,
            'G' => 20,
            'H' => 20,
            'I' => 15,
            'J' => 15,
            'K' => 40,
            'L' => 20,
            'M' => 30,
            'N' => 10,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1 => ['font' => ['bold' => true]]
        ];
    }

    public function title(): string
    {
        $title = $this->data[0]->branch_name ?? 'Khac';
        return $title;
    }
}
