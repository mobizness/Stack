<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\User;
use App\Brand;
use Validator;
use Auth;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $perPage = $request->input('per');
      $users = User::paginate($perPage);

      $data = array();
      foreach ($users as $user) {
        $arry = $user->toArray();
        $arry['created_at'] = $user->created_at->timestamp;
        $arry['updated_at'] = $user->updated_at->timestamp;
        $data[] = $arry;
      }

      $links = array();
      $links['current_page'] = $users->currentPage();
      if ($users->hasMorePages())
        $links['next_page'] = $links['current_page'] + 1;
      $links['total_entries'] = $users->total();
      $links['total_pages'] = $users->lastPage();
      $links['size'] = $users->perPage();
      return array('users' => $data, '_links' => $links);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $user = $request->input('user');

      $brand_id = isset($user['brand_id']) ? $user['brand_id'] : 0;
      $project_id = isset($user['project_id']) ? $user['project_id'] : 0;
      $email = isset($user['email']) ? $user['email'] : '';
      $username = isset($user['username']) ? $user['username'] : '';
      $password = isset($user['password']) ? $user['password'] : '';
      $country = isset($user['country']) ? $user['country'] : '';

      return User::create([
        'brand_id' => $brand_id,
        'project_id' => $project_id,
        'email' => $email,
        'username' => $username,
        'password' => bcrypt($password),
        'remember_token' => Str::Random(60),
        'country' => $country,
      ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return $user;
    }

    public function me()
    {
      $user = Auth::user();
      $brand = Brand::find($user->brand_id);

      $data = $user->toArray();
      $data['created_at'] = $user->created_at->timestamp;
      $data['updated_at'] = $user->updated_at->timestamp;
      $data['url'] = $brand->url;
      $data['admin'] = true;
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
      $validator = Validator::make($request->all(), [
        'brand_id' => 'required|numeric',
        'project_id' => 'required|numeric',
        'email' => 'required|email',
        'username' => 'required',
        'password' => 'required|min:6',
        'country' => 'required'
      ]);

      if ($validator->fails()) {
        return $validator->errors()->all();
      }

      $user = User::find($id);
      $user->brand_id = $request->input('brand_id');
      $user->project_id = $request->input('project_id');
      $user->email = $request->input('email');
      $user->username = $request->input('username');
      $user->password = bcrypt($request->input('password'));
      $user->country = $request->input('country');

      $user->save();

      return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $user = User::find($id);
      $user->delete();
    }

    public function getToken()
    {
      echo csrf_token();
    }

    public function login(Request $request)
    {
      if (isset($_COOKIE['digifi_token']) && Auth::check()) {
        setcookie('digifi_token', Auth::user()->remember_token, 0, '/');
        return redirect('../home');
      }

      $brand = $request->input('brand');
      $return_to = $request->input('return_to');
      return view('welcome');
    }
}
