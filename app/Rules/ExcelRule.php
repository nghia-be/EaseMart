<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\UploadedFile;

class ExcelRule implements Rule
{
    public function __construct()
    {
    }

    public function passes($attribute, $value)
    {
        return in_array($value->getClientOriginalExtension(), ['csv', 'xls', 'xlsx']);
    }

    public function message()
    {
        return 'The excel file must be a file of type: csv, xls, xlsx.';
    }
}
