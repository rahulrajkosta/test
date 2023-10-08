@include('admin.common.header')
<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();


$invoice_id = (isset($invoice->id))?$invoice->id:'';
$name = (isset($invoice->name))?$invoice->name:'';
$invoice_no = (isset($invoice->invoice_no))?$invoice->invoice_no:'';
$invoice_id = (isset($invoice->id))?$invoice->id:'';
$invoice_id = (isset($invoice->id))?$invoice->id:'';
$invoice_id = (isset($invoice->id))?$invoice->id:'';



$status = (isset($invoice->status))?$invoice->status:'';


$routeName = CustomHelper::getSadminRouteName();
$storage = Storage::disk('public');
$path = 'influencer/';

$link = "<script>window.open('https://newadmin.reptileindia.co.in/admin/invoice', 'width=710,height=555,left=160,top=170')</script>";

// echo $link;

?>


<!-- BEGIN: Content-->
<div class="app-content content">
  <div class="content-wrapper">
    <div class="content-wrapper-before"></div>
    <div class="content-header row">
      <div class="content-header-left col-md-4 col-12 mb-2">
        <h3 class="content-header-title">{{ $page_heading }}</h3>
      </div>
      <div class="content-header-right col-md-8 col-12">
        <div class="breadcrumbs-top float-md-right">
          <div class="breadcrumb-wrapper mr-1">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a>
              </li>
              <li class="breadcrumb-item active">{{ $page_heading }}
              </li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    <div class="content-body">
      <section class="input-validation">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">{{ $page_heading }}</h4>
                <a class="heading-elements-toggle">
                  <i class="la la-ellipsis-v font-medium-3"></i>
                </a>
                <div class="heading-elements">
                  <ul class="list-inline mb-0">
                    <li>
                     <?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
                     <a href="{{ url($back_url)}}" class="btn btn-info btn-sm" style='float: right;'>Back</a><?php } ?>
                   </li>
                 </ul>
               </div>
             </div>
             @include('snippets.errors')
             @include('snippets.flash')

             <div class="card-content collapse show">
              <div class="card-body">

                <div class="form-row">
                  <div class="col-md-6 mb-3">
                   <label for="userName">Name<span class="text-danger">*</span></label>
                   <input type="text" name="name" value="{{ old('name', $name) }}" id="name" class="form-control"  maxlength="255" placeholder="Enter Name" />

                   @include('snippets.errors_first', ['param' => 'name'])
                 </div>

                 <div class="col-md-6 mb-3">
                   <label for="userName">Address<span class="text-danger">*</span></label>
                   <input type="text" name="address" value="" id="address" class="form-control"  maxlength="255" placeholder="Enter address" />

                   @include('snippets.errors_first', ['param' => 'address'])
                 </div>


                 <div class="col-md-6 mb-3">
                  <label for="userName">Invoice No<span class="text-danger">*</span></label>
                  <input type="text" name="invoice_no" value="{{ old('invoice_no', $invoice_no) }}" id="invoice_no" class="form-control"  maxlength="255" placeholder="Enter invoice_no For Login" />

                  @include('snippets.errors_first', ['param' => 'invoice_no'])

                </div>




              </div>
              <h3>Items & Description</h3>
              <div class="row">
                <div class="col-md-4">
                  <div class="mb-3">
                    <label for="fullname" class="form-label">Rank From *</label>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="mb-3">
                    <label for="fullname" class="form-label">Rank To *</label>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="mb-3">
                    <label for="fullname" class="form-label">Amount  *</label>
                  </div>
                </div>


              </div>

              <div class="row field_wrapper" id="after-add-more">
                <div class="row">
                  <div class="col-md-4">
                    <div class="mb-3">
                      <input class="form-control mb-3" type="number" name="lower_rank[]" value="" placeholder="Lower Rank" aria-label="default input example">

                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="mb-3">
                      <input class="form-control mb-3" type="number" name="upper_rank[]" value="" placeholder="Upper Rank" aria-label="default input example">

                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="mb-3">
                      <input class="form-control mb-3" type="number" name="amount[]" value="" placeholder="Prize Amount" aria-label="default input example">

                    </div>
                  </div>
                  <div class="col-md-1">
                    <div class="mb-3">
                     <a class="btn btn-success add_button" id="add-more"><i class="fa fa-plus" aria-hidden="true"></i></a>

                   </div>
                 </div>
               </div>
             </div>
             

           </div>
         </div>
       </div>
     </div>
   </div>
 </section>

</div>


<div class="content-body" id='printarea'><section class="card">
  <div id="invoice-template" class="card-body">
    <!-- Invoice Company Details -->
    <div id="invoice-company-details" class="row">
      <div class="col-md-6 col-sm-12 text-left text-md-left">             
        <img src="https://newadmin.reptileindia.co.in/public/storage/settings/reptile-rem.png" alt="company logo" class="mb-2" style="width: 250px;">
        <ul class="px-0 list-unstyled">
          <li class="text-bold-700">Carecone Technoloogies Private Limited</li>
          <li>Aggarwal City Plaza, 265, 2nd floor,</li>                            
          <li>Mangalam Place, Sector 3, Rohini,</li>
          <li>New Delhi, Delhi, India 110085</li>
        </ul>

      </div>
      <div class="col-md-6 col-sm-12 text-center text-md-right">
        <h2>INVOICE</h2>
        <p># <span id="invoice_no_value"></span></p>
        <p>{{date('d M , Y')}}</p>        
      </div>
    </div>
    <!--/ Invoice Company Details -->

    <!-- Invoice Customer Details -->
    <div id="invoice-customer-details" class="row pt-2">
      <div class="col-md-6 col-sm-12">
        <p class="text-muted">8178334747</p>
        <p class="text-muted">info@reptileindia.co.in</p>
      </div>
      <div class="col-md-6 col-sm-12 text-center text-md-right">
        <p class="text-muted">Bill To</p>
        <ul class="px-0 list-unstyled">
          <li class="text-bold-700"><span id="name_value"></span></li>
          <li><span id="address_value"></span></li>

        </ul>
        
      </div>
    </div>
    <!--/ Invoice Customer Details -->

    <!-- Invoice Items Details -->
    <div id="invoice-items-details" class="pt-2">
      <div class="row">
        <div class="table-responsive col-sm-12">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>#</th>
                <th>Item &amp; Description</th>
                <th class="text-right">Rate</th>
                <th class="text-right">Hours</th>
                <th class="text-right">Amount</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <th scope="row">1</th>
                <td>
                  <p>Create Mobile Dashboard</p>
                  <p class="text-muted">Vestibulum euismod est eu elit convallis.</p>
                </td>
                <td class="text-right">$ 18.00/hr</td>
                <td class="text-right">100</td>
                <td class="text-right">$ 1800.00</td>
              </tr>
              <tr>
                <th scope="row">2</th>
                <td>
                  <p>Android App Development</p>
                  <p class="text-muted">Pellentesque maximus feugiat lorem at cursus.</p>
                </td>
                <td class="text-right">$ 14.00/hr</td>
                <td class="text-right">300</td>
                <td class="text-right">$ 4200.00</td>
              </tr>
              <tr>
                <th scope="row">3</th>
                <td>
                  <p>Laravel Template Development</p>
                  <p class="text-muted">Vestibulum euismod est eu elit convallis.</p>
                </td>
                <td class="text-right">$ 10.00/hr</td>
                <td class="text-right">500</td>
                <td class="text-right">$ 5000.00</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="row">
        <div class="col-md-7 col-sm-12 text-center text-md-left">
          <p class="lead">Payment Methods:</p>
          <div class="row">
            <div class="col-md-8">
              <table class="table table-borderless table-sm">
                <tbody>
                  <tr>
                    <td>Bank name:</td>
                    <td class="text-right">US Bank, USA</td>
                  </tr>
                  <tr>
                    <td>Acc name:</td>
                    <td class="text-right">Carla Prop</td>
                  </tr>
                  <tr>
                    <td>IBAN:</td>
                    <td class="text-right">ABC123546655</td>
                  </tr>
                  <tr>
                    <td>SWIFT code:</td>
                    <td class="text-right">US12345</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="col-md-5 col-sm-12">
          <p class="lead">Total due</p>
          <div class="table-responsive">
            <table class="table">
              <tbody>
                <tr>
                  <td>Sub Total</td>
                  <td class="text-right">$ 12,000.00</td>
                </tr>
                <tr>
                  <td>TAX (12%)</td>
                  <td class="text-right">$ 1,440.00</td>
                </tr>
                <tr>
                  <td class="text-bold-800">Total</td>
                  <td class="text-bold-800 text-right"> $ 15,440.00</td>
                </tr>
                <tr>
                  <td>Payment Made</td>
                  <td class="pink text-right">(-) $ 2,440.00</td>
                </tr>
                <tr class="bg-grey bg-lighten-4">
                  <td class="text-bold-800">Balance Due</td>
                  <td class="text-bold-800 text-right">$ 13,000.00</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="text-center">
            <p>Authorized person</p>
            <img src="https://demos.themeselection.com/chameleon-admin-template/app-assets/images/pages/signature-scan.png" alt="signature" class="height-100">
            <h6>Carla Prop</h6>
            <p class="text-muted">Managing Director</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Invoice Footer -->
    <div id="invoice-footer">
      <div class="row">
        <div class="col-md-7 col-sm-12">
          <h6>Terms &amp; Condition</h6>
          <p>You know, being a test pilot isn't always the healthiest business in the world. We predict too much for the next year and yet far too little for the next 10.</p>
        </div>
        <div class="col-md-5 col-sm-12 text-center">
          <button type="button" id="print" class="btn btn-info btn-lg my-1"><i class="fa fa-print"></i> Print</button>
        </div>
      </div>
    </div>
    <!--/ Invoice Footer -->

  </div>
</section>
</div>

















</div>
</div>





@include('admin.common.footer')


<script type="text/javascript">

 $("#invoice_no").keyup(function(){
  $("#invoice_no_value").html(this.value);
});
 $("#name").keyup(function(){
  $("#name_value").html(this.value);
}); 

 $("#address").keyup(function(){
  $("#address_value").html(this.value);
});

 $("#print").click(function () {
    // $("#printarea").printThis();
   var prtContent = document.getElementById("printarea");
   var WinPrint = window.open('', '', 'left=0,top=0,width=1300,height=1700,toolbar=0,scrollbars=0,status=0');
   WinPrint.document.write(prtContent.innerHTML);
   WinPrint.document.close();
   WinPrint.focus();
   WinPrint.print();
   WinPrint.close();


 });

</script>


<script type="text/javascript">
  $(document).ready(function(){
    var maxField = 10; //Input fields increment limitation
    var addButton = $('.add_button'); //Add button selector
    var wrapper = $('.field_wrapper'); //Input field wrapper
    var fieldHTML = '<div class="row field_wrapper"><div class="col-md-4">    <div class="mb-3">      <input class="form-control mb-3" type="number" name="lower_rank[]" value="" placeholder="Lower Rank" aria-label="default input example">    </div>  </div>  <div class="col-md-4">    <div class="mb-3">      <input class="form-control mb-3" type="number" name="upper_rank[]" value="" placeholder="Upper Rank" aria-label="default input example">    </div> </div>  <div class="col-md-3">    <div class="mb-3">      <input class="form-control mb-3" type="number" name="amount[]" value="" placeholder="Prize Amount" aria-label="default input example">    </div>  </div>  <div class="col-md-1">    <div class="mb-3">      <a class="btn btn-danger remove_button" ><i class="fa fa-minus" aria-hidden="true"></i></a>    </div></div><div>'; //New input field html 
    var x = 1; //Initial field counter is 1
    
    //Once add button is clicked
    $(addButton).click(function(){
        //Check maximum number of input fields
      if(x < maxField){ 
            x++; //Increment field counter
            $(wrapper).append(fieldHTML); //Add field html
          }
        });
    
    //Once remove button is clicked
    $(wrapper).on('click', '.remove_button', function(e){
      e.preventDefault();
        $(this).closest(".field_wrapper").remove(); //Remove field html
        x--; //Decrement field counter
      });
  });
</script>