<?php 

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
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
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] :"";
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] :"";
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] :"";


// Filter the excel data 
function filterData(&$str){ 
    // $str = preg_replace("/\t/", "\\t", $str); 
    // $str = preg_replace("/\r?\n/", "\\n", $str); 
    // if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"'; 
} 

// Excel file name for download 
$fileName = "all_transaction_" . date('Y-m-d') . ".xls"; 

// Column names 
$fields = array('Coupon ID','Super Distributor','Distributor','Sales Person', 'Retailer', 'Super Distributor Date','Distributor Date','Sales Person Date', 'Retailer Date', 'Super Distributor State', 'Distributor State','Sales Person State', 'Retailer State'); 

$sql = '';


$excelData = implode("\t", array_values($fields)) . "\n"; 


if(!empty($start_date) && !empty($end_date)){
 $sql = "SELECT * FROM `coupons` WHERE  `sdist_date` >='".$start_date."' AND `sdist_date`<= '".$end_date."' ";
}


$query = $db->query($sql); 
if($query->num_rows > 0){ 
    while($row = $query->fetch_assoc()){ 
        $superdistributor_state_name = '';
        $distributor_state_name = '';
        $tsm_state_name = '';
        $seller_state_name = '';


        $superdistributor_data = '';
        $distributor_data = '';
        $tsm_data = '';
        $seller_data = '';


         if(!empty($row['sdist_id'])){
            $superdistributor_query = $db->query("SELECT `unique_id`,`business_name`,`state_id` FROM admins WHERE id = ".$row['sdist_id']."");
            $superdistributor = $superdistributor_query->fetch_assoc();
            $superdistributor_state_query = $db->query("SELECT `name` FROM states WHERE id = ".$superdistributor['state_id']." ");
            $superdistributor_state = $superdistributor_state_query->fetch_assoc();
            $superdistributor_state_name = isset($superdistributor_state['name']) ? $superdistributor_state['name'] :"";

            $superdistributor_data = $superdistributor['business_name'] . "-" .$superdistributor['unique_id'];
        }

         if(!empty($row['dist_id'])){
            $distributor_query = $db->query("SELECT `unique_id`,`business_name`,`state_id` FROM admins WHERE id = ".$row['dist_id']."");
            $distributor = $distributor_query->fetch_assoc();
            if(!empty($distributor['state_id'])){
                $distributor_state_query = $db->query("SELECT `name` FROM states WHERE id = ".$distributor['state_id']." ");
                $distributor_state = $distributor_state_query->fetch_assoc();
                $distributor_state_name = $distributor_state['name']; 
            }
           

            $distributor_data = $distributor['business_name'] . "-" .$distributor['unique_id'];
        }




        if(!empty($row['tsm_id'])){
            $tsm_query = $db->query("SELECT `unique_id`,`name`,`state_id` FROM admins WHERE id = ".$row['tsm_id']."");
            $tsm = $tsm_query->fetch_assoc();
            $tsm_state_query = $db->query("SELECT `name` FROM states WHERE id = ".$tsm['state_id']." ");
            $tsm_state = $tsm_state_query->fetch_assoc();
            $tsm_state_name = $tsm_state['name'];

            $tsm_data = $tsm['name'] . "-" .$tsm['unique_id'];

        }

        if(!empty($row['seller_id'])){
            $seller_query = $db->query("SELECT `unique_id`,`business_name`,`state_id` FROM admins WHERE id = ".$row['seller_id']." ");
            $seller = $seller_query->fetch_assoc();
            $seller_state_query = $db->query("SELECT `name` FROM states WHERE id = ".$seller['state_id']." ");
            $seller_state = $seller_state_query->fetch_assoc();
            $seller_state_name = $seller_state['name'];

            $seller_data = $seller['business_name'] . "-" .$seller['unique_id'];

        }

        
        $lineData = array($row['couponID'], $superdistributor_data, $distributor_data, $tsm_data,$seller_data, $row['sdist_date'],$row['dist_date'],$row['tsm_date'],$row['seller_date'] , $superdistributor_state_name , $distributor_state_name ,$tsm_state_name , $seller_state_name); 
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