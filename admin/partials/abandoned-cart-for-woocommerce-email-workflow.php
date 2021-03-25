<?php
/**
 * Provide Email workflows
 *
 * This file is used to show workflows to the merchat for sending in email.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Abandoned_Cart_For_Woocommerce
 * @subpackage Abandoned_Cart_For_Woocommerce/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $acfw_mwb_acfw_obj;
$acfw_genaral_settings = apply_filters( 'mwb_custom_email_settings_array', array() );

?>
<div class="m-section-wrap">
<div class="m-section-note">

<img src="<?php echo esc_html( ABANDONED_CART_FOR_WOOCOMMERCE_DIR_URL ) . 'admin/src/images/note.svg'; ?>" alt="">

<?php esc_html_e( 'Use Placeholders', 'abandoned-cart-for-woocommerce' ); ?> <span><?php echo esc_html( '{coupon}' ); ?></span> <?php esc_html_e( 'to apply a coupon on the cart', 'abandoned-cart-for-woocommerce' ); ?><span> <?php echo esc_html( ' {cart} ' ); ?></span> <?php esc_html_e( 'for displaying the cart in the email', 'abandoned-cart-for-woocommerce' ); ?>
<span> <?php echo esc_html( '{checkout}' ); ?></span> <?php esc_html_e( ' for checkout page', 'abandoned-cart-for-woocommerce' ); ?>
</div>
<form action="" method="POST" class="mwb-m-gen-section-form">
<?php
global $wpdb;

	$result  = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'mwb_email_workflow' );
		$m_settings_template = array();
foreach ( $result as $data ) {
			$ew_id        = $data->ew_id;
			$enable_value = $data->ew_enable;
			$content      = $data->ew_content;
			$time         = $data->ew_initiate_time;
			$subject      = $data->ew_mail_subject;
			$count = $ew_id - 1;
	?>
						<input type="hidden" name="nonce" value="<?php echo esc_html( wp_create_nonce() ); ?>">
						<div class="mwb-form-group">
							<div class="mwb-form-group__label">
								<label class="mwb-form-label mwb-form-group step_label_class"><?php echo esc_attr( 'Step' . $ew_id ); ?></label>
							</div>
						</div>

					<div class="mwb-form-group">
						<div class="mwb-form-group__label">
							<label for="<?php echo 'enable_email-workflow'; ?>" class="mwb-form-label"><?php esc_html_e( 'Enable The workflow', 'abandoned-cart-for-woocommerce' ); ?></label>
						</div>
						<div class="mwb-form-group__control mwb-pl-4">
							<div class="mdc-form-field">
								<div class="mdc-checkbox">
									<input name="checkbox[<?php echo esc_attr( 'check_' . $count ); ?>][]" id="<?php echo 'enable_email-workflow_' . esc_html( $ew_id ); ?>" type="checkbox" class="mdc-checkbox__native-control m-checkbox-class" <?php checked( $enable_value, 'on' ); //phpcs:ignore ?>	/>
									<div class="mdc-checkbox__background">
										<svg class="mdc-checkbox__checkmark" viewBox="0 0 24 24">
											<path class="mdc-checkbox__checkmark-path" fill="none" d="M1.73,12.91 8.1,19.28 22.79,4.59"/>
										</svg>
										<div class="mdc-checkbox__mixedmark"></div>
									</div>
									<div class="mdc-checkbox__ripple"></div>
								</div>
							</div>
						</div>
					</div>
					<div class="mwb-form-group" id=<?php echo esc_attr( 'time_parent' . $ew_id ); ?>>
						<div class="mwb-form-group__label">
							<label for="<?php echo esc_attr( 'initiate_time' . $ew_id ); ?>" class="mwb-form-label"><?php esc_html_e( 'Initiate Time', 'abandoned-cart-for-woocommerce' ); // WPCS: XSS ok. ?></label>
						</div>
						<div class="mwb-form-group__control">
							<label class="mdc-text-field mdc-text-field--outlined">
								<span class="mdc-notched-outline">
									<span class="mdc-notched-outline__leading"></span>
									<span class="mdc-notched-outline__notch">
									</span>
									<span class="mdc-notched-outline__trailing"></span>
								</span>
								<input class="mdc-text-field__input m-number-class" name="time[]" id="<?php echo esc_attr( 'initiate_time' . $ew_id ); ?>" type="number" value="<?php echo esc_html( $time ); ?>" placeholder="<?php esc_attr_e( 'Enter time', 'abandoned-cart-for-woocommerce' ); ?>" min="1" >
							</label>
							<div class="mdc-text-field-helper-line">
								<div class="mdc-text-field-helper-text--persistent mwb-helper-text" id="" aria-hidden="true"><?php esc_attr_e( 'Enter time In Hours', 'abandoned-cart-for-woocommerce' ); ?></div>
							</div>
						</div>
					</div>
					<div class="mwb-form-group" id=<?php echo esc_attr( 'subject_parent' . $ew_id ); ?>>
						<div class="mwb-form-group__label">
							<label for="<?php echo esc_attr( 'subject' . $ew_id ); ?>" class="mwb-form-label"><?php esc_html_e( 'Mail Subject', 'abandoned-cart-for-woocommerce' ); // WPCS: XSS ok. ?></label>
						</div>
						<div class="mwb-form-group__control">
							<label class="mdc-text-field mdc-text-field--outlined">
								<span class="mdc-notched-outline">
									<span class="mdc-notched-outline__leading"></span>
									<span class="mdc-notched-outline__notch">
									</span>
									<span class="mdc-notched-outline__trailing"></span>
								</span>
								<input class="mdc-text-field__input m-number-class" name="subject[]" id="<?php echo esc_attr( 'subject' . $ew_id ); ?>" type="text" value="<?php echo esc_html( $subject ); ?>" placeholder="<?php esc_attr_e( 'Enter Mail Subject', 'abandoned-cart-for-woocommerce' ); ?>" >
							</label>
							<div class="mdc-text-field-helper-line">
								<div class="mdc-text-field-helper-text--persistent mwb-helper-text" id="" aria-hidden="true"><?php esc_attr_e( 'Enter Subject', 'abandoned-cart-for-woocommerce' ); ?></div>
							</div>
						</div>
					</div>
					<div class="mwb-form-group" id=<?php echo esc_attr( 'content_parent' . $ew_id ); ?>>
						<div class="mwb-form-group__label">
							<label class="mwb-form-label" for="<?php echo esc_attr( 'email_content' . $ew_id ); ?>"><?php esc_attr_e( 'Content', 'abandoned-cart-for-woocommerce' ); ?></label>
						</div>
						<div class="mwb-form-group__control">

						<?php $settings = array( 'textarea_name' => 'email_workflow_content[]' ); ?>
						<?php wp_editor( $content, "benz_tab_content_$ew_id", $settings ); ?>

						</div>
					</div>


	<?php
}
?>
		<div class="mwb-form-group">
				<div class="mwb-form-group__control">
					<input type="submit" class="mdc-button mdc-button--raised mdc-ripple-upgraded" name="submit_workflow" value="<?php esc_html_e( 'Save Workflow', 'abandoned-cart-for-woocommerce' ); ?>">
				</div>
		</div>
</form>
</div>

