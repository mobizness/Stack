<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Location;
use App\Box;
use App\SplashPage;
use App\AccessType;
use App\Network;
use Validator;

class LocationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $perPage = $request->input('per');
      $locations = Location::paginate($perPage);

      $data = array();
      foreach ($locations as $location) {
        $arry = $location->toArray();
        $arry['created_at'] = $location->created_at->timestamp;
        $arry['updated_at'] = $location->updated_at->timestamp;
        $arry['slug'] = $location->id;
        $arry['boxes_alerting'] = true;
        $arry['boxes_offline'] = 0;
        $arry['boxes_count'] = Box::where('location_id', $location->id)->count();
        $arry['clients_online'] = 0;
        $arry['networks'] = 0;
        $arry['has_devices'] = $arry['boxes_count'] > 0;
        $arry['metadata'] = array();
        $arry['metadata']['boxes_alerting'] = true;
        $arry['metadata']['boxes_offline'] = 0;
        $arry['metadata']['boxes_count'] = Box::where('location_id', $location->id)->count();
        $arry['metadata']['clients_online'] = 0;
        $arry['metadata']['networks'] = 0;
        $data[] = $arry;
      }

      $links = array();
      $links['current_page'] = $locations->currentPage();
      if ($locations->hasMorePages())
        $links['next_page'] = $links['current_page'] + 1;
      $links['total_entries'] = $locations->total();
      $links['total_pages'] = $locations->lastPage();
      $links['size'] = $locations->perPage();
      return array('locations' => $data, '_links' => $links);
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
      $location = $request->input('location');
      if (!isset($location)) {
        return array('code' => 0, 'message' => 'Invalid Request');
      }

      $add_to_global_map = isset($location['add_to_global_map']) ? $location['add_to_global_map'] : 0;
      $brand_id = isset($location['brand_id']) ? $location['brand_id'] : 0;
      $project_id = isset($location['project_id']) ? $location['project_id'] : 0;
      $country = isset($location['country']) ? $location['country'] : '';
      $description = isset($location['description']) ? $location['description'] : '';
      $development_mode = isset($location['development_mode']) ? $location['development_mode'] : '';
      $is_favourite = isset($location['is_favourite']) ? $location['is_favourite'] : 0;
      $location_address = isset($location['location_address']) ? $location['location_address'] : '';
      $location_name = isset($location['location_name']) ? $location['location_name'] : '';
      $network_location = isset($location['network_location']) ? $location['network_location'] : '';
      $reports_active = isset($location['reports_active']) ? $location['reports_active'] : 0;
      $reports_emails = isset($location['reports_emails']) ? $location['reports_emails'] : '';
      $postcode = isset($location['postcode']) ? $location['postcode'] : '';
      $street = isset($location['street']) ? $location['street'] : '';
      $timezone = isset($location['timezone']) ? $location['timezone'] : '';
      $tagged_logins = isset($location['tagged_logins']) ? $location['tagged_logins'] : 0;
      $town = isset($location['town']) ? $location['town'] : '';

      $item = Location::create([
        'brand_id' => $brand_id,
        'project_id' => $project_id,
        'country' => $country,
        'description' => $description,
        'development_mode' => $development_mode,
        'is_favourite' => $is_favourite,
        'location_address' => $location_address,
        'location_name' => $location_name,
        'network_location' => $network_location,
        'reports_active' => $reports_active,
        'reports_emails' => $reports_emails,
        'postcode' => $postcode,
        'street' => $street,
        'timezone' => $timezone,
        'tagged_logins' => $tagged_logins,
        'town' => $town,
      ]);

      $data = $item->toArray();
      $data['created_at'] = $item->created_at->timestamp;
      $data['updated_at'] = $item->updated_at->timestamp;
      $data['slug'] = $item->id;
      $data['boxes_alerting'] = true;
      $data['boxes_offline'] = 0;
      $data['boxes_count'] = Box::where('location_id', $item->id)->count();
      $data['clients_online'] = 0;
      $data['networks'] = 0;
      $data['has_devices'] = $data['boxes_count'] > 0;
      $data['metadata'] = array();
      $data['metadata']['boxes_alerting'] = true;
      $data['metadata']['boxes_offline'] = 0;
      $data['metadata']['boxes_count'] = Box::where('location_id', $item->id)->count();
      $data['metadata']['clients_online'] = 0;
      $data['metadata']['networks'] = 0;

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
      $item = Location::find($id);
      if ($item == null)
        return array('code' => 0, 'message' => 'Invalid Request');

      $data = $item->toArray();
      $data['created_at'] = $item->created_at->timestamp;
      $data['updated_at'] = $item->updated_at->timestamp;
      $data['slug'] = $item->id;
      $data['boxes_alerting'] = true;
      $data['boxes_offline'] = 0;
      $data['boxes_count'] = Box::where('location_id', $item->id)->count();
      $data['clients_online'] = 0;
      $data['networks'] = 0;
      $data['has_devices'] = $data['boxes_count'] > 0;
      $data['metadata'] = array();
      $data['metadata']['boxes_alerting'] = true;
      $data['metadata']['boxes_offline'] = 0;
      $data['metadata']['boxes_count'] = Box::where('location_id', $item->id)->count();
      $data['metadata']['clients_online'] = 0;
      $data['metadata']['networks'] = 0;

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
      $item = Location::find($id);
      if ($item == null)
        return array('code' => 0, 'message' => 'Invalid Request');

      $location = $request->input('location');
      if (!isset($location)) {
        return array('code' => 0, 'message' => 'Invalid Request');
      }

      $add_to_global_map = isset($location['add_to_global_map']) ? $location['add_to_global_map'] : 0;
      $brand_id = isset($location['brand_id']) ? $location['brand_id'] : 0;
      $project_id = isset($location['project_id']) ? $location['project_id'] : 0;
      $country = isset($location['country']) ? $location['country'] : '';
      $description = isset($location['description']) ? $location['description'] : '';
      $development_mode = isset($location['development_mode']) ? $location['development_mode'] : '';
      $is_favourite = isset($location['is_favourite']) ? $location['is_favourite'] : 0;
      $location_address = isset($location['location_address']) ? $location['location_address'] : '';
      $location_name = isset($location['location_name']) ? $location['location_name'] : '';
      $network_location = isset($location['network_location']) ? $location['network_location'] : '';
      $reports_active = isset($location['reports_active']) ? $location['reports_active'] : 0;
      $reports_emails = isset($location['reports_emails']) ? $location['reports_emails'] : '';
      $postcode = isset($location['postcode']) ? $location['postcode'] : '';
      $street = isset($location['street']) ? $location['street'] : '';
      $timezone = isset($location['timezone']) ? $location['timezone'] : '';
      $tagged_logins = isset($location['tagged_logins']) ? $location['tagged_logins'] : 0;
      $town = isset($location['town']) ? $location['town'] : '';

      $item->brand_id = $brand_id;
      $item->project_id = $project_id;
      $item->country = $country;
      $item->description = $description;
      $item->development_mode = $development_mode;
      $item->is_favourite = $is_favourite;
      $item->location_address = $location_address;
      $item->location_name = $location_name;
      $item->network_location = $network_location;
      $item->reports_active = $reports_active;
      $item->reports_emails = $reports_emails;
      $item->postcode = $postcode;
      $item->street = $street;
      $item->timezone = $timezone;
      $item->tagged_logins = $tagged_logins;
      $item->town = $town;

      $item->save();

      $data = $item->toArray();
      $data['created_at'] = $item->created_at->timestamp;
      $data['updated_at'] = $item->updated_at->timestamp;
      $data['slug'] = $item->id;
      $data['boxes_alerting'] = true;
      $data['boxes_offline'] = 0;
      $data['boxes_count'] = Box::where('location_id', $item->id)->count();
      $data['clients_online'] = 0;
      $data['networks'] = 0;
      $data['has_devices'] = $data['boxes_count'] > 0;
      $data['metadata'] = array();
      $data['metadata']['boxes_alerting'] = true;
      $data['metadata']['boxes_offline'] = 0;
      $data['metadata']['boxes_count'] = Box::where('location_id', $item->id)->count();
      $data['metadata']['clients_online'] = 0;
      $data['metadata']['networks'] = 0;

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
      $item = Location::find($id);
      if ($item == null)
        return array('code' => 0, 'message' => 'Invalid Request');

      $item->delete();
      return array('code' => 0, 'message' => 'Success');
    }

    public function clients()
    {

    }
    
    public function splash_codes()
    {

    }
}
