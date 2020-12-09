<?php
/**
 * Cart totals
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-totals.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 2.3.6
 */

defined( 'ABSPATH' ) || exit;

?>
<div class="cart_totals <?php echo ( WC()->customer->has_calculated_shipping() ) ? 'calculated_shipping' : ''; ?>">

  <?php do_action( 'woocommerce_before_cart_totals' ); ?>

  <table cellspacing="0" class="shop_table">

    <tr class="cart-subtotal">
      <th><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></th>
      <td class="text-right" data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>"><?php wc_cart_totals_subtotal_html(); ?></td>
    </tr>

    <tr>
      <th>Discount</th>
      <td></td>
    </tr>

    <?php
    $user_id = get_current_user_id();
    $getPointMember = getPointMember($user_id);
    $my_point = 0;
    if ($getPointMember) {
      $my_point = $getPointMember->point_balance;
    }
    $checkbox_redemp_point = WC()->session->get( 'enable_redemp_point');
    $use_point = WC()->session->get( 'used_redemp_point', 0);
    ?>
    <style>
      .redemp_point {
        color: red;
        cursor: pointer;
      }
      .line-used-point {
        color: silver;
      }
      .use-redemp-notice {
        font-size: 1rem;
        color: #3f3f3f;
        font-weight: normal;
      }
    </style>

    <?php if ($my_point): ?>
    <tr>
      <th style="width: 60%">
        <p>Point Redemption</p>
        <p class="use-redemp-notice">
          <?php echo !!$checkbox_redemp_point ? sprintf('You spend %d points on this order', $use_point) : '' ?>
        </p>
      </th>
      <td style="text-align: right;">
        <p class="use-redemp-notice"><?php echo $my_point; ?></p>
        <p>
          <input type="checkbox" id="checkbox_redemp_point" name="checkbox_redemp_point" <?php echo !!$checkbox_redemp_point ? 'checked' : '' ?>> Use point
        </p>
      </td>
    </tr>
    <?php endif; ?>

    <?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
      <tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
        <th><?php echo sanitize_title( $code ); ?></th>
        <td class="position-relative text-right" data-title="<?php echo esc_attr( wc_cart_totals_coupon_label( $coupon, false ) ); ?>">
          <?php wc_cart_totals_coupon_html( $coupon ); ?>
        </td>
      </tr>
    <?php endforeach; ?>

    <?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

      <?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>

      <?php wc_cart_totals_shipping_html(); ?>

      <?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>

    <?php elseif ( WC()->cart->needs_shipping() && 'yes' === get_option( 'woocommerce_enable_shipping_calc' ) ) : ?>

      <tr class="shipping">
        <th><?php esc_html_e( 'Shipping & Handling', 'woocommerce' ); ?></th>
        <td data-title="<?php esc_attr_e( 'Shipping & Handling', 'woocommerce' ); ?>"><?php woocommerce_shipping_calculator(); ?></td>
      </tr>

    <?php endif; ?>

    <?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
      <tr class="fee">
        <th><?php echo esc_html( $fee->name ); ?></th>
        <td data-title="<?php echo esc_attr( $fee->name ); ?>"><?php wc_cart_totals_fee_html( $fee ); ?></td>
      </tr>
    <?php endforeach; ?>

    <?php
    if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) {
      $taxable_address = WC()->customer->get_taxable_address();
      $estimated_text  = '';

      if ( WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping() ) {
        /* translators: %s location. */
        $estimated_text = sprintf( ' <small>' . esc_html__( '(estimated for %s)', 'woocommerce' ) . '</small>', WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ] );
      }

      if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) {
        foreach ( WC()->cart->get_tax_totals() as $code => $tax ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.OverrideProhibited
          ?>
          <tr class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
            <th><?php echo esc_html( $tax->label ) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></th>
            <td data-title="<?php echo esc_attr( $tax->label ); ?>"><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
          </tr>
          <?php
        }
      } else {
        ?>
        <tr class="tax-total">
          <th><?php echo esc_html( WC()->countries->tax_or_vat() ) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></th>
          <td data-title="<?php echo esc_attr( WC()->countries->tax_or_vat() ); ?>"><?php wc_cart_totals_taxes_total_html(); ?></td>
        </tr>
        <?php
      }
    }
    ?>

    <?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>
    <?php
    if ( wc_tax_enabled() && WC()->cart->display_prices_including_tax() ):
        $cart_tax_totals  = WC()->cart->get_tax_totals();
    ?>
      <tr class="order-tax">
          <th><?php esc_html_e( 'Tax', 'woocommerce' ); ?></th>
          <td data-title="Total">
              <strong>
                  <span class="woocommerce-Price-amount amount woocommerce-Price-amount-front">
                      <span><?= '$'.$cart_tax_totals['TAX-1']->amount ?></span>
                  </span>
              </strong>
          </td>
      </tr>
      <?php endif; ?>
    <tr class="order-total">
      <th><?php esc_html_e( 'Total (inc. TAX)', 'woocommerce' ); ?></th>
      <td data-title="<?php esc_attr_e( 'Total', 'woocommerce' ); ?>"><?php wc_cart_totals_order_total_html(); ?></td>
    </tr>

    <?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>

  </table>

  <div class="wc-proceed-to-checkout">
    <?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
  </div>

  <?php do_action( 'woocommerce_after_cart_totals' ); ?>

</div>
