<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Network;

class NetworksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($location_id, Request $request)
    {
      $perPage = $request->input('per');
      $networks = Network::where('location_id', $location_id)->paginate($perPage);

      $data = array();
      foreach ($networks as $network) {
        $arry = $network->toArray();
        $arry['created_at'] = $network->created_at->timestamp;
        $arry['updated_at'] = $network->updated_at->timestamp;
        $arry['captive_portal_url'] = '';
        $arry['captive_portal_port'] = 0;
        $arry['captive_portal_uamsecret'] = '';
        $arry['captive_portal_allowed_hosts'] = '';
        $arry['captive_portal_allowed_macs'] = '';
        $arry['captive_portal_ssl_enabled'] = '';
        $arry['ssid_disabled'] = '';
        $arry['zones'] = array();
        $arry['splash_pages'] = array();
        $data[] = $arry;
      }

      return $data;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($location_id, Request $request)
    {
      $network = $request->input('network');
      if (!isset($network)) {
        return array('code' => 0, 'message' => 'Invalid Request');
      }

      $network_name = isset($network['network_name']) ? $network['network_name'] : '';
      $description = isset($network['description']) ? $network['description'] : '';
      $dhcp_option_type = isset($network['dhcp_option_type']) ? $network['dhcp_option_type'] : 6;
      $metric = isset($network['metric']) ? $network['metric'] : 100;
      $network_radio_mode = isset($network['network_radio_mode']) ? $network['network_radio_mode'] : '';
      $interface_ipaddr = isset($network['interface_ipaddr']) ? $network['interface_ipaddr'] : '';
      $interface_netmask = isset($network['interface_netmask']) ? $network['interface_netmask'] : 0;
      $dhcp_start = isset($network['dhcp_start']) ? $network['dhcp_start'] : 0;
      $dhcp_end = isset($network['dhcp_end']) ? $network['dhcp_end'] : 0;
      $dhcp_lease = isset($network['dhcp_lease']) ? $network['dhcp_lease'] : '';
      $dhcp_limit = isset($network['dhcp_limit']) ? $network['dhcp_limit'] : 0;
      $captive_portal_roaming = isset($network['captive_portal_roaming']) ? $network['captive_portal_roaming'] : 0;
      $content_filter = isset($network['content_filter']) ? $network['content_filter'] : '';
      $radius_host_1 = isset($network['radius_host_1']) ? $network['radius_host_1'] : '';
      $radius_host_2 = isset($network['radius_host_2']) ? $network['radius_host_2'] : '';
      $radius_secret_1 = isset($network['radius_secret_1']) ? $network['radius_secret_1'] : '';
      $radius_secret_2 = isset($network['radius_secret_2']) ? $network['radius_secret_2'] : '';
      $ssid = isset($network['ssid']) ? $network['ssid'] : '';
      $encryption_type = isset($network['encryption_type']) ? $network['encryption_type'] : '';
      $encryption_key = isset($network['encryption_key']) ? $network['encryption_key'] : '';
      $active = isset($network['active']) ? $network['active'] : 0;
      $ssid_hidden = isset($network['ssid_hidden']) ? $network['ssid_hidden'] : 0;
      $dhcp_enabled = isset($network['dhcp_enabled']) ? $network['dhcp_enabled'] : 0;
      $dhcp_relay_on = isset($network['dhcp_relay_on']) ? $network['dhcp_relay_on'] : 0;
      $config_type_bridge = isset($network['config_type_bridge']) ? $network['config_type_bridge'] : 0;
      $bridge_to_switch_ports = isset($network['bridge_to_switch_ports']) ? $network['bridge_to_switch_ports'] : 0;
      $make_part_of_lan = isset($network['make_part_of_lan']) ? $network['make_part_of_lan'] : 0;
      $captive_portal_enabled = isset($network['captive_portal_enabled']) ? $network['captive_portal_enabled'] : 0;
      $radius_8021x_host_1 = isset($network['radius_8021x_host_1']) ? $network['radius_8021x_host_1'] : '';
      $radius_8021x_secret_1 = isset($network['radius_8021x_secret_1']) ? $network['radius_8021x_secret_1'] : '';
      $radius_8021x_port_1 = isset($network['radius_8021x_port_1']) ? $network['radius_8021x_port_1'] : 1812;
      $radius_8021x_host_2 = isset($network['radius_8021x_host_2']) ? $network['radius_8021x_host_2'] : '';
      $radius_8021x_secret_2 = isset($network['radius_8021x_secret_2']) ? $network['radius_8021x_secret_2'] : '';
      $radius_8021x_port_2 = isset($network['radius_8021x_port_2']) ? $network['radius_8021x_port_2'] : 1812;
      $radius_8021x_interval = isset($network['radius_8021x_interval']) ? $network['radius_8021x_interval'] : 0;
      $access_type = isset($network['access_type']) ? $network['access_type'] : '';
      $captive_portal_ps = isset($network['captive_portal_ps']) ? $network['captive_portal_ps'] : 0;
      $use_ps_radius = isset($network['use_ps_radius']) ? $network['use_ps_radius'] : 0;
      $proxy_server_host = isset($network['proxy_server_host']) ? $network['proxy_server_host'] : '';
      $proxy_server_port = isset($network['proxy_server_port']) ? $network['proxy_server_port'] : '';
      $dns_1 = isset($network['dns_1']) ? $network['dns_1'] : '';
      $dns_2 = isset($network['dns_2']) ? $network['dns_2'] : '';
      $vlan_id = isset($network['vlan_id']) ? $network['vlan_id'] : null;
      $job_id = isset($network['job_id']) ? $network['job_id'] : 0;
      $roaming_allowed = isset($network['roaming_allowed']) ? $network['roaming_allowed'] : 0;
      $running_radtest = isset($network['running_radtest']) ? $network['running_radtest'] : 0;
      $isolate = isset($network['isolate']) ? $network['isolate'] : '';
      $isolate_traffic = isset($network['isolate_traffic']) ? $network['isolate_traffic'] : 0;
      $isolate_icmp = isset($network['isolate_icmp']) ? $network['isolate_icmp'] : 0;
      $band_selection = isset($network['band_selection']) ? $network['band_selection'] : '';


      $item = Network::create([
        'location_id' => $location_id,
        'network_name' => $network_name,
        'description' => $description,
        'dhcp_option_type' => $dhcp_option_type,
        'metric' => $metric,
        'network_radio_mode' => $network_radio_mode,
        'interface_ipaddr' => $interface_ipaddr,
        'interface_netmask' => $interface_netmask,
        'dhcp_start' => $dhcp_start,
        'dhcp_end' => $dhcp_end,
        'dhcp_lease' => $dhcp_lease,
        'dhcp_limit' => $dhcp_limit,
        'captive_portal_roaming' => $captive_portal_roaming,
        'content_filter' => $content_filter,
        'radius_host_1' => '37.58.63.3',
        'radius_host_2' => '37.58.63.3',
        'radius_secret_1' => 'usman123',
        'radius_secret_2' => 'usman123',
        'ssid' => $ssid,
        'encryption_type' => $encryption_type,
        'encryption_key' => $encryption_key,
        'active' => $active,
        'ssid_hidden' => $ssid_hidden,
        'dhcp_enabled' => $dhcp_enabled,
        'dhcp_relay_on' => $dhcp_relay_on,
        'config_type_bridge' => $config_type_bridge,
        'bridge_to_switch_ports' => $bridge_to_switch_ports,
        'make_part_of_lan' => $make_part_of_lan,
        'captive_portal_enabled' => $captive_portal_enabled,
        'radius_8021x_host_1' => $radius_8021x_host_1,
        'radius_8021x_secret_1' => $radius_8021x_secret_1,
        'radius_8021x_port_1' => $radius_8021x_port_1,
        'radius_8021x_host_2' => $radius_8021x_host_2,
        'radius_8021x_secret_2' => $radius_8021x_secret_2,
        'radius_8021x_port_2' => $radius_8021x_port_2,
        'radius_8021x_interval' => $radius_8021x_interval,
        'access_type' => $access_type,
        'captive_portal_ps' => $captive_portal_ps,
        'use_ps_radius' => $use_ps_radius,
        'proxy_server_host' => $proxy_server_host,
        'proxy_server_port' => $proxy_server_port,
        'dns_1' => $dns_1,
        'dns_2' => $dns_2,
        'vlan_id' => $vlan_id,
        'job_id' => $job_id,
        'roaming_allowed' => $roaming_allowed,
        'running_radtest' => $running_radtest,
        'isolate' => $isolate,
        'isolate_traffic' => $isolate_traffic,
        'isolate_icmp' => $isolate_icmp,
        'band_selection' => $band_selection,
        'allowed_urls' => '37.58.63.3,10.1.0.1,204.15.20.0/22,69.63.176.0/20,66.220.144.0/20,66.220.144.0/21,69.63.184.0/21,69.63.176.0/21,74.119.76.0/22,69.171.255.0/24,173.252.64.0/18,69.171.224.0/19,69.171.224.0/20,103.4.96.0/22,69.63.176.0/24,173.252.64.0/19,173.252.70.0/24,31.13.64.0/18,31.13.24.0/21,66.220.152.0/21,66.220.159.0/24,69.171.239.0/24,69.171.240.0/20,31.13.64.0/19,31.13.64.0/24,31.13.65.0/24,31.13.67.0/24,31.13.68.0/24,31.13.69.0/24,31.13.70.0/24,31.13.71.0/24,31.13.72.0/24,31.13.73.0/24,31.13.74.0/24,31.13.75.0/24,31.13.76.0/24,31.13.77.0/24,31.13.96.0/19,31.13.66.0/24,173.252.96.0/19,69.63.178.0/24,31.13.78.0/24,31.13.79.0/24,31.13.80.0/24,31.13.82.0/24,31.13.83.0/24,31.13.84.0/24,31.13.85.0/24,31.13.86.0/24,31.13.87.0/24,31.13.88.0/24,31.13.89.0/24,31.13.90.0/24,31.13.91.0/24,31.13.92.0/24,31.13.93.0/24,31.13.94.0/24,31.13.95.0/24,69.171.253.0/24,69.63.186.0/24,31.13.81.0/24,179.60.192.0/22,179.60.192.0/24,179.60.193.0/24,179.60.194.0/24,179.60.195.0/24,185.60.216.0/22,45.64.40.0/22,185.60.216.0/24,185.60.217.0/24,185.60.218.0/24,185.60.219.0/24,129.134.0.0/16,157.240.0.0/16,204.15.20.0/22,69.63.176.0/20,69.63.176.0/21,69.63.184.0/21,66.220.144.0/20,69.63.176.0/20,199.96.56.0/24,199.96.57.0/24,199.16.156.0/22,199.59.148.0/22,192.133.76.0/22,192.133.76.0/23,199.96.59.0/24,199.96.58.0/24,199.96.63.0/24,199.96.56.0/21,103.252.112.0/22,103.252.114.0/23,185.45.4.0/23,199.96.62.0/23,199.96.58.0/23,185.45.6.0/23,192.44.68.0/23,192.48.236.0/23,69.12.56.0/21,104.244.40.0/21,104.244.42.0/24,103.252.112.0/23,104.244.43.0/24,85.45.5.0/24,185.45.4.0/24,199.16.156.0/22,199.96.57.0/24,199.96.63.0/24,192.133.76.0/22,8.25.194.0/23,199.96.61.0/24,192.133.78.0/23,199.96.59.0/24,8.25.196.0/23,199.96.60.0/24,199.96.56.0/24,199.96.58.0/24,199.59.148.0/22,199.96.56.0/21,192.133.76.0/23,8.25.195.0/24,8.25.194.0/24,8.25.197.0/24,8.25.196.0/24,103.252.114.0/23,103.252.112.0/23,103.252.112.0/22',
        'allowed_domains' => '.googleapis.com,.facebook.com,.facebook.net,.icloud.com,.akamai.net,.akadns.net,.akamaiedge.net,.amazonaws.com,.akamaihd.net,.fbcdn.net,.linkedin.com,.licdn.com,.twitter.com,.twimg.com,.abs.twitter.com',
      ]);

      $data = $item->toArray();
      $data['created_at'] = $item->created_at->timestamp;
      $data['updated_at'] = $item->updated_at->timestamp;
      $data['zone_ids'] = array();

      return $data;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($location_id, $id)
    {
      $item = Network::find($id);
      if ($item == null)
        return array('code' => 0, 'message' => 'Invalid Request');

      $data = $item->toArray();
      $data['created_at'] = $item->created_at->timestamp;
      $data['updated_at'] = $item->updated_at->timestamp;
      $data['captive_portal_url'] = '';
      $data['captive_portal_port'] = 0;
      $data['captive_portal_uamsecret'] = '';
      $data['captive_portal_allowed_hosts'] = '';
      $data['captive_portal_allowed_macs'] = '';
      $data['captive_portal_ssl_enabled'] = '';
      $data['ssid_disabled'] = '';
      $data['zones'] = array();
      $data['zone_ids'] = array();
      $data['splash_pages'] = array();

      return $data;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($location_id, Request $request, $id)
    {
      $item = Network::find($id);
      if ($item == null)
        return array('code' => 0, 'message' => 'Invalid Request');

      $network = $request->input('network');
      if (!isset($network)) {
        return array('code' => 0, 'message' => 'Invalid Request');
      }

      $network_name = isset($network['network_name']) ? $network['network_name'] : '';
      $description = isset($network['description']) ? $network['description'] : '';
      $dhcp_option_type = isset($network['dhcp_option_type']) ? $network['dhcp_option_type'] : 6;
      $metric = isset($network['metric']) ? $network['metric'] : 100;
      $network_radio_mode = isset($network['network_radio_mode']) ? $network['network_radio_mode'] : '';
      $interface_ipaddr = isset($network['interface_ipaddr']) ? $network['interface_ipaddr'] : '';
      $interface_netmask = isset($network['interface_netmask']) ? $network['interface_netmask'] : 0;
      $dhcp_start = isset($network['dhcp_start']) ? $network['dhcp_start'] : 0;
      $dhcp_end = isset($network['dhcp_end']) ? $network['dhcp_end'] : 0;
      $dhcp_lease = isset($network['dhcp_lease']) ? $network['dhcp_lease'] : '';
      $dhcp_limit = isset($network['dhcp_limit']) ? $network['dhcp_limit'] : 0;
      $captive_portal_roaming = isset($network['captive_portal_roaming']) ? $network['captive_portal_roaming'] : 0;
      $content_filter = isset($network['content_filter']) ? $network['content_filter'] : '';
      $radius_host_1 = isset($network['radius_host_1']) ? $network['radius_host_1'] : '';
      $radius_host_2 = isset($network['radius_host_2']) ? $network['radius_host_2'] : '';
      $radius_secret_1 = isset($network['radius_secret_1']) ? $network['radius_secret_1'] : '';
      $radius_secret_2 = isset($network['radius_secret_2']) ? $network['radius_secret_2'] : '';
      $ssid = isset($network['ssid']) ? $network['ssid'] : '';
      $encryption_type = isset($network['encryption_type']) ? $network['encryption_type'] : '';
      $encryption_key = isset($network['encryption_key']) ? $network['encryption_key'] : '';
      $active = isset($network['active']) ? $network['active'] : 0;
      $ssid_hidden = isset($network['ssid_hidden']) ? $network['ssid_hidden'] : 0;
      $dhcp_enabled = isset($network['dhcp_enabled']) ? $network['dhcp_enabled'] : 0;
      $dhcp_relay_on = isset($network['dhcp_relay_on']) ? $network['dhcp_relay_on'] : 0;
      $config_type_bridge = isset($network['config_type_bridge']) ? $network['config_type_bridge'] : 0;
      $bridge_to_switch_ports = isset($network['bridge_to_switch_ports']) ? $network['bridge_to_switch_ports'] : 0;
      $make_part_of_lan = isset($network['make_part_of_lan']) ? $network['make_part_of_lan'] : 0;
      $captive_portal_enabled = isset($network['captive_portal_enabled']) ? $network['captive_portal_enabled'] : 0;
      $radius_8021x_host_1 = isset($network['radius_8021x_host_1']) ? $network['radius_8021x_host_1'] : '';
      $radius_8021x_secret_1 = isset($network['radius_8021x_secret_1']) ? $network['radius_8021x_secret_1'] : '';
      $radius_8021x_port_1 = isset($network['radius_8021x_port_1']) ? $network['radius_8021x_port_1'] : 1812;
      $radius_8021x_host_2 = isset($network['radius_8021x_host_2']) ? $network['radius_8021x_host_2'] : '';
      $radius_8021x_secret_2 = isset($network['radius_8021x_secret_2']) ? $network['radius_8021x_secret_2'] : '';
      $radius_8021x_port_2 = isset($network['radius_8021x_port_2']) ? $network['radius_8021x_port_2'] : 1812;
      $radius_8021x_interval = isset($network['radius_8021x_interval']) ? $network['radius_8021x_interval'] : 0;
      $access_type = isset($network['access_type']) ? $network['access_type'] : '';
      $captive_portal_ps = isset($network['captive_portal_ps']) ? $network['captive_portal_ps'] : 0;
      $use_ps_radius = isset($network['use_ps_radius']) ? $network['use_ps_radius'] : 0;
      $proxy_server_host = isset($network['proxy_server_host']) ? $network['proxy_server_host'] : '';
      $proxy_server_port = isset($network['proxy_server_port']) ? $network['proxy_server_port'] : '';
      $dns_1 = isset($network['dns_1']) ? $network['dns_1'] : '';
      $dns_2 = isset($network['dns_2']) ? $network['dns_2'] : '';
      $vlan_id = isset($network['vlan_id']) ? $network['vlan_id'] : null;
      $job_id = isset($network['job_id']) ? $network['job_id'] : 0;
      $roaming_allowed = isset($network['roaming_allowed']) ? $network['roaming_allowed'] : 0;
      $running_radtest = isset($network['running_radtest']) ? $network['running_radtest'] : 0;
      $isolate = isset($network['isolate']) ? $network['isolate'] : '';
      $isolate_traffic = isset($network['isolate_traffic']) ? $network['isolate_traffic'] : 0;
      $isolate_icmp = isset($network['isolate_icmp']) ? $network['isolate_icmp'] : 0;
      $band_selection = isset($network['band_selection']) ? $network['band_selection'] : '';

      $item->network_name = $network_name;
      $item->description = $description;
      $item->dhcp_option_type = $dhcp_option_type;
      $item->metric = $metric;
      $item->network_radio_mode = $network_radio_mode;
      $item->interface_ipaddr = $interface_ipaddr;
      $item->interface_netmask = $interface_netmask;
      $item->dhcp_start = $dhcp_start;
      $item->dhcp_end = $dhcp_end;
      $item->dhcp_lease = $dhcp_lease;
      $item->dhcp_limit = $dhcp_limit;
      $item->captive_portal_roaming = $captive_portal_roaming;
      $item->content_filter = $content_filter;
      $item->radius_host_1 = $radius_host_1;
      $item->radius_host_2 = $radius_host_2;
      $item->radius_secret_1 = $radius_secret_1;
      $item->radius_secret_2 = $radius_secret_2;
      $item->ssid = $ssid;
      $item->encryption_type = $encryption_type;
      $item->encryption_key = $encryption_key;
      $item->active = $active;
      $item->ssid_hidden = $ssid_hidden;
      $item->dhcp_enabled = $dhcp_enabled;
      $item->dhcp_relay_on = $dhcp_relay_on;
      $item->config_type_bridge = $config_type_bridge;
      $item->bridge_to_switch_ports = $bridge_to_switch_ports;
      $item->make_part_of_lan = $make_part_of_lan;
      $item->captive_portal_enabled = $captive_portal_enabled;
      $item->radius_8021x_host_1 = $radius_8021x_host_1;
      $item->radius_8021x_secret_1 = $radius_8021x_secret_1;
      $item->radius_8021x_port_1 = $radius_8021x_port_1;
      $item->radius_8021x_host_2 = $radius_8021x_host_2;
      $item->radius_8021x_secret_2 = $radius_8021x_secret_2;
      $item->radius_8021x_port_2 = $radius_8021x_port_2;
      $item->radius_8021x_interval = $radius_8021x_interval;
      $item->access_type = $access_type;
      $item->captive_portal_ps = $captive_portal_ps;
      $item->use_ps_radius = $use_ps_radius;
      $item->proxy_server_host = $proxy_server_host;
      $item->proxy_server_port = $proxy_server_port;
      $item->dns_1 = $dns_1;
      $item->dns_2 = $dns_2;
      $item->vlan_id = $vlan_id;
      $item->job_id = $job_id;
      $item->roaming_allowed = $roaming_allowed;
      $item->running_radtest = $running_radtest;
      $item->isolate = $isolate;
      $item->isolate_traffic = $isolate_traffic;
      $item->isolate_icmp = $isolate_icmp;
      $item->band_selection = $band_selection;

      $item->save();

      $data = $item->toArray();
      $data['created_at'] = $item->created_at->timestamp;
      $data['updated_at'] = $item->updated_at->timestamp;
      $data['zone_ids'] = array();

      return $data;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($location_id, $id)
    {
      $item = Network::find($id);
      if ($item == null)
        return array('code' => 0, 'message' => 'Invalid Request');

      $item->delete();
      return array('code' => 0, 'message' => 'Success');
    }
}
