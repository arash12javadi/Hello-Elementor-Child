//_____________________________________ user_profile.js _____________________________________//
//--------------------------- Password Validation ---------------------------//
function validatePasswordStrength(input, charElem, capitalElem, numberElem, lengthElem, messageElem, submitButton) {
  var specialCharacters = /[!@#$%^&*()_+{}\[\]:;<>,.?~\\\/-]/g;
  var upperCaseLetters = /[A-Z]/g;
  var numbers = /[0-9]/g;

  charElem.classList.toggle("reg_psw_valid", specialCharacters.test(input.value));
  charElem.classList.toggle("reg_psw_invalid", !specialCharacters.test(input.value));

  capitalElem.classList.toggle("reg_psw_valid", upperCaseLetters.test(input.value));
  capitalElem.classList.toggle("reg_psw_invalid", !upperCaseLetters.test(input.value));

  numberElem.classList.toggle("reg_psw_valid", numbers.test(input.value));
  numberElem.classList.toggle("reg_psw_invalid", !numbers.test(input.value));

  lengthElem.classList.toggle("reg_psw_valid", input.value.length >= 8);
  lengthElem.classList.toggle("reg_psw_invalid", input.value.length < 8);

  var isValid =
    charElem.classList.contains("reg_psw_valid") &&
    capitalElem.classList.contains("reg_psw_valid") &&
    numberElem.classList.contains("reg_psw_valid") &&
    lengthElem.classList.contains("reg_psw_valid");

  messageElem.style.display = isValid ? "none" : "block";
  submitButton.disabled = !isValid;
}

// ---------------- Register Password Check ----------------
var AJDWP_myInput = document.getElementById("register_psw");
var Special_char = document.getElementById("Special_char");
var capital = document.getElementById("capital");
var number = document.getElementById("number");
var length = document.getElementById("length");
var regMessage = document.getElementById("reg_psw_message");
var regSubmitButton = document.getElementById("reg_user_submit");

if (AJDWP_myInput) {
  AJDWP_myInput.onfocus = function () {
    regMessage.style.display = "block";
  };
  AJDWP_myInput.onblur = function () {
    regMessage.style.display = "none";
  };
  AJDWP_myInput.onkeyup = function () {
    validatePasswordStrength(AJDWP_myInput, Special_char, capital, number, length, regMessage, regSubmitButton);
  };
}

// ---------------- Change Password Check ----------------
var AJDWP_myInput_cp = document.getElementById("change_psw");
var Special_char_cp = document.getElementById("Special_char_cp");
var capital_cp = document.getElementById("capital_cp");
var number_cp = document.getElementById("number_cp");
var length_cp = document.getElementById("length_cp");
var cpMessage = document.getElementById("chg_psw_message");
var cpSubmitButton = document.getElementById("chg_psw_submit");

if (AJDWP_myInput_cp) {
  AJDWP_myInput_cp.onfocus = function () {
    cpMessage.style.display = "block";
  };
  AJDWP_myInput_cp.onblur = function () {
    cpMessage.style.display = "none";
  };
  AJDWP_myInput_cp.onkeyup = function () {
    validatePasswordStrength(AJDWP_myInput_cp, Special_char_cp, capital_cp, number_cp, length_cp, cpMessage, cpSubmitButton);
  };
}

// ---------------- Reset Password Form Check ----------------
var AJDWP_myInput_rpf = document.getElementById("new_password_reset");
var Special_char_rpf = document.getElementById("Special_char_rpf");
var capital_rpf = document.getElementById("capital_rpf");
var number_rpf = document.getElementById("number_rpf");
var length_rpf = document.getElementById("length_rpf");
var rpf_Message = document.getElementById("rpf_psw_message");
var rpf_SubmitButton = document.getElementById("new_password_reset_submit");

if (AJDWP_myInput_rpf) {
  AJDWP_myInput_rpf.onfocus = function () {
    rpf_Message.style.display = "block";
  };
  AJDWP_myInput_rpf.onblur = function () {
    rpf_Message.style.display = "none";
  };
  AJDWP_myInput_rpf.onkeyup = function () {
    validatePasswordStrength(AJDWP_myInput_rpf, Special_char_rpf, capital_rpf, number_rpf, length_rpf, rpf_Message, rpf_SubmitButton);
  };
}

// ---------------- Media library picker ----------------
jQuery(function ($) {
  $("#open-media-library").on("click", function (e) {
    e.preventDefault();

    var mediaFrame = wp.media({
      title: (window.po_media_i18n && po_media_i18n.title) || "Select or Upload Media",
      button: { text: (window.po_media_i18n && po_media_i18n.button) || "Use this media" },
      multiple: false,
    });

    mediaFrame.on("select", function () {
      var attachment = mediaFrame.state().get("selection").first().toJSON();
      $("#selected-image").attr("src", attachment.url);
      $("#selected-image-url").val(attachment.url);
    });

    mediaFrame.open();
  });
});

//--------------------------- USER REGISTER ---------------------------//
//--------------------------- USER REGISTER ---------------------------//
//--------------------------- USER REGISTER ---------------------------//
/* global AJDWPReg */
(function ($) {
  "use strict";

  // -------- strength UI helpers --------
  function updateStrengthUI(val) {
    var hasSpec = /[^A-Za-z0-9]/.test(val);
    var hasUp = /[A-Z]/.test(val);
    var hasNum = /\d/.test(val);
    var lenOK = val.length >= 8;

    $("#Special_char").toggleClass("reg_psw_valid", hasSpec).toggleClass("reg_psw_invalid", !hasSpec);
    $("#capital").toggleClass("reg_psw_valid", hasUp).toggleClass("reg_psw_invalid", !hasUp);
    $("#number").toggleClass("reg_psw_valid", hasNum).toggleClass("reg_psw_invalid", !hasNum);
    $("#length").toggleClass("reg_psw_valid", lenOK).toggleClass("reg_psw_invalid", !lenOK);

    return hasSpec && hasUp && hasNum && lenOK;
  }

  function showMsg($box, type, content) {
    $box.removeClass("alert alert-danger alert-success").empty().hide();
    if (!content) return;
    $box
      .addClass("alert " + (type === "error" ? "alert-danger" : "alert-success"))
      .html(content)
      .show();
    if ($box[0] && $box[0].scrollIntoView) {
      $box[0].scrollIntoView({ behavior: "smooth", block: "center" });
    }
  }

  function disableForm(disabled) {
    $("#AJDWP_registration_form").find("input,button,select,textarea").prop("disabled", !!disabled);
  }

  $(function () {
    var cfg = window.AJDWP || {};
    var $form = $("#AJDWP_registration_form");
    if (!$form.length) return; // no form on this page

    var $msg = $("#user-register-message");
    if (!$msg.length) {
      $msg = $('<div id="user-register-message" class="mb-3" style="display:none"></div>');
      $form.before($msg);
    }

    var $btn = $("#reg_user_submit");
    var $psw = $("#register_psw");
    var $conf = $("#password_again");

    function refreshSubmitState() {
      var strong = updateStrengthUI($psw.val());
      var matches = $psw.val() && $psw.val() === $conf.val();
      $btn.prop("disabled", !(strong && matches));
      $("#reg_psw_message").toggle(!(strong && matches));
    }

    $psw.on("focus", function () {
      $("#reg_psw_message").show();
    });
    $psw.on("blur", function () {
      if ($psw.val() === "") $("#reg_psw_message").hide();
    });
    $psw.on("keyup", refreshSubmitState);
    $conf.on("keyup", refreshSubmitState);

    $form.on("submit", function (e) {
      e.preventDefault();
      showMsg($msg, null, "");

      var data = {
        action: "user_register_ajax",
        AJDWP_csrf_nonce: $("#AJDWP_csrf_nonce").val() || cfg.nonce || "",

        AJDWP_user_login: $("#reg_AJDWP_user_Login").val(),
        AJDWP_user_email: $("#reg_AJDWP_user_email").val(),
        AJDWP_user_first: $("#reg_AJDWP_user_first").val(),
        AJDWP_user_last: $("#reg_AJDWP_user_last").val(),
        AJDWP_user_pass: $("#register_psw").val(),
        AJDWP_user_pass_confirm: $("#password_again").val(),
        AJDWP_user_role: $('input[name="user_role"]:checked').val() || "subscriber",

        // for localized server messages
        lang: cfg.lang || (document.documentElement.getAttribute("lang") || "").trim(),
      };

      // quick client checks
      var t = cfg.i18n || {};
      var errs = [];
      if (!data.AJDWP_user_login) errs.push(t.enter_username || "Please enter a username");
      if (!data.AJDWP_user_email) errs.push(t.enter_email || "Please enter an email address");
      if (data.AJDWP_user_email && !/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(data.AJDWP_user_email)) {
        errs.push(t.invalid_email || "Invalid email");
      }
      if (!data.AJDWP_user_pass) errs.push(t.enter_password || "Please enter a password");
      if (data.AJDWP_user_pass !== data.AJDWP_user_pass_confirm) {
        errs.push(t.mismatch || "Passwords do not match");
      }
      if (errs.length) {
        showMsg($msg, "error", "<ul><li>" + errs.join("</li><li>") + "</li></ul>");
        return;
      }

      disableForm(true);

      $.ajax({
        url: cfg.ajax_url || "/wp-admin/admin-ajax.php",
        type: "POST",
        dataType: "json",
        data: data,
      })
        .done(function (res) {
          if (res && res.success === true) {
            var message = (res.data && res.data.message) || t.success || "Registration successful.";
            var redirect = (res.data && res.data.redirect) || cfg.home_url || "/";

            showMsg($msg, "success", message);
            $("#success_register_gif_anim").html(
              '<img src="' +
                (cfg.gif_url || "") +
                '" alt="Success"><br>' +
                '<a href="' +
                (cfg.home_url || "/") +
                '" class="suc_home_btn">&#8592; ' +
                (t.go_home || "Go to Home") +
                "</a>"
            );
            $form.hide();
            setTimeout(function () {
              window.location.href = redirect;
            }, 800);
            return;
          }

          // Render server-side errors
          var html = "";
          if (res && res.data) {
            if (Array.isArray(res.data.errors)) {
              html = "<ul><li>" + res.data.errors.join("</li><li>") + "</li></ul>";
            } else if (res.data.message) {
              html = res.data.message;
            }
          }
          showMsg($msg, "error", html || t.failed || "Registration failed.");
        })
        .fail(function (xhr) {
          console.error("AJAX register failed:", xhr.status, xhr.responseText);
          // Try to show server JSON if present
          var res = xhr.responseJSON;
          if (!res && xhr.responseText) {
            try {
              res = JSON.parse(xhr.responseText);
            } catch (e) {}
          }
          if (res && res.data) {
            if (Array.isArray(res.data.errors)) {
              showMsg($msg, "error", "<ul><li>" + res.data.errors.join("</li><li>") + "</li></ul>");
              return;
            } else if (res.data.message) {
              showMsg($msg, "error", res.data.message);
              return;
            }
          }
          showMsg($msg, "error", (cfg.i18n && cfg.i18n.server_error) || "Server error. Please try again.");
        })
        .always(function () {
          disableForm(false);
        });
    });
  });
})(jQuery);

//--------------------------- FORGOT PASSWORD ---------------------------//
//--------------------------- FORGOT PASSWORD ---------------------------//
//--------------------------- FORGOT PASSWORD ---------------------------//

/* global AJDWPForgot */
(function ($) {
  $(function () {
    var cfg = window.AJDWP || {};
    var t = cfg.i18n || {};
    var $form = $("#forgot-password-form");
    if (!$form.length) return;

    var $msg = $("#reset-password-message");

    function show(type, html) {
      $msg.removeClass("alert alert-danger alert-success").empty().hide();
      if (!html) return;
      $msg
        .addClass("alert " + (type === "error" ? "alert-danger" : "alert-success"))
        .html(html)
        .show();
      if ($msg[0] && $msg[0].scrollIntoView) {
        $msg[0].scrollIntoView({ behavior: "smooth", block: "center" });
      }
    }

    $form.on("submit", function (e) {
      e.preventDefault();
      $form.find("input,button").prop("disabled", true);
      show(null, "");

      $.ajax({
        url: cfg.ajax_url || "/wp-admin/admin-ajax.php",
        type: "POST",
        dataType: "json",
        data: {
          action: "custom_reset_password",
          user_login: $("#user_login").val(),
          security: $("#security").val(), // nonce field from wp_nonce_field('ajax-forgot-nonce','security')
          lang: cfg.lang || (document.documentElement.getAttribute("lang") || "").trim(),
        },
      })
        .done(function (res) {
          if (res && res.success === true) {
            show("success", (res.data && res.data.message) || t.reset_sent || "Password reset link sent. Check your email.");
            $form.hide();
          } else {
            var html = "";
            if (res && res.data) {
              if (Array.isArray(res.data.errors)) {
                html = "<ul><li>" + res.data.errors.join("</li><li>") + "</li></ul>";
              } else if (res.data.message) {
                html = res.data.message;
              }
            }
            show("error", html || t.reset_generic || "Something went wrong. Please try again.");
          }
        })
        .fail(function (xhr) {
          var res = xhr.responseJSON,
            html = "";
          if (!res && xhr.responseText) {
            try {
              res = JSON.parse(xhr.responseText);
            } catch (e) {}
          }
          if (res && res.data) {
            if (Array.isArray(res.data.errors)) {
              html = "<ul><li>" + res.data.errors.join("</li><li>") + "</li></ul>";
            } else if (res.data.message) {
              html = res.data.message;
            }
          }
          show("error", html || t.reset_generic || "Something went wrong. Please try again.");
        })
        .always(function () {
          $form.find("input,button").prop("disabled", false);
        });
    });
  });
})(jQuery);

//--------------------------- DELETE ACCOUNT ---------------------------//
//--------------------------- DELETE ACCOUNT ---------------------------//
//--------------------------- DELETE ACCOUNT ---------------------------//

/* global AJDWPDelete */
(function ($) {
  $(function () {
    var cfg = window.AJDWP || {};
    var t = cfg.i18n || {};

    var $confirmBtn = $("#confirm-delete");
    if (!$confirmBtn.length) return; // no delete tab on this page

    // Block admins
    if (cfg.is_admin) {
      $confirmBtn.on("click", function (e) {
        e.preventDefault();
        alert(t.admin_blocked || "Admins cannot delete their account from the front-end.");
      });
      return;
    }

    // Open dialog
    $confirmBtn.on("click", function (e) {
      e.preventDefault();
      $("#overlay, #customDialog").show();
    });

    // Cancel/hide
    $("#btnCancel").on("click", function (e) {
      e.preventDefault();
      $("#overlay, #customDialog").hide();
    });

    // Confirm delete
    $("#btnContinue").on("click", function (e) {
      e.preventDefault();

      if (!$("#myCheckbox").is(":checked")) {
        alert(t.tick_checkbox || "Please tick the checkbox to continue.");
        return;
      }

      var nonce = $confirmBtn.data("nonce");

      $("#btnContinue, #btnCancel").prop("disabled", true);

      $.ajax({
        url: cfg.ajax_url || "/wp-admin/admin-ajax.php",
        type: "POST",
        dataType: "json",
        data: {
          action: "delete_user_account",
          nonce: nonce,
          confirmed: "1",
          lang: cfg.lang || (document.documentElement.getAttribute("lang") || "").trim(),
        },
      })
        .done(function (res) {
          if (!res || res.success !== true) {
            var msg = res && res.data && res.data.message ? res.data.message : t.generic_error || "Error";
            alert(msg);
            $("#btnContinue, #btnCancel").prop("disabled", false);
            return;
          }
          var redirect = res.data && res.data.redirect ? res.data.redirect : cfg.home_url || "/";
          window.location.href = redirect;
        })
        .fail(function (xhr) {
          console.error("AJAX error", xhr.status, xhr.responseText);
          alert(t.server_error || "Server error. Please try again.");
          $("#btnContinue, #btnCancel").prop("disabled", false);
        });
    });
  });
})(jQuery);
