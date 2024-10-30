<?php
/**
 * Provides a list of sections.
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
	required
	aria-required="true"
	aria-label="<?php esc_attr( _e( $atts['aria'], 'minitek-faq' ) ); ?>"
	class="<?php echo isset($atts['class']) ? esc_attr( $atts['class'] ) : ''; ?>"
	id="<?php echo esc_attr( $atts['id'] ); ?>"
	name="<?php echo esc_attr( $atts['name'] ); ?>"><?php

	if ($atts['value'] == '-1')
	{
		$option_atts = 'selected';
	}
	else
	{
		$option_atts = 'disabled hidden';
	}
	?><option value="-1" id="inherit_option" <?php echo $option_atts; ?>><?php esc_html_e( 'Inherited', 'minitek-faq' ); ?></option>
	<?php

	$args = array(
	  'taxonomy'     => 'mf_section',
		'orderby'      => 'name',
		'show_count'   => 0,
		'pad_counts'   => 0,
		'hierarchical' => FALSE,
		'title_li'     => '',
		'hide_empty'   => FALSE
	);

	$sections_all = get_categories( $args );

	foreach ( $sections_all as $section ) {

		if ($atts['value'] == '-1')
		{
			$selected = '';
			$section_atts = 'disabled hidden';
		}
		else
		{
			if ($section->term_id == $atts['value'])
			{
				$selected = 'selected';
			}
			else
			{
				$selected = '';
			}
			$section_atts = '';
		}
		?><option <?php echo $selected; ?> value="<?php echo esc_attr( $section->term_id ); ?>" <?php echo $section_atts; ?>><?php echo esc_html( $section->name ); ?></option>
		<?php

	} // foreach

	?>
	</select>
<p class="description"><?php esc_html_e( $atts['description'], 'minitek-faq' ); ?></p>
</td>
