@include('admin.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();


$storage = Storage::disk('public');
$path = 'influencer/thumb/';
// $roleId = Auth::guard('admin')->user()->role_id; 

?>

<div class="app-content content">
  <div class="content-wrapper">
    <div class="content-wrapper-before"></div>
    <div class="content-header row">
      <div class="content-header-left col-md-4 col-12 mb-2">
        <h3 class="content-header-title">FAQs</h3>
      </div>
      <div class="content-header-right col-md-8 col-12">
        <div class="breadcrumbs-top float-md-right">
          <div class="breadcrumb-wrapper mr-1">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a>
              </li>
              <li class="breadcrumb-item active">FAQs
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
              <h4 class="card-title">FAQs</h4>
              <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                <div class="heading-elements">
                  <ul class="list-inline mb-0">
                    <li>
                    
                     <a href="{{ route($routeName.'.faqs.add').'?back_url='.$BackUrl }}" class="btn btn-info btn-sm" style='float: right;'>Add FAQs</a>
                   </li>
                 </ul>
               </div>
            </div>
            <div class="card-content collapse show">
              <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                       <th>S.No.</th>
                      <th>Questions</th>
                      <th>Answer</th>                 
                      <th>Status</th>
                      <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if(!empty($faqs)){

                    $i = 1;
                    foreach($faqs as $cat){
                      $question =  mb_strlen(strip_tags($cat->questions),'utf-8') > 50 ? mb_substr(strip_tags($cat->questions),0,50,'utf-8').'...' : strip_tags($cat->questions);
                      $answer =  mb_strlen(strip_tags($cat->answer),'utf-8') > 50 ? mb_substr(strip_tags($cat->answer),0,50,'utf-8').'...' : strip_tags($cat->answer);
                      ?>
                      <tr>
                        <td>{{$i++}}</td>
                        <td>{{$question}}</td>
                        <td>{{$answer}}</td>
                        <td>
                          <select id='change_faq_status{{$cat->id}}' onchange='change_faq_status({{$cat->id}})' class="form-control">
                            <option value='1' <?php if($cat->status ==1)echo "selected";?> >Active</option>
                            <option value='0' <?php if($cat->status ==0)echo "selected";?>>InActive</option>
                          </select> 


                        </td>

                        <td>
                         
                          <a class="btn btn-success" href="{{ route($routeName.'.faqs.edit', $cat->id.'?back_url='.$BackUrl) }}"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;&nbsp;

                          <a class="btn btn-danger" onclick="return confirm('Are You Want To Delete ??');" href="{{ route($routeName.'.faqs.delete', $cat->id.'?back_url='.$BackUrl) }}"><i class="fa fa-trash"></i></a>


                        </td>
                      </tr>
                    <?php }}?>
                        </tbody>
                        {{ $faqs->appends(request()->input())->links('admin.pagination') }}
                      </table>
                   
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

  <script>
   

function change_faq_status(faq_id){
  var status = $('#change_faq_status'+faq_id).val();


   var _token = '{{ csrf_token() }}';

            $.ajax({
                url: "{{ route($routeName.'.faqs.change_faq_status') }}",
                type: "POST",
                data: {faq_id:faq_id, status:status},
                dataType:"JSON",
                headers:{'X-CSRF-TOKEN': _token},
                cache: false,
                success: function(resp){
                    if(resp.success){
                      alert(resp.message);
                    }else{
                      alert(resp.message);
                      
                    }
                }
            });


}



  </script>
