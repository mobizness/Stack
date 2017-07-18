<!DOCTYPE html>
<html>
  <head>
    <title>DigiFi Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.1.3/material.indigo-pink.min.css">
    <script defer src="https://code.getmdl.io/1.1.3/material.min.js"></script>
    <link rel="stylesheet" media="all" href="{{asset('public/css/login.css')}}" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:300,400,500,700" type="text/css">
  </head>
  <body>
    <div class="mdl-grid">
      <div class='mdl-card mdl-shadow--2dp login-card-wide'>
        <div class='mdl-card__title'>
          <h2 class='mdl-card__title-text'>Welcome Back</h2>
        </div>
        <div class='mdl-card__supporting-text'>
          <form class="new_user" id="new_user" role="form" method="POST" action="{{ url('/login') }}">
            {{ csrf_field() }}
            <div class='mdl-textfield mdl-js-textfield mdl-textfield--floating-label' style='margin-top: 20px;'>
              <input required="required" autofocus="autofocus" class="mdl-textfield__input" autocomplete="off" value="" type="text" name="brand" id="brand" />
              <label class='mdl-textfield__label'>
                Brand Domain
              </label>
              @if ($errors->has('brand'))
                  <span class="help-block">
                      <strong>{{ $errors->first('brand') }}</strong>
                  </span>
              @endif
            </div>
            <div class='mdl-textfield mdl-js-textfield mdl-textfield--floating-label'>
              <input required="required" class="mdl-textfield__input" autocomplete="off" type="text" value="" id="email" name="email" />
                <label class='mdl-textfield__label'>
                  Email
                </label>
                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
            <div class='mdl-textfield mdl-js-textfield mdl-textfield--floating-label'>
              <input required="required" class="mdl-textfield__input" autocomplete="off" type="password" id="password" name="password" />
              <label class='mdl-textfield__label'>
                Password
              </label>
              @if ($errors->has('password'))
                  <span class="help-block">
                      <strong>{{ $errors->first('password') }}</strong>
                  </span>
              @endif
            </div>
            <div class='button'>
                <input name="commit" type="submit" value="Sign in" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" data-disable-with="Signing in..." />
            </div>
          </form>
        </div>
        <div class='mdl-card__actions mdl-card--border'>
          <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" href="{{ url('/password/new') }}">Forgot password?</a>
        </div>
      </div>
    </div>
  </body>
</html>
