<?php

namespace App\Reports;
require_once dirname(__FILE__)."/../../vendor/koolreport/core/autoload.php"; // No need, if you install KoolReport through composer
use koolreport\KoolReport;
use koolreport\processes\Group;
use koolreport\processes\Sort;

class Coupon extends KoolReport
{
    protected $myData;

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

        $this->src("myData")
            ->query("
                SELECT 
                    md.coupon_id,
                    CONCAT(sd.business_name, '-', sd.unique_id) as super_distributor_details,
                    CONCAT(d.business_name, '-', d.unique_id) as distributor_details,
                    CONCAT(t.name, '-', t.unique_id) as sales_person_details,
                    CONCAT(a.business_name, '-', a.unique_id) as retailer_details,
                    sd_state.name as super_distributor_state,
                    d_state.name as distributor_state,
                    t_state.name as sales_person_state,
                    a_state.name as retailer_state,
                    'Used' as uses_or_unused,
                    md.user_name,
                    md.user_phone,
                    md.mobile_name,
                    md.imei_no,
                    md.date_of_purchase,
                    md.phone_status
                FROM mobile_details md
                LEFT JOIN admins a ON md.coupon_parent_id = a.id
                LEFT JOIN admins t ON a.parent_id = t.id
                LEFT JOIN admins d ON t.parent_id = d.id
                LEFT JOIN admins sd ON d.parent_id = sd.id
                LEFT JOIN states a_state ON a.state_id = a_state.id
                LEFT JOIN states t_state ON t.state_id = t_state.id
                LEFT JOIN states d_state ON d.state_id = d_state.id
                LEFT JOIN states sd_state ON sd.state_id = sd_state.id
                WHERE DATE(md.date_of_purchase) BETWEEN :start_date AND :end_date
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
