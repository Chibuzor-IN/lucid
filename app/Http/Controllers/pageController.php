<?php

namespace Lucid\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
use Validator;
use Parsedown;
use Carbon\Carbon;
class pageController extends Controller
{
    public function user($username) {
        $user_exists = DB::table('users')->where('name',$username)->orWhere('username',$username)->get();
        if(!isset($user_exists[0])) {
            return false;
        }
        return $user_exists[0];
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
                $following = $post->subscription();
                $follower = $post->subscriber();
                $post = $post->Feeds();
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


  //dd($fcheck);
                return view('timeline', ['posts' => $post,'fcheck' => $fcheck,'user'=>$user,'fcount'=>$fcount, 'count' => $count, 'following' => $following, 'follower' => $follower]);

        }else {


            $app = new \Lucid\Core\Document($username);
            $feed =$app->Feeds();

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

              $userposts=$this->getPosts($username);

              return view('home', ['posts' => $feed,'user'=>$user,'fcheck' => $fcheck,'fcount'=>$fcount, 'count' => $count,"userposts"=>$userposts]);

        }


    }

    public function getPost($username,$id){
      $user = $this->user($username);
      $post = DB::table('posts')->where(['id'=>$id,'user_id'=>$user->id])->first();  
      $parsedown  = new Parsedown();
      $createdAt = Carbon::parse($post->created_at);
      $content['tags'] = $post->tags;
      $content['title'] =$post->title;
      $content['body'] = $parsedown->text($post->content);
      $content['date'] = $createdAt->format('l jS \\of F Y h:i A');
      $content['slug'] = $this->clean($post->slug);

      return $content;
    }

    public function singlePostPage($username,$postTitle,$id){
        if(!$this->user($username)) {
            return abort(404);
        }
        $user = $this->user($username);
        $app  = new \Lucid\Core\Document($username);
        $id = base64_decode($id);
        $post=$this->getPost($username,$id);

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

    public function getPosts($username){
      $user =  $this->user($username);;
      $posts = DB::table('posts')->where('user_id',$user->id)->get();
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
        $content['body']  = $this->trim_words($postContent, 200);
        $content['tags']  = $post->tags;
        $content['slug']  = $this->clean($post->slug).'/'.base64_encode($post->id);
        $content['image'] = $first_img;
        $content['date']  =  $createdAt->format('l jS \\of F Y h:i A');;
        $content['id'] = $post->id;
        array_push($allPost,$content);
      }
      return $allPost;
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
            $posts=$this->getPosts($username);
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

            return view('post',compact('user','posts'), ['fcheck' => $fcheck, 'fcount'=>$fcount, 'count' => $count ]);
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


        return view('contact',compact('user','posts','contact'), ['fcheck' => $fcheck, 'fcount'=>$fcount, 'count' => $count ]);
    }



    public function thoughts($username)
    {
      if(!$this->user($username)) {
          return abort(404);
      }

      $user = $this->user($username);
      $post = new \Lucid\Core\Document($username);
      $post = $post->get('micro-blog-posts');
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


}
