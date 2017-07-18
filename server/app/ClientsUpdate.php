<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientsUpdate extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
    protected $fillable = [
        'request_id',
        'ap_mac',
        'user_mac',
        'cust_ip',
        'auth_status',
        'session_id',
        'username',
        'session_time',
        'max_session_time',
        'idle_time',
        'max_idle_time',
        'input_octets',
        'max_input_octets',
        'output_octets',
        'max_output_octets',
        'max_allowed_octets',
        'max_up_bw',
        'max_down_bw',
        'interface',
        'inactive_time',
        'rx_packets',
        'tx_packets',
        'rx_bytes',
        'tx_bytes',
        'tx_retries',
        'tx_failed',
        'signals',
        'signal_avg',
        'tx_bitrate',
        'rx_bitrate',
        'authorized',
        'authenticated',
        'wmm',
        'mfp',
        'tdls',
        'preamble',
        'expected_output',
    ];
    
    public $timestamps = false;

    protected $table = 'clients_update';
  
}
