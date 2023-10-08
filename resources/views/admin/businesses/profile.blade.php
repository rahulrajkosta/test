@include('admin.common.header')
<?php
$routeName = CustomHelper::getAdminRouteName();
$BackUrl = CustomHelper::BackUrl();

?>
<style>
.blk-flx{
display: flex;
}

.astro-name{
color: #FB7112 !important;
}

.text span{
border: 1px solid #FB7112;
padding: 2px 9px;
border-radius: 21px;
margin: 0px 1px;
line-height: 30px;
background-color: #fb711226;
color: #000;

}

ul h6{
line-height: 23px;
font-size: 14px;
font-weight: 500;
}

.pro-div{
height: 50px;
width: 50px;
background: #fb7112d6;
border-radius: 10px;
text-align: center;
margin-right: 18px !important;
border: 1px solid #8b5c3cd6;
}

.gallery-div{
height: 240px;
width: 350px;
}
.gallery-div img{
width: 100%;
height: 100%;
}
.pro-div h5{
line-height: 46px;
color: #000
}
.language{
line-height: 30px;
}

.btn-danger {
color: #fff;
background-color: #d92605;
border-color: #d92605;
}
.about-me{
line-height: 26px;
}
.submit-btn{
position: absolute;
right: 26px;
height: 38px;
width: 99px;

}

#myBtn{
color: #FB7112;
}
.accordion-item .sn{


font-weight: 700;
color: #FB7112;
line-height: 25px;  

overflow: hidden;
font-size: 15px;
text-overflow: ellipsis;
-webkit-box-orient: vertical;
display: -webkit-box;
-webkit-line-clamp: 1;
}
.accordion-button{
display: block;
width: 100%
}
/*.accordion-body p{

overflow: hidden;
font-size: 15px;
text-overflow: ellipsis;
-webkit-box-orient: vertical;
display: -webkit-box;
-webkit-line-clamp: 4;

}*/
.accordion-header,
.accordion-flush .accordion-item{
width: 100%

}
.gallery-del .fa-trash {
position: absolute;
top: 3px;
z-index: 1050;
height: fit-content;
right: 14px;
color: #FB7112;
background: #fff;
padding: 4px;
font-size: 12px;
/* display: -webkit-box; */
border-radius: 8px;

}

.fa-trash {
position: relative;
bottom:  0px;
z-index: 1050;
right: 0px;
color: #FB7112;
background: #fff;
padding: 7px;
display: -webkit-box;
border-radius: 8px;
}
.status {
position: relative;
right: 2%;
margin-left: auto;
background-color: #fa6374;
color: #fff;
}

input[type=file]::file-selector-button,
{
border: 1px solid #FB7112;
color: black;

border-radius: .2em;
background-color: #8b00009c  !important;
transition: 1s;
}

.star-color i{
color: #FB7112;
font-size: 17px;
}


.btn-primary{
background-color: #FB7112;
border-color: #FB7112;
border: 1px solid #8b5c3cd6;
}

.alert-danger, .alert{
position: fixed;
top: 10%;
z-index: 1050;

box-shadow: 0 2px 6px 0 rgb(218 218 253 / 65%), 0 2px 6px 0 rgb(206 206 238 / 54%);
}

.close{
border:none;
border-radius: 100%;
background-color: #d92605;
color: #fff;
width: 31px;
height: 31px;
}

.edit_faq_hide{
background: #fb711261;
padding: 16px 8px;
border-radius: 8px;
display: none;
} #more {display: none;}

.btn-info {
color: #fff;
background-color: #fb7112;
border-color: #fb7112;
}

.starrating{


margin-right: 65%;




}
.starrating > input {display: none;}  /* Remove radio buttons */
.starrating > label i{
font-size: 34px;
}

.starrating > label
{
color: #222222;

margin: 0 5px; /* Start color when not clicked */
}

.starrating > input:checked ~ label
{ color: #FB7112; } /* Set yellow color when star checked */

.starrating > input:hover ~ label
{ color: #FB7112 ; } /* Set yellow color when star hover */


.view_detail{
display: none;
padding: 10px
}


.card-title{
font-weight: 400;
}
.card-title b{
color: #7f6755;
margin-right: 8px;
font-weight: 500;
}
.gen-btn{
position: absolute;
/* display: block; */
color: #FB7112;
right: 18px;
border: 1px solid;
margin: 8px 2px;
width: fit-content;
padding: 6px 9px;
border-radius: 5px;

}
.repotr--{
color: #FB7112;
padding: 6px 9px;
margin: 8px 2px;
font-weight: 600;
text-align: center;

}
.card_info{
width: 472px;

margin-left: auto;margin-right: auto;
border:1px solid rgb(218 218 253 / 65%);
}


@media screen and (max-width: 425px){


.text span{

padding: 2px 15px;

}
}


@media screen and (max-width: 767px){
.pro-div{
margin-right: 1rem !important;


}



.starrating{


margin-right: 0


}

.blk-flx{
display: block;
}
.gen-btn{
left: 25%;
top:5%;
position: relative;
}

.card_info{
width: auto;
box-shadow: none;

}
}

/* @media screen and (max-width: 425px){
.simplebar-content .align-items-start:nth-child(2) .pro-div{
width: 275px;
}
}*/
</style>

<div class="row">
<div class="col-12">
<div class="page-title-box d-flex align-items-center justify-content-between">
<h4 class="mb-0">Business Profile</h4>

<div class="page-title-right">
<ol class="breadcrumb m-0">
<?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
<a href="{{ url($back_url)}}" style="float:right"><button type="button" class="btn btn-info d-lg-block m-l-15 text-white"><i
class="fa fa-arrow-left"></i>  Back</button></a>
<?php } ?>
</ol>
</div>

</div>
</div>
</div>   

<div class="row mb-4">

<!--------------------- /////////////  SIDECONTENT ASTRO /////////////  ---------------->


<div class="col-xl-4">
<div class="card overflow-hidden">
<div class="profile-user"></div>
<div class="card-body">
<div class="profile-content ">
<div class="profile-user-img text-center">

<?php

$image = isset($business->image) ? $business->image : '';
$storage = Storage::disk('public');
$path = 'business_gallery/';

if(!empty($image))
{
	?>

	<a href="{{ url('public/storage/'.$path.'/'.$image) }}" target='_blank'><img src="{{ url('public/storage/'.$path.'/'.$image) }}" class="avatar-lg rounded-circle img-thumbnail"></a>

<?php }else{ ?>
	<a href="{{ url('public/storage/'.$path.'/default.jpg') }}" target='_blank'><img src="{{ url('public/storage/'.$path.'/default.jpg') }}" class="avatar-lg rounded-circle img-thumbnail"></a>
<?php } ?>

</div>
<h5 class="mt-3 mb-3 astro-name">{{$business->business_name ?? ''}}</h5>                

<p class="text"><b><i class="fas fa-award"></i> Phone: </b>{{$business->mobile 
?? '' }}</p>

<p class="text"><b><i class="fas fa-award"></i> Address: </b>{{$business->address 
?? '' }}</p>
<p class="text"><b><i class="fas fa-award"></i> Pincode: </b>{{$business->pincode ?? ''}}</p>


<p class="text"><b><i class="fas fa-award"></i> Categories: </b>

                    <ul data-role="treeview">
                      <?php
                     $categories = \App\BusinessCategory::where('business_id',$business->id)->get();
                     if(!empty($categories)){
                      foreach ($categories as $key) {
                        $category = \App\Category::where('id',$key->cat_id)->first();
                        ?>
                      <li>{{$category->name??''}}</li>


                      <?php if(!empty($key->sub_cat_id)){
                        $sub_cat_id = explode(",", $key->sub_cat_id);
                        if(!empty($sub_cat_id)){
                          foreach ($sub_cat_id as $key=>$value){
                            $sub_category = DB::table('sub_categories')->where('id',$value)->first();
                            if(!empty($sub_category)){
                            ?>
                            
                         
                       <ul>
                        <li>{{$sub_category->name ??'' }}</li>
                      </ul>
                       <?php } }}}
                        ?>




                    <?php }}?>
                    </ul>
</p>
<?php 
$lat = $business->latitude ??'';
$long = $business->longitude ??'';
	
?>
<p>
	<iframe  width="400" height="400" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" 
  src="https://maps.google.com/maps?q={{$lat}},{{$long}}&hl=es&z=14&amp;output=embed"
 >
 </iframe>
</p>


</div>
</div>
</div>
</div>


<!----------------- /////////  SIDECONTENT ASTRO END //////////////////  ---------------------->


<div class="col-xl-8">
<div class="card mb-0">
<!-- Nav tabs -->
<ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">


<li class="nav-item">
<!-- <a class="nav-link <?php //if($tabs == 'rating') echo "active";?>" data-bs-toggle="tab" href="#rating" role="tab" aria-selected="false"> -->

	<a class="nav-link active" data-bs-toggle="tab" href="#rating" role="tab" aria-selected="false">
	<i class="bx bx-mail-send font-size-20"></i>
	<span class="d-none d-sm-block">Rating & Reviews</span>   
</a>
</li>

<li class="nav-item">
<a class="nav-link <?php if($tabs == 'gallery') echo "active";?>"  data-bs-toggle="tab" href="#gallery" role="tab" aria-selected="false">
	<i class="fas fa-image font-size-20"></i></i>
	<span class="d-none d-sm-block">Gallery</span>   
</a>
</li>



</ul>

<!-- ///////////////////////////////////  TABS ///////////////////////////////////////////////////////  -->

<div class="tab-content p-4">
<!-- /////////////////   ABOUT TAB ///////////////////  -->


<!-- /////////////////   ABOUT TAB END ///////////////////  -->

<!-- ///////////////////////////  TRANSACTION TAB  //////////////////////////// -->


<!-- ///////////////////////////  TRANSACTION TAB END  //////////////////////////// -->


<!------ /////////////////////  RATING TAB ////////////////////////////------------>

<div class="tab-pane active" id="rating" role="tabpanel">
<div>
	<div data-simplebar="init" style="max-height: 430px;">
		<div class="simplebar-wrapper" style="margin: 0px;">
			<div class="simplebar-height-auto-observer-wrapper"><div class="simplebar-height-auto-observer"></div>
		</div>
		<div class="simplebar-mask">
			<div class="simplebar-offset" style="right: 0px; bottom: 0px;">
				<div class="simplebar-content-wrapper" style="height: auto; overflow: hidden;">
					<div class="simplebar-content" style="padding: 0px;">
						<?php if(!empty($ratings)){
							foreach($ratings as $rate){
							?>
						<div class="d-flex align-items-start border p-4">
							<div class="pro-div">
								<h5>
									<?php
									$user = App\User::where('id',$rate->user_id)->first();
									$user_name = $user->name ?? '';
									echo substr($user_name,0,1);
									?>
								</h5>
							</div>




							<div class="flex-grow-1">
								<div style="position: absolute;right: 20px;font-weight: 600;color: #FB7112;" role="button">
									<div style="margin-left: auto;">
										<a  onclick="edit_comment({{$rate->id}})" id="edit_comment{{$rate->id}}">Edit</a></div>
									</div>
									<div id="comment{{$rate->id}}" style="display:none;padding: 10px;">

										<form action="{{route('admin.businesses.ratings')}}" method="POST">
											{{csrf_field()}}
											<input type="hidden" name="id" id="id" value="{{$rate->id}}">
											
											<input type="hidden" name="user_id" id="user_id" value="{{$rate->user_id}}">
											<input type="hidden" name="business_id" id="business_id" value="{{$business->id}}">
											<label>Rating:</label>
											<div class="starrating risingstar d-flex justify-content-left flex-row-reverse my-3">


												<input type="radio" id="star5{{$rate->id}}" <?php if($rate->rating == 5) echo "checked"?> name="rating" value="5" />
												<label for="star5{{$rate->id}}" title="5 star"><i class="fa fa-star"></i></label>


												<input type="radio" id="star4{{$rate->id}}" <?php if($rate->rating == 4) echo "checked"?> name="rating" value="4" />
												<label for="star4{{$rate->id}}" title="4 star"><i class="fa fa-star"></i></label>



												<input type="radio" <?php if($rate->rating == 3) echo "checked"?> id="star3{{$rate->id}}"  name="rating" value="3" />
												<label for="star3{{$rate->id}}" title="3 star"><i class="fa fa-star"></i></label>



												<input type="radio" id="star2{{$rate->id}}" <?php if($rate->rating == 2) echo "checked"?> name="rating" value="2" />
												<label for="star2{{$rate->id}}" title="2 star"><i class="fa fa-star"></i></label>



												<input type="radio" id="star1{{$rate->id}}" <?php if($rate->rating == 1) echo "checked"?>  name="rating" value="1" />
												<label for="star1{{$rate->id}}" title="1 star"><i class="fa fa-star"></i>
												</label>


											</div>

											<label>Comment:</label>
											<textarea type="text" name="review" id="review" class="form-control mb-3"></textarea>
											<button type="submit" class="btn btn-primary text-white">Submit</button>
										</form>
									</div>
									<div id="hide5{{$rate->id}}">
										<h6 class="star-color">                      
											<?php  
											$rates = $rate->rating;
											for($i=0;$i<$rates;$i++){ 

												?>
												<i class="fa fa-star"></i>
											<?php } ?>
										</h6>
										<h6>
											{{$rate->created_at ?? ''}}
										</h6>
										<h6>
										</h6>
										<p class="text-muted">
											{{$rate->review ?? ''}}
										</p>
										<?php
										$user = App\User::where('id',$rate->user_id)->first();
										echo $user->name ?? '';
										?>
									</div>
								</div>
							</div>


							<?php }}?> 

						</div></div></div></div><div class="simplebar-placeholder" style="width: 0px; height: 0px;"></div></div><div class="simplebar-liack simplebar-horizontal" style="visibility: hidden;"><div class="simplebar-scrollbar" style="transform: translate3d(0px, 0px, 0px); display: none;"></div></div><div class="simplebar-track simplebar-vertical" style="visibility: hidden;"><div class="simplebar-scrollbar" style="transform: translate3d(0px, 0px, 0px); display: none; height: 365px;"></div></div></div>
					</div>
				</div>

				<!------ /////////////////////  RATING TAB END  ////////////////////////////------------>


				<!------------ //////////////////   GALLERY TAB //////////////////////////---------------->


				<div class="tab-pane <?php if($tabs == 'gallery') echo "active";?>" id="gallery" role="tabpanel">
					<div>
						<div>
							<h5 class="font-size-16 mb-4">Upload Images</h5>
							<form id="gallery_form" action="{{route('admin.businesses.gallery')}}" method="POST" enctype="multipart/form-data">
								@csrf
								<input type="hidden" name="business_id" value="{{$business->id ?? 0}}">

								<div class="d-flex my-3">
									<input class="form-control" type="file" multiple name="images[]" id="images">

									<button type="submit" id="submit" class="btn btn-primary btn-sm submit-btn">Submit</button>
								</div>
							</form>
							<div class="row"> 
								<?php
								if(!empty($galleries)){
								foreach($galleries as $gallery){
									$images = isset($gallery->file) ? $gallery->file : '';
									$storage = Storage::disk('public');
									$path = 'business_gallery/';
									if(!empty($images))
									{
										?>
										<div class="col-xl-4 col-sm-6 col-xs-6 col-6 mb-3">
											<a href="{{route($routeName.'.businesses.img_delete', ['id'=>$gallery->id,'back_url'=>$BackUrl])}}" onclick="return confirm('Are You Want To Delete ?')" class="gallery-del"><i class="fas fa-trash"></i></a>

											<a href="{{ url('public/storage/'.$path.'/'.$images) }}" class="gallery-div" target="blank"> 
												<img class="card img-fluid" src="{{ url('public/storage/'.$path.'/'.$images) }}">
											</a> 
										</div>                      
									<?php } } }?>
								</div>
								
							</div>
						</div>
					</div>

					<!------------ //////////////////   GALLERY TAB END  //////////////////////////---------------->


					<!------------ ////////////////////  ASKED QUESTIONS TAB ///////////////// ----------------------->

					

			<!------------////////////////////  ASKED QUESTIONS TAB END ///////////////// ----------------------->

	
				</div>
			</div>
		</div>
	</div>

	@include('admin.common.footer');

<script>

function edit_comment(rate_id){
$('#comment'+rate_id).toggle(1000);
//$('#hide5'+rate_id).hide();
}

function view_report(id){
$('#view_detail'+id).toggle(1000);

}

function edit_btn(id){
CKEDITOR.replace( 'answer'+id );
CKEDITOR.replace( 'question'+id );
$('#edit_faq'+id).toggle(1000);

}

function myFunction() {
var dots = document.getElementById("dots");
var moreText = document.getElementById("more");
var btnText = document.getElementById("myBtn");

if (dots.style.display === "none") {
dots.style.display = "inline";
moreText.style.display = "none";

} else {
dots.style.display = "none";
btnText.style.display = "none";
moreText.style.display = "inline";
}
}
</script>

											