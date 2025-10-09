// quick-login.js

// Guard: require localization
(function () {
  if (!window.quick_login_ajax || !quick_login_ajax.ajax_url) {
    console.error("quick_login_ajax is missing or ajax_url is empty. Check wp_localize_script handle/order.");
  }
})();

// Open/Close login form
function openForm() {
  document.getElementById("myForm").style.display = "block";
}
function closeForm() {
  document.getElementById("myForm").style.display = "none";
}

jQuery(function ($) {
  var $btn = $("#ql_form_submit");
  var inflight = false;

  $btn.off(".ajdwpQuickLogin").on("click.ajdwpQuickLogin", function (event) {
    event.preventDefault();
    if (!window.quick_login_ajax || !quick_login_ajax.ajax_url) return;

    if (inflight) return;
    inflight = true;
    $btn.prop("disabled", true);

    var $msg = $("#ql_err_msg").removeClass("alert alert-danger alert-success").empty();

    var username = $("#ql_username").val();
    var password = $("#ql_password").val();
    var fieldNonce = $("#user_quick_login_field").val();
    var nonce = fieldNonce || quick_login_ajax.nonce || "";
    var lang = quick_login_ajax.lang || "";

    if (!username || !password) {
      var t = quick_login_ajax.strings || {};
      $msg.addClass("alert alert-danger").html(t.empty || "Please enter both username and password.");
      inflight = false;
      $btn.prop("disabled", false);
      return;
    }

    $.ajax({
      type: "POST",
      url: quick_login_ajax.ajax_url,
      dataType: "json",
      cache: false,
      data: {
        action: "custom_user_login",
        username: username,
        password: password,
        user_quick_login_field: nonce,
        nonce: nonce,
        lang: lang,
      },
    })
      .done(function (res) {
        var t = quick_login_ajax.strings || {};
        if (!res || res.success !== true) {
          var msg = (res && res.data && (res.data.message || res.data.error)) || t.unexpected || "An unexpected error occurred.";
          $msg.addClass("alert alert-danger").html(msg);

          if (res && res.data && Array.isArray(res.data.help?.actions)) {
            $msg.append(
              '<div class="mt-2">' +
                res.data.help.actions
                  .map(function (a) {
                    return '<a class="me-2" href="' + a.url + '">' + a.label + "</a>";
                  })
                  .join("") +
                "</div>"
            );
          }
          return;
        }

        $msg.addClass("alert alert-success").html(res.data.message || t.success || "Login successful");
        setTimeout(function () {
          window.location.href = res.data.redirect_url || "/";
        }, 700);
      })
      .fail(function (xhr, textStatus) {
        var t = quick_login_ajax.strings || {};
        var msg;

        if (textStatus === "parsererror") {
          // Likely HTML output from server; log it to debug
          console.error("Non-JSON response:", xhr.responseText);
        }

        if (xhr.responseJSON && xhr.responseJSON.data) {
          msg = xhr.responseJSON.data.message || (Array.isArray(xhr.responseJSON.data.errors) ? xhr.responseJSON.data.errors.join("<br>") : null);
        }
        $msg.addClass("alert alert-danger").html(msg || t.server_error || "Server error. Please try again.");
      })
      .always(function () {
        inflight = false;
        $btn.prop("disabled", false);
      });
  });
});
