<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Box;
use App\BoxesUpdate;
use App\Radacct;
use App\Zone;
use App\APIConf;
use App\DeviceType;
use Validator;

class BoxesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index(Request $request)
     {
       $perPage = $request->input('per');
       $boxes = Box::paginate($perPage);

       $data = array();
       foreach ($boxes as $box) {
         $arry = $box->toArray();
         $arry['created_at'] = $box->created_at->timestamp;
         $arry['updated_at'] = $box->updated_at->timestamp;
         $arry['is_cucumber'] = $box->is_cucumber == 1;
         $arry['access_token'] = '';
         $arry['device_icon'] = '';
         $arry['last_heartbeat'] = 0;
         $arry['last_speedtest'] = '';
         $arry['machine_type'] = '';
         $arry['next_firmware'] = '';
         $arry['public_ip'] = '';
         $arry['serial_number'] = '';
         $arry['state'] = '';
         $arry['slug'] = $box->id;
         $arry['uptime'] = 0;
         $arry['uptime_seconds'] = 0;
         $arry['metadata'] = array();
         $arry['metadata']['online'] = 0;
         $arry['metadata']['ssids'] = '';
         $arry['metadata']['wired'] = '';
         $arry['metadata']['zone_name'] = '';

         $zone = Zone::find($box->zone_id);
         if ($zone) $arry['zone_name'] = $zone->zone_name;

         $data[] = $arry;
       }

       $links = array();
       $links['current_page'] = $boxes->currentPage();
       if ($boxes->hasMorePages())
         $links['next_page'] = $links['current_page'] + 1;
       $links['total_entries'] = $boxes->total();
       $links['total_pages'] = $boxes->lastPage();
       $links['size'] = $boxes->perPage();
       return array('boxes' => $data, '_links' => $links);
     }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $box = $request->input('box');
      $location_id = $request->input('location_id');
      if (!isset($box) || !isset($location_id)) {
        return array('code' => 0, 'message' => 'Invalid Request');
      }

      $address = isset($box['address']) ? $box['address'] : '';
      $alert_interval = isset($box['alert_interval']) ? $box['alert_interval'] : 0;
      $auto_upgrades = isset($box['auto_upgrades']) ? $box['auto_upgrades'] : 1;
      $band_steering = isset($box['band_steering']) ? $box['band_steering'] : 1;
      $basic_rate_enabled = isset($box['basic_rate_enabled']) ? $box['basic_rate_enabled'] : 1;
      $basic_rate_2 = isset($box['basic_rate_2']) ? $box['basic_rate_2'] : 0;
      $basic_rate_5 = isset($box['basic_rate_5']) ? $box['basic_rate_5'] : 0;
      $beacon_interval_2 = isset($box['beacon_interval_2']) ? $box['beacon_interval_2'] : 0;
      $beacon_interval_5 = isset($box['beacon_interval_5']) ? $box['beacon_interval_5'] : 0;
      $beacon_interval = isset($box['beacon_interval']) ? $box['beacon_interval'] : 0;
      $calledstationid = isset($box['calledstationid']) ? $box['calledstationid'] : '';
      $is_cucumber = isset($box['is_cucumber']) ? $box['is_cucumber'] : 1;
      $dual_band = isset($box['dual_band']) ? $box['dual_band'] : 1;
      $channel = isset($box['channel']) ? $box['channel'] : 'auto';
      $channel_5 = isset($box['channel_5']) ? $box['channel_5'] : 'auto';
      $debug = isset($box['debug']) ? $box['debug'] : 1;
      $description = isset($box['description']) ? $box['description'] : '';
      $development_mode = isset($box['development_mode']) ? $box['development_mode'] : '';
      $force_upgrades_nightly = isset($box['force_upgrades_nightly']) ? $box['force_upgrades_nightly'] : 1;
      $ht_mode_2 = isset($box['ht_mode_2']) ? $box['ht_mode_2'] : 'HT20';
      $ht_mode_5 = isset($box['ht_mode_5']) ? $box['ht_mode_5'] : 'VHT20';
      $is_monitored = isset($box['is_monitored']) ? $box['is_monitored'] : 1;
      $is_online = isset($box['is_online']) ? $box['is_online'] : 1;
      $latitude = isset($box['latitude']) ? $box['latitude'] : 0;
      $longitude = isset($box['longitude']) ? $box['longitude'] : 0;
      $no_scan = isset($box['no_scan']) ? $box['no_scan'] : 1;
      $pppoe_username = isset($box['pppoe_username']) ? $box['pppoe_username'] : '';
      $pppoe_password = isset($box['pppoe_password']) ? $box['pppoe_password'] : '';
      $radio_log_level = isset($box['radio_log_level']) ? $box['radio_log_level'] : 0;
      $radiusnasid = isset($box['radiusnasid']) ? $box['radiusnasid'] : Str::random('30');
      $radius_secret = isset($box['radius_secret']) ? $box['radius_secret'] : '';
      $rsyslog_host = isset($box['rsyslog_host']) ? $box['rsyslog_host'] : '';
      $rsyslog_port = isset($box['rsyslog_port']) ? $box['rsyslog_port'] : '';
      $rsyslog_protocol = isset($box['rsyslog_protocol']) ? $box['rsyslog_protocol'] : '';
      $timezone = isset($box['timezone']) ? $box['timezone'] : '';
      $tx_power_2 = isset($box['tx_power_2']) ? $box['tx_power_2'] : 0;
      $tx_power_5 = isset($box['tx_power_5']) ? $box['tx_power_5'] : 0;
      $wan_mtu = isset($box['wan_mtu']) ? $box['wan_mtu'] : 1492;
      $wan_metric_1 = isset($box['wan_metric_1']) ? $box['wan_metric_1'] : 40;
      $wan_dns_1 = isset($box['wan_dns_1']) ? $box['wan_dns_1'] : '';
      $wan_dns_2 = isset($box['wan_dns_2']) ? $box['wan_dns_2'] : '';
      $wan_ip = isset($box['wan_ip']) ? $box['wan_ip'] : '';
      $wan_ipaddr = isset($box['wan_ipaddr']) ? $box['wan_ipaddr'] : '';
      $wan_proto = isset($box['wan_proto']) ? $box['wan_proto'] : 'dhcp';
      $wan_netmask = isset($box['wan_netmask']) ? $box['wan_netmask'] : '';
      $wan_gateway = isset($box['wan_gateway']) ? $box['wan_gateway'] : '';
      $zone_id = isset($box['zone_id']) ? $box['zone_id'] : 0;
      $device_type_id = isset($box['device_type_id']) ? $box['device_type_id'] : 0;

      $remote_port = DB::table('boxes')->max('remote_port') + 1;
      if ($remote_port < 5001)
        $remote_port = 5001;

      $item = Box::create([
        'location_id' => $location_id,
        'device_type_id' => $device_type_id,
        'address' => $address,
        'alert_interval' => $alert_interval,
        'auto_upgrades' => $auto_upgrades,
        'band_steering' => $band_steering,
        'basic_rate_enabled' => $basic_rate_enabled,
        'basic_rate_2' => $basic_rate_2,
        'basic_rate_5' => $basic_rate_5,
        'beacon_interval_2' => $beacon_interval_2,
        'beacon_interval_5' => $beacon_interval_5,
        'beacon_interval' => $beacon_interval,
        'calledstationid' => $calledstationid,
        'is_cucumber' => $is_cucumber,
        'dual_band' => $dual_band,
        'channel' => $channel,
        'channel_5' => $channel_5,
        'debug' => $debug,
        'description' => $description,
        'development_mode' => $development_mode,
        'force_upgrades_nightly' => $force_upgrades_nightly,
        'ht_mode_2' => $ht_mode_2,
        'ht_mode_5' => $ht_mode_5,
        'is_monitored' => $is_monitored,
        'is_online' => $is_online,
        'latitude' => $latitude,
        'longitude' => $longitude,
        'no_scan' => $no_scan,
        'pppoe_username' => $pppoe_username,
        'pppoe_password' => $pppoe_password,
        'radio_log_level' => $radio_log_level,
        'radiusnasid' => $radiusnasid,
        'radius_secret' => 'usman123',
        'rsyslog_host' => $rsyslog_host,
        'rsyslog_port' => $rsyslog_port,
        'rsyslog_protocol' => $rsyslog_protocol,
        'timezone' => $timezone,
        'tx_power_2' => $tx_power_2,
        'tx_power_5' => $tx_power_5,
        'wan_mtu' => $wan_mtu,
        'wan_metric_1' => $wan_metric_1,
        'wan_dns_1' => $wan_dns_1,
        'wan_dns_2' => $wan_dns_2,
        'wan_ip' => $wan_ip,
        'wan_ipaddr' => $wan_ipaddr,
        'wan_proto' => $wan_proto,
        'wan_netmask' => $wan_netmask,
        'wan_gateway' => $wan_gateway,
        'zone_id' => $zone_id,
        'server_url' => '37.58.63.3',
        'server_username' => 'root',
        'server_ssh_port' => 22,
        'server_connect_port' => 22,
        'remote_port' => $remote_port,
        'update_status' => 1,
      ]);

      $data = $item->toArray();
      $data['created_at'] = $item->created_at->timestamp;
      $data['updated_at'] = $item->updated_at->timestamp;
      $data['is_cucumber'] = $item->is_cucumber == 1;
      $data['access_token'] = '';
      $data['device_icon'] = '';
      $data['last_heartbeat'] = 0;
      $data['last_speedtest'] = '';
      $data['machine_type'] = '';
      $data['next_firmware'] = '';
      $data['public_ip'] = '';
      $data['serial_number'] = '';
      $data['state'] = '';
      $data['slug'] = $item->id;
      $data['uptime'] = 0;
      $data['uptime_seconds'] = 0;
      $data['metadata'] = array();
      $data['metadata']['online'] = 0;
      $data['metadata']['ssids'] = '';
      $data['metadata']['wired'] = '';
      $data['metadata']['zone_name'] = '';

      $zone = Zone::find($item->zone_id);
      if ($zone) $data['zone_name'] = $zone->zone_name;

      return $data;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
      $item = BoxesUpdate::leftJoin('boxes', 'boxes_update.ap_mac', '=', 'boxes.calledstationid')
          ->where('boxes.id', $id)
          ->orderBy('boxes_update.timestamp', 'desc')
          ->select('boxes.*', 'boxes_update.timestamp as timestamp', 
                'boxes_update.machine_type', 
                'boxes_update.uam_listen',
                'boxes_update.live_ip_address',
                'boxes_update.ap_mac',
                'boxes_update.uptime',
                'boxes_update.total_octets',
                'boxes_update.output_octets',
                'boxes_update.total_ram',
                'boxes_update.free_ram'
          )
          ->limit(1)
          ->first()
      ;
      if ($item == null)
        return array('code' => 0, 'message' => 'Invalid Request');
      $data = $item->toArray();
      
      // Time interval
      $period = $request->get('period');
      switch(strtolower(substr($period, -1, 1))){
          case "m":
              $time_interval = intval($period) * 60;
          break;
          case "h":
              $time_interval = intval($period) * 3600;
          break;
          case "d":
              $time_interval = intval($period) * 3600 * 24;
          break;
          default:
              $time_interval = 6 * 3600 * 24;
          break;
      }
      
      // Get load average
      $free_rams = BoxesUpdate::where('timestamp', '>', date('Y-m-d H:i:s', time() - $time_interval))
            ->select('timestamp', 'free_ram', 'total_ram')
            ->orderBy('timestamp', 'asc')
            ->get()
      ;
      $load_average_values = array();
      foreach($free_rams as $free_ram){
          $load_average_values[] = array(
                'value' => (intval($free_ram->total_ram) - intval($free_ram->free_ram)) / (intval($free_ram->total_ram)),
                'time' => strtotime($free_ram->timestamp),
          );
      }
      
      // Get Wifi Usage
      $wifi_usage_values = Radacct::where('acctstarttime', '>', date('Y-m-d H:i:s', time() - $time_interval))
            ->where('calledstationid', $item->ap_mac)
            ->select(DB::raw('ifnull(sum(acctinputoctets), 0) as inbound, ifnull(sum(acctoutputoctets), 0) as outbound, (sum(acctinputoctets) + sum(acctoutputoctets)) as total'))
            ->first()
      ;
      // Get Network Location
      $locations = file_get_contents("http://api.ipinfodb.com/v3/ip-city/?key=103c0b4b51b82a55666609e0183b895cdad99fc75559faf388d159ca74a300d5&ip={$item->live_ip_address}&format=json");
      $locations = json_decode($locations, true);
      if(is_array($locations)){
          $city_region_name = ($locations['cityName']==$locations['regionName']) ? $locations['cityName'] : ($locations['cityName']. ", " . $locations['regionName']);
          $network_location = $city_region_name.", ".$locations['countryName'];
      }else{
          $network_location = "";
      }
      
      $extra_data = array(
          'status' => (time() - strtotime($item->timestamp) > 15*60) ? "Offline" : "Online",
          // 'last_seen' => Date('H:i:s', strtotime(Date('Y-m-d')) + (time() - strtotime($item->timestamp))) . " ago" 
          'last_seen' => Date('Y-m-d H:i:s', strtotime($item->timestamp)),
          'timestamp' =>  $item->timestamp,
          'tags' => $item->machine_type,
          'wan_ip' => $item->uam_listen,
          'public_ip' => $item->live_ip_address,
          'wifi_usage_values' => $wifi_usage_values, // for Wifi usage
          'load_average_values' => $load_average_values, // for Load average
          'last_heartbeat' => $item->timestamp,
          'network_location' => $network_location,
          'created_at' => strtotime($item->created_at),
          'updated_at' => strtotime($item->updated_at),
      );
      
      $data = array_merge($data, $extra_data);
      // $data['created_at'] = $item->timestamp;
      // $data['updated_at'] = $item->timestamp;
      // $data['is_cucumber'] = $item->is_cucumber == 1;
      // $data['access_token'] = '';
      // $data['device_icon'] = '';
      // $data['last_heartbeat'] = 0;
      // $data['last_speedtest'] = '';
      // $data['machine_type'] = '';
      // $data['next_firmware'] = '';
      // $data['public_ip'] = '';
      // $data['serial_number'] = '';
      // $data['state'] = '';
      // $data['slug'] = $item->id;
      // $data['uptime'] = 0;
      // $data['uptime_seconds'] = 0;
      // $data['is_ac'] = true;
      // $data['metadata'] = array();
      // $data['metadata']['online'] = 0;
      // $data['metadata']['ssids'] = '';
      // $data['metadata']['wired'] = '';
      // $data['metadata']['zone_name'] = '';

      $zone = Zone::find($item->zone_id);
      if ($zone) $data['zone_name'] = $zone->zone_name;

      $device_types = DeviceType::all();
      $data['device_types'] = array();
      foreach ($device_types as $device_type) {
        $data['device_types'][] = $device_type->toArray();
      }

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
    public function update(Request $request, $id)
    {
      $item = Box::find($id);
      if ($item == null)
        return array('code' => 0, 'message' => 'Invalid Request');

      $box = $request->input('box');
      if (!isset($box)) {
        return array('code' => 0, 'message' => 'Invalid Request');
      }

      $address = isset($box['address']) ? $box['address'] : '';
      $alert_interval = isset($box['alert_interval']) ? $box['alert_interval'] : 0;
      $auto_upgrades = isset($box['auto_upgrades']) ? $box['auto_upgrades'] : 1;
      $band_steering = isset($box['band_steering']) ? $box['band_steering'] : 1;
      $basic_rate_enabled = isset($box['basic_rate_enabled']) ? $box['basic_rate_enabled'] : 1;
      $basic_rate_2 = isset($box['basic_rate_2']) ? $box['basic_rate_2'] : 0;
      $basic_rate_5 = isset($box['basic_rate_5']) ? $box['basic_rate_5'] : 0;
      $beacon_interval_2 = isset($box['beacon_interval_2']) ? $box['beacon_interval_2'] : 0;
      $beacon_interval_5 = isset($box['beacon_interval_5']) ? $box['beacon_interval_5'] : 0;
      $beacon_interval = isset($box['beacon_interval']) ? $box['beacon_interval'] : 0;
      $calledstationid = isset($box['calledstationid']) ? $box['calledstationid'] : '';
      $is_cucumber = isset($box['is_cucumber']) ? $box['is_cucumber'] : 1;
      $dual_band = isset($box['dual_band']) ? $box['dual_band'] : 1;
      $channel = isset($box['channel']) ? $box['channel'] : 'auto';
      $channel_5 = isset($box['channel_5']) ? $box['channel_5'] : 'auto';
      $debug = isset($box['debug']) ? $box['debug'] : 1;
      $description = isset($box['description']) ? $box['description'] : '';
      $development_mode = isset($box['development_mode']) ? $box['development_mode'] : '';
      $force_upgrades_nightly = isset($box['force_upgrades_nightly']) ? $box['force_upgrades_nightly'] : 1;
      $ht_mode_2 = isset($box['ht_mode_2']) ? $box['ht_mode_2'] : 'HT20';
      $ht_mode_5 = isset($box['ht_mode_5']) ? $box['ht_mode_5'] : 'VHT20';
      $is_monitored = isset($box['is_monitored']) ? $box['is_monitored'] : 1;
      $is_online = isset($box['is_online']) ? $box['is_online'] : 1;
      $latitude = isset($box['latitude']) ? $box['latitude'] : 0;
      $longitude = isset($box['longitude']) ? $box['longitude'] : 0;
      $no_scan = isset($box['no_scan']) ? $box['no_scan'] : 1;
      $pppoe_username = isset($box['pppoe_username']) ? $box['pppoe_username'] : '';
      $pppoe_password = isset($box['pppoe_password']) ? $box['pppoe_password'] : '';
      $radio_log_level = isset($box['radio_log_level']) ? $box['radio_log_level'] : 0;
      $radiusnasid = isset($box['radiusnasid']) ? $box['radiusnasid'] : '';
      $radius_secret = isset($box['radius_secret']) ? $box['radius_secret'] : '';
      $rsyslog_host = isset($box['rsyslog_host']) ? $box['rsyslog_host'] : '';
      $rsyslog_port = isset($box['rsyslog_port']) ? $box['rsyslog_port'] : '';
      $rsyslog_protocol = isset($box['rsyslog_protocol']) ? $box['rsyslog_protocol'] : '';
      $timezone = isset($box['timezone']) ? $box['timezone'] : '';
      $tx_power_2 = isset($box['tx_power_2']) ? $box['tx_power_2'] : 0;
      $tx_power_5 = isset($box['tx_power_5']) ? $box['tx_power_5'] : 0;
      $wan_mtu = isset($box['wan_mtu']) ? $box['wan_mtu'] : 1492;
      $wan_metric_1 = isset($box['wan_metric_1']) ? $box['wan_metric_1'] : 40;
      $wan_dns_1 = isset($box['wan_dns_1']) ? $box['wan_dns_1'] : '';
      $wan_dns_2 = isset($box['wan_dns_2']) ? $box['wan_dns_2'] : '';
      $wan_ip = isset($box['wan_ip']) ? $box['wan_ip'] : '';
      $wan_ipaddr = isset($box['wan_ipaddr']) ? $box['wan_ipaddr'] : '';
      $wan_proto = isset($box['wan_proto']) ? $box['wan_proto'] : 'dhcp';
      $wan_netmask = isset($box['wan_netmask']) ? $box['wan_netmask'] : '';
      $wan_gateway = isset($box['wan_gateway']) ? $box['wan_gateway'] : '';
      $zone_id = isset($box['zone_id']) ? $box['zone_id'] : 0;
      $device_type_id = isset($box['device_type_id']) ? $box['device_type_id'] : 0;

      $item->device_type_id = $device_type_id;
      $item->address = $address;
      $item->alert_interval = $alert_interval;
      $item->auto_upgrades = $auto_upgrades;
      $item->band_steering = $band_steering;
      $item->basic_rate_enabled = $basic_rate_enabled;
      $item->basic_rate_2 = $basic_rate_2;
      $item->basic_rate_5 = $basic_rate_5;
      $item->beacon_interval_2 = $beacon_interval_2;
      $item->beacon_interval_5 = $beacon_interval_5;
      $item->beacon_interval = $beacon_interval;
      $item->calledstationid = $calledstationid;
      $item->is_cucumber = $is_cucumber;
      $item->dual_band = $dual_band;
      $item->channel = $channel;
      $item->channel_5 = $channel_5;
      $item->debug = $debug;
      $item->description = $description;
      $item->development_mode = $development_mode;
      $item->force_upgrades_nightly = $force_upgrades_nightly;
      $item->ht_mode_2 = $ht_mode_2;
      $item->ht_mode_5 = $ht_mode_5;
      $item->is_monitored = $is_monitored;
      $item->is_online = $is_online;
      $item->latitude = $latitude;
      $item->longitude = $longitude;
      $item->no_scan = $no_scan;
      $item->pppoe_username = $pppoe_username;
      $item->pppoe_password = $pppoe_password;
      $item->radio_log_level = $radio_log_level;
      $item->radiusnasid = $radiusnasid;
      $item->radius_secret = $radius_secret;
      $item->rsyslog_host = $rsyslog_host;
      $item->rsyslog_port = $rsyslog_port;
      $item->rsyslog_protocol = $rsyslog_protocol;
      $item->timezone = $timezone;
      $item->tx_power_2 = $tx_power_2;
      $item->tx_power_5 = $tx_power_5;
      $item->wan_mtu = $wan_mtu;
      $item->wan_metric_1 = $wan_metric_1;
      $item->wan_dns_1 = $wan_dns_1;
      $item->wan_dns_2 = $wan_dns_2;
      $item->wan_ip = $wan_ip;
      $item->wan_ipaddr = $wan_ipaddr;
      $item->wan_proto = $wan_proto;
      $item->wan_netmask = $wan_netmask;
      $item->wan_gateway = $wan_gateway;
      $item->zone_id = $zone_id;
      $item->server_url = '37.58.63.3';
      $item->server_username = 'root';
      $item->server_ssh_port = 22;
      $item->server_connect_port = 22;

      $remote_port = DB::table('boxes')->max('remote_port') + 1;
      if ($remote_port < 5001)
        $remote_port = 5001;
      $item->remote_port = $remote_port;
      $item->update_status = 1;

      $item->save();

      $data = $item->toArray();
      $data['created_at'] = $item->created_at->timestamp;
      $data['updated_at'] = $item->updated_at->timestamp;
      $data['is_cucumber'] = $item->is_cucumber == 1;
      $data['access_token'] = '';
      $data['device_icon'] = '';
      $data['last_heartbeat'] = 0;
      $data['last_speedtest'] = '';
      $data['machine_type'] = '';
      $data['next_firmware'] = '';
      $data['public_ip'] = '';
      $data['serial_number'] = '';
      $data['state'] = '';
      $data['slug'] = $item->id;
      $data['uptime'] = 0;
      $data['uptime_seconds'] = 0;
      $data['metadata'] = array();
      $data['metadata']['online'] = 0;
      $data['metadata']['ssids'] = '';
      $data['metadata']['wired'] = '';
      $data['metadata']['zone_name'] = '';

      $zone = Zone::find($item->zone_id);
      if ($zone) $data['zone_name'] = $zone->zone_name;

      return $data;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
      $item = Box::find($id);
      if ($item == null)
        return array('code' => 0, 'message' => 'Invalid Request');

      $after_action = $request->input('after_action');

      $item->delete();
      return array('code' => 0, 'message' => 'Success');
    }

    public function getUpdates(Request $request)
    {
      $apiKey = $request->input('apiKey');
      if ($apiKey == null || $apiKey != APIConf::API_KEY()) {
        return 'Invalid Request';
      }

      $mac = $request->input('mac');
      if (!isset($mac)) {
        return array('code' => 0, 'message' => 'Invalid Request');
      }

      DB::setFetchMode(\PDO::FETCH_ASSOC);
      $item = DB::table('boxes')
        ->join('zones', 'zones.id', '=', 'boxes.zone_id')
        ->join('networks', 'networks.zone_id', '=', 'boxes.zone_id')
        ->join('splash_associations', 'splash_associations.network_id', 'networks.id')
        ->join('device_types', 'device_types.id', 'boxes.device_type_id')
        ->where('boxes.calledstationid', $mac)
        ->where('boxes.update_status','!=', '0')
        ->select( 'boxes.id',
                  'boxes.dual_band',
                  'boxes.channel',
                  'boxes.channel_5',
                  'boxes.radius_secret',
                  'boxes.radiusnasid',
                  'boxes.ap_name',
                  'boxes.ssh_password',
                  'boxes.tx_power_2',
                  'boxes.tx_power_5',
                  'boxes.wan_dns_1',
                  'boxes.wan_dns_2',
                  'boxes.wan_ipaddr',
                  'boxes.wan_netmask',
                  'boxes.wan_gateway',
                  'boxes.server_url',
                  'boxes.server_username',
                  'boxes.server_ssh_port',
                  'boxes.server_connect_port',
                  'boxes.remote_port',
                  'boxes.update_status',
                  'boxes.firmware_update',
                  'boxes.firmware_path',
                  'networks.radius_host_1',
                  'networks.radius_host_2',
                  'networks.radius_secret_1',
                  'networks.radius_secret_2',
                  'networks.ssid',
                  'networks.allowed_urls',
                  'networks.allowed_domains',
                  'networks.access_type',
                  'networks.encryption_type',
                  'networks.encryption_key',
                  'networks.active',
                  'networks.ssid_hidden',
                  'networks.captive_portal_enabled',
                  'networks.access_type',
                  'networks.dns_1',
                  'networks.dns_2',
                  'networks.isolate',
                  'networks.isolate_traffic',
                  'networks.band_selection',
                  'splash_associations.splash_page_id',
                  'splash_associations.uam_secret',
                  'device_types.name',
                  'device_types.hs_lanif',
                  'device_types.hs_wanif')
        ->first();

    if (!isset($item)) {
      print("required=no" . "\n");
        return "Not Required";
      //return array('code' => 0, 'message' => 'Invalid Request');
    }
    else {
      $data = $item;
      $ap_name = !empty($data['ap_name']) ? $data['ap_name'] : 'Digifi';
      $ssh_password = !empty($data['ssh_password']) ? $data['ssh_password'] : 'test@8712';
      $wan_network = !empty($data['wan_ipaddr']) ? $data['wan_ipaddr'] : '10.1.0.0';
      $wan_netmask = !empty($data['wan_netmask']) ? $data['wan_netmask'] : '255.255.255.0';
      $uam_listen = !empty($data['wan_gateway']) ? $data['wan_gateway'] : '10.1.0.1';
      $dns_1 = !empty($data['wan_dns_1']) ? $data['wan_dns_1'] : $data['dns_1'];
      $dns_2 = !empty($data['wan_dns_2']) ? $data['wan_dns_2'] : $data['dns_2'];
      $dns_1 = !empty($dns_1) ? $dns_1 : '8.8.8.8';
      $dns_2 = !empty($dns_2) ? $dns_2 : '8.8.4.4';
      $allowed_urls = !empty($data['allowed_urls']) ? $data['allowed_urls'] : '';
      $allowed_domains = !empty($data['allowed_domains']) ? $data['allowed_domains'] : '';
      $access_type = !empty($data['access_type']) ? $data['access_type'] : 'open';
      $encryption_type = !empty($data['encryption_type']) ? $data['encryption_type'] : 'psk2';
      $encryption_key =  !empty($data['encryption_key']) ? $data['encryption_key'] : '';
      $ssid_hidden = !empty($data['ssid_hidden']) ? $data['ssid_hidden'] : '0';
      $isolate_traffic = !empty($data['isolate_traffic']) ? $data['isolate_traffic'] : '1';
      $band_selection = !empty($data['band_selection']) ? $data['band_selection'] : 'dual';
      $channel_2 = !empty($data['channel']) ? $data['channel'] : '5';
      $channel_5 = !empty($data['channel_5']) ? $data['channel_5'] : '5';
      $power_2 = !empty($data['tx_power_2']) ? $data['tx_power_2'] : '5';
      $power_5 = !empty($data['tx_power_5']) ? $data['tx_power_5'] : '5';
      $uam_secret = !empty($data['uam_secret']) ? $data['uam_secret'] : 'usman123';
      $splash_page_id = !empty($data['splash_page_id']) ? $data['splash_page_id'] : '';
      $splash_page_url = 'https://app.digifi.social/' . $splash_page_id;
      $device_type = !empty($data['name']) ? $data['name'] : '';
      $hs_lanif = !empty($data['hs_lanif']) ? $data['hs_lanif'] : '';
      $hs_wanif = !empty($data['hs_wanif']) ? $data['hs_wanif'] : '';
      $firmware_update = !empty($data['firmware_update']) ? $data['firmware_update'] : '0';
      $firmware_path = !empty($data['firmware_path']) ? $data['firmware_path'] : '0';
      
      //. '?cmd=login&mac=' . $mac . '&apname=' . $ap_name;
      if($access_type == "open" || $access_type == "radius"){
        $encryption_type = "open";
      }
      if ($item['update_status'] == 1) {
        print("required=yes" . "\n");
      }
      else if ($item['update_status'] == 2) {
        print("required=reboot" . "\n");
      }
      print("DEVICE_TYPE=" . $device_type . "\n");
      print("HS_LANIF=" . $hs_lanif . "\n");
      print("HS_WANIF=" . $hs_wanif . "\n");
      print("HS_NETWORK=" . $wan_network . "\n");
      print("HS_NETMASK=" . $wan_netmask . "\n");
      print("HS_UAMLISTEN=" . $uam_listen . "\n");
      print("HS_DNS1=" . $dns_1 . "\n");
      print("HS_DNS2=" . $dns_2 . "\n");
      print("HS_NASID=" . $data['radiusnasid'] . "\n");
      print("HS_RADIUS1=" . $data['radius_host_1'] . "\n");
      print("HS_RADIUS2=" . $data['radius_host_2'] . "\n");
      print("HS_RADSECRET=" . $data['radius_secret'] . "\n");
      print("HS_UAMSECRET=" . $uam_secret . "\n");
      print("HS_UAMALIASNAME=" . $data['radiusnasid'] . "\n");
      print("HS_UAMFORMAT$" . $splash_page_url . "\n");
      print("HS_UAMHOMEPAGE=" . "" . "\n");
      print("HS_UAMDOMAINS=" . $allowed_domains . "\n");
      print("HS_UAMALLOW=" . $allowed_urls . "," . $dns_1 . "," . $dns_2 . "\n");
      print("AP_NAME=" . $ap_name . "\n");
      print("AP_SSID=" . $data['ssid'] . "\n");
      print("AP_SSH_PASSWORD=" . $ssh_password . "\n");
      print("UPDATE_REQUIRED=" . $firmware_update . "\n");
      print("FILE_NAME=" . $firmware_path . "\n");
      print("SSH_SERVER=" . $data['server_url'] . "\n");
      print("USERNAME=" . $data['server_username'] . "\n");
      print("SERVER_SSH_PORT=" . $data['server_ssh_port'] . "\n");
      print("SERVER_CONNECT_PORT=" . $data['server_connect_port'] . "\n");
      print("REMOTE_PORT=" . $data['remote_port'] . "\n");
      print("ENCRYPTION_TYPE=" . $encryption_type . "\n");
      print("ENCRYPTION_KEY=" . $encryption_key . "\n");
      print("SSID_HIDDEN=" . $ssid_hidden . "\n");
      print("ISOLATE_TRAFFIC=" . $isolate_traffic . "\n");
      print("BAND_SELECTION=" . $band_selection . "\n");
      print("CHANNEL_2=" . $channel_2 . "\n");
      print("CHANNEL_5=" . $channel_5 . "\n");
      print("POWER_2=" . $power_2 . "\n");
      print("POWER_5=" . $power_5 . "\n");

      $item = Box::find($data['id']);
      $item->firmware_update = 0;
      $item->update_status = 0;
      $item->save();
      
      return "Success";
    }
  }

}
