<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class ExcelReportController extends Controller
{
    public function generateExcelReport(Request $request)
    {
        // Load the database configuration from .env or config/database.php
        // You don't need to set it explicitly here.
        
        $start_date = $request->input('start_date', '');
        $end_date = $request->input('end_date', '');

        // Filter the excel data
        function filterData(&$str)
        {
            // $str = preg_replace("/\t/", "\\t", $str);
            // $str = preg_replace("/\r?\n/", "\\n", $str);
            // if (strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
        }

        // Excel file name for download
        $fileName = "user_data" . date('Y-m-d') . ".xls";

        // Column names
        $fields = array(
            'couponID', 'Super Dist Details', 'Distributor Details', 'Sales Person Details',
            'Retailer Details', 'Super Distributor State', 'Distributor State', 'Sales Person State',
            "Retailer State", 'Uses/UnUsed', 'User Name', 'User Phone', 'Mobile Name', 'Imei No',
            'Date Of Purchase', 'Phone Status'
        );

        $excelData = implode("\t", array_values($fields)) . "\n";

        $sql = "SELECT coupon_id, user_name, user_phone, imei_no, date_of_purchase, coupon_parent_id, phone_status, mobile_name
                FROM mobile_details
                WHERE DATE(date_of_purchase) >= ? AND DATE(date_of_purchase) <= ?";

        $query = DB::select($sql, [$start_date, $end_date]);

        if (count($query) > 0) {
            foreach ($query as $row) {
                // Process your data as before
                $lineData = array(
                    $row->coupon_id,
                    $super_distributor_data,
                    $distributor_data,
                    $tsm_data,
                    $seller_data,
                    $super_distributor_state,
                    $distributor_state,
                    $tsm_state,
                    $seller_state,
                    "Used",
                    $row->user_name,
                    $row->user_phone,
                    $row->mobile_name,
                    $row->imei_no,
                    $row->date_of_purchase,
                    $row->phone_status
                );

                array_walk($lineData, 'filterData');

                $excelData .= implode("\t", array_values($lineData)) . "\n";
            }
        } else {
            $excelData .= 'No records found...' . "\n";
        }

        // Headers for download
        $headers = array(
            "Content-Type" => "application/vnd.ms-excel",
            "Content-Disposition" => "attachment; filename=\"$fileName\""
        );

        return Response::make($excelData, 200, $headers);
    }
}
