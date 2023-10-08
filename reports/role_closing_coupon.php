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
$fields = array('SuperDistributor','Distributor', 'TSM', 'Seller', 'State', 'City','Total COupon' ,'Closing Coupons'); 
$excelData = implode("\t", array_values($fields)) . "\n"; 

$query = $db->query("SELECT `id` FROM `admins` WHERE `role_id` = ".$role." "); 
if($query->num_rows > 0){ 
    while($row = $query->fetch_assoc()){ 
        $closing_coupon = 0;
        $total_coupon = 0;
        $super_dist_data = '';
        $dist_data = '';
        $tsm_data = '';
        $seller_data = '';
        $state_data = '';
        $city_data = '';
        if($role == 2){
            $superdistributor_query = $db->query("SELECT `no_of_coupons`,`total_assign_coupon`,`state_id`,`city_id`,`business_name`,`unique_id` FROM admins WHERE id = ".$row['id']."");
            $superdistributor = $superdistributor_query->fetch_assoc();

            $super_dist_data = $superdistributor['business_name'] . " - " .$superdistributor['unique_id'];

            $closing_coupon = $superdistributor['no_of_coupons'];
            $total_coupon = $superdistributor['total_assign_coupon'];
            if(!empty($superdistributor['state_id'])){

                $state_query = $db->query("SELECT `name` FROM states WHERE id = ".$superdistributor['state_id']."");
                $state = $state_query->fetch_assoc();
                $state_data = $state['name'];
            }
            if(!empty($superdistributor['state_id'])){

                $city_query = $db->query("SELECT `business_name` FROM cities WHERE id = ".$superdistributor['city_id']."");
                $city = $city_query->fetch_assoc();
                $city_data = isset($city['name']) ? $city['name'] :"";
            }



        }
        if($role == 3){
            $distributor_query = $db->query("SELECT `id`,`no_of_coupons`,`total_assign_coupon`,`state_id`,`city_id`,`parent_id`,`business_name`,`unique_id` FROM admins WHERE id = ".$row['id']."");
            $distributor = $distributor_query->fetch_assoc();

            $superdistributor_query = $db->query("SELECT `business_name`,`unique_id` FROM admins WHERE id = ".$distributor['parent_id']."");
            $superdistributor = $superdistributor_query->fetch_assoc();

            $super_dist_data = $superdistributor['business_name'] . " - " .$superdistributor['unique_id'];
            $dist_data = $distributor['business_name'] . " - " .$distributor['unique_id'];
            $total_coupon = $distributor['total_assign_coupon'];

            if(!empty($distributor['state_id'])){

                $state_query = $db->query("SELECT `name` FROM states WHERE id = ".$distributor['state_id']."");
                $state = $state_query->fetch_assoc();
                $state_data = $state['name'];
            }
            if(!empty($distributor['state_id'])){

                $city_query = $db->query("SELECT `name` FROM cities WHERE id = ".$distributor['city_id']."");
                $city = $city_query->fetch_assoc();
                $city_data = isset($city['name']) ?$city['name'] :"";
            }




            // $in_coupon_query = $db->query("SELECT count(`id`) as in_count_coupon FROM assign_coupons WHERE child_id = ".$distributor['id']." ");
            // $in_coupon = $in_coupon_query->fetch_assoc();

            // $out_coupon_query = $db->query("SELECT count(`id`) as out_count_coupon FROM assign_coupons WHERE parent_id = ".$distributor['id']." ");
            // $out_coupon = $out_coupon_query->fetch_assoc();

            // $closing_coupon = $in_coupon['in_count_coupon'] - $out_coupon['out_count_coupon'];
            $closing_coupon = $distributor['no_of_coupons'];


            $in_coupon_query = $db->query("SELECT count(`id`) as in_count_coupon FROM coupons WHERE dist_id = ".$row['id']." ");
            $in_coupon = $in_coupon_query->fetch_assoc();
            $total_coupon = $in_coupon['in_count_coupon'];



        }
        if($role == 4){
            $tsm_query = $db->query("SELECT `id`,`no_of_coupons`,`parent_id`,`total_assign_coupon`,`state_id`,`city_id`,`name`,`unique_id` FROM admins WHERE id = ".$row['id']."");
            $tsm = $tsm_query->fetch_assoc();

            $distributor_query = $db->query("SELECT `id`,`no_of_coupons`,`parent_id`,`business_name`,`unique_id` FROM admins WHERE id = ".$tsm['parent_id']."");
            $distributor = $distributor_query->fetch_assoc();

            $superdistributor_query = $db->query("SELECT `business_name`,`unique_id` FROM admins WHERE id = ".$distributor['parent_id']."");
            $superdistributor = $superdistributor_query->fetch_assoc();

            $super_dist_data = $superdistributor['business_name'] . " - " .$superdistributor['unique_id'];
            $dist_data = $distributor['business_name'] . " - " .$distributor['unique_id'];
            $tsm_data = $tsm['name'] . " - " .$tsm['unique_id'];
            
            $closing_coupon = $tsm['no_of_coupons'];

            $total_coupon = $tsm['total_assign_coupon'];

            if(!empty($tsm['state_id'])){

                $state_query = $db->query("SELECT `name` FROM states WHERE id = ".$tsm['state_id']."");
                $state = $state_query->fetch_assoc();
                $state_data = $state['name'];
            }
            if(!empty($tsm['city_id'])){

                $city_query = $db->query("SELECT `name` FROM cities WHERE id = ".$tsm['city_id']."");
                $city = $city_query->fetch_assoc();
                if(!empty($city)){
                    $city_data = isset($city['name']) ? $city['name'] :"";

                }
            }

            $in_coupon_query = $db->query("SELECT count(`id`) as in_count_coupon FROM coupons WHERE tsm_id = ".$row['id']." ");
            $in_coupon = $in_coupon_query->fetch_assoc();
            $total_coupon = $in_coupon['in_count_coupon'];
        }
        if($role == 5){
            $sqllll = "SELECT `id`,`no_of_coupons`,`total_assign_coupon`,`state_id`,`city_id`,`parent_id`,`business_name`,`unique_id` FROM admins WHERE id = ".$row['id']."";

            $seller_query = $db->query($sqllll);
            $seller = $seller_query->fetch_assoc();

            if(!empty($seller['parent_id'])){
                $tsm_query = $db->query("SELECT `parent_id`,`name`,`unique_id` FROM admins WHERE id = ".$seller['parent_id']."");
                $tsm = $tsm_query->fetch_assoc();

                $distributor_query = $db->query("SELECT `parent_id`,`business_name`,`unique_id` FROM admins WHERE id = ".$tsm['parent_id']." ");
                $distributor = $distributor_query->fetch_assoc();
                if(!empty($distributor)){
                    $superdistributor_query = $db->query("SELECT `business_name`,`unique_id` FROM admins WHERE id = ".$distributor['parent_id']."");
                    $superdistributor = $superdistributor_query->fetch_assoc();
                    $super_dist_data = $superdistributor['business_name'] . " - " .$superdistributor['unique_id'];
                }
                

                if(!empty($distributor)){
                    $dist_data = $distributor['business_name'] . " - " .$distributor['unique_id'];
                }
                if(!empty($tsm)){
                    $tsm_data = $tsm['name'] . " - " .$tsm['unique_id'];
                }
            }
            
            $seller_data = $seller['business_name'] . " - " .$seller['unique_id'];

            $total_coupon = $seller['total_assign_coupon'];

            // $in_coupon_query = $db->query("SELECT count(`id`) as in_count_coupon FROM coupons WHERE seller_id = ".$row['id']." ");
            // $in_coupon = $in_coupon_query->fetch_assoc();
            // $total_coupon = $in_coupon['in_count_coupon'];
            
            $closing_coupon = $seller['no_of_coupons'];
            if(!empty($seller['state_id'])){
                $state_query = $db->query("SELECT `name` FROM states WHERE id = ".$seller['state_id']."");
                $state = $state_query->fetch_assoc();
                $state_data = $state['name'];
            }
            if(!empty($seller['city_id'])){

                $city_query = $db->query("SELECT `name` FROM cities WHERE id = ".$seller['city_id']."");
                $city = $city_query->fetch_assoc();
                $city_data = $city['name'];
            }
        }
        





        $lineData = array($super_dist_data, $dist_data, $tsm_data,$seller_data, $state_data, $city_data,$total_coupon ,$closing_coupon); 
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