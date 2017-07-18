<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Social extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'first_name', 'last_name', 'email', 'gender', 'facebook_id', 'google_id', 'linkedin_id',
  ];
}
