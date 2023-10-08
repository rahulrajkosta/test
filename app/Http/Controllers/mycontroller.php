<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\product;
use Symfony\Component\HttpFoundation\File\File;
use App\Reports\ExcelReport;
use App\Reports\ZeroTouchReport;
use koolreport\excel\ExcelExportable;
use App\Reports\CouponAllocationReport;
use Illuminate\Support\Facades\Response;

class mycontroller extends Controller
{
    //use ExcelExportable;

    public function generateExcelReport(Request $request)
    {
        $start_date = $request->input('start_date', '');
        $end_date = $request->input('end_date', '');
        $params=array('start_date'=>$start_date,'end_date'=>$end_date);

        $excelReport = (new ExcelReport($params))
            ->params([
                'start_date' => $start_date,
                'end_date' => $end_date,
            ])
            ->run();

        $excelData = $excelReport->dataStore('data')->data();

        if (count($excelData) === 0) {
            return redirect()->back()->with('error', 'No records found.');
        }

        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="zero_touch_data.xls"',
        ];

        return Response::stream(function () use ($excelData) {
            $handle = fopen('php://output', 'w');

            // Write Excel headers
            fputcsv($handle, [
                'modemtype',
                'modemid',
                'manufacturer',
                'profiletype',
                'owner',
                'Date',
            ], "\t");

            // Write Excel data
            foreach ($excelData as $row) {
                fputcsv($handle, $row, "\t");
            }

            fclose($handle);
        }, 200, $headers);
    }
    public function insert(Request $req)
    {
       $name=$req->get('name');
       $email=$req->get('email');
        $image = $req->file('image')->getClientOriginalName();
        // Move uploded file
        $req->image->move(public_path('images'), $image);
        // return $req->input();
        $pro = new product();
        $pro->name = $name;
        $pro->email = $email;
        $pro->image = $image;
        $pro->save();
        return redirect('display');
    }
    public function display()
    {
        $pdata = product::all();
        return view('display', ['disdata' => $pdata]);
    }
    public function delete($id)
    {
       
        $del=product::find($id);     
        $del->delete(public_path() . 'images/' . $del->image);
        //product::find($id)->delete();
        echo "<script>alert('data delete!');window.location.href='/display';</script>";
    }
    public function updatefrm($id)
      {
        $data=product::find($id);
        $x=compact('data');
        return view('update',$x);
      }
    public function edit(Request $request,$id)
    {
        
        $data=product::find($id);
        $data->name = $request->get('name');
        $data->email = $request->get('email');
        $image = $request->file('image')->getClientOriginalName();
        // Move uploded file
        $request->image->move(public_path('images'), $image);
        $data->image = $image;
        $data->save();
        echo "<script>alert('Data Update Success!');window.location.href='../display';</script>";
    }
    public function chklist()
    {
        return view('record');
    }
    public function chkfn(Request $req)
    {
        $x = $req->get("hobby");
        print_r($x);
        
    }
    //public function generateExcelReport()
    // {
    //     $report = new \App\Reports\ExcelReport();
    //     $report->run()->render();
    // }
    public function UserDataReport(Request $request)
    {
       // print_r
        $start_date = $request->input('start_date', '');
        $end_date = $request->input('end_date', '');
        $params=array('start_date'=>$start_date,'end_date'=>$end_date);
        $report = new \App\Reports\UserData($params);
        $report->run()->render();
    }
    // public function coupon(Request $request)
    // {
    //     $start_date = $request->input('start_date', '');
    //     $end_date = $request->input('end_date', '');
    //     $params=array('start_date'=>$start_date,'end_date'=>$end_date);
    //     $report = new \App\Reports\Coupon($params);
    //     // echo "<pre>";
    //     // print_r($report->run()->render());
    //     // die("===");
    //     $report->run()->render();
    // }
    public function generateExcelReportZero(Request $request)
    {
        $start_date = $request->input('start_date', '');
        $end_date = $request->input('end_date', '');
        $params=array('start_date'=>$start_date,'end_date'=>$end_date);
        $report = new \App\Reports\ZeroTouchReport($params);

        // Run and render the report
        $report->run()->render();

        // Export the report to Excel format
    }
    public function generateCouponAllocationReport(Request $request)
    {
        $start_date = $request->input('start_date', '');
        $end_date = $request->input('end_date', '');
        $params=array('start_date'=>$start_date,'end_date'=>$end_date);
        $report =new \App\Reports\CouponAllocationReport($params);
        // echo "<pre>";
        // print_r($report->run());
        // die("===");
        $report->run()->render();
    }
    public function new_coupon(Request $request)
    {
        $start_date = $request->input('start_date', '');
        $end_date = $request->input('end_date', '');
        $params=array('start_date'=>$start_date,'end_date'=>$end_date);
        $report =new \App\Reports\new_coupon($params);
        // echo "<pre>";
        // print_r($report->run());
        // die("===");
        $report->run()->render();
    }
    public function CouponSummery(Request $request)
    {
        $role = $request->input('role', '');
        
        $params=array('role'=>$role);
        $report =new \App\Reports\CouponSummery($params);
        // echo "<pre>";
        // print_r($report->run());
        // die("===");
        $report->run()->render();
    }
    

}   