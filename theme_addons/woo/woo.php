<?php
if (!defined('ABSPATH')) exit; // Prevent direct access
//_____________________________________ Mini Cart AJAX _____________________________________//

// Mini Cart Button Function (Header)
function AJDWP_minicart_button()
{
    if (! class_exists('WooCommerce') || is_cart() || is_checkout()) return;

    $cart_count = WC()->cart->get_cart_contents_count();
    $cart_total = WC()->cart->get_subtotal();
?>
    <div class="mini_cart_and_sum position-relative ms-1" id="AJDWP_minicart">
        <button class="btn border-0 py-3 p-lg-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#miniCartOffcanvas" aria-controls="miniCartOffcanvas">
            <span class="mx-2"><?php echo get_woocommerce_currency_symbol() . " " . esc_html($cart_total); ?></span>
            <i class="shopping-cart-icon fa fa-shopping-cart mb-lg-0"></i>
            <span class="count-minicart position-lg-absolute translate-middle badge border border-light rounded-circle bg-danger"><?php echo esc_html($cart_count); ?></span>
        </button>
    </div>
<?php
}
add_action('AJDWP_minicart', 'AJDWP_minicart_button');

// Offcanvas Modal for Mini Cart (Modal Content Only)
function AJDWP_minicart_offcanvas()
{
    if (! class_exists('WooCommerce') || is_cart() || is_checkout()) return;
?>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="miniCartOffcanvas" aria-labelledby="miniCartOffcanvasLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="miniCartOffcanvasLabel"><?php esc_html_e('Your Cart', 'woocommerce'); ?></h5>
            <button type="button" class="btn-close text-reset rounded-circle p-3 minicart-sidebar-close-btn" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <?php AJDWP_minicart_modal(); // Output only the modal content 
            ?>
        </div>
    </div>
<?php
}
add_action('wp_footer', 'AJDWP_minicart_offcanvas');

// Offcanvas Modal Content Function
function AJDWP_minicart_modal()
{
    if (! class_exists('WooCommerce') || is_cart() || is_checkout()) return;
?>
    <div class="AJDWP_woocommerce_mini_cart" id="AJDWP_woocommerce_mini_cart">
        <?php if (WC()->cart->is_empty()) : ?>
            <div class="text-center">
                <i class="fa fa-shopping-basket fa-3x text-muted"></i>
                <p class="mt-2 text-muted fw-bold"><?php esc_html_e('Your cart is empty.', 'woocommerce'); ?></p>
                <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn btn-sm btn-dark">
                    <?php esc_html_e('Start Shopping', 'woocommerce'); ?>
                </a>
            </div>
        <?php else : ?>
            <ul class="woocommerce-mini-cart list-group mb-3">
                <?php foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                    $product = $cart_item['data'];
                    if (! $product || ! $product->exists()) continue;
                    $product_id = $product->get_id();
                ?>
                    <li class="woocommerce-mini-cart-item list-group-item d-flex align-items-center justify-content-between px-1">
                        <a href="<?php echo esc_url(get_permalink($product_id)); ?>" class="me-2">
                            <?php echo $product->get_image('thumbnail', ['class' => 'rounded']); ?>
                        </a>
                        <div class="flex-grow-1">
                            <a href="<?php echo esc_url(get_permalink($product_id)); ?>" class="text-dark fw-bold">
                                <?php echo $product->get_name(); ?>
                            </a>
                            <div class="d-flex align-items-center justify-content-between mt-1">
                                <!-- Quantity Input -->
                                <input type="number" class="mini-cart-qty form-control form-control-sm w-25 me-2"
                                    data-cart-item="<?php echo esc_attr($cart_item_key); ?>"
                                    value="<?php echo esc_attr($cart_item['quantity']); ?>" min="1">
                                <!-- Display Unit Price x Quantity = Line Total -->
                                <span class="mini-cart-price fw-bold">
                                    <?php
                                    // Get unit price and format it using wc_price
                                    $unit_price = $product->get_price();
                                    $formatted_unit_price = wc_price($unit_price);
                                    // Get line total (unit price × quantity)
                                    $line_total = WC()->cart->get_product_subtotal($product, $cart_item['quantity']);
                                    echo $formatted_unit_price . ' x ' . esc_html($cart_item['quantity']) . ' = ' . $line_total;
                                    ?>
                                </span>
                            </div>
                        </div>
                        <a href="#" class="mini-cart-remove text-danger ms-2" data-cart-item="<?php echo esc_attr($cart_item_key); ?>">
                            <i class="fa fa-times rounded-circle p-1 d-flex justify-content-center bg-dark text-white item-remove-btn"></i>
                        </a>
                    </li>

                <?php } ?>
            </ul>

            <div class="d-flex justify-content-between align-items-center my-3 bg-dark text-white p-3 rounded">
                <span class="fw-bold"><?php esc_html_e('Basket Total:', 'woocommerce'); ?></span>
                <span class="fw-bold"><?php echo WC()->cart->get_cart_subtotal(); ?></span>
            </div>

            <div class="text-center">
                <a href="<?php echo wc_get_cart_url(); ?>" class="btn btn-sm btn-outline-dark me-2"><?php esc_html_e('View Cart', 'woocommerce'); ?></a>
                <a href="<?php echo wc_get_checkout_url(); ?>" class="btn btn-sm btn-dark"><?php esc_html_e('Checkout', 'woocommerce'); ?></a>
            </div>

        <?php endif; ?>
    </div>
<?php
}

function AJDWP_woocommerce_cart_fragments($fragments)
{
    // Update mini cart button (in header)
    ob_start();
    AJDWP_minicart_button();
    $fragments['#AJDWP_minicart'] = ob_get_clean();

    // Update offcanvas modal content (mini cart modal)
    ob_start();
    AJDWP_minicart_modal();
    $fragments['#AJDWP_woocommerce_mini_cart'] = ob_get_clean();

    return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'AJDWP_woocommerce_cart_fragments');

function AJDWP_update_cart_quantity()
{
    if (! isset($_POST['cart_item_key']) || ! isset($_POST['quantity'])) {
        wp_send_json(['success' => false, 'message' => 'Invalid request.']);
    }

    $cart_item_key = sanitize_text_field($_POST['cart_item_key']);
    $quantity      = (int) $_POST['quantity'];

    if ($quantity <= 0) {
        WC()->cart->remove_cart_item($cart_item_key);
    } else {
        WC()->cart->set_quantity($cart_item_key, $quantity);
    }

    WC()->cart->calculate_totals();

    // Capture the updated header button
    ob_start();
    AJDWP_minicart_button();
    $mini_cart_button = ob_get_clean();

    // Capture the updated offcanvas modal content
    ob_start();
    AJDWP_minicart_modal();
    $mini_cart_modal = ob_get_clean();

    wp_send_json([
        'success'            => true,
        'mini_cart_button'   => $mini_cart_button,
        'mini_cart_modal'    => $mini_cart_modal,
        'cart_total'         => WC()->cart->get_cart_subtotal()
    ]);
}
add_action('wp_ajax_AJDWP_update_cart_quantity', 'AJDWP_update_cart_quantity');
add_action('wp_ajax_nopriv_AJDWP_update_cart_quantity', 'AJDWP_update_cart_quantity');


//_____________________________________ WOO Extra Container _____________________________________//

/**
 * (Optional) Extra container around the standard Woo shop loop.
 */
function AJDWP_products_container_open()
{
    echo '<div id="woo-products-container">';
}
add_action('woocommerce_before_shop_loop', 'AJDWP_products_container_open', 15);

function AJDWP_products_container_close()
{
    echo '</div>';
}
add_action('woocommerce_after_shop_loop', 'AJDWP_products_container_close', 15);
