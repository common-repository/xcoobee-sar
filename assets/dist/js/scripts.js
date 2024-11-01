"use strict";

(function ($, window, document, undefined) {
  /**
   * On document ready.
   */
  $(document).ready(function () {
    // Request Data.
    $('.xbee-sar').on('click', function (e) {
      e.preventDefault();
      var form = $(this).closest('form');
      var campaignRef = $(this).data('public-id');
      var miniSarUrl = xbeeSarParams.miniSarUrl + '?public_id=' + campaignRef;
      var iframeParams = 'scrollbars=no,resizable=no,status=no,location=no,toolbar=no,menubar=no,width=500,height=500,left=0,top=0';
      var miniSarWindow = window.open(miniSarUrl, 'xbeeDataRequest', iframeParams);
      window.addEventListener('message', function (event) {
        if (event.data === 'success') {
          xbeeLoadModal('xbee-sar-success', xbeeSarParams.messages.successModalTitle, xbeeSarParams.messages.successModalMessage, 'success');
        }
      }, false);
    });
  });
})(jQuery, window, document);