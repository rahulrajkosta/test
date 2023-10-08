<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Load the database configuration file 
$dbHost     = "localhost"; 
$dbUsername = "matrixemilocker"; 
$dbPassword = "Password@321"; 
$dbName     = "makesecurepro"; 
 
// Create database connection 
$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName); 
 
// Check connection 
if ($db->connect_error) { 
    die("Connection failed: " . $db->connect_error); 
} 
 
// Filter the excel data 
function filterData(&$str){ 
    // $str = preg_replace("/\t/", "\\t", $str); 
    // $str = preg_replace("/\r?\n/", "\\n", $str); 
    // if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"'; 
} 
 
// Excel file name for download 
$fileName = "members-data_" . date('Y-m-d') . ".xls"; 
 
// Column names 
$fields = array('coupon_id','coupon_code' ,'tsm_id', 'seller_id', 'date', 'used_status', 'user_name', 'user_phone', 'mobile_name','imei','phone_status','finance_name'); 
 
// Display column names as first row 
$excelData = implode("\t", array_values($fields)) . "\n"; 
 
// Fetch records from database 
$query = $db->query("SELECT `mobile_details`.`coupon_id` ,`mobile_details`.`coupon_code`,`mobile_details`.`coupon_parent_id`,`mobile_details`.`date_of_purchase`,`mobile_details`.`user_name`,`mobile_details`.`mobile_name`,`mobile_details`.`user_phone`,`mobile_details`.`imei_no` ,`mobile_details`.`phone_status`,`mobile_details`.`loan_provider`,`mobile_details`.`coupon_parent_id` FROM `mobile_details` WHERE date(`mobile_details`.`date_of_purchase`) >= '2022-08-01' AND date(`mobile_details`.`date_of_purchase`) <= '2023-02-07' "); 
if($query->num_rows > 0){ 
    // Output each row of the data 
    while($row = $query->fetch_assoc()){ 
        // $status = ($row['status'] == 1)?'Used':'Used'; 
        
        $seller_query = $db->query("SELECT * FROM admins WHERE id = ".$row['coupon_parent_id']." ");
       
        $seller = $seller_query->fetch_assoc();
        
        $tsm_query = $db->query("SELECT * FROM admins WHERE id = ".$seller['parent_id']."");
        $tsm = $tsm_query->fetch_assoc();


        $lineData = array($row['coupon_id'], $row['coupon_code'], $tsm['name'], $seller['business_name'], $row['date_of_purchase'], "Used", $row['user_name'], $row['user_phone'],$row['mobile_name'],$row['imei_no'],$row['phone_status'],$row['loan_provider']); 
        array_walk($lineData, 'filterData'); 
        $excelData .= implode("\t", array_values($lineData)) . "\n"; 
    } 
}else{ 
    $excelData .= 'No records found...'. "\n"; 
} 
 
// Headers for download 
header("Content-Type: application/vnd.ms-excel"); 
header("Content-Disposition: attachment; filename=\"$fileName\""); 
 
// Render excel data 
echo $excelData; 
 
exit;