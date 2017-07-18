<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Mail;
use App\Mail\InviteEmail;

use App\Location;
use App\User;
use App\Brand;

class InvitesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($location_id)
    {
      $location = Location::find($location_id);
      if (!isset($location)) {
        return array('code' => 0, 'message' => 'Invalid Request');
      }

      $users = User::where('project_id', $location->project_id)->get();

      return $users;
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
      $invite = $request->input('invite');
      $location_id = $request->input('location_id');
      $location = Location::find($location_id);
      if (!isset($invite) || !isset($location)) {
        return array('code' => 0, 'message' => 'Invalid Request');
      }

      $email = isset($invite['email']) ? $invite['email'] : '';
      $name = isset($invite['name']) ? $invite['name'] : '';
      $role_id = isset($invite['role_id']) ? $invite['role_id'] : 0;
      $password = Str::random('10');

      $item = User::create([
        'brand_id' => $location->brand_id,
        'project_id' => $location->project_id,
        'role_id' => $role_id,
        'username' => $name,
        'email' => $email,
        'password' => $password,
        'remember_token' => Str::random('60'),
      ]);

      $brand = Brand::find($locatio->brand_id);
      $title = 'Digifi Invitation';
      $content = 'Brand : ' . $brand->brand_name . ', Password : ' . $password;

      Mail::to($item)->send(new InviteEmail($title, $content));

      return $item;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $item = User::find($id);
      if ($item == null)
        return array('code' => 0, 'message' => 'Invalid Request');

      return $item;
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

    public function patch(Request $request)
    {
      $email = $request->input('email');
      if ($email == null)
        return array('code' => 0, 'message' => 'Invalid Request');

      $item = User::where('email', $email)->first();
      if ($item == null)
        return array('code' => 0, 'message' => 'Invalid Request');

      $role_id = $request->input('role_id');
      $item->role_id = $role_id;
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

    public function destroy2()
    {
      $email = $request->input('email');
      if ($email == null)
        return array('code' => 0, 'message' => 'Invalid Request');

      User::where('email', $email)->delete();

      return array('code' => 0, 'message' => 'Success');
    }
}
