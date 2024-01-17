//Added to footer in <script src="https://arashjavadi.com/wp-content/themes/AJDWP_child_theme/theme_addons/Like_follow/Like_Follow_Ajax.js"></script>
jQuery(document).ready(function() {

// like and unlike click
$(" [name='like_button'], [name='unlike_button'], [name='follow_button'], [name='unfollow_button'] ").click(function(){
    var id = this.id; // Getting Button id
    var split_id = id.split("_");

    var text = split_id[0] + "_" + split_id[1];
    var postid = split_id[2]; // postid

    // var userid = <?php //echo get_current_user_id(); ?>;
    // var authorid = <?php //the_author_meta('ID'); ?>;

    var userid = document.getElementsByName('user_id')[0].value;
    var authorid = document.getElementsByName('author_id')[0].value;
    
    // alert(userid);
    // alert(authorid);

    // Finding click type
    var btntype = 0;
    if (text == "like_button") {
        btntype = "like";
    }
    if (text == "unlike_button") {
        btntype = "unlike";
    }
    if (text == "follow_button") {
        btntype = "follow";
    }
    if (text == "unfollow_button") {
        btntype = "unfollow";
    }
    // alert(btntype);
//     alert(text+"_"+split_id[2]);

    // var path = window.location.protocol + '//' + window.location.hostname;
    // AJAX Request
    $.ajax({
        url: `${window.location.origin}/wp-admin/admin-ajax.php`, //for live
        // url: `${window.location.origin}/ajdwp/wp-admin/admin-ajax.php`, //for localhost
        type: 'post',
        data: {
            action: "my_action_2", // the action to fire in the server
            data: {
                userid: userid,
                postid: postid,
                authorid: authorid,
                btntype: btntype
            }, // any JS object
        },

        // dataType: 'json',
        success: function(data) {
            var likes = data.data['likes'];
            var unlikes = data.data['unlikes'];
            var followers = data.data['followers'];
            //alert(data);
            // console.log(data.data['likes']);

            $("#total_likes_" + postid).text(likes); // setting likes
            $("#total_likes2_" + postid).text(likes); // setting likes

            // $("#unlikes_"+postid).text(unlikes);            // setting unlikes
            $("#total_follow_" + authorid).text(followers); // setting likes
            $("#total_follow2_" + authorid).text(followers);


            if (btntype == 'like') {
                $("#like_button_" + postid).css("display", "none");
                $("#unlike_button_" + postid).css("display", "inline-block");
            }

            if (btntype == 'unlike') {
                $("#unlike_button_" + postid).css("display", "none");
                $("#like_button_" + postid).css("display", "inline-block");
            }

            if (btntype == 'follow') {
                $("#follow_button_" + authorid).css("display", "none");
                $("#unfollow_button_" + authorid).css("display", "inline-block");
            }

            if (btntype == 'unfollow') {
                $("#unfollow_button_" + authorid).css("display", "none");
                $("#follow_button_" + authorid).css("display", "inline-block");
            }

        }
    });

});

});