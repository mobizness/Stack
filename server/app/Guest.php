<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'location_id', 'location_name', 'codes', 'registered', 'username', 'email', 'pic_url',
  ];

  /**
   * The attributes that should be casted to native types.
   *
   * @var array
   */
  protected $casts = [
      'registered' => 'boolean',
  ];
}
