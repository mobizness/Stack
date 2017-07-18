<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'location_id', 'splash_page_id', 'batch_name', 'batch_description', 'is_active', 'voucher_value', 'quantity',
      'access_type', 'access_period', 'limit_type', 'session_timeout', 'ilde_timeout', 'validity_minutes',
      'access_restrict_period', 'access_restrict_data', 'expiration', 'secure_password', 'voucher_format',
      'csv_link', 'pdf_link', 'username', 'location_name', 'completed', 'donwload_speed', 'upload_speed',
      'simultaneous_use',
  ];

  /**
   * The attributes that should be casted to native types.
   *
   * @var array
   */
  protected $casts = [
      'is_active' => 'boolean',
      'secure_password' => 'boolean',
      'completed' => 'boolean',
  ];
}
