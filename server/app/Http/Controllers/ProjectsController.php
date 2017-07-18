<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Project;
use Auth;

class ProjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $perPage = $request->input('per');
      $projects = Project::paginate($perPage);

      $data = array();
      foreach ($projects as $project) {
        $arry = $project->toArray();
        $arry['created_at'] = $project->created_at->timestamp;
        $arry['updated_at'] = $project->updated_at->timestamp;
        $data[] = $arry;
      }

      $links = array();
      $links['current_page'] = $projects->currentPage();
      if ($projects->hasMorePages())
        $links['next_page'] = $links['current_page'] + 1;
      $links['total_entries'] = $projects->total();
      $links['total_pages'] = $projects->lastPage();
      $links['size'] = $projects->perPage();
      return array('projects' => $data, '_links' => $links);
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
      $project = $request->input('project');
      if (!isset($project)) {
        return array('code' => 0, 'message' => 'Invalid Request');
      }

      $description = isset($project['description']) ? $project['description'] : '';
      $notes = isset($project['notes']) ? $project['notes'] : '';
      $project_name = isset($project['project_name']) ? $project['project_name'] : '';

      $item = Project::create([
        'brand_id' => Auth::user()->brand_id,
        'description' => $description,
        'notes' => $notes,
        'project_name' => $project_name,
      ]);

      $data = $item->toArray();
      $data['created_at'] = $item->created_at->timestamp;
      $data['updated_at'] = $item->updated_at->timestamp;
      $data['role_id'] = 0;
      $data['slug'] = '';
      $data['type'] = '';
      $data['user_id'] = Auth::user()->id;

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
      $item = Project::find($id);
      $data = $item->toArray();
      $data['created_at'] = $item->created_at->timestamp;
      $data['updated_at'] = $item->updated_at->timestamp;
      $data['role_id'] = 0;
      $data['slug'] = '';
      $data['type'] = '';
      $data['user_id'] = Auth::user()->id;

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
      $item = Project::find($id);
      if ($item == null)
        return array('code' => 0, 'message' => 'Invalid Request');

      $project = $request->input('project');
      if (!isset($project)) {
        return array('code' => 0, 'message' => 'Invalid Request');
      }

      $description = isset($project['description']) ? $project['description'] : '';
      $notes = isset($project['notes']) ? $project['notes'] : '';
      $project_name = isset($project['project_name']) ? $project['project_name'] : '';

      $item->description = $description;
      $item->notes = $notes;
      $item->project_name = $project_name;
      $item->save();

      $data = $item->toArray();
      $data['created_at'] = $item->created_at->timestamp;
      $data['updated_at'] = $item->updated_at->timestamp;
      $data['role_id'] = 0;
      $data['slug'] = '';
      $data['type'] = '';
      $data['user_id'] = Auth::user()->id;

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
      $item = Project::find($id);
      if ($item == null)
        return array('code' => 0, 'message' => 'Invalid Request');

      $item->delete();
      return array('code' => 0, 'message' => 'Success');
    }
}
