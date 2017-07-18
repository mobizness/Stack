<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\GroupPolicy;
use App\Network;

class GroupPoliciesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($location_id, Request $request)
    {
      $perPage = $request->input('per');
      $policies = GroupPolicy::where('location_id', $location_id)->paginate($perPage);

      $data = array();
      foreach ($policies as $policy) {
        $arry = $policy->toArray();
        $arry['created_at'] = $policy->created_at->timestamp;
        $arry['updated_at'] = $policy->updated_at->timestamp;
        $arry['networks'] = Network::where('group_policy_id', $policy->id)->count();
        $data[] = $arry;
      }

      $links = array();
      $links['current_page'] = $policies->currentPage();
      if ($policies->hasMorePages())
        $links['next_page'] = $links['current_page'] + 1;
      $links['total_entries'] = $policies->total();
      $links['total_pages'] = $policies->lastPage();
      $links['size'] = $policies->perPage();
      return array('group_policies' => $data, '_links' => $links);
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
      $group_policy = $request->input('group_policy');
      if (!isset($group_policy)) {
        return array('code' => 0, 'message' => 'Invalid Request');
      }

      $group_type = isset($group_policy['group_type']) ? $group_policy['group_type'] : '';
      $policy = isset($group_policy['policy']) ? $group_policy['policy'] : '';
      $policy_name = isset($group_policy['policy_name']) ? $group_policy['policy_name'] : '';

      $item = GroupPolicy::create([
        'location_id' => $location_id,
        'group_type' => $group_type,
        'policy' => $policy,
        'policy_name' => $policy_name,
      ]);

      $data = $item->toArray();
      $data['created_at'] = $item->created_at->timestamp;
      $data['updated_at'] = $item->updated_at->timestamp;
      $data['networks'] = 0;

      return array('group_policy' => $data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($location_id, $id)
    {
      $item = GroupPolicy::find($id);
      if (!isset($item)) {
        return array('code' => 0, 'message' => 'Invalid Request');
      }

      $data = $item->toArray();
      $data['created_at'] = $item->created_at->timestamp;
      $data['updated_at'] = $item->updated_at->timestamp;

      $networks = Network::where('group_policy_id', $item->id)->get();
      $data1 = array();
      foreach ($networks as $network) {
        $item1 = array();
        $item1['network_id'] = $network->id;
        $data1[] = $item1;
      }

      return array('group_policy' => $data, 'networks' => $data1);
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
      $group_policy = $request->input('group_policy');
      $item = GroupPolicy::find($id);
      if (!isset($group_policy) || !isset($item)) {
        return array('code' => 0, 'message' => 'Invalid Request');
      }

      $group_type = isset($group_policy['group_type']) ? $group_policy['group_type'] : '';
      $policy = isset($group_policy['policy']) ? $group_policy['policy'] : '';
      $policy_name = isset($group_policy['policy_name']) ? $group_policy['policy_name'] : '';
      $networks_attributes = isset($group_policy['networks_attributes']) ? $group_policy['networks_attributes'] : null;

      $item->group_type = $group_type;
      $item->policy = $policy;
      $item->policy_name = $policy_name;
      $item->save();

      if ($networks_attributes) {
        foreach ($networks_attributes as $network) {
          $item1 = Network::find($network['id']);
          if ($item1) {
            if (isset($network['_destroy']) && $network['_destroy'] == true) {
              $item1->group_policy_id = 0;
            } else {
              $item1->group_policy_id = $item->id;
            }
            $item1->save();
          }
        }
      }

      $data = $item->toArray();
      $data['created_at'] = $item->created_at->timestamp;
      $data['updated_at'] = $item->updated_at->timestamp;

      $networks = Network::where('group_policy_id', $item->id)->get();
      $data1 = array();
      foreach ($networks as $network) {
        $item1 = array();
        $item1['network_id'] = $network->id;
        $data1[] = $item1;
      }

      return array('group_policy' => $data, 'networks' => $data1);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($location_id, $id)
    {
      $item = GroupPolicy::find($id);
      if ($item == null)
        return array('code' => 0, 'message' => 'Invalid Request');

      $item->delete();
      return array('code' => 0, 'message' => 'Success');
    }
}
