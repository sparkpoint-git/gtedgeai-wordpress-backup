<?php
/**
 * Template admin/views/settings/tab-submissions.php
 *
 * @package Forminator
 */

$section = Forminator_Core::sanitize_text_field( 'section', 'dashboard' );
$nonce   = wp_create_nonce( 'forminator_save_privacy_settings' );
?>

<div class="sui-box" data-nav="submissions" style="<?php echo esc_attr( 'submissions' !== $section ? 'display: none;' : '' ); ?>">

	<div class="sui-box-header">
		<h2 class="sui-box-title"><?php esc_html_e( 'Submissions', 'forminator' ); ?></h2>
	</div>

	<form class="forminator-settings-save" action="">

		<div class="sui-box-body">

			<?php $this->template( 'settings/data/forms-privacy' ); ?>

			<?php $this->template( 'settings/data/polls-privacy' ); ?>

			<?php $this->template( 'settings/data/quizzes-privacy' ); ?>

		</div>

		<div class="sui-box-footer">

			<div class="sui-actions-right">

				<button class="sui-button sui-button-blue wpmudev-action-done" data-title="<?php esc_attr_e( 'Submissions settings', 'forminator' ); ?>" data-action="privacy_settings"
						data-nonce="<?php echo esc_attr( $nonce ); ?>">
					<span class="sui-loading-text"><?php esc_html_e( 'Save Settings', 'forminator' ); ?></span>
					<i class="sui-icon-loader sui-loading" aria-hidden="true"></i>
				</button>

			</div>

		</div>

	</form>

</div>