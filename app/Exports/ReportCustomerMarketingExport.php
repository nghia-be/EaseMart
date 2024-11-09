<?php

namespace App\Exports;

// use App\Invoice;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Models\ReportCustomerMarketing;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportCustomerMarketingExport implements FromQuery, WithHeadings
{
    use Exportable;

    public function __construct(int $import_id) {
        $this->import_id = $import_id;
    }

    public function query()
    {
        return ReportCustomerMarketing::query()->select(['customer_id', 'phone', 'contract_no', 'full_name', 'email', 'address', 'location_zone', 'branch_name', 'foxpay_status', 'is_fpt_employee', 'local_type', 'first_date_login', 'date_login'])->where('import_id', $this->import_id);
    }

    public function headings(): array
    {
        return [
            'Mã khách hàng',
            'Số điện thoại',
            'Mã hợp đồng',
            'Tên',
            'Email',
            'Địa chỉ',
            'Vùng',
            'Mã chi nhánh',
            'Trạng thái liên kết ví Foxpay',
            'Có phải là nhân viên FPT',
            'Loại thuê bao',
            'Ngày login đầu tiên',
            'Ngày login gần nhất'
        ];
    }
}
