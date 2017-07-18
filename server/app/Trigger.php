<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trigger extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'brand_id', 'location_id', 'trigger_name', 'trigger_type', 'ttl', 'type', 'tags', 'attr_1', 'attr_2', 'attr_3', 'attr_4',
    'channel', 'match', 'starttime', 'start_hour', 'endtime', 'end_hour', 'periodic_days',
  ];

  /**
   * The attributes that should be casted to native types.
   *
   * @var array
   */
  protected $casts = [
    'active' => 'boolean',
  ];
}
