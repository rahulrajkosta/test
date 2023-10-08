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
$role = $_GET['role'];
 
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
$fileName = "role_closing_coupon_" . date('Y-m-d') . ".xls"; 
$role_query = $db->query("SELECT `name` FROM roles WHERE id = ".$role."");
$role_data = $role_query->fetch_assoc();
$fileName = $role_data['name']." Role Info". date('Y-m-d') . ".xls"; 

// Column names 
$fields = array('Unique ID','Business Name', 'Name', 'Email', 'Phone', 'Alternate Phone', 'GST','Address',"State",'City','Pincode','Registered On'); 
$excelData = implode("\t", array_values($fields)) . "\n"; 
 

$query = $db->query("SELECT * FROM `admins` WHERE `role_id` = ".$role." AND `status`='1' "); 
if($query->num_rows > 0){ 
    while($row = $query->fetch_assoc()){ 
        

        $state_query = $db->query("SELECT `name` FROM states WHERE id = ".$row['state_id']." ");
        $state = $state_query->fetch_assoc();
        $state_name = isset($state['name']) ? $state['name'] :"";

        $city_name = '';
        if(!empty($row['city_id'])){
            $city_query = $db->query("SELECT `name` FROM cities WHERE id = ".$row['city_id']." ");
        $city = $city_query->fetch_assoc();
        $city_name = isset($city['name']) ? $city['name'] :"";
        }
        



        $lineData = array($row['unique_id'], $row['business_name'], $row['name'],$row['email'], $row['phone'], $row['alternate_phone'],$row['gst'],$row['address'],$state_name,$city_name,$row['pincode'],$row['created_at']); 
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