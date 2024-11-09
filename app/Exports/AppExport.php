<?php

namespace App\Exports;

use App\Models\AppLog;
use Maatwebsite\Excel\Concerns\WithLimit;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStyles;
use Throwable;
use Yajra\DataTables\Exports\DataTablesCollectionExport;

class AppExport implements FromQuery, WithHeadings,WithColumnWidths, ShouldAutoSize,WithStyles, ShouldQueue,WithLimit
{
    use Exportable;
    // public function __construct(int $type, $dateStart ,$dateEnd)
    // {
    //     $this->type = $type;
    //     $this->start = $dateStart;
    //     $this->end = $dateEnd;
    // }
    public function forCondition($type, $dateStart ,$dateEnd, $filter_duplicate)
    {
        $this->type = $type;
        $this->start = $dateStart;
        $this->end = $dateEnd;
        $this->filter_duplicate = $filter_duplicate;
        return $this;
    }

    public function query()
    {
        $model = DB::table('app_log')->select('id','type','phone','url','date_action')->orderBy('id', 'desc');
        $type = $this->type;
        $start = $this->start ? \Carbon\Carbon::parse($this->start)->format('Y-m-d H:i:s'): null;
        $end = $this->end ? \Carbon\Carbon::parse($this->end)->format('Y-m-d H:i:s'): null;
        if(!empty($type)) {
            $model->where('type', $type);
        }
        if(!empty($end) && !empty($start)) {
            $model->whereBetween('date_action', [$start, $end]);
        }
        if($this->filter_duplicate=='yes') {
            \DB::statement("SET SQL_MODE=''");
            $model->groupBy(['phone','type']);
        }
        return $model;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Loại',
            'Số điện thoại',
            'URL',
            'Ngày tạo'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 10,
            'C' => 20,
            'D' => 100,
            'E' => 30,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]]
        ];
    }

    public function failed(Throwable $exception): void
    {
        dd($exception);
    }

    public function limit(): int
    {
        return 100; // only take 100 rows
    }
}
