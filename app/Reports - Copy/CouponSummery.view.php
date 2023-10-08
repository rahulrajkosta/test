<?php
use koolreport\widgets\koolphp\Table;
use koolreport\widgets\google\BarChart;
?>

<h1>Role Closing Coupon Report</h1>

<?php
$roles=DB::table('roles')->select('*')->get();
$distributerss=array();
foreach($roles as $role){
    if(($role->id)==2){
        $super_distributers=DB::table('admins')->where('role_id',$role->id)->get()->toArray();
        foreach($super_distributers as $super_distributer){
                $distributers=DB::table('admins')->where('role_id',3)->where('parent_id',$super_distributer->id)->get()->toArray();
                $distributerss=array_merge($distributerss,$distributers);
        }
    }else{
        continue;
    }
}
echo "<pre>";
print_r($distributerss);
die("===");
Table::create(array(
    "dataStore" => $fnl,
    "title" => "Closing Coupons"
));
?>
