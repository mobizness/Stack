<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\SplashPage;
use App\Network;
use App\Location;
use App\AccessType;
use App\SplashAssociation;
use App\RegistrationForm;
use App\RegistrationField;

class SplashPagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($location_id)
    {
      $data = array();

      $data['splash_pages'] = array();
      $splashPages = SplashPage::all();
      foreach ($splashPages as $splashPage) {
        $item = $splashPage->toArray();
        $item['created_at'] = $splashPage->created_at->timestamp;
        $item['updated_at'] = $splashPage->updated_at->timestamp;
        $item['available_days'] = array();
        $item['available_start'] = '00:00';
        $item['available_end'] = '00:00';

        $accessType = AccessType::find($splashPage->primary_access_id);
        if ($accessType)
          $item['primary_access'] = $accessType->name;

        $item['networks'] = array();
        $associations = SplashAssociation::where('splash_page_id', $splashPage->id)->get();
        foreach ($associations as $association) {
          $network = Network::find($association->network_id);
          if ($network)
            $item['networks'][] = $network->ssid;
        }

        $data['splash_pages'][] = $item;
      }

      $data['access_types'] = array();
      $accessTypes = AccessType::all();
      foreach ($accessTypes as $accessType ) {
        $item = array();
        $item['id'] = $accessType->id;
        $item['name'] = $accessType->name;
        $data['access_types'][] = $item;
      }

      $data['location'] = array();
      $data['location']['slug'] = '';

      return $data;
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
      $splashPage = $request->input('splash_page');
      $location = Location::find($location_id);
      if (!isset($splashPage) || !isset($location)) {
        return array('code' => 0, 'message' => 'Invalid Request');
      }

      $description = isset($splashPage['description']) ? $splashPage['description'] : '';
      $splash_name = isset($splashPage['splash_name']) ? $splashPage['splash_name'] : '';
      $unique_id = isset($splashPage['unique_id']) ? $splashPage['unique_id'] : 0;
      $header_image_name = isset($splashPage['header_image_name']) ? $splashPage['header_image_name'] : '';
      $active = isset($splashPage['active']) ? $splashPage['active'] : 1;
      $primary_access_id = isset($splashPage['primary_access_id']) ? $splashPage['primary_access_id'] : 0;
      $passwd_auto_gen = isset($splashPage['passwd_auto_gen']) ? $splashPage['passwd_auto_gen'] : 0;
      $access_restrict = isset($splashPage['access_restrict']) ? $splashPage['access_restrict'] : '';
      $access_restrict_period = isset($splashPage['access_restrict_period']) ? $splashPage['access_restrict_period'] : '';
      $access_restrict_mins = isset($splashPage['access_restrict_mins']) ? $splashPage['access_restrict_mins'] : 0;
      $access_restrict_down = isset($splashPage['access_restrict_down']) ? $splashPage['access_restrict_down'] : 0;
      $access_restrict_up = isset($splashPage['access_restrict_up']) ? $splashPage['access_restrict_up'] : 0;
      $download_speed = isset($splashPage['download_speed']) ? $splashPage['download_speed'] : 0;
      $upload_speed = isset($splashPage['upload_speed']) ? $splashPage['upload_speed'] : 0;
      $weight = isset($splashPage['weight']) ? $splashPage['weight'] : 100;
      $shop_active = isset($splashPage['shop_active']) ? $splashPage['shop_active'] : 0;
      $success_url = isset($splashPage['success_url']) ? $splashPage['success_url'] : '';
      $newsletter_active = isset($splashPage['newsletter_active']) ? $splashPage['newsletter_active'] : 0;
      $vsg_enabled = isset($splashPage['vsg_enabled']) ? $splashPage['vsg_enabled'] : 0;
      $debug = isset($splashPage['debug']) ? $splashPage['debug'] : 0;
      $display_console = isset($splashPage['display_console']) ? $splashPage['display_console'] : 0;
      $allow_registration = isset($splashPage['allow_registration']) ? $splashPage['allow_registration'] : 0;
      $auto_login = isset($splashPage['auto_login']) ? $splashPage['auto_login'] : 0;
      $auto_login_timeout = isset($splashPage['auto_login_timeout']) ? $splashPage['auto_login_timeout'] : 0;
      $available_days = isset($splashPage['available_days']) ? $splashPage['available_days'] : null;
      $available_start = isset($splashPage['available_start']) ? $splashPage['available_start'] : '';
      $available_end = isset($splashPage['available_end']) ? $splashPage['available_end'] : '';
      $backup_email = isset($splashPage['backup_email']) ? $splashPage['backup_email'] : 0;
      $backup_vouchers = isset($splashPage['backup_vouchers']) ? $splashPage['backup_vouchers'] : 0;
      $blacklisted = isset($splashPage['blacklisted']) ? $splashPage['blacklisted'] : null;
      $bypass_popup_android = isset($splashPage['bypass_popup_android']) ? $splashPage['bypass_popup_android'] : 0;
      $bypass_popup_ios = isset($splashPage['bypass_popup_ios']) ? $splashPage['bypass_popup_ios'] : 0;
      $email_required = isset($splashPage['email_required']) ? $splashPage['email_required'] : 0;
      $fb_app_id = isset($splashPage['fb_app_id']) ? $splashPage['fb_app_id'] : '';
      $fb_app_secret = isset($splashPage['fb_app_secret']) ? $splashPage['fb_app_secret'] : '';
      $fb_checkin = isset($splashPage['fb_checkin']) ? $splashPage['fb_checkin'] : 0;
      $fb_checkin_msg = isset($splashPage['fb_checkin_msg']) ? $splashPage['fb_checkin_msg'] : '';
      $fb_link = isset($splashPage['fb_link']) ? $splashPage['fb_link'] : '';
      $fb_login_on = isset($splashPage['fb_login_on']) ? $splashPage['fb_login_on'] : 0;
      $fb_msg = isset($splashPage['fb_msg']) ? $splashPage['fb_msg'] : 0;
      $fb_page_id = isset($splashPage['fb_page_id']) ? $splashPage['fb_page_id'] : '';
      $fb_redirect_to_page = isset($splashPage['fb_redirect_to_page']) ? $splashPage['fb_redirect_to_page'] : 0;
      $fb_use_ps = isset($splashPage['fb_use_ps']) ? $splashPage['fb_use_ps'] : 0;
      $forgot_password_link = isset($splashPage['forgot_password_link']) ? $splashPage['forgot_password_link'] : 0;
      $g_api_key = isset($splashPage['g_api_key']) ? $splashPage['g_api_key'] : '';
      $g_login_on = isset($splashPage['g_login_on']) ? $splashPage['g_login_on'] : 0;
      $g_page_id = isset($splashPage['g_page_id']) ? $splashPage['g_page_id'] : '';
      $g_redirect_to_page = isset($splashPage['g_redirect_to_page']) ? $splashPage['g_redirect_to_page'] : 0;
      $g_use_ps = isset($splashPage['g_use_ps']) ? $splashPage['g_use_ps'] : 0;
      $google_analytics_id = isset($splashPage['google_analytics_id']) ? $splashPage['google_analytics_id'] : '';
      $idle_timeout = isset($splashPage['idle_timeout']) ? $splashPage['idle_timeout'] : 60;
      $li_api_key = isset($splashPage['li_api_key']) ? $splashPage['li_api_key'] : '';
      $li_api_secret = isset($splashPage['li_api_secret']) ? $splashPage['li_api_secret'] : '';
      $li_login_on = isset($splashPage['li_login_on']) ? $splashPage['li_login_on'] : 0;
      $li_use_ps = isset($splashPage['li_use_ps']) ? $splashPage['li_use_ps'] : 0;
      $merge_fields = isset($splashPage['merge_fields']) ? $splashPage['merge_fields'] : 0;
      $network_ids = isset($splashPage['network_ids']) ? $splashPage['network_ids'] : 0;
      $networks = isset($splashPage['networks']) ? $splashPage['networks'] : null;
      $newsletter_api_token = isset($splashPage['newsletter_api_token']) ? $splashPage['newsletter_api_token'] : '';
      $newsletter_checked = isset($splashPage['newsletter_checked']) ? $splashPage['newsletter_checked'] : 0;
      $newsletter_list_id = isset($splashPage['newsletter_list_id']) ? $splashPage['newsletter_list_id'] : '';
      $newsletter_type = isset($splashPage['newsletter_type']) ? $splashPage['newsletter_type'] : 0;
      $paid_user = isset($splashPage['paid_user']) ? $splashPage['paid_user'] : 0;
      $passwd_change_day = isset($splashPage['passwd_change_day']) ? $splashPage['passwd_change_day'] : null;
      $passwd_change_email = isset($splashPage['passwd_change_email']) ? $splashPage['passwd_change_email'] : null;
      $password = isset($splashPage['password']) ? $splashPage['password'] : '123456';
      $periodic_days = isset($splashPage['periodic_days']) ? $splashPage['periodic_days'] : null;
      $powered_by = isset($splashPage['powered_by']) ? $splashPage['powered_by'] : 0;
      $quota_message = isset($splashPage['quota_message']) ? $splashPage['quota_message'] : null;
      $registered_access_id = isset($splashPage['registered_access_id']) ? $splashPage['registered_access_id'] : null;
      $remove_registration_link = isset($splashPage['remove_registration_link']) ? $splashPage['remove_registration_link'] : 0;
      $session_timeout = isset($splashPage['session_timeout']) ? $splashPage['session_timeout'] : 60;
      $simultaneous_use = isset($splashPage['simultaneous_use']) ? $splashPage['simultaneous_use'] : 0;
      $single_opt_in = isset($splashPage['single_opt_in']) ? $splashPage['single_opt_in'] : 0;
      $skip_user_registration = isset($splashPage['skip_user_registration']) ? $splashPage['skip_user_registration'] : 0;
      $splash_tag = isset($splashPage['splash_tag']) ? $splashPage['splash_tag'] : '';
      $store_buy_link_text = isset($splashPage['store_buy_link_text']) ? $splashPage['store_buy_link_text'] : '';
      $tagged_logins = isset($splashPage['tagged_logins']) ? $splashPage['tagged_logins'] : 0;
      $timeout = isset($splashPage['timeout']) ? $splashPage['timeout'] : 0;
      $timezone = isset($splashPage['timezone']) ? $splashPage['timezone'] : '';
      $unified_login_code = isset($splashPage['unified_login_code']) ? $splashPage['unified_login_code'] : null;
      $userdays = isset($splashPage['userdays']) ? $splashPage['userdays'] : null;
      $vsg_async = isset($splashPage['vsg_async']) ? $splashPage['vsg_async'] : 0;
      $vsg_host = isset($splashPage['vsg_host']) ? $splashPage['vsg_host'] : '';
      $vsg_pass = isset($splashPage['vsg_pass']) ? $splashPage['vsg_pass'] : '';
      $vsg_port = isset($splashPage['vsg_port']) ? $splashPage['vsg_port'] : 0;
      $walled_gardens = isset($splashPage['walled_gardens']) ? $splashPage['walled_gardens'] : '';
      $walled_gardens_array = isset($splashPage['walled_gardens_array']) ? $splashPage['walled_gardens_array'] : null;
      $whitelisted = isset($splashPage['whitelisted']) ? $splashPage['whitelisted'] : null;

      $item = SplashPage::create([
        'location_id' => $location_id,
        'description' => $description,
        'splash_name' => $splash_name,
        'unique_id' => $unique_id,
        'design_id' => 1,
        'header_image_name' => $header_image_name,
        'active' => $active,
        'primary_access_id' => $primary_access_id,
        'passwd_auto_gen' => $passwd_auto_gen,
        'access_restrict' => $access_restrict,
        'access_restrict_period' => $access_restrict_period,
        'access_restrict_mins' => $access_restrict_mins,
        'access_restrict_down' => $access_restrict_down,
        'access_restrict_up' => $access_restrict_up,
        'download_speed' => $download_speed,
        'upload_speed' => $upload_speed,
        'weight' => $weight,
        'shop_active' => $shop_active,
        'success_url' => $success_url,
        'newsletter_active' => $newsletter_active,
        'vsg_enabled' => $vsg_enabled,
        'debug' => $debug,
        'display_console' => $display_console,
        'allow_registration' => $allow_registration,
        'auto_login' => $auto_login,
        'auto_login_timeout' => $auto_login_timeout,
        'available_start' => $available_start,
        'available_end' => $available_end,
        'backup_email' => $backup_email,
        'backup_vouchers' => $backup_vouchers,
        'bypass_popup_android' => $bypass_popup_android,
        'bypass_popup_ios' => $bypass_popup_ios,
        'email_required' => $email_required,
        'fb_app_id' => $fb_app_id,
        'fb_app_secret' => $fb_app_secret,
        'fb_checkin' => $fb_checkin,
        'fb_checkin_msg' => $fb_checkin_msg,
        'fb_link' => $fb_link,
        'fb_login_on' => $fb_login_on,
        'fb_msg' => $fb_msg,
        'fb_page_id' => $fb_page_id,
        'fb_redirect_to_page' => $fb_redirect_to_page,
        'fb_use_ps' => $fb_use_ps,
        'forgot_password_link' => $forgot_password_link,
        'g_api_key' => $g_api_key,
        'g_login_on' => $g_login_on,
        'g_page_id' => $g_page_id,
        'g_redirect_to_page' => $g_redirect_to_page,
        'g_use_ps' => $g_use_ps,
        'google_analytics_id' => $google_analytics_id,
        'idle_timeout' => $idle_timeout,
        'li_api_key' => $li_api_key,
        'li_api_secret' => $li_api_secret,
        'li_login_on' => $li_login_on,
        'li_use_ps' => $li_use_ps,
        'merge_fields' => $merge_fields,
        'newsletter_api_token' => $newsletter_api_token,
        'newsletter_checked' => $newsletter_checked,
        'newsletter_list_id' => $newsletter_list_id,
        'newsletter_type' => $newsletter_type,
        'paid_user' => $paid_user,
        'password' => $password,
        'powered_by' => $powered_by,
        'remove_registration_link' => $remove_registration_link,
        'session_timeout' => $session_timeout,
        'simultaneous_use' => $simultaneous_use,
        'single_opt_in' => $single_opt_in,
        'skip_user_registration' => $skip_user_registration,
        'splash_tag' => $splash_tag,
        'store_buy_link_text' => $store_buy_link_text,
        'tagged_logins' => $tagged_logins,
        'timeout' => $timeout,
        'timezone' => $timezone,
        'vsg_async' => $vsg_async,
        'vsg_host' => $vsg_host,
        'vsg_pass' => $vsg_pass,
        'vsg_port' => $vsg_port,
        'walled_gardens' => $walled_gardens,
        'header_image_type' => 1,
        'header_text' => 'Welcome to ' . $location->location_name,
        'info' => "Who in the world am I? Ah, that's the great puzzle.",
        'container_width' => '850px',
        'container_text_align' => 'center',
        'body_background_colour' => '#FFFFFF',
      	'heading_text_colour' => '#000000',
      	'body_text_colour' => '#333333',
      	'border_colour' => '#CCCCCC',
      	'link_colour' => '#2B68B6',
      	'container_colour' => '#FFFFFF',
      	'button_colour' => '#FFFFFF',
        'button_radius' => '0px',
        'button_border_colour' => '#000',
        'button_padding' => '0px 16px',
      	'header_colour' => '#FFFFFF',
      	'error_colour' => '#ED561B',
      	'container_transparency' => 0.8,
      	'container_float' => 'center',
      	'container_inner_width' => '100%',
        'container_inner_padding' => '10px',
      	'bg_dimension' => 'full',
      	'words_position' => 'right',
      	'logo_position' => 'left',
        'hide_terms' => 0,
      	'font_family' => "'Helvetica Neue', Arial, Helvetica, sans-serif",
      	'body_font_size' => '14px',
      	'heading_text_size' => '22px',
      	'heading_2_text_size' => '16px',
      	'heading_2_text_colour' => '#000000',
      	'heading_3_text_size' => '14px',
      	'heading_3_text_colour' => '#000000',
      	'btn_text' => 'Login Now',
        'reg_btn_text' => 'Register',
      	'btn_font_size' => '18px',
      	'btn_font_colour' => '#000000',
        'input_required_colour' => '#CCC',
        'input_required_size' => '10px',
        'input_height' => '40px',
        'input_padding' => '10px 15px',
        'input_border_colour' => '#D0D0D0',
        'input_border_radius' => '0px',
        'input_border_width' => '1px',
        'input_background' => '#FFFFFF',
        'input_text_colour' => '#3D3D3D',
        'input_max_width' => '400px',
        'footer_text_colour' => '#CCC',
      ]);

      $data = $item->toArray();
      $data['created_at'] = $item->created_at->timestamp;
      $data['updated_at'] = $item->updated_at->timestamp;

      $accessType = AccessType::find($item->primary_access_id);
      if ($accessType)
        $data['primary_access'] = $accessType->name;

      $ssid = isset($splashPage['ssid']) ? $splashPage['ssid'] : null;
      if ($ssid != null) {
        $network = Network::create();
        $network->network_name = $ssid;
        $network->ssid = $ssid;
        $network->location_id = $location_id;
        $network->save();
        $network_ids = $network->id;
      }

      if ($network_ids != 0) {
        SplashAssociation::create([
          'splash_page_id' => $item->id,
          'network_id' => $network_ids
        ]);
      }

      $data['networks'] = array();
      $associations = SplashAssociation::where('splash_page_id', $item->id)->get();
      foreach ($associations as $association) {
        $network = Network::find($association->network_id);
        if ($network)
          $data['networks'][] = $network->ssid;
      }

      $form = RegistrationForm::create([
        'splash_page_id' => $item->id,
        'message' => 'Please enter your details to get online',
      ]);

      RegistrationField::create([
        'form_id' => $form->id,
        'label' => 'Password',
        'field_type' => 'password',
        'name' => 'password',
        'required' => 1,
        'order' => 0,
        'hidden' => 0,
        'value' => '',
      ]);

      RegistrationField::create([
        'form_id' => $form->id,
        'label' => 'Email',
        'field_type' => 'email',
        'name' => 'email',
        'required' => 1,
        'order' => 1,
        'hidden' => 0,
        'value' => '',
      ]);

      return $data;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($location_id, $id)
    {
      $item = SplashPage::find($id);
      if ($item == null)
        return array('code' => 0, 'message' => 'Invalid Request');

      $data = array();
      $data['splash_page'] = $item->toArray();
      $data['splash_page']['created_at'] = $item->created_at->timestamp;
      $data['splash_page']['updated_at'] = $item->updated_at->timestamp;

      $data['splash_page']['networks'] = array();
      $associations = SplashAssociation::where('splash_page_id', $item->id)->get();
      foreach ($associations as $association) {
        $data['splash_page']['networks'][] = $association->network_id;
      }

      $data['access_types'] = array();
      $accessTypes = AccessType::all();
      foreach ($accessTypes as $accessType ) {
        $item2 = array();
        $item2['id'] = $accessType->id;
        $item2['name'] = $accessType->name;
        $data['access_types'][] = $item2;
      }

      $data['networks'] = array();
      $networks = Network::where('location_id', $location_id)->get();
      foreach ($networks as $network) {
        $item3 = array();
        $item3['id'] = $network->id;
        $item3['ssid'] = $network->ssid;
        $data['networks'][] = $item3;
      }

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
    public function update($location_id, Request $request, $id)
    {
      $item = SplashPage::find($id);
      $location = Location::find($location_id);

      if (!isset($item) || !isset($location)) {
        return array('code' => 0, 'message' => 'Invalid Request');
      }

      $splashPage = $request->input('splash_page');
      if (!isset($splashPage)) {
        return array('code' => 0, 'message' => 'Invalid Request');
      }

      $description = isset($splashPage['description']) ? $splashPage['description'] : '';
      $splash_name = isset($splashPage['splash_name']) ? $splashPage['splash_name'] : '';
      $unique_id = isset($splashPage['unique_id']) ? $splashPage['unique_id'] : 0;
      $header_image_name = isset($splashPage['header_image_name']) ? $splashPage['header_image_name'] : '';
      $active = isset($splashPage['active']) ? $splashPage['active'] : 1;
      $primary_access_id = isset($splashPage['primary_access_id']) ? $splashPage['primary_access_id'] : 0;
      $passwd_auto_gen = isset($splashPage['passwd_auto_gen']) ? $splashPage['passwd_auto_gen'] : 0;
      $access_restrict = isset($splashPage['access_restrict']) ? $splashPage['access_restrict'] : '';
      $access_restrict_period = isset($splashPage['access_restrict_period']) ? $splashPage['access_restrict_period'] : '';
      $access_restrict_mins = isset($splashPage['access_restrict_mins']) ? $splashPage['access_restrict_mins'] : 0;
      $access_restrict_down = isset($splashPage['access_restrict_down']) ? $splashPage['access_restrict_down'] : 0;
      $access_restrict_up = isset($splashPage['access_restrict_up']) ? $splashPage['access_restrict_up'] : 0;
      $download_speed = isset($splashPage['download_speed']) ? $splashPage['download_speed'] : 0;
      $upload_speed = isset($splashPage['upload_speed']) ? $splashPage['upload_speed'] : 0;
      $weight = isset($splashPage['weight']) ? $splashPage['weight'] : 100;
      $shop_active = isset($splashPage['shop_active']) ? $splashPage['shop_active'] : 0;
      $success_url = isset($splashPage['success_url']) ? $splashPage['success_url'] : '';
      $newsletter_active = isset($splashPage['newsletter_active']) ? $splashPage['newsletter_active'] : 0;
      $vsg_enabled = isset($splashPage['vsg_enabled']) ? $splashPage['vsg_enabled'] : 0;
      $debug = isset($splashPage['debug']) ? $splashPage['debug'] : 0;
      $display_console = isset($splashPage['display_console']) ? $splashPage['display_console'] : 0;
      $allow_registration = isset($splashPage['allow_registration']) ? $splashPage['allow_registration'] : 0;
      $auto_login = isset($splashPage['auto_login']) ? $splashPage['auto_login'] : 0;
      $auto_login_timeout = isset($splashPage['auto_login_timeout']) ? $splashPage['auto_login_timeout'] : 0;
      $available_days = isset($splashPage['available_days']) ? $splashPage['available_days'] : null;
      $available_start = isset($splashPage['available_start']) ? $splashPage['available_start'] : '';
      $available_end = isset($splashPage['available_end']) ? $splashPage['available_end'] : '';
      $backup_email = isset($splashPage['backup_email']) ? $splashPage['backup_email'] : 0;
      $backup_vouchers = isset($splashPage['backup_vouchers']) ? $splashPage['backup_vouchers'] : 0;
      $blacklisted = isset($splashPage['blacklisted']) ? $splashPage['blacklisted'] : null;
      $bypass_popup_android = isset($splashPage['bypass_popup_android']) ? $splashPage['bypass_popup_android'] : 0;
      $bypass_popup_ios = isset($splashPage['bypass_popup_ios']) ? $splashPage['bypass_popup_ios'] : 0;
      $email_required = isset($splashPage['email_required']) ? $splashPage['email_required'] : 0;
      $fb_app_id = isset($splashPage['fb_app_id']) ? $splashPage['fb_app_id'] : '';
      $fb_app_secret = isset($splashPage['fb_app_secret']) ? $splashPage['fb_app_secret'] : '';
      $fb_checkin = isset($splashPage['fb_checkin']) ? $splashPage['fb_checkin'] : 0;
      $fb_checkin_msg = isset($splashPage['fb_checkin_msg']) ? $splashPage['fb_checkin_msg'] : '';
      $fb_link = isset($splashPage['fb_link']) ? $splashPage['fb_link'] : '';
      $fb_login_on = isset($splashPage['fb_login_on']) ? $splashPage['fb_login_on'] : 0;
      $fb_msg = isset($splashPage['fb_msg']) ? $splashPage['fb_msg'] : 0;
      $fb_page_id = isset($splashPage['fb_page_id']) ? $splashPage['fb_page_id'] : '';
      $fb_redirect_to_page = isset($splashPage['fb_redirect_to_page']) ? $splashPage['fb_redirect_to_page'] : 0;
      $fb_use_ps = isset($splashPage['fb_use_ps']) ? $splashPage['fb_use_ps'] : 0;
      $forgot_password_link = isset($splashPage['forgot_password_link']) ? $splashPage['forgot_password_link'] : 0;
      $g_api_key = isset($splashPage['g_api_key']) ? $splashPage['g_api_key'] : '';
      $g_login_on = isset($splashPage['g_login_on']) ? $splashPage['g_login_on'] : 0;
      $g_page_id = isset($splashPage['g_page_id']) ? $splashPage['g_page_id'] : '';
      $g_redirect_to_page = isset($splashPage['g_redirect_to_page']) ? $splashPage['g_redirect_to_page'] : 0;
      $g_use_ps = isset($splashPage['g_use_ps']) ? $splashPage['g_use_ps'] : 0;
      $google_analytics_id = isset($splashPage['google_analytics_id']) ? $splashPage['google_analytics_id'] : '';
      $idle_timeout = isset($splashPage['idle_timeout']) ? $splashPage['idle_timeout'] : 60;
      $li_api_key = isset($splashPage['li_api_key']) ? $splashPage['li_api_key'] : '';
      $li_api_secret = isset($splashPage['li_api_secret']) ? $splashPage['li_api_secret'] : '';
      $li_login_on = isset($splashPage['li_login_on']) ? $splashPage['li_login_on'] : 0;
      $li_use_ps = isset($splashPage['li_use_ps']) ? $splashPage['li_use_ps'] : 0;
      $merge_fields = isset($splashPage['merge_fields']) ? $splashPage['merge_fields'] : 0;
      $network_ids = isset($splashPage['network_ids']) ? $splashPage['network_ids'] : null;
      $networks = isset($splashPage['networks']) ? $splashPage['networks'] : null;
      $newsletter_api_token = isset($splashPage['newsletter_api_token']) ? $splashPage['newsletter_api_token'] : '';
      $newsletter_checked = isset($splashPage['newsletter_checked']) ? $splashPage['newsletter_checked'] : 0;
      $newsletter_list_id = isset($splashPage['newsletter_list_id']) ? $splashPage['newsletter_list_id'] : '';
      $newsletter_type = isset($splashPage['newsletter_type']) ? $splashPage['newsletter_type'] : 0;
      $paid_user = isset($splashPage['paid_user']) ? $splashPage['paid_user'] : 0;
      $passwd_change_day = isset($splashPage['passwd_change_day']) ? $splashPage['passwd_change_day'] : null;
      $passwd_change_email = isset($splashPage['passwd_change_email']) ? $splashPage['passwd_change_email'] : null;
      $password = isset($splashPage['password']) ? $splashPage['password'] : '';
      $periodic_days = isset($splashPage['periodic_days']) ? $splashPage['periodic_days'] : null;
      $powered_by = isset($splashPage['powered_by']) ? $splashPage['powered_by'] : 0;
      $quota_message = isset($splashPage['quota_message']) ? $splashPage['quota_message'] : null;
      $registered_access_id = isset($splashPage['registered_access_id']) ? $splashPage['registered_access_id'] : null;
      $remove_registration_link = isset($splashPage['remove_registration_link']) ? $splashPage['remove_registration_link'] : 0;
      $session_timeout = isset($splashPage['session_timeout']) ? $splashPage['session_timeout'] : 60;
      $simultaneous_use = isset($splashPage['simultaneous_use']) ? $splashPage['simultaneous_use'] : 0;
      $single_opt_in = isset($splashPage['single_opt_in']) ? $splashPage['single_opt_in'] : 0;
      $skip_user_registration = isset($splashPage['skip_user_registration']) ? $splashPage['skip_user_registration'] : 0;
      $splash_tag = isset($splashPage['splash_tag']) ? $splashPage['splash_tag'] : '';
      $store_buy_link_text = isset($splashPage['store_buy_link_text']) ? $splashPage['store_buy_link_text'] : '';
      $tagged_logins = isset($splashPage['tagged_logins']) ? $splashPage['tagged_logins'] : 0;
      $timeout = isset($splashPage['timeout']) ? $splashPage['timeout'] : 0;
      $timezone = isset($splashPage['timezone']) ? $splashPage['timezone'] : '';
      $unified_login_code = isset($splashPage['unified_login_code']) ? $splashPage['unified_login_code'] : null;
      $userdays = isset($splashPage['userdays']) ? $splashPage['userdays'] : null;
      $vsg_async = isset($splashPage['vsg_async']) ? $splashPage['vsg_async'] : 0;
      $vsg_host = isset($splashPage['vsg_host']) ? $splashPage['vsg_host'] : '';
      $vsg_pass = isset($splashPage['vsg_pass']) ? $splashPage['vsg_pass'] : '';
      $vsg_port = isset($splashPage['vsg_port']) ? $splashPage['vsg_port'] : 0;
      $walled_gardens = isset($splashPage['walled_gardens']) ? $splashPage['walled_gardens'] : '';
      $walled_gardens_array = isset($splashPage['walled_gardens_array']) ? $splashPage['walled_gardens_array'] : null;
      $whitelisted = isset($splashPage['whitelisted']) ? $splashPage['whitelisted'] : null;
      $design_id = isset($splashPage['design_id']) ? $splashPage['design_id'] : 1;
      $terms_url = isset($splashPage['terms_url']) ? $splashPage['terms_url'] : '';
      $logo_file_name = isset($splashPage['logo_file_name']) ? $splashPage['logo_file_name'] : null;
      $background_image_name = isset($splashPage['background_image_name']) ? $splashPage['background_image_name'] : null;
      $location_image_name = isset($splashPage['location_image_name']) ? $splashPage['location_image_name'] : null;
      $location_image_name_svg = isset($splashPage['location_image_name_svg']) ? $splashPage['location_image_name_svg'] : null;
      $header_image_name = isset($splashPage['header_image_name']) ? $splashPage['header_image_name'] : null;
      $header_image_name_svg = isset($splashPage['header_image_name_svg']) ? $splashPage['header_image_name_svg'] : null;
      $header_image_type = isset($splashPage['header_image_type']) ? $splashPage['header_image_type'] : 0;
      $header_text = isset($splashPage['header_text']) ? $splashPage['header_text'] : '';
      $info = isset($splashPage['info']) ? $splashPage['info'] : '';
      $info_two = isset($splashPage['info_two']) ? $splashPage['info_two'] : null;
      $address = isset($splashPage['address']) ? $splashPage['address'] : '';
      $error_message_text = isset($splashPage['error_message_text']) ? $splashPage['error_message_text'] : null;
      $website = isset($splashPage['website']) ? $splashPage['website'] : null;
      $facebook_name = isset($splashPage['facebook_name']) ? $splashPage['facebook_name'] : null;
      $twitter_name = isset($splashPage['twitter_name']) ? $splashPage['twitter_name'] : null;
      $google_name = isset($splashPage['google_name']) ? $splashPage['google_name'] : null;
      $linkedin_name = isset($splashPage['linkedin_name']) ? $splashPage['linkedin_name'] : null;
      $instagram_name = isset($splashPage['instagram_name']) ? $splashPage['instagram_name'] : null;
      $pinterest_name = isset($splashPage['pinterest_name']) ? $splashPage['pinterest_name'] : null;
      $container_width = isset($splashPage['container_width']) ? $splashPage['container_width'] : '';
      $container_text_align = isset($splashPage['container_text_align']) ? $splashPage['container_text_align'] : '';
      $body_background_colour = isset($splashPage['body_background_colour']) ? $splashPage['body_background_colour'] : '';
      $heading_text_colour = isset($splashPage['heading_text_colour']) ? $splashPage['heading_text_colour'] : '';
      $body_text_colour = isset($splashPage['body_text_colour']) ? $splashPage['body_text_colour'] : '';
      $border_colour = isset($splashPage['border_colour']) ? $splashPage['border_colour'] : '';
      $link_colour = isset($splashPage['link_colour']) ? $splashPage['link_colour'] : '';
      $container_colour = isset($splashPage['container_colour']) ? $splashPage['container_colour'] : '';
      $button_colour = isset($splashPage['button_colour']) ? $splashPage['button_colour'] : '';
      $button_radius = isset($splashPage['button_radius']) ? $splashPage['button_radius'] : '';
      $button_border_colour = isset($splashPage['button_border_colour']) ? $splashPage['button_border_colour'] : '';
      $button_padding = isset($splashPage['button_padding']) ? $splashPage['button_padding'] : '';
      $header_colour = isset($splashPage['header_colour']) ? $splashPage['header_colour'] : '';
      $error_colour = isset($splashPage['error_colour']) ? $splashPage['error_colour'] : '';
      $container_transparency = isset($splashPage['container_transparency']) ? $splashPage['container_transparency'] : '';
      $container_float = isset($splashPage['container_float']) ? $splashPage['container_float'] : '';
      $container_inner_width = isset($splashPage['container_inner_width']) ? $splashPage['container_inner_width'] : '';
      $container_inner_padding = isset($splashPage['container_inner_padding']) ? $splashPage['container_inner_padding'] : '';
      $bg_dimension = isset($splashPage['bg_dimension']) ? $splashPage['bg_dimension'] : '';
      $words_position = isset($splashPage['words_position']) ? $splashPage['words_position'] : '';
      $logo_position = isset($splashPage['logo_position']) ? $splashPage['logo_position'] : '';
      $hide_terms = isset($splashPage['hide_terms']) ? $splashPage['hide_terms'] : 0;
      $font_family = isset($splashPage['font_family']) ? $splashPage['font_family'] : '';
      $body_font_size = isset($splashPage['body_font_size']) ? $splashPage['body_font_size'] : '';
      $heading_text_size = isset($splashPage['heading_text_size']) ? $splashPage['heading_text_size'] : '';
      $heading_2_text_size = isset($splashPage['heading_2_text_size']) ? $splashPage['heading_2_text_size'] : '';
      $heading_2_text_colour = isset($splashPage['heading_2_text_colour']) ? $splashPage['heading_2_text_colour'] : '';
      $heading_3_text_size = isset($splashPage['heading_3_text_size']) ? $splashPage['heading_3_text_size'] : '';
      $heading_3_text_colour = isset($splashPage['heading_3_text_colour']) ? $splashPage['heading_3_text_colour'] : '';
      $btn_text = isset($splashPage['btn_text']) ? $splashPage['btn_text'] : '';
      $reg_btn_text = isset($splashPage['reg_btn_text']) ? $splashPage['reg_btn_text'] : '';
      $btn_font_size = isset($splashPage['btn_font_size']) ? $splashPage['btn_font_size'] : '';
      $btn_font_colour = isset($splashPage['btn_font_colour']) ? $splashPage['btn_font_colour'] : '';
      $input_required_colour = isset($splashPage['input_required_colour']) ? $splashPage['input_required_colour'] : '';
      $input_required_size = isset($splashPage['input_required_size']) ? $splashPage['input_required_size'] : '';
      $welcome_text = isset($splashPage['welcome_text']) ? $splashPage['welcome_text'] : null;
      $welcome_timeout = isset($splashPage['welcome_timeout']) ? $splashPage['welcome_timeout'] : null;
      $show_welcome = isset($splashPage['show_welcome']) ? $splashPage['show_welcome'] : '';
      $external_css = isset($splashPage['external_css']) ? $splashPage['external_css'] : null;
      $custom_css = isset($splashPage['custom_css']) ? $splashPage['custom_css'] : null;
      $input_height = isset($splashPage['input_height']) ? $splashPage['input_height'] : '';
      $input_padding = isset($splashPage['input_padding']) ? $splashPage['input_padding'] : '';
      $input_border_colour = isset($splashPage['input_border_colour']) ? $splashPage['input_border_colour'] : '';
      $input_border_radius = isset($splashPage['input_border_radius']) ? $splashPage['input_border_radius'] : '';
      $input_border_width = isset($splashPage['input_border_width']) ? $splashPage['input_border_width'] : '';
      $input_background = isset($splashPage['input_background']) ? $splashPage['input_background'] : '';
      $input_text_colour = isset($splashPage['input_text_colour']) ? $splashPage['input_text_colour'] : '';
      $input_max_width = isset($splashPage['input_max_width']) ? $splashPage['input_max_width'] : '';
      $footer_text_colour = isset($splashPage['footer_text_colour']) ? $splashPage['footer_text_colour'] : '';
      $preview_mac = isset($splashPage['preview_mac']) ? $splashPage['preview_mac'] : null;

      $item->description = $description;
      $item->splash_name = $splash_name;
      $item->unique_id = $unique_id;
      $item->header_image_name = $header_image_name;
      $item->active = $active;
      $item->primary_access_id = $primary_access_id;
      $item->passwd_auto_gen = $passwd_auto_gen;
      $item->access_restrict = $access_restrict;
      $item->access_restrict_period = $access_restrict_period;
      $item->access_restrict_mins = $access_restrict_mins;
      $item->access_restrict_down = $access_restrict_down;
      $item->access_restrict_up = $access_restrict_up;
      $item->download_speed = $download_speed;
      $item->upload_speed = $upload_speed;
      $item->weight = $weight;
      $item->shop_active = $shop_active;
      $item->success_url = $success_url;
      $item->newsletter_active = $newsletter_active;
      $item->vsg_enabled = $vsg_enabled;
      $item->debug = $debug;
      $item->display_console = $display_console;
      $item->allow_registration = $allow_registration;
      $item->auto_login = $auto_login;
      $item->auto_login_timeout = $auto_login_timeout;
      $item->available_start = $available_start;
      $item->available_end = $available_end;
      $item->backup_email = $backup_email;
      $item->backup_vouchers = $backup_vouchers;
      $item->bypass_popup_android = $bypass_popup_android;
      $item->bypass_popup_ios = $bypass_popup_ios;
      $item->email_required = $email_required;
      $item->fb_app_id = $fb_app_id;
      $item->fb_app_secret = $fb_app_secret;
      $item->fb_checkin = $fb_checkin;
      $item->fb_checkin_msg = $fb_checkin_msg;
      $item->fb_link = $fb_link;
      $item->fb_login_on = $fb_login_on;
      $item->fb_msg = $fb_msg;
      $item->fb_page_id = $fb_page_id;
      $item->fb_redirect_to_page = $fb_redirect_to_page;
      $item->fb_use_ps = $fb_use_ps;
      $item->forgot_password_link = $forgot_password_link;
      $item->g_api_key = $g_api_key;
      $item->g_login_on = $g_login_on;
      $item->g_page_id = $g_page_id;
      $item->g_redirect_to_page = $g_redirect_to_page;
      $item->g_use_ps = $g_use_ps;
      $item->google_analytics_id = $google_analytics_id;
      $item->idle_timeout = $idle_timeout;
      $item->li_api_key = $li_api_key;
      $item->li_api_secret = $li_api_secret;
      $item->li_login_on = $li_login_on;
      $item->li_use_ps = $li_use_ps;
      $item->merge_fields = $merge_fields;
      $item->newsletter_api_token = $newsletter_api_token;
      $item->newsletter_checked = $newsletter_checked;
      $item->newsletter_list_id = $newsletter_list_id;
      $item->newsletter_type = $newsletter_type;
      $item->paid_user = $paid_user;
      $item->password = $password;
      $item->powered_by = $powered_by;
      $item->remove_registration_link = $remove_registration_link;
      $item->session_timeout = $session_timeout;
      $item->simultaneous_use = $simultaneous_use;
      $item->single_opt_in = $single_opt_in;
      $item->skip_user_registration = $skip_user_registration;
      $item->splash_tag = $splash_tag;
      $item->store_buy_link_text = $store_buy_link_text;
      $item->tagged_logins = $tagged_logins;
      $item->timeout = $timeout;
      $item->timezone = $timezone;
      $item->vsg_async = $vsg_async;
      $item->vsg_host = $vsg_host;
      $item->vsg_pass = $vsg_pass;
      $item->vsg_port = $vsg_port;
      $item->walled_gardens = $walled_gardens;
      $item->design_id = $design_id;
      $item->terms_url = $terms_url;
      $item->logo_file_name = $logo_file_name;
      $item->background_image_name = $background_image_name;
      $item->location_image_name = $location_image_name;
      $item->location_image_name_svg = $location_image_name_svg;
      $item->header_image_name = $header_image_name;
      $item->header_image_name_svg = $header_image_name_svg;
      $item->header_image_type = $header_image_type;
      $item->header_text = $header_text;
      $item->info = $info;
      $item->info_two = $info_two;
      $item->address = $address;
      $item->error_message_text = $error_message_text;
      $item->website = $website;
      $item->facebook_name = $facebook_name;
      $item->twitter_name = $twitter_name;
      $item->google_name = $google_name;
      $item->linkedin_name = $linkedin_name;
      $item->instagram_name = $instagram_name;
      $item->pinterest_name = $pinterest_name;
      $item->container_width = $container_width;
      $item->container_text_align = $container_text_align;
      $item->body_background_colour = $body_background_colour;
      $item->heading_text_colour = $heading_text_colour;
      $item->body_text_colour = $body_text_colour;
      $item->border_colour = $border_colour;
      $item->link_colour = $link_colour;
      $item->container_colour = $container_colour;
      $item->button_colour = $button_colour;
      $item->button_radius = $button_radius;
      $item->button_border_colour = $button_border_colour;
      $item->button_padding = $button_padding;
      $item->header_colour = $header_colour;
      $item->error_colour = $error_colour;
      $item->container_transparency = $container_transparency;
      $item->container_float = $container_float;
      $item->container_inner_width = $container_inner_width;
      $item->container_inner_padding = $container_inner_padding;
      $item->bg_dimension = $bg_dimension;
      $item->words_position = $words_position;
      $item->logo_position = $logo_position;
      $item->hide_terms = $hide_terms;
      $item->font_family = $font_family;
      $item->body_font_size = $body_font_size;
      $item->heading_text_size = $heading_text_size;
      $item->heading_2_text_size = $heading_2_text_size;
      $item->heading_2_text_colour = $heading_2_text_colour;
      $item->heading_3_text_size = $heading_3_text_size;
      $item->heading_3_text_colour = $heading_3_text_colour;
      $item->btn_text = $btn_text;
      $item->reg_btn_text = $reg_btn_text;
      $item->btn_font_size = $btn_font_size;
      $item->btn_font_colour = $btn_font_colour;
      $item->input_required_colour = $input_required_colour;
      $item->input_required_size = $input_required_size;
      $item->welcome_text = $welcome_text;
      $item->welcome_timeout = $welcome_timeout;
      $item->show_welcome = $show_welcome;
      $item->external_css = $external_css;
      $item->custom_css = $custom_css;
      $item->input_height = $input_height;
      $item->input_padding = $input_padding;
      $item->input_border_colour = $input_border_colour;
      $item->input_border_radius = $input_border_radius;
      $item->input_border_width = $input_border_width;
      $item->input_background = $input_background;
      $item->input_text_colour = $input_text_colour;
      $item->input_max_width = $input_max_width;
      $item->footer_text_colour = $footer_text_colour;
      $item->preview_mac = $preview_mac;

      $item->save();

      $arry = array();

      $data = $item->toArray();
      $data['created_at'] = $item->created_at->timestamp;
      $data['updated_at'] = $item->updated_at->timestamp;

      if ($network_ids) {
        SplashAssociation::where('splash_page_id', $item->id)->delete();
        foreach ($network_ids as $network_id) {
          SplashAssociation::create([
            'splash_page_id' => $item->id,
            'network_id' => $network_id
          ]);
        }
      }

      $data['networks'] = array();
      $associations = SplashAssociation::where('splash_page_id', $item->id)->get();
      foreach ($associations as $association) {
        $data['networks'][] = $association->network_id;
      }

      $arry['splash_page'] = $data;

      $arry['access_types'] = array();
      $accessTypes = AccessType::all();
      foreach ($accessTypes as $accessType ) {
        $item2 = array();
        $item2['id'] = $accessType->id;
        $item2['name'] = $accessType->name;
        $arry['access_types'][] = $item2;
      }

      $arry['networks'] = array();
      $networks = Network::where('location_id', $location_id)->get();
      foreach ($networks as $network) {
        $item3 = array();
        $item3['id'] = $network->id;
        $item3['ssid'] = $network->ssid;
        $arry['networks'][] = $item3;
      }

      return $arry;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($location_id, $id)
    {
      $item = SplashPage::find($id);
      if ($item == null)
        return array('code' => 0, 'message' => 'Invalid Request');

      SplashAssociation::where('splash_page_id', $item->id)->delete();

      $item->delete();
      return array('code' => 0, 'message' => 'Success');
    }
}
