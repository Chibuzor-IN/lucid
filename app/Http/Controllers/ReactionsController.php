<?php

namespace Lucid\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class ReactionsController extends Controller
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


    public function like(Request $request)
    {

  $post = DB::table('posts')->where('id', $request->id)->first();
//dd($request->id);
  $user = Auth::user();
switch ($request->act){
    case "1":
if (DB::table('notifications')->where(['post_id'=>$request->id,'sender_id'=> $user['id'], 'action'=> "Like"])->exists()) {

}else {

    $like = DB::table('notifications')->insert([
      'post_id'=>$request->id,
      'parent_comment_id'=> null,
      'comment'=>null,
      'sender_id'=> $user['id'],
      'user_id'=>$post->user_id,
      'status'=> 0,
      'action'=>"Like",
      'type'=>"Reaction",
    ]);

  }
    break;

    case "0":
    $like= DB::table('notifications')
    ->where([
      'post_id'   =>$request->id,
      'sender_id' => $user['id'],
      'action'    =>"Like",
      'type'      =>"Reaction"
      ])->delete();


    break;

}
$count = DB::table('notifications')->where(['post_id'=>$request->id,'action'=> "like"])->count();

$count ="<sub id='count$request->id'>$count</sub>";

$link = DB::table('notifications')->where(['post_id'=>$request->id,'sender_id'=> $user['id'], 'action'=> "like"])->exists();
//dd($link);
if ($link) {
  $output = "
<span id='like$request->id'>
<button type='button' title='unlike this Post' onclick='like(0,$request->id)' class='btn'><i class='fas fa-thumbs-up text-secondary' style='font-size: 1.2em;'></i>
$count
</button></span>
";
}else {
    $output = "
  <span id='like$request->id'>
  <button type='button' title='like this Post' onclick='like(1,$request->id)' class='btn'><i class='fas fa-thumbs-up' style='font-size: 1.2em;'></i>
$count
  </button></span>
  ";

    }
    $data = array(
       'button' => $output,
       'count'  => $count
    );
    return response()->json($data);
  }


  //Love reaction
  public function love(Request $request)
  {

$post = DB::table('posts')->where('id', $request->id)->first();
//dd($request->id);
$user = Auth::user();
switch ($request->act){
  case "1":
  if (DB::table('notifications')->where(['post_id'=>$request->id,'sender_id'=> $user['id'], 'action'=> "Love"])->exists()) {

  }else {
  $love = DB::table('notifications')->insert([
    'post_id'=>$request->id,
    'parent_comment_id'=> null,
    'comment'=>null,
    'sender_id'=> $user['id'],
    'user_id'=>$post->user_id,
    'status'=> 0,
    'action'=>"Love",
    'type'=>"Reaction",
  ]);
}
  break;

  case "0":
  $love= DB::table('notifications')
  ->where([
    'post_id'   =>$request->id,
    'sender_id' => $user['id'],
    'action'    =>"Love",
    'type'      =>"Reaction"
    ])->delete();


  break;

}
$count = DB::table('notifications')->where(['post_id'=>$request->id,'action'=> "Love"])->count();

$count ="<sub id='count$request->id'>$count</sub>";

$link = DB::table('notifications')->where(['post_id'=>$request->id,'sender_id'=> $user['id'], 'action'=> "Love"])->exists();
//dd($link);
if ($link) {
$output = "
<span id='love$request->id'>
  <button type='button' title='' onclick='love(0,$request->id)' class='btn'>
    <i class='fas fa-heart text-secondary' style='font-size: 1.2em;'></i>
    <sub id='count$request->id'> $count </sub>
  </button>
</span>
";
}else {
  $output = "
  <span id='love$request->id'>
    <button type='button' title='' onclick='love(1,$request->id)' class='btn'>
      <i class='fas fa-heart' style='font-size: 1.2em;'></i>
      <sub id='count$request->id'> $count </sub>
    </button>
  </span>
";

  }
  $data = array(
     'button' => $output,
     'count'  => $count
  );
  return response()->json($data);
}
}
