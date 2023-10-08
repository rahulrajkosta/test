@include('admin.common.header')
 

 <div class="app-content content">
  <div class="content-wrapper">
    <div class="content-wrapper-before"></div>
    <div class="content-header row">
      <div class="content-header-left col-md-4 col-12 mb-2">
        <h3 class="content-header-title">Lock Phone</h3>
      </div>
      <div class="content-header-right col-md-8 col-12">
        <div class="breadcrumbs-top float-md-right">
          <div class="breadcrumb-wrapper mr-1">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#">Home</a>
              </li>
              <li class="breadcrumb-item active">Lock Phone
            </li>
          </ol>
        </div>
      </div>
    </div>
  </div>

 @include('snippets.errors')
 @include('snippets.flash')

  <div class="content-body">
    <section class="tooltip-validations" id="tooltip-validation">
      <div class="row"> 
        <div class="col-12 mt-3 mb-1">

        </div>
      </div>
      <div class="row match-height">
        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
           <h1></h1>

              <label class="card-title danger" for="inputDanger"><strong>Lock Phone</strong></label>
            </div>
            <div class="card-block">
              <div class="card-body">
                <form action="" method="post">
                  @csrf
                  <input type="hidden" name="type" value="lock">
                  <div class="form-row">
                    <div class="col-md-12 mb-3">
                     <label>Device Token</label>
                     <textarea class="form-control" name="device_token" placeholder="Enter Device Token"></textarea>
                   </div>

                   <div class="col-md-12 mb-3">
                     <label>Phone Number</label>
                     <input type="text" placeholder="Enter Phone Number" class="form-control" name="phone">
                   </div>

                  <div class="col-md-3 mb-3">
                   <button class="btn btn-primary" type="submit" style="margin-top: 25px;">Submit</button>
                 </div>


               </div>

             </form>
           </div>
         </div>
       </div>
     </div>


   


        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
           <h1></h1>

              <label class="card-title danger" for="inputDanger"><strong>UnLock Phone</strong></label>
            </div>
            <div class="card-block">
              <div class="card-body">
                <form action="" method="post">
                  @csrf
                  <input type="hidden" name="type" value="unlock">
                  <div class="form-row">
                    <div class="col-md-12 mb-3">
                     <label>Device Token</label>
                     <textarea class="form-control" name="device_token" placeholder="Enter Device Token"></textarea>
                   </div>

                   <div class="col-md-12 mb-3">
                     <label>Phone Number</label>
                     <input type="text" placeholder="Enter Phone Number" class="form-control" name="phone">
                   </div>

                  <div class="col-md-3 mb-3">
                   <button class="btn btn-primary" type="submit" style="margin-top: 25px;">Submit</button>
                 </div>


               </div>

             </form>
           </div>
         </div>
       </div>
     </div>
</div>







<div class="row match-height">
        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
           <h1></h1>

              <label class="card-title danger" for="inputDanger"><strong>Temporary Lock Phone</strong></label>
            </div>
            <div class="card-block">
              <div class="card-body">
                <form action="{{route('admin.temporary_lock_phone')}}" method="post">
                  @csrf
                  <input type="hidden" name="type" value="temp_lock">
                  <div class="form-row">
                    <div class="col-md-12 mb-3">
                     <label>Device Token</label>
                     <textarea class="form-control" name="device_token" placeholder="Enter Device Token"></textarea>
                   </div>

                   <div class="col-md-12 mb-3">
                     <label>Phone Number</label>
                     <input type="text" placeholder="Enter Phone Number" class="form-control" name="phone">
                   </div>

                   <div class="col-md-12 mb-3">
                     <label>Seller Name</label>
                     <input type="text" placeholder="Enter Seller Name" class="form-control" name="seller_name">
                   </div>

                   <div class="col-md-12 mb-3">
                     <label>Seller Phone</label>
                     <input type="text" placeholder="Enter Seller Phone" class="form-control" name="seller_phone">
                   </div>

                    <div class="col-md-12 mb-3">
                     <label>User Phone</label>
                     <input type="text" placeholder="Enter User Phone" class="form-control" name="user_phone">
                   </div>

                  <div class="col-md-3 mb-3">
                   <button class="btn btn-primary" type="submit" style="margin-top: 25px;">Submit</button>
                 </div>


               </div>

             </form>
           </div>
         </div>
       </div>
     </div>

        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
           <h1></h1>

              <label class="card-title danger" for="inputDanger"><strong>Temprary UnLock Phone</strong></label>
            </div>
            <div class="card-block">
              <div class="card-body">
                <form action="{{route('admin.temporary_lock_phone')}}" method="post">
                  @csrf
                  <input type="hidden" name="type" value="temp_unlock">
                  <div class="form-row">
                    <div class="col-md-12 mb-3">
                     <label>Device Token</label>
                     <textarea class="form-control" name="device_token" placeholder="Enter Device Token"></textarea>
                   </div>

                   <div class="col-md-12 mb-3">
                     <label>Phone Number</label>
                     <input type="text" placeholder="Enter Phone Number" class="form-control" name="phone">
                   </div>

                   <div class="col-md-12 mb-3">
                     <label>Seller Name</label>
                     <input type="text" placeholder="Enter Seller Name" class="form-control" name="seller_name">
                   </div>

                   <div class="col-md-12 mb-3">
                     <label>Seller Phone</label>
                     <input type="text" placeholder="Enter Seller Phone" class="form-control" name="seller_phone">
                   </div>

                    <div class="col-md-12 mb-3">
                     <label>User Phone</label>
                     <input type="text" placeholder="Enter User Phone" class="form-control" name="user_phone">
                   </div>

                  <div class="col-md-3 mb-3">
                   <button class="btn btn-primary" type="submit" style="margin-top: 25px;">Submit</button>
                 </div>


               </div>

             </form>
           </div>
         </div>
       </div>
     </div>
</div>


<div class="row match-height">
        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
           <h1></h1>

              <label class="card-title danger" for="inputDanger"><strong>Check Lock Status</strong></label>
            </div>
            <div class="card-block">
              <div class="card-body">
                <form action="{{route('admin.check_lock_status')}}" method="post">
                  @csrf
                  <input type="hidden" name="type" value="temp_lock">
                  <div class="form-row">
                    <div class="col-md-12 mb-3">
                     <label>Device Token</label>
                     <textarea class="form-control" name="device_token" placeholder="Enter Device Token"></textarea>
                   </div>
                   <div class="col-md-12 mb-3">
                     <label>Seller Name</label>
                     <input type="text" placeholder="Enter Seller Name" class="form-control" name="seller_name">
                   </div>

                   <div class="col-md-12 mb-3">
                     <label>Seller Phone</label>
                     <input type="text" placeholder="Enter Seller Phone" class="form-control" name="seller_phone">
                   </div>

                    <div class="col-md-12 mb-3">
                     <label>User Phone</label>
                     <input type="text" placeholder="Enter User Phone" class="form-control" name="user_phone">
                   </div>

                  <div class="col-md-3 mb-3">
                   <button class="btn btn-primary" type="submit" style="margin-top: 25px;">Submit</button>
                 </div>


               </div>

             </form>
           </div>
         </div>
       </div>
     </div>

        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
           <h1></h1>

              <label class="card-title danger" for="inputDanger"><strong>Update Screenshot</strong></label>
            </div>
            <div class="card-block">
              <div class="card-body">
                <form action="{{route('admin.update_screenshot')}}" method="post">
                  @csrf
                  <input type="hidden" name="type" value="temp_unlock">
                  <div class="form-row">
                    <div class="col-md-12 mb-3">
                     <label>Device Token</label>
                     <textarea class="form-control" name="device_token" placeholder="Enter Device Token"></textarea>
                   </div>

                   <div class="col-md-12 mb-3">
                     <label>Seller Name</label>
                     <input type="text" placeholder="Enter Seller Name" class="form-control" name="seller_name">
                   </div>

                   <div class="col-md-12 mb-3">
                     <label>Seller Phone</label>
                     <input type="text" placeholder="Enter Seller Phone" class="form-control" name="seller_phone">
                   </div>

                    <div class="col-md-12 mb-3">
                     <label>User Phone</label>
                     <input type="text" placeholder="Enter User Phone" class="form-control" name="user_phone">
                   </div>

                  <div class="col-md-3 mb-3">
                   <button class="btn btn-primary" type="submit" style="margin-top: 25px;">Submit</button>
                 </div>


               </div>

             </form>
           </div>
         </div>
       </div>
     </div>
</div>



































</section>
</div>
</div>
</div>
@include('admin.common.footer')
