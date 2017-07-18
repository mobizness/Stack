<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\SplashPage;
use App\Location;
use App\RegistrationForm;
use App\RegistrationField;
use App\RegistrationData;
use App\Guest;
use App\Client;
use App\VoucherCode;
use App\User;
use App\Social;
use App\Services\GlobalService;

use Socialite;

class GuestsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $perPage = $request->get('per');
      $guests = Guest::paginate($perPage);

      $data = array();
      foreach ($guests as $guest) {
        $arry = $guest->toArray();
        $arry['created_at'] = $guest->created_at->timestamp;
        $arry['updated_at'] = $guest->updated_at->timestamp;
        $arry['mac'] = array();
        $data[] = $arry;
      }

      $links = array();
      $links['current_page'] = $guests->currentPage();
      if ($guests->hasMorePages())
        $links['next_page'] = $links['current_page'] + 1;
      $links['total_entries'] = $guests->total();
      $links['total_pages'] = $guests->lastPage();
      $links['size'] = $guests->perPage();
      return array('guests' => $data, '_links' => $links);
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
        //
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

    public function login($id, Request $request)
    {
        
        $res = $request->get('res');
        $redurl = 'http://fabrica.com.eg';
        if ($res == 'success') {
            header("Location: $redurl");
            exit;
        }
        // $challenge = filter_input(INPUT_GET, 'challenge');
        // $userurl = filter_input(INPUT_GET, 'userurl');
        // $res = filter_input(INPUT_GET, 'res');
        // $uamip = filter_input(INPUT_GET, 'uamip');
        // $uamport = filter_input(INPUT_GET, 'uamport');
        // $ap_mac = filter_input(INPUT_GET, 'called');
        // $customer_mac = filter_input(INPUT_GET, 'mac');
        // $qs = filter_input(INPUT_SERVER, 'QUERY_STRING');
        // $uamsecret = 'usman123'; //Shared secret between CoovaChilli and CaptivePortal
        
        $challenge = $request->get('challenge');
        $userurl = $request->get('userurl');
        $res = $request->get('res');
        $uamip = $request->get('uamip');
        $uamport = $request->get('uamport');
        $ap_mac = $request->get('called');
        $customer_mac = $request->get('mac');
        // $qs = filter_input(INPUT_SERVER, 'QUERY_STRING');
        $uamsecret = 'usman123'; //Shared secret between CoovaChilli and CaptivePortal

        session_start();
        $_SESSION["uamsecret"] = $uamsecret;
        $_SESSION["challenge"] = $challenge;
        $_SESSION["uamip"] = $uamip;
        $_SESSION["uamport"] = $uamport;
        $_SESSION["userurl"] = $userurl;
        $_SESSION["ap_mac"] = $ap_mac;
        $_SESSION["customer_mac"] = $customer_mac;        
        
        
        
      $item = SplashPage::find($id);
      if ($item == null)
        return array('code' => 0, 'message' => 'Invalid Request');

      $fields = null;
      if ($item->primary_access_id == 8) {
        $form = RegistrationForm::where('splash_page_id', $item->id)->first();
        if ($form) {
          $fields = RegistrationField::where('form_id', $form->id)->get();
        }
      }

      return view('network/login', [
        'splash' => $item,
        'fields' => $fields,
      ]);
    }

    public function doLogin(Request $request, $id)
    {
//        351871265206264
      $item = SplashPage::find($id);
      if ($item == null)
        return array('code' => 0, 'message' => 'Invalid Request');

      if ($item->primary_access_id == 1) {
        $password = $request->get('password');
        if ($password == $item->password) {
          $location = Location::find($item->location_id);
          if ($location == null)
            return array('code' => 0, 'message' => 'Invalid Request');

          $guest = Guest::create();
          $guest->location_id = $location->id;
          $guest->location_name = $location->location_name;
          $guest->username = 'Guest-' . $guest->id;
          $guest->save();

          return array('code' => 0, 'message' => 'Login Success');
        } else {
          return array('code' => 0, 'message' => 'Invalid Password');
        }
      } else if ($item->primary_access_id == 2) {
        $username = $request->get('username');
        $password = $request->get('password');
        $voucherCode = VoucherCode::where([
          ['splash_page_id', $id],
          ['username', $username],
          ['password', $password],
        ])->first();

        if ($voucherCode) {
          $location = Location::find($item->location_id);
          if ($location == null)
            return array('code' => 0, 'message' => 'Invalid Request');

          $guest = Guest::create();
          $guest->location_id = $location->id;
          $guest->location_name = $location->location_name;
          $guest->username = $username;
          $guest->save();

          return array('code' => 0, 'message' => 'Login Success');
        } else {
          return array('code' => 0, 'message' => 'Invalid Credentials');
        }
      } else if ($item->primary_access_id == 8) {
        $location = Location::find($item->location_id);
        if ($location == null)
          return array('code' => 0, 'message' => 'Invalid Request');

        $input = $request->all();
        $guest = Guest::create();
        $guest->location_id = $location->id;
        $guest->location_name = $location->location_name;
        $guest->username = isset($input['username']) ? $input['username'] : 'Guest-' . $guest->id;
        $guest->save();

        foreach ($input as $key => $value) {
          if ($key == '_token' || $key == 'preview') continue;

          RegistrationData::create([
            'guest_id' => $guest->id,
            'name' => $key,
            'value' => $value,
          ]);
        }

        return array('code' => 0, 'message' => 'Registration Success');
      }
    }

    public function getSocialRedirect($id, Request $request, $provider)
    {
        $customProvider = GlobalService::getSocialiteProvider($id, $provider);

        if(!$customProvider)
            return array('code' => 0, 'message' => 'No such provider');

        return $customProvider->with(['state' => $id])->redirect();
    }

    public function getSocialHandle( $provider, Request $request )
    {
        
        $splash_page_id = request()->input('state');
        $item = SplashPage::find($splash_page_id);
        if ($item == null)
            return array('code' => 0, 'message' => 'Invalid Request');

        $location = Location::find($item->location_id);
        if ($location == null)
            return array('code' => 0, 'message' => 'Invalid Request');

        $customProvider = GlobalService::getSocialiteProvider($splash_page_id, $provider);

        $guest = $customProvider->stateless()->user();

        $guest_check = Guest::where('email', '=', $guest->email)->first();
        if(empty($guest_check))
        {
            $new_social_guest = new Guest;
            $new_social_guest->location_id = $location->id;
            $new_social_guest->location_name = $location->location_name;
            $new_social_guest->email = $guest->email;
            $new_social_guest->pic_url = $guest->getAvatar();
            $name = explode(' ', $guest->name);
		            if ($guest->email) {
			            $new_social_guest->username = $guest->email;
		            } else {
			            $new_social_guest->username = $name[0];
		            }

            $new_social_guest->save();
        }
        $social_check = Social::where('email', $guest->email)
            ->where('provider', $provider)
            ->first();
        if(!$social_check){
            $social_check = new Social;
        }
        $social_check->email = isset($guest->email) ? $guest->email : "";
        $social_check->provider = $provider;
        $social_check->user_id = isset($guest->id) ? $guest->id : "";
        $social_check->full_name = isset($guest->name) ? $guest->name : "";
        $social_check->gender = isset($guest->user['gender']) ? $guest->user['gender'] : "";
        $social_check->profile_pic = isset($guest->avatar) ? $guest->avatar : "";
        $social_check->profile_url = isset($guest->profileUrl) ? $guest->profileUrl : "";
        $social_check->save();
        
        session_start();
        // Redirect to another page after sucess to social login    
        $username = $social_check->email ? $social_check->email : $social_check->user_id;
        $uamip = isset($_SESSION["uamip"]) ? $_SESSION["uamip"] : 'empty';
        $uamport = isset($_SESSION["uamport"]) ? $_SESSION["uamport"] : 'empty';
        $uamsecret = isset($_SESSION["uamsecret"]) ? $_SESSION["uamsecret"] : 'empty';
        $challenge = isset($_SESSION["challenge"]) ? $_SESSION["challenge"] : 'empty';
        $userurl = isset($_SESSION["userurl"]) ? $_SESSION["userurl"] : 'empty';
        $redir = isset($_SESSION["redurl"]) ? $_SESSION["redurl"] : 'http://fabrica.com.eg';
        $sm_password = "1456";

        if ($uamip != "") {
            $dir = '/logon';
            $enc_pwd = GlobalService::encryptPassword($sm_password, $challenge, $uamsecret);
            $target = "http://$uamip" . ':' . $uamport . $dir . "?username=$username&password=$enc_pwd&userurl=$redir";
            header("Location: $target");
            exit;
        }
        
        return array('code' => 0, 'message' => 'Registration Success');
    }
}
