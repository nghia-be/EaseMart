<?php

namespace App\Imports;

use Excel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Validators\ValidationException;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
// use App\Exceptions\CustomException;

use App\Models\ImportLogReportCustomerInfoMarketingDetail;
use App\Models\ImportLogReportCustomerInfoMarketing;

class ReportCustomerImport implements ToCollection, WithBatchInserts, WithChunkReading, WithEvents, WithHeadingRow, WithValidation
{
    use Importable, RegistersEventListeners;
    protected $limit_rows = 0;
    protected $file_name = '';
    protected $total_rows = 0;
    protected $import_id = null;

    public function __construct($limit_rows, $file_name) {
        $this->limit_rows = $limit_rows;
        $this->file_name = $file_name;
    }

    public function headingRow(): int
    {
        return 1;
    }

    // public function fromFile(string $fileName)
    // {
    //     dd($fileName);
    //     $this->file_name = $fileName;
    //     return $this;
    // }

    public function collection(Collection $rows) {
        foreach($rows as $row) {
            ImportLogReportCustomerInfoMarketingDetail::insert(['phone' => json_decode(json_encode($row['so_dien_thoai']), true), 'import_id' => $this->import_id]);
        }
    }

    public function onFailure(Failure ...$failures) {
        // Handle the failures how you'd like.
        dd($failures);
    }

    public function batchSize(): int
    {
        return $this->limit_rows;
    }

    public function chunkSize(): int
    {
        return $this->limit_rows;
    }

    public function registerEvents(): array
    {
        return [
            // Handle by a closure.
            BeforeImport::class => function(BeforeImport $event) {
                $totalRows = $event->getReader()->getTotalRows();
                $this->total_rows = intval(array_values($totalRows)[0]);

                if($this->total_rows > $this->limit_rows) {
                    throw ValidationException::withMessages(['Limit rows: ' . $this->limit_rows]);
                }

                $this->import_id = ImportLogReportCustomerInfoMarketing::insertGetId(['file_name' => $this->file_name, 'total_row' => $this->total_rows - 1, 'is_runned' => 0]);
            },
		   
            // // Using a class with an __invoke method.
            // BeforeSheet::class => new BeforeSheetHandler(),
            
            // // Array callable, refering to a static method.
            // AfterSheet::class => [self::class, 'afterSheet'],
                        
        ];
    }

    // public function beforeImport(BeforeImport $event)
    // {
    //     $totalRows = $event->getReader()->getTotalRows();
    //     $this->total_rows = intval($totalRows['Sheet 1']);
    // }

    public function rules(): array
    {
        return [
            'phone' => [
                function ($attribute, $value, $fail){
                    $pattern = '/^(03|05|07|08|09)[0-9, ]*$/';
                    if($value == null) {
                        return $fail("Field có giá trị trống, thử xóa hết form và nhập lại đúng định dạng");
                    }
                    if ((strlen($value)!==10)) {
                        return $fail("Trường $value phải đúng 10 kí tự");
                    }
                    if(!preg_match($pattern, $value)) {
                        return $fail("Trường $value sai định dạng số điện thoại Việt Nam");
                    }
                }
            ]
        ];
    }
}
