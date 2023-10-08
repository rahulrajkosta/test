@include('admin.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
// $SADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();

$routeName = CustomHelper::getAdminRouteName();
$storage = Storage::disk('public');
$path = 'influencer/';

$read_id = isset($reads->id) ? $reads->id : '';
$text = isset($reads->text) ? $reads->text : '';
$status = isset($reads->status) ? $reads->status : '1';


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






<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <h4 class="header-title">{{ $page_Heading }}</h4>

        <form class="card-body" action="" method="post" accept-chartset="UTF-8" enctype="multipart/form-data" role="form">

          {{ csrf_field() }}

          <input type="hidden" value="{{ $read_id }}">

          <div class="mb-3">
            <label for="email" class="form-label">Text :</label>
            <textarea class="form-control mb-3"name="text" id="text"  placeholder="Write Category Description .........." aria-label="default input example">{{ old('text',$text) }}</textarea>
          </div>


          <div class="d-none">
            <p class="lead text-light mt-4">Select Voice</p>
            <select id="voices" class="form-select bg-secondary text-light"></select>
            <div class="d-flex mt-4 text-light">
              <div>
                <p class="lead">Volume</p>
                <input type="range" min="0" max="1" value="1" step="0.1" id="volume" />
                <span id="volume-label" class="ms-2">1</span>
              </div>
              <div class="mx-5">
                <p class="lead">Rate</p>
                <input type="range" min="0.1" max="10" value="1" id="rate" step="0.1" />
                <span id="rate-label" class="ms-2">1</span>
              </div>
              <div>
                <p class="lead">Pitch</p>
                <input type="range" min="0" max="2" value="1" step="0.1" id="pitch" />
                <span id="pitch-label" class="ms-2">1</span>
              </div>
            </div>



            <div class="mb-5">
              <a id="start" class="btn btn-success mt-5 me-3">Start</a>
              <a id="pause" class="btn btn-warning mt-5 me-3">Pause</a>
              <a id="resume" class="btn btn-info mt-5 me-3">Resume</a>
              <!-- <a id="cancel" class="btn btn-danger mt-5 me-3">Cancel</a> -->
            </div>


          </div>



          <div class="mb-3">
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



@include('snippets.errors')
@include('snippets.flash')



<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <h4 class="header-title">Questions</h4>

        <form class="card-body" action="{{route($routeName.'.read_respond.add_question')}}" method="post" accept-chartset="UTF-8" enctype="multipart/form-data" role="form">

          {{ csrf_field() }}

          <input type="hidden" name="read_id" value="{{ $read_id }}">

          <div class="row">


            <div class="col-md-6">
              <div class="mb-3">
                <label for="email" class="form-label">Question Name:</label>
                <textarea class="form-control mb-3"name="question_name"   placeholder="Question Name" aria-label="default input example">{{old('question_name')}}</textarea>
              </div>
            </div>

            <div class="col-md-3">
             <div class="mb-3">
              <label for="email" class="form-label">Answer Type :</label>
              <select class="form-control" name="type" id="type">
                <option value="" selected disabled>Select Answer Type</option>
                <option value="option">Options</option>
                <option value="text">Text</option>
              </select>
            </div>
          </div>



          

          <div class="col-md-3" id="option_type1">
           <div class="mb-3">
            <label for="email" class="form-label">Option-1 :</label>
            <textarea class="form-control mb-3"name="option_1"   placeholder="Option 1" aria-label="default input example">{{old('option_1')}}</textarea>
          </div>
        </div>



        <div class="col-md-3" id="answer">
         <div class="mb-3">
          <label for="email" class="form-label">Answer :</label>
          <textarea class="form-control mb-3"name="answer"   placeholder="Answer" aria-label="default input example">{{old('answer')}}</textarea>
        </div>
      </div>
    </div>







    <div class="row" id="option_type">
      <div class="col-md-3">
       <div class="mb-3">
        <label for="email" class="form-label">Option-2 :</label>
        <textarea class="form-control mb-3"name="option_2"   placeholder="Option 2" aria-label="default input example">{{old('option_2')}}</textarea>
      </div>
    </div>

    <div class="col-md-3">
     <div class="mb-3">
      <label for="email" class="form-label">Option-3 :</label>
      <textarea class="form-control mb-3"name="option_3"   placeholder="Option 3" aria-label="default input example">{{old('option_3')}}</textarea>
    </div>
  </div>

  <div class="col-md-3">
   <div class="mb-3">
    <label for="email" class="form-label">Option-4 :</label>
    <textarea class="form-control mb-3"name="option_4"   placeholder="Option 4" aria-label="default input example">{{old('option_4')}}</textarea>
  </div>
</div>

<div class="col-md-3">
 <div class="mb-3">
  <label for="email" class="form-label">Right Option :</label>
  <select name="right_option" class="form-control">
    <option value="" selected disabled>Select Right Option</option>

    <option value="1">Option-1</option>
    <option value="2">Option-2</option>
    <option value="3">Option-3</option>
    <option value="4">Option-4</option>
  </select>
</div>
</div>


</div>









<div>
  <input type="submit" class="btn btn-success" value="Submit">
</div>

</form>
</div>
</div> 
</div> 
</div>









<!-- end page title --> 
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <h4 class="header-title">Questions List</h4>
        <div class="table-responsive">

          <table id="basic-datatable" class="table dt-responsive nowrap w-100">
            <thead>
              <tr>
                <th>S.No.</th>
                <th>Question</th>
                <th>Option-1</th>
                <th>Option-2</th>
                <th>Option-3</th>
                <th>Option-4</th>
                <th>Right Option</th>
                <th>Answer</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php if(!empty($questions)){
                $i=1;
                foreach ($questions as $que){

                  $question_name = mb_strlen(strip_tags($que->question_name),'utf-8') > 50 ? mb_substr(strip_tags($que->question_name),0,50,'utf-8').'...' : strip_tags($que->question_name);
                  $option_1 = mb_strlen(strip_tags($que->option_1),'utf-8') > 50 ? mb_substr(strip_tags($que->option_1),0,50,'utf-8').'...' : strip_tags($que->option_1);
                  $option_2 = mb_strlen(strip_tags($que->option_2),'utf-8') > 50 ? mb_substr(strip_tags($que->option_2),0,50,'utf-8').'...' : strip_tags($que->option_2);
                  $option_3 = mb_strlen(strip_tags($que->option_3),'utf-8') > 50 ? mb_substr(strip_tags($que->option_3),0,50,'utf-8').'...' : strip_tags($que->option_3);
                  $option_4 = mb_strlen(strip_tags($que->option_4),'utf-8') > 50 ? mb_substr(strip_tags($que->option_4),0,50,'utf-8').'...' : strip_tags($que->option_4);



                  ?>

                  <tr>  
                    <td>{{$i++}}</td>
                    <td>{{$question_name}}</td>
                    <td>{{$option_1}}</td>
                    <td>{{$option_2}}</td>
                    <td>{{$option_3}}</td>
                    <td>{{$option_4}}</td>
                    <td><?php 
                    if($que->right_option == 1){
                      echo "Option-1";
                    }
                    if($que->right_option == 2){
                      echo "Option-2";
                    }
                    if($que->right_option == 3){
                      echo "Option-3";
                    }
                    if($que->right_option == 4){
                      echo "Option-4";
                    }
                  ?></td>
                  <td>{{$que->answer ?? ''}}</td>
                  <td><a class="btn btn-danger" onclick="return confirm('Are You Want To Delete ??');" href="{{ route($routeName.'.read_respond.delete_question', $que->id.'?back_url='.$BackUrl) }}"><i class="fa fa-trash"></i></a></td>
                </tr>



              <?php }}?>
            </tbody>
          </table>
          <?php if(!empty($questions)){?>
            {{ $questions->appends(request()->input())->links('admin.pagination') }}
          <?php }?>
        </div>
      </div> <!-- end card body-->
    </div> <!-- end card -->
  </div><!-- end col-->
</div>










@include('admin.common.footer')


<script type="text/javascript">
  CKEDITOR.replace('text');
</script>


<script type="text/javascript">
  $( document ).ready(function() {
    $('#option_type').hide();
    $('#option_type1').hide();
    $('#answer').hide();
  });



  $( "#type" ).change(function() {
    var value = this.value;

    if(value == 'option'){
     $('#option_type').show();
     $('#option_type1').show();
     $('#answer').hide();

   }
   if(value == 'text'){
    $('#option_type').hide();
    $('#option_type1').hide();
    $('#answer').show();
  }
});
</script>

















<script type="text/javascript">
  $( document ).ready(function() {
    speech();
  });
  $("#text").keyup(function(){
    speech();
  });
  function speech(){
    let speech = new SpeechSynthesisUtterance();
    speech.lang = "en";

    let voices = [];
    window.speechSynthesis.onvoiceschanged = () => {
      voices = window.speechSynthesis.getVoices();
      speech.voice = voices[0];
      let voiceSelect = document.querySelector("#voices");
      voices.forEach((voice, i) => (voiceSelect.options[i] = new Option(voice.name, i)));
    };

    document.querySelector("#rate").addEventListener("input", () => {
      const rate = document.querySelector("#rate").value;
      speech.rate = rate;
      document.querySelector("#rate-label").innerHTML = rate;
    });

    document.querySelector("#volume").addEventListener("input", () => {
      const volume = document.querySelector("#volume").value;
      speech.volume = volume;
      document.querySelector("#volume-label").innerHTML = volume;
    });

    document.querySelector("#pitch").addEventListener("input", () => {
      const pitch = document.querySelector("#pitch").value;
      speech.pitch = pitch;
      document.querySelector("#pitch-label").innerHTML = pitch;
    });

    document.querySelector("#voices").addEventListener("change", () => {
      speech.voice = voices[document.querySelector("#voices").value];
    });

    document.querySelector("#start").addEventListener("click", () => {
      speech.text = document.querySelector("textarea").value;
      window.speechSynthesis.speak(speech);
    });

    document.querySelector("#pause").addEventListener("click", () => {
      window.speechSynthesis.pause();
    });

    document.querySelector("#resume").addEventListener("click", () => {
      window.speechSynthesis.resume();
    });

    document.querySelector("#cancel").addEventListener("click", () => {
      window.speechSynthesis.cancel();
    });
  }
</script>
