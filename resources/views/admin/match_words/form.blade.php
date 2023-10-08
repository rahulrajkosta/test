@include('admin.common.header')


<style>
	.remove-btn{
		position: absolute;
		left: -41px;
		top: -18px;
	}
	#parent{
		height: 100%;
	}
</style>

<?php
$BackUrl = CustomHelper::BackUrl();
// $SADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();

$routeName = CustomHelper::getAdminRouteName();
$storage = Storage::disk('public');
$path = 'match_words/';

 // $word_id = isset($word_of_day->id) ? $word_of_day->id : '';
 // $type_id = isset($word_of_day->type) ? $word_of_day->type : '';
 // $word = isset($word_of_day->word) ? $word_of_day->word : '';
 // $meaning = isset($word_of_day->meaning) ? $word_of_day->meaning : '';
 // $date = isset($word_of_day->date) ? $word_of_day->date : ''; 
 // $status = isset($word_of_day->status) ? $word_of_day->status : '';

 // $type = config('custom.daily_learnings');


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
				<div class="d-flex">
					<h4 class="header-title me-5">{{ $page_Heading }}</h4>
					<a class="btn btn-info " onclick="cloneFunction()">
								<i class="fa fa-plus"></i> Add More
							</a>
				</div>
				

				<form class="card-body"  action="{{route($routeName.'.match_words.add', ['back_url' => $BackUrl])}}" method="post" accept-chartset="UTF-8" enctype="multipart/form-data" role="form">

					{{ csrf_field() }}

					<!-- <input type="hidden" id="id"  value=""> -->
					<div class="row">
						<div class="col-md-5">
							<h5>Word</h5>
						</div>
						<div class="col-md-5">
							<h5>Match</h5>
						</div>
						

					</div>
					

					<div class="mb-3 row" id="parent">
 
						<div class="col-md-5  my-3 ggg">
							<input class="form-control" type="text"  name="word" id="example-file-input">
						</div>
						<div class="col-md-5  my-3 ggg">
							<input class="form-control" type="text"   name="match" id="example-file-input">
						</div>	

					</div>


					<div id="childattribute" style="display:none;">

						<div class="col-md-5  my-3 ggg">
							<input class="form-control" type="text" name="child_word[]"  id="example-file-input">
						</div>				 

						<div class="col-md-5  my-3 ggg">
							<input class="form-control" type="text"  name="child_match[]" id="example-file-input">
						</div>
						<!-- <div class="col-md-2 my-3 ggg">
							<a class="btn btn-info" onclick="removeattri()">
								<i class="fa fa-trash"></i>
							</a>
						</div> -->

					</div>
						<button class="btn btn-info" type="submit">Submit</button>



				</form>
			</div>
		</div> <!-- end card-->
	</div> <!-- end col-->
</div>


@include('admin.common.footer')

<script>

	function cloneFunction() {
		var html = $("#childattribute").html();
		$("#parent").append(html);
	}

	function removeattri(id) {
            const myobj = $("#parent .ggg");
            myobj.remove();            
        }
    </script>

