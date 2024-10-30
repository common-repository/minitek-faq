<?php
/**
 * Provides the markup for an editor field.
 *
 * @since      	1.0.1
 * @package    	Minitek-Faq
 * @subpackage 	Minitek-Faq/admin/partials
 */

if ( ! empty( $atts['label'] ) ) {

	?><th><label for="<?php echo esc_attr( $atts['id'] ); ?>"><?php esc_html_e( $atts['label'], 'minitek-faq' ); ?>: </label></th><?php

}

?><td><?php

	$content = $atts['value'];
	$editor_id = $atts['id'];

	wp_editor( $content, $editor_id );

?>
<p class="description"><?php esc_html_e( $atts['description'], 'minitek-faq' ); ?></p>
</td>
