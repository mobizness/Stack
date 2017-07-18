<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeviceType extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'name', 'hs_lanif', 'hs_wanif',
  ];
}
