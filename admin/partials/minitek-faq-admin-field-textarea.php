<?php
/**
 * Provides the markup for any textarea field.
 *
 * @since      	1.0.1
 * @package    	Minitek-Faq
 * @subpackage 	Minitek-Faq/admin/partials
 */

if ( ! empty( $atts['label'] ) ) {

	?><label for="<?php echo esc_attr( $atts['id'] ); ?>"><?php esc_html_e( $atts['label'], 'minitek-faq' ); ?>: </label><?php

}

?><textarea
	class="<?php echo isset($atts['class']) ? esc_attr( $atts['class'] ) : ''; ?>"
	cols=""
	id="<?php echo esc_attr( $atts['id'] ); ?>"
	name="<?php echo esc_attr( $atts['name'] ); ?>"
	rows=""><?php

	echo esc_textarea( $atts['value'] );

?></textarea>
<p class="description"><?php esc_html_e( $atts['description'], 'minitek-faq' ); ?></p>
