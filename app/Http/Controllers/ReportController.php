<?php
// ReportController.php
namespace App\Http\Controllers;

use App\Reports\MyReport;
use App\Reports\MyExcelReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class ReportController extends Controller
{
    public function generateExcelReport(Request $request)
    {
        $start_date = $request->input('start_date', '');
        $end_date = $request->input('end_date', '');

        $excelReport = new MyExcelReport([
            "start_date" => $start_date,
            "end_date" => $end_date,
        ]);

        $excelReport->run();

        return $excelReport->export();
    }
}