<?php
/**
 * Caching page layout.
 *
 * @package Hummingbird
 */

use Hummingbird\Core\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $this->has_meta_boxes( 'main' ) ) {
	$this->do_meta_boxes( 'main' );
}

$forms = array( 'page_cache', 'rss', 'settings' );
?>

<div class="sui-row-with-sidenav">
	<?php $this->show_tabs(); ?>

	<?php if ( 'caching' === $this->get_current_tab() ) : ?>
		<div class="box-caching-status" id="wrap-wphb-browser-caching"></div>
	<?php endif; ?>

	<?php if ( in_array( $this->get_current_tab(), $forms, true ) ) : ?>
		<?php $form_name = 'page_cache' === $this->get_current_tab() && Utils::get_api()->hosting->has_fast_cgi_header() ? 'fastcgi' : $this->get_current_tab(); ?>
		<form id="<?php echo esc_attr( $form_name ); ?>-form" method="post">
			<?php $this->do_meta_boxes( $this->get_current_tab() ); ?>
		</form>
	<?php else : ?>
		<?php $this->do_meta_boxes( $this->get_current_tab() ); ?>
	<?php endif; ?>
</div>

<?php
if ( 'caching' === $this->get_current_tab() || 'integrations' === $this->get_current_tab() ) {
	$this->modal( 'integration-cloudflare' );
}
?>

<script>
	jQuery(document).ready( function() {
		if ( window.WPHB_Admin ) {
			window.WPHB_Admin.getModule( 'caching' );
			window.WPHB_Admin.getModule( 'cloudflare' );
		}
	});
</script>