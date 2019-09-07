<?php

namespace Lucid\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Str;
use Validator;
use Parsedown;
use URL;
use Str;
use Lucid\Notification;
use Carbon\Carbon;

class pageController extends Controller
{
    public function user($username) {
        $user_exists = DB::table('users')->where('name',$username)->orWhere('username',$username)->get();
      //  dd($user_exists);
        if(!isset($user_exists[0])) {
            return false;
        }
        return $user_exists[0];
    }

public function Feeds($username)
{

  if(!$this->user($username)) {
      return abort(404);
  }
  $user = $this->user($username);
  if(Auth::user() && Auth::user()->username == $username){
          $user = Auth::user();
          $username = $user->username;

          $feeds = new \Lucid\Core\Document($username);

          $feeds = $feeds->MyFeeds();
        //  dd($feeds);

  return view('feeds', ['posts' => $feeds]);

  }else {


      $app = new \Lucid\Core\Document($username);
      $feeds =$app->Feeds();

    return view('feeds', ['posts' => $feeds]);
}
}

    public function homePage($username)
    {
        if(!$this->user($username)) {
            return abort(404);
        }
        $user = $this->user($username);
        if(Auth::user() && Auth::user()->username == $username){
                $user = Auth::user();
                $username = $user->username;

                $post = new \Lucid\Core\Document($username);

                $post = $post->MyFeeds();
              //  dd($post);
            //$post =[];
                $sub = new \Lucid\Core\Subscribe($username);
                $fcount = $sub->myfollowercount();
                if (!empty($fcount)) {
                    $fcount = count($fcount);
                  }else {
                    $fcount = "";
                  }
                $fcheck = $sub->followCheck($user->name);

                $count = $sub->count();
                if (!empty($count)) {
                  $count = count($count);
                }
                else {
                  $count = "";
                }


  //dd($likes);
                $tabs = DB::table('interests')->get();
                return view('timeline', [
                  'fcheck' => $fcheck,
                  'user'=>$user,
                  'fcount'=>$fcount,
                  'count' => $count]);

        }else {


            $app = new \Lucid\Core\Document($username);
            $feed =$app->Feeds();
          //  dd(  $feed);
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
            //  $follower = $app->subscription();
               //dd($follower);

               $userposts=$app->getPublishedPosts($username);

              return view('home', ['userposts' => $userposts,'user'=>$user,'fcheck' => $fcheck,'fcount'=>$fcount, 'count' => $count]);

        }


    }

    public function getPostData($username,$postSlug) {
      $app = new \Lucid\Core\Document($username);
      $post=$app->getPost($username,$postSlug);
      if(!$post){
          return response()->json(['error'=>'post not found'],404);
      }
      return response()->json(['data'=>$post]);
    }

    public function singlePostPage($username,$postSlug){
      // return $postSlug;
        if(!$this->user($username)) {
            return abort(404);
        }
        $user = $this->user($username);
        $app  = new \Lucid\Core\Document($username);
      //  $id = base64_decode($id);

        $post=$app->getPost($username,$postSlug);

        if(!$post){
            return redirect('/'.$username.'/home');
        }

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

        return view('single-blog-post',compact('post','user'),['fcheck' => $fcheck, 'fcount'=>$fcount, 'count' => $count ]);
    }



    public function clean($string) {
      $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

      return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    }

    public function trim_words($string,$limit,$break=".",$pad="...")
    {
        if (strlen($string) <= $limit) return $string;

        if (false !== ($breakpoint = strpos($string, $break, $limit))) {
            if ($breakpoint < strlen($string) - 1) {
                $string = substr($string, 0, $breakpoint) . $pad;
            }
        }

        return $string;
    }

    public function posts($username){

            if(Auth::user() && $username == Auth::user()->username){

            if(!$this->user($username)) {
                return abort(404);
            }

            $user = $this->user($username);
            $app  = new \Lucid\Core\Document($username);
            $posts=$app->fetchAllRss();

            //dd($posts);
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
              $post_id = isset($post_id) ? $post_id:'';
              $likes = DB::table('notifications')
                      ->where('post_id',$post_id)
                      ->where('notifications.action','=',"like")
                      ->get();
                    //  dd(  $like );
            return view('post',compact('user','posts'), [
              'fcheck' => $fcheck,
              'fcount'=>$fcount,
              'count' => $count,
              'likes' => $likes
            ]);
        }else {
            return redirect('/'.$username);
        }

    }

    public function contact($username){
        if(!$this->user($username)) {
            return abort(404);
        }

        $user = $this->user($username);
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



        $contact = DB::table('contact_settings')->where('user_id',$user->id)->first();


        return view('contact',compact('user','contact'), ['fcheck' => $fcheck, 'fcount'=>$fcount, 'count' => $count ]);
    }



    public function thoughts($username)
    {
      if(!$this->user($username)) {
          return abort(404);
      }

      $user = $this->user($username);
      $post = new \Lucid\Core\Document($username);
      $post = $post->Thoughts();
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

      return view('thoughts', ['fcheck' => $fcheck,'posts' => $post,'user'=>$user,'fcount'=>$fcount, 'count' => $count]);

    }

    public function following($username) {
        if(!$this->user($username)) {
          return abort(404);
      }
      $user = $this->user($username);

      $post = new \Lucid\Core\Document($username);
              $following = $post->subscription();
              $follower = $post->subscriber();
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
                    $myfollower = $check->followerArray();
                //    dd($myfollower);
                }
                else {
                  $fcheck = "no";
                  $myfollower = [];
                }

      return view('follow-details', [
        'fcheck' => $fcheck,
        'posts' => $post,
        'user'=>$user,
        'fcount'=>$fcount,
        'count' => $count,
        'following' => $following,
        'follower' => $follower,
        'followerArray' =>$myfollower
      ]);
    }

    public function followers($username) {
        if(!$this->user($username)) {
          return abort(404);
      }
      $user = $this->user($username);

      $post = new \Lucid\Core\Document($username);
                $following = $post->subscription();
                $follower = $post->subscriber();
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
//dd($following);

                  //User Follower checker
                  if(Auth::user()){
                    $check = new \Lucid\Core\Subscribe(Auth::user()->username);
                    $fcheck = $check->followCheck($user->name);
                    $myfollower = $check->followerArray();
//dd($myfollower);
                  }
                  else {
                    $fcheck = "no";
                    $myfollower = [];
                  }

      return view('follow-details', [
        'fcheck' => $fcheck,
        'posts' => $post,
        'user'=>$user,
        'fcount'=>$fcount,
        'count' => $count,
        'following' => $following,
        'follower' => $follower,
        'followerArray' =>$myfollower
      ]);
    }


    public function construction(){
      return view('under-construction');
    }

    public function saveSubscriptionEmail(Request $request){
        $validator=Validator::make($request->all(),[
          'email' =>'required|email'
      ]);

      if($validator->fails()){
        return response()->json($validator->messages(), 200);
    }

    $insert = DB::table('maillists')->insert([
      'email'=>$request->email
    ]);

    if($insert){
      return response()->json(['success'=>'Thanks For Subscribing To Our Newsletters'], 200);
    }


  }


  public function comments($username, $post_id) {

    $comments = DB::table('notifications')
                ->join('users','notifications.sender_id','=','users.id')
                ->select('notifications.*','users.username','users.email','users.image')
                ->where('notifications.post_id',$post_id)
                ->where('notifications.parent_comment_id','=',null)
                ->where('notifications.action','Commented')
                ->orderBy('notifications.id','DESC')
                ->get();
    $carbon =  new Carbon;
  //  dd($comments);
    $replies = DB::table('notifications')
            ->join('users','notifications.sender_id','=','users.id')
            ->select('notifications.*','users.username','users.email','users.image')
            ->where('notifications.post_id',$post_id)
            ->where('notifications.parent_comment_id','!=',null)
            ->where('notifications.action','Replied')
            ->orderBy('notifications.id','DESC')
            ->get();
          //  dd(  $replies);
     $user = $this->user($username);
     return view('comments')->with(['comments'=>$comments,"user"=> $user,'carbon'=>$carbon,'replies'=>$replies]);
  }

  public function reply(Request $request) {
//dd($request->id);
    $replies = DB::table('notifications')
                ->join('users','notifications.sender_id','=','users.id')
                ->select('notifications.*','users.username','users.email','users.image')
                ->where('notifications.parent_comment_id','=',$request->id)
                //->where('notifications.post_id',$post_id)
                ->orderBy('notifications.id','DESC')
                ->get();
    $carbon =  new Carbon;
   //dd($replies);
    return view('reply')->with(['replies'=>$replies,'carbon'=>$carbon]);

  }
  public function notification(Request $request, $username)
  {

    if($request->view != '')

    {
      DB::table('notifications')
            ->where(['user_id' => Auth::user()->id, 'status' => 0 ] )
            ->update(['status' => 1]);

    }

    $user =   DB::table('users')->where('username', $username)->first();
    $notif = DB::table('notifications')
                ->where(['user_id' => Auth::user()->id] )
                ->where('sender_id', "!=", Auth::user()->id)
                ->orderBy('notifications.id','DESC')
                ->take(5)
                ->get();

          //      dd($notif);
    $output = '';
  if (count($notif) > 0) {

    foreach ($notif as $notifs) {

      if ($notifs->type == "Post") {
    $notif = DB::table('notifications')
                ->join('users','notifications.sender_id','=','users.id')
                ->join('posts','notifications.post_id','=','posts.id')
                ->select('notifications.*', 'posts.title', 'posts.slug', 'users.username','users.email','users.image')
                ->where(['notifications.user_id' => Auth::user()->id, 'notifications.post_id' => $notifs->post_id ] )
                ->where('notifications.sender_id', "!=", Auth::user()->id)
                ->first();


              //  dd($notif);
    if ($notif->action == 'Commented') {
      //  foreach ($notif as $notifs) {
            $output .='
            <div class="post-content border p-3 d-flex align-items-center">
              <img src="'.$notif->image.'" class="img-fluid img-thumb" alt="user" />
              <div class="post-content-body">
                <a class="m-0 font-weight-bold" href="'.secure_url('/').'/'.$notif->username.'">'.$notif->username.'</a> commented on your post <a href="'.secure_url('/').'/'.Auth::user()->username.'/post/'.$notif->slug.'" class="font-weight-bold">'.$notif->title.'</a>
              </div>
            </div>';

          //}

  }
  if ($notif->action == 'Replied') {
    //  foreach ($notif as $notifs) {
          $output .='
          <div class="post-content border p-3">
            <img src="'.$notif->image.'" class="img-fluid img-thumb" alt="user" />
            <div class="post-content-body">
              <a class="m-0 font-weight-bold" href="'.secure_url('/').'/'.$notif->username.'">'.$notif->username.'</a> Replied your comment on <a href="'.secure_url('/').'/'.Auth::user()->username.'/post/'.$notif->slug.'" class="font-weight-bold">'.$notif->title.'</a>
            </div>
          </div>';

        //}

}
}
    if ($notifs->type == 'Following') {
      $user= DB::table('users')->where('id', $notifs->sender_id)->first();

            $output .='
            <div class="post-content border p-3">
              <img src="'.$user->image.'" class="img-fluid img-thumb" alt="user" />
              <div class="post-content-body">
                <a class="m-0 font-weight-bold" href="'.secure_url('/').'/'.$user->username.'">'.$user->username.'</a> is now Following you
              </div>
            </div>';
}
if ($notifs->type == 'Reaction') {

  if ($notifs->action == 'Like') {
  $notif = DB::table('notifications')
              ->join('users','notifications.sender_id','=','users.id')
              ->join('posts','notifications.post_id','=','posts.id')
              ->select('notifications.*', 'posts.title', 'posts.slug', 'users.username','users.email','users.image')
              ->where(['notifications.user_id' => Auth::user()->id, 'notifications.post_id' => $notifs->post_id, 'notifications.action' =>"Like" ] )
              ->where('notifications.sender_id', "!=", Auth::user()->id)
              ->orderBy('notifications.id','DESC')
              ->first();


        $output .='
        <div class="post-content border p-3">
          <img src="'.$notif->image.'" class="img-fluid img-thumb" alt="user" />
          <div class="post-content-body">
            <a class="m-0 font-weight-bold" href="'.secure_url('/').'/'.$notif->username.'">'.$notif->username.'</a> Liked your post <a href="'.secure_url('/').'/'.Auth::user()->username.'/post/'.$notif->slug.'" class="font-weight-bold">'.$notif->title.'</a>
          </div>
        </div>';

      }
      if ($notifs->action == 'Love') {
        $notif = DB::table('notifications')
                    ->join('users','notifications.sender_id','=','users.id')
                    ->join('posts','notifications.post_id','=','posts.id')
                    ->select('notifications.*', 'posts.title', 'posts.slug', 'users.username','users.email','users.image')
                    ->where(['notifications.user_id' => Auth::user()->id, 'notifications.post_id' => $notifs->post_id, 'notifications.action' =>"Love" ] )
                    ->where('notifications.sender_id', "!=", Auth::user()->id)
                    ->orderBy('notifications.id','DESC')
                    ->first();
            $output .='
            <div class="post-content border p-3">
              <img src="'.$notif->image.'" class="img-fluid img-thumb" alt="user" />
              <div class="post-content-body">
                <a class="m-0 font-weight-bold" href="'.secure_url('/').'/'.$notif->username.'">'.$notif->username.'</a> Love your post <a href="'.secure_url('/').'/'.Auth::user()->username.'/post/'.$notif->slug.'" class="font-weight-bold">'.$notif->title.'</a>
              </div>
            </div>';

          }
}

//dd($output);
}


  }else{
        $output .= '
        <div class="post-content border p-3"><div class="post-content-body">
            <p> No Notification Found</p>
          </div>
        </div>';
    }
    $count = DB::table('notifications')
                ->where(['user_id' => Auth::user()->id, 'status' => 0 ] )
                ->where('sender_id', "!=", Auth::user()->id)
                ->count();

  //  $count = count($notif);

  //dd($output);
    //dd($count);
    $data = array(
       'notification' => $output,
       'unseen_notification'  => $count
    );

 return response()->json($data);

    }


  public function filterPost($method) {

    if($method == "Recent"){

      $posts = DB::table('posts')
                ->join('users','posts.user_id','=','users.id')
                ->select('posts.*','users.image','users.username')
                ->orderBy('posts.id','DESC')
                ->get();
      if(!empty($posts)){

        $allPost = [];
      foreach($posts as $post){
        $parsedown  = new Parsedown();
        $postContent = $parsedown->text($post->content);
        preg_match('/<img[^>]+src="((\/|\w|-)+\.[a-z]+)"[^>]*\>/i', $postContent, $matches);
        $first_img = "";
        if (isset($matches[1])) {
            // there are images
            $first_img = $matches[1];
            // strip all images from the text
            $postContent = preg_replace("/<img[^>]+\>/i", " ", $postContent);
        }
        $createdAt = Carbon::parse($post->created_at);
        $content['title'] = $post->title;
        $content['body']  = $this->trim_words($postContent, 500);
        $content['tags']  = $post->tags;
        $content['slug']  = $this->clean($post->slug);
        $content['image'] = $first_img;
        $content['date']  =  $createdAt->format('M jS, Y h:i A');;
        $content['id'] = $post->id;
        $content['username'] = $post->username;
        $content['user_img'] = $post->image;

        array_push($allPost,$content);
      }
      return view('filtered-posts')->with(['posts'=>$allPost]);

      }

    }elseif($method =="Popular"){

      $posts = DB::table('posts')
                ->join('users','posts.user_id','=','users.id')
                ->join('notifications','posts.id','=','notifications.post_id')
                ->select('posts.*','users.image','users.username',DB::raw('count(notifications.comment) as total'))
                ->groupBy('posts.id')
                ->orderBy('total', 'DESC')
                ->get();
        if(!empty($posts)){

          $allPost = [];
        foreach($posts as $post){
          $parsedown  = new Parsedown();
          $postContent = $parsedown->text($post->content);
          preg_match('/<img[^>]+src="((\/|\w|-)+\.[a-z]+)"[^>]*\>/i', $postContent, $matches);
          $first_img = "";
          if (isset($matches[1])) {
              // there are images
              $first_img = $matches[1];
              // strip all images from the text
              $postContent = preg_replace("/<img[^>]+\>/i", " ", $postContent);
          }
          $createdAt = Carbon::parse($post->created_at);
          $content['title'] = $post->title;
          $content['body']  = $this->trim_words($postContent, 500);
          $content['tags']  = $post->tags;
          $content['slug']  = $this->clean($post->slug);
          $content['image'] = $first_img;
          $content['date']  =  $createdAt->format('M jS, Y h:i A');;
          $content['id'] = $post->id;
          $content['username'] = $post->username;
          $content['user_img'] = $post->image;

          array_push($allPost,$content);
        }
        return view('filtered-posts')->with(['posts'=>$allPost]);

        }
    }
  }


  public function explorePage(){
    $interests = DB::table('interests')->get();

    return view('explore')->with('interests',$interests);
  }

  public function interest($interest){
    $posts = DB::table('posts')
             ->join('users','posts.user_id','=','users.id')
             ->select('posts.*','users.image','users.username')
             ->where('tags','!=',NULL)->orderBy('id','DESC')->get();
    $interestsArray = [];
    $interestPosts=[];
    foreach($posts as $post) {
       $tags = explode(',',$post->tags);
       $tags = array_filter(array_map('trim',$tags));
       $tags = array_filter(array_map('strtolower',$tags));
       if(in_array(strtolower($interest), $tags)) {

          $parsedown  = new Parsedown();
          $postContent = $parsedown->text($post->content);
          preg_match('/<img[^>]+src="((\/|\w|-)+\.[a-z]+)"[^>]*\>/i', $postContent, $matches);
          $first_img = "";
          if (isset($matches[1])) {
              // there are images
              $first_img = $matches[1];
              // strip all images from the text
              $postContent = preg_replace("/<img[^>]+\>/i", " ", $postContent);
          }
          $createdAt = Carbon::parse($post->created_at);
          $content['title'] = $post->title;
          $content['body']  = $this->trim_words($postContent, 500);
          $content['tags']  = $post->tags;
          $content['slug']  = $this->clean($post->slug);
          $content['image'] = $first_img;
          $content['date']  =  $createdAt->format('M jS, Y h:i A');;
          $content['id'] = $post->id;
          $content['username'] = $post->username;
          $content['user_img'] = $post->image;

          array_push($interestPosts,$content);

       }
    }
    return view('interest-posts')->with(['posts'=>$interestPosts,'interest'=>strtoupper($interest)]);
  }

  public function postCategories($category) {
    $categories = explode(',',$category);
    $posts = DB::table('posts')
             ->join('users','posts.user_id','=','users.id')
             ->select('posts.*','users.image','users.username')

             ->where('tags','!=',NULL)->where('action','publish')->orWhere('action',NULL)->orderBy('id','DESC')->get();

    $users = DB::table('posts')
            ->join('users','posts.user_id','=','users.id')
            ->select('posts.*','users.image','users.username')
            ->where('tags','!=',NULL)->where('action','publish')->orWhere('action',NULL)->orderBy('id','DESC')->get();

    return view('category')->with(['categories'=>array_reverse($categories),'posts'=>$posts,'pageController'=>new pageController,'users'=>$users]);
  }
  public function sitemapUsers()
  {
$users = DB::table('users')->get();
$content ='';

    //OK. Everything is done. Now generate the feed.
  $content .='<?xml version="1.0" encoding="UTF-8"?>';
  $content .='<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    foreach ($users as $key => $value) {
      $content .='<url>
      <loc>'.url('/').'/'.$value->username.'</loc>
      <lastmod>'.$value->updated_at.'</lastmod></url>';
    };
  $content .='
  </urlset>' ;

    return response($content, 200)
                ->header('Content-Type', 'text/xml');
  }





  public function sitemapFeeds($value='')
  {
    $feeds = DB::table('posts')->get();
    $content ='';

        //OK. Everything is done. Now generate the feed.
      $content .='<?xml version="1.0" encoding="UTF-8"?>
  <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">';
  foreach ($feeds as $key => $value) {
    $user = DB::table('users')->where('id', $value->user_id)->first();
    $content .='<url>
    <loc>'.url('/').'/'.$user->username.'/'.$value->slug.'</loc>
    <news:news>
    <news:publication>
    <news:name>'.\Illuminate\Support\Str::title($this->clean($user->name)).'&apos;s Blog</news:name>
    <news:language>en</news:language>
    </news:publication>
    <news:publication_date>'.$value->created_at.'</news:publication_date>
    <news:title><![CDATA['.$value->title.']]></news:title>
    </news:news></url>';
  };
  $content .='</urlset>' ;


      return response($content, 200)
                  ->header('Content-Type', 'text/xml');
  }
  public function sitemaps()
  {
    $content ='';
    $content .='<?xml version="1.0" encoding="UTF-8"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<sitemap>
<loc>'.url('/').'/sitemap_users.xml</loc>
<lastmod>'.now().'</lastmod>
</sitemap>
<sitemap>
<loc>'.url('/').'/sitemap_feeds.xml</loc>
<lastmod>'.now().'</lastmod>
</sitemap>
<sitemap>
<loc>'.url('/').'/sitemap.xml</loc>
<lastmod>'.now().'</lastmod>
</sitemap>
</sitemapindex>';
return response($content, 200)
            ->header('Content-Type', 'text/xml');
  }
  public function sitemap()
  {
    $content ='';
    $content .='<?xml version="1.0" encoding="UTF-8"?>
    <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
      <url>
        <loc>'.url('/').'</loc>
        <lastmod>'.now().'</lastmod>
      </url>
      <url>
        <loc>'.url('/').'/explore</loc>
        <lastmod>'.now().'</lastmod>
      </url>
    </urlset>';
return response($content, 200)
            ->header('Content-Type', 'text/xml');
  }
}
