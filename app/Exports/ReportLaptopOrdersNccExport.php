<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMapping;
use Yajra\DataTables\Exports\DataTablesCollectionExport;
 
class ReportLaptopOrdersNccExport extends DataTablesCollectionExport implements WithMapping {
    public function headings(): array
    {
        return [
            'Nhà cung cấp 1',
            'Doanh số',
            'Đơn hàng',
            'AVO',
            'Số lượt xem của nhà cung cấp',
            'Số lượng User xem',
            'Số trang 01 User xem',
            'Tỷ lệ chuyển đổi đơn hàng vs Page view',
            'Số lượng đơn hàng của CTV nội bộ',
            'Số lượng đơn hàng App users',
            '% User App vs CTV nội bộ'
        ];
    }
 
    public function map($row): array
    {
        return [
            $row['agent_id'],
            $row['amount_delivered'],
            $row['count_delivered'],
            $row['avo'],
            $row['page_view'],
            $row['page_view_user'],
            $row['page_view_user_per_page_view'],
            $row['cr'],
            $row['emp_count'],
            $row['app_users_count'],
            $row['userapp_ctv'],
        ];
    }
}