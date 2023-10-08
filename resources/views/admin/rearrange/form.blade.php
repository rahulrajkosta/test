@include('admin.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
// $SADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();

$routeName = CustomHelper::getAdminRouteName();
$storage = Storage::disk('public');
$path = 'banners/';

 $sentence_id = isset($rearrange->id) ? $rearrange->id : '';
 $sentence = isset($rearrange->sentence) ? $rearrange->sentence : '';
 $right_answer = isset($rearrange->right_answer) ? $rearrange->right_answer : '';
 $marks = isset($rearrange->marks) ? $rearrange->marks : '';
 $duration = isset($rearrange->duration) ? $rearrange->duration : '';
 
 $date = isset($rearrange->created_at) ? $rearrange->created_at : ''; 
 $status = isset($rearrange->status) ? $rearrange->status : '';


?>

<style>
  #mandate{
    color: red;
    font-size: 15px;
  }
</style>

<div class="row">
  <div class="col-12">
    <div class="page-title-box d-flex align-items-center justify-content-between">
      <h4 class="mb-0">{{ $page_Heading }}</h4>
      <div class="page-title-right">
        <ol class="breadcrumb m-0">
          <?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
                            <a href="{{ url($back_url)}}" class="btn btn-info btn-sm" style='float: right;'>Back</a><?php } ?>
        </ol>
      </div>

    </div>
  </div>
</div>
       @include('snippets.errors')
            @include('snippets.flash')

      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <h4 class="header-title">{{ $page_Heading }}</h4>

             <form class="card-body" action="{{route($routeName.'.rearrange.add', ['back_url' => $BackUrl])}}" method="post" accept-chartset="UTF-8" enctype="multipart/form-data" role="form">

             {{ csrf_field() }}

                 <input type="hidden" id="id" name = 'id' value="{{$sentence_id}}">

                 

                  <div class="mb-3">
                  <label for="email" class="form-label">Words for Rearrange <span id="mandate">*</span></label>
                <textarea class="form-control mb-3" name="sentence" id="sentence" placeholder="Enter Words" >{{ old('sentence',$sentence) }}</textarea>
                </div>


                <div class="mb-3" id="idmeaning" >
                  <label for="email" class="form-label">Right Answer <span id="mandate">*</span></label>
                <textarea class="form-control mb-3" name="right_answer" id="right_answer" placeholder="Enter Answer" >{{ old('right_answer',$right_answer) }}</textarea>
                </div>

               <div class="row">
                 <div class="col-md-6">
                       <div class="mb-3" id="idmeaning" >
                        <label for="email" class="form-label">Marks <span id="mandate">*</span></label>
                      <input type="number"  class="form-control mb-3" name="marks" id="marks" placeholder="Enter Marks" value="{{ old('marks',$marks) }}">
                      </div>                   
                 </div>

                 <div class="col-md-6">
                      <div class="mb-3" id="idmeaning" >
                        <label for="email" class="form-label">Duration (in minutes) <span id="mandate">*</span></label>
                      <input class="form-control mb-3" type="time" name="duration" id="duration" placeholder="Enter Duration" value="{{ old('duration',$duration) }}">
                      </div>                   
                 </div>
               </div>

                <div class="form-group">
                                        <label>Status</label>
                                        <div>
                                         Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> checked>
                                         &nbsp;
                                         Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >

                                         @include('snippets.errors_first', ['param' => 'status'])
                                     </div>
                                 </div>

                                 

                <div>
                  <input type="submit" class="btn btn-success" value="Submit">
                </div>

              </form>
            </div>
          </div> <!-- end card-->
        </div> <!-- end col-->
      </div>


@include('admin.common.footer')

<script>
  $('#type').on('change', function() {

      var type = this.value;
      var meaning = $('#idmeaning');
      if(type == '0' || type == '2'){      
        $('#idmeaning').show();
      }
      if(type == '1'){
        $('#idmeaning').hide();
      }

    

  });
</script>

