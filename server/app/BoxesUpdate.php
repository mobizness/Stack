<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BoxesUpdate extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
    protected $fillable = [
        'request_id',
        'ap_mac',
        'nas_id',
        'wan_gateway',
        'live_ip_address',
        'wan_name',
        'system_type',
        'machine_type',
        'channel',
        'htmode',
        'hwmode',
        'txpower',
        'total_ram',
        'free_ram',
        'available_ram',
        'procs',
        'uam_listen',
        'user_count',
        'input_octets',
        'output_octets',
        'total_octets',
        'uptime',
        'ssid',
        'timestamp'
    ];
    
    public $timestamps = false;

    protected $table = 'boxes_update';
  
}
