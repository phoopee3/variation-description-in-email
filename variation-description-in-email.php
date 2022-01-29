<?php
/**
 * Plugin Name: Variation Description in Email
 * Description: Add the variation description to a new order email
 * Version: 1.0.0
 * Author: Jason Lawton <jason@jasonlawton.com>
 */

// thanks in part to https://stackoverflow.com/questions/43564232/add-the-product-description-to-woocommerce-email-notifications
// and https://www.businessbloomer.com/woocommerce-easily-get-order-info-total-items-etc-from-order-object/

// Setting the email_is as a global variable
add_action('woocommerce_email_before_order_table', 'the_email_id_as_a_global', 1, 4);
function the_email_id_as_a_global($order, $sent_to_admin, $plain_text, $email ){
    $GLOBALS['email_id_str'] = $email->id;
}

// Displaying product description in new email notifications
add_action( 'woocommerce_order_item_meta_end', 'product_description_in_new_email_notification', 10, 4 );
function product_description_in_new_email_notification( $item_id, $item, $order = null, $plain_text = false ){

    // Getting the email ID global variable
    $refNameGlobalsVar = $GLOBALS;
    $email_id = $refNameGlobalsVar['email_id_str'];

    // If empty email ID we exit
    if(empty($email_id)) return;

    // Only for "New Order email notification"
    if ( 'new_order' == $email_id ) {

        $product = $item->get_product();
        
        if( $product->is_type('variation') ) {
            $product = wc_get_product( $item->get_product_id() );
        }

        // get the variation
        $variation_id = $item->get_variation_id();
        $variation_description = get_post_meta( $variation_id, '_variation_description', true );

        // Display the product description
        echo '<div class="variation-description"><p>' . $variation_description . '</p></div>';
    }
}