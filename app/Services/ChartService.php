<?php

namespace App\Services;
use Illuminate\Support\Facades\DB;

class ChartService
{
    public function getDataChart30DaysAgo(){
        $before = 30;
        $sql = 'select
        COUNT( IF ( B.LOAIXE = "XE MAY", 1, null )) as XEMAY,
        COUNT( IF ( B.LOAIXE = "XE OTO", 1, null )) as XEOTO,
        COUNT( B.LOAIXE ) as TOTAL,
        SUM(IF ( B.LOAIXE = "XE MAY", B.total_amount, 0 )) as REVENUE_XEMAY,
        SUM(IF ( B.LOAIXE = "XE OTO", B.total_amount, 0 )) as REVENUE_XEOTO,
        SUM( B.total_amount) as REVENUE,
        B.date
        from
        (
        SELECT
        IF(hdi_orders.insur_request_data LIKE \'%"vhGroup":"XE_MAY"%\',"XE MAY","XE OTO") AS "LOAIXE",
        hdi_list_certificate.total_amount,
        DATE_FORMAT(hdi_list_certificate.date_created,"%d-%m-%Y") as date
        FROM hdi_list_certificate
        LEFT JOIN hdi_orders
        ON hdi_list_certificate.order_id = hdi_orders.order_id
        where DATE(hdi_list_certificate.date_created) BETWEEN DATE_SUB(CURDATE(), INTERVAL '.$before .' DAY) AND DATE_SUB(CURDATE(), INTERVAL 1 DAY)
        ORDER BY hdi_list_certificate.date_created
        )
        AS B
        Group By B.date
        ORDER BY STR_TO_DATE(B.date, "%d-%m-%Y")';
        // var_dump($sql);die;
        $result = DB::connection('mysql2')->select(DB::raw($sql));
        return $result;
    }

    public function getDataChartADayAgo(){
        $sql = 'select
        COUNT( IF ( B.LOAIXE = "XE MAY", 1, null )) as XEMAY,
        COUNT( IF ( B.LOAIXE = "XE OTO", 1, null )) as XEOTO,
        COUNT( B.LOAIXE ) as TOTAL,
        SUM(IF ( B.LOAIXE = "XE MAY", B.total_amount, 0 )) as REVENUE_XEMAY,
        SUM(IF ( B.LOAIXE = "XE OTO", B.total_amount, 0 )) as REVENUE_XEOTO,
        SUM( B.total_amount) as REVENUE,
        B.date
        from
        (
        SELECT
        IF(hdi_orders.insur_request_data LIKE \'%"vhGroup":"XE_MAY"%\',"XE MAY","XE OTO") AS "LOAIXE",
        hdi_list_certificate.total_amount,
        DATE_FORMAT(hdi_list_certificate.date_created,"%d-%m-%Y") as date
        FROM hdi_list_certificate
        LEFT JOIN hdi_orders
        ON hdi_list_certificate.order_id = hdi_orders.order_id
        where DATE(hdi_list_certificate.date_created) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)
        )
        AS B
        Group By B.date';
        
        // dd($sql);
        $result = DB::connection('mysql2')->select(DB::raw($sql));
        return $result;
    }
}