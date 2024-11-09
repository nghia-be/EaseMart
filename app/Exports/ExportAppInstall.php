<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Sheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

use App\Models\App_install_logs;

class ExportAppInstall implements FromView, ShouldAutoSize, WithEvents
{
    private $group_by;
    private $from;
    private $to;

    public function __construct($data)
    {
        $this->group_by = $data['group_by'];
        $this->from = $data['from'];
        $this->to = $data['to'];
    }

    /**
     * @return Builder
     */

    public function view(): View
    {
        return view('export.app_install', [
            'data'          => $this->getData(),
            // 'branch_name'   => $this->branch_name
        ]);
    }

    /**
     * @return array
     */
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

        $dataCellRange = 'A1:C' . $event->sheet->getHighestRow();
        $event->sheet->styleCells($dataCellRange, [
            'borders'               => [
                'allBorders'        => [
                    'borderStyle'   => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color'         => ['argb' => '000000'],
                ]
            ]
        ]);

        $event->sheet->styleCells('A1:C2', [
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

    public function getData() {
        $data = [];
        switch($this->group_by) {
            case 'week':
                $raw_data = App_install_logs::selectRaw('date_report, WEEK(date_report, 1) AS week_number, JSON_EXTRACT(`data`, "$.list_data") AS list_data')
                                    ->whereBetween('date_report', [date('Y-m-d H:i:s', strtotime($this->from . ' 00:00:00')), date('Y-m-d H:i:s', strtotime($this->to . ' 23:59:59'))])
                                    ->get()
                                    ->groupBy('week_number');
                foreach($raw_data as $key => $value) {
                    $countInstallWithContract = 0;
                    $countInstallWithOutContract = 0;
                    foreach($value as $childData) {
                        $list_data = (!empty($childData['list_data'])) ? json_decode($childData['list_data']) : [];
                        $countInstallWithContract += (!empty($list_data[0])) ? $list_data[0] : 0;
                        $countInstallWithOutContract += (!empty($list_data[1])) ? $list_data[1] : 0;
                    }
                    $data[] = [
                        'date_report'                   => "Tuần " . @$value[0]['week_number'] . " (" . date('d-m-Y', strtotime(@$value[0]['date_report'])) . ' - ' . date('d-m-Y', strtotime(@$value[count($value) - 1]['date_report'])) . ")",
                        'countInstallWithContract'      => $countInstallWithContract,
                        'countInstallWithOutContract'   => $countInstallWithOutContract
                    ];
                }
                
                break;
            case 'month':
                $raw_data = App_install_logs::selectRaw('date_report, MONTH(date_report) AS month_number, JSON_EXTRACT(`data`, "$.list_data") AS list_data')
                                    ->whereBetween('date_report', [date('Y-m-d H:i:s', strtotime($this->from . ' 00:00:00')), date('Y-m-d H:i:s', strtotime($this->to . ' 23:59:59'))])
                                    ->get()
                                    ->groupBy('month_number');
                foreach($raw_data as $key => $value) {
                    $countInstallWithContract = 0;
                    $countInstallWithOutContract = 0;
                    foreach($value as $childData) {
                        $list_data = (!empty($childData['list_data'])) ? json_decode($childData['list_data']) : [];
                        $countInstallWithContract += (!empty($list_data[0])) ? $list_data[0] : 0;
                        $countInstallWithOutContract += (!empty($list_data[1])) ? $list_data[1] : 0;
                    }
                    $data[] = [
                        'date_report'                   => "Tháng " . @$value[0]['month_number'] . " (" . date('d-m-Y', strtotime(@$value[0]['date_report'])) . ' - ' . date('d-m-Y', strtotime(@$value[count($value) - 1]['date_report'])) . ")",
                        'countInstallWithContract'      => $countInstallWithContract,
                        'countInstallWithOutContract'   => $countInstallWithOutContract
                    ];
                }
                break;
            default:
                $data = App_install_logs::selectRaw('*, JSON_EXTRACT(`data`, "$.list_data") AS `list_data`')->whereBetween('date_report', [date('Y-m-d H:i:s', strtotime($this->from . ' 00:00:00')), date('Y-m-d H:i:s', strtotime($this->to . ' 23:59:59'))])->get()->toArray();
                foreach($data as $key => &$value) {
                    $list_data = $list_data = (!empty($value['list_data'])) ? json_decode($value['list_data']) : [];
                    $value['date_report'] = !empty($value['date_report']) ? date('d-m-Y', strtotime($value['date_report'])) : $value['date_report'];
                    $value['countInstallWithContract'] = $list_data[0];
                    $value['countInstallWithOutContract'] = $list_data[1];
                }
        }
        return $data;
    }

}
