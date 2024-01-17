<?php

// exit if file is called directly
// if (!defined('ABSPATH')) {
//     exit;
// }


function user_dashboard_float_button(){
    ob_start(); 
    
    $plugin_path = 'display-admin-page-on-frontend-premium/index.php';
    if (file_exists(WP_PLUGIN_DIR . '/' . $plugin_path)) {

        $current_slug = get_query_var('pagename');

        if (is_user_logged_in() && current_user_can('author') || current_user_can( 'manage_options' )){

            if( get_current_user_id() == get_query_var('author') || 
                $current_slug == 'my-media' ||
                $current_slug == 'my-posts' ||
                $current_slug == 'my-comments' 
            ) {

?>
    <div id="hamburger">
        <div id="wrapper">
            <span class="icon-bar" id="one"></span>
            <span class="icon-bar" id="two"></span>
            <span class="icon-bar" id="thr"></span>
        </div>
    </div>
    <div class="nav-udfb" id="my-posts">
        <a href="<?php echo home_url( 'my-posts' );?>"><i class="fa fa-envelope"></i></a>
    </div>
    <div class="nav-udfb" id="my-comments">
        <a href="<?php echo home_url( 'my-comments' );?>"><i class="fa fa-comment"></i></a>
    </div>
    <div class="nav-udfb" id="my-media">
        <a href="<?php echo home_url( 'my-media' );?>"><i class="fa fa-image"></i></a>
    </div>


    <main>
        <div id="udfb-overlay"></div>
    </main>

    <style>

        #udfb-overlay {
            z-index: 2;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(220, 220, 220, 0.7);
            visibility: hidden;
            opacity: 0;
            transition: all 0.2s ease-in;
            will-change: opacity;
        }

        #udfb-overlay.show {
            visibility: visible;
            opacity: 1;
        }

        #hamburger {
            z-index: 10;
            position: fixed;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            bottom: 10%;
            right: 5%;
            background-color: #d10d00;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 2px 2px 10px rgba(10, 10, 10, 0.3);
            transition: all 0.2s ease-in-out;
        }

        #hamburger .icon-bar {
            display: block;
            background-color: #FFFFFF;
            width: 22px;
            height: 2px;
            transition: all 0.3s ease-in-out;
        }

        #hamburger .icon-bar+.icon-bar {
            margin-top: 4px;
        }

        .nav-udfb {
            z-index: 9;
            position: fixed;
            bottom: 10.5%;
            right: 5.5%;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background-color: #f9f9f9;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            visibilty: hidden;
            opacity: 0;
            box-shadow: 3px 3px 10px 0px rgba(0, 0, 0, 0.48);
            cursor: pointer;
            transition: all 0.3s ease-in;
        }

        .material-icons {
            font-size: 24px;
            color: rgba(0, 0, 0, 0.54);
        }

        #my-posts.show {
            transform: translateY(-125%);
            color: #7f0800;
            font-size: 22px;
        }

        #my-comments.show {
            transform: translateY(-250%);
            color: #7f0800;
            font-size: 22px;
        }

        #my-media.show {
            transform: translateY(-375%);
            color: #7f0800;
            font-size: 22px;
        }

        #my-posts.show:hover, #my-media.show:hover, #my-comments.show:hover {
            color: #ff2819;
            font-size: 28px;
        }

        #hamburger.show {
            box-shadow: 7px 7px 10px 0px rgba(0, 0, 0, 0.48);
            background-color: #ff2819;
        }

        #hamburger.show #wrapper {
            transition: transform 0.4s ease-in-out;
            transform: rotateZ(90deg);
        }

        #hamburger.show #one {
            transform: translateY(6px) rotateZ(45deg) scaleX(0.9);
        }

        #hamburger.show #thr {
            transform: translateY(-6px) rotateZ(-45deg) scaleX(0.9);
        }

        #hamburger.show #two {
            opacity: 0;
        }

        .nav-udfb.show {
            visibility: visible;
            opacity: 1;
        }

    </style>

    <script>
        jQuery(document).ready(function($) {
            $('#hamburger').click(function() {
                $('#hamburger').toggleClass('show');
                $('#udfb-overlay').toggleClass('show');
                $('.nav-udfb').toggleClass('show');
            });
        });
    </script>

<?php 
           }   // end if ( get_current_user_id() == get_query_var('author') ...
        }       // end if (is_user_logged_in())
    }           // end if (file_exists...
    return ob_get_clean(); 
}

add_shortcode('user_dashboard_float_button', 'user_dashboard_float_button');
