<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ExcelExport implements WithMultipleSheets
{
    use Exportable;

    protected $sheetData;
    protected $exportClassSheet;
    
    public function __construct($sheetData, $exportClassSheet)
    {
        $this->sheetData = $sheetData;
        $this->exportClassSheet = $exportClassSheet;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->sheetData as $key => $value) {
            $sheets[] = new $this->exportClassSheet($value);
        }

        return $sheets;
    }
}
