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
 $role = 4;
$start_date = $_GET['start_date']??'';
$end_date = $_GET['end_date']??'';
$user_id = $_GET['user_id']??'';


// Filter the excel data 
function filterData(&$str){ 
    // $str = preg_replace("/\t/", "\\t", $str); 
    // $str = preg_replace("/\r?\n/", "\\n", $str); 
    // if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"'; 
} 
 
// Excel file name for download 
$fileName = "tsm_allocation_" . date('Y-m-d') . ".xls"; 
 
// Column names 
$fields = array('Coupon ID','Sales Person', 'Retailer', 'Sales Person Date', 'Retailer Date', 'Sales Person State', 'Retailer State'); 

$excelData = implode("\t", array_values($fields)) . "\n"; 
 $sql = "SELECT * FROM `assign_coupons` WHERE `parent_role_id` = '".$role."' AND `date` >='".$start_date."' AND `date`<= '".$end_date."' ";

 if($user_id != ''){
 $sql = "SELECT * FROM `assign_coupons` WHERE `parent_role_id` = '".$role."' AND `date` >='".$start_date."' AND `date`<= '".$end_date."' AND `parent_id`='".$user_id."' ";
 }
 


$query = $db->query($sql); 
if($query->num_rows > 0){ 
    while($row = $query->fetch_assoc()){ 
        $coupon_query = $db->query("SELECT `couponID` FROM coupons WHERE id = ".$row['coupon_id']."");
        $coupon = $coupon_query->fetch_assoc();
        $tsm_query = $db->query("SELECT `unique_id`,`name`,`state_id` FROM admins WHERE id = ".$row['parent_id']."");
        $tsm = $tsm_query->fetch_assoc();
        $seller_query = $db->query("SELECT `unique_id`,`business_name`,`state_id` FROM admins WHERE id = ".$row['child_id']." ");
        $seller = $seller_query->fetch_assoc();

        $tsm_state_query = $db->query("SELECT `name` FROM states WHERE id = ".$tsm['state_id']." ");
        $tsm_state = $tsm_state_query->fetch_assoc();

        $seller_state_query = $db->query("SELECT `name` FROM states WHERE id = ".$seller['state_id']." ");
        $seller_state = $seller_state_query->fetch_assoc();

        $lineData = array($coupon['couponID'], $tsm['name'].'-'.$tsm['unique_id'], $seller['business_name'].'-'.$seller['unique_id'], "", $row['date'], $tsm_state['name'], $seller_state['name']); 
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