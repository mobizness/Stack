<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Client;
use App\APIConf;

class ClientsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function uploadAPData(Request $request)
    {
      $apiKey = $request->input('apiKey');
      if ($apiKey == null || $apiKey != APIConf::API_KEY()) {
        return 'Invalid Request';
      }

      $request_id = $request->input('rid');
      $ap_mac = $request->input('mac');
      $user_details = $request->input("user_details");

      if (!isset($request_id) || !isset($ap_mac) || !isset($user_details)) {
        return array('code' => 0, 'message' => 'Invalid Request');
      }

      $user_details_arr = explode("|", $user_details);

      for ($i = 0; $i < sizeof($user_details_arr) - 1; $i++) {

        $individual_user_details_arr = explode(",", $user_details_arr[$i]);

        $user_mac = isset($individual_user_details_arr[0]) ? $individual_user_details_arr[0] : '';
        $ip_address = isset($individual_user_details_arr[1]) ? $individual_user_details_arr[1] : '';
        $status = isset($individual_user_details_arr[2]) ? $individual_user_details_arr[2] : '';
        $session_id = isset($individual_user_details_arr[3]) ? $individual_user_details_arr[3] : '';
        $login_status = isset($individual_user_details_arr[4]) ? $individual_user_details_arr[4] : '';
        $customer_id = isset($individual_user_details_arr[5]) ? $individual_user_details_arr[5] : '';
        $time_consumed = isset($individual_user_details_arr[6]) ? $individual_user_details_arr[6] : '';
        $input_octets = isset($individual_user_details_arr[7]) ? $individual_user_details_arr[7] : '';
        $output_octets = isset($individual_user_details_arr[8]) ? $individual_user_details_arr[8] : '';
        $input_octets_arr = explode("/", $input_octets);
        $output_octets_arr = explode("/", $output_octets);


        Client::create([
          "request_id" => $request_id,
          "ap_mac" => $ap_mac,
          "chilli_status" => $status,
          "flag" => $login_status,
          "customer_id" => $customer_id,
          "input_octets" => $input_octets_arr[0],
          "output_octets" => $output_octets_arr[0],
          "time_consumed" => $time_consumed,
          "ip_address" => $ip_address,
          "user_mac" => $user_mac,
          "session_id" => $session_id
        ]);
      }

      return 'Success';
    }

    public function uploadScanData(Request $request)
    {
      $apiKey = $request->input('apiKey');
      if ($apiKey == null || $apiKey != APIConf::API_KEY()) {
        return 'Invalid Request';
      }

      $request_id = $request->input('rid');
      $ap_mac = $request->input('mac');
      $user_details = $request->input("user_details");

      if (!isset($request_id) || !isset($ap_mac) || !isset($user_details)) {
        return array('code' => 0, 'message' => 'Invalid Request');
      }

      $data = array();
      $user_details_arr = explode(";", $user_details);

      for ($i = 0; $i < sizeof($user_details_arr); $i++) {

        $individual_user_details_arr = explode(",", $user_details_arr[$i]);

        $user_mac = isset($individual_user_details_arr[0]) ? $individual_user_details_arr[0] : '';
        $user_mac = strtoupper(str_replace(":", "-", $user_mac));

        $inactive_time = isset($individual_user_details_arr[2]) ? $individual_user_details_arr[2] : '';
        $signal = isset($individual_user_details_arr[1]) ? $individual_user_details_arr[1] : '';
        $connected_time = isset($individual_user_details_arr[3]) ? $individual_user_details_arr[3] : '';

        $update_data = Array(
          "signal" => $signal,
          "inactive_time" => $inactive_time,
          "connected_time" => $connected_time
        );
        $db->where('ap_mac', $ap_mac);
        $db->where('request_id', $request_id);
        $db->where('user_mac', $user_mac);
        $db->update('api_user_stats', $update_data);

        $item = Client::where('ap_mac', $ap_mac)->
                        where('request_id', $request_id)->
                        where('user_mac', $user_mac)->get();

        if (isset($item)) {
          $item->signal = $signal;
          $item->inactive_time = $inactive_time;
          $item->connected_time = $connected_time;
          $item->save();

          $data[] = $user_mac;
        }
      }

      return 'Success';
    }
}
