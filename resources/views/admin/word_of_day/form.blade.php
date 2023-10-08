@include('admin.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
// $SADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();

$routeName = CustomHelper::getAdminRouteName();
$storage = Storage::disk('public');
$path = 'banners/';

 $word_id = isset($word_of_day->id) ? $word_of_day->id : '';
 $type_id = isset($word_of_day->type) ? $word_of_day->type : '';
 $word = isset($word_of_day->word) ? $word_of_day->word : '';
 $meaning = isset($word_of_day->meaning) ? $word_of_day->meaning : '';
 $date = isset($word_of_day->date) ? $word_of_day->date : ''; 
 $status = isset($word_of_day->status) ? $word_of_day->status : '';

 $type = config('custom.daily_learnings');


?>
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

             <form class="card-body" action="{{route($routeName.'.daily_learnings.add', ['back_url' => $BackUrl])}}" method="post" accept-chartset="UTF-8" enctype="multipart/form-data" role="form">

             {{ csrf_field() }}

                 <input type="hidden" id="id" name = 'id' value="{{$word_id}}">

                   <div class="mb-3">
                  <label for="email" class="form-label">Select Type</label>
                <select class="form-control mb-3" name="type" id="type">
                  <option>Select Type</option>
                @foreach($type as $typ => $value) 
                           
                  <option value="{{$typ}}" <?php if($typ == $type_id){ echo "selected"; }?>>{{$value}}</option>
                @endforeach
                </select>
                </div>

                  <div class="mb-3">
                  <label for="email" class="form-label">Word / Sentence / Phrase</label>
                <textarea class="form-control mb-3" name="word" id="word" placeholder="Enter Link" >{{ old('word',$word) }}</textarea>
                </div>


                <div class="mb-3" id="idmeaning" style="display:none">
                  <label for="email" class="form-label">Meaning</label>
                <textarea class="form-control mb-3" name="meaning" id="meaning" placeholder="Enter Meaning" >{{ old('meaning',$meaning) }}</textarea>
                </div>

               
                      <div class="mb-3">
                        <label for="email" class="form-label">Date</label>
                      <input class="form-control mb-3" type="date" name="date" id="date" placeholder="Enter Date" value="{{ old('date',$date) }}">
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

