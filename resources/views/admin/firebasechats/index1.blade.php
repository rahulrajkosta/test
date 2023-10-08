
@include('admin.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();


$storage = Storage::disk('public');
$path = 'influencer/thumb/';
// $roleId = Auth::guard('admin')->user()->role_id; 

?>
<style type="text/css">
  .chatactive{
    background: antiquewhite;
  }
</style>
<!-- BEGIN: Content-->
<div class="app-content content">
  <div class="sidebar-left sidebar-fixed">
    <div class="sidebar"><div class="sidebar-content card d-none d-lg-block">
      <div class="card-body chat-fixed-search">
        <fieldset class="form-group position-relative has-icon-left m-0  w-75 display-inline">
          <input type="text" class="form-control round" id="searchUser" placeholder="Search user">
          <div class="form-control-position">
            <i class="fa fa-search"></i>
          </div>            
        </fieldset>  
      </div>
      <div id="users-list" class="list-group position-relative" >
        <div class="users-list-padding media-list" >
          <div id="user_list">

          </div>

        </div>
      </div>
    </div>

  </div>
</div>
<div class="content-right">
  <div class="content-wrapper">
    <div class="content-wrapper-before"></div>
    <div class="content-header row">
    </div>
    <div class="content-body"><section class="chat-app-window" id="chat_scroll">
      <div class="mb-1 secondary text-bold-700 d-flex">Chat History - <div id="user_name"></div></div>
      <div class="chats">
        <div class="chats">
            <div id="chat-data">
              
            </div>


          </div>
      </div>
    </section>
    <section class="chat-app-form">
      <form class="chat-app-input d-flex" action="#" onsubmit="return false">
        <fieldset class="col-10 m-0">
          <div class="input-group position-relative has-icon-left">
            <div class="form-control-position">
              <span id="basic-addon3"><i class="ft-image"></i></span>
            </div>
            <input type="text" id="message" name="message" class="form-control" placeholder="Send message" aria-describedby="button-addon2">
          </div>
        </fieldset>
        <fieldset class="form-group position-relative has-icon-left col-2 m-0">
          <button  class="btn btn-danger" onclick="chat_submit()">
            <i class="la la-paper-plane-o d-xl-none"></i>
            <span class="d-none d-lg-none d-xl-block">Send Message </span>
          </button>
        </fieldset>
      </form>
    </section>
  </div>
</div>
</div>
</div>
<!-- END: Content-->


<!-- BEGIN: Customizer-->
<div class="customizer border-left-blue-grey border-left-lighten-4 d-none d-xl-block"><a class="customizer-close" href="#"><i class="ft-x font-medium-3"></i></a><a class="customizer-toggle bg-primary box-shadow-3" href="#" id="customizer-toggle-setting"><i class="ft-settings font-medium-3 spinner white"></i></a><div class="customizer-content p-2">
  <h5 class="mt-1 mb-1 text-bold-500">Navbar Color Options</h5>
  <div class="navbar-color-options clearfix">
    <div class="gradient-colors mb-1 clearfix">
      <div class="bg-gradient-x-purple-blue navbar-color-option" data-bg="bg-gradient-x-purple-blue" title="bg-gradient-x-purple-blue"></div>
      <div class="bg-gradient-x-purple-red navbar-color-option" data-bg="bg-gradient-x-purple-red" title="bg-gradient-x-purple-red"></div>
      <div class="bg-gradient-x-blue-green navbar-color-option" data-bg="bg-gradient-x-blue-green" title="bg-gradient-x-blue-green"></div>
      <div class="bg-gradient-x-orange-yellow navbar-color-option" data-bg="bg-gradient-x-orange-yellow" title="bg-gradient-x-orange-yellow"></div>
      <div class="bg-gradient-x-blue-cyan navbar-color-option" data-bg="bg-gradient-x-blue-cyan" title="bg-gradient-x-blue-cyan"></div>
      <div class="bg-gradient-x-red-pink navbar-color-option" data-bg="bg-gradient-x-red-pink" title="bg-gradient-x-red-pink"></div>
    </div>
    <div class="solid-colors clearfix">
      <div class="bg-primary navbar-color-option" data-bg="bg-primary" title="primary"></div>
      <div class="bg-success navbar-color-option" data-bg="bg-success" title="success"></div>
      <div class="bg-info navbar-color-option" data-bg="bg-info" title="info"></div>
      <div class="bg-warning navbar-color-option" data-bg="bg-warning" title="warning"></div>
      <div class="bg-danger navbar-color-option" data-bg="bg-danger" title="danger"></div>
      <div class="bg-blue navbar-color-option" data-bg="bg-blue" title="blue"></div>
    </div>
  </div>

  <hr>

  <h5 class="my-1 text-bold-500">Layout Options</h5>
  <div class="row">
    <div class="col-12">
      <div class="d-inline-block custom-control custom-radio mb-1 col-4">
        <input type="radio" class="custom-control-input bg-primary" name="layouts" id="default-layout" checked>
        <label class="custom-control-label" for="default-layout">Default</label>
      </div>
      <div class="d-inline-block custom-control custom-radio mb-1 col-4">
        <input type="radio" class="custom-control-input bg-primary" name="layouts" id="fixed-layout">
        <label class="custom-control-label" for="fixed-layout">Fixed</label>
      </div>
      <div class="d-inline-block custom-control custom-radio mb-1 col-4">
        <input type="radio" class="custom-control-input bg-primary" name="layouts" id="static-layout">
        <label class="custom-control-label" for="static-layout">Static</label>
      </div>
      <div class="d-inline-block custom-control custom-radio mb-1">
        <input type="radio" class="custom-control-input bg-primary" name="layouts" id="boxed-layout">
        <label class="custom-control-label" for="boxed-layout">Boxed</label>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      <div class="d-inline-block custom-control custom-checkbox mb-1">
        <input type="checkbox" class="custom-control-input bg-primary" name="right-side-icons" id="right-side-icons">
        <label class="custom-control-label" for="right-side-icons">Right Side Icons</label>
      </div>
    </div>
  </div>

  <hr>

  <h5 class="mt-1 mb-1 text-bold-500">Sidebar menu Background</h5>
  <!-- <div class="sidebar-color-options clearfix">
    <div class="bg-black sidebar-color-option" data-sidebar="menu-dark" title="black"></div>
    <div class="bg-white sidebar-color-option" data-sidebar="menu-light" title="white"></div>
  </div> -->
  <div class="row sidebar-color-options ml-0">
    <label for="sidebar-color-option" class="card-title font-medium-2 mr-2">White Mode</label>
    <div class="text-center mb-1">
      <input type="checkbox" id="sidebar-color-option" class="switchery" data-size="xs"/>
    </div>
    <label for="sidebar-color-option" class="card-title font-medium-2 ml-2">Dark Mode</label>
  </div>

  <hr>

  <label for="collapsed-sidebar" class="font-medium-2">Menu Collapse</label>
  <div class="float-right">
    <input type="checkbox" id="collapsed-sidebar" class="switchery" data-size="xs"/>
  </div>
  
  <hr>

  <!--Sidebar Background Image Starts-->
  <h5 class="mt-1 mb-1 text-bold-500">Sidebar Background Image</h5>
  <div class="cz-bg-image row">
    <div class="col mb-3">
      <img src="{{url('/assets/antitheft/')}}/app-assets/images/backgrounds/04.jpg" class="rounded sidiebar-bg-img" width="50" height="100" alt="background image">
    </div>
    <div class="col mb-3">
      <img src="{{url('/assets/antitheft/')}}/app-assets/images/backgrounds/01.jpg" class="rounded sidiebar-bg-img" width="50" height="100" alt="background image">
    </div>
    <div class="col mb-3">
      <img src="{{url('/assets/antitheft/')}}/app-assets/images/backgrounds/02.jpg" class="rounded sidiebar-bg-img" width="50" height="100" alt="background image">
    </div>
    <div class="col mb-3">
      <img src="{{url('/assets/antitheft/')}}/app-assets/images/backgrounds/05.jpg" class="rounded sidiebar-bg-img" width="50" height="100" alt="background image">
    </div>
  </div>
  <!--Sidebar Background Image Ends-->

  <!--Sidebar BG Image Toggle Starts-->
  <div class="sidebar-image-visibility">
    <div class="row ml-0">
      <label for="toggle-sidebar-bg-img" class="card-title font-medium-2 mr-2">Hide Image</label>
      <div class="text-center mb-1">
        <input type="checkbox" id="toggle-sidebar-bg-img" class="switchery" data-size="xs" checked/>
      </div>
      <label for="toggle-sidebar-bg-img" class="card-title font-medium-2 ml-2">Show Image</label>
    </div>
  </div>
  <!--Sidebar BG Image Toggle Ends-->

  <hr>

  <div class="row mb-2">
    <div class="col">
      <a href="https://themeselection.com/" class="btn btn-primary btn-block box-shadow-1" target="_blank">More Themes</a>
    </div>
  </div>
  <div class="text-center">
    <button id="twitter" class="btn btn-social-icon btn-twitter sharrre mr-1"><i class="la la-twitter"></i></button>
    <button id="facebook" class="btn btn-social-icon btn-facebook sharrre mr-1"><i class="la la-facebook"></i></button>
    <button id="google" class="btn btn-social-icon btn-google sharrre"><i class="la la-google"></i></button>
  </div>
</div>
</div>
<!-- End: Customizer-->
<input type="hidden" name="user_id" id="user_id" value="{{$user_id ?? 0}}">
<input type="hidden" name="page" id="page" value="1">


@include('admin.common.footer')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


<script>
 $(document).ready(function(){
   var user_id = $('#user_id').val();
   var page = $('#page').val();
    //  setInterval(function() {
    //   get_chats();
    // }, 5000);
  });


 $("#chat_scroll").scroll(function() {
  var page = $('#page').val();
  var objDiv = document.getElementById('chat_scroll');
  var doScroll=objDiv.scrollTop>=(objDiv.scrollHeight-objDiv.clientHeight);    
  if( doScroll) {
    objDiv.scrollTop = objDiv.scrollHeight;
    page++;
    var user_id = $('#user_id').val();
    $('#page').val(page);
    var user_id = $('#user_id').val();
    get_chats(user_id,page);
  }
});





 function chat_submit(){
  var user_id = $('#user_id').val();
  var page = $('#page').val();
  var message = $('#message').val();
  if(message==''){
    alert('Type Something!!');
    return false;
  }
  var _token = '{{ csrf_token() }}';
  $.ajax({
    url: "{{ route($routeName.'.firebasechats.send_message') }}",
    type: "get",
    data: {user_id:user_id,message:message},
    dataType:"HTML",
    headers:{'X-CSRF-TOKEN': _token},
    cache: false,
    success: function(resp){
      $('#message').val('');
      get_user_chat(user_id,page);
    }
  });
}



function openFileOption(){
  document.getElementById("file").click();
}


$('#file').change(function() {


  var user_id = $('#user_id').val();
  var page = $('#page').val();
  var data = new FormData();
  data.append('file', $('#file').prop('files')[0]);
  data.append('reciever_id', user_id);
  var _token = '{{ csrf_token() }}';
  $.ajax({
    type: 'post',
    url: "{{ route($routeName.'.firebasechats.upload_file') }}",
    processData: false,
    contentType: false,
    data: data,
    dataType:"JSON",
    headers:{'X-CSRF-TOKEN': _token},
    cache: false,
    success: function (response) {
      get_chats(user_id,page);   
    },
  });
});




$( document ).ready(function() {
  var user_id = $('#user_id').val();
  if(user_id!=0){
    get_user_list(search='',user_id);
    get_user_name(user_id);
    get_user_chat(user_id);
  }
});


function get_user_name(user_id)
{
  var _token = '{{ csrf_token() }}';
  $.ajax({
    url: "{{ route($routeName.'.firebasechats.get_user_name') }}",
    type: "POST",
    data: {user_id:user_id},
    dataType:"HTML",
    headers:{'X-CSRF-TOKEN': _token},
    cache: false,
    success: function(resp){
      $('#user_id').val(user_id);
      $('#user_name').html(resp);
    }
  });
}



$('#searchUser').keyup(function() {
  var search = this.value;
  var length = search.length;
  if(length >= 3){         
    get_user_list(search); 
  }
});


function get_user_list(search='',user_id=''){
 var _token = '{{ csrf_token() }}';
 var page = $('#page').val();
 $.ajax({
  url: "{{ route($routeName.'.firebasechats.get_user_list') }}",
  type: "POST",
  data: {search:search,user_id:user_id},
  dataType:"HTML",
  headers:{'X-CSRF-TOKEN': _token},
  cache: false,
  success: function(resp){
    $('#user_list').html(resp);


  }
});
}


function get_user_chat(user_id,page=1){

  var page = $('#page').val();
  $('#user_id').val(user_id);
    // alert(user_id);
    get_user_name(user_id);
    get_user_list(search='',user_id,page=1); 

    get_chats(user_id,page);   
  }
  
  function get_chats(user_id,page=1){
    var user_id = $('#user_id').val();
    var page = $('#page').val();
    var _token = '{{ csrf_token() }}';
    $.ajax({
      url: "{{ route($routeName.'.firebasechats.get_user_chat') }}",
      type: "get",
      data: {user_id:user_id,page:page},
      dataType:"HTML",
      headers:{'X-CSRF-TOKEN': _token},
      cache: false,
      success: function(resp){
        $("#chat-data").html(resp);
      }
    });
  }

</script>