<?php 

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

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
                    <h5 class="modal-title text-dark fw-bold">Update Your Profile: </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="AJDWP_up_modal_body text-dark">
                        <fieldset>
                            <form method="post" action="">
                                <!-- ----------- Show & Hide Email and Phone ----------- -->
                                <div class="d-flex justify-content-between mb-3">
                                    <label class="fw-bold">Show Email:</label>
                                    <div class="toggle-switch">
                                        <input type="checkbox" name="show_email" id="toggle-email" <?php checked(get_user_meta(get_current_user_id(), 'show_email', true), 'yes'); ?>>
                                        <label for="toggle-email"></label>
                                    </div>															
                                    
                                    <label class="fw-bold">Show Phone Num:</label>
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
                                
                                        <script>
                                            jQuery(document).ready(function($){
                                                $('#open-media-library').on('click', function(e){
                                                    e.preventDefault();
                                
                                                    var mediaFrame = wp.media({
                                                        title: 'Select or Upload Media',
                                                        button: {
                                                            text: 'Use this media'
                                                        },
                                                        multiple: false
                                                    });
                                
                                                    mediaFrame.on('select', function(){
                                                        var attachment = mediaFrame.state().get('selection').first().toJSON();
                                                        var imageUrl = attachment.url;
                                
                                                        $('#selected-image').attr('src', imageUrl);
                                                        $('#selected-image-url').val(imageUrl);
                                                    });
                                
                                                    mediaFrame.open();
                                                });
                                            });
                                        </script>
                                    </div>

                                </div>

                                <div class="border-top border-dark my-3"></div>

                                <label for="first_name" class="fw-bold">First Name:</label>
                                <input style="color: #7f0800;" type="text" name="first_name" value="<?php echo esc_attr($current_user->first_name); ?>" >

                                <label for="last_name" class="fw-bold">Last Name:</label>
                                <input  style="color: #7f0800;" type="text" name="last_name" value="<?php echo esc_attr($current_user->last_name); ?>" >

                                <label for="display_name" class="fw-bold">Display Name:</label>
                                <input style="color: #7f0800;" type="text" name="display_name" value="<?php echo esc_attr($current_user->display_name); ?>" >

                                <label for="phone_number" class="fw-bold">Phone Number:</label>
                                <input type="tel" style="color: #7f0800;" name="phone_number" value="<?php echo esc_attr(get_user_meta($current_user->ID, 'phone_number', true)); ?>">

                                <label for="user_bio" class="fw-bold">Bio:</label>
                                <textarea style="color: #7f0800;" name="user_bio" rows="5"><?php echo esc_textarea($current_user->description); ?></textarea>															

                                <?php do_action('edit_user_social_media'); ?>
                                <?php wp_nonce_field('update_user_info'); ?>
                                <input type="submit" value="Update" name="submit_profile_update">
                            </form>
    <!-- -------------- CSS FOR TOGGLE BUTTON -------------- -->
                            <style>
                                /* Style for the toggle switch */
                                .toggle-switch {
                                    position: relative;
                                    display: inline-block;
                                    width: 40px;
                                    height: 20px;
                                }

                                .toggle-switch input {
                                    display: none;
                                }

                                .toggle-switch label {
                                    position: absolute;
                                    top: 0;
                                    left: 0;
                                    width: 40px;
                                    height: 20px;
                                    background-color: #ccc;
                                    border-radius: 10px;
                                    cursor: pointer;
                                }

                                .toggle-switch input:checked + label {
                                    background-color: #ff2819;
                                }

                                .toggle-switch label:after {
                                    content: '';
                                    position: absolute;
                                    top: 50%;
                                    left: 25%;
                                    transform: translate(-50%, -50%);
                                    width: 16px;
                                    height: 16px;
                                    background-color: #fff;
                                    border-radius: 50%;
                                    transition: 0.3s;
                                }

                                .toggle-switch input:checked + label:after {
                                    left: calc(160% - 16px);
                                    transform: translate(-160%, -50%);
                                }

                                th {
                                    justify-content: center;
                                    align-items: center;
                                    display: flex;
                                    padding: 30px !important;
                                    border: none !important;
                                }

                                td {
                                    border: none !important;
                                    width: 400px;
                                }

                                div#set-user-avatar-container > img#selected-image {
                                    border: 3px solid #333;
                                    border-radius: 3px;
                                    padding: 2px;
                                    margin-left: 3rem;
                                }

                                table.form-table > tbody {
                                    border: 1px solid #333;
                                    border-radius: 3px !important;
                                }
                            </style>


                        </fieldset>

                    </div>
                </div>


            </div>
        </div>
    </div>


    <?php } ?>
</div>

<?php //}

// add_shortcode( 'author_profile_edit', 'author_profile_edit_func' ); 
?>