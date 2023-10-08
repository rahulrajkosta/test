<?php

namespace App\Reports;
require_once dirname(__FILE__)."/../../vendor/koolreport/core/autoload.php"; // No need, if you install KoolReport through composer
// Create a file named CouponAllocationReport.php

use \koolreport\KoolReport;

class new_coupon extends \koolreport\KoolReport
{
    public function settings()
    {
       
        return [
            'dataSources' => [
                'myData' => [
                    'class' => \koolreport\datasources\PdoDataSource::class,
                    'connectionString' => 'mysql:host=localhost;dbname=makesecurepro',
                    'username' => 'matrixemilocker',
                    'password' => 'Password@321',
                    'query' => function () {
                        return "SELECT coupon_id,user_name,user_phone,imei_no,date_of_purchase,coupon_parent_id,phone_status,mobile_name FROM `mobile_details` WHERE date(`date_of_purchase`) >= :start_date AND date(`date_of_purchase`) <= :end_date";
                    },
                    'params' => [
                        ':start_date' => $this->params['start_date'],
                        ':end_date' => $this->params['end_date'],
                    ],
                ],
            ],
        ];
    }

    public function setup()
    {
        $start_date = '2023-08-26';
        $end_date = '2023-12-31';
        // $userArr['Mobile Name'] =$mobile_details->mobile_name??'';
        // $userArr['Imei No'] =$mobile_details->imei_no??'';
        // $userArr['Date Of Purchase'] =$mobile_details->date_of_purchase??'';
        // $userArr['Phone Status'] =$mobile_details->phone_status??'';
        // $userArr['Finance Name'] =$mobile_details->loan_provider??'';
        $this->src("myData")
            ->query("SELECT couponID, 
            CONCAT(ad1.business_name, '-', ad1.unique_id) as superdistributor_data,
            c.sdist_date,
            CONCAT(ad2.business_name, '-', ad2.unique_id) as distributor_data,
            c.dist_date,
            CONCAT(ad3.name, '-', ad3.unique_id) as tsm_data,
            c.tsm_date,
            CONCAT(ad4.business_name, '-', ad4.unique_id) as seller_data,
            c.seller_date,
            c.is_used,
            md.user_name,
            md.user_phone,
            md.mobile_name,
            md.imei_no,
            md.date_of_purchase,
            md.phone_status,
            md.loan_provider
            FROM coupons c
            LEFT JOIN admins ad1 ON c.sdist_id = ad1.id
            LEFT JOIN admins ad2 ON c.dist_id = ad2.id
            LEFT JOIN admins ad3 ON c.tsm_id = ad3.id
            LEFT JOIN admins ad4 ON c.seller_id = ad4.id
            LEFT JOIN mobile_details md ON c.couponID = md.coupon_id
            WHERE `sdist_date` >= '$start_date' AND `sdist_date` <= '$end_date'
            ")
            ->params(array(
                ":start_date" => $start_date,
                ":end_date" => $end_date,
            ))->pipe($this->dataStore("myData"));
    }
    public function generateReport()
    {
        $data = $this->dataStore("myData")->getData();
        $this->dataStore("myData")->clear();
        $this->dataStore("myData")->setData($data);

        $this->dataStore("myData")
            ->pipe(new Filter(array(
                array("uses_or_unused", "=", "Used"),
            )))
            ->pipe(new Custom(function ($row) {
                $row['date_of_purchase'] = date('Y-m-d', strtotime($row['date_of_purchase']));
                return $row;
            }))
            ->pipe($this->dataStore("formatted_data"));

        $this->src("formatted_data")
            ->pipe($this->dataStore("result"));
    }
}
