<?php  

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

include dirname(__FILE__)."/class-wp-bootstrap-navwalker.php";

//-------------------------------------------------------------------------//

register_nav_menus( [ 'AJDWPMenu1' => 'AJDWP Menu' ] );

//-------------------------------------------------------------------------//

if ( ! function_exists( 'AJDWP_primary_navigation_func' ) ) :
function AJDWP_primary_navigation_func() {
	wp_nav_menu( 
		array(  
			'theme_location' => 'AJDWPMenu1',
			'container'  => '',
			'menu_class' => 'menu-wrap navbar-nav me-auto',
			'fallback_cb' => 'WP_Bootstrap_Navwalker::fallback',
			'walker' => new WP_Bootstrap_Navwalker()
			) 
		);
	} 
endif;
add_action( 'AJDWP_primary_navigation', 'AJDWP_primary_navigation_func' );


function AJDWP_avatar_sm_func() {
    ?>
        <?php if(is_user_logged_in()){ $AJDWP_user_data = wp_get_current_user(); ?>
            <div class="AJDWP_avatar_sm">
                    <a href='<?php echo get_author_posts_url (wp_get_current_user()->ID)?>'> 
                        <?php if(!empty(get_user_meta(get_current_user_id(), 'custom_avatar_url', true))){ ?>
                            <img id="selected-image" src="<?php echo get_user_meta(get_current_user_id(), 'custom_avatar_url', true);?>" alt="Selected Image" style="width:50px; height:50px;">                           
                        <?php } else{ echo get_avatar($AJDWP_user_data);} ?>
                    </a>
                    <br>
                    <?php echo($AJDWP_user_data->data->display_name . "<br>"); ?>
                    <?php //echo($AJDWP_user_data->data->user_email."<br>"); ?>
                    <a href="<?php echo wp_logout_url(); ?>" title="Logout">Logout</a>
            </div>
        <?php } ?>
    <?php
} 

add_action( 'AJDWP_avatar_sm', 'AJDWP_avatar_sm_func' );

function AJDWP_avatar_func() {
    ?>
        <?php if(is_user_logged_in()){ $AJDWP_user_data = wp_get_current_user(); ?>
            <div class="AJDWP_avatar">
                    <a href='<?php echo get_author_posts_url (wp_get_current_user()->ID)?>'> 
                        <?php if(!empty(get_user_meta(get_current_user_id(), 'custom_avatar_url', true))){ ?>
                            <img id="selected-image" src="<?php echo get_user_meta(get_current_user_id(), 'custom_avatar_url', true);?>" alt="Selected Image" style="width:50px; height:50px;">                           
                        <?php } else{ echo get_avatar($AJDWP_user_data);} ?>
                    </a>
                    <br>
                    <?php echo($AJDWP_user_data->data->display_name . "<br>"); ?>
                    <?php //echo($AJDWP_user_data->data->user_email."<br>"); ?>
                    <a href="<?php echo wp_logout_url(); ?>" title="Logout">Logout</a>
            </div>
        <?php } ?>
    <?php
} 

add_action( 'AJDWP_avatar', 'AJDWP_avatar_func' );




function AJDWP_search_modal_func() {
    ?>
        <div class="float-lg-end AJDWP_search_modal">
                <span type="" class="fa fa-search fa-2x text-primary mx-3" data-bs-toggle="modal" data-bs-target="#myModal" style="<?php if(!is_user_logged_in()){echo 'padding-right: 2rem;';} ?> cursor:pointer;"></span>
        </div>

        <!-- The Modal -->
        <div class="search_modal modal" id="myModal">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header text-center">
                        <h4 class="modal-title">Seach </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body my-3 d-flex justify-content-center align-items-center text-center">
                        <div class="AJDWP_search">
                            <?php get_search_form(); ?>
                        </div>
                    </div>

                    <!-- Modal footer
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </div> -->

                </div>
            </div>
        </div>


    <?php 
} 

add_action( 'AJDWP_search_modal', 'AJDWP_search_modal_func' );



function AJDWP_search_form_func() {
    ?>
        <div class="AJDWP_search_form">
            <?php get_search_form(); ?>
        </div>
    <?php
} 

add_action( 'AJDWP_search_form', 'AJDWP_search_form_func' );



function AJDWP_minicart_red_func() {
    ?>
        <?php if ( class_exists( 'WooCommerce' ) && !is_cart() && !is_checkout() ) { ?>
                <?php if(is_shop() || is_product_category() || is_single()) {?>

                <div class="mini_cart_and_sum  ">
                    
                    <div class="currency_for_cart_sum dropdown " data-bs-toggle="dropdown">
                        <?php 
                            echo get_woocommerce_currency_symbol()." ". WC()->cart->get_subtotal(); 
                        ?>
                    </div>


                    <div class="dropdown ">
                        
                        <div class="text-danger dropdown-toggle minicart-wrapper " data-bs-toggle="dropdown" style="<?php if(!is_user_logged_in()){echo 'padding-right: 4rem;';} ?>">

                                <?php echo "<span class='count-minicart'>".count( WC()->cart->get_cart() ) ."</span>"; ?>
                                <i class="fa fa-shopping-cart minicart-icon"></i>
        
                        </div>
                            
                        <ul class="dropdown-menu shadow">
                            <div class="AJDWP_woocommerce_mini_cart">
                                <?php woocommerce_mini_cart();?>
                            </div>
                        </ul>
                    
                    </div>
            

                </div>

            <?php } ?>
        <?php } ?>
    <?php 
} 

add_action( 'AJDWP_minicart_red', 'AJDWP_minicart_red_func' );




function AJDWP_minicart_blue_func() {
    ?>
        <?php if ( class_exists( 'WooCommerce' ) && !is_cart() && !is_checkout() && !is_shop() && !is_product_category() && !is_single()) { ?>

            <div class="mini_cart_and_sum_2  ">
                
                <div class="currency_for_cart_sum dropdown_2 " data-bs-toggle="dropdown">
                    <?php 
                        echo get_woocommerce_currency_symbol()." ". WC()->cart->get_subtotal(); 
                    ?>
                </div>


                <div class="dropdown ">
                    
                    <div class="text-primary dropdown-toggle  minicart-wrapper_2 " data-bs-toggle="dropdown" style="<?php if(!is_user_logged_in()){echo 'padding-right: 4rem;';} ?>">
                                
                        <?php echo "<span class='count-minicart'>".count( WC()->cart->get_cart() ) ."</span>"; ?>
                        <i class="fa fa-shopping-cart minicart-icon_2"></i>
                    
                    </div>
                        
                    <ul class="dropdown-menu shadow">
                        <div class="AJDWP_woocommerce_mini_cart_2">
                            <?php woocommerce_mini_cart();?>
                        </div>
                    </ul>
                
                </div>


            </div>

        <?php } ?>    

    <?php 
} 

add_action( 'AJDWP_minicart_blue', 'AJDWP_minicart_blue_func' );

?>