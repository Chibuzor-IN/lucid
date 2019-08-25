<?php

namespace Lucid;

use Illuminate\Database\Eloquent\Model;

class Thought extends Model
{
    //
    protected $fillable = [
      'user_id',
      'content'
    ];
}
