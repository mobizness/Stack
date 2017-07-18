<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SplashPage extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'location_id', 'description', 'splash_name', 'unique_id', 'header_image_name', 'active',
      'primary_access_id', 'passwd_auto_gen', 'access_restrict', 'access_restrict_period', 'access_restrict_mins',
      'access_restrict_down', 'access_restrict_up', 'download_speed', 'upload_speed', 'weight', 'shop_active', 'success_url',
      'newsletter_active', 'vsg_enabled', 'debug', 'display_console', 'allow_registration', 'auto_login', 'auto_login_timeout',
      'available_start', 'available_end', 'backup_email', 'backup_vouchers', 'bypass_popup_android', 'bypass_popup_ios', 'email_required',
      'fb_app_id', 'fb_checkin', 'fb_checkin_msg', 'fb_link', 'fb_login_on', 'fb_msg', 'fb_page_id', 'fb_redirect_to_page', 'fb_use_ps',
      'forgot_password_link', 'g_api_key', 'g_login_on', 'g_page_id', 'g_redirect_to_page', 'g_use_ps', 'google_analytics_id',
      'idle_timeout', 'li_api_key', 'li_api_secret', 'li_login_on', 'li_use_ps', 'merge_fields',
      'newsletter_api_token', 'newsletter_checked', 'newsletter_list_id', 'newsletter_type', 'paid_user', 'password',
      'powered_by', 'remove_registration_link', 'session_timeout', 'simultaneous_use', 'single_opt_in', 'skip_user_registration',
      'splash_tag', 'store_buy_link_text', 'tagged_logins', 'timeout', 'timezone', 'vsg_async', 'vsg_host', 'vsg_pass', 'vsg_port',
      'walled_gardens', 'design_id', 'terms_url',
      'logo_file_name', 'background_image_name', 'location_image_name', 'location_image_name_svg', 'header_image_name_svg',
      'header_image_type', 'header_text', 'info', 'info_two', 'address',
      'error_message_text', 'website', 'facebook_name', 'twitter_name', 'google_name', 'linkedin_name', 'instagram_name', 'pinterest_name',
      'container_width', 'container_text_align', 'body_background_colour', 'heading_text_colour', 'body_text_colour', 'border_colour',
      'link_colour', 'container_colour', 'button_colour', 'button_radius', 'button_border_colour', 'button_padding', 'header_colour',
      'error_colour', 'container_transparency', 'container_float', 'container_inner_width', 'container_inner_padding',
      'bg_dimension', 'words_position', 'logo_position', 'hide_terms', 'font_family', 'body_font_size', 'heading_text_size',
      'heading_2_text_size', 'heading_2_text_colour',
      'heading_3_text_size', 'heading_3_text_colour', 'btn_text', 'reg_btn_text', 'btn_font_size', 'btn_font_colour', 'input_required_colour',
      'input_required_size', 'welcome_text', 'welcome_timeout', 'show_welcome', 'external_css', 'custom_css', 'input_height', 'input_padding',
      'input_border_colour', 'input_border_radius', 'input_border_width', 'input_background', 'input_text_colour', 'input_max_width',
      'footer_text_colour', 'preview_mac',
  ];

  /**
   * The attributes that should be casted to native types.
   *
   * @var array
   */
  protected $casts = [
      'active' => 'boolean',
      'tagged_logins' => 'boolean',
      'passwd_auto_gen' => 'boolean',
      'fb_use_ps' => 'boolean',
      'fb_login_on' => 'boolean',
      'g_login_on' => 'boolean',
      'li_login_on' => 'boolean',
      'g_use_ps' => 'boolean',
      'li_use_ps' => 'boolean',
      'g_redirect_to_page' => 'boolean',
      'fb_redirect_to_page' => 'boolean',
      'fb_checkin' => 'boolean',
      'backup_email' => 'boolean',
      'backup_vouchers' => 'boolean',
      'fb_msg' => 'boolean',
      'auto_login' => 'boolean',
      'skip_user_registration' => 'boolean',
      'powered_by' => 'boolean',
      'allow_registration' => 'boolean',
      'vsg_async' => 'boolean',
      'remove_registration_link' => 'boolean',
      'email_required' => 'boolean',
      'single_opt_in' => 'boolean',
      'merge_fields' => 'boolean',
      'newsletter_active' => 'boolean',
      'newsletter_checked' => 'boolean',
      'forgot_password_link' => 'boolean',
      'require_registration' => 'boolean',
      'paid_user' => 'boolean',
      'debug' => 'boolean',
      'display_console' => 'boolean',
      'shop_active' => 'boolean',
      'bypass_popup_ios' => 'boolean',
      'bypass_popup_android' => 'boolean',
      'hide_terms' => 'boolean',
  ];
  
    public static function getSocialiteConfig($id, $provider){
        $splash = SplashPage::find($id);
                
        $config = array(); 
        switch($provider){
            case "mailgun": 
                $config = array(
                    'domain' => env('MAILGUN_DOMAIN'),
                    'secret' => env('MAILGUN_SECRET'),
                );
            break;
            case "ses": 
                $config = array(
                    'key' => env('SES_KEY'),
                    'secret' => env('SES_SECRET'),
                    'region' => 'us-east-1',
                );
            break;
            case "sparkpost": 
                $config = array(
                    'secret' => env('SPARKPOST_SECRET'),
                );
            break;
            case "stripe": 
                $config = array(
                    'model' => App\User::class,
                    'key' => env('STRIPE_KEY'),
                    'secret' => env('STRIPE_SECRET'),
                );
            break;
            case "facebook": 
                $config = array(
                    'client_id'     => $splash->fb_app_id,
                    'client_secret' => $splash->fb_app_secret,
                    'redirect'      => env('FB_REDIRECT')
                );
            break;
            case "twitter": 
                $config = array(
                    'client_id'     => env('TW_ID'),
                    'client_secret' => env('TW_SECRET'),
                    'redirect'      => env('TW_REDIRECT')
                );
            break;
            case "google": 
                $config = array(
                    'client_id'     => env('GOOGLE_ID'),
                    'client_secret' => env('GOOGLE_SECRET'),
                    'redirect'      => env('GOOGLE_REDIRECT')
                );
            break;
            case "linkedin": 
                $config = array(
                    'client_id'     => env('LINKEDIN_ID'),
                    'client_secret' => env('LINKEDIN_SECRET'),
                    'redirect'      => env('LINKEDIN_REDIRECT')
                );
            break;
        }
        
        return $config;
    }
}
