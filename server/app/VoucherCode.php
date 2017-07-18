<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VoucherCode extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'splash_page_id', 'voucher_id', 'username', 'password', 'description', 'serial', 'active',
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
