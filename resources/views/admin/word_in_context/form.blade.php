@include('admin.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
// $SADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();

$routeName = CustomHelper::getAdminRouteName();
$storage = Storage::disk('public');
$path = 'banners/';

 $phrase_id = isset($word_in_context->id) ? $word_in_context->id : '';
 $phrase = isset($word_in_context->phrase) ? $word_in_context->phrase : '';
 $clue = isset($word_in_context->clue) ? $word_in_context->clue : '';
 $right_answer = isset($word_in_context->right_answer) ? $word_in_context->right_answer : '';
 $marks = isset($word_in_context->marks) ? $word_in_context->marks : '';
 $duration = isset($word_in_context->duration) ? $word_in_context->duration : '';
 
 $date = isset($word_in_context->created_at) ? $word_in_context->created_at : ''; 
 $status = isset($word_in_context->status) ? $word_in_context->status : '';


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

             <form class="card-body" action="{{route($routeName.'.word_in_context.add', ['back_url' => $BackUrl])}}" method="post" accept-chartset="UTF-8" enctype="multipart/form-data" role="form">

             {{ csrf_field() }}

                 <input type="hidden" id="id" name = 'id' value="{{$phrase_id}}">

                 

                  <div class="mb-3">
                  <label for="email" class="form-label">Phrase / Paragraph <span id="mandate">*</span></label>
                <textarea class="form-control mb-3" name="phrase" id="phrase" placeholder="Enter Phrase / Paragraph" >{{ old('phrase',$phrase) }}</textarea>
                </div>

                  <div class="mb-3">
                  <label for="email" class="form-label">Clues</label>
                <textarea class="form-control mb-3" name="clue" id="clue" placeholder="Enter Clue" >{{ old('clue',$clue) }}</textarea>
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
                      <input class="form-control mb-3" type="number" name="duration" id="duration" placeholder="Enter Duration" value="{{ old('duration',$duration) }}">
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

