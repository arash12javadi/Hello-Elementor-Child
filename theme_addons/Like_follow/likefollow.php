<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$options = get_option('AJDWP_theme_options');
if (!empty($options['like_follow_system'])) {

    add_action('AJDWP_like_follow_social', 'AJDWP_like_follow_social_func');

    function AJDWP_like_follow_social_func()
    {
        if (!is_user_logged_in()) {
            return;
        }

        include dirname(__FILE__) . "/likeFollowCounters.php";

        $user_id = get_current_user_id();
        $author_id = get_the_author_meta('ID');
        $post_id = get_the_ID();
?>
        <div class="like_follow d-flex align-items-center">
            <div class="like_follow_btns">
                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                <input type="hidden" name="author_id" value="<?php echo $author_id; ?>">

                <?php if (!is_author()) : ?>
                    <button class="btn like_btn position-relative border-0"
                        type="button"
                        id="like_button_<?php echo $post_id; ?>"
                        name="like_button"
                        style="<?php echo $like_exsists ? 'display:none;' : ''; ?>">
                        <i class="fa fa-thumbs-up p-2 rounded-circle border border-1" style="font-size: 30px; cursor:pointer; color:lightgrey;"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary" id="total_likes_<?php echo $post_id; ?>">
                            <?php echo $totalLikes; ?>
                        </span>
                    </button>

                    <button class="btn unlike_btn position-relative border-0"
                        type="button"
                        id="unlike_button_<?php echo $post_id; ?>"
                        name="unlike_button"
                        style="<?php echo !$like_exsists ? 'display:none;' : ''; ?>">
                        <i class="fa fa-thumbs-up p-2 rounded-circle border border-1 border-primary text-primary" style="font-size: 30px; cursor:pointer;"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary" id="total_likes2_<?php echo $post_id; ?>">
                            <?php echo $totalLikes; ?>
                        </span>
                    </button>
                <?php endif; ?>

                <?php if (is_author()) : ?>
                    <button class="btn follow_btn position-relative border-0"
                        type="button"
                        id="follow_button_<?php echo $author_id; ?>"
                        name="follow_button"
                        style="<?php echo $follow_exsists ? 'display:none;' : ''; ?>">
                        <i class="fa fa-user-plus p-2 rounded-circle border border-1" style="font-size: 30px; cursor:pointer; color:lightgrey; width: 48px;"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary" id="total_follow_<?php echo $author_id; ?>">
                            <?php echo $totalfollow; ?>
                        </span>
                    </button>

                    <button class="btn unfollow_btn position-relative border-0"
                        type="button"
                        id="unfollow_button_<?php echo $author_id; ?>"
                        name="unfollow_button"
                        style="<?php echo !$follow_exsists ? 'display:none;' : ''; ?>">
                        <i class="fa fa-users p-2 rounded-circle border border-1 border-primary text-primary" style="font-size: 30px; cursor:pointer; width: 48px;"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary" id="total_follow2_<?php echo $author_id; ?>">
                            <?php echo $totalfollow; ?>
                        </span>
                    </button>
                <?php endif; ?>
            </div>
        </div>
<?php
    }
}
?>