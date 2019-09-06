<?php
namespace Lucid\Core;

use Parsedown;
use Mni\FrontYAML\Parser;
use KzykHys\FrontMatter\FrontMatter;
use Symfony\Component\Finder\Finder;
use Illuminate\Support\Str;
use KzykHys\FrontMatter\Document as Doc;
use Auth;
use DB;
use Carbon\Carbon;
use Lucid\extfeeds;
use Lucid\Thought;
use Storage;
/**
 *	The Document class holds all properties and methods of a single page document.
 *
 */

class Document
{
    //define an instance of the symfony clss
    //define an instance of the frontMatter class

    protected $user;

    public function __construct($user)
    {
        //FileSystem::makeDir($file);
        $this->user   = $user;
    }

    public function file()
    {
        return $this->user;
    }

    //for creating markdown files
    //kjarts code here
    /*
    public function createPost($title, $content, $tag="", $image, $extra, $postType="")
    {

        date_default_timezone_set("Africa/Lagos");
        $time = date(DATE_RSS, time());
        $unix = strtotime($time);

        // Write md file
        $document = FrontMatter::parse($content);
        $md = new Parser();

        $markdown = $md->parse($document);

        $yaml = $markdown->getYAML();
        $html = $markdown->getContent();


        $yamlfile = new Doc();
        if ($title != "") {
            $yamlfile['title'] = $title;
        }

        if (!empty($image)) {
            $url = $this->user."/images/";
            if(is_array($image)) {
                foreach ($image as $key => $value) {

                    $decoded = base64_decode($image[$key]);

                    $img_path = 'public/'.$this->user."/images/".$key;
                    Storage::disk('local')->put( $img_path, $decoded);
                    $yamlfile['image'] = $url.$key;
                }
            } else {

              $path =  Storage::disk('public')->put($url, $image);
              $yamlfile['image'] = $path;
            }


        }

        if( $tag !== "") {
            $yamlfile['tags'] = $tag;
        }

        if (!$extra) {
            $yamlfile['post_dir'] =$this->user."/contents/{$unix}";
        } else {
            $yamlfile['post_dir'] = $this->user."/drafts/{$unix}";

        }

        // create slug by first removing spaces
        $striped = str_replace(' ', '-', $title);

        $striped = preg_replace("/(&#[0-9]+;)/", "", $striped);

        $yamlfile['slug'] = $unix;
        $yamlfile['timestamp'] = $time;
        $yamlfile->setContent($content);
        $yaml = FrontMatter::dump($yamlfile);
        $file = $this->user;
        $dir = '';
        if($postType == "full-blog"){
            $dir = $file .'/content/posts/'. $unix . ".md";
        }elseif($postType == "micro-blog") {
            $dir = $file .'/content/micro-blog-posts/'. $unix . ".md";
        }




        //return $dir; die();
        $doc = Storage::put($dir, $yaml);
        if (!$extra) {
            if ($doc) {
                $result = array("error" => false, "action"=>"publish", "message" => "Post published successfully");
                $this->createRSS();
            } else {
                $result = array("error" => true, "action"=>"publish", "message" => "Fail while publishing, please try again");
            }
        } else {
            if ($doc) {
                $result = array("error" => false, "action"=>"savedToDrafts", "message" => "Draft saved successfully");
            } else {
                $result = array("error" => true,"action"=>"savedToDrafts", "message" => "Fail while publishing, please try again");
            }
        }

        return $result;
    }
    */
    public function createPost($title,$content, $tags, $image,$username, $action){

        if (!empty($image)) {
          $url = Auth::user()->id."/images/";
          if(is_array($image)) {
              foreach ($image as $key => $value) {
                  $image = $value;
                  $decoded = base64_decode($image);
                  $img_path = 'public/'.Auth::user()->id."/images/".$key;
                  $image = Storage::disk('local')->put( $img_path, $decoded);

              }
          }
      }else {
        $image = null;
      }


      $slug = Str::slug($title);
      $slug = $slug ."-".substr(md5(uniqid(mt_rand(), true)), 0, 3);
      $insertPosts = DB::table('posts')->insert([
        'user_id'=>Auth::user()->id,
        'title'=>$title,
        'content'=>$content,
        'tags'=>$tags,
        'image'=> $image,
        'slug'=> $slug,
        'action'=>$action
      ]);

    if ($insertPosts) {
        return $action;
    } else {
        return false;
    }

    }

    public function saveUpdatedPost($title,$content, $tags, $image,$username,$post_id) {

        if (!empty($image)) {
            $url = Auth::user()->id."/images/";
            if(is_array($image)) {
                foreach ($image as $key => $value) {
                    $image = $value;
                    $decoded = base64_decode($image);

                    $img_path = 'public/'.Auth::user()->id."/images/".$key;
                  $image = Storage::disk('local')->put( $img_path, $decoded);

                }
            }
        }else {
          $image = null;
        }

        $slug = Str::slug($title);
        $slug = $slug ."-".substr(md5(uniqid(mt_rand(), true)), 0, 3);
        //$slug = preg_replace("/(&#[0-9]+;)/", "", $slug);
        $oldpost = DB::table('posts')->where('id',$post_id)->first('title');
      //  dd($oldpost->title);
        $updateFeeds = DB::table('extfeeds')->where('title',$oldpost->title)
        ->update([
          'title'=>$title,
          'des'=>$content,
          'tags'=>$tags,
          'image'=> $image,
          'link'=> '/post/'.$slug

        ]);
        $updatePosts = DB::table('posts')->where('id',$post_id)->update([
          'user_id'=>Auth::user()->id,
          'title'=>$title,
          'content'=>$content,
          'tags'=>$tags,
          'image'=> $image,
          'slug'=> $slug
        ]);

        //dd($updateFeeds);
        if ($updatePosts) {

          return true;
      } else {

          return false;
      }

    }

    public function createThough($content){


      $insertPosts = DB::table('thoughts')->insert([
        'user_id'=>Auth::user()->id,
        'content'=>$content
      ]);

      if ($insertPosts) {
        $result = array("error" => false, "action"=>"publish", "message" => "Post published successfully");
        return true;
    } else {
        $result = array("error" => true, "action"=>"publish", "message" => "Fail while publishing, please try again");
        return false;
    }

    }

    //get post
    public function postFixer($postTypeSubDir)
    {
        $finder = new Finder();
        // $finder->sortByModifiedTime();
        // $finder->reverseSorting();

        // find all files in the current directory

        if(file_exists(storage_path('app/'.$this->user.'/content/'.$postTypeSubDir.'/'))){

            $finder->files()->in(storage_path('app/'.$this->user.'/content/'.$postTypeSubDir.'/'));

        $posts = [];
        if ($finder->hasResults()) {
            foreach ($finder as $file) {
                $document = $file->getContents();
                $parser = new Parser();
                $document = $parser->parse($document);
                $yaml = $document->getYAML();
                $body = $document->getContent();
                //$document = FileSystem::read($this->user);
                $parsedown  = new Parsedown();
                $tags = isset($yaml['tags']) ? $yaml['tags'] : '';
                $title = isset($yaml['title']) ? $parsedown->text($yaml['title']) : '';
                $slug = $parsedown->text($yaml['slug']);
                $image = isset($yaml['image']) ? $parsedown->text($yaml['image']) : '';
                $slug = preg_replace("/<[^>]+>/", '', $slug);
                $image = preg_replace("/<[^>]+>/", '', $image);
                $bd = $parsedown->text($body);
                ////
                preg_match('/<img[^>]+src="((\/|\w|-)+\.[a-z]+)"[^>]*\>/i', $bd, $matches);
                $first_img = false;
                if (isset($matches[1])) {
                    // there are images
                    $first_img = $matches[1];
                    // strip all images from the text
                    $bd = preg_replace("/<img[^>]+\>/i", " ", $bd);
                }
                $time = $parsedown->text($yaml['timestamp']);
                $url = $parsedown->text($yaml['post_dir']);
                $content['title'] = $title;
                $content['body'] = $bd;
                $content['url'] = $url;
                $content['timestamp'] = $time;
                $content['tags'] = $tags;
                $content['slug'] = $this->clean($slug);
                $content['preview_img'] = $first_img;
                //content['slug'] = $slug;
                $file = explode("-", $slug);
                $filename = $file[count($file) - 1];
                $content['filename'] = $filename;
                //content['timestamp'] = $time;
                $SlugArray = explode('-',$this->clean($slug));
                $content['post_id']=end($SlugArray);
                array_pop($SlugArray);
                $content['post_title']=implode('-',array_filter(array_map('trim', $SlugArray)));
                $content['image'] = $image;
                $content['date'] = date('d M Y ', $filename);
                $content['created_at'] = date('F j, Y, g:i a',$filename);
                array_push($posts, $content);
            }
            $this->array_sort_by_column($posts,'created_at');
//dd($posts );
            foreach ($posts as $key => $value) {
              $title = strip_tags($value['title']);
              $slug = Str::slug($title);
              $slug = $slug ."-".substr(md5(uniqid(mt_rand(), true)), 0, 3);

              if(DB::table('posts')->where(['title' => $title, 'user_id' => Auth::user()->id])->exists() ==1){

              //  dd($value['body']);
                $updatePosts = DB::table('posts')
                ->where(['title' => $title, 'user_id' => Auth::user()->id])
                ->update([
                  'content'=> $value['body']
               ]);
          //      dd("here");
              }else {
              $insertPosts = DB::table('posts')->insert([
                'user_id'=>Auth::user()->id,
                'title'=>  $title,
                'content'=> $value['body'],
                'tags'=>$value['tags'],
                'image'=> $value['image'],
                'slug'=>$slug
              ]);


          //    dd('not here');
}

            };
          Storage::deleteDirectory($this->user.'/content/'.$postTypeSubDir.'/');
          //  storage_path('app/'.$this->user.'/content/'.$postTypeSubDir.'/')
      //      dd($insertPosts);
            return true;
        } else {
            return [];
        }

      }elseif (file_exists(storage_path('app/'.$this->user.'/content/posts/'))) {
      dd("here");
      }

        else{
            return [];
        }


    }

    //kjarts code for getting and creating markdown files end here

    //trim_words used in triming strings by words
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
/// sort post method added by problemSolved (@porh)
   public function array_sort_by_column(&$arr,$col,$sortMethod =SORT_DESC )
    {
        $sort_col = array();

        foreach ($arr as $key=>$row)
        {
            $sort_col[$key] = strtotime($row[$col]);
        }

        array_multisort($sort_col,$sortMethod,$arr);
    }

    ///use to clean slug special chars by problemSolved
   public function clean($string) {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    }

 static function build_sorter($key) {
    // dd($key);
    return function ($a, $b) use ($key) {
        return strnatcmp($a[$key], $b[$key]);
    };
}
public function FeedFixer()
{
   $feeds= DB::table('extfeeds')->get('title');
   foreach ($feeds as $key => $value) {
  //dd($value->title);
     if (DB::table('posts')->Where('title', '=', $value->title)->exists() !== 1) {
      return extfeeds::Where(['title' => $value->title])->delete();
     }
   }

}
public function MyFeeds()
{
  $user = Auth::user();
//  $this->FeedFixer();
  $data= DB::table('following')->where('my_id', $user['id'])->get('follower_id');
  //$data=[];
  $urlArray = json_decode($data, true);

    $urlArray2 = array(
        array('follower_id' => $user['id'])
      //  array('title' => 'Stratechery by Ben Thompson',  'url' => 'http://stratechery.com/feed/' , 'desc' => 'On the business, strategy, and impact of technology.', 'link' => '', 'image' => "https://stratechery.com/wp-content/uploads/2018/03/cropped-android-chrome-512x512-1-32x32.png", 'time' => ' Fri, 12 Jul 2019 16:06:22 +0000')
    );
   $result = array_merge($urlArray, $urlArray2);

  //
//dd($result);
  $feed = [];
foreach ($result as $id) {
  $user= DB::table('users')->where(['id' => $id['follower_id'] ])->first('name');

  $feeds = DB::table('extfeeds')
  ->join('users','extfeeds.site','=','users.name')
  ->join('posts',['extfeeds.title' =>'posts.title','extfeeds.user_id'=> 'posts.user_id'])
  ->select('extfeeds.*','posts.id','users.username','users.email','users.image')
  ->where('site', $user->name)->get();
//  dd($feeds );
    $feeds = json_decode($feeds, true);
  array_push($feed, $feeds);
}
  $ex =[];
  for ($i=0; $i < count($feed) ; $i++) {
    for ($j=0; $j <count($feed[$i]) ; $j++) {
       $rv=$feed[$i][$j];
    //   krsort($rv);
      array_push($ex, $rv);
      //dd($ex);
    }
  }
  //dd($ex);
  usort($ex, $this->build_sorter('id'));

    //arsort($ex);
  krsort($ex);
  //dd($ex);
  //$feed = json_decode($feed, true);

return $ex;


}
public function Feeds()
{
  $user = Auth::user();
//  $this->FeedFixer();
  $data= DB::table('following')->where('my_id', $user['id'])->get('follower_id');
  //$data=[];
  $urlArray = json_decode($data, true);

  //  $urlArray2 = array(
    //    array('follower_id' => $user['id'])
      //  array('title' => 'Stratechery by Ben Thompson',  'url' => 'http://stratechery.com/feed/' , 'desc' => 'On the business, strategy, and impact of technology.', 'link' => '', 'image' => "https://stratechery.com/wp-content/uploads/2018/03/cropped-android-chrome-512x512-1-32x32.png", 'time' => ' Fri, 12 Jul 2019 16:06:22 +0000')
    //);
   //$result = array_merge($urlArray, $urlArray2);

  //
//dd($result);
  $feed = [];
foreach ($urlArray  as $id) {
  $user= DB::table('users')->where(['id' => $id['follower_id'] ])->first('name');

  $feeds = DB::table('extfeeds')
  ->join('users','extfeeds.site','=','users.name')
  ->join('posts',['extfeeds.title' =>'posts.title','extfeeds.user_id'=> 'posts.user_id'])
  ->select('extfeeds.*','posts.id','users.username','users.email','users.image')
  ->where('site', $user->name)->get();
//  dd($feeds );
    $feeds = json_decode($feeds, true);
  array_push($feed, $feeds);
}
  $ex =[];
  for ($i=0; $i < count($feed) ; $i++) {
    for ($j=0; $j <count($feed[$i]) ; $j++) {
       $rv=$feed[$i][$j];
    //   krsort($rv);
      array_push($ex, $rv);
      //dd($ex);
    }
  }
  //dd($ex);
  usort($ex, $this->build_sorter('id'));

    //arsort($ex);
  krsort($ex);
  //dd($ex);
  //$feed = json_decode($feed, true);

return $ex;


}

public function checker()
{


  /////////////

/*
  $query = "SELECT * FROM notifications  WHERE status_user_id=($user_string) and sender_id !=($user_string) ORDER BY notif_id DESC";

  $result = mysqli_query($con, $query);
  $output = '';
  if(mysqli_num_rows($result) > 0)
  {

  while($row = mysqli_fetch_array($result))

  {
  }

  else{
      $output .= '<a href="#" class="dropdown-item">No Noti Found</a>';
  }

  $status_query = "SELECT * FROM notifications WHERE status_user_id=($user_string) and sender_id !=($user_string) and comment_status=0 ";
  $result_query = mysqli_query($con, $status_query);
  $count = mysqli_num_rows($result_query);

  $data = array(
     'notification' => $output,
     'unseen_notification'  => $count
  );

  echo json_encode($data);




  ////////////
  */

}

    public function fetchAllRss()
    {
    /*  if (file_exists(storage_path('app/'.$this->user."/rss/rss.xml"))) {
                  $xml = file_get_contents(storage_path('app/'.$this->user."/rss/rss.xml"));
                  $url = storage_path('app/'.$this->user."/rss/rss.xml");
          } else {
          $xml = file_get_contents(base_path("storage/rss/rss.xml"));
          $url = base_path("storage/rss/rss.xml");
          }
        $url = storage_path('app/'.$this->user."/rss/rss.xml");
        $feed = [];
        if (strlen($xml != "")) {
            $rss = new \DOMDocument();

            $user = Auth::user();
            //$data= ext_rss::where('user_id', $user['id'])->get();
            //$data=[];
          //  $urlArray = json_decode($data, true);
            $urlArray2 = array(
                array('title' => $user['name'], 'url' => $url, 'desc' => '', 'link' => '', 'image' => $user['image'], 'time' => ''),
              //  array('title' => 'Stratechery by Ben Thompson',  'url' => 'http://stratechery.com/feed/' , 'desc' => 'On the business, strategy, and impact of technology.', 'link' => '', 'image' => "https://stratechery.com/wp-content/uploads/2018/03/cropped-android-chrome-512x512-1-32x32.png", 'time' => ' Fri, 12 Jul 2019 16:06:22 +0000')
            );
          //  $result = array_merge($urlArray, $urlArray2);
            foreach ($urlArray2 as $url) {
            //if (extfeeds::where('site', $url["title"])->doesntExist() == 1) {
              //  dd($url['link']);
              $rss->load($url['url']);
              $user = Auth::user();
              $feeds = DB::table('extfeeds')->where('user_id', $user['id'])->get();
              foreach ($rss->getElementsByTagName('item') as $node) {
                   if (count($rss->getElementsByTagName('item')) == count($feeds)) {
                return false;
              }else{

                if (!isset($node->getElementsByTagName('image')->item(0)->nodeValue)) {
                  $item = array(
                    'user_id'          => $user['id'],
                    'site'  => $url['title'],
                    'site_image'  => $url['image'],
                    'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
                    'des'  => isset( $node->getElementsByTagName('description')->item(0)->nodeValue) ?
                    $node->getElementsByTagName('description')->item(0)->nodeValue : '',
                    //'link'  => $node->getElementsByTagName('link')->item(0)->nodeValue . "?d=" . base64_encode(SITE_URL),
                    'link'  => $node->getElementsByTagName('link')->item(0)->nodeValue,
                    'date'  => date("F j, Y, g:i a", strtotime(isset($node->getElementsByTagName('pubDate')->item(0)->nodeValue) ?
                    $node->getElementsByTagName('pubDate')->item(0)->nodeValue : '')),
                    'image'  => "",

                  );
                } else {
                  $item = array(
                    'user_id'          => $user['id'],
                    'site'  => $url['title'],
                    'site_image'  => $url['image'],
                    'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
                    'des'  => $node->getElementsByTagName('description')->item(0)->nodeValue,
                    'link'  => $node->getElementsByTagName('link')->item(0)->nodeValue,
                    'date'  => date("F j, Y, g:i a", strtotime($node->getElementsByTagName('pubDate')->item(0)->nodeValue)),
                    'image'  => $node->getElementsByTagName('image')->item(0)->nodeValue,
                  );
                }
                //}
              }
              array_push($feed, $item);
              }

            }

            */
            $feed = $this->getPosts();
            $this->postFixer("posts");

                //  print_r($feed);
                $user = DB::table('users')->where('username', $this->user)->first();
                  foreach ($feed as $key => $value) {

                  //  dd($this->user."/post/" . strtolower(strip_tags($value['slug' ])));
                  if (extfeeds::Where('link', "/post/" . strtolower(strip_tags($value['slug' ])))->Where('site', "=", $user->name)->doesntExist()== 1) {
                    $feedId  = DB::table('extfeeds')->insert([
                        'user_id'          =>$user->id,
                        'site'             => $user->name,
                        'site_image'       => $user->image,
                        'title'            => strip_tags($value['title']),
                        'des'             =>  strip_tags($value['body']),
                        'link'             => "/post/" . $value['slug' ],
                        'date'    => $value['date'],
                        'image'   => $value['image'],
                      ]);
                  }
                  };

                //  dd($feed);

                return $feed;

              //}
          }
          public function Thoughts()
          {

                  $feed = $this->getThoughts($this->user);
                //  $this->postFixer("micro-blog-posts");
                        krsort($feed);


                    //    dd($feed);
                      return $feed;
                    //}
                }
public function cleaner()
{
  $user= DB::table('users')->where('username', $this->user)->first();
  if (extfeeds::Where('user_id', '=', $user->id)->Where('site', "!=", $user->name)->exists()== 1) {
  return extfeeds::Where('user_id', '=', $user->id)->Where('site', "!=", $user->name)->delete();
  }
  $this->fetchAllRss();
}
    //RSS designed By DMAtrix;
    public function fetchRss()
    {
      if (file_exists(storage_path('app/'.$this->user."/rss/rss.xml"))) {
                  $xml = file_get_contents(storage_path('app/'.$this->user."/rss/rss.xml"));
                  $url = storage_path('app/'.$this->user."/rss/rss.xml");
          } else {
          $xml = file_get_contents(base_path("storage/rss/rss.xml"));
          $url = base_path("storage/rss/rss.xml");
          }
      $feed = [];
        if (strlen($xml !== "")) {
            $feed = [];
            $rss = new \DOMDocument();
            $user = Auth::user();
            $urlArray = array(
                array('name' => $user['name'], 'url' => $url, 'img' => $user['image']),
            );

            foreach ($urlArray as $url) {
                $rss->load($url['url']);

                foreach ($rss->getElementsByTagName('item') as $node) {
                    $item = array(
                        'site'  => $url['name'],
                        'img'  => $url['img'],
                        'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
                        'desc'  => $node->getElementsByTagName('description')->item(0)->nodeValue,
                        'link'  => $node->getElementsByTagName('link')->item(0)->nodeValue,
                        'date'  => date("F j, Y, g:i a", strtotime($node->getElementsByTagName('pubDate')->item(0)->nodeValue)),
                        'image'  => $node->getElementsByTagName('image')->item(0)->nodeValue,
                    );
                    array_push($feed, $item);
                }
            }
            krsort($feed);
            return $feed;
        } else {
            return [];
        }
        krsort($feed);
        return $feed;

    }
    //store rss By DMAtrix
    public function createRSS()
    {
      //  $user = file_get_contents("./src/config/auth.json");
//$user = Auth::user();
$user= DB::table('users')->where('username', $this->user)->first();
//$user = json_decode($user, true);
//dd($user->name);
          date_default_timezone_set("Africa/Lagos");
        $Feed = new RSS2;
        // Setting some basic channel elements. These three elements are mandatory.
        $Feed->setTitle($user->name);
        $Feed->setLink(storage_path('app/'.$this->user.'./rss/rss.xml'));
        $Feed->setDescription("");

        // Image title and link must match with the 'title' and 'link' channel elements for RSS 2.0,
        // which were set above.
        $Feed->setImage($user->name, '', $user->image);

        $Feed->setChannelElement('language', 'en-US');
        $Feed->setDate(date(DATE_RSS, time()));
        $Feed->setChannelElement('pubDate', date(\DATE_RSS, strtotime('2013-04-06')));


        $Feed->setSelfLink(storage_path('app/'.$this->user."/rss/rss.xml"));
        $Feed->setAtomLink('http://pubsubhubbub.appspot.com', 'hub');

        $Feed->addNamespace('creativeCommons', 'http://backend.userland.com/creativeCommonsRssModule');
        $Feed->setChannelElement('creativeCommons:license', 'http://www.creativecommons.org/licenses/by/1.0');

        $Feed->addGenerator();

        $finder = new Finder();
        $finder->files()->in(storage_path().'/app/'.$this->user.'/content');

        if ($finder->hasResults()) {
            foreach ($finder as $file) {
                $document = $file->getContents();
                $parser = new Parser();
                $document = $parser->parse($document);
                $yaml = $document->getYAML();
                $body = $document->getContent();

                $parsedown  = new Parsedown();

                $title = isset($yaml['title']) ? $parsedown->text($yaml['title']) : '';
                $slug = $parsedown->text($yaml['slug']);
                $image = isset($yaml['image']) ? $parsedown->text($yaml['image']) : '';
                $slug = preg_replace("/<[^>]+>/", '', $slug);
                $image = preg_replace("/<[^>]+>/", '', $image);
                $bd = $parsedown->text($body);

                $time = $parsedown->text($yaml['timestamp']);
                $url = $parsedown->text($yaml['post_dir']);
                $newItem = $Feed->createNewItem();
                $newItem->setTitle(strip_tags($title));
                $newItem->setLink($this->user."/post/" . strtolower($slug));
                $newItem->setDescription(substr(strip_tags($bd), 0, 100));
                $newItem->setDate(date(\DateTime::RSS, strtotime($yaml['timestamp'])));

                $newItem->setAuthor($user->name, $user->email);
                $newItem->setId($url, true);
                $newItem->addElement('source', $user->name . '\'s page');

                $newItem->addElement('image', $image);


                $Feed->addItem($newItem);
            }
            $myFeed = $Feed->generateFeed();
            $handle = $this->user."/rss/rss.xml";
          //  dd($handle);
            $doc = Storage::put($handle, $myFeed);
            //        fwrite($handle, $myFeed);
            //      fclose($handle);
           // $strxml = $Feed->printFeed();
$this->fetchAllRss();
        } else {
            return false;
        }
    }

    public function DemoRSS()
    {
      //  $user = file_get_contents("./src/config/auth.json");
        //$user = json_decode($user, true);
$user = Auth::user();
          date_default_timezone_set("Africa/Lagos");
        $Feed = new RSS2;
        // Setting some basic channel elements. These three elements are mandatory.
        $Feed->setTitle($user['name']);
        $Feed->setLink(storage_path('app/'.$this->user.'./rss/rss.xml'));
        $Feed->setDescription("");

        // Image title and link must match with the 'title' and 'link' channel elements for RSS 2.0,
        // which were set above.
        $Feed->setImage($user['name'], '', $user['image']);

        $Feed->setChannelElement('language', 'en-US');
        $Feed->setDate(date(DATE_RSS, time()));
        $Feed->setChannelElement('pubDate', date(\DATE_RSS, strtotime('2013-04-06')));


        $Feed->setSelfLink(storage_path('app/'.$this->user."/rss/rss.xml"));
        $Feed->setAtomLink('http://pubsubhubbub.appspot.com', 'hub');

        $Feed->addNamespace('creativeCommons', 'http://backend.userland.com/creativeCommonsRssModule');
        $Feed->setChannelElement('creativeCommons:license', 'http://www.creativecommons.org/licenses/by/1.0');

        $Feed->addGenerator();


            $myFeed = $Feed->generateFeed();

            $handle = $this->user."/rss/rss.xml";
          //  dd($handle);
            $doc = Storage::put($handle, $myFeed);
            //        fwrite($handle, $myFeed);
            //      fclose($handle);
           // $strxml = $Feed->printFeed();

    }

    //RSS designed By DMAtrix;
    public function getRss()
    {
        $user = file_get_contents("./src/config/auth.json");
        $user = json_decode($user, true);

        date_default_timezone_set('UTC');
        $Feed = new RSS2;
        // Setting some basic channel elements. These three elements are mandatory.
        $Feed->setTitle($user['name']);
        $Feed->setLink(SITE_URL);
        $Feed->setDescription("");

        // Image title and link must match with the 'title' and 'link' channel elements for RSS 2.0,
        // which were set above.
        $Feed->setImage($user['name'], '', $user['image']);

        $Feed->setChannelElement('language', 'en-US');
        $Feed->setDate(date(DATE_RSS, time()));
        $Feed->setChannelElement('pubDate', date(\DATE_RSS, strtotime('2013-04-06')));


        $Feed->setSelfLink(SITE_URL . 'storage/rss/rss.xml');
        $Feed->setAtomLink('http://pubsubhubbub.appspot.com', 'hub');

        $Feed->addNamespace('creativeCommons', 'http://backend.userland.com/creativeCommonsRssModule');
        $Feed->setChannelElement('creativeCommons:license', 'http://www.creativecommons.org/licenses/by/1.0');

        $Feed->addGenerator();

        $finder = new Finder();
        $finder->files()->in($this->user);

        if ($finder->hasResults()) {
            foreach ($finder as $file) {
                $document = $file->getContents();
                $parser = new Parser();
                $document = $parser->parse($document);
                $yaml = $document->getYAML();
                $body = $document->getContent();

                $parsedown  = new Parsedown();

                $title = $parsedown->text($yaml['title']);
                $slug = $parsedown->text($yaml['slug']);
                $slug = preg_replace("/<[^>]+>/", '', $slug);
                $bd = $parsedown->text($body);
                $time = $parsedown->text(time());
                $url = $parsedown->text($yaml['post_dir']);

                $newItem = $Feed->createNewItem();
                $newItem->setTitle(strip_tags($title));
                $newItem->setLink($slug);
                $newItem->setDescription(substr(strip_tags($bd), 0, 100));
                $newItem->setDate("2013-04-07 00:50:30");

                $newItem->setAuthor($user['name'], $user['email']);
                $newItem->setId($url, true);
                $newItem->addElement('source', $user['name'] . '\'s page', array('url' => SITE_URL));
                $Feed->addItem($newItem);
            }
            $myFeed = $Feed->generateFeed();

            $strxml = $Feed->printFeed();
        } else {
            return false;
        }
    }
    public function subscriber()
    {

      $user =   DB::table('users')->where('username', $this->user)->first();

        $data= DB::table('following')->where('follower_id', $user->id)->get();
        $data = json_decode($data, true);
        //  dd($data);

          $follower = [];
          foreach ($data as $key => $value) {

            $follow = DB::table('users')->where('id', $value['my_id'])->get();

             foreach($follow as $key => $follow){

            $content['name'] = $follow->name;
            $content['username'] = $follow->username;
            $content['img'] = $follow->image;
            $content['id'] = $follow->id;
            $content['desc'] = $follow->short_bio;
            array_push($follower, $content);

        }
          }
          return $follower;
    }
    public function subscription()
    {
      //$user = Auth::user();
    $user =   DB::table('users')->where('username', $this->user)->first();
      $data= DB::table('following')->where('my_id', $user->id)->get();
      $data = json_decode($data, true);

    //  dd($data);
        $following = [];
        foreach ($data as $key => $value) {
  $follower= DB::table('users')->where('id', $value['follower_id'])->get();
          foreach($follower as $key => $follower){
          $content['name'] = $follower->name;
          $content['username'] = $follower->username;
          $content['img'] = $follower->image;
          $content['id'] = $follower->id;
          $content['desc'] = $follower->short_bio;
            array_push($following, $content);
        }
      }
      //  dd( $following);
        return $following;
    }
    //code for returnng details of each codes
    public function getEach($id)
    {
        $finder = new Finder();
        // find all files in the current directory
        $finder->files()->in($this->user);
        $posts = [];
        if ($finder->hasResults()) {
            foreach ($finder as $file) {
                $document = $file->getContents();
                $parser = new Parser();
                $document = $parser->parse($document);
                $yaml = $document->getYAML();
                $body = $document->getContent();
                //$document = FileSystem::read($this->user);
                $parsedown  = new Parsedown();
                $slug = $parsedown->text($yaml['slug']);
                $slug = preg_replace("/<[^>]+>/", '', $slug);
                if ($slug == $id) {
                    $title = isset($yaml['title']) ? $parsedown->text($yaml['title']) : '';;
                    $bd = $parsedown->text($body);
                    $time = $parsedown->text($yaml['timestamp']);
                    $url = $parsedown->text($yaml['post_dir']);
                    $content['title'] = $title;
                    $content['body'] = $bd;
                    $content['url'] = $url;
                    $content['timestamp'] = $time;
                    array_push($posts, $content);
                }
            }
            return $posts;
        }
    }
    //end of get a post function

    // post
    public function tagPosts($id)
    {
        $finder = new Finder();
        // find all files in the current directory
        $finder->files()->in($this->user);
        $posts = [];
        if ($finder->hasResults()) {
            foreach ($finder as $file) {
                $document = $file->getContents();
                $parser = new Parser();
                $document = $parser->parse($document);
                $yaml = $document->getYAML();
                $body = $document->getContent();
                //$document = FileSystem::read($this->user);
                $parsedown  = new Parsedown();
                // skip this document if it has no tags
                if (!isset($yaml['tags'])) {
                    continue;
                }
                $tags = $yaml['tags'];
                for ($i = 0; $i < count($tags); $i++) {
                    // strip away the leading "#" of the tag name
                    if (substr($tags[$i], 1) == $id) {
                        $slug = $parsedown->text($yaml['slug']);
                        $bd = $parsedown->text($body);

                        // get the first image in the post body
                        // it will serve as the preview image
                        preg_match('/<img[^>]+src="((\/|\w|-)+\.[a-z]+)"[^>]*\>/i', $bd, $matches);
                        $first_img = false;
                        if (isset($matches[1])) {
                            // there are images
                            $first_img = $matches[1];
                            // strip all images from the text
                            $bd = preg_replace("/<img[^>]+\>/i", " ", $bd);
                        }
                        $time = $parsedown->text($yaml['timestamp']);
                        $url = $parsedown->text($yaml['post_dir']);
                        if (isset($yaml['title'])) {
                            $title = $parsedown->text($yaml['title']);
                            $content['title'] = $title;
                        }
                        $content['body'] = $bd;
                        $content['url'] = $url;
                        $content['timestamp'] = $time;
                        $content['tags'] = $tags;
                        $content['slug'] = $yaml['slug'];
                        $content['preview_img'] = $first_img;
                        array_push($posts, $content);
                    }
                }
            }
        }
        return $posts;
    }

    //kjarts code for deleting post
    public function delete($id, $extra)
    {
        $finder = new Finder();
        // find all files in the current directory
        $finder->files()->in($this->user);
        if ($finder->hasResults()) {
            foreach ($finder as $file) {
                $document = $file->getContents();
                $parser = new Parser();
                $document = $parser->parse($document);
                $yaml = $document->getYAML();
                $body = $document->getContent();
                $parsedown  = new Parsedown();
                $slug = $parsedown->text($yaml['slug']);
                $slug = preg_replace("/<[^>]+>/", '', $slug);
                if ($slug == $id) {
                    unlink($file);
                    $delete = "File deleted successfully";
                }
            }
            if (!$extra) {
                $this->createRSS();
            }
            return $delete;
        }
    }

    //deleteapOST by ProblemSolved;
    public function deletePost($post)
    {
        $finder = new Finder();
        // find post in the current directory
        $finder->files()->in($this->user)->name($post . '.md');
        if (!$finder->hasResults()) {
            return $this->redirect('/404');
        } else {
            ///coming back for some modifications
            unlink($this->user.$post.'.md');
            $this->createRSS();
        }
    }

    //get single post

    public function getPost($username,$postSlug){
    //  $user = $this->user($username);
      $user =   DB::table('users')->where('username', $username)->first();
    //   echo $postSlug;
      $post = DB::table('posts')->where(['slug'=>$postSlug,'user_id'=>$user->id])->first();
    //   dd($post);
      if(!empty($post)) {

        $parsedown  = new Parsedown();
        $createdAt = Carbon::parse($post->created_at);
        $content['tags'] = $post->tags;
        $content['title'] =$post->title;
        $content['body'] = $parsedown->text($post->content);
        $content['date'] = $createdAt->format('M jS, Y h:i A');
        $content['slug'] = $post->slug;
        $content['id'] = $post->id;
        $content['image'] = $post->image;
        return $content;

      }

    }

    //Get all post
    public function getThoughts(){
    //  $user =  $this->user($username);
      $user =   DB::table('users')->where('username', $this->user)->first();

      $thoughts = DB::table('thoughts')->where('user_id',$user->id)->get();
//dd($thoughts);
      if(!empty($thoughts)){

        $allPost = [];
      foreach($thoughts as $thought){

        $content['body']  = $thought->content;
        $content['date']  =  $thought->created_at;
        $content['id'] = $thought->id;
        array_push($allPost,$content);
      }
    //  $this->fetchAllRss();
    //    dd($allPost);
      return $allPost;

      }

    }

    public function getPublishedPosts(){

      $user =   DB::table('users')->where('username', $this->user)->first();
      $matchThese = [['user_id',$user->id],['action','publish']];
      $orthis = [['user_id',$user->id],['action',NULL]];
          $posts = DB::table('posts')->where($matchThese)->orWhere($orthis)->orderBy('id','DESC')->get();
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
            $content['body']  = $this->trim_words($postContent, 200);
            $content['tags']  = $post->tags;
            $content['slug']  = $post->slug;
            $content['image'] = $first_img;
            $content['date']  =  $createdAt->format('M jS, Y h:i A');;
            $content['id'] = $post->id;
            
            array_push($allPost,$content);
          }
          return $allPost;

          }

    }

        public function getPosts(){
        //  $user =  $this->user($username);
          $user =   DB::table('users')->where('username', $this->user)->first();

          $posts = DB::table('posts')->where('user_id',$user->id)->orderBy('id','DESC')->get();
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
            $content['body']  = $this->trim_words($postContent, 200);
            $content['tags']  = $post->tags;
            $content['slug']  = $post->slug;
            $content['image'] = $first_img;
            $content['date']  =  $createdAt->format('M jS, Y h:i A');;
            $content['id'] = $post->id;
            $content['status'] = $post->action;
            array_push($allPost,$content);
          }
          return $allPost;

          }

        }

 /*  public function getPost($post)
    {
        $finder = new Finder();
        // find post in the current directory
        $finder->files()->in(storage_path().'/app/'.$this->user.'/content')->name($post . '.md');
        $content = [];
        if (!$finder->hasResults()) {
            return false;
        } else {
            foreach ($finder as $file) {
                $document = $file->getContents();
                $parser = new Parser();
                $document = $parser->parse($document);
                $yaml = $document->getYAML();
                $body = $document->getContent();
                $parsedown  = new Parsedown();
                //$yamlTag = isset($yaml['tags']) ? $yaml['tags'] : [];
              //  $tags = [];
              //  foreach ($yamlTag as $tag) {
                //    $removeHashTag = explode('#', $tag);
                //    $tags[] = trim(end($removeHashTag));
                //}

                $slug = $parsedown->text($yaml['slug']);
                $slug = preg_replace("/<[^>]+>/", '', $slug);
                $title = isset($yaml['title']) ? $parsedown->text($yaml['title']) : '';
                $bd = $parsedown->text($body);
                //preg_match('/<img[^>]+src="((\/|\w|-)+\.[a-z]+)"[^>]*\>/i', $bd, $matches);
                //$first_img = '';
              //  if (isset($matches[1])) {
                //    $first_img = $matches[1];
                //}
                $image= isset($yaml['image']) ? $parsedown->text($yaml['image']) : "";
                $time = $parsedown->text($yaml['timestamp']);
                $url = $parsedown->text($yaml['post_dir']);
              //  $content['tags'] = $tags;
                $content['title'] = strip_tags($title);
                $content['body'] = $bd;
                $content['url'] = $url;
                $content['timestamp'] = $time;
                $content['date'] = date('d M Y ', $post);
                $content['crawlerImage'] = strip_tags($image);
                $content['slug'] = $this->clean($slug);
                $SlugArray = explode('-',$this->clean($slug));
                $content['post_id']=end($SlugArray);
                array_pop($SlugArray);
                $content['post_title']=implode('-',array_filter(array_map('trim', $SlugArray)));
            }
            return $content;
        }
    }*/

    public function update_Post($title, $content, $tags, $image, $extra,$post_id)
    {
        $time = date(DATE_RSS, time());
        $unix = strtotime($time);
        // Write md file
        $document = FrontMatter::parse($content);
        $md = new Parser();
        $markdown = $md->parse($document);

        $yaml = $markdown->getYAML();
        $html = $markdown->getContent();
        //$doc = Storage::put($this->user, $yaml . "\n" . $html);

        $yamlfile = new Doc();
        if($title != ""){
        $yamlfile['title'] = $title;
        }
        if ($tags != "") {
            $tag = explode(",", $tags);
            $put = [];
            foreach ($tag as $value) {
                array_push($put, $value);
            }
            $yamlfile['tags'] = $put;
        }
        if (!empty($image)) {
            foreach ($image as $key => $value) {
                $decoded = base64_decode($image[$key]);
                $url = "./storage/images/" . $key;
                Storage::put($url, $decoded);
            }
        }

        if (!$extra) {
            $yamlfile['post_dir'] = SITE_URL . "/storage/contents/{$post_id}";
        } else {
            $yamlfile['post_dir'] = SITE_URL . "/storage/drafts/{$post_id}";
            $yamlfile['image'] = "./storage/images/" . $key;
        }

        // create slug by first removing spaces
        $striped = str_replace(' ', '-', $title);
        // then removing encoded html chars
        $striped = preg_replace("/(&#[0-9]+;)/", "", $striped);
        $yamlfile['slug'] = $striped . "-{$post_id}";
        $yamlfile['timestamp'] = $time;
        $yamlfile->setContent($content);
        $yaml = FrontMatter::dump($yamlfile);
        $dir = $this->user.$post_id.'.md';
        $explodeSChars = explode('&#10;',$yaml);
        $fopen = fopen($dir,'w');
        foreach($explodeSChars as $yamlTextContent )
        {
            $doc = fwrite($fopen, $yamlTextContent.PHP_EOL);
        }

        if (!$extra) {
            if ($doc) {
                $result =  array("error" => false, "action"=>"update", "message" => "Post Updated successfully");
                $this->createRSS();
            } else {
                $result = array("error" => true,"action"=>"update", "message" => "Fail while Updating, please try again");
            }
        } else {
            if ($doc) {
                $result = array("error" => false, "action"=>"save_draft", "message" => "Draft saved successfully");
            } else {
                $result = array("error" => true, "action"=>"save_draft", "message" => "Fail while updating, please try again");
            }
        }

        return $result;


    }


    public function redirect($location)
    {
        header('Location:' . $location);
    }

    public function getRelatedPost($limit = 4, $tags, $skip_post)
    {

        $finder = new Finder();
        // find post in the current directory
        $finder->files()->in($this->user)->notName($skip_post . '.md')->contains($tags);
        $posts = [];
        if ($finder->hasResults()) {
            foreach ($finder as $file) {
                $document = $file->getContents();
                $parser = new Parser();
                $document = $parser->parse($document);
                $yaml = $document->getYAML();
                $body = $document->getContent();
                //$document = FileSystem::read($this->user);
                $parsedown  = new Parsedown();
                if (!isset($yaml['tags'])) {
                    continue;
                }
                $tags = $yaml['tags'];

                $slug = $parsedown->text($yaml['slug']);
                $image = isset($yaml['image']) ? $parsedown->text($yaml['image']) : '';
                $slug = preg_replace("/<[^>]+>/", '', $slug);
                $image = preg_replace("/<[^>]+>/", '', $image);
                $bd = $parsedown->text($body);
                preg_match('/<img[^>]+src="((\/|\w|-)+\.[a-z]+)"[^>]*\>/i', $bd, $matches);
                $first_img = false;
                if (isset($matches[1])) {
                    // there are images
                    $first_img = $matches[1];
                    // strip all images from the text
                    $bd = preg_replace("/<img[^>]+\>/i", " ", $bd);
                }
                $time = $parsedown->text($yaml['timestamp']);
                $url = $parsedown->text($yaml['post_dir']);
                if (isset($yaml['title'])) {
                    $title = $parsedown->text($yaml['title']);
                    $content['title'] = $title;
                }
                $content['url'] = $url;
                $content['timestamp'] = $time;
                $content['tags'] = str_replace('#', '', implode(',', $tags));
                $content['slug'] = $this->clean($slug);
                $content['preview_img'] = $first_img;
                //content['slug'] = $slug;
                $file = explode("-", $slug);
                $filename = $file[count($file) - 1];
                $content['filename'] = $filename;
                //content['timestamp'] = $time;
                $SlugArray = explode('-',$this->clean($slug));
                $content['post_id']=end($SlugArray);
                array_pop($SlugArray);
                $content['post_title']=implode('-',array_filter(array_map('trim', $SlugArray)));
                $content['image'] = $image;
                $content['date'] = date('d M Y ', $filename);
                $content['created_at'] = date('F j, Y, g:i a',$filename);
                array_push($posts, $content);
            }
            $this->array_sort_by_column($posts,'created_at');
            $countPosts = count($posts);
            if ($countPosts > $limit)
                array_shift($posts);
            return $posts;
        } else {
            return false;
        }
    }
    //stupid code by problemSolved ends here

    /**
     * updates a post stored in an md file
     * and echos a json object;
     *
     * @param [type] $mdfile
     * @param [type] $title
     * @param [type] $content
     * @param [type] $tags
     * @param [type] $image
     * @return void
     */
    public function updatePost($mdfile, $title, $content, $tags, $image)
    {
        $text = file_get_contents($mdfile);
        $document = FrontMatter::parse($text);
        $date = date("F j, Y, g:i a");
        // var_dump($document);
        // var_dump($document->getConfig());
        // var_dump($document->getContent());
        // var_dump($document['tags']);
        $document = new Doc();
        $tmp_title = explode(' ', $title);
        $slug = implode('-', $tmp_title);
        $document['title'] = $title;
        $document['slug'] = $slug;
        $document['timestamp'] = $date;
        $document['tags'] = explode(',', $tags);
        $hashedTags = [];
        // adding hash to the tags before storage
        foreach ($document['tags'] as $tag) {
            $hashedTags[] = '#' . $tag;
        }
        $document['tags'] = $hashedTags;
        $document['image'] = $image;
        $document->setContent($content);
        $yamlText = FrontMatter::dump($document);
        // var_dump($yamlText);
        $doc = Storage::put($mdfile, $yamlText);
        if ($doc) {
            $result = array("error" => false, "message" => "Post published successfully");
        } else {
            $result = array("error" => true, "message" => "Fail while publishing, please try again");
        }
        echo json_encode($result);
    }

    public function getSinglePost($id)
    {
        $directory = "./storage/contents/${id}.md";
        // var_dump($directory);
        $document = FrontMatter::parse(file_get_contents($directory));
        // var_dump($document);
        $content['title'] = $document['title'];
        $content['body'] = $document->getContent();
        // $content['url'] = $url;
        $content['timestamp'] = $document['timestamp'];

        return $content;
    }

    public function addVideo($url, $title, $content)
    {
        $time = date("F j, Y, g:i a");
        $unix = strtotime($time);
        // Write md file
        $document = FrontMatter::parse($content);
        $md = new Parser();
        $markdown = $md->parse($document);

        $yaml = $markdown->getYAML();
        $html = $markdown->getContent();
        //$doc = Storage::put($this->user, $yaml . "\n" . $html);

        $yamlfile = new Doc();
        $yamlfile['title'] = $title;
        $yamlfile['url'] = $url;

        $striped = str_replace(' ', '-', $title);
        $yamlfile['slug'] = $striped . "-{$unix}";
        $yamlfile['timestamp'] = $time;
        $yamlfile->setContent($content);
        $yaml = FrontMatter::dump($yamlfile);
        $file = $this->user;
        $dir = $file . $unix . ".md";
        //return $dir; die();
        $doc = Storage::put($dir, $yaml);
        if ($doc) {
            return true;
        }
        return false;
    }

    //get video
    public function getVideo()
    {
        $finder = new Finder();

        // find all files in the current directory
        $finder->files()->in($this->user);
        $videos = [];
        if ($finder->hasResults()) {
            foreach ($finder as $file) {
                $document = $file->getContents();
                $parser = new Parser();
                $document = $parser->parse($document);
                $yaml = $document->getYAML();
                $body = $document->getContent();
                //$document = FileSystem::read($this->user);
                $parsedown  = new Parsedown();
                $title = $parsedown->text($yaml['title']);
                $bd = $parsedown->text($body);
                $time = $parsedown->text($yaml['timestamp']);
                $url = $parsedown->text($yaml['url']);
                $content['title'] = $title;
                $content['description'] = $bd;
                $content['domain'] = $url;
                $content['timestamp'] = $time;
                array_push($videos, $content);
            }
            return $videos;
        } else {
            return $videos;
        }
    }
}
