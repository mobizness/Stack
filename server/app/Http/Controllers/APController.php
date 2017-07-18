<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

use App\Client;
use App\APIConf;
use App\Box;
use App\BoxesUpdate;
use App\ClientsUpdate;
use App\SurveyData;

class APController extends Controller
{
    public function uploadAPData(Request $request) {
                                             
		$apiKey = $request->get('apiKey');
        $mac = $request->get('mac');
		$request_id = $request->get('rid');
		
		$json_str = stripslashes($request->get("data"));
		Log::error('request_all='.$request->fullUrl());
//        $json_str = substr_replace($json_str, '', strlen($json_str) - 3, 1);
		$json_str = str_replace(',]', ']', $json_str);
		$json_str = str_replace(',}', '}', $json_str);
		$data = json_decode($json_str, true);
        Log::error('json_str='.$json_str);
        
		
		Log::error('json_code='.json_last_error());

        Log::error('-------------------------');
        Log::error('json_str='.$mac);
        Log::error('json_str='.$apiKey);
        Log::error('json_str='.json_encode($data));

		if ( empty($mac) || empty($apiKey) || empty($data)) {
			return response()->json([
				'code' => '200',
				'msg' => 'invalid parameters',
				'success' => 'false'
			]);
		}

		$box = $data['device'];
		$volume = isset($box['volume']) ? $box['volume'] : "";
		$input_output_total_octets = explode(",", $volume);
		$input_octets = isset($input_output_total_octets[0]) ? $input_output_total_octets[0] : "";
		$output_octets = isset($input_output_total_octets[1]) ? $input_output_total_octets[1] : "";
		$total_octets = isset($input_output_total_octets[2]) ? $input_output_total_octets[2] : "";
		$live_ip_address = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
		
		$box_data = array(
			'request_id' => $request_id, 
			'ap_mac' => isset($box['ap_mac']) ? $box['ap_mac'] : "", 
            'wan_name' => isset($box['wan_name']) ? $box['wan_name'] : "",
            'wan_gateway' => isset($box['wan_gateway']) ? $box['wan_gateway'] : "",
            'system_type' => isset($box['system_type']) ? $box['system_type'] : "",
            'machine_type' => isset($box['machine_type']) ? $box['machine_type'] : "",
            'user_count' => isset($box['user_count']) ? $box['user_count'] : "",
            'ssid' => isset($box['ssid']) ? $box['ssid'] : "",
            'uptime' => isset($box['uptime']) ? $box['uptime'] : "",
            'total_ram' => isset($box['total_ram']) ? $box['total_ram'] : "",
            'free_ram' => isset($box['free_ram']) ? $box['free_ram'] : "",
            'available_ram' => isset($box['available_ram']) ? $box['available_ram'] : "",
            'nas_id' => isset($box['nas_id']) ? $box['nas_id'] : "",
            'procs' => isset($box['procs']) ? $box['procs'] : "",
            'hwmode' => isset($box['hwmode']) ? $box['hwmode'] : "",
            'htmode' => isset($box['htmode']) ? $box['htmode'] : "",
            'channel' => isset($box['channel']) ? $box['channel'] : "",
            'txpower' => isset($box['txpower']) ? $box['txpower'] : "",
            'input_octets' => $input_octets,
            'output_octets' => $output_octets,
            'total_octets' => $total_octets,
			'live_ip_address' => $live_ip_address
		);

		$newBoxes = new BoxesUpdate($box_data);
        $newBoxes->save();
// print_r($data);exit;
		$stations = $data['stations'];
		$sessions = $data['sessions'];
		
		$clients = array();
		foreach($stations as $station){
            array_walk($station, function($value, $key) use(&$station){
                $station[trim($key)] = $value;
            });
			$station['mac'] = trim(str_replace(":", "-", strtoupper($station['mac']))) ;

			$client = array(
				'station' => $station,
				'session' => array()
			);
			
			foreach($sessions as $session){
                array_walk($session, function($value, $key) use(&$session){
                    $session[trim($key)] = $value;
                });
				// print_r($session['mac']);exit;
				$session['mac'] = trim(str_replace(":", "-", strtoupper($session['mac']))) ;
				if($station['mac'] == $session['mac']){
					$client['session'] = $session;
					break;
				}
			}
			$clients[] = $client;
		}                

		foreach($clients as $client) {
			
			
			$user = array_merge($client['station'], $client['session']);
			
			$client_data = array(
                'request_id' => $request_id,
                'ap_mac' => isset($box_data['ap_mac']) ? $box_data['ap_mac'] : "",
                'user_mac' => isset($user['mac']) ? $user['mac'] : "",
                'cust_ip' => isset($user['cust_ip']) ? $user['cust_ip'] : "",
                'auth_status' => isset($user['auth_status']) ? $user['auth_status'] : "",
                'session_id' => isset($user['session_id']) ? $user['session_id'] : "",
                'username' => isset($user['username']) ? $user['username'] : "",
                'session_time' => isset($user['session_time/max_allow']) ? explode("/", $user['session_time/max_allow'])[0] : "",
                'max_session_time' => isset($user['session_time/max_allow']) ? explode("/", $user['session_time/max_allow'])[1] : "",
                'idle_time' => isset($user['idle/max_allow']) ? explode("/", $user['idle/max_allow'])[0] : "",
                'max_idle_time' => isset($user['idle/max_allow']) ? explode("/", $user['idle/max_allow'])[1] : "",
                'input_octets' => isset($user['input_octets/max_allow']) ? explode("/", $user['input_octets/max_allow'])[0] : "",
                'max_input_octets' => isset($user['input_octets/max_allow']) ? explode("/", $user['input_octets/max_allow'])[1] : "",
                'output_octets' => isset($user['output_octets/max_allow']) ? explode("/", $user['output_octets/max_allow'])[0] : "",
                'max_output_octets' => isset($user['output_octets/max_allow']) ? explode("/", $user['input_octets/max_allow'])[1] : "",
                'max_allowed_octets' => isset($user['max_allowed_octets']) ? $user['max_allowed_octets'] : "",
                'max_up_bw' => isset($user['max_up_bw']) ? $user['max_up_bw'] : "",
                'max_down_bw' => isset($user['max_down_bw']) ? $user['max_down_bw'] : "",
                'interface' => isset($user['interface']) ? str_replace(")", "", $user['interface']) : "",
                'inactive_time' => isset($user['inactive_time']) ? $user['inactive_time'] : "",
                'rx_packets' => isset($user['rx_packets']) ? $user['rx_packets'] : "",
                'tx_packets' => isset($user['tx_packets']) ? $user['tx_packets'] : "",
                'rx_bytes' => isset($user['rx_bytes']) ? $user['rx_bytes'] : "",
                'tx_bytes' => isset($user['tx_bytes']) ? $user['tx_bytes'] : "",
                'tx_retries' => isset($user['tx_retries']) ? $user['tx_retries'] : "",
                'tx_failed' => isset($user['tx_failed']) ? $user['tx_failed'] : "",
                'signals' => isset($user['signal']) ? $user['signal'] : "",
                'signal_avg' => isset($user['signal_avg']) ? $user['signal_avg'] : "",
                'tx_bitrate' => isset($user['tx_bitrate']) ? $user['tx_bitrate'] : "",
                'rx_bitrate' => isset($user['rx_bitrate']) ? $user['rx_bitrate'] : "",
                'authorized' => isset($user['authorized']) ? $user['authorized'] : "",
                'authenticated' => isset($user['authenticated']) ? $user['authenticated'] : "",
                'wmm' => isset($user['wmm']) ? $user['wmm'] : "",
                'mfp' => isset($user['mfp']) ? $user['mfp'] : "",
                'tdls' => isset($user['tdls']) ? $user['tdls'] : "",
                'preamble' => isset($user['preamble']) ? $user['preamble'] : "",
				'expected_output' => isset($user['expected_output']) ? $user['expected_output'] : "",
			);
//            print_r($client_data);exit;
            
            $newClient = new ClientsUpdate($client_data);
            $newClient->save($client_data);
		}
        
        $surveyDatas = array();
        foreach($data['survey'] as $survey){
            array_walk($survey, function($value, $key) use(&$survey){
                $survey[trim($key)] = $value;
            });
            $survey['mac'] = trim(str_replace(":", "-", strtoupper($survey['mac']))) ;
            
            $new_survey = array(
                'request_id' => $request_id,
                'channel' => $survey['channel'],
                'ssid' => $survey['ssid'],
                'mac' => $survey['mac'],
                'enc' => $survey['enc'],
                'signals' => $survey['signal'],
                'htmode' =>  $survey['htmode'],
                'chain' => $survey['chain'],
            );
            
            $surveyModel = new SurveyData($new_survey);
            $surveyModel->save();
        }
		
		return response()->json([
			'code' => '200',
			'success' => 'true'
		]);
	}
}
