<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Zone;
use App\Box;
use App\Network;

class ZonesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($location_id, Request $request)
    {
      $perPage = $request->input('per');
      $zones = Zone::where('location_id', $location_id)->paginate($perPage);

      $data = array();
      foreach ($zones as $zone) {
        $arry = $zone->toArray();
        $arry['created_at'] = $zone->created_at->timestamp;
        $arry['updated_at'] = $zone->updated_at->timestamp;
        $arry['boxes'] = Box::where('zone_id', $zone->id)->count();
        $arry['networks'] = Network::where('zone_id', $zone->id)->count();
        $data[] = $arry;
      }

      return array('zones' => $data);
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
    public function store($location_id, Request $request)
    {
      $zone = $request->input('zone');
      if (!isset($zone)) {
        return array('code' => 0, 'message' => 'Invalid Request');
      }

      $zone_description = isset($zone['zone_description']) ? $zone['zone_description'] : '';
      $zone_name = isset($zone['zone_name']) ? $zone['zone_name'] : '';

      $item = Zone::create([
        'location_id' => $location_id,
        'zone_description' => $zone_description,
        'zone_name' => $zone_name,
      ]);

      $data = $item->toArray();
      $data['created_at'] = $item->created_at->timestamp;
      $data['updated_at'] = $item->updated_at->timestamp;
      $data['boxes'] = 0;
      $data['networks'] = 0;

      return array('zone' => $data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($location_id, $id)
    {
      $item = Zone::find($id);
      if (!isset($item)) {
        return array('code' => 0, 'message' => 'Invalid Request');
      }

      $data = $item->toArray();
      $data['created_at'] = $item->created_at->timestamp;
      $data['updated_at'] = $item->updated_at->timestamp;
      $data['boxes'] = Box::where('zone_id', $item->id)->count();

      $networks = Network::where('zone_id', $item->id)->get();

      $data1 = array();
      foreach ($networks as $network) {
        $item1 = array();
        $item1['network_id'] = $network->id;
        $data1[] = $item1;
      }

      return array('zone' => $data, 'networks' => $data1);
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
      $zone = $request->input('zone');
      $item = Zone::find($id);
      if (!isset($zone) || !isset($item)) {
        return array('code' => 0, 'message' => 'Invalid Request');
      }

      $zone_description = isset($zone['zone_description']) ? $zone['zone_description'] : '';
      $zone_name = isset($zone['zone_name']) ? $zone['zone_name'] : '';
      $networks_str = isset($zone['networks']) ? $zone['networks'] : '';
      $networks = explode(',', $networks_str);

      $item->zone_description = $zone_description;
      $item->zone_name = $zone_name;
      $item->save();

      Network::where('zone_id', $item->id)->update(['zone_id' => 0]);

      foreach ($networks as $network) {
        $item1 = Network::find($network);
        if ($item1) {
          $item1->zone_id = $item->id;
          $item1->save();
        }
      }

      $data = $item->toArray();
      $data['created_at'] = $item->created_at->timestamp;
      $data['updated_at'] = $item->updated_at->timestamp;
      $data['boxes'] = Box::where('zone_id', $item->id)->count();

      $networks = Network::where('zone_id', $item->id)->get();
      $data1 = array();
      foreach ($networks as $network) {
        $item1 = array();
        $item1['network_id'] = $network->id;
        $data1[] = $item1;
      }

      return array('zone' => $data, 'networks' => $data1);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($location_id, $id)
    {
      $item = Zone::find($id);
      if ($item == null)
        return array('code' => 0, 'message' => 'Invalid Request');

      $item->delete();
      return array('code' => 0, 'message' => 'Success');
    }
}
