<?php 

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Start output buffering to prevent headers already sent error
ob_start();

$author_email = get_the_author_meta( 'user_email' );
$user = get_user_by( 'email', $author_email );
$userId = $user->ID;
$post_count = count_user_posts( $userId );

// Handle form submission
if (isset($_POST['submit_profile_update']) && wp_verify_nonce($_POST['_wpnonce'], 'update_user_info')) {
	// Update user information
	$user_data = array(
		'ID'           => get_current_user_id(),
		'first_name'   => sanitize_text_field($_POST['first_name']),
		'last_name'    => sanitize_text_field($_POST['last_name']),
		'display_name' => sanitize_text_field($_POST['display_name']),
		'description'  => sanitize_textarea_field($_POST['user_bio'])
	);

	wp_update_user($user_data);
    $user_avatar_image_url = sanitize_text_field($_POST['selected-avatar-image-url']);

	// Update user meta for phone number
	$phone_number = sanitize_text_field($_POST['phone_number']);
	update_user_meta(get_current_user_id(), 'phone_number', $phone_number);

	// Update user meta for show_email and show_number options
	update_user_meta(get_current_user_id(), 'show_email', isset($_POST['show_email']) ? 'yes' : 'no');
	update_user_meta(get_current_user_id(), 'show_number', isset($_POST['show_number']) ? 'yes' : 'no');
	
	update_user_meta( get_current_user_id(), 'user_facebook', sanitize_text_field($_POST['user_facebook'] ));
    update_user_meta( get_current_user_id(), 'user_instagram', sanitize_text_field($_POST['user_instagram'] ));
    update_user_meta( get_current_user_id(), 'user_twitter', sanitize_text_field($_POST['user_twitter'] ));
    update_user_meta( get_current_user_id(), 'user_linkedin', sanitize_text_field($_POST['user_linkedin'] ));
    update_user_meta( get_current_user_id(), 'user_google', sanitize_text_field($_POST['user_google'] ));
    update_user_meta( get_current_user_id(), 'user_youtube', sanitize_text_field($_POST['user_youtube'] ));
    update_user_meta( get_current_user_id(), 'user_GitHub', sanitize_text_field($_POST['user_GitHub'] ));
    update_user_meta( get_current_user_id(), 'user_StackOverFlow', sanitize_text_field($_POST['user_StackOverFlow'] ));
    update_user_meta( get_current_user_id(), 'user_whatsapp', sanitize_text_field($_POST['user_whatsapp'] ));
    update_user_meta( get_current_user_id(), 'user_other', sanitize_text_field($_POST['user_other'] ));
    update_user_meta( get_current_user_id(), 'custom_avatar_url', $user_avatar_image_url);

    if(isset($_POST['delete_avatar'])){
        delete_user_meta(get_current_user_id(), 'custom_avatar_url');
    }

}

$showEmail = get_user_meta($userId, 'show_email', true);
$showPhone = get_user_meta($userId, 'show_number', true);


//------------------------------------- Form HTML for user profile update ------------------------------------//

// function author_profile_edit_func(){

    $author_email = get_the_author_meta( 'user_email' );
    $user = get_user_by( 'email', $author_email );
    $userId = $user->ID;
    $current_user = wp_get_current_user();

    if (is_user_logged_in() && get_current_user_id() === $userId) { ?>

    <div class="float-lg-end AJDWP_update_profile_modal " data-bs-toggle="modal" data-bs-target="#edit_profile_Modal" style="cursor:pointer;color:#ff2819;">
        <span>Edit <b><?php echo get_the_author(); ?></b> profile ?</span> <span class="fa fa-edit fa-2x m-3" ></span>
    </div>

    <!-- The Modal -->
    <div class="edit_profile_modal modal" id="edit_profile_Modal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header text-center">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="AJDWP_up_modal_body text-dark">
                        <fieldset>
                            <form method="post" action="">
                                <!-- ----------- Show & Hide Email and Phone ----------- -->
                                <div class="d-flex justify-content-between mb-3">
                                    <label for="toggle-email" class="fw-bold">Show Email:</label>
                                    <div class="toggle-switch">
                                        <input type="checkbox" name="show_email" id="toggle-email" <?php checked(get_user_meta(get_current_user_id(), 'show_email', true), 'yes'); ?>>
                                        <label for="toggle-email"></label>
                                    </div>															
                                    
                                    <label for="toggle-phone" class="fw-bold">Show Phone Num:</label>
                                    <div class="toggle-switch">
                                        <input type="checkbox" name="show_number" id="toggle-phone" <?php checked(get_user_meta(get_current_user_id(), 'show_number', true), 'yes'); ?>>
                                        <label for="toggle-phone"></label>
                                    </div>
                                </div>

                                <div class="border-top border-dark my-3"></div>
                                
                                <!-- UPDATE USER AVATAR -->

                                <h5 class="AJDWP_header my-3 fw-bold"><?php _e('Change Your Profile Image'); ?></h5>
                                <div class="my-3">
                                    
                                    <div id="set-user-avatar-container">

                                        <button id="open-media-library">My Media</button>
                                        
                                        <?php          
                                        $user_avatar_image_selected = ''; 
                                        if(!empty(get_user_meta(get_current_user_id(), 'custom_avatar_url', true))){ ?>

                                            <img id="selected-image" src="<?php echo get_user_meta(get_current_user_id(), 'custom_avatar_url', true);?>" alt="Selected Image" style="width:100px; height:100px;">
                                        
                                        <?php } 
                                        
                                            $custom_avatar_url = get_user_meta(get_current_user_id(), 'custom_avatar_url', true);
                                            if (!empty($custom_avatar_url)) {
                                                $user_avatar_image_selected = esc_url($custom_avatar_url);
                                            } else {
                                                $author_email = get_the_author_meta('user_email');
                                                echo get_avatar($author_email, $size = '100', null, null, array('class' => array('rounded-circle border shadow')));
                                            }
                                        ?>

                                        <br>
                                            <!-- Checkbox to delete avatar -->
                                            <label for="delete_avatar">
                                                <input type="checkbox" name="delete_avatar" id="delete_avatar"> Delete Avatar
                                            </label>
                                        <br>

                                        <input type="hidden" id="selected-image-url" name="selected-avatar-image-url" value="<?php echo $user_avatar_image_selected ; ?>">
                                
                                    </div>

                                </div>

                                <div class="border-top border-dark my-3"></div>

                                <div class="row">
                                    <div class="col-11">
                                        <h5 class="modal-title text-dark fw-bold">Update Your Profile Details: </h5>
                                    </div>
                                    <div class="col-1">
                                        <button id="toggle-user-profile" class="user-profile-toggle-btn">+</button>
                                    </div>
                                </div>
                                <br>
                                <div class="edit-user-detals" id="edit-user-details" style="display: block;">
                                    <label for="first_name" class="fw-bold">First Name:</label>
                                    <input style="color: #7f0800;" type="text" name="first_name" id="first_name" value="<?php echo esc_attr($current_user->first_name); ?>"  autocomplete="given-name">

                                    <label for="last_name" class="fw-bold">Last Name:</label>
                                    <input  style="color: #7f0800;" type="text" name="last_name" id="last_name" value="<?php echo esc_attr($current_user->last_name); ?>" autocomplete="family-name">

                                    <label for="display_name" class="fw-bold">Display Name:</label>
                                    <input style="color: #7f0800;" type="text" name="display_name" id="display_name" value="<?php echo esc_attr($current_user->display_name); ?>" autocomplete="username">

                                    <label for="phone_number" class="fw-bold">Phone Number:</label>
                                    <input type="tel" style="color: #7f0800;" name="phone_number" id="phone_number" value="<?php echo esc_attr(get_user_meta($current_user->ID, 'phone_number', true)); ?>" autocomplete="tel">

                                    <label for="user_bio" class="fw-bold">Bio:</label>
                                    <textarea style="color: #7f0800;" name="user_bio" id="user_bio" rows="5" autocomplete="off"><?php echo esc_textarea($current_user->description); ?></textarea>
                                </div>
				<script>
					document.addEventListener("DOMContentLoaded", function() {
					    const toggleBtn = document.getElementById('toggle-user-profile');
					    const useDetailsDiv = document.getElementById('edit-user-details');
					
					    toggleBtn.addEventListener('click', function(event) {
					        event.preventDefault(); // Prevent the default button behavior
					
					        if (useDetailsDiv.style.display === "none") {
					            useDetailsDiv.style.display = "block";
					            toggleBtn.textContent = "-";
					        } else {
					            useDetailsDiv.style.display = "none";
					            toggleBtn.textContent = "+";
					        }
					    });
					});
				</script>
                                <?php do_action('edit_user_social_media'); ?>
                                <?php wp_nonce_field('update_user_info'); ?>
                                <input type="submit" value="Update" name="submit_profile_update">
                            </form>

                        </fieldset>

                    </div>
                </div>


            </div>
        </div>
    </div>


    <?php } ?>
</div>

<?php
// End output buffering and send output
ob_end_flush();
?>
