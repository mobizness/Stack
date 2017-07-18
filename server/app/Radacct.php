<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Radacct extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
    protected $fillable = [
        
    ];
    
    public $timestamps = false;

    protected $table = 'radacct';
  
}
