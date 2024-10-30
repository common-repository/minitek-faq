<?php

/**
 * Data source class.
 *
 * @since      	1.0.1
 * @package    	Minitek-Faq
 * @subpackage 	Minitek-Faq/includes
 */

class MFaq_Data {

	/**
	 * Constructor.
	 */
	public function __construct() {

		// Nothing to see here...

	} // __construct()

	/**
	 * Get first level topics from specific parent within a section.
	 *
	 * @since    	1.0.1
	 */
	 public function mf_get_flat_topics ($section_id, $parent = 0, $fields = 'all') {

 		$args = array(
 			'taxonomy' => 'mf_topic',
 			'orderby' => 'name',
 			'order' => 'ASC',
 			'hide_empty' => false,
 			'fields' => $fields,
 			'count' => false,
 			'hierarchical' => true,
 			'pad_counts' => false,
 			'parent' => $parent
 		);

 		if ($section_id)
 		{
 			$args['meta_query'] = array(
 				 array(
 					'key' => 'topic-section',
 					'value' => $section_id,
 					'compare' => '='
 				 )
 			);
 		}

 		$topics = get_categories( $args );

 		return $topics;

 	}

	/**
	 * Get the depth of a topic.
	 *
	 * @since    	1.0.1
	 */
	public function mf_get_topic_depth ($topic_id, $levels) {

		$children = self::mf_get_flat_topics(false, $topic_id);

		if (count($children))
		{
		  $levels++;
		  foreach ($children as $child)
			{
			  if (count(self::mf_get_flat_topics(false, $child->term_id)))
				{
					return;
				}
			}
		}

		return $levels;

  }

	/**
	 * Get questions of specific topic.
	 *
	 * @since    	1.0.1
	 */
	public function mf_get_topic_questions ($topic_id, $page, $options) {

		$offset = ($page - 1) * (int)$options['questions-limit'];
		$order = $options['questions-direction'];
		$orderby = $options['questions-ordering'];

		// Count all questions
		$args = array(
			'post_type' => 'mf_question',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'offset' => 0,
			'tax_query' => array(
				array(
					'taxonomy' => 'mf_topic',
					'field' => 'term_id',
					'terms' => array( $topic_id ),
					'include_children' => false
				),
			),
		);

		$query = new WP_Query( $args );
		$total_count = $query->post_count;

		// Get questions of current page
		$args = array(
			'post_type' => 'mf_question',
			'post_status' => 'publish',
			'posts_per_page' => $options['questions-limit'],
			'offset' => $offset,
			'order' => $order,
			'orderby' => $orderby,
			'tax_query' => array(
				array(
					'taxonomy' => 'mf_topic',
					'field' => 'term_id',
					'terms' => array( $topic_id ),
					'include_children' => false
				),
			),
		);

		$query = new WP_Query( $args );
		$questions = $query->get_posts();
		$remaining = $total_count - $offset - $query->post_count;

		return array('questions' => $questions, 'remaining' => $remaining);

	}

	/**
	 * Get the inherited section of a topic.
	 *
	 * @since    	1.0.1
	 */
	public function get_inherited_section ($topic_id) {

		$section_id = get_term_meta($topic_id, 'topic-section', true);

		if (!$section_id || $section_id == '-1')
		{
			$term = get_term($topic_id, 'mf_topic');
			$termParent = ($term->parent == 0) ? 0 : get_term($term->parent, 'mf_topic');
			if ($termParent)
			{
				return self::get_inherited_section($termParent->term_id);
			}
			return false;
		}

		return $section_id;

  }

} // class
