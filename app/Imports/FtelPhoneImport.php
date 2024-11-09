<?php

namespace App\Imports;

use Excel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class FtelPhoneImport implements ToCollection, WithBatchInserts, WithValidation
{
    use Importable;

    public function collection(Collection $rows)
    {
        return $rows;
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function rules(): array
    {
        return [
            '*' => [
                function ($attribute,$value, $fail){
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
