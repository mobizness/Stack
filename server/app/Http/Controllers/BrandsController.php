<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Brand;
use App\Project;
use Auth;

class BrandsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $perPage = $request->input('per');
      $brands = Brand::paginate($perPage);

      $data = array();
      foreach ($brands as $brand) {
        $arry = $brand->toArray();
        $arry['created_at'] = $brand->created_at->timestamp;
        $arry['updated_at'] = $brand->updated_at->timestamp;
        $data[] = $arry;
      }

      $links = array();
      $links['current_page'] = $brands->currentPage();
      if ($brands->hasMorePages())
        $links['next_page'] = $links['current_page'] + 1;
      $links['total_entries'] = $brands->total();
      $links['total_pages'] = $brands->lastPage();
      $links['size'] = $brands->perPage();
      return array('brands' => $data, '_links' => $links);
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
      $brand = Brand::find($id);
      if ($brand == null)
        $brand = Brand::where('url', $id)->first();

      if ($brand == null)
        return array('code' => 0, 'message' => 'Invalid Request');

      return $brand;
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
      $brand = $request->input('brand');
      if (!isset($brand)) {
        return array('code' => 0, 'message' => 'Invalid Request');
      }

      $item = Brand::find($id);
      if ($item == null)
        $item = Brand::where('url', $id)->first();

      if (!isset($item)) {
        return array('code' => 0, 'message' => 'Invalid Request');
      }

      $background_colour = isset($brand['background_colour']) ? $brand['background_colour'] : '';
      $brand_name = isset($brand['brand_name']) ? $brand['brand_name'] : '';
      $cname = isset($brand['cname']) ? $brand['cname'] : '';
      $cname_status = isset($brand['cname_status']) ? $brand['cname_status'] : 0;
      $country = isset($brand['country']) ? $brand['country'] : '';
      $from_email = isset($brand['from_email']) ? $brand['from_email'] : '';
      $from_name = isset($brand['from_name']) ? $brand['from_name'] : '';
      $locale = isset($brand['locale']) ? $brand['locale'] : '';
      $network_location = isset($brand['network_location']) ? $brand['network_location'] : '';
      $quota_project_users = isset($brand['quota_project_users']) ? $brand['quota_project_users'] : 1;
      $quota_vouchers = isset($brand['quota_vouchers']) ? $brand['quota_vouchers'] : 0;
      $quota_locations = isset($brand['quota_locations']) ? $brand['quota_locations'] : 0;
      $quota_boxes = isset($brand['quota_boxes']) ? $brand['quota_boxes'] : 0;
      $quota_splash_views = isset($brand['quota_splash_views']) ? $brand['quota_splash_views'] : 0;
      $quota_admins = isset($brand['quota_admins']) ? $brand['quota_admins'] : 0;
      $quota_zones = isset($brand['quota_zones']) ? $brand['quota_zones'] : 0;
      $quota_triggers = isset($brand['quota_triggers']) ? $brand['quota_triggers'] : 0;
      $quota_projects = isset($brand['quota_projects']) ? $brand['quota_projects'] : 0;
      $quota_store = isset($brand['quota_store']) ? $brand['quota_store'] : 1;
      $timezone = isset($brand['timezone']) ? $brand['timezone'] : '';
      $url = isset($brand['url']) ? $brand['url'] : '';
      $website = isset($brand['website']) ? $brand['website'] : '';

      $item->background_colour = $background_colour;
      $item->brand_name = $brand_name;
      $item->cname = $cname;
      $item->cname_status = $cname_status;
      $item->country = $country;
      $item->from_email = $from_email;
      $item->locale = $locale;
      $item->network_location = $network_location;
      $item->quota_project_users = $quota_project_users;
      $item->quota_vouchers = $quota_vouchers;
      $item->quota_locations = $quota_locations;
      $item->quota_boxes = $quota_boxes;
      $item->quota_splash_views = $quota_splash_views;
      $item->quota_admins = $quota_admins;
      $item->quota_zones = $quota_zones;
      $item->quota_triggers = $quota_triggers;
      $item->quota_projects = $quota_projects;
      $item->quota_store = $quota_store;
      $item->timezone = $timezone;
      $item->url = $url;
      $item->website = $website;

      $item->save();

      return $item;
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

    public function projects(Request $request, $id)
    {
      $projects = Project::where('brand_id', $id)->get();

      $data = array();
      foreach ($projects as $project) {
        $item = $project->toArray();
        $item['created_at'] = $project->created_at->timestamp;
        $item['updated_at'] = $project->updated_at->timestamp;
        $item['role_id'] = 0;
        $item['slug'] = '';
        $item['type'] = '';
        $item['user_id'] = Auth::user()->id;

        $data[] = $item;
      }

      return $data;
    }
}
