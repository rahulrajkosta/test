<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reports\UserReport;

class KoolReportController extends Controller
{
    public function generateExcelReport(Request $request)
    {
        $start_date = $request->input('start_date', '');
        $end_date = $request->input('end_date', '');

        $report = new UserReport;
        $report->run([
            "start_date" => $start_date,
            "end_date" => $end_date,
        ]);

        $report->export("excel");
    }
}
