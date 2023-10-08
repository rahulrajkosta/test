<?php

namespace App\Reports;
require_once dirname(__FILE__)."/../../vendor/koolreport/core/autoload.php"; // No need, if you install KoolReport through composer
use koolreport\KoolReport;
use koolreport\excel\Table;

class ExcelReport extends KoolReport
{
    public function settings()
    {
        return [
            'dataSources' => [
                'db' => [
                    'connectionString' => 'mysql:host=localhost;dbname=makesecurepro',
                    'username' => 'root',
                    'password' => 'vertrigo',
                ],
            ],
        ];
    }

    public function setup()
    {
        $this->src('db')
            ->query("SELECT imei_no, mobile_name, date_of_purchase FROM mobile_details WHERE date(`date_of_purchase`) >= :start_date AND date(`date_of_purchase`) <= :end_date")
            ->params([
                ':start_date' => $this->params['start_date'],
                ':end_date' => $this->params['end_date'],
            ])
            ->pipe($this->dataStore('data'));

        $this->dataStore('data')
            ->renameColumn('imei_no', 'modemid')
            ->renameColumn('mobile_name', 'manufacturer')
            ->renameColumn('date_of_purchase', 'Date')
            ->addColumn('modemtype', 'IMEI')
            ->addColumn('profiletype', 'ZERO_TOUCH')
            ->addColumn('owner', '1431994248');

        $table = Table::create([
            'dataSource' => $this->dataStore('data'),
        ]);

        $table->format([
            'modemid' => ['label' => 'modemid'],
            'manufacturer' => ['label' => 'manufacturer'],
            'Date' => ['label' => 'Date'],
        ]);

        $table->saveAs('public/zero_touch_data.xls');
    }
}
