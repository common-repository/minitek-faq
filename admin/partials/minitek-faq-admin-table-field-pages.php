<?php
/**
 * Provides a list of pages.
 *
 * @since      	1.0.1
 * @package    	Minitek-Faq
 * @subpackage 	Minitek-Faq/admin/partials
 */

if ( ! empty( $atts['label'] ) ) {

	?><th scope="row"><label for="<?php echo esc_attr( $atts['id'] ); ?>"><?php esc_html_e( $atts['label'], 'minitek-faq' ); ?></label></th><?php

}

?><td>
	<select
	aria-label="<?php esc_attr( _e( $atts['aria'], 'minitek-faq' ) ); ?>"
	class="<?php echo isset($atts['class']) ? esc_attr( $atts['class'] ) : ''; ?>"
	id="<?php echo esc_attr( $atts['id'] ); ?>"
	name="<?php echo esc_attr( $atts['name'] ); ?>">

	<?php
	if ('none' == $atts['value'])
	{
		$selected = 'selected';
	}
	else
	{
		$selected = '';
	} ?>
	<option <?php echo $selected; ?> value="none"><?php echo esc_html_e( '- None -', 'minitek-faq' ); ?></option>

	<?php

	$pages = get_pages();

	foreach ( $pages as $page ) {

		if ($page->ID == $atts['value'])
		{
			$selected = 'selected';
		}
		else
		{
			$selected = '';
		}

		?><option <?php echo $selected; ?> value="<?php echo esc_attr( $page->ID ); ?>"><?php echo esc_html( $page->post_title ); ?></option>
		<?php

	} // foreach

	?>
	</select>
<p class="description"><?php esc_html_e( $atts['description'], 'minitek-faq' ); ?></p>
</td>
