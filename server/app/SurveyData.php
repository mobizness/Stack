<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SurveyData extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
    protected $fillable = [
        'request_id',
        'ssid',
        'mac',
        'enc',
        'channel',
        'signals',
        'htmode',
        'chain',
        'timestamp'
    ];
    
    public $timestamps = false;

    protected $table = 'survey_update';
  
}
