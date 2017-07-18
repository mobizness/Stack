<!DOCTYPE html>
<html>
  <head>
    <title>DigiFi Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.1.3/material.indigo-pink.min.css">
    <script defer src="https://code.getmdl.io/1.1.3/material.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/angular-material/1.1.1/angular-material.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:300,400,500,700" type="text/css">

    <style>
      .dc {
        font-family: {{ $splash->font_family }}!important;
      }

      .dc h1 {
        font-size: {{ $splash->heading_text_size }};
        font-weight: normal;
        color: {{ $splash->heading_text_colour }};
        font-family: {{ $splash->font_family }};
      }

      .dc h2 {
        font-size: {{ $splash->heading_2_text_size }};
        color: {{ $splash->heading_2_text_colour }};
        font-family: {{ $splash->font_family }};
        font-weight: normal;
        line-height: 1.3em;
      }

      h3 {
        font-size: {{ $splash->heading_3_text_size }};
        color: {{ $splash->heading_3_text_colour }};
        font-weight: normal;
        font-family: {{ $splash->font_family }};
        line-height: 1.3em;
      }

      .dc p {
        font-size: {{ $splash->body_font_size }}!important;
        color: {{ $splash->body_text_colour }};
        font-family: {{ $splash->font_family }};
      }

      a {
        color: {{ $splash->link_colour }};
        font-family: {{ $splash->font_family }};
      }

      .designer .button, button {
      }

      .btn {
        -webkit-appearance: none;
        -moz-appearance: none;
        border-style: solid;
        border-width: 1px;
        cursor: pointer;
        font-family: Lato,"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif;
        font-weight: 400;
        position: relative;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        transition: background-color 300ms ease-out;
        font-size: {{ $splash->btn_font_size }}!important;
        color: {{ $splash->btn_font_colour }}!important;
        margin: 10px 0 15px 0;
        padding: {{ $splash->button_padding }};
        height: {{ $splash->button_height or '50px' }}!important;
        line-height: {{ $splash->button_height or '50px' }}!important;
        border-radius: {{ $splash->button_radius }};
        background-color: {{ $splash->button_colour }};
        border-color: {{ $splash->button_border_colour }};
      }

      small, .small {
        font-size: 11px;
      }

      .design-container {
        float: {{ $splash->container_float }}!important;
        margin: 0 auto;
      }

      .splash-container {
        padding: 0px 0 0 0;
        margin: 0 auto;
        max-width: {{ $splash->container_width }};
        width: 98%;
      }

      .ps_inner {
        text-align: {{ $splash->container_text_align }};
        border: 1px solid {{ $splash->border_colour }};
        display: block;
        background-color: {{ $splash->container_colour }}!important;
        opacity: {{ $splash->container_transparency }};
        padding-top: 10px;
        padding-left: {{ $splash->container_inner_padding }};
        padding-right: {{ $splash->container_inner_padding }};
        margin: 0 auto;
        width: 94%;
        min-height: 400px;
      }

      .banner {
        text-align: center;
        margin: 0 auto;
      }

      .splash-footer, .splash_footer a {
        padding: 20px 0;
        font-size: 10px;
        color: {{ $splash->footer_text_colour }};
      }

      .location_logo {
        text-align: {{ $splash->logo_position }};
      }

      .location_logo img {
        max-width: 220px;
        padding: 14px 0;
      }

      .social {
        margin: 10px 0 0 -5px;
      }

      .social img {
        width: 32px;
        height: 32px;
      }

      #container-c1 {

      }

      #container-c2 {
        padding-top: 15px;
      }

      h2.norm {
        font-family: "Open Sans","Helvetica Neue",Helvetica,Roboto,Arial,sans-serif;
        font-weight: 500;
        font-style: normal;
        font-size: 18px;
        color: #1fad5c;
        text-rendering: optimizeLegibility;
        margin-top: 0.2rem;
      }

      input.design-input, textarea.designer-input {
        font-family: {!! $splash->font_family !!} !important;
        max-width: {{ $splash->input_max_width or '400px' }}!important;
        width: 80%;
        margin-left: -7px;
        padding: {{ $splash->input_padding }}!important;

        border: {{ $splash->input_border_width }} solid {{ $splash->input_border_colour }}!important;
        border-radius: {{ $splash->input_border_radius }}!important;
        box-shadow: inset 0 0px 0px rgb(255, 255, 255)!important;
        background-color: #ffffff!important;

        border-width: 1px!important;
        border-color: {{ $splash->input_border_width }} solid {{ $splash->input_border_colour }}!important;
        margin: 0 0 0.5rem -5px!important;
        color: #3d3d3d!important;
      }

      .designer label {
        font-size: {{ $splash->body_font_size }}!important;
        color: {{ $splash->body_text_colour }};
        line-height: 40px;
        font-family: {{ $splash->font_family }};
      }

      .designer {
        min-height: 100%;
        padding: 0 10px 0 10px;
        margin: 0 auto;
        background-image: url({{ $splash->background_image_name }} );
        background-color: {{ $splash->background_image_name ? 'transparent' : $splash->body_background_colour }}!important;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
      }

      .des-facebook {
        background-color: #3b5998;
        min-width: 150px;
        max-width: 200px;
        width: 100%;
        color: white!important;
        font-weight: bold;
      }

      .des-google {
        background-color: #DF4526;
        min-width: 150px;
        max-width: 200px;
        width: 100%;
        color: white!important;
        font-weight: bold;
      }

      .des-linkedin {
        background-color: #046799;
        min-width: 150px;
        max-width: 200px;
        width: 100%;
        color: white!important;
        font-weight: bold;
      }

      .social {
        margin: 10px;
        border-style: solid;
        border-width: 0px;
        cursor: pointer;
        line-height: normal;
        margin: 0 0 1.25rem;
        position: relative;
        text-decoration: none;
        text-align: center;
        -webkit-appearance: none;
        border-radius: 0;
        display: inline-block;
        padding-top: 1rem;
        padding-right: 2rem;
        padding-bottom: 1.0625rem;
        padding-left: 2rem;
        font-size: 1rem;
        /* background-color: #008CBA; */
        border-color: #007095;
        color: #FFFFFF;
        transition: background-color 300ms ease-out;
      }
    </style>
  </head>
  <body>
    <div class="designer" ng-hide="loading">
    <div class="dc design-container">
      <div class="splash-container md-padding">
        @if (!$splash->header_image_name && !$splash->logo_file_name)
        <div style="height: 150px">
          <!-- <div ng-show="splash.header_image_type == 1" class="logo location_logo"><img src="https://d247kqobagyqjh.cloudfront.net/api/file/QPcfBDMQ4GIws26Bn63g" alt="" class=""></div> -->
          <!-- <div ng-show="splash.header_image_type == 2" class="banner"><img src="https://d247kqobagyqjh.cloudfront.net/api/file/an030sXEQF2nBNDmLOdO" alt="" class=""></div> -->
        </div>
        @endif

        @if ($splash->header_image_name || $splash->logo_file_name)
        <div class="{{ $splash->header_image_type == 1 ? 'logo location_logo' : 'banner' }}">
          <a href="http://{{ $splash->website }}">
            <img src="{{ $splash->header_image_type == 1 ? $splash->logo_file_name : $splash->header_image_name }}" alt="" class="">
          </a>
        </div>
        @endif

        <div class="ps_inner">
          <!--<div ng-if="welcomeEditing == true">
            <div ng-include="'components/splash_pages/_welcome.html'"></div>
          </div>-->
          <!--<div ng-hide="welcomeEditing">-->
          <form method="POST" action"">
          {!! csrf_field() !!}
          <div>
            <h1>{{ $splash->header_text }}</h1>
            <h2><div>{{ $splash->info }}</div></h2>
            <p>{{ $splash->info_two }}</p>
            @if ($splash->primary_access_id == 1)
              <input type="password" name="password" class="design-input" placeholder="What's the password?" required><br>
              <button class='btn edit'>{{ $splash->btn_text }}</button>
            @elseif ($splash->primary_access_id == 2)
              <label>Enter your username</label><br>
              <input style="display:none" type="text" name="fakeinput"/>
              <input style="display:none" type="password" name="fakeinput"/>
              <input type="text" name="username" class="design-input" placeholder="What's your username" required><br>
              <label>Enter your password</label><br>
              <input type="password" name="password" class="design-input" placeholder="What's the password" required><br>
              <button class='btn edit'>{{ $splash->btn_text }}</button>
            @elseif ($splash->primary_access_id == 3)
              <button class='btn'>{{ $splash->btn_text }}</button>
            @elseif ($splash->primary_access_id == 7)
              @if ($splash->fb_login_on)
              <a href="{{ route('social.redirect', ['id' => $splash->id, 'provider' => 'facebook']) }}">
              <p class='social des-facebook'>Facebook</p>
              </a><br>
              @endif
              @if ($splash->g_login_on)
              <a href="{{ route('social.redirect', ['id' => $splash->id, 'provider' => 'google']) }}">
              <p class='social des-google'>Google+</p>
              </a><br>
              @endif
              @if ($splash->li_login_on)
              <a href="{{ route('social.redirect', ['id' => $splash->id, 'provider' => 'linkedin']) }}">
              <p class='social des-linkedin'>LinkedIn</p>
              </a>
              @endif
            @elseif ($splash->primary_access_id == 8)
              @foreach ($fields as $field)
                @if ($field->field_type == 'checkbox')
                <input type="{{ $field->field_type }}" name="{{ $field->name }}" value="{{ $field->name }}" {{ $field->required ? 'required' : ''}}><label>{{ $field->name }}</label><br>
                @elseif ($field->field_type == 'textarea')
                <label>Enter your {{ $field->label }}</label><br>
                <textarea name="{{ $field->name }}" class="designer-input" placeholder="Enter your {{ $field->name }}" {{ $field->required ? 'required' : ''}}></textarea><br>
                @else
                <label>Enter your {{ $field->label }}</label><br>
                <input type="{{ $field->field_type }}" name="{{ $field->name }}" class="design-input" placeholder="Enter your {{ $field->name }}" {{ $field->required ? 'required' : ''}}><br>
                @endif
              @endforeach
              <button class='btn edit'>{{ $splash->btn_text }}</button>
            @else
              <button>{{ $splash->btn_text }}</button>
            @endif
            <p class="small" ng-hide="splash.hide_terms" translate>By logging in, you agree to our <a href="{{ $splash->terms_url }}">terms of use</a></p>
            <div class="location_address">
              <p>{{ $splash->address }}</p>
            </div>

            <div class="social">
              @if ($splash->twitter_name)
              <a href="https://twitter.com/{{ $splash->twitter_name }}"><img src="https://d3e9l1phmgx8f2.cloudfront.net/images/social/twitter.svg"></a>
              @endif
              @if ($splash->google_name)
              <a href="https://plus.google.com/{{ $splash->google_name }}/about"><img src="https://d3e9l1phmgx8f2.cloudfront.net/images/social/google.svg"></a>
              @endif
              @if ($splash->facebook_name)
              <a href="http://facebook.com/{{ $splash->facebook_name }}"><img src="https://d3e9l1phmgx8f2.cloudfront.net/images/social/facebook.svg"></a>
              @endif
              @if ($splash->pinterest_name)
              <a href="http://pinterest.com/{{ $splash->pinterest_name }}"><img src="https://d3e9l1phmgx8f2.cloudfront.net/images/social/pinterest.svg"></a>
              @endif
              @if ($splash->instagram_name)
              <a href="http://instagram.com/{{ $splash->instagram_name }}"><img src="https://d3e9l1phmgx8f2.cloudfront.net/images/social/instagram.svg"></a>
              @endif
              @if ($splash->linkedin_name)
              <a href="http://linkedin.com/{{ $splash->linkedin_name }}"><img src="https://d3e9l1phmgx8f2.cloudfront.net/images/social/linkedin.svg"></a>
              @endif
            </div>
            <p><a href="http://{{ $splash->website }}">{{ $splash->website }}</a></p>
          </div>
          </form>
        </div>
        @if (!$splash->header_image_name && !$splash->logo_file_name)
        <div style="height: 150px">
          <!-- <div ng-show="splash.header_image_type == 1" class="logo location_logo"><img src="https://d247kqobagyqjh.cloudfront.net/api/file/QPcfBDMQ4GIws26Bn63g" alt="" class=""></div> -->
          <!-- <div ng-show="splash.header_image_type == 2" class="banner"><img src="https://d247kqobagyqjh.cloudfront.net/api/file/an030sXEQF2nBNDmLOdO" alt="" class=""></div> -->
        </div>
        @endif
      </div>
    </div>
    </div>
  </body>
</html>
