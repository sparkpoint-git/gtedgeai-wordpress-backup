<?php
/**
 * Cancelled Subscription email
 *
 * @package WooCommerce_Subscriptions/Templates/Emails
 * @version 7.3.0 - Updated for WC core email improvements.
 */

defined( 'ABSPATH' ) || exit;

$email_improvements_enabled = wcs_is_wc_feature_enabled( 'email_improvements' );

do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<?php echo $email_improvements_enabled ? '<div class="email-introduction">' : ''; ?>
<p>
	<?php
	if ( 'pending-cancel' === $subscription->get_status() ) {
		printf(
			/* translators: $1$s customer's billing first name and last name %2$s: date on which the subscription ends. */
			esc_html__( 'A subscription belonging to %1$s is now pending cancellation, and will end on %2$s. Their subscription\'s details are as follows:', 'woocommerce-subscriptions' ),
			esc_html( $subscription->get_formatted_billing_full_name() ),
			esc_html( date_i18n( wc_date_format(), $subscription->get_time( 'end', 'site' ) ) )
		);
	} else {
		printf(
			/* translators: $1$s customer's billing first name and last name. */
			esc_html__( 'A subscription belonging to %1$s has been cancelled. Their subscription\'s details are as follows:', 'woocommerce-subscriptions' ),
			esc_html( $subscription->get_formatted_billing_full_name() )
		);
	}
	?>
</p>
<?php echo $email_improvements_enabled ? '</div>' : ''; ?>

<table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" border="1">
	<thead>
		<tr>
			<th class="td" scope="col" style="text-align:left;"><?php esc_html_e( 'Subscription', 'woocommerce-subscriptions' ); ?></th>
			<th class="td" scope="col" style="text-align:left;"><?php echo esc_html_x( 'Price', 'table headings in notification email', 'woocommerce-subscriptions' ); ?></th>
			<th class="td" scope="col" style="text-align:left;"><?php echo esc_html_x( 'Last Order Date', 'table heading', 'woocommerce-subscriptions' ); ?></th>
			<th class="td" scope="col" style="text-align:left;"><?php echo esc_html_x( 'End of Prepaid Term', 'table headings in notification email', 'woocommerce-subscriptions' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="td" width="1%" style="text-align:left; vertical-align:middle;">
				<a href="<?php echo esc_url( wcs_get_edit_post_link( $subscription->get_id() ) ); ?>">#<?php echo esc_html( $subscription->get_order_number() ); ?></a>
			</td>
			<td class="td" style="text-align:left; vertical-align:middle;">
				<?php echo wp_kses_post( $subscription->get_formatted_order_total() ); ?>
			</td>
			<td class="td" style="text-align:left; vertical-align:middle;">
				<?php
				$last_order_time_created = $subscription->get_time( 'last_order_date_created', 'site' );
				if ( ! empty( $last_order_time_created ) ) {
					echo esc_html( date_i18n( wc_date_format(), $last_order_time_created ) );
				} else {
					esc_html_e( '-', 'woocommerce-subscriptions' );
				}
				?>
			</td>
			<td class="td" style="text-align:left; vertical-align:middle;">
				<?php echo esc_html( date_i18n( wc_date_format(), $subscription->get_time( 'end', 'site' ) ) ); ?>
			</td>
		</tr>
	</tbody>
</table>
<br/>
<?php

do_action( 'woocommerce_subscriptions_email_order_details', $subscription, $sent_to_admin, $plain_text, $email );

do_action( 'woocommerce_email_customer_details', $subscription, $sent_to_admin, $plain_text, $email );

/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( $additional_content ) {
	echo $email_improvements_enabled ? '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td class="email-additional-content">' : '';
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
	echo $email_improvements_enabled ? '</td></tr></table>' : '';
}

do_action( 'woocommerce_email_footer', $email );
