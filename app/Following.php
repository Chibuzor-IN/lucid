<?php

namespace Lucid;

use Illuminate\Database\Eloquent\Model;

class Following extends Model
{

  protected $fillable = [
    'my_id', 'follower_id', 'status'
  ];
}
