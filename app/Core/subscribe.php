<?php
namespace Lucid\Core;
use Auth;
use Storage;
use Lucid\Following;
use DB;


/**
 *
 */
class Subscribe
{
  var $name;
  var $rss;
  var $img;
  var $desc;
  var $link;

  protected $user;

  public function __construct($user)
  {

      $this->user  = $user;
  }

  public function file()
  {
      return $this->file;
  }


  public function setSubName($value)
  {
    $this->name = $value;
  }
  public function setSubRss($value)
  {
    $this->rss = $value;
  }
  public function setSubDesc($value)
  {
    $this->desc = $value;
  }
  public function setSubLink($value)
  {
    $this->link = $value;
  }
  public function setSubImg($value)
  {
    $this->img = $value;
  }
    public function fix()
  {
    $getc = DB::table('ext_rsses')->get();
    $get = DB::table('ext_rsses')->take(500)->get();
    foreach ($get as $key => $value) {
    //  dd($value->title);
if (DB::table('users')->where('name', $value->title)->exists() == 1) {
    $user = DB::table('users')->where('name', $value->title)->first();

    $action = DB::table('following')->insert([
          'my_id'          => $value->user_id,
          'follower_id'    => $user->id,
          'status'         => 1
      ]);
      if ($action) {
        DB::table('ext_rsses')->where(["title" => $value->title, 'user_id' =>$value->user_id])->delete();

      }
      }
      DB::table('ext_rsses')->where(["title" => $value->title, 'user_id' =>$value->user_id])->delete();

  }

  $all = count($getc);
  echo 'A total of '.$all." undo, the first 500 done, refresh till done";
  //  \Schema::dropIfExists('ext_rsses');
  //  var_dump($action);
//return;
  }

public function extract($url)
{
  $rss = new \DOMDocument();
  $user = DB::table('users')->where('username', $url)->first();
  $me = Auth::user();

                $follow = $this->findOrCreateRss(
                  $me['id'],
                  $user->id,
                  0
                );

                if ($follow) {
                  $createComment = DB::table('notifications')->insert([

                    'sender_id'=> $me['id'],
                    'user_id'=> $user->id,
                    'status'=> 0,
                    'action'=>"Followed",
                    'type'=>"Following",
                  ]);
                }

  }

  public function extractPub($url)
  {
    $rss = new \DOMDocument();

    //if (!$url = file_get_contents($url)) {
    //  return false;
      //  } else {

          //$url = storage_path('app/'.$url."/rss/rss.xml");

        echo ($url);
        $rss->load(trim($url));
        foreach ($rss->getElementsByTagName('channel') as $r) {
          $title = $r->getElementsByTagName('title')->item(0)->nodeValue;

          $link = $r->getElementsByTagName('link')->item(0)->nodeValue;
          $description = $r->getElementsByTagName('description')->item(0)->nodeValue;

          $image = isset($r->getElementsByTagName('url')->item(0)->nodeValue) ?
                    $r->getElementsByTagName('url')->item(0)->nodeValue : '';

          $lastbuild =isset( $r->getElementsByTagName('lastBuildDate')->item(0)->nodeValue ) ?
                        $r->getElementsByTagName('lastBuildDate')->item(0)->nodeValue : '';


        }

                $this->setSubName($title);
                $this->setSubRss($url);
                $this->setSubDesc($description);
                $this->setSubImg($image);
                $this->setSubLink($link);

                  $this->findOrCreateRss(
                    $this->name,
                    $url,
                    $this->desc,
                    $this->link,
                    $this->img,
                    $lastbuild

                  );

              //  }
    }

  public function findOrCreateRss($me, $them, $stat){

      $user = Auth::user();

    return DB::table('following')->insert([
          'my_id'          => $me,
          'follower_id'    => $them,
          'status'         => $stat
      ]);

  }



  public function subc($url)
  {
    $rss = new \DOMDocument();


        $rss->load(trim($url));
        foreach ($rss->getElementsByTagName('channel') as $r) {
          $title = $r->getElementsByTagName('title')->item(0)->nodeValue;
          $link = $r->getElementsByTagName('link')->item(0)->nodeValue;
          $description = $r->getElementsByTagName('description')->item(0)->nodeValue;
          if (is_null($r->getElementsByTagName('image')->item(0)->nodeValue)) {
          $image ="resources/themes/ghost/secure_asset/img/bubbles.png";
        }else {
          $image = $r->getElementsByTagName('url')->item(0)->nodeValue;

        }

        }


                $db = "storage/rss/subscriber.json";

                $file = FileSystem::read($db);
                $data=json_decode($file, true);
                unset($file);

                if (count($data) >= 1) {

                foreach ($data as $key => $value) {
                   if ($value["name"] == $title) {

                     $message= "false";

                     break;
                   }else {
                     $message= "true";

                   }


                }
                if ($message == "true") {

                //  $db_json = file_get_contents("storage/rss/subscriber.json");

                  $time = date("Y-m-d h:i:sa");
                    $img = $image;
                    $sub[] = array('name'=> $title, 'rss'=>$url,'desc'=>$description, 'link'=>$link, 'img'=> $image, 'time' => $time);

                    $json_db = "storage/rss/subscriber.json";
                    $file = file_get_contents($db);
                    $prev_sub = json_decode($file);
                    $new =array_merge($sub, $prev_sub);
                    $new = json_encode($new);
                    $doc = FileSystem::write($json_db, $new);
  }
                }else {
                $time = date("Y-m-d h:i:sa");
                $img = $image;
                $sub[] = array('name'=> $title, 'rss'=>$url,'desc'=>$description, 'link'=>$link, 'img'=> $image, 'time' => $time);

                $json_db = "storage/rss/subscriber.json";
                $file = file_get_contents($db);
                $prev_sub = json_decode($file);

                $new = array_merge($sub, $prev_sub);
                $new = json_encode($new);
                $doc = FileSystem::write($json_db, $new);


            }
            //header("loaction: /subscriptions");
    }
  public function unfollow($del)
  {
$fuser= DB::table('users')->where('name', $del)->get('id')->first();

$user = Auth::user();

  $file= DB::table('following')->where('my_id', $user->id)->where('follower_id', $fuser->id)->delete();

//  dd($file);
return $file;

  }
  public function count()
  {

    $user= DB::table('users')->where('username', $this->user)->get('id')->first();
  //  $user=json_decode($user,true);
    $name= DB::table('following')->where('my_id', $user->id)->get('follower_id');
$fuser = [];
    foreach($name as $key => $id){
    //  dd($id->follower_id);
      $user= DB::table('users')->where('id', $id->follower_id)->get();
    //  dd(  $user);
      foreach($user as $key => $user){
    //  $content['name'] = $user->name;
    array_push($fuser, $user->name);
    }
}
  //  $data=json_decode($file,true);
  //  dd($fuser);
    if(!empty($fuser)){
      unset($user_id);
      return $fuser;
    }
  }
  public function myfollowercount()
  {
  //  $user = Auth::user();
      $user= DB::table('users')->where('username', $this->user)->first();
      $data= DB::table('following')->where('follower_id', $user->id)->get();
      $data = json_decode($data, true);

        $follower = [];
        foreach ($data as $key => $value) {

          $follow = DB::table('users')->where('id', $value['my_id'])->get();
        //  dd($value);

           foreach($follow as $key => $follow){

          $content['name'] = $follow->name;
          $content['username'] = $follow->username;
          $content['img'] = $follow->image;
          $content['id'] = $follow->id;
          $content['desc'] = $follow->short_bio;
          array_push($follower, $content);

      }
        }
        if(!empty($data)){
          unset($data);
        return $follower;
    //  return $data;

    }


  }
  public function followerArray()
  {
    //$user= DB::table('users')->where('username', $value)->get();

    $check = new Subscribe(Auth::user()->username);
    //dd(Auth::user()->username);
    $title = [];
    if (!is_null($check->count())) {

    foreach($check->count() as $key => $fuser){
    //  dd($fuser);
    array_push($title , $fuser);
  }
//dd($title );
}
return $title;

  }
  public function followCheck($value)
  {
$title = $this->followerArray();


                      if (in_array($value, $title)) {
                        $fcheck = "yes";
                      }else {
                        $fcheck = "no";
                      }
                    return $fcheck;
    }
}
