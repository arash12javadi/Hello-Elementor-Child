<?php 

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

//------------------------------------- Add input for user social media ------------------------------------//

add_action( 'show_user_profile', 'extra_user_profile_fields' );
add_action( 'edit_user_social_media', 'extra_user_profile_fields' );

function extra_user_profile_fields() { 

?>

    <div class="row">
        <div class="col-11">
            <h5 class="fw-bold">Add Your Social Media Links</h5>
        </div>
        <div class="col-1">
            <?php if (!is_admin()) : ?>
            <button id="toggle-social-links" class="user-profile-toggle-btn">+</button>
            <?php endif; ?>
        </div>
    </div>
    <br>
    <?php if (!is_admin()) { ?>
    <table class="form-table" id="social-links-table" style="display: none;">
    <?php }else{ ?>
    <table class="form-table" id="social-links-table" style="display: block;">
    <?php } ?>
    <tr>
        <th><label for="user_facebook">Facebook</label></th>
        <td>
            <input type="text" name="user_facebook" id="user_facebook" value="<?php echo esc_attr( get_the_author_meta( 'user_facebook', get_current_user_id() ) ); ?>" class="regular-text" />
        </td>
    </tr>
    <tr>
        <th><label for="user_instagram">Instagram</label></th>
        <td>
            <input type="text" name="user_instagram" id="user_instagram" value="<?php echo esc_attr( get_the_author_meta( 'user_instagram', get_current_user_id() ) ); ?>" class="regular-text" />
        </td>
    </tr>
    <tr>
        <th><label for="user_twitter">Twitter</label></th>
        <td>
            <input type="text" name="user_twitter" id="user_twitter" value="<?php echo esc_attr( get_the_author_meta( 'user_twitter', get_current_user_id() ) ); ?>" class="regular-text" />
        </td>
    </tr>
    <tr>
        <th><label for="user_linkedin">Linkedin</label></th>
        <td>
            <input type="text" name="user_linkedin" id="user_linkedin" value="<?php echo esc_attr( get_the_author_meta( 'user_linkedin', get_current_user_id() ) ); ?>" class="regular-text" />
        </td>
    </tr>
    <tr>
        <th><label for="user_google">Google</label></th>
        <td>
            <input type="text" name="user_google" id="user_google" value="<?php echo esc_attr( get_the_author_meta( 'user_google', get_current_user_id() ) ); ?>" class="regular-text" />
        </td>
    </tr>
    <tr>
        <th><label for="user_youtube">YouTube</label></th>
        <td>
            <input type="text" name="user_youtube" id="user_youtube" value="<?php echo esc_attr( get_the_author_meta( 'user_youtube', get_current_user_id() ) ); ?>" class="regular-text" />
        </td>
    </tr>
    <tr>
        <th><label for="user_GitHub">GitHub</label></th>
        <td>
            <input type="text" name="user_GitHub" id="user_GitHub" value="<?php echo esc_attr( get_the_author_meta( 'user_GitHub', get_current_user_id() ) ); ?>" class="regular-text" />
        </td>
    </tr>
    <tr>
        <th><label for="user_StackOverFlow">Stack Overflow</label></th>
        <td>
            <input type="text" name="user_StackOverFlow" id="user_StackOverFlow" value="<?php echo esc_attr( get_the_author_meta( 'user_StackOverFlow', get_current_user_id() ) ); ?>" class="regular-text" />
        </td>
    </tr>
    <tr>
        <th><label for="user_whatsapp">WhatsApp</label></th>
        <td>
            <input type="text" name="user_whatsapp" id="user_whatsapp" placeholder="https://wa.me/1XXXXXXXXXX" value="<?php echo esc_attr( get_the_author_meta( 'user_whatsapp', get_current_user_id() ) ); ?> " class="regular-text" />
        </td>
    </tr>


    <tr>
        <th><label for="user_other">Other Links</label></th>
        <td>
            <input type="text" name="user_other" id="user_other" value="<?php echo esc_attr( get_the_author_meta( 'user_other', get_current_user_id() ) ); ?>" class="regular-text" />
        </td>
    </tr>
    
    </table>

	<script>
	
	document.addEventListener("DOMContentLoaded", function() {
	    const toggleBtn = document.getElementById('toggle-social-links');
	    const table = document.getElementById('social-links-table');
	
	    toggleBtn.addEventListener('click', function(event) {
	        event.preventDefault(); // Prevent the default button behavior
	
	        if (table.style.display === "none") {
	            table.style.display = "table";
	            toggleBtn.textContent = "-";
	        } else {
	            table.style.display = "none";
	            toggleBtn.textContent = "+";
	        }
	    });
	});
		
	</script>

<?php 

}


//------------------------------------- Save user social medias as usermeta ------------------------------------//


add_action( "User_Social_Media", "User_Social_Media_func" );
function User_Social_Media_func(){

?>

<div class="text-left justify-content-start  font-weight-bold h5">
    <?php echo "Social medias: ";?>
</div>

<div class="text-left justify-content-center  font-weight-bold h5 py-2">
    
    <?php if(get_user_meta( get_the_author_meta('ID'), 'user_facebook', true)){ ?>
            <!-- user_facebook -->
            <a href="<?php echo get_user_meta( get_the_author_meta('ID'), 'user_facebook', true); ?>" target="_blank" ><i class="fa-brands fa-facebook-f fa-2x m-3" style="color: #3b5998;"></i></a>
    <?php } ?>


    <?php if(get_user_meta( get_the_author_meta('ID'), 'user_twitter', true)){ ?>
            <!-- user_twitter -->
            <a href="<?php echo get_user_meta( get_the_author_meta('ID'), 'user_twitter', true); ?>" target="_blank" ><i class="fa fa-twitter fa-2x m-3" style="color: #55acee;"></i></a>
    <?php } ?>


    <?php if(get_user_meta( get_the_author_meta('ID'), 'user_google', true)){ ?>
            <!-- user_google -->
            <a href="<?php echo get_user_meta( get_the_author_meta('ID'), 'user_google', true); ?>" target="_blank" ><i class="fa fa-google fa-2x m-3" style="color: #dd4b39;"></i></a>
    <?php } ?>


    <?php if(get_user_meta( get_the_author_meta('ID'), 'user_instagram', true)){ ?>
            <!-- user_instagram -->
            <a href="<?php echo get_user_meta( get_the_author_meta('ID'), 'user_instagram', true); ?>" target="_blank" ><i class="fa fa-instagram fa-2x m-3" style="color: #ac2bac;"></i></a>
    <?php } ?>


    <?php if(get_user_meta( get_the_author_meta('ID'), 'user_linkedin', true)){ ?>
            <!-- user_linkedin -->
            <a href="<?php echo get_user_meta( get_the_author_meta('ID'), 'user_linkedin', true); ?>" target="_blank" ><i class="fa fa-linkedin fa-2x m-3" style="color: #0082ca;"></i></a>
    <?php } ?>


    <?php if(get_user_meta( get_the_author_meta('ID'), 'user_youtube', true)){ ?>
            <!-- user_youtube -->
            <a href="<?php echo get_user_meta( get_the_author_meta('ID'), 'user_youtube', true); ?>" target="_blank" ><i class="fa fa-youtube fa-2x m-3" style="color: #c4302b;"></i></a>
    <?php } ?>

    <?php if(get_user_meta( get_the_author_meta('ID'), 'user_GitHub', true)){ ?>
            <!-- user_GitHub -->
            <a href="<?php echo get_user_meta( get_the_author_meta('ID'), 'user_GitHub', true); ?>" target="_blank" ><i class="fa-brands fa-github fa-2x m-3" style="color: #2dba4e;"></i></a>
    <?php } ?>

    <?php if(get_user_meta( get_the_author_meta('ID'), 'user_StackOverFlow', true)){ ?>
            <!-- user_StackOverFlow -->
            <a href="<?php echo get_user_meta( get_the_author_meta('ID'), 'user_StackOverFlow', true); ?>" target="_blank" ><i class="fa-brands fa-stack-overflow fa-2x m-3" style="color: #F48024;"></i></a>
    <?php } ?>


    <?php if(get_user_meta( get_the_author_meta('ID'), 'user_whatsapp', true)){ ?>
            <!-- user_whatsapp -->
            <a href="<?php echo get_user_meta( get_the_author_meta('ID'), 'user_whatsapp', true); ?>" target="_blank" ><i class="fa fa-whatsapp fa-2x m-3" style="color: #25D366;"></i></a>
    <?php } ?>


    <?php if(get_user_meta( get_the_author_meta('ID'), 'user_other', true)){ ?>
            <!-- user_other -->
            <a href="<?php echo get_user_meta( get_the_author_meta('ID'), 'user_other', true); ?>" target="_blank" ><i class="fa fa-link fa-2x m-3" style="color: gold;"></i></a>
    <?php } ?>

</div>

<?php } 

function has_social_media_links() {
    $author_id = get_queried_object_id(); // This gets the author ID from the author archive page
    // Retrieve user data
    $the_id_of_the_author_page = get_userdata($author_id)->ID;
    // Define social media fields
    $social_media_fields = [
        'user_facebook',
        'user_twitter',
        'user_google',
        'user_instagram',
        'user_linkedin',
        'user_youtube',
        'user_GitHub',
        'user_StackOverFlow',
        'user_whatsapp',
        'user_other'
    ];

    // Check if any social media fields have a value
    foreach ($social_media_fields as $field) {
        if (get_user_meta($the_id_of_the_author_page, $field, true)) {
            return true;
        }
    }

    return false;
}


?>
