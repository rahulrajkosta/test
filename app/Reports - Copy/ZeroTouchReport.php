<?php

namespace App\Reports;
require_once dirname(__FILE__)."/../../vendor/koolreport/core/autoload.php"; // No need, if you install KoolReport through composer
use koolreport\KoolReport;
use koolreport\processes\Group;
use koolreport\processes\Sort;

class ZeroTouchReport extends KoolReport
{
    protected $myData;

    public function settings()
    {
        // print_r($this->params);
        // die("===");
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
                    // 'params' => [
                    //     ':start_date' => $this->params['start_date'],
                    //     ':end_date' => $this->params['end_date'],
                    // ],
                ],
            ],
        ];
    }

    protected function setup()
    {
        // -- - mobile_name as manufacturer,
        //-- CASE WHEN mobile_name = null THEN 'N/A' ELSE 'YES' END AS manufacturer,
        $start_date = request('start_date');
        $end_date = request('end_date');
    
        // Define the SQL query with conditions
        echo $sql = "SELECT
        'IMEI' AS ModelType,
        imei_no as ModelId,
       
        CASE WHEN mobile_name = '' THEN 'N/A' ELSE mobile_name END AS manufacturer,
        
        'GERO_TOUCH' AS producttype,
        '1431994248' AS owner,
        date_of_purchase as Dates
      FROM
        mobile_details
      WHERE
        1";
    
        if ($start_date) {
            $sql .= " AND date_of_purchase >= '$start_date'";
        }
    
        if ($end_date) {
            $sql .= " AND date_of_purchase <= '$end_date'";
        }
    
        $this->src("myData")
            ->query($sql)
            ->pipe($this->dataStore("zero_touch_data"));
    }
    
    
    protected function columns()
    {
        return [
            'imei_no' => 'Modem ID',
            'mobile_name' => 'Manufacturer',
            'date_of_purchase' => 'Date',
        ];
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
