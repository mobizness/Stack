<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'brand_id', 'project_id', 'country', 'description', 'development_mode', 'is_favourite',
      'location_address', 'location_name', 'network_location', 'reports_active', 'reports_emails',
      'postcode', 'street', 'timezone', 'tagged_logins', 'town',
  ];
}
