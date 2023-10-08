<?php
echo View::make('admin/common/header');
use koolreport\widgets\koolphp\Table;
use koolreport\widgets\google\BarChart;
?>
<head>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/dataTables.jqueryui.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/staterestore/1.3.0/css/stateRestore.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/searchbuilder/1.5.0/css/searchBuilder.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/datetime/1.5.1/css/dataTables.dateTime.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedcolumns/4.3.0/css/fixedColumns.dataTables.min.css">



<style>
    table-responsive {
    display: block;
    overflow-x: auto;
    width: 98% !important;
    -webkit-overflow-scrolling: touch;
    margin: 0px auto;
} 
.main-menu.menu-light .navigation > li > a i {
    line-height: 40px;
    display: inline-block;
    width: 40px;
    height: 40px;
    margin-right: 20px;
    text-align: center;
    vertical-align: middle;
    border-radius: 12%;
    background: #fff;
    -webkit-box-shadow: 0 0 10px rgba(0,0,0,.12);
    box-shadow: 0 0 10px rgba(0,0,0,.12);
}
/* Sidebar Styles */
.main-menu {
  width: 250px;
  height: 100%;
  position: fixed;
  top: 0;
  /* left: -250px; Hide the menu by default */
  background-color: #333; /* Background color of the menu */
  transition: left 0.3s ease;
  z-index: 1000;
 
}
.main-menu:hover{
  overflow-y: auto;
}

.main-menu.menu-open {
  left: 0; /* Display the menu when open */
}

/* Navbar Header Styles */
.navbar-header {
  background-color: #fff; /* Background color of the header */
  padding: 20px;
  border-bottom: 1px solid #ddd;
}

.navbar-brand img {
  max-width: 100%;
  height: auto;
}

/* Menu Item Styles */
.navigation-main {
  list-style-type: none;
  padding: 0;
  margin: 0;
}

.nav-item {
  padding: 10px 20px;
  cursor: pointer;
  color: #fff;
}

.nav-item:hover {
  /* background-color: #555; Background color on hover */
}

.menu-title {
  margin-left: 10px;
}

/* Submenu Styles */
.menu-content {
  list-style-type: none;
  padding-left: 20px;
  display: none;
}

.menu-content .menu-item {
  padding: 8px 0;
  cursor: pointer;
}

/* Close Navbar Button Styles */
.close-navbar {
  display: block;
  margin: 10px 20px;
  color: #fff;
  cursor: pointer;
}

/* Customize Additional Styles as Needed */

/* Example styles for active menu item */
.navigation-main .active {
  background-color: #007bff; /* Background color for the active menu item */
  color: #fff;
}
    button.dt-button.buttons-columnVisibility.active:after {
    position: absolute;
    top: 50%;
    margin-top: -10px;
    right: 1em;
    display: inline-block;
    content: "✓";
    color: inherit;
}


</style>
</head>
<div class="app-content content">
<div class="content-wrapper">
<div class="content-wrapper-before"></div>
    <div class="content-header row">
      <div class="content-header-left col-md-4 col-12 mb-2">
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
           <h3 class="content-header-title text-dark">User Data report  
        <h4><?php if(!empty($this->dataStore("myData")->toArray())){echo "from ";
          $date=date_create($this->dataStore("myData")->toArray()[0]['date_of_purchase']);
          echo date_format($date,"d-m-Y H:i:s A");
          ?> to <?php
          $date=date_create($this->dataStore("myData")->toArray()[count($this->dataStore("myData")->toArray())-1]['date_of_purchase']);
          echo date_format($date,"d-m-Y H:i:s A");
          }?></h4></h3>
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
<?php
if(count($this->dataStore("myData")->toArray())>0){
  Table::create(array(
    "dataStore" => $this->dataStore("myData"),
    "title" => "Closing Coupons",
    "cssClass"=>array(
        "table"=>"ttl"
    ),
      "columns"=>array(
	"user_id"=>array(
				"label"=>"User ID"
			),
	"user_name"=>array(
				"label"=>"User Name"
			),
	"address"=>array(
				"label"=>"Address"
			),
	"user_phone"=>array(
				"label"=>"User Phone"
			),
	"mobile_name"=>array(
				"label"=>"Mobile Name"
			),
	"imei_no"=>array(
				"label"=>"IMIEI No"
			),
	"app_status"=>array(
				"label"=>"App Status"
			),
	"total_price"=>array(
				"label"=>"Total Price"
			),
	"downpayment"=>array(
				"label"=>"Down Payment"
			),
	"emi"=>array(
				"label"=>"EMI"
			),
			
	"total_months"=>array(
				"label"=>"Total Months"
			),
			
	"EMI_Payed"=>array(
				"label"=>"EMI Payed"
			),
			
	"EMI_pending"=>array(
				"label"=>"EMI Pending"
			),
			
	"coupon_id"=>array(
				"label"=>"Coupon Id"
			),
	"date_of_purchase"=>array(
				"label"=>"Date Of Purchase"
			),
	"phone_status"=>array(
				"label"=>"Phone Status"
			),
	),
));
}else{
  ?>
 <table class="ttl">
    <thead>
        <tr>
        <th>User ID</th>
        <th>User Name</th>
        <th>Address</th>
        <th>User Phone</th>
        <th>Mobile Name</th>
        <th>IMIEI No</th>
        <th>App Status</th>
        <th>Total Price</th>
        <th>Down Payment</th>
        <th>EMI</th>
        <th>Total Months</th>
        <th>EMI Payed</th>
        <th>EMI Pending</th>
        <th>Coupon Id</th>
        <th>Date Of Purchase</th>
        <th>Phone Status</th>
        </tr>
    </thead>
    <tbody>
      <tr>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      </tr>
    </tbody>
  </table>
  <?php
}

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

<script src="https://code.jquery.com/jquery-3.7.0.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
<script src="https://code.jquery.com/jquery-migrate-1.4.1.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js" ></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js" ></script>
  <script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/dataTables.jqueryui.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.colVis.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/searchbuilder/1.5.0/js/dataTables.searchBuilder.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
<script src="http://datatables.net/download/build/dataTables.tableTools.nightly.js?_=60133663e907c73303e914416ea258d8"></script>
<script src="https://cdn.datatables.net/datetime/1.5.1/js/dataTables.dateTime.min.js"></script>

<script src="https://cdn.datatables.net/plug-ins/1.10.21/sorting/datetime-moment.js"></script>


<!-- <script src="<?= url('/assets/antitheft/')?>/app-assets/vendors/js/new.min.js" type="text/javascript" ></script> -->
<script>
  
 $(document).ready(function() {
 var printCounter = 0;
var title="User Data Report";
$.fn.dataTable.moment( 'HH:mm MMM D, YY' );
$.fn.dataTable.moment( 'D-MM-YYYY' );
$('.ttl').append('<caption style="caption-side: bottom">A fictional company\'s staff table.</caption>');

$('.ttl').DataTable({
    dom: 'QBfrtip',
    columnDefs: [
        {
            targets: 1,
            className: 'noVis'
        }
    ],
    buttons: [
        'copy',
        {
            extend: 'excel',
            title:title,
            customize: function (xlsx) {
            var sheet = xlsx.xl["styles.xml"];
            var tagName = sheet.getElementsByTagName("sz");
            for (let i = 0; i < tagName.length; i++) {
              tagName[i].setAttribute("val", "12");
            }
          },
            messageBottom: function () {
                printCounter++;

                if ( printCounter === 1 ) {
                    return 'This is the first time you have printed this document.';
                }
                else {
                    return 'You have printed this document '+printCounter+' times';
                }
            },
            //messageBottom: 'The information in this table is copyright to MakeSecurePro.'
        },
        // {
        //     extend: 'csvHtml5',
        //     messageTop: 'The information in this table is copyright to MakeSecurePro.'
        // },
        {
            extend: 'pdf',
            title:title,
            orientation: 'landscape', 
            pageSize: 'A4',
            fontSize: '10',
            //messageTop: { text: '<?php echo date('d-m-y H:i:s A'); ?>' + '\n' + $("div.toolbar").text(), fontSize: 10, fontFamily: 'Poppins sans-serif', bold: true, italics: false, alignment: 'left',color:'',position:'abosolute',top:1000,left:0 },
            customize: function(doc) {
            doc.styles.title = {
              color: '#fefffc',
              fontSize: '40',
              background: '#2d4154',
              alignment: 'center'
            }
            doc.header={
              margin: [5, 0, 10, 0],
              height: 30,
              columns: [{
                alignment: "left",
                text: '<?php echo date('d-m-y H:i:s A'); ?>',
              }]
            }
            doc.footer = function(page, pages) {
            return {
              margin: [5, 0, 10, 0],
              height: 30,
              columns: [{
                alignment: "right",
                text: [
                  { text: page.toString(), italics: true },
                    " of ",
                  { text: pages.toString(), italics: true }
                ]
              }]
            }
          }
          },
            messageBottom: function () {
                printCounter++;

                if ( printCounter === 1 ) {
                    return 'This is the first time you have printed this document.';
                }
                else {
                    return 'You have printed this document '+printCounter+' times';
                }
            },
           // messageBottom: 'The information in this table is copyright to MakeSecurePro.'
        },
        {
            extend: 'print',
            title:title,
            orientation: 'landscape', 
            pageSize: 'letter',
            fontSize: '10',
            customize: function(win) {
              $(win.document.body).find('h1').css('text-align', 'center');
                    $(win.document.body).css( 'font-size', '9px' );
                    $(win.document.body).find( 'table' )
                    .addClass( 'compact' )
                    .css( 'font-size', 'inherit' );
          },
            messageBottom: function () {
                printCounter++;

                if ( printCounter === 1 ) {
                    return 'This is the first time you have printed this document.';
                }
                else {
                    return 'You have printed this document '+printCounter+' times';
                }
            },
            //messageBottom: 'The information in this table is copyright to MakeSecurePro.'
        },
        {
            extend: 'colvis',
            columns: ':not(.noVis)',
        },
    ],
    searchBuilder: {
        conditions: {
            "num": {
                // Overwrite the equals condition for the num type
                '+-5': {
                    // This function decides whether to include the criteria in the search
                    isInputValid: function (el, that) {
                        let allFilled = true;

                        // Check each element to make sure that the inputs are valid
                        for (let element of el) {
                            if ($(element).is('input') && $(element).val().length === 0) {
                                allFilled = false;
                            }
                        }

                        return allFilled;
                    },
                    // This is the string displayed in the condition select
                    conditionName: '+- 5',
                    // This function gathers/sets the values from the DOM elements created in the init function that are to be used for searching
                    inputValue: function (el) {
                        let values = [];

                        // Go through the input elements and push each value to the return array
                        for (let element of el) {
                            if ($(element).is('input')) {
                                values.push($(element).val());
                            }
                        }

                        return values;
                    },
                    // This function initializes the criteria, specifically any DOM elements that are required for its use
                    init: function (that, fn, preDefined = null) {
                        // Declare the input element
                        let el = $('<input/>')
                            .addClass(that.classes.value)
                            .addClass(that.classes.input)
                            .on('input', function() { fn(that, this); });

                        // If there is a preDefined value then add it
                        if (preDefined !== null) {
                            $(el).val(preDefined[0]);
                        }

                        return el;
                    },
                    // Straightforward search function comparing value from the table and comparison from the input element
                    // These values are retrieved in `inputValue`
                    search: function (value, comparison) {
                        return +value <= +comparison[0] + 5 && +value >= +comparison[0] - 5;
                    }
                }
            }
        }
    }
});
// $('.ttl').DataTable({
//     dom: 'QBlfrtip',
//     buttons:['createState', 'savedStates']
// });
$("#DataTables_Table_0_wrapper .dt-buttons").append("<button class='dtsb-group dt-button dtsb-add dtsb-button fltr'><span class=''>Filter</span></button>");
  $('.dtsb-searchBuilder').hide();
  // $('.dt-buttons').hide();
  // $('.dataTables_filter').hide();
$(".fltr").on('click',function(){
  $('.dtsb-searchBuilder').toggle('hide');
  // $('.dt-buttons').toggle('hide');
  // $('.dataTables_filter').toggle('hide');
});
$(".has-sub").click(function() {
// Toggle the submenu visibility
$(this).toggleClass("open");
$(this).find(".menu-content").slideToggle();
});

// Add click event listener to close the menu when a menu item is clicked
$(".menu-item").click(function() {
$(".main-menu-content").addClass("menu-closed");
});
$(".nav-item").mouseenter(function() {
$(this).addClass("hover");
});
$(".nav-item").mouseout(function() {
$(this).removeClass("hover");
});

// Add click event listener to open/close the menu
$(".close-navbar").click(function() {
$(".main-menu-content").toggleClass("menu-closed");
});
$(".menu-toggle").click(function() {
$(this).toggleClass("is-active");
$('body').toggleClass('menu-expanded');
$('body').toggleClass('menu-collapsed');
});
$('body').addClass('pace-done menu-expanded');


});
</script>
<footer class="footer footer-static footer-light navbar-border navbar-shadow" >
  <div class="clearfix blue-grey lighten-2 text-sm-center mb-0 px-2">
    <span class="float-md-left d-block d-md-inline-block">
        <?=date('Y');?>  &copy; Copyright <a class="text-bold-800 grey darken-2" href="#" target="_blank">MATRIX MY EMI LOCKER</a></span>
  </div>
</footer>


<?php //echo View::make('admin/common/footer');?>
