<?php
/**
 * Provides a list of topics.
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

	$args = array(
	  'taxonomy'     => 'mf_topic',
		'orderby'      => 'name',
		'show_count'   => 0,
		'pad_counts'   => 0,
		'hierarchical' => FALSE,
		'title_li'     => '',
		'hide_empty'   => FALSE
	);

	$topics_all = get_categories( $args );

	foreach ( $topics_all as $topic ) {

		if ($topic->term_id == $atts['value'])
		{
			$selected = 'selected';
		}
		else
		{
			$selected = '';
		}
		$topic_atts = '';
		?><option <?php echo $selected; ?> value="<?php echo esc_attr( $topic->term_id ); ?>" <?php echo $topic_atts; ?>><?php echo esc_html( $topic->name ); ?></option>
		<?php

	} // foreach

	?>
	</select>
<p class="description"><?php esc_html_e( $atts['description'], 'minitek-faq' ); ?></p>
</td>
