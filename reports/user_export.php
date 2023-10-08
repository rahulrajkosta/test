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
$start_date = $_GET['start_date']??'';
$end_date = $_GET['end_date']??'';
 
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
$fileName = "user_data" . date('Y-m-d') . ".xls"; 

// Column names 
$fields = array('couponID','Super Dist Details', 'Distributor Details', 'Sales Person Details', 'Retailer Details', 'Super Distributor State', 'Distributor State','Sales Person State',"Retailer State",'Uses/UnUsed','User Name','User Phone','Mobile Name','Imei No','Date Of Purchase','Phone Status'); 
$excelData = implode("\t", array_values($fields)) . "\n"; 
 
$sql = "SELECT coupon_id,user_name,user_phone,imei_no,date_of_purchase,coupon_parent_id,phone_status,mobile_name FROM `mobile_details` WHERE date(`date_of_purchase`) >= '".$start_date."' AND date(`date_of_purchase`) <= '".$end_date."' ";

$query = $db->query($sql); 
if($query->num_rows > 0){ 
    while($row = $query->fetch_assoc()){ 
        $super_distributor_data = '';
        $distributor_data = '';
        $tsm_data = '';
        $seller_data = '';

        $super_distributor_state = '';
        $distributor_state = '';
        $tsm_state = '';
        $seller_state = '';
        
        /////////////Seller/////////////////////
        $seller_query = $db->query("SELECT `unique_id`,`business_name`,`state_id`,`parent_id` FROM admins WHERE id = ".$row['coupon_parent_id']." ");
        $seller = $seller_query->fetch_assoc();
        $seller_data = $seller['business_name'].'-'.$seller['unique_id'];
        $seller_state_query = $db->query("SELECT `name` FROM states WHERE id = ".$seller['state_id']." ");
        $seller_state = $seller_state_query->fetch_assoc();
        $seller_state = $seller_state['name'];


        /////////////TSM/////////////////////

        $tsm_query = $db->query("SELECT `unique_id`,`name`,`state_id`,`parent_id` FROM admins WHERE id = ".$seller['parent_id']." ");
        $tsm = $tsm_query->fetch_assoc();
        $tsm_data = $tsm['name'].'-'.$tsm['unique_id'];
        $tsm_state_query = $db->query("SELECT `name` FROM states WHERE id = ".$tsm['state_id']." ");
        $tsm_state = $tsm_state_query->fetch_assoc();
        $tsm_state = $tsm_state['name'];


        /////////////Distributor/////////////////////

        $distributor_query = $db->query("SELECT `unique_id`,`business_name`,`state_id`,`parent_id` FROM admins WHERE id = ".$tsm['parent_id']." ");
        $distributor = $distributor_query->fetch_assoc();
        $distributor_data = $distributor['business_name'].'-'.$distributor['unique_id'];
        $distributor_state_query = $db->query("SELECT `name` FROM states WHERE id = ".$distributor['state_id']." ");
        $distributor_state = $distributor_state_query->fetch_assoc();
        $distributor_state = $distributor_state['name'];


        /////////////Super Distributor/////////////////////

        $super_distributor_query = $db->query("SELECT `unique_id`,`business_name`,`state_id`,`parent_id` FROM admins WHERE id = ".$distributor['parent_id']." ");
        $super_distributor = $super_distributor_query->fetch_assoc();
        $super_distributor_data = $super_distributor['business_name'].'-'.$super_distributor['unique_id'];
        $super_distributor_state_query = $db->query("SELECT `name` FROM states WHERE id = ".$super_distributor['state_id']." ");
        $super_distributor_state = $super_distributor_state_query->fetch_assoc();
        $super_distributor_state = $super_distributor_state['name'];
      


        $lineData = array($row['coupon_id'], $super_distributor_data, $distributor_data,$tsm_data, $seller_data, $super_distributor_state,$distributor_state,$tsm_state,$seller_state,"Used",$row['user_name'],$row['user_phone'],$row['mobile_name'],$row['imei_no'],$row['date_of_purchase'],$row['phone_status']); 
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