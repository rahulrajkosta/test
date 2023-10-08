<?php

namespace App\Reports;
require_once dirname(__FILE__)."/../../vendor/koolreport/autoload.php"; // No need, if you install KoolReport through composer
use \koolreport\KoolReport;
use \koolreport\excel\PdfExport;
use \koolreport\excel\ExcelExport;

class UserReport extends \koolreport\KoolReport
{
    use \koolreport\laravel\Friendship;

    protected function setup()
    {
        $this->src("mysql")
            ->query("SELECT coupon_id, user_name, user_phone, imei_no, date_of_purchase, coupon_parent_id, phone_status, mobile_name FROM mobile_details")
            ->pipe($this->dataStore("mobile_data"));
    }

    protected function settings()
    {
        return [
            'dataSources' => [
                'mysql' => [
                    'connection' => 'mysql', // Specify your database connection name
                ],
            ],
        ];
    }
}
