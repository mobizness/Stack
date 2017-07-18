<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SplashAssociation extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'splash_page_id', 'network_id',
  ];
}
