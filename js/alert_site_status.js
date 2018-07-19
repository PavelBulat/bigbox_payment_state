(function ($, Drupal, drupalSettings) {

  'use strict';

  /**
   * Click handler for click "Добавить блок" button.
   *
   * @type {Object}
   */
  Drupal.behaviors.bigboxAlertClose = {
    attach: function (context) {
      $('#show-alert', context)
        .once('bigboxAlertClose')
        .on('click', function (event) {
          // Stop default execution of click event.
          var message = $('#alert-message');
          var showAlert = $('#show-alert');
          if (showAlert.hasClass('alert-close')){
            Drupal.ajax({
              url: drupalSettings.path.baseUrl + drupalSettings.path.pathPrefix + 'admin/change_state_site_status_message',
              submit: {'data': false},
              format: 'json',
              success:  function success(data) {
                showAlert.removeClass('alert-close');
                showAlert.addClass('alert-open');
                message.removeClass('visible');
                message.addClass('hide');
              }
            }).execute();

          } else if (showAlert.hasClass('alert-open')) {
            Drupal.ajax({
              url: drupalSettings.path.baseUrl + drupalSettings.path.pathPrefix + 'admin/change_state_site_status_message',
              submit: {'data': true},
              format: 'json',
              success:  function success(data) {
                showAlert.removeClass('alert-open');
                showAlert.addClass('alert-close');
                message.removeClass('hide');
                message.addClass('visible');
              }
            }).execute();
          }
        });
    }
  };
}(jQuery, Drupal, drupalSettings));