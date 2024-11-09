<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Awobaz\Compoships\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Str;

class Hdi_Orders extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 'hdi_orders';
    protected $primaryKey = 'transaction_id';
    protected $fillable = ['transaction_id','customer_id','customer_phone','customer_name','payment_type','order_status','is_created_insur','is_duplicated','amount','payment_id','link_payment','date_created','date_modified','engine_no','chassis_no','number_plate','effective_date','expiration_date','insur_request_data','is_sent_duplicated','referral_phone','promotion_value','promotion_code','is_sent_promotion_result'];

    public function testReadHdi() {
        return DB::connection('mysql3')->select("SELECT * FROM z_bk_autopay");
    }

    public function employees_table() {
        return $this->hasOne(Employees::class, 'phone', 'referral_phone');
    }

    public function reportByTime1($service, $from, $to) {
        switch($service) {
            case 'hdi':
                return DB::connection('mysql3')->select("
                    SELECT *
                    FROM (
                            SELECT 	organizations.zone_name AS 'zone', 
                                    organizations.branch_code AS 'branch_code', 
                                    organizations.branch_name_code AS 'branch_name_code', 
                                    SUM(DATE(hdi.date_created) BETWEEN '2022-05-01 00:00:00' AND '2022-05-31 23:59:59') AS 'count_last_time', 
                                    SUM(IF(DATE(hdi.date_created) BETWEEN '2022-05-01 00:00:00' AND '2022-05-31 23:59:59', amount, 0)) AS 'amount_last_time',
                                    SUM(DATE(hdi.date_created) BETWEEN '2022-06-01 00:00:00' AND '2022-06-30 23:59:59') AS 'count_this_time', 
                                    SUM(IF(DATE(hdi.date_created) BETWEEN '2022-06-01 00:00:00' AND '2022-06-30 23:59:59', amount, 0)) AS 'amount_this_time'
                            FROM hihdi_db.hdi_orders AS hdi
                            JOIN hiadmin_stag_db.employees AS emp
                                ON hdi.referral_phone = emp.phone
                            JOIN hiadmin_stag_db.list_organizations AS organizations
                                ON emp.organizationCode = organizations.code
                            WHERE hdi.date_created BETWEEN '2022-05-01 00:00:00' AND '2022-06-30 23:59:59'
                            GROUP BY organizations.zone_name, organizations.branch_code, organizations.branch_name_code
                            UNION
                            SELECT 	organizations.zone_name AS 'zone', 
                                    NULL,
                                    NULL,
                                    SUM(DATE(hdi.date_created) BETWEEN '2022-05-01 00:00:00' AND '2022-05-31 23:59:59') AS 'count_last_time', 
                                    SUM(IF(DATE(hdi.date_created) BETWEEN '2022-05-01 00:00:00' AND '2022-05-31 23:59:59', amount, 0)) AS 'amount_last_time',
                                    SUM(DATE(hdi.date_created) BETWEEN '2022-06-01 00:00:00' AND '2022-06-30 23:59:59') AS 'count_this_time', 
                                    SUM(IF(DATE(hdi.date_created) BETWEEN '2022-06-01 00:00:00' AND '2022-06-30 23:59:59', amount, 0)) AS 'amount_this_time'
                            FROM hihdi_db.hdi_orders AS hdi
                            JOIN hiadmin_stag_db.employees AS emp
                                ON hdi.referral_phone = emp.phone
                            JOIN hiadmin_stag_db.list_organizations AS organizations
                                ON emp.organizationCode = organizations.code
                            WHERE hdi.date_created BETWEEN '2022-05-01 00:00:00' AND '2022-06-30 23:59:59'
                            GROUP BY organizations.zone_name) AS full_data
                        ORDER BY full_data.zone, full_data.branch_code, full_data.branch_name_code
                        LIMIT 10 OFFSET 0
                ");
                break;
            case 'ict':
            case 'elmich':
            case 'vuanem':
                break;
        }
    }

    public function reportByTime() {
        $mysql3 = config('database.connections.mysql3.database');
        $connection = config('database.connections.mysql3.username') . '@' . config('database.connections.mysql3.host');
        // $data = DB::table('hihdi_db.hdi_orders as hdi')->connection('mysql3')
        //         ->join('hiadmin_stag_db.employees as employees', 'employees.phone', '=', 'hdi.referral_phone')
        //         ->join('hiadmin_stag_db.list_organizations as organizations', 'employees.organizationCode', '=', 'organizations.code');
        $employees = Employees::select('phone', 'organizationCode');
        $output = DB::connection('mysql3')->table('hdi_orders AS hdi')
                    // ->join(DB::connection('mysql')->raw("(SELECT `phone`, `organizationCode` FROM " . config('database.connections.mysql.database') . "employees) AS employees"), function($join) {
                    //     $join->on('hdi.referral_phone', '=', 'employees.phone');
                    // })
                    ->joinSub($employees, 'emp', function($join) {
                        $join->on('hdi.referral_phone', '=', 'emp.phone');
                    })
                    // ->join(DB::connection('mysql')->table('list_organizations as "organizations"')->get(), function($join2) {
                    //     $join2->on('employees.organizationCode', '=', 'organizations.code');
                    // })
                    // ->selectRaw("organizations.zone_name AS 'zone_name', 
                    //             organizations.branch_code AS 'branch_code', 
                    //             organizations.branch_name_code AS 'branch_name_code', 
                    //             SUM(IF(DATE(hdi.date_created) BETWEEN '2022-05-01 00:00:00' AND '2022-05-31 23:59:59', 1, 0)) AS 'count_last_time', 
                    //             SUM(IF(DATE(hdi.date_created) BETWEEN '2022-05-01 00:00:00' AND '2022-05-31 23:59:59', amount, 0)) AS 'amount_last_time',
                    //             SUM(IF(DATE(hdi.date_created) BETWEEN '2022-06-01 00:00:00' AND '2022-06-30 23:59:59', 1, 0)) AS 'count_this_time', 
                    //             SUM(IF(DATE(hdi.date_created) BETWEEN '2022-06-01 00:00:00' AND '2022-06-30 23:59:59', amount, 0)) AS 'amount_this_time'")
                    ->whereBetween('hdi.date_created', ['2022-05-01 00:00:00', '2022-06-30 23:59:59'])
                    // ->groupBy('zone_name', 'branch_code', 'branch_name_code')
                    // ->orderBy('zone_name', 'asc')
                    // ->orderBy('branch_code', 'asc')
                    // ->orderBy('branch_name_code', 'asc')
                    ->get();
        dd($output->toArray());
        return $output;
    }

    public function testCrossData() {
        // // $data = DB::select('select hihdi_db.hdi_orders.customer_phone, hihdi_db.hdi_orders.customer_name, hiadmin_stag_db.employees.phone, hihdi_db.hdi_orders.referral_phone
        // //                     where hihdi_db.hdi_orders.date_created between "2022-05-01 00:00:00" AND "2022-06-30 23:59:59"');
        // $data = DB::table('hihdi_db.hdi_orders as dt1')->leftjoin('hiadmin_stag_db.employees as dt2', 'dt2.phone', '=', 'dt1.referral_phone');        
        // $output = $data->select(['dt1.*','dt2.*'])->whereBetween('dt1.date_created', ["2022-05-01 00:00:00", "2022-06-30 23:59:59"])->get();
        // return $output;

        $databaseName1 = (new Model1())->getConnection()->getDatabaseName();
        $tableName1 = (new Model1())->getTable();
        $tableName2 = (new Model2())->getTable();
    }
    
    public function countReportData($branch, $from, $to) {
        return DB::connection('mysql')->select("
            SELECT COUNT(order_id) AS count, SUM(amount) AS amount
            FROM `hdi_orders`
            WHERE `order_status` = 'SUCCESS' AND `referral_phone` IN
            (
                SELECT Replace(coalesce(`phone`,''), ' ','') as `phone`
                FROM `employees`
                WHERE `phone` != '' AND `phone` IS NOT NULL AND location_id IN ($branch)
            ) AND `date_created` BETWEEN '$from' AND '$to';
        ");
    }

    public function getDetailReportData($branch, $from, $to) {
        return DB::connection('mysql')->select("
            SELECT customer_locations.location_name, hdi_orders.customer_phone, hdi_orders.customer_name, hdi_orders.payment_type, hdi_orders.order_status, hdi_orders.amount, hdi_orders.date_created, hdi_orders.referral_phone, employees.name, employees.full_name, ftel_branch.branch_name
            FROM `hdi_orders`
            LEFT OUTER JOIN employees
                ON employees.phone = hdi_orders.referral_phone
            LEFT OUTER JOIN ftel_branch
                ON employees.location_id=ftel_branch.location_id AND employees.branch_code=ftel_branch.branch_code
            LEFT OUTER JOIN customer_locations
                ON employees.location_id=customer_locations.customer_location_id
            WHERE `order_status` = 'SUCCESS' AND `referral_phone` IN
            (
                SELECT DISTINCT Replace(coalesce(`phone`,''), ' ','') as `phone`
                FROM `employees`
                WHERE `phone` != '' AND `phone` IS NOT NULL AND location_id IN ($branch)
            ) AND hdi_orders.date_created BETWEEN '$from' AND '$to'
            GROUP BY hdi_orders.transaction_id;
        ");
    }

    public function getDetailRefNoEmp($from, $to) {
        return DB::connection('mysql')->select("
            SELECT *
            FROM `hdi_orders`
            WHERE `order_status` = 'SUCCESS' AND `referral_phone` NOT IN
            (
                SELECT DISTINCT Replace(coalesce(`phone`,''), ' ','') as `phone`
                FROM `employees`
                WHERE `phone` != '' AND `phone` IS NOT NULL
            ) AND `referral_phone` != '' AND `referral_phone` IS NOT NULL AND hdi_orders.date_created BETWEEN '$from' AND '$to'
            GROUP BY hdi_orders.transaction_id;
        ");
    }

    public function countReportDataPNCTIN($type, $zone, $from, $to) {
        return DB::connection('mysql')->select("
            SELECT COUNT(`order_id`) AS count, SUM(`amount`) AS amount
            FROM `hdi_orders`
            LEFT OUTER JOIN employees
            ON Replace(coalesce(employees.phone,''), ' ','') = hdi_orders.referral_phone
            LEFT OUTER JOIN ftel_branch
            ON employees.location_id=ftel_branch.location_id AND employees.branch_code=ftel_branch.branch_code
            LEFT OUTER JOIN customer_locations
            ON employees.location_id=customer_locations.customer_location_id
            WHERE `order_status` = 'SUCCESS' AND `referral_phone` IN
            (
                SELECT Replace(coalesce(`phone`,''), ' ','') as `phone`
                FROM `employees`
                WHERE `name` LIKE '$type' AND `phone` != '' AND `phone` != '0' AND `phone` IS NOT NULL AND location_zone IN ('$zone')
            ) AND hdi_orders.date_created BETWEEN '$from' AND '$to';
        ");
    }

    public function getDetailReportDataPNCTIN($type, $zone, $from, $to) {
        return DB::connection('mysql')->select("
            SELECT customer_locations.location_name, hdi_orders.order_id, hdi_orders.customer_phone, hdi_orders.customer_name, hdi_orders.payment_type, hdi_orders.order_status, hdi_orders.amount, hdi_orders.date_created, hdi_orders.referral_phone, employees.name, employees.full_name, ftel_branch.branch_name
            FROM `hdi_orders`
            LEFT OUTER JOIN employees
            ON Replace(coalesce(employees.phone,''), ' ','') = hdi_orders.referral_phone
            LEFT OUTER JOIN ftel_branch
            ON employees.location_id=ftel_branch.location_id AND employees.branch_code=ftel_branch.branch_code
            LEFT OUTER JOIN customer_locations
            ON employees.location_id=customer_locations.customer_location_id
            WHERE `order_status` = 'SUCCESS' AND `referral_phone` IN
            (
                SELECT Replace(coalesce(`phone`,''), ' ','') as `phone`
                FROM `employees`
                WHERE `name` LIKE '$type' AND `phone` != '' AND `phone` != '0' AND `phone` IS NOT NULL AND location_zone IN ('$zone')
            ) AND hdi_orders.date_created BETWEEN '$from' AND '$to';
        ");
    }
}
