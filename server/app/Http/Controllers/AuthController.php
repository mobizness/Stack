<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Brand;
use Validator;
use Redirect;
use Auth;

class AuthController extends Controller
{
    //
    public function login(Request $request)
    {
      $validator = Validator::make($request->all(), [
        'email' => 'email|exists:users,email',
      ]);

      if ($validator->fails()) {
        return Redirect::back()->withErrors($validator)->withInput();
      }

      $email = $request->input('email');
      $password = $request->input('password');

      $user = User::where('email', $email)->first();
      $brand = Brand::find($user->brand_id);

      if ( !isset($brand->brand_name) || $brand->brand_name != $request->input('brand') ) {
        $validator->getMessageBag()->add('brand', 'Forgot brand domain?');
        return Redirect::back()->withErrors($validator)->withInput();
      }

      if (Auth::attempt(['email' => $email, 'password' => $password], true)) {
        // Authentication passed...
        setcookie('digifi_token', Auth::user()->remember_token, 0, '/');
        return redirect()->intended("../");
      } else {
        $validator->getMessageBag()->add('email', 'Invalid email or password. Try again.');
        return Redirect::back()->withErrors($validator)->withInput();
      }
    }

    public function logout(Request $request)
    {
      if (Auth::check()) {
        Auth::logout();
        return redirect()->intended("../");
      }
    }

    public function newPassword()
    {
      return view('auth/passwords/new');
    }
}
