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
$fileName = "zero_touch_data" . date('Y-m-d') . ".xls"; 

// Column names 
$fields = array('modemtype','modemid', 'manufacturer', 'profiletype', 'owner','Date'); 
$excelData = implode("\t", array_values($fields)) . "\n"; 
 
$sql = "SELECT imei_no,mobile_name,date_of_purchase FROM `mobile_details` WHERE date(`date_of_purchase`) >= '".$start_date."' AND date(`date_of_purchase`) <= '".$end_date."' ";




$query = $db->query($sql); 
if($query->num_rows > 0){ 
    while($row = $query->fetch_assoc()){ 
        $modemtype ='IMEI';
        $modemid = $row['imei_no'];
        $manufacturer =$row['mobile_name'];
        $date_time = $row['date_of_purchase'];
        $profiletype ='ZERO_TOUCH';
        $owner ='1431994248';

        $lineData = array($modemtype,$modemid,$manufacturer,$profiletype,$owner,$date_time); 
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