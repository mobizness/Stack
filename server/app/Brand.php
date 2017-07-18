<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'background_color', 'brand_name', 'cname', 'cname_status', 'country', 'from_email', 'from_name', 'locale',
      'network_location', 'quota_project_users', 'quota_vouchers', 'quota_locations', 'quota_boxes', 'quota_splash_views',
      'quota_admins', 'quota_zones', 'quota_triggers', 'quota_projects', 'quota_store', 'timezone', 'url', 'website',
  ];
}
