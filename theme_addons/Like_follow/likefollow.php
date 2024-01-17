<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
add_action('AJDWP_like_follow_social', 'AJDWP_like_follow_social_func');

function AJDWP_like_follow_social_func()
{

    if (is_user_logged_in()) {

        // include get_template_directory()."/theme_addons/like_follow/likeFollowCounters.php";
        // include "likeFollowCounters.php";
        include dirname(__FILE__). "/likeFollowCounters.php";

        ?>
        <div class="like_follow d-flex align-items-center">

            <div class="like_follow_btns">
                <input type="hidden" name="user_id"  value="<?php echo get_current_user_id(); ?>">
                <input type="hidden" name="author_id"  value="<?php the_author_meta('ID'); ?>">

                <?php if (!is_author()) { ?>

                    <button class="btn like_btn position-relative border-0" 
                            type="submit" 
                            id="like_button_<?php echo get_the_ID(); ?>" 
                            name="like_button" 
                            style="<?php if ($like_exsists == 1) {echo 'display:none;';} ?>"
                    >
                        <i class="fa fa-thumbs-up p-2 rounded-circle border border-1 " style="font-size: 30px;cursor:pointer;color:lightgrey;"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary" id="total_likes_<?php echo get_the_ID(); ?>"><?php echo $totalLikes; ?></span>
                    </button>


                    <button class="btn unlike_btn position-relative border-0" 
                            type="submit" 
                            id="unlike_button_<?php echo get_the_ID(); ?>" 
                            name="unlike_button" 
                            style="<?php if ($like_exsists == 0) {echo 'display:none;';} ?>"
                    >
                        <i class="fa fa-thumbs-up p-2 rounded-circle border border-1 border-primary text-primary" style="font-size: 30px;cursor:pointer;"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary" id="total_likes2_<?php echo get_the_ID(); ?>"><?php echo $totalLikes; ?></span>
                    </button>

                <?php } ?>
                <?php if (is_author()) { ?>
                    


                    <button class="btn follow_btn position-relative border-0" 
                            type="submit" 
                            id="follow_button_<?php the_author_meta('ID'); ?>" 
                            name="follow_button" 
                            style="<?php if ($follow_exsists == 1) {echo 'display:none;';} ?>"
                    >
                        <i class="fa fa-user-plus p-2 rounded-circle border border-1 " style="font-size: 30px;cursor:pointer;color:lightgrey;width: 48px;"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary" id="total_follow_<?php the_author_meta('ID'); ?>"><?php echo $totalfollow; ?></span>
                    </button>

                    <button class="btn unfollow_btn position-relative border-0" 
                            type="submit" 
                            id="unfollow_button_<?php the_author_meta('ID'); ?>" 
                            name="unfollow_button" 
                            style="<?php if ($follow_exsists == 0) {echo 'display:none;';} ?>"
                    >
                        <i class="fa fa-users p-2 rounded-circle border border-1 border-primary text-primary" style="font-size: 30px;cursor:pointer;width: 48px;"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary" id="total_follow2_<?php the_author_meta('ID'); ?>"><?php echo $totalfollow; ?></span>
                    </button>

                <?php } ?>
            </div>

        </div>

        <?php

    }
}
?>