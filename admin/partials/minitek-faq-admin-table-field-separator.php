<?php
/**
 * Provides the markup for a separator.
 *
 * @since      	1.0.1
 * @package    	Minitek-Faq
 * @subpackage 	Minitek-Faq/admin/partials
 */

if ( ! empty( $atts['label'] ) ) {

	?><th><h3 class="<?php echo esc_attr( $atts['class'] ); ?>"><?php esc_html_e( $atts['label'], 'minitek-faq' ); ?>: </h3></th><?php

}
