<?php

namespace App\Console\Commands;

use App\Services\SendMailService;
use Illuminate\Console\Command;
use App\Exports\ExcelExport;
use App\Services\Minio;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Input\InputArgument;

class AirConditionAutoMailWeekly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     * @support TrinhHDP@fpt.com.vn
     */
    protected $signature = 'reportAirConditionWeekly {date}';

    /**
     * The console command description.
     *
     * @var string
     * @support TrinhHDP@fpt.com.vn
     */
    protected $description = 'Report Air Condition Weekly Is Running!';
    protected string $minioUrl;

    /**
     * Create a new command instance.
     *
     * @return void
     * @support TrinhHDP@fpt.com.vn
     */
    public function __construct()
    {
        parent::__construct();
        $this->minio = new Minio;
        $this->minioUrl = 'http://hi-static.fpt.vn/sys';
    }

    /**
     * @throws \JsonException
     */
    public function sendMailDailyReport($date)
    {
        $report = $this->reportData($date);
        $mailContent = view('emails.weekReport')->with(['data' =>$report, 'date'=>$date])->render();
        $nameService = "[ Hi FPT ] Báo cáo bán hàng cùng HiFPT - Dịch vụ vệ sinh máy lạnh theo tuần";
        $email = [
            'to'    => json_decode(setting('manually_email_list_email'), true)[0]['to'] ?? null,
            'cc'    => json_decode(setting('manually_email_list_email'), true)[0]['cc'] ?? null,
            'bcc'   => json_decode(setting('manually_email_list_email'), true)[0]['bcc'] ?? null,
        ];
        $attach = '';
        if (!empty($report['detailUrl'])) {
            $attach .= $report['detailUrl'];
        }
        $mail = new SendMailService();
        $mail->send($nameService, $mailContent, $email, $attach);
    }

    public function reportData(array $date)
    {
        $reportWeek=$sheetData=[];
        $data = DB::table('air_condition_service as a')
            ->select('a.contact_phone', 'a.contact_name', 'a.id', 'a.payment_method', 'a.state_payment', 'a.total_amount_finish',
                'a.t_checkin', 'a.t_checkout', 'a.referral_code','b.employee_code','b.emailAddress','b.full_name', 'b.organizationCodePath')
            ->leftJoin('employees as b', 'a.referral_code', '=', 'b.phone')
            ->whereBetween('t_checkout', $date)
            ->orderBy('b.organization_branch_code')
            ->get(); // get data 7 day from <air_condition_service> and <employees>

        foreach ($data as $item) {
            $codePath = explode('/', $item->organizationCodePath ?? null);
            $item->branch_name = str_replace('PSG','SG',$codePath[count($codePath)-3] ?? null);
            if (str_contains($item->branch_name,'BIL')){
                $item->branch_name = 'SG'.substr($codePath[count($codePath)-2], 5, 2);
            } else if (str_contains($item->branch_name,'SG')){
            } else if (str_contains($item->organizationCodePath,'HiFPT')){
                $item->branch_name = 'HI FPT';
            } else if (str_contains($item->organizationCodePath,'PNC')){
                $item->branch_name = 'PNCHO';
            } else if (str_contains($item->organizationCodePath,'FAD') || str_contains($item->organizationCodePath,'FTC') || str_contains($item->organizationCodePath,'PMB')){
                $item->branch_name = 'FTEL';
            } else if (empty($item->branch_name) && !empty($item->referral_code)) {
                $item->branch_name = 'Không có dữ liệu nhân viên';
            } else if (empty($item->referral_code)){
                $item->branch_name = 'inApp';
            } else {
                $item->branch_name = 'Chi nhánh Khác';
            }
            $sheetData[$item->branch_name][] = $item;
            //unset($item->organizationCodePath);

        } // filter branch_name -> push to $sheetData by branch_name
        if(!empty($data)) {
            $reportUrl =  '/hifpt/report/air-condition/'.date('Ymd').'/FCONNECT_'.date('Ymd', strtotime('now')). "_" . date('YmdHis') . ".xlsx";
            $exportData = $this->dataDetailExportExcel($sheetData,$reportUrl);
            $uploadResult = Storage::disk('minio')->put($reportUrl, $exportData);
            if ($uploadResult) {
                $reportWeek['detailUrl'] = $this->minioUrl . $reportUrl;
            }
        } // => create file excel by laravel-excel => push to minio excel (http://upload-static.fpt.net/minio/sys/hifpt/report/vsml_detail_week/)

        $reportWeek['total']['turnover']=0;
        $reportWeek['total']['employees']=0;
        foreach ($sheetData as $key => $items) {
            $duplicate=array();
            $reportWeek['data'][$key]['DoanhThu']=0;
            for($i=0;$i<count($items);$i++) {
                if (!empty($items[$i]->employee_code)) {
                    $duplicate[]=$items[$i]->employee_code;
                }
                $reportWeek['total']['turnover']+=$items[$i]->total_amount_finish;
                $reportWeek['data'][$key]['DoanhThu']+=$items[$i]->total_amount_finish;
            }
            $totalEmployee = count(array_unique($duplicate));
            $reportWeek['data'][$key]['key'] = $key;
            $reportWeek['data'][$key]['SoNhanVienThamGia'] = $totalEmployee;
            $reportWeek['data'][$key]['SoDonSuccess'] = count($items);
            $reportWeek['total']['employees'] += $totalEmployee;
        }
        $reportWeek['total']['order'] = $data->count();
        usort($reportWeek['data'], fn($first,$second) => $first['SoDonSuccess'] < $second['SoDonSuccess']); // sort desc by SoDonSuccess
        return $reportWeek;
    }

    public function dataDetailExportExcel($zoneData, $storage_path)
    {
        if (!file_exists(storage_path('app/' . $storage_path))) {
            mkdir(storage_path('app/' . $storage_path), 0777, true);
        }
        return Excel::raw(new ExcelExport($zoneData, 'App\Exports\ExportAirConditionAutoMailWeekly'), \Maatwebsite\Excel\Excel::XLSX);
    }


    /**
     * Execute the console command.
     *
     * @return array[]
     * @support TrinhHDP@fpt.com.vn
     *** Structure code:
     * Run php artisan reportAirConditionWeekly
    * => sendMailDailyReport call reportData
    * => reportData get data 7 day from <air_condition_service> and <employees>
    * => handle data -> export excel to minio
    * => render data view airconditionreport/weekReport
    * => auto send in monday at 7:00AM
     */

    public function handle()
    {
        $this->sendMailDailyReport($this->argument('date'));
    }
}
