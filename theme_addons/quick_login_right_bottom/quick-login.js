// Open login form
function openForm() {
  document.getElementById("myForm").style.display = "block";
}

// Close login form
function closeForm() {
  document.getElementById("myForm").style.display = "none";
}

jQuery(document).ready(function ($) {
  // Attach event to login button
  $("#ql_form_submit").on("click", function (event) {
    event.preventDefault(); // Prevent default form submission

    // Get input values
    var username = $("#ql_username").val();
    var password = $("#ql_password").val();
    var nonce = $("#user_quick_login_field").val();

    // Perform AJAX request
    $.ajax({
      type: "POST",
      url: quick_login_ajax.ajax_url,
      data: {
        action: "custom_user_login",
        username: username,
        password: password,
        user_quick_login_field: nonce,
      },
      success: function (response) {
        try {
          var jsonResponse = typeof response === "string" ? JSON.parse(response) : response;

          if (jsonResponse.status === "error") {
            $("#ql_err_msg").html(jsonResponse.message).addClass("alert alert-danger");
          } else {
            $("#ql_err_msg").html(jsonResponse.message).addClass("alert alert-success");
            setTimeout(function () {
              window.location.href = jsonResponse.redirect_url;
            }, 1000);
          }
        } catch (e) {
          console.error("JSON Parse Error:", response);
          $("#ql_err_msg").html("An unexpected error occurred.").addClass("alert alert-danger");
        }
      },
      error: function (xhr, status, error) {
        console.error("AJAX error:", status, error);
        console.error(xhr.responseText);
        $("#ql_err_msg").html("Server error. Please try again.").addClass("alert alert-danger");
      },
    });
  });
});
