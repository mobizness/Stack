<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Network extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'location_id', 'zone_id', 'group_policy_id', 'network_name', 'description', 'dhcp_option_type', 'metric', 'network_radio_mode', 'interface_ipaddr', 'interface_netmask', 'dhcp_start', 'dhcp_end', 'dhcp_lease', 'dhcp_limit',
      'captive_portal_roaming', 'content_filter', 'radius_host_1', 'radius_host_2', 'radius_secret_1', 'radius_secret_2', 'ssid', 'encryption_type', 'encryption_key', 'active', 'ssid_hidden',
      'dhcp_enabled', 'dhcp_relay_on', 'config_type_bridge', 'bridge_to_switch_ports', 'make_part_of_lan', 'captive_portal_enabled', 'radius_8021x_host_1', 'radius_8021x_secret_1',
      'radius_8021x_port_1', 'radius_8021x_host_2', 'radius_8021x_secret_2', 'radius_8021x_port_2', 'radius_8021x_interval', 'access_type', 'captive_portal_ps', 'use_ps_radius',
      'proxy_server_host', 'proxy_server_port', 'dns_1', 'dns_2', 'vlan_id', 'job_id', 'roaming_allowed', 'running_radtest', 'isolate', 'isolate_traffic', 'isolate_icmp', 'band_selection',
      'allowed_urls', 'allowed_domains',
  ];

  /**
   * The attributes that should be casted to native types.
   *
   * @var array
   */
  protected $casts = [
      'metric' => 'int',
      'radius_8021x_port_1' => 'int',
      'radius_8021x_port_2' => 'int',
      'active' => 'boolean',
      'bridge_to_switch_ports' => 'boolean',
      'captive_portal_roaming' => 'boolean',
      'isolate' => 'boolean',
      'isolate_icmp' => 'boolean',
      'isolate_traffic' => 'boolean',
      'make_part_of_lan' => 'boolean',
      'ssid_hidden' => 'boolean',
  ];
}
