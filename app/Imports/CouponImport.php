<?php
namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;

use Maatwebsite\Excel\Concerns\WithBatchInserts;
use App\Product;
use App\Category;
use App\Helpers\CustomHelper;

use DB;
use App\QuestionNotValid;
use App\MobileDetails;
use App\Question;
use App\Coupon;
use App\AssignCoupon;


class CouponImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading{

   public function  __construct(){
   }

   public function model(array $row)
   {    

       $dbArray = [];
       $dbArray['parent_id'] = 2;
       $dbArray['coupon_code'] = $row['coupon_code']??'';
       $dbArray['couponID'] = $row['couponid']??'';
       $dbArray['invoice_no'] = '';
       $dbArray['date'] = date('Y-m-d');
       $dbArray['time'] = date('H:i');
       $coupon_id = Coupon::insertGetId($dbArray);
       $dbArray1 = [];
       $dbArray1['coupon_id'] = $coupon_id;
       $dbArray1['parent_id'] = 1;
       $dbArray1['child_id'] = 2;
       $dbArray1['date'] = date('Y-m-d');
       $dbArray1['time'] = date('H:i');

       AssignCoupon::insert($dbArray1);


       return ;
   }


   public function batchSize(): int
   {
    return 1000;
}

public function chunkSize(): int
{
    return 1000;
}




/* end of class */
}