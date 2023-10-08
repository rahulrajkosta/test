@include('admin.common.header')

<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();


$storage = Storage::disk('public');
$path = 'influencer/thumb/';
// $roleId = Auth::guard('admin')->user()->role_id; 

?>
<style>
  
  .chat-list li .chat-img {
    display: inline-block;
    width: 45px;
    vertical-align: top;
}

</style>

<div class="content-page">
  <div class="content">

    <!-- Start Content-->
    <div class="container-fluid">

      <!-- start page title -->
      <div class="row">
        <div class="col-12">
          <div class="page-title-box">
            <div class="page-title-right">

            </div>
            <h4 class="page-title">Chats</h4>
          </div>
        </div>
      </div>     
      <!-- end page title --> 
      <div class="row">
        <!-- start chat users-->
        <div class="col-xl-3 col-lg-4">
          <div class="card">
            <div class="card-body">

              <div class="d-flex align-items-start mb-3">
                <img src="{{asset('assets/new/images/users/user-1.jpg')}}" class="me-2 rounded-circle" height="42" alt="Brandon Smith">
                <div class="w-100">
                  <h5 class="mt-0 mb-0 font-15">
                    <a class="text-reset">{{Auth::guard('admin')->user()->name ?? ''}}</a>
                  </h5>
                </div>
              </div>

              <!-- start search box -->
              <form class="search-bar mb-3">
                <div class="position-relative">
                  <input type="text" class="form-control form-control-light" id="search" placeholder="People, groups & messages...">
                  <span class="fa fa-search"></span>

                </div>
              </form>
              <!-- end search box -->

           

              <h6 class="font-13 text-muted text-uppercase mb-2">Contacts</h6>

              <!-- users -->
              <div class="row">
                <div class="col">
                  <div data-simplebar style="max-height:400px;">

                            <!-- Student Lists -->
                            <!-- bg-light    (Used For Active ) -->
                  <!--   <a href="javascript:void(0);" class="text-body"> -->
                      <div class="d-flex align-items-start bg-light p-2">
                      
                        <div class="w-100">
                          <h5 class="mt-0 mb-0 font-14">
                            <span class="float-end text-muted fw-normal font-12">Wed</span>
                            <ul class="chatonline style-none ps ps--theme_default ps--active-y" data-ps-id="ebaf5428-568b-1f1b-b861-a1a4b7ededc3">
                        <p id="user_list">

                        </p>
                       <!--  <li class="p-20"></li> -->
                        
                    </ul>
                          </h5>
                        </div>
                      </div>
                  <!--   </a> -->



                       <!-- Student Lists End -->

                       <div class="ps__scrollbar-x-rail" style="left: 0px; bottom: 0px;">
                          <div class="ps__scrollbar-x" tabindex="0" style="left: 0px; width: 0px;"></div>
                        </div>
                        <div class="ps__scrollbar-y-rail" style="top: 0px; right: 0px; height: 486px;">
                          <div class="ps__scrollbar-y" tabindex="0" style="top: 0px; height: 364px;">
                            
                          </div>
                      </div>

                  </div>
                  <!-- end slimscroll-->
                </div>
                <!-- End col -->
              </div>                                        
              <!-- end users -->
            </div> <!-- end card-body-->
          </div> <!-- end card-->
        </div>
        <!-- end chat users-->

        <!-- chat area -->
        <div class="col-xl-9 col-lg-8">

          <div class="card">


            <div class="card-body py-2 px-3 border-bottom border-light">
               

              <div class="row justify-content-between py-1">
                <div class="col-sm-7">
                  <div class="d-flex align-items-start">
                    <img src="{{asset('assets/new/images/users/user-3.jpg')}}" class="me-2 rounded-circle" height="36" alt="Brandon Smith">
                    <div>
                     
                    </div>
                  </div>
                </div>
               
              </div> 
            </div>


            <div class="card-body">
              <ul class="conversation-list chat-list" data-simplebar style="max-height: 460px;" id="chat_scroll">

                <p id = "chat-data">
                      
                </p>
                <li class="clearfix">

                  <div class="chat-avatar">
                  <!--   <img src="{{asset('assets/new/images/users/user-3.jpg')}}" class="rounded" alt="James Z" />
                    <i>10:00</i> -->
                  </div>
                  <div class="conversation-text">
                    <div class="ctext-wrap">
                      
                      
                    </div>
                  </div>
                </li>
                <!-- <li class="clearfix odd">
                  <div class="chat-avatar">
                    <img src="{{asset('assets/new/images/users/user-1.jpg')}}" class="rounded" alt="Geneva M" />
                    <i>10:01</i>
                  </div>
                  <div class="conversation-text">
                    <div class="ctext-wrap">
                      <i>Geneva M</i>
                      <p>
                        Hi, How are you? What about our next meeting?
                      </p>
                    </div>
                  </div>
                 
                </li> -->
               <!--  <li class="clearfix">
                  <div class="chat-avatar">
                    <img src="{{asset('assets/new/images/users/user-5.jpg')}}" class="rounded" alt="James Z" />
                    <i>10:01</i>
                  </div>
                  <div class="conversation-text">
                    <div class="ctext-wrap">
                      <i>James Z</i>
                      <p>
                        Yeah everything is fine
                      </p>
                    </div>
                  </div>
                </li> -->
                <!-- <li class="clearfix odd">
                  <div class="chat-avatar">
                    <img src="{{asset('assets/new/images/users/user-1.jpg')}}" class="rounded" alt="Geneva M" />
                    <i>10:02</i>
                  </div>
                  <div class="conversation-text">
                    <div class="ctext-wrap">
                      <i>Geneva M</i>
                      <p>
                        Wow that's great
                      </p>
                    </div>
                  </div>
                 
                </li>
                <li class="clearfix">
                  <div class="chat-avatar">
                    <img src="{{asset('assets/new/images/users/user-5.jpg')}}" alt="James Z" class="rounded" />
                    <i>10:02</i>
                  </div>
                  <div class="conversation-text">
                    <div class="ctext-wrap">
                      <i>James Z</i>
                      <p>
                        Let's have it today if you are free
                      </p>
                    </div>
                  </div>
                 
                </li> -->
                <!-- <li class="clearfix odd">
                  <div class="chat-avatar">
                    <img src="{{asset('assets/new/images/users/user-1.jpg')}}" alt="Geneva M" class="rounded" />
                    <i>10:03</i>
                  </div>
                  <div class="conversation-text">
                    <div class="ctext-wrap">
                      <i>Geneva M</i>
                      <p>
                        Sure thing! let me know if 2pm works for you
                      </p>
                    </div>
                  </div>
                 
                </li>
                <li class="clearfix">
                  <div class="chat-avatar">
                    <img src="{{asset('assets/new/images/users/user-5.jpg')}}" alt="James Z" class="rounded" />
                    <i>10:04</i>
                  </div>
                  <div class="conversation-text">
                    <div class="ctext-wrap">
                      <i>James Z</i>
                      <p>
                        Sorry, I have another meeting scheduled at 2pm. Can we have it at 3pm instead?
                      </p>
                    </div>
                  </div>
                 
                </li> -->
               <!--  <li class="clearfix">
                  <div class="chat-avatar">
                    <img src="{{asset('assets/new/images/users/user-5.jpg')}}" alt="James Z" class="rounded" />
                    <i>10:04</i>
                  </div>
                  <div class="conversation-text">
                    <div class="ctext-wrap">
                      <i>James Z</i>
                      <p>
                        We can also discuss about the presentation talk format if you have some extra mins
                      </p>
                    </div>
                  </div>
                  
                </li> -->
                <!-- <li class="clearfix odd">
                  <div class="chat-avatar">
                    <img src="{{asset('assets/new/images/users/user-1.jpg')}}" alt="Geneva M" class="rounded" />
                    <i>10:05</i>
                  </div>
                  <div class="conversation-text">
                    <div class="ctext-wrap">
                      <i>Geneva M</i>
                      <p>
                        3pm it is. Sure, let's discuss about presentation format, it would be great to finalize today. I am attaching the last year format and assets here...
                      </p>
                    </div>
                    <div class="card mt-2 mb-1 shadow-none border text-start">
                      <div class="p-2">
                        <div class="row align-items-center">
                          <div class="col-auto">
                            <div class="avatar-sm">
                              <span class="avatar-title bg-primary rounded">
                                .ZIP
                              </span>
                            </div>
                          </div>
                          <div class="col ps-0">
                            <a href="javascript:void(0);" class="text-muted fw-bold">UBold-sketch.zip</a>
                            <p class="mb-0">2.3 MB</p>
                          </div>
                          <div class="col-auto">
                           
                            <a href="javascript:void(0);" class="btn btn-link btn-lg text-muted">
                              <i class="dripicons-download"></i>
                            </a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </li> -->
              </ul>

              <div class="row">
                <div class="col">
                  <div class="mt-2 bg-light p-3 rounded">
                    <form class="needs-validation" novalidate="" name="chat-form" id="chat-form">
                      <div class="row">
                        <div class="col mb-2 mb-sm-0">
                           <textarea placeholder="Type your message here" id="message" class="form-control border-0"></textarea>
                          <div class="invalid-feedback">
                            Please enter your messsage
                          </div>
                        </div>
                        <div class="col-sm-auto">
                          <div class="btn-group">
                           <!--  <a href="#" class="btn btn-light"><i class="fe-paperclip"></i></a> -->
                            <button type="submit" class="btn btn-success chat-send w-100" id="send_message"><i class="fas fa-paper-plane"></i></button>
                          </div>
                        </div>
                        <!-- end col -->
                      </div>
                      <!-- end row-->
                    </form>
                  </div>
                </div>
                <!-- end col-->
              </div>
              <!-- end row -->
            </div>
            <!-- end card-body -->                                    
          </div> <!-- end card -->
        </div>
        <!-- end chat area-->

      </div> <!-- end row-->


    </div>
  </div>
</div>

<?php //print_r($user_id); ?>

   <input type="hidden" name="user_id" id="user_id" value="{{$user_id ?? 0}}">
<input type="hidden" name="page" id="page" value="1">


@include('admin.common.footer')

<script>

  $('#search').keyup(function() {

      var search = this.value;
      var length = search.length;

      if(length >= 5)
      {         
        get_user_list(search ='',user_id); 
      }
  });
  
  $(document).ready(function() {

   var search = '';
   var page = 1;
    var user_id = $('#user_id').val();
    get_user_name(user_id);
    get_user_list(search ='',user_id);


$('#chat_scroll').on('scroll',function() {
  page++;
  var user_id = $('#user_id').val();

  $('#page').val(page);
  get_user_chat(user_id,page);
});

    get_user_chat(user_id,page);

  });

  $(document).ready(function(){
 var user_id = $('#user_id').val();
  var page = $('#page').val();
 setInterval(get_user_chat(user_id,page),2000);
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

                //  alert("resp = "+resp);
                    $('#user_id').html(resp);
                }
            });
  }

   function get_user_list(search='',user_id='')
   {        
        var _token = '{{ csrf_token() }}';
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


     function get_user_chat(user_id='',page=1)
     {
            $('#user_id').val(user_id);
            if(page == 1){
                $("#chat-data").html('');
            }

            get_user_name(user_id);
            get_user_list(search='',user_id)

            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ route($routeName.'.chats.get_user_chat') }}",
                type: "get",
                data: {user_id:user_id,page:page},
                dataType:"HTML",
                headers:{'X-CSRF-TOKEN': _token},
                cache: false,
                success: function(resp){
                //$('#hospital_list').html(resp);
                //alert(resp);
                $("#chat-data").html(resp);
            }
        });

        }

     $("#send_message").click(function(){
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
       });


</script>