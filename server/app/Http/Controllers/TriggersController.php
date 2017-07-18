<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Trigger;

class TriggersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($category, $category_id, Request $request)
    {
      $perPage = $request->input('per');
      if ($category == 'locations') {
        $triggers = Trigger::where('location_id', $category_id)->paginate($perPage);
      } else if ($category == 'brands') {
        $triggers = Trigger::where('brand_id', $category_id)->paginate($perPage);
      }

      $data = array();
      foreach ($triggers as $trigger) {
        $arry = $trigger->toArray();
        $arry['created_at'] = $trigger->created_at->timestamp;
        $arry['updated_at'] = $trigger->updated_at->timestamp;
        $data[] = $arry;
      }

      $links = array();
      $links['current_page'] = $triggers->currentPage();
      if ($triggers->hasMorePages())
        $links['next_page'] = $links['current_page'] + 1;
      $links['total_entries'] = $triggers->total();
      $links['total_pages'] = $triggers->lastPage();
      $links['size'] = $triggers->perPage();
      return array('triggers' => $data, '_links' => $links);
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
    public function store($category, $category_id, Request $request)
    {
      $trigger = $request->input('trigger');
      if (!isset($trigger)) {
        return array('code' => 0, 'message' => 'Invalid Request');
      }

      $trigger_name = isset($trigger['trigger_name']) ? $trigger['trigger_name'] : '';
      $trigger_type = isset($trigger['trigger_type']) ? $trigger['trigger_type'] : '';
      $ttl = isset($trigger['ttl']) ? $trigger['ttl'] : '';
      $type = isset($trigger['type']) ? $trigger['type'] : '';
      $tags = isset($trigger['tags']) ? $trigger['tags'] : '';
      $attr_1 = isset($trigger['attr_1']) ? $trigger['attr_1'] : '';
      $attr_2 = isset($trigger['attr_2']) ? $trigger['attr_2'] : '';
      $attr_3 = isset($trigger['attr_3']) ? $trigger['attr_3'] : '';
      $attr_4 = isset($trigger['attr_4']) ? $trigger['attr_4'] : '';
      $channel = isset($trigger['channel']) ? $trigger['channel'] : '';
      $match = isset($trigger['match']) ? $trigger['match'] : '';
      $starttime = isset($trigger['starttime']) ? $trigger['starttime'] : '';
      $start_hour = isset($trigger['start_hour']) ? $trigger['start_hour'] : '';
      $endtime = isset($trigger['endtime']) ? $trigger['endtime'] : '';
      $end_hour = isset($trigger['end_hour']) ? $trigger['end_hour'] : '';
      $periodic_days = isset($trigger['periodic_days']) ? $trigger['periodic_days'] : null;

      $data = array();
      if ($category == 'locations') {
        $data['location_id'] = $category_id;
      } else if ($category == 'brands') {
        $data['brand_id'] = $category_id;
      }

      $data['trigger_name'] = $trigger_name;
      $data['trigger_type'] = $trigger_type;
      $data['ttl'] = $ttl;
      $data['type'] = $type;
      $data['tags'] = $tags;
      $data['attr_1'] = $attr_1;
      $data['attr_2'] = $attr_2;
      $data['attr_3'] = $attr_3;
      $data['attr_4'] = $attr_4;
      $data['channel'] = $channel;
      $data['match'] = $match;
      $data['starttime'] = $starttime;
      $data['start_hour'] = $start_hour;
      $data['endtime'] = $endtime;
      $data['end_hour'] = $end_hour;
      $data['active'] = 1;

      if ($periodic_days) {
        $data['periodic_days'] = implode(',', $periodic_days);
      }

      $item = Trigger::create($data);

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
    public function show($category, $category_id, $id)
    {
      $item = Trigger::find($id);
      if (!isset($item)) {
        return array('code' => 0, 'message' => 'Invalid Request');

      }
      $data = $item->toArray();
      $data['created_at'] = $item->created_at->timestamp;
      $data['updated_at'] = $item->updated_at->timestamp;
      $data['allowed_days'] = explode(',', $item->periodic_days);

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
    public function update($category, $category_id, Request $request, $id)
    {
      $item = Trigger::find($id);
      $trigger = $request->input('trigger');
      if (!isset($item) || !isset($trigger)) {
        return array('code' => 0, 'message' => 'Invalid Request');
      }

      $trigger_name = isset($trigger['trigger_name']) ? $trigger['trigger_name'] : '';
      $trigger_type = isset($trigger['trigger_type']) ? $trigger['trigger_type'] : '';
      $ttl = isset($trigger['ttl']) ? $trigger['ttl'] : '';
      $type = isset($trigger['type']) ? $trigger['type'] : '';
      $tags = isset($trigger['tags']) ? $trigger['tags'] : '';
      $attr_1 = isset($trigger['attr_1']) ? $trigger['attr_1'] : '';
      $attr_2 = isset($trigger['attr_2']) ? $trigger['attr_2'] : '';
      $attr_3 = isset($trigger['attr_3']) ? $trigger['attr_3'] : '';
      $attr_4 = isset($trigger['attr_4']) ? $trigger['attr_4'] : '';
      $channel = isset($trigger['channel']) ? $trigger['channel'] : '';
      $match = isset($trigger['match']) ? $trigger['match'] : '';
      $starttime = isset($trigger['starttime']) ? $trigger['starttime'] : '';
      $start_hour = isset($trigger['start_hour']) ? $trigger['start_hour'] : '';
      $endtime = isset($trigger['endtime']) ? $trigger['endtime'] : '';
      $end_hour = isset($trigger['end_hour']) ? $trigger['end_hour'] : '';
      $periodic_days = isset($trigger['periodic_days']) ? $trigger['periodic_days'] : null;

      $item->trigger_name = $trigger_name;
      $item->trigger_type = $trigger_type;
      $item->ttl = $ttl;
      $item->type = $type;
      $item->tags = $tags;
      $item->attr_1 = $attr_1;
      $item->attr_2 = $attr_2;
      $item->attr_3 = $attr_3;
      $item->attr_4 = $attr_4;
      $item->channel = $channel;
      $item->match = $match;
      $item->starttime = $starttime;
      $item->start_hour = $start_hour;
      $item->endtime = $endtime;
      $item->end_hour = $end_hour;

      if ($periodic_days) {
        $item->periodic_days = implode(',', $periodic_days);
      }

      $item->save();

      $data = $item->toArray();
      $data['created_at'] = $item->created_at->timestamp;
      $data['updated_at'] = $item->updated_at->timestamp;
      $data['allowed_days'] = explode(',', $item->periodic_days);

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
        //
    }
}
