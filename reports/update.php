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
$user_id = $_GET['user_id'];

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



$query = $db->query("SELECT id FROM `admins` WHERE `role_id` = ".$role." AND `status`='1' "); 
if($user_id !=''){
    $query = $db->query("SELECT id FROM `admins` WHERE `id` = ".$user_id." AND `status`='1' "); 
}


if($query->num_rows > 0){ 
    while($row = $query->fetch_assoc()){ 

        $in_coupon_query = $db->query("SELECT COUNT(*) as in_count_coupon FROM assign_coupons WHERE child_id = ".$row['id']." GROUP BY coupon_id ");
        $in_coupon = $in_coupon_query->num_rows;
        if($role == 5){
            $out_coupon_query = $db->query("SELECT COUNT(*) as out_count_coupon FROM mobile_details WHERE coupon_parent_id = ".$row['id']." GROUP BY coupon_id ");
            $out_coupon = $out_coupon_query->num_rows;
        }else{
            $out_coupon_query = $db->query("SELECT COUNT(*) as out_count_coupon FROM assign_coupons WHERE parent_id = ".$row['id']." GROUP BY coupon_id ");
            $out_coupon = $out_coupon_query->num_rows;
        }
        

        $closing_coupon = $in_coupon - $out_coupon;

        if($closing_coupon < 0){
            $closing_coupon = 0 ;
        }
        $db->query("UPDATE  admins SET `no_of_coupons`=".$closing_coupon." WHERE id = ".$row['id']."");
    } 
}