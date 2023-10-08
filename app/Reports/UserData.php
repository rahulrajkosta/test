<?php

namespace App\Reports;
require_once dirname(__FILE__)."/../../vendor/koolreport/core/autoload.php"; // No need, if you install KoolReport through composer
use koolreport\KoolReport;
use koolreport\processes\Group;
use koolreport\processes\Sort;

class UserData extends KoolReport
{
    protected $myData;

    public function settings()
    {
       
        return [
            'dataSources' => [
                'myData' => [
                    'class' => \koolreport\datasources\PdoDataSource::class,
                    'connectionString' => 'mysql:host=localhost;dbname=makesecurepro',
                    'username' => 'root',
                    'password' => 'vertrigo',
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
        $start_date = $this->params['start_date'];
        $end_date = $this->params['end_date'];  
        if(!empty($start_date)){
            $this->src("myData")
            ->query("
                SELECT 
                    md.user_id,
                    md.user_name,
                    md.address,
                    md.user_phone,
                    md.mobile_name,
                    md.imei_no,
                    md.date_of_purchase,
                    md.phone_status,
                    md.app_status,
                    md.total_price,
                    md.downpayment,
                    md.emi,
                    md.total_months,
                    md.total_months * md.emi as EMI_Payed,
                    md.total_price - md.downpayment - md.total_months * md.emi as EMI_pending,
                    md.coupon_id
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
        }else{
            $this->src("myData")
            ->query("
                SELECT 
                    md.user_id,
                    md.user_name,
                    md.address,
                    md.user_phone,
                    md.mobile_name,
                    md.imei_no,
                    md.date_of_purchase,
                    md.phone_status,
                    md.app_status,
                    md.total_price,
                    md.downpayment,
                    md.emi,
                    md.total_months,
                    md.total_months * md.emi as EMI_Payed,
                    md.total_price - md.downpayment - md.total_months * md.emi as EMI_pending,
                    md.coupon_id
                FROM mobile_details md
                LEFT JOIN admins a ON md.coupon_parent_id = a.id
                LEFT JOIN admins t ON a.parent_id = t.id
                LEFT JOIN admins d ON t.parent_id = d.id
                LEFT JOIN admins sd ON d.parent_id = sd.id
                LEFT JOIN states a_state ON a.state_id = a_state.id
                LEFT JOIN states t_state ON t.state_id = t_state.id
                LEFT JOIN states d_state ON d.state_id = d_state.id
                LEFT JOIN states sd_state ON sd.state_id = sd_state.id
            ")
            ->pipe($this->dataStore("myData"));
        }
        
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
