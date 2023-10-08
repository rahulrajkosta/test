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


class MobileDetailsImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading{

       public function  __construct(){
    }

 public function model(array $row)
    {    
        
            $dbArray['user_id'] = $row['user_id'] ?? '';
            $dbArray['user_name'] = $row['user_name'] ?? '';
            $dbArray['user_phone'] = $row['user_phone'] ?? '';
            $dbArray['user_image'] = $row['user_image'] ?? '';
            $dbArray['coupon_code'] = $row['coupon_code'] ?? '';
            $dbArray['coupon_id'] = $row['coupon_id'] ?? '';
            $dbArray['coupon_parent_id'] = $row['coupon_parent_id'] ?? '';
            $dbArray['mobile_name'] = $row['mobile_name'] ?? '';
            $dbArray['phone_model'] = $row['phone_model'] ?? '';
            $dbArray['latitude'] = $row['latitude'] ?? '';
            $dbArray['longitude'] = $row['longitude'] ?? '';
            $dbArray['location_updated_at'] = $row['location_updated_at'] ?? '';
            $dbArray['android_version'] = $row['android_version'] ?? '';
            $dbArray['imei_no'] = $row['imei_no'] ?? '';
            $dbArray['phone_status'] = $row['phone_status'] ?? '';
            $dbArray['sim1_operator'] = $row['sim1_operator'] ?? '';
            $dbArray['date_of_purchase'] = $row['date_of_purchase'] ?? '';
            $dbArray['sim2_operator'] = $row['sim2_operator'] ?? '';
            $dbArray['imei_no2'] = $row['imei_no2'] ?? '';
            $dbArray['address'] = $row['address'] ?? '';
            $dbArray['other_person_mobile_no'] = $row['other_person_mobile_no'] ?? '';
            $dbArray['other_person_name'] = $row['other_person_name'] ?? '';
            $dbArray['relation'] = $row['relation'] ?? '';
            $dbArray['sim1'] = $row['sim1'] ?? '';
            $dbArray['sim2'] = $row['sim2'] ?? '';
            $dbArray['total_price'] = $row['total_price'] ?? '';
            $dbArray['downpayment'] = $row['downpayment'] ?? '';
            $dbArray['emi'] = $row['downpayment'] ?? '';
            $dbArray['total_months'] = $row['downpayment'] ?? '';
            $dbArray['start_date'] = $row['downpayment'] ?? '';
            $dbArray['device_token'] = $row['downpayment'] ?? '';
           
            MobileDetails::create($dbArray);
       
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