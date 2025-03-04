jQuery(document).ready(function ($) {
  // Event handler for like, unlike, follow, and unfollow buttons
  $("[name='like_button'], [name='unlike_button'], [name='follow_button'], [name='unfollow_button']").on("click", function () {
    var id = this.id; // Getting Button id
    var split_id = id.split("_");

    // Extracting button action and IDs from the button ID
    var action = split_id[0] + "_" + split_id[1];
    var postid = split_id[2];
    var authorid = split_id[3] || ""; // Handle case where authorid may not be present

    // Collect user and author IDs from hidden inputs
    var userid = document.getElementsByName("user_id")[0].value;
    var authorid = document.getElementsByName("author_id")[0].value;

    // Determine the button action type based on the button ID
    var btntype =
      {
        like_button: "like",
        unlike_button: "unlike",
        follow_button: "follow",
        unfollow_button: "unfollow",
      }[action] || "";

    if (!btntype) {
      console.error("Button type not recognized:", action);
      return;
    }

    // Log the type and ID to debug
    // console.log("Button ID:", id);
    // console.log("Action:", action);
    // console.log("Type:", btntype);
    // console.log("Post ID:", postid);
    // console.log("Author ID:", authorid);

    // AJAX Request
    $.ajax({
      url: like_follow_ajax.ajax_url, // URL for AJAX request
      type: "POST",
      data: {
        action: "my_action_2", // The action to fire on the server
        data: {
          userid: userid,
          postid: postid,
          authorid: authorid,
          btntype: btntype,
        },
      },
      dataType: "json",
      success: function (response) {
        if (response.success) {
          var { likes, followers } = response.data;

          // Update like and follow counts
          $("#total_likes_" + postid).text(likes);
          $("#total_likes2_" + postid).text(likes);
          $("#total_follow_" + authorid).text(followers);
          $("#total_follow2_" + authorid).text(followers);

          // Toggle button visibility based on action type
          if (btntype === "like") {
            $("#like_button_" + postid).hide();
            $("#unlike_button_" + postid).show();
          } else if (btntype === "unlike") {
            $("#unlike_button_" + postid).hide();
            $("#like_button_" + postid).show();
          } else if (btntype === "follow") {
            $("#follow_button_" + authorid).hide();
            $("#unfollow_button_" + authorid).show();
          } else if (btntype === "unfollow") {
            $("#unfollow_button_" + authorid).hide();
            $("#follow_button_" + authorid).show();
          }
        } else {
          console.error("AJAX request failed:", response.data);
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error("AJAX error:", textStatus, errorThrown);
      },
    });
  });
});
