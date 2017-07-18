'use strict';

/* Filters */

var app = angular.module('myApp.events.filters', []);

app.filter('eventLevel', [function() {
  return function(input, cmd) {
    switch(input) {
      case 0:
        return 'Debug';
      case 1:
        return 'Info';
      case 2:
        return 'Alert';
    }
  };
}]);

app.filter('eventSummary', [function() {
  return function(input,type) {

    if (input === undefined || input === '') {
      return 'Unknown';
    }

    var action = 'unknown';
    var x = type.split('.');
    if (x.length === 2) {
      action = x[1];
    }

    switch(action) {
      case 'upgrade_failed':
        action = 'Failed upgrade for';
        break;
      case 'purchase':
        action = 'Purchased voucher at';
        break;
      case 'upgrade_success':
        action = 'Completed upgrade for';
        break;
      case 'upgrade':
        action = 'Upgraded';
        break;
      case 'update':
        action = 'Updated';
        break;
      case 'create':
        action = 'Created';
        break;
      case 'destroy':
        action = 'Deleted';
        break;
      case 'resync':
        action = 'Re-synced';
        break;
      case 'reboot':
        action = 'Rebooted';
        break;
      case 'password':
        action = 'Password changed for';
        break;
      case 'reset':
        action = 'Reset';
        break;
      case 'invite':
        action = 'Invited';
        break;
      case 'revoke':
        action = 'Revoked';
        break;
      case 'enable':
        action = 'Enabled';
        break;
      case 'disable':
        action = 'Disabled';
        break;
    }

    var msg = action + ' ' + input;
    return msg;
  };
}]);
