"use strict";

(function ($, window, document, undefined) {
  /**
   * On document ready.
   */
  $(document).ready(function () {
    // Checks.
    $('#xbee-settings-sar #xbee-perform-checks').on('click', function (e) {
      e.preventDefault(); // Request data.

      var data = {
        'action': 'xbee_sar_checks'
      }; // Response message.

      var message = '';
      $.ajax({
        url: xbeeSarAdminParams.ajaxUrl,
        method: 'post',
        data: data,
        beforeSend: function beforeSend() {
          // Show overlay.
          xbeeShowOverlay();
        },
        success: function success(response) {
          // Hide overlay.
          xbeeHideOverlay();
          response = JSON.parse(response);
          var apiKeysStatus = response.api_keys ? 'good' : 'error';
          var defaultCallbackUrlStatus = response.default_callback_url ? 'good' : 'error';
          $('#xbee-sar-checks .xbee-check').removeClass('good error');
          $('#xbee-sar-checks .xbee-check[data-xbee-check="api_keys"]').addClass(apiKeysStatus);
          $('#xbee-sar-checks .xbee-check[data-xbee-check="default_callback_url"]').addClass(defaultCallbackUrlStatus);
        },
        error: function error() {
          // Hide overlay.
          xbeeHideOverlay();
        }
      });
    });
  });
})(jQuery, window, document);