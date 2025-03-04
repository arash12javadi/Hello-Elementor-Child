jQuery(function ($) {
  function updateMiniCart(cartItemKey, quantity) {
    $.ajax({
      url: wc_add_to_cart_params.ajax_url,
      type: "POST",
      data: {
        action: "AJDWP_update_cart_quantity",
        cart_item_key: cartItemKey,
        quantity: quantity,
      },
      beforeSend: function () {
        $(".AJDWP_woocommerce_mini_cart").addClass("loading");
      },
      success: function (response) {
        if (response.success) {
          // Update header mini cart button content
          $("#AJDWP_minicart").html(response.mini_cart_button);
          // Update offcanvas modal content
          $("#miniCartOffcanvas .offcanvas-body").html(response.mini_cart_modal);
        } else {
          alert(response.message);
        }
      },
      complete: function () {
        $(".AJDWP_woocommerce_mini_cart").removeClass("loading");
      },
    });
  }

  $(document).on("change", ".mini-cart-qty", function () {
    let cartItemKey = $(this).data("cart-item");
    let quantity = $(this).val();
    updateMiniCart(cartItemKey, quantity);
  });

  $(document).on("click", ".mini-cart-remove", function (e) {
    e.preventDefault();
    let cartItemKey = $(this).data("cart-item");
    updateMiniCart(cartItemKey, 0);
  });
});
