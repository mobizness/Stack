<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Box extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'location_id', 'zone_id', 'device_type_id', 'address', 'alert_interval', 'auto_upgrades', 'band_steering', 'basic_rate_enabled', 'basic_rate_2', 'basic_rate_5',
    'beacon_interval_2', 'beacon_interval_5', 'beacon_interval', 'calledstationid', 'is_cucumber', 'dual_band', 'channel',
    'channel_5', 'debug', 'description', 'development_mode', 'force_upgrades_nightly', 'ht_mode_2', 'ht_mode_5', 'is_monitored',
    'is_online', 'latitude', 'longitude', 'no_scan', 'pppoe_username', 'pppoe_password', 'radio_log_level', 'radiusnasid',
    'radius_secret', 'rsyslog_host', 'rsyslog_port', 'rsyslog_protocol', 'timezone', 'tx_power_2', 'tx_power_5', 'wan_mtu', 'wan_metric_1',
    'wan_dns_1', 'wan_dns_2', 'wan_ip', 'wan_ipaddr', 'wan_proto', 'wan_netmask', 'wan_gateway',
    'server_url', 'server_username', 'server_ssh_port', 'server_connect_port', 'remote_port', 'update_status',
    'firmware', 'serial', 'wan_name', 'machine_type', 'tx_bytes', 'rx_bytes', 'uptime', 'total_ram', 'free_ram', 'procs'
  ];

  /**
   * The attributes that should be casted to native types.
   *
   * @var array
   */
  protected $casts = [
      'wan_mtu' => 'int',
      'wan_metric_1' => 'int',
  ];
}
