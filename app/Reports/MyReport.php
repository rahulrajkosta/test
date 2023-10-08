<?php
namespace App\Reports;

require_once dirname(__FILE__)."/../../vendor/koolreport/autoload.php"; // No need, if you install KoolReport through composer
use \koolreport\datasources\MysqliDataSource;
use \koolreport\KoolReport;
use \koolreport\processes\Filter;
use \koolreport\processes\Group;
use \koolreport\processes\Sort;
use \koolreport\processes\Limit;
use \koolreport\processes\Custom;
class MyReport extends \koolreport\KoolReport
{
    public function settings()
    {
        return array(
            "dataSources" => array(
                "mobile_details" => array(
                    "connectionString" => "mysql:host=localhost;dbname=makesecurepro",
                    "username" => "matrixemilocker",
                    "password" => "Password@321",
                    "charset" => "utf8",
                ),
            ),
        );
    }

    public function setup()
    {
        $start_date = '2023-08-26';
        $end_date = '2023-12-31';

        $this->src("mobile_details")
            ->query("
                SELECT * FROM users");
            // ->params(array(
            //     ":start_date" => $start_date,
            //     ":end_date" => $end_date,
            // ))
            // ->pipe($this->dataStore("mobile_data"));
    }

    public function generateReport()
    {
        $data = $this->dataStore("mobile_data")->getData();
        $this->dataStore("mobile_data")->clear();
        $this->dataStore("mobile_data")->setData($data);

        $this->dataStore("mobile_data")
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