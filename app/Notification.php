<?php

namespace Lucid;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    //

    protected $fillable = [
      'post_id',
      'parent_comment_id',
      'comment',
      'sender_id',
      'user_id',
      'status',
      'action',
      'type'
    ];


}
