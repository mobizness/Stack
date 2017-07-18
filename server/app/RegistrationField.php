<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegistrationField extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'form_id', 'label', 'field_type', 'name', 'required', 'order', 'hidden', 'value',
  ];

  /**
   * The attributes that should be casted to native types.
   *
   * @var array
   */
  protected $casts = [
      'required' => 'boolean',
      'hidden' => 'boolean',
  ];
}
