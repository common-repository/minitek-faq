<?php
/**
 * Provides a list of sections.
 *
 * @since      	1.0.1
 * @package    	Minitek-Faq
 * @subpackage 	Minitek-Faq/admin/partials
 */

?>
<div class="form-field form-required term-topic-section-wrap">

<?php if ( ! empty( $atts['label'] ) ) {

	?><label for="<?php echo esc_attr( $atts['id'] ); ?>"><?php esc_html_e( $atts['label'], 'minitek-faq' ); ?></label><?php

}

?>
	<select
	aria-required="true"
	aria-label="<?php esc_attr( _e( $atts['aria'], 'minitek-faq' ) ); ?>"
	class="<?php echo isset($atts['class']) ? esc_attr( $atts['class'] ) : ''; ?>"
	id="<?php echo esc_attr( $atts['id'] ); ?>"
	name="<?php echo esc_attr( $atts['name'] ); ?>">

	<option value="-1" id="inherit_option" disabled hidden><?php esc_html_e( 'Inherited', 'minitek-faq' ); ?></option>
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

		if ($section->term_id == $atts['value'])
		{
			$selected = 'selected';
		}
		else
		{
			$selected = '';
		} ?>

		<option <?php echo $selected; ?> value="<?php echo esc_attr( $section->term_id ); ?>" ><?php echo esc_html( $section->name ); ?></option>
		<?php

	} // foreach

	?>
	</select>
<p class="description"><?php esc_html_e( $atts['description'], 'minitek-faq' ); ?></p>

</div>
