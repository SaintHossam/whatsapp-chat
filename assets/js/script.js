/**
 * Saint Hossam WhatsApp Chat Script
 * Version: 1.0.0
 * Author: Saint Hossam
 */

(function ($) {
  "use strict";

  $(document).ready(function () {
    /**
     * Open chat popup when button is clicked
     */
    $(".sh-wa-trigger").on("click", function (e) {
      e.preventDefault();
      $("#sh-whatsapp-popup").fadeIn(300);
      $("#sh-whatsapp-message").focus();
    });

    /**
     * Close chat popup when close button is clicked
     */
    $(".sh-whatsapp-close").on("click", function () {
      $("#sh-whatsapp-popup").fadeOut(300);
    });

    /**
     * Send message when send button is clicked
     */
    $("#sh-send-whatsapp").on("click", function (e) {
      e.preventDefault();
      sendWhatsAppMessage();
    });

    /**
     * Send message when Shift + Enter is pressed
     */
    $("#sh-whatsapp-message").on("keydown", function (e) {
      if (e.key === "Enter" && e.shiftKey) {
        e.preventDefault();
        sendWhatsAppMessage();
      }
    });

    /**
     * Close popup when clicking outside
     */
    $(document).on("click", function (e) {
      if (
        !$(e.target).closest(".sh-whatsapp-popup-content, .sh-wa-trigger")
          .length
      ) {
        $("#sh-whatsapp-popup").fadeOut(300);
      }
    });

    /**
     * Close popup when Escape key is pressed
     */
    $(document).on("keydown", function (e) {
      if (e.key === "Escape") {
        $("#sh-whatsapp-popup").fadeOut(300);
      }
    });

    /**
     * Send WhatsApp message
     */
    function sendWhatsAppMessage() {
      var message = $("#sh-whatsapp-message").val().trim();

      // Validate message
      if (message === "") {
        alert("الرجاء كتابة رسالة أولاً");
        $("#sh-whatsapp-message").focus();
        return;
      }

      // Build WhatsApp URL
      var phone = shWhatsAppChat.phoneNumber;
      var url =
        "https://wa.me/" + phone + "?text=" + encodeURIComponent(message);

      // Open WhatsApp in new window
      window.open(url, "_blank");

      // Close popup and clear message after short delay
      setTimeout(function () {
        $("#sh-whatsapp-popup").fadeOut(300);
        $("#sh-whatsapp-message").val("");
      }, 500);
    }
  });
})(jQuery);
