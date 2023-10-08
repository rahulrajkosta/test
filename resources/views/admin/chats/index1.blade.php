
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
              <span id="basic-addon3"><i class="fa fa-envelope"></i></span>
            </div>
            <input type="text" id="message" name="message" class="form-control" placeholder="Send message" aria-describedby="button-addon2">
          </div>
        </fieldset>
        <fieldset class="form-group position-relative has-icon-left col-2 m-0">
          <button  class="btn btn-danger" onclick="chat_submit()">
            <i class="fa fa-paper-plane"></i>
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
    url: "{{ route($routeName.'.chats.send_message') }}",
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
    url: "{{ route($routeName.'.chats.upload_file') }}",
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
  get_user_list(search='',user_id);
  if(user_id!=0){
    get_user_name(user_id);
    get_user_chat(user_id);
  }
});


function get_user_name(user_id)
{
  var _token = '{{ csrf_token() }}';
  $.ajax({
    url: "{{ route($routeName.'.chats.get_user_name') }}",
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
  url: "{{ route($routeName.'.chats.get_user_list') }}",
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
      url: "{{ route($routeName.'.chats.get_user_chat') }}",
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