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
          <h2 class='mdl-card__title-text'>Password Reset</h2>
        </div>
        <div class='mdl-card__supporting-text'>
          <p>To reset your password, enter the email address you use to sign-in.</p>
          <form class="new_user" id="new_user" role="form" method="POST" action="{{ url('/password/new') }}">
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
            <div class='button'>
                <input name="commit" type="submit" value="SEND" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" data-disable-with="Sending..." />
            </div>
          </form>
        </div>
      </div>
    </div>
  </body>
</html>
