<?php
/**
 * Provides the markup for any text field.
 *
 * @since      	1.0.1
 * @package    	Minitek-Faq
 * @subpackage 	Minitek-Faq/admin/partials
 */

?><input
	class="<?php echo isset($atts['class']) ? esc_attr( $atts['class'] ) : ''; ?>"
	id="<?php echo esc_attr( $atts['id'] ); ?>"
	name="<?php echo esc_attr( $atts['name'] ); ?>"
	placeholder="<?php echo esc_attr( $atts['placeholder'] ); ?>"
	type="<?php echo esc_attr( $atts['type'] ); ?>"
	value="<?php echo esc_attr( $atts['value'] ); ?>" /><?php

if ( ! empty( $atts['description'] ) ) {

	?><p class="description">
		<?php
		if (array_key_exists('desc_link', $atts)) {
		?><a href="<?php echo esc_url( $atts['desc_link'] ); ?>" target="_blank">
		<?php }
		esc_html_e( $atts['description'], 'minitek-faq' );
		if (array_key_exists('desc_link', $atts)) {
		?></a>
		<?php } ?>
	</p><?php

}
