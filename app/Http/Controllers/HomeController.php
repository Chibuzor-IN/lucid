<?php

namespace Lucid\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Validator;
use DB;
use Storage;
use Parsedown;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $user = Auth::user();
      $username = $user['username'];
      $post = new \Lucid\Core\Document($username);
            $feed = $post->fetchRss();
           // print_r($feed);
           // follower and following Count
           $sub = new \Lucid\Core\Subscribe($username);
           $fcount =$sub->myfollowercount();
           $count = $sub->count();
           //dd($fcount);
           if (!empty($fcount)) {
               $fcount = count($fcount);
             }else {
               $fcount = "";
             }
             if (!empty($count)) {
               $count = count($count);
             }else {
               $count = "";
             }


             //User Follower checker
             if(Auth::user()){
               $check = new \Lucid\Core\Subscribe(Auth::user()->username);
               $fcheck = $check->followCheck($user->name);
             }
             else {
               $fcheck = "no";
             }

        return view('home', ['fcheck' => $fcheck, 'posts' => $feed,'fcount'=>$fcount, 'count' => $count]);

    }
    public function fix()
    {
      echo "Initiating Fix";
     $fix = new \Lucid\Core\Subscribe(Auth::user()->username);
      $fix = $fix->fix();
    }
    public function timeline($username)
    {
      $user = Auth::user();
      if ($username == $user->username) {

      $username = $user->username;
      $post = new \Lucid\Core\Document($username);

      $post = $post->fetchAllRss();

      // follower and following Count
      $sub = new \Lucid\Core\Subscribe($username);
      $fcount =$sub->myfollowercount();
      $count = $sub->count();
      //dd($fcount);
      if (!empty($fcount)) {
          $fcount = count($fcount);
        }else {
          $fcount = "";
        }
        if (!empty($count)) {
          $count = count($count);
        }else {
          $count = "";
        }


        //User Follower checker
        if(Auth::user()){
          $check = new \Lucid\Core\Subscribe(Auth::user()->username);
          $fcheck = $check->followCheck($user->name);
        }
        else {
          $fcheck = "no";
        }

     return view('timeline', ['posts' => $post,'fcheck' => $fcheck, 'user'=>$user,'fcount'=>$fcount, 'count' => $count]);
     }else {

        return view($user->username.'/timeline', ['posts' => $post,'user'=>$user,'fcount'=>$fcount, 'count' => $count]);

    }
  }


/*
*
*
*

*/



    public function savePost(Request $request)
    {
      $request->validate([
        'body'=>'required'
      ]);

      $title = '';
      $body = $request->body;
      // filter out non-image data
      $user = Auth::user();
      $username = $user->username;
      $post = new \Lucid\Core\Document($username);
      $result = $post->createThough($body);
      return redirect($username.'/thoughts')->with('msg', 'Post Published');
    }

    public function subscribe()
    {
      $user = Auth::user();
      $username = preg_split('/ +/', $user->name);
      $path = $username[0];

      $post=[];
      // follower and following Count
      $sub = new \Lucid\Core\Subscribe($username);
      $fcount =$sub->myfollowercount();
      $count = $sub->count();
      //dd($fcount);
      if (!empty($fcount)) {
          $fcount = count($fcount);
        }else {
          $fcount = "";
        }
        if (!empty($count)) {
          $count = count($count);
        }else {
          $count = "";
        }


        //User Follower checker
        if(Auth::user()){
          $check = new \Lucid\Core\Subscribe(Auth::user()->username);
          $fcheck = $check->followCheck($user->name);
        }
        else {
          $fcheck = "no";
        }

      return view('subscribe', ['fcheck' => $fcheck,'user'=>$user,'fcount'=>$fcount, 'count' => $count]);

    }


    public function publish(Request $request,$username) {
        $title = isset($request->title) ? $request->title : '';
        $content = $request->postVal;
        $tags = $request->tags;


          $initial_images = array_filter($request->all(), function ($key) {
            return preg_match('/^img-\w*$/', $key);
        }, ARRAY_FILTER_USE_KEY);

        $images = [];
        foreach ($initial_images as $key => $value) {
            $newKey = preg_replace('/_/', '.', $key);
            $images[$newKey] = $value;
        }
        $post = new \Lucid\Core\Document($username);
        $createPost = $post->createPost($title, $content, $tags, $images,$username);

      //  dd(  $createPost);
        if($createPost){
          return response()->json(["error" => false, "action"=>"publish", "message" => "Post published successfully"],200);
        }else{
          return response()->json(["error" => true, "action"=>"publish", "message" => "Fail while publishing, please try again"]);
        }
    }




    public function settings(){
      $user = Auth::user();
      $username = $user['username'];
      // follower and following Count
      $sub = new \Lucid\Core\Subscribe($username);
      $fcount =$sub->myfollowercount();
      $count = $sub->count();
      //dd($fcount);
      if (!empty($fcount)) {
          $fcount = count($fcount);
        }else {
          $fcount = "";
        }
        if (!empty($count)) {
          $count = count($count);
        }else {
          $count = "";
        }


        //User Follower checker
        if(Auth::user()){
          $check = new \Lucid\Core\Subscribe(Auth::user()->username);
          $fcheck = $check->followCheck($user->name);
        }
        else {
          $fcheck = "no";
        }

      return view('settings', ['fcheck' => $fcheck,'user'=>$user,'fcount' => $fcount , 'count' => $count ]);

    }

    public function saveSettings(Request $request) {
          $validator=Validator::make($request->all(),[
            'name' => 'required',
            'email' => ['required','email',
             Rule::unique('users')->ignore(Auth::user()->id),
            ],
            'profileimage' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'user_id' =>'required',
            'username'=>['required',
             Rule::unique('users')->ignore(Auth::user()->id),
            ]
        ]);

      if($validator->fails()){
          return response()->json($validator->messages(), 200);
      }

      $oldname = Auth::user()->name;
      $newname = $request->name;
      $user_id = $request->user_id;
      $email = $request->email;
      $username= $request->username;
      $bio = $request->bio;
      $FolderName = storage_path('app/'.Auth::user()->id);

      if(!is_null($request->file('profileimage')) && $request->file('profileimage') !== ""){
          $url = Auth::user()->id."/images/";

         $path = Storage::disk('public')->put($url, $request->file('profileimage'));
         $fullPath = '/storage/'.$path;

         $updated =   DB::transaction(function ()
   use ($oldname,$newname,$fullPath,$FolderName,$user_id,$email,$username,$bio) {

  $updated= DB::table('users')->where('id',$user_id)
    ->update(['name'=>$newname,'username'=>$username,'email'=>$email,'image'=>$fullPath,'short_bio'=>$bio]);

DB::table('ext_rsses')->where('title',$oldname)
    ->update([
      'title'=>$newname,
      'url'=> $FolderName."/rss/rss.xml",
      'link'=> $FolderName."/rss/rss.xml",
      'image' => $fullPath
    ]);

return true;

   });

        if($updated) {

          return response()->json(['success'=>"Your changes has been saved successfully",'img_path'=>$fullPath,'renamedUserContentFolderName'=>$request->username], 200);
        }
      } else {
        $fullPath = Auth::user()->image;

        $updated =   DB::transaction(function ()
   use ($oldname,$newname,$fullPath,$FolderName,$user_id,$email,$username,$bio) {

     DB::table('users')->where('id',$user_id)
                ->update(['name'=>$newname,'username'=>$username,'email'=>$email,'short_bio'=>$bio,'image' => $fullPath]);

    DB::table('ext_rsses')->where('title',$oldname)
                ->update([
                  'title'=>$newname,
                  'url'=> $FolderName."/rss/rss.xml",
                  'link'=> $FolderName."/rss/rss.xml",
                  'image' => $fullPath
                ]);
return true;

});
                                      if($updated){
                                        return response()->json(['success'=>"Your changes has been saved successfully",'renamedUserContentFolderName'=>$request->username], 200);
                                      }
      }

    }

    public function updateContactDetails(Request $request){
        $validator=Validator::make($request->all(),[
          'email' => ['required','email',
            Rule::unique('contact_settings')->ignore(Auth::user()->id,'user_id'),
          ],
          'user_id'=>'required'

      ]);

      if($validator->fails()){
        return response()->json($validator->messages(), 200);
      }

      $detailsExist = DB::table('contact_settings')->where('user_id',$request->user_id)->first();

      if(empty($detailsExist)){
        $insert = DB::table('contact_settings')->insert([
          'user_id'=>$request->user_id,
          'email'=>$request->email,
          'display_message'=>$request->message
        ]);

        if($insert) {
          return response()->json(['success'=>'Your changes has been saved successfully'], 200);
        }


      }else{
        $update = DB::table('contact_settings')->where('user_id',$request->user_id)->update([
          'email'=>$request->email,
          'display_message'=>$request->message
        ]);

        if($update) {
          return response()->json(['success'=>'Your changes has been saved successfully'], 200);
        }else{
          return response()->json(['noChanges'=>'You made no changes'], 200);
        }
      }

    }

    public function deletePost(Request $request, $username){
       $post = DB::table('posts')->where('id',$request->post_id)->first();
       $parsedown  = new Parsedown();
       $postContent = $parsedown->text($post->content);
       preg_match_all('/<img[^>]+src="((\/|\w|-)+\.[a-z]+)"[^>]*\>/i', $postContent, $matches);
       foreach($matches[1] as $found_img) {
         $image_name_array = explode('/',$found_img);
         $img_name = end($image_name_array);
         $imagePath = storage_path('app/public/'.$post->user_id.'/images/'.$img_name);
         if(file_exists($imagePath)) {
           unlink($imagePath);
         }
       }
       DB::table('notifications')->where('post_id',$post->id)->delete();
      $deletePost = DB::table('posts')->where('id',$post->id)->delete();
      if($deletePost) {
        return response()->json(['success'=>"Post Successfully Deleted"],200);
      }else{
        return response()->json(['error'=>"Something not right"],500);
      }
      
    }


    public function saveComment(Request $request, $username) {

          $user_id = Auth::user()->id;
         $validator=Validator::make($request->all(),[
           'body' =>'required',
           'post_id'=>'required'
        ]);

       if($validator->fails()){
         return response()->json($validator->messages(), 200);
     }

        $post = DB::table('posts')->where('id', $request->post_id)->first();

        if (isset($request->parents_id) && $request->parents_id !== "") {
          // code...
          $parentPost = $request->parents_id;
        }else {
          $parentPost = null;
        }

    //     dd($post);
      $createComment = DB::table('notifications')->insert([
        'post_id'=>$request->post_id,
        'parent_comment_id'=>$parentPost,
        'comment'=>$request->body,
        'sender_id'=> $user_id,
        'user_id'=>$post->user_id,
        'status'=> 0,
        'action'=>"Commented",
        'type'=>"Post",
      ]);
 //dd($createComment);

      if($createComment){
        return response()->json(['comment'=>'saved'], 200);
      }else{
        return response()->json(['error'=>'Sorry an error occured while processing your comment.']);
      }

    }

    public function editPost(Request $request, $username) {

        $title = isset($request->title) ? $request->title : '';
        $content = $request->postVal;
        $tags = $request->tags;
        $post_id = $request->post_id;


          $initial_images = array_filter($request->all(), function ($key) {
            return preg_match('/^img-\w*$/', $key);
        }, ARRAY_FILTER_USE_KEY);

        $images = [];
        foreach ($initial_images as $key => $value) {
            $newKey = preg_replace('/_/', '.', $key);
            $images[$newKey] = $value;
        }
        $post = new \Lucid\Core\Document($username);
        $updatePost = $post->saveUpdatedPost($title, $content, $tags, $images,$username,$post_id);

        if($updatePost){
          return response()->json(["error" => false, "action"=>"update", "message" => "Post Updated successfully"],200);
        }else{
          return response()->json(["error" => true, "action"=>"error", "message" => "Fail while publishing, please try again"]);
        }
    }

}
