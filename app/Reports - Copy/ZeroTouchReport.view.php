
<?php
echo View::make('admin/common/header');
use koolreport\widgets\koolphp\Table;
use koolreport\widgets\google\BarChart;
?>
<head>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.5.1/css/dataTables.dateTime.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
<script src="http://datatables.net/download/build/dataTables.tableTools.nightly.js?_=60133663e907c73303e914416ea258d8"></script>
<script src="http://code.jquery.com/jquery-3.7.0.js"></script>
<script src="http://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="http://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="http://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.2/moment.min.js"></script>
<script src="https://cdn.datatables.net/datetime/1.5.1/js/dataTables.dateTime.min.js"></script>
<style>
    table-responsive {
    display: block;
    overflow-x: auto;
    width: 98% !important;
    -webkit-overflow-scrolling: touch;
    margin: 0px auto;
} 
</style>
</head>
<div class="app-content content">
<div class="content-wrapper">
<div class="content-wrapper-before"></div>
    <div class="content-header row">
      <div class="content-header-left col-md-4 col-12 mb-2">
        <h3 class="content-header-title">Zero Touch Details report from <?=$this->dataStore("zero_touch_data")->toArray()[0]['Dates'] ;?> to <?=$this->dataStore("zero_touch_data")->toArray()[count($this->dataStore("zero_touch_data")->toArray())-1]['Dates']; ?></h3>
      </div>
      <div class="content-header-right col-md-8 col-12">
        <div class="breadcrumbs-top float-md-right">
          <div class="breadcrumb-wrapper mr-1">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a>
              </li>
              <li class="breadcrumb-item active">Reports
              </li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    <div class="content-body">

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">Reports</h4>
        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
        <div class="heading-elements">
          <ul class="list-inline mb-0">
            <li>

             
           </li>
         </ul>
       </div>
     </div>
     <div class="card-content collapse show">
      <div class="table-responsive" style="display: block;
    overflow-x: auto;
    width: 98% !important;
    -webkit-overflow-scrolling: touch;
    margin: 0px auto;">
      <tr>
            <td>Minimum date:</td>
            <td><input type="text" id="min" name="min"></td>
        </tr>
        <tr>
            <td>Maximum date:</td>
            <td><input type="text" id="max" name="max"></td>
        </tr>
        <?php

        // echo "<pre>";
        // print_r($this->dataStore("zero_touch_data")->toArray()[769]);
        // die("===");
        Table::create(array(
            "dataStore" => $this->dataStore("zero_touch_data"),
            "title" => "Closing Coupons",
            "cssClass"=>array(
                "table"=>"ttl"
            )
        ));
        ?>
       </div>
      </div>
    </div>
  </div>
</div>
<!-- Responsive tables end -->
</div>

</div>
</div>
<?php echo View::make('admin/common/footer');?>
<script>
    
    $(document).ready(function() {
        
        let minDate, maxDate;
 
 // Custom filtering function which will search data in column four between two values
 DataTable.ext.search.push(function (settings, data, dataIndex) {
     let min = minDate.val();
     let max = maxDate.val();
     let date = new Date(data[4]);
  
     if (
         (min === null && max === null) ||
         (min === null && date <= max) ||
         (min <= date && max === null) ||
         (min <= date && date <= max)
     ) {
         return true;
     }
     return false;
 });
  
 // Create date inputs
 minDate = new DateTime('#min', {
     format: 'MMMM Do YYYY'
     //2023-08-26
 });
 maxDate = new DateTime('#max', {
     format: 'MMMM Do YYYY'
 });
  
 // DataTables initialisation
 let table = new DataTable('.ttl');
  
 // Refilter the table
 document.querySelectorAll('#min, #max').forEach((el) => {
     el.addEventListener('change', () => table.draw({
    dom: 'Bfrtip',
    buttons: [
        'copyHtml5',
        'excelHtml5',
        'csvHtml5',
        'pdfHtml5'
    ]
} ));
 });
} );
</script>