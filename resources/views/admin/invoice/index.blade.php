@include('admin.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();


$storage = Storage::disk('public');
$path = 'category/';
?>


<div class="app-content content">
  <div class="content-wrapper">
    <div class="content-wrapper-before"></div>
    <div class="content-header row">
      <div class="content-header-left col-md-4 col-12 mb-2">
        <h3 class="content-header-title">Invoices</h3>
      </div>
      <div class="content-header-right col-md-8 col-12">
        <div class="breadcrumbs-top float-md-right">
          <div class="breadcrumb-wrapper mr-1">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a>
              </li>
              <li class="breadcrumb-item active">Invoices
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
              <h4 class="card-title">Invoices</h4>
              <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
              <div class="heading-elements">
                <ul class="list-inline mb-0">
                  <li>
                   <a href="{{ route($routeName.'.invoice.add', ['back_url' => $BackUrl]) }}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add Invoice</a>
                 </li>
               </ul>
             </div>
           </div>

           <div class="card-content collapse show">
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">#ID</th>
                    <th scope="col">Invoice No</th>
                    <th scope="col">Invoice</th>
                    <th scope="col">Date Created</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if(!empty($invoices)){

                    $i = 1;
                    foreach($invoices as $invoice){
                      ?>
                      <tr>
                        <td>{{$i++}}</td>
                        <td>{{$invoice->invoice_nos ?? ''}}
                         
                        </td>

                        <td>
                          
                        </td>
                        <td>{{date('d M Y',strtotime($invoice->created_at))}}</td>
                      </tr>



                    <?php }}?>
                  </tbody>
                </table>
                <?php if(!empty($invoices)){?>
                  {{ $invoices->appends(request()->input())->links('admin.pagination') }}
                <?php }?>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Responsive tables end -->
    </div>
  </div>
</div>
<!-- END: Content-->






@include('admin.common.footer')

