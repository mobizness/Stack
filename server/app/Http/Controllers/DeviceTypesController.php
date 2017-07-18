<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\DeviceType;

class DeviceTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index(Request $request)
     {
       $perPage = $request->input('per');
       $device_types = DeviceType::paginate($perPage);

       $data = array();
       foreach ($device_types as $type) {
         $arry = $type->toArray();
         $arry['created_at'] = $type->created_at->timestamp;
         $arry['updated_at'] = $type->updated_at->timestamp;
         $data[] = $arry;
       }

       $links = array();
       $links['current_page'] = $device_types->currentPage();
       if ($device_types->hasMorePages())
         $links['next_page'] = $links['current_page'] + 1;
       $links['total_entries'] = $device_types->total();
       $links['total_pages'] = $device_types->lastPage();
       $links['size'] = $device_types->perPage();
       return array('device_types' => $data, '_links' => $links);
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
      $device_type = $request->input('device_type');
      if (!isset($device_type)) {
        return array('code' => 0, 'message' => 'Invalid Request');
      }

      $name = isset($device_type['name']) ? $device_type['name'] : '';
      $hs_lanif = isset($device_type['hs_lanif']) ? $device_type['hs_lanif'] : '';
      $hs_wanif = isset($device_type['hs_wanif']) ? $device_type['hs_wanif'] : '';

      $item = DeviceType::create([
        'name' => $name,
        'hs_lanif' => $hs_lanif,
        'hs_wanif' => $hs_wanif,
      ]);

      $data = $item->toArray();
      $data['created_at'] = $item->created_at->timestamp;
      $data['updated_at'] = $item->updated_at->timestamp;

      return $data;
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
      $item = DeviceType::find($id);
      if ($item == null)
        return array('code' => 0, 'message' => 'Invalid Request');

      $device_type = $request->input('device_type');
      if (!isset($device_type)) {
        return array('code' => 0, 'message' => 'Invalid Request');
      }

      $name = isset($device_type['name']) ? $device_type['name'] : '';
      $hs_lanif = isset($device_type['hs_lanif']) ? $device_type['hs_lanif'] : '';
      $hs_wanif = isset($device_type['hs_wanif']) ? $device_type['hs_wanif'] : '';

      $item->name = $name;
      $item->hs_lanif = $hs_lanif;
      $item->hs_wanif = $hs_wanif;

      $item->save();

      $data = $item->toArray();
      $data['created_at'] = $item->created_at->timestamp;
      $data['updated_at'] = $item->updated_at->timestamp;

      return $data;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $item = DeviceType::find($id);
      if ($item == null)
        return array('code' => 0, 'message' => 'Invalid Request');

      $item->delete();
      return array('code' => 0, 'message' => 'Success');
    }
}
