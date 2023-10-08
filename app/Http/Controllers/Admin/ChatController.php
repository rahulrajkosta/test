<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\Controller;
use App\Helpers\CustomHelper;
use Auth;
use Validator;
use App\User;
use App\Admin;
use App\Course;
use App\Chats;
use App\Category;
use App\City;
use App\SubCategory;

use Yajra\DataTables\DataTables;


use Storage;
use DB;
use Hash;

use PhpOffice\PhpWord\IOFactory;




Class ChatController extends Controller
{
    private $limit;   
    private $ADMIN_ROUTE_NAME;

    public function __construct()
    {
        $this->limit = 100;
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
    }

    public function index(Request $request)
    {
        $data = [];
        $chats = Chats::select('sender_id','reciever_id')->where('sender_id','!=',0)->orWhere('reciever_id','!=',0)->latest()->first();
        $user_id = 0;
        // $user_id = [];

//         if(!empty($chats)){
//             foreach($chats as $c){         
//              $sender_id = $c->sender_id;                    
//              if(!in_array($c->sender_id, $user_id)){ 
//                 if($c->reciever_id != 0){
//                    $user_id[] = $c->reciever_id;
//                }          
//            }
//            if(!in_array($c->reciever_id, $user_id)){
//             if($c->reciever_id != 0){
//                $user_id[] = $c->reciever_id;
//            }               
//        }
//    }
// }
        if(!empty($chats)){
            if($chats->sender_id !=0){
                $user_id = $chats->sender_id;
            }
            if($chats->reciever_id !=0){
                $user_id = $chats->reciever_id;
            }
        }
        

// if(!empty($user_id)){
//     $data['user_id'] = $user_id[0];
// }         

        // pr($user_id);
        $data['user_id'] = $user_id;
        $data['chats'] = $chats;
        return view('admin.chats.index1',$data);
    }



    


    public function get_user_name(Request $request)
    {    
        $user_id = isset($request->user_id) ? $request->user_id : 0;
        $html = '';
        $user = User::where('id', $user_id)->first();
        $imgUrl = config('custom.NO_IMG');
        $storage = Storage::disk('public');
        $path = 'user/';
        $image_name = $user->image ?? '';
        if(!empty($image_name)){
            if($storage->exists($path.$image_name)){
              $imgUrl =  url('storage/app/public/'.$path.'/'.$image_name);
          }
      }


        $html .= ' <div class="d-flex align-items-start">
        <img src='.$imgUrl.' class="me-2 rounded-circle" height="36" alt="Brandon Smith">
        <div>
        <h5 class="mt-0 mb-0 font-15">
        <a class="text-reset">'.$user->name ?? ''.'</a>
        </h5>
        </div>
        </div>';

        echo $html;
    }

    public function get_user_list(Request $request)
    {
        $html = '';
        $search = isset($request->search) ? $request->search : '';
        $user_id = isset($request->user_id) ? $request->user_id : 0;

        $chats = Chats::select('sender_id','reciever_id')->latest()->get();
        $user_ids = [];
        if(!empty($chats)){
            foreach($chats as $c){         
               if(!in_array($c->sender_id, $user_ids)){ 
                if($c->sender_id != 0){
                 $user_ids[] = $c->sender_id;
             }          
         }
         if(!in_array($c->reciever_id, $user_ids)){
            if($c->reciever_id != 0){
             $user_ids[] = $c->reciever_id;
         }               

     }
 }
}


if(!empty($user_ids)){
    if(empty($search)){
        $user_ids1 = implode(',', $user_ids);
        $users = User::select('id','name','image')->whereIn('id', $user_ids);
        $users->orderByRaw("FIELD(id, $user_ids1)");

        $users = $users->get();  
    }else{
      $users = User::select('id','name','image')->where('status',1)->where('is_delete',0);
      $users->where('name','like','%'.$search.'%');
      $users = $users->limit(50)->get();   
  }

  if(!empty($users)){
    $i=1;
    foreach($users as $user){
        $active = '';
        if($user->id == $user_id){
            $active = 'chatactive';
        }
        
        $imgUrl = config('custom.NO_IMG');
        $storage = Storage::disk('public');
        $path = 'user/';
        $image_name = $user->image ?? '';
        if(!empty($image_name)){
            if($storage->exists($path.$image_name)){
              $imgUrl =  url('storage/app/public/'.$path.'/'.$image_name);
          }
      }

      $html.='<a onclick="get_user_chat('.$user->id.',1)" class="media border-bottom-blue-grey border-bottom-lighten-5 '.$active.'">
      <div class="media-left pr-1"><span class="avatar avatar-md"><img class="media-object rounded-circle" src='.$imgUrl.' alt="Generic placeholder image">                    
      </span>
      </div>
      <div class="media-body w-100">
      <h6 class="list-group-item-heading font-medium-1 text-bold-700">'.$user->name.'</h6>
      </div>
      </a>';
      $i++;
  }
}
}



echo $html;

}




public function upload_file(Request $request){

    $dbArray = [];
    $reciever_id = $request->reciever_id;
    if($request->hasFile('file')){
        $file = $request->file('file');

        $extension = $file->getClientOriginalExtension();
        $image = $this->saveImage($request);  
        $dbArray['reciever_id'] = $reciever_id;
        $dbArray['sender_id'] = 0;
        $dbArray['sender_type'] = 'admin';
        $dbArray['reciever_type'] = 'user';
        $dbArray['text'] = $image;
        $dbArray['is_file'] = 1;
        $dbArray['file_type'] = $extension;
        Chats::insert($dbArray);

    }
    echo 1;

}

private function saveImage($request){
    $file = $request->file('file');
    if ($file) {
        $path = 'chats/';
        $storage = Storage::disk('public');
        $uploaded_data = CustomHelper::UploadFileNew($file, $path,$ext='');
        if($uploaded_data['success']){
            $image = $uploaded_data['file_name'];
           // $type = $uploaded_data['extension'];
            return $image;
        }else{
            //return false;
        }
    }
}

public function get_user_chat(Request $request){
    $page = isset($request->page) ? $request->page :1;
    $user_id = isset($request->user_id) ? $request->user_id :'';
    $html = '';
    $perpage = 10;
    $count = $perpage * $page;
    $chats = Chats::where('sender_id','=',$user_id)->orWhere('reciever_id','=',$user_id)->skip(0)->take($count)->get();
    $admin = Auth::guard('admin')->user();
    // print_r($chats);
    if(!empty($chats)){
        foreach($chats as $chat){
            if($chat->sender_id !=0){
                $user = User::where('id',$chat->sender_id);
                $user = $user->first();
            }
            if($chat->reciever_id !=0){
                $user = User::select('id','name','image')->where('id',$chat->reciever_id);
                $user = $user->first();
            }

            // $imgUrl = url('api/public/images/users/user.png');
            // if(!empty($user->image)){
            //     $imgUrl = url('api/public/images/users/'.$user->image);
            // }
            $time = date('d M h:i A',strtotime($chat->created_at));
            // $created_at = date('h:i A',strtotime($chat->created_at));
            if($chat->sender_type == 'user' || $chat->reciever_type == 'admin'){

                $text = $chat->text;
                if($chat->is_file == 1){
                    if($chat->file_type == 'jpg' || $chat->file_type == 'jpeg' ||$chat->file_type == 'png'){
                        $fileUrl = url('api/public/images/chats/'.$chat->text);
                        $img = url('api/public/images/chats/image.png');
                        $text = '<a href='.$fileUrl.' target="_blank"><img src='.$img.' height="50px" width="50px"></a>';
                    }
                    if($chat->file_type == 'mp3'){
                        $fileUrl = url('api/public/images/chats/'.$chat->text);
                        $img = url('api/public/images/chats/mp3.jpg');
                        $text = '<a href='.$fileUrl.' target="_blank"><img src='.$img.' height="50px" width="50px"></a>';
                    }

                    if($chat->file_type == 'mp4'){
                        $fileUrl = url('api/public/images/chats/'.$chat->text);
                        $img = url('api/public/images/chats/mp4.jpg');
                        $text = '<a href='.$fileUrl.' target="_blank"><img src='.$img.' height="50px" width="50px"></a>';
                    }


                    if($chat->file_type == 'pdf'){
                        $fileUrl = url('api/public/images/chats/'.$chat->text);
                        $img = url('api/public/images/chats/pdf.png');
                        $text = '<a href='.$fileUrl.' target="_blank"><img src='.$img.' height="50px" width="50px"></a>';
                    }if($chat->file_type == 'xls' || $chat->file_type == 'csv' || $chat->file_type == 'xlsx'){
                        $fileUrl = url('api/public/images/chats/'.$chat->text);
                        $img = url('api/public/images/chats/xls.png');
                        $text = '<a href='.$fileUrl.' target="_blank"><img src='.$img.' height="50px" width="50px"></a>';
                    }

                    if($chat->file_type == 'doc' || $chat->file_type == 'doc' ){
                        $fileUrl = url('api/public/images/chats/'.$chat->text);
                        $img = url('api/public/images/chats/doc.png');
                        $text = '<a href='.$fileUrl.' target="_blank"><img src='.$img.' height="50px" width="50px"></a>';
                    }


                }
                $imgUrl = config('custom.NO_IMG');
                $storage = Storage::disk('public');
                $path = 'user/';
                $image_name = $user->image ?? '';
                if(!empty($image_name)){
                    if($storage->exists($path.$image_name)){
                      $imgUrl =  url('storage/app/public/'.$path.'/'.$image_name);
                  }
              }



              $html.='<div class="chat chat-left"><div class="chat-avatar"><a class="avatar" data-toggle="tooltip" href="#" data-placement="left" title="" data-original-title="">
              <img src='.$imgUrl.' class="box-shadow-4" alt='.$user->name.' /></a></div><div class="chat-body">
              <div class="chat-content"><p>'.$text.'</p><p><i class="fa fa-clock"></i>  '.$time.'</p></div></div></div>';
          }

          if($chat->sender_type == 'admin' || $chat->reciever_type == 'user'){
              $text = $chat->text;
              if($chat->file_type == 'jpg' || $chat->file_type == 'jpeg' ||$chat->file_type == 'png'){
                $fileUrl = url('public/storage/chats/'.$chat->text);
                $img = url('api/public/images/chats/image.png');
                $text = '<a href='.$fileUrl.' target="_blank"><img src='.$img.' height="50px" width="50px"></a>';
            }

            if($chat->file_type == 'mp3'){
                $fileUrl = url('public/storage/chats/'.$chat->text);
                $img = url('api/public/images/chats/mp3.jpg');
                $text = '<a href='.$fileUrl.' target="_blank"><img src='.$img.' height="50px" width="50px"></a>';
            }

            if($chat->file_type == 'mp4'){
                $fileUrl = url('public/storage/chats/'.$chat->text);
                $img = url('api/public/images/chats/mp4.jpg');
                $text = '<a href='.$fileUrl.' target="_blank"><img src='.$img.' height="50px" width="50px"></a>';
            }


            if($chat->file_type == 'pdf'){
                $fileUrl = url('public/storage/chats/'.$chat->text);
                $img = url('api/public/images/chats/pdf.png');
                $text = '<a href='.$fileUrl.' target="_blank"><img src='.$img.' height="50px" width="50px"></a>';
            }if($chat->file_type == 'xls' || $chat->file_type == 'csv' || $chat->file_type == 'xlsx'){
                $fileUrl = url('public/storage/chats/'.$chat->text);
                $img = url('api/public/images/chats/xls.png');
                $text = '<a href='.$fileUrl.' target="_blank"><img src='.$img.' height="50px" width="50px"></a>';
            }
            if($chat->file_type == 'doc' || $chat->file_type == 'docx'){
                $fileUrl = url('public/storage/chats/'.$chat->text);
                $img = url('api/public/images/chats/doc.jpg');
                $text = '<a href='.$fileUrl.' target="_blank"><img src='.$img.' height="50px" width="50px"></a>';
            }



            $imgUrl = config('custom.NO_IMG');
            $settings = DB::table('settings')->where('id',1)->first();

            $storage = Storage::disk('public');
            $path = 'settings/';

            $image_name = $settings->logo ?? '';
            if(!empty($image_name)){
                if($storage->exists($path.$image_name)){
                  $imgUrl =  url('storage/app/public/'.$path.'/'.$image_name);
              }
          }


          $html.=' <div class="chat">
          <div class="chat-avatar">
          <a class="avatar" data-toggle="tooltip" href="#" data-placement="right" title="" data-original-title="">
          <img src='.$imgUrl.' class="box-shadow-4" alt="Admin" />
          </a>
          </div>
          <div class="chat-body">
          <div class="chat-content">
          <p>'.$text.'</p>
          <p><i class="fa fa-clock"></i>  '.$time.'</p>
          </div>
          </div>
          </div>';

      }


  }

}

echo $html;
}

public function send_message(Request $request){
    $user_id = isset($request->user_id) ? $request->user_id :'';
    $message = isset($request->message) ? $request->message :'';

    $dbArray = [];
    $dbArray['sender_id'] = 0;
    $dbArray['reciever_id'] = $user_id;
    $dbArray['sender_type'] = 'admin';
    $dbArray['reciever_type'] = 'user';
    $dbArray['text'] = $message;
    Chats::insert($dbArray);
    echo 1;

}


}




