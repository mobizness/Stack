<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'request_id', 'ap_mac', 'chilli_status', 'flag', 'customer_id', 'input_octets', 'output_octets', 'time_consumed',
    'ip_address', 'user_mac', 'session_id', 'signal', 'inactive_time', 'connected_time',

    'interface',
    'ssid',
    'mac',
    'rx_packets',
    'tx_packets',
    'rx_bytes',
    'tx_bytes',
    'beacon_rx',
    'tx_retries',
    'tx_failed',
    'beacon_loss',
    'signal_avg',
    'tx_bitrate',
    'rx_bitrate',
    'authorized',
    'authenticated',
    'associated',
    'wmm',
    'mfp',
    'tdls',
    'preamble',
    'conn_time',
    'expected_tput'

  ];
}
