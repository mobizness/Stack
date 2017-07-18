<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'location_id', 'zone_name', 'zone_description',
  ];
}
