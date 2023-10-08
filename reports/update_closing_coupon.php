<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Load the database configuration file 
$dbHost     = "localhost"; 
$dbUsername = "matrixemilocker"; 
$dbPassword = "Password@321"; 
$dbName     = "makesecurepro"; 



$role = $_GET['role'];





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
$fileName = "role_closing_coupon_" . date('Y-m-d') . ".xls"; 
$role_query = $db->query("SELECT `name` FROM roles WHERE id = ".$role."");
$role_data = $role_query->fetch_assoc();
$fileName = $role_data['name']." Closing Coupon". date('Y-m-d') . ".xls"; 




// Column names 
$fields = array('SuperDistributor','Distributor', 'Sales Person', 'Retailer', 'State', 'City', 'Closing Coupons'); 
$excelData = implode("\t", array_values($fields)) . "\n"; 

$query = $db->query("SELECT `id` FROM `admins` WHERE `role_id` = ".$role." "); 
if($query->num_rows > 0){ 
    while($row = $query->fetch_assoc()){ 
        $closing_coupon = 0;
        $super_dist_data = '';
        $dist_data = '';
        $tsm_data = '';
        $seller_data = '';
        $state_data = '';
        $city_data = '';
        if($role == 2){
            $superdistributor_query = $db->query("SELECT `no_of_coupons`,`state_id`,`city_id`,`business_name`,`unique_id` FROM admins WHERE id = ".$row['id']."");
            $superdistributor = $superdistributor_query->fetch_assoc();

            $in_coupon_query = $db->query("SELECT count(`id`) as in_count_coupon FROM assign_coupons WHERE child_id = ".$row['id']." ");
            $in_coupon = $in_coupon_query->fetch_assoc();

            $out_coupon_query = $db->query("SELECT count(`id`) as out_count_coupon FROM assign_coupons WHERE parent_id = ".$row['id']." ");
            $out_coupon = $out_coupon_query->fetch_assoc();

            $super_dist_data = $superdistributor['business_name'] . " - " .$superdistributor['unique_id'];

            $closing_coupon = $in_coupon['in_count_coupon'] - $out_coupon['out_count_coupon'];
            // $closing_coupon = $superdistributor['no_of_coupons'];
            if($closing_coupon < 0){
                $closing_coupon = 0;
            }
            $superdistributor_query = $db->query("UPDATE  admins SET `no_of_coupons`=".$closing_coupon." WHERE id = ".$row['id']."");
            // $superdistributor = $superdistributor_query->fetch_assoc();



        }
        if($role == 3){
            $distributor_query = $db->query("SELECT `id`,`no_of_coupons`,`state_id`,`city_id`,`parent_id`,`business_name`,`unique_id` FROM admins WHERE id = ".$row['id']."");
            $distributor = $distributor_query->fetch_assoc();

            $in_coupon_query = $db->query("SELECT count(`id`) as in_count_coupon FROM assign_coupons WHERE child_id = ".$distributor['id']." ");
            $in_coupon = $in_coupon_query->fetch_assoc();

            $out_coupon_query = $db->query("SELECT count(`id`) as out_count_coupon FROM assign_coupons WHERE parent_id = ".$distributor['id']." ");
            $out_coupon = $out_coupon_query->fetch_assoc();

            $closing_coupon = $in_coupon['in_count_coupon'] - $out_coupon['out_count_coupon'];
            // $closing_coupon = $distributor['no_of_coupons'];
            if($closing_coupon < 0){
                $closing_coupon = 0;
            }
            $distributor_query = $db->query("UPDATE  admins SET `no_of_coupons`=".$closing_coupon." WHERE id = ".$row['id']."");
            // $distributor = $distributor_query->fetch_assoc();




        }
        if($role == 4){
            $tsm_query = $db->query("SELECT `id`,`no_of_coupons`,`parent_id`,`state_id`,`city_id`,`name`,`unique_id` FROM admins WHERE id = ".$row['id']."");
            $tsm = $tsm_query->fetch_assoc();
            $in_coupon_query = $db->query("SELECT count(`id`) as in_count_coupon FROM assign_coupons WHERE child_id = ".$tsm['id']." ");
            $in_coupon = $in_coupon_query->fetch_assoc();

            $out_coupon_query = $db->query("SELECT count(`id`) as out_count_coupon FROM assign_coupons WHERE parent_id = ".$tsm['id']." ");
            $out_coupon = $out_coupon_query->fetch_assoc();

            $closing_coupon = $in_coupon['in_count_coupon'] - $out_coupon['out_count_coupon'];

            if($closing_coupon < 0){
                $closing_coupon = 0;
            }
            $tsm_query = $db->query("UPDATE  admins SET `no_of_coupons`=".$closing_coupon." WHERE id = ".$row['id']."");

          
        }
        if($role == 5){

            $seller_query = $db->query("SELECT `id`,`no_of_coupons`,`state_id`,`city_id`,`parent_id`,`business_name`,`unique_id` FROM admins WHERE id = ".$row['id']."");
            $seller = $seller_query->fetch_assoc();

            $in_coupon_query = $db->query("SELECT count(`id`) as in_count_coupon FROM assign_coupons WHERE child_id = ".$seller['id']." ");
            $in_coupon = $in_coupon_query->fetch_assoc();

            $out_coupon_query = $db->query("SELECT count(`id`) as out_count_coupon FROM mobile_details WHERE coupon_parent_id = ".$seller['id']." ");
            $out_coupon = $out_coupon_query->fetch_assoc();

            $closing_coupon = $in_coupon['in_count_coupon'] - $out_coupon['out_count_coupon'];

            if($closing_coupon < 0){
                $closing_coupon = 0;
            }
            $tsm_query = $db->query("UPDATE  admins SET `no_of_coupons`=".$closing_coupon." WHERE id = ".$row['id']."");

        }
        

        if($closing_coupon < 0){
            $closing_coupon = 0;
        }


        $lineData = array($super_dist_data, $dist_data, $tsm_data,$seller_data, $state_data, $city_data, $closing_coupon); 
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