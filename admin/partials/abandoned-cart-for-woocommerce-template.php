<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the html field for general tab.
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
$acfw_template_settings = apply_filters( 'acfw_template_settings_array', array() );
?>
<!--  template file for admin settings. -->
<div class="acfw-section-wrap">
	<?php
		$acfw_template_html = $acfw_mwb_acfw_obj->mwb_acfw_plug_generate_html( $acfw_template_settings );
		echo esc_html( $acfw_template_html );
	?>
</div>
