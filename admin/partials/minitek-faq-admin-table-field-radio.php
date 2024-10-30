<?php
/**
 * Provides the markup for a radio field.
 *
 * @since      	1.0.1
 * @package    	Minitek-Faq
 * @subpackage 	Minitek-Faq/admin/partials
 */

if ( ! empty( $atts['label'] ) ) {

	?><th><label for="<?php echo esc_attr( $atts['id'] ); ?>"><?php esc_html_e( $atts['label'], 'minitek-faq' ); ?></label></th><?php

}

if ( empty( $atts['value'] ) ) {

	$atts['value'] = '0';

}

?>
<td>
<fieldset>
<?php
foreach ( $atts['selections'] as $selection ) {

	if ( is_array( $selection ) ) {

		$label = $selection['label'];
		$value = $selection['value'];

	} else {

		$label = strtolower( $selection );
		$value = strtolower( $selection );

	}

	?><label>
		<input
			type="radio"
			name="<?php echo esc_attr( $atts['name'] ); ?>"
			value="<?php echo esc_attr( $value ); ?>" <?php
			checked( $atts['value'], $value ); ?>>

		<span><?php echo esc_html_e( $label, 'minitek-faq' ); ?></span>
		<?php
		if (array_key_exists('desc', $selection)) {
			?>
			<code><?php echo esc_html_e( $selection['desc'], 'minitek-faq' ); ?></code>
		<?php }
	?></label></br>

<?php
} // foreach

?>
<p class="description"><?php esc_html_e( $atts['description'], 'minitek-faq' ); ?></p>
</fieldset>
</td>
