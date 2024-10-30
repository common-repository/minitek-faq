<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @since      	1.0.1
 * @package    	Minitek-Faq
 * @subpackage 	Minitek-Faq/public
 */

class MFaq_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    	1.0.1
	 * @access   	private
	 * @var      	string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    	1.0.1
	 * @access   	private
	 * @var      	string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The plugin settings.
	 *
	 * @since    	1.0.1
	 * @access   	private
	 * @var      	array
	 */
	private $options;

	/**
	 * Whether current page is section.
	 *
	 * @since    	1.0.1
	 * @access   	private
	 * @var      	bool
	 */
	private $is_section;

	/**
	 * Whether current page is topic.
	 *
	 * @since    	1.0.1
	 * @access   	private
	 * @var      	bool
	 */
	private $is_topic;

	/**
	 * Whether current page is question.
	 *
	 * @since    	1.0.1
	 * @access   	private
	 * @var      	bool
	 */
	private $is_question;

	/**
	 * Current section id.
	 *
	 * @since    	1.0.1
	 * @access   	private
	 * @var      	int
	 */
	private $sectionId;

	/**
	 * Current topic id.
	 *
	 * @since    	1.0.1
	 * @access   	private
	 * @var      	int
	 */
	private $topicId;

	/**
	 * Data source instance.
	 *
	 * @since    	1.0.1
	 * @access   	private
	 * @var      	int
	 */
	private $dataSource;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    	1.0.1
	 * @param    	string    $plugin_name       The name of the plugin.
	 * @param    	string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->options = get_option('minitek-faq-options', array());
		$this->dataSource = new MFaq_Data();

		$this->is_section = 0;
		$this->is_topic = 0;
		$this->is_question = 0;
		$this->sectionId = 0;
		$this->topicId = 0;
		$this->activeTopic = 0;

		// Sections shortcode
		add_shortcode( 'mf-sections', array( $this, 'mf_sections_display' ) );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    	1.0.1
	 */
	public function enqueue_styles() {

		wp_enqueue_style( 'dashicons' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/minitek-faq-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    	1.0.1
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/minitek-faq-public.js', array( 'jquery' ), $this->version, false );

		// Pass php variables to javascript
		$data = $this->mf_get_javascript_variables();
		wp_localize_script( $this->plugin_name, 'mf_vars', $data );

	}

	/**
	 * Get javascript variables.
	 *
	 * @since    	1.0.1
	 */
	public function mf_get_javascript_variables () {

		$loadAllTopics = get_term_meta($this->sectionId, 'section-load-topics', true);

		if ($loadAllTopics == 'all')
		{
			$loadAllTopics = 1;
		}
		else
		{
			$loadAllTopics = 0;
		}

		$topnav = get_term_meta($this->sectionId, 'section-top-nav', true);

		if ($topnav == 'yes')
		{
			$topnav = 1;
		}
		else
		{
			$topnav = 0;
		}

		$leftnav = get_term_meta($this->sectionId, 'section-left-nav', true);

		if ($leftnav == 'yes')
		{
			$leftnav = 1;
		}
		else
		{
			$leftnav = 0;
		}

		$data = array(
			'ajax_url'				=> admin_url( 'admin-ajax.php' ),
			'is_section'			=> $this->is_section,
			'is_topic'				=> $this->is_topic,
			'is_question'			=> $this->is_question,
			'sectionId' 			=> $this->sectionId,
			'topicId' 				=> $this->topicId,
			'activeTopic' 		=> $this->activeTopic,
			'topnav' 					=> $topnav,
			'leftnav' 				=> $leftnav,
			'loadAllTopics' 	=> $loadAllTopics
		);

		return $data;

	}

	/**
	 * Set the template.
	 *
	 * @since    	1.0.1
	 */
	public function mf_set_template( $template ) {

		// Set Section template
		if (is_tax('mf_section') && !self::mf_template_in_theme($template, 'taxonomy', 'mf_section'))
		{
    	$template = plugin_dir_path( dirname( __FILE__ ) ) . 'public/templates/taxonomy-mf_section.php';
			$this->is_section = true;
			$this->sectionId = get_queried_object()->term_id;
			if (get_term_meta($this->sectionId, 'section-show-active-topic', true) == 'yes')
			{
				$this->activeTopic = get_term_meta($this->sectionId, 'section-active-topic', true);
			}
		}

		// Set Topic template
		if (is_tax('mf_topic') && !self::mf_template_in_theme($template, 'taxonomy', 'mf_topic'))
		{
      $template = plugin_dir_path( dirname( __FILE__ ) ) . 'public/templates/taxonomy-mf_topic.php';
			$this->is_topic = true;
			$this->topicId = get_queried_object()->term_id;
			$this->sectionId = get_term_meta($this->topicId, 'topic-section', true);
			if (!$this->sectionId || $this->sectionId == '-1')
			{
				$this->sectionId = $this->dataSource->get_inherited_section($this->topicId);
			}
			$this->url = $_SERVER['REQUEST_URI'];
		}

		return $template;

	}

	/**
	 * Checks if there is a template in the theme. The theme template will override the plugin template.
	 *
	 * @since    	1.0.1
	 */
	public function mf_template_in_theme( $template_path, $type = 'taxonomy', $name = '' ) {

		if ($type == 'sections'
		|| $type == 'popular-questions'
		|| $type == 'popular-topics'
		|| $type == 'section-content'
		|| $type == 'topic-content'
		|| $type == 'topicquestions'
		|| $type == 'topics-tree')
		{
			if (file_exists($template_path))
				return true;
		}
		else
		{
			//Get template name
			$template = basename($template_path);

			if (1 == preg_match('/^'.$type.'-'.$name.'((-(\S*))?).php/', $template))
				return true;
		}

		return false;

	}

	/**
	 * Renders the Sections shortcode.
	 *
	 * @since    	1.0.1
	 */
	public function mf_sections_display ($atts, $content = null ) {

		// Get section ids from shortcode
		$atts = shortcode_atts(array('ids' => ''), $atts, 'mf-sections' );
		$ids = $atts['ids'];

		if (!$ids)
			return null;

		$sections_ids = array_map('intval', explode(',', $ids));

		// Load template
    $template = get_template_directory().'/mf-sections.php';

		ob_start();

		if (self::mf_template_in_theme($template, 'sections'))
		{
			include( $template );
		}
		else
		{
			include( plugin_dir_path( __FILE__ ) . 'templates/mf-sections.php' );
		}

		return ob_get_clean();

	}

	/**
	 * Loads the topic questions.
	 *
	 * @since    	1.0.1
	 */
	public function mf_topicquestions_display ($data, $topic_id) {

		if (isset($_POST['ajax']) && isset($_POST['topic']))
		{
			$topic_id = (int)$_POST['topic'];
		}

		// Load template
    $template = get_template_directory().'/partials/mf-topicquestions.php';

		ob_start();

		if (self::mf_template_in_theme($template, 'topicquestions'))
		{
			include( $template );
		}
		else
		{
			include( plugin_dir_path( __FILE__ ) . 'templates/partials/mf-topicquestions.php' );
		}

		echo ob_get_clean();

	}

	/**
	 * Loads the popular topics.
	 *
	 * @since    	1.0.1
	 */
	public function mf_popular_topics_display ($section_id) {

 		$sectionParams = get_term_meta($section_id);

 		$topics = $this->dataSource->mf_get_flat_topics($section_id, 0, 'ids');

 		$popular_topics = get_terms( array(
 	    'taxonomy' => 'mf_topic',
 			'term_taxonomy_id' => $topics,
 			'number' => $sectionParams['section-popular-topics-count'][0],
 			'orderby' => 'meta_value_num',
 			'order' => 'DESC',
 	    'hide_empty' => false,
 			'meta_query' => array(
 				'relation' => 'AND',
 				array(
 			    'key' => 'topic-views',
 			    'type' => 'NUMERIC',
 				),
 				array(
 			    'key' => 'topic-section',
 			    'value' => $section_id,
 					'compare' => '='
 				),
 			),
 		) );

 		if (!$popular_topics)
 		  return;

 		// Load template
     $template = get_template_directory().'/partials/mf-popular-topics.php';

 		ob_start();

 		if (self::mf_template_in_theme($template, 'popular-topics'))
 		{
 			include( $template );
 		}
 		else
 		{
 			include( plugin_dir_path( __FILE__ ) . 'templates/partials/mf-popular-topics.php' );
 		}

 		echo ob_get_clean();

 	}

	/**
	 * Loads the topics tree.
	 *
	 * @since    	1.0.1
	 */
	public function mf_topics_tree_display ($section_id) {

		$sectionParams = get_term_meta($section_id);

		$first_level_topics = $this->dataSource->mf_get_flat_topics($section_id);

		if (!$first_level_topics)
		  return;

		// Load template
    $template = get_template_directory().'/partials/mf-topics-tree.php';

		ob_start();

		if (self::mf_template_in_theme($template, 'topics-tree'))
		{
			include( $template );
		}
		else
		{
			include( plugin_dir_path( __FILE__ ) . 'templates/partials/mf-topics-tree.php' );
		}

		echo ob_get_clean();

	}

	/**
	 * Loads a topic tree.
	 *
	 * @since    	1.0.1
	 */
	public function mf_topic_tree ($topic_id, $maxLevels, $cols, $level = 1, $icons = 'yes') {

		$topicParams = get_term_meta($topic_id);

		$output = '';

		$subtopics = $this->dataSource->mf_get_flat_topics(false, $topic_id);

		$style = '';
		if ($level == 1)
		{
			$style = 'style="width:'.number_format(100/$cols, 1).'%;"';
		}

		$output .= '<li '.$style.'>';
		$output .= '<a href="'.esc_url(get_term_link($topic_id)).'">';

		if ($icons == 'yes' && isset($topicParams['topic-icon-class'][0]) && $topicParams['topic-icon-class'][0])
		{
			$output .= '<i class="dashicons dashicons-'.$topicParams['topic-icon-class'][0].'"></i>&nbsp;&nbsp;';
		}
		$output .= get_term($topic_id)->name;
		$output .= '</a>';

		if (count($subtopics) && $level < $maxLevels)
		{
			$output .= '<ul class="level'.$level.'">';
			foreach ($subtopics as $subtopic)
			{
				$output .= self::mf_topic_tree($subtopic->term_id, $maxLevels, $cols, $level + 1, $icons);
			}
			$output .= '</ul>';
		}
		$output .= '</li>';

		return $output;

	}

	/**
	 * Loads the section.
	 *
	 * @since    	1.0.1
	 */
	public function mf_section_content_display () {

		// Is this an ajax request?
		if (isset($_POST['ajax']))
		{
			$safe_ajax = (int)$_POST['ajax'];
		}
		if (isset($safe_ajax) && $safe_ajax == '1')
		{
			$is_ajax = true;
		}
		else
		{
			$is_ajax = false;
		}

		// Get section
		if (isset($_POST['section']))
		{
			$section_id = (int)$_POST['section'];
		}
		else
		{
			$section_id = $this->sectionId;
		}

		// Get section params
		$sectionParams = get_term_meta($section_id);

		// Load template
    $template = get_template_directory().'/partials/mf-section-content.php';

		ob_start();

		if (self::mf_template_in_theme($template, 'section-content'))
		{
			include( $template );
		}
		else
		{
			include( plugin_dir_path( __FILE__ ) . 'templates/partials/mf-section-content.php' );
		}

		echo ob_get_clean();

		if ($is_ajax)
			wp_die();

	}

	/**
	 * Loads the topic content.
	 *
	 * @since    	1.0.1
	 */
	public function mf_topic_content_display () {

		// Is this an ajax request?
		if (isset($_POST['ajax']))
		{
			$safe_ajax = (int)$_POST['ajax'];
		}
		if (isset($safe_ajax) && $safe_ajax == '1')
		{
			$is_ajax = true;
		}
		else
		{
			$is_ajax = false;
		}

		// Is this a request for a section or for a topic?
		if (isset($_POST['section']))
		{
			$safe_section = (int)$_POST['section'];
			$section_id = $safe_section;
		}
		else
		{
			$section_id = $this->sectionId;
		}

		if ((isset($safe_section) && $safe_section) || $this->activeTopic)
		{
			$this->is_section = true;
		}
		else
		{
			$this->is_section = false;
		}

		// Get topic id
		if (isset($_POST['topic']))
		{
			$topic_id = (int)$_POST['topic'];
		}
		else
		{
			$topic_id = $this->topicId;
		}

		// Get page
		if (isset($_POST['page']))
		{
			$page = (int)$_POST['page'];
		}
		else
		{
			$page = 1;
		}

		// If no topic_id, then it's a request for an active topic in section
		if (!$topic_id)
		{
			$active_topic_id = get_term_meta($section_id, 'section-active-topic', true);
			$topic_id = (int)$active_topic_id;
		}

		// Get topic object
		$topic = get_term( $topic_id, 'mf_topic' );

		// Get topic params
		$topicParams = get_term_meta($topic_id);
		if (empty($topicParams))
		{
		  return;
		}

		if ($topicParams['topic-title'][0] == 'global')
		{
		  $topicParams['topic-title'][0] = $this->options['topic-title'];
		}

		if ($topicParams['topic-description'][0] == 'global')
		{
		  $topicParams['topic-description'][0] = $this->options['topic-description'];
		}

		if ($topicParams['topic-show-image'][0] == 'global')
		{
		  $topicParams['topic-show-image'][0] = $this->options['topic-image'];
		}

		if ($topicParams['topic-show-content'][0] == 'global')
		{
		  $topicParams['topic-show-content'][0] = $this->options['topic-content'];
		}

		if ($topicParams['topic-questions-opened'][0] == 'global')
		{
		  $topicParams['topic-questions-opened'][0] = $this->options['questions-opened'];
		}

		if ($topicParams['topic-subtopics'][0] == 'global')
		{
		  $topicParams['topic-subtopics'][0] = $this->options['subtopics'];
		}

		if ($topicParams['topic-subtopics-titles'][0] == 'global')
		{
		  $topicParams['topic-subtopics-titles'][0] = $this->options['subtopics-title'];
		}

		if ($topicParams['topic-subtopics-descriptions'][0] == 'global')
		{
		  $topicParams['topic-subtopics-descriptions'][0] = $this->options['subtopics-description'];
		}

		if ($topicParams['topic-subtopics-images'][0] == 'global')
		{
		  $topicParams['topic-subtopics-images'][0] = $this->options['subtopics-image'];
		}

		if ($topicParams['topic-subtopics-content'][0] == 'global')
		{
		  $topicParams['topic-subtopics-content'][0] = $this->options['subtopics-content'];
		}

		if ($topicParams['topic-subtopics-questions'][0] == 'global')
		{
		  $topicParams['topic-subtopics-questions'][0] = $this->options['subtopics-questions'];
		}

		// Get topic questions
		$topic->questions = false;
		$topic_questions = $this->dataSource->mf_get_topic_questions($topic_id, $page, $this->options);
		if (!empty($topic_questions['questions']))
		{
			$topic->questions = $topic_questions['questions'];

			foreach ($topic->questions as $question)
			{
				// Introtext
				$question->introtext = wp_trim_words( $question->post_content, $this->options['questions-introtext-limit'] );

				// Image
				$question->image = get_the_post_thumbnail_url( $question->ID, 'full' );

				// Date
				$question->formatted_date = get_the_date( $this->options['questions-date-format'], $question->ID );
			}
		}

		// Get topic subtopics
		$topic->subtopics = get_terms( array(
	    'taxonomy' => 'mf_topic',
			'parent' => $topic_id,
			'orderby' => 'name',
			'order' => 'ASC',
	    'hide_empty' => false,
		) );

		if (!empty($topic->subtopics))
		{
			foreach ($topic->subtopics as $subtopic)
		  {
				// Get subtopic params
				$subtopic->params = get_term_meta($subtopic->term_id);
				if (empty($subtopic->params))
				{
				  continue;
				}

				// Get subtopic questions
				$subtopic->questions = false;
				$subtopic_questions = $this->dataSource->mf_get_topic_questions($subtopic->term_id, $page, $this->options);
				if (!empty($subtopic_questions['questions']))
				{
					$subtopic->questions = $subtopic_questions['questions'];

					foreach ($subtopic->questions as $question)
					{
						// Introtext
						$question->introtext = wp_trim_words( $question->post_content, $this->options['questions-introtext-limit']);

						// Image
						$question->image = get_the_post_thumbnail_url( $question->ID, 'full' );

						// Date
						$question->formatted_date = get_the_date( $this->options['questions-date-format'], $question->ID );
					}
				}
			}
		}

		add_filter( 'mf_format_content', 'wptexturize'       );
		add_filter( 'mf_format_content', 'convert_smilies'   );
		add_filter( 'mf_format_content', 'convert_chars'     );
		add_filter( 'mf_format_content', 'wpautop'           );
		add_filter( 'mf_format_content', 'shortcode_unautop' );
		add_filter( 'mf_format_content', 'do_shortcode'      );

		// Load template
    $template = get_template_directory().'/partials/mf-topic-content.php';

		ob_start();

		if (self::mf_template_in_theme($template, 'topic-content'))
		{
			include( $template );
		}
		else
		{
			include( plugin_dir_path( __FILE__ ) . 'templates/partials/mf-topic-content.php' );
		}

		echo ob_get_clean();

		if ($is_ajax && !$this->is_section)
			wp_die();

	}

	/**
	 * Loads more questions in topic.
	 *
	 * @since    	1.0.1
	 */
	public function mf_topic_load_more () {

		// Get topic id
		if (isset($_POST['topic']))
		{
			$topic_id = (int)$_POST['topic'];
		}
		else
		{
			$topic_id = $this->topicId;
		}

		// Get page
		if (isset($_POST['page']))
		{
			$page = (int)$_POST['page'];
		}
		else
		{
			$page = 1;
		}

		// Get topic object
		$topic = get_term( $topic_id, 'mf_topic' );

		// Get topic params
		$topicParams = get_term_meta($topic_id);
		if (empty($topicParams))
		{
		  return;
		}

		if ($topicParams['topic-questions-opened'][0] == 'global')
		{
		  $topicParams['topic-questions-opened'][0] = $this->options['questions-opened'];
		}

		// Get topic questions
		$topic->questions = false;
		$topic_questions = $this->dataSource->mf_get_topic_questions($topic_id, $page, $this->options);
		if (!empty($topic_questions['questions']))
		{
			$topic->questions = $topic_questions['questions'];

			foreach ($topic->questions as $question)
			{
				// Introtext
				$question->introtext = wp_trim_words( $question->post_content, $this->options['questions-introtext-limit'] );

				// Image
				$question->image = get_the_post_thumbnail_url( $question->ID, 'full' );

				// Date
				$question->formatted_date = get_the_date( $this->options['questions-date-format'], $question->ID );
			}
		}

		ob_start();

		if ($topic->questions && !empty($topic->questions))
		{
		  $data = array();
		  $data['params'] = $topicParams;
		  $data['questions'] = $topic->questions;
		  $questions = do_action('mf_topicquestions', $data, $topic_id);
		}

		$questions = ob_get_clean();

		echo json_encode(array('questions' => $questions, 'remaining' => $topic_questions['remaining']));

		wp_die();

	}

	/**
	 * Loads the top navigation.
	 *
	 * @since    	1.0.1
	 */
	public function mf_topnav_display ($section_id = '') {

		$output = '<div class="mfTopNavigation_core_outer">';

			$output .= '<div class="mfTopNavigation_core">';

				$output .= '<div class="mfTopNavigation_wrap">';

					$output .= '<ul class="mfTopNavigation_root mf-hidden-phone">';

						if (is_page())
						{
							$content = get_the_content();
							if (has_shortcode($content, 'mf-sections'))
							{
								$output .= '<li class="NavTopUL_home">';
									$output .= '<a class="NavTopUL_link" href="'.esc_url(get_the_permalink()).'">';
										$output .= '<i class="dashicons dashicons-admin-home NavTopUL_homeIcon"></i>&nbsp;&nbsp;';
										$output .= get_the_title();
									$output .= '</a>';
								$output .= '</li>';
							}
						}

						if (!is_page() && $section_id)
						{
							$sectionParams = get_term_meta($section_id);
							if ($sectionParams && isset($sectionParams['section-top-nav-root'][0]) && $sectionParams['section-top-nav-root'][0] > 0)
							{
								// Get topnav root page
								$top_nav_root = get_post($sectionParams['section-top-nav-root'][0]);
								$output .= '<li class="NavTopUL_home">';
									$output .= '<a class="NavTopUL_link" href="'.esc_url(get_page_link($sectionParams['section-top-nav-root'][0])).'">';
										$output .= '<i class="dashicons dashicons-admin-home NavTopUL_homeIcon"></i>&nbsp;&nbsp;';
										$output .= $top_nav_root->post_title;
									$output .= '</a>';
								$output .= '</li>';
							}

							// Get section
							$sectionObj = get_term_by('id', $section_id, 'mf_section');
							$output .= '<li id="top_liid_home" class="NavTopUL_item NavTopUL_firstChild NavTopUL_lastChild">';
								if ($sectionParams && isset($sectionParams['section-top-nav-root'][0]) && $sectionParams['section-top-nav-root'][0] > 0)
								{
									$output .= '<i class="dashicons dashicons-arrow-right NavTopUL_homeIcon"></i>&nbsp;';
								}
								else
								{
									$output .= '<i class="dashicons dashicons-admin-home NavTopUL_homeIcon"></i>&nbsp;';
								}
								$output .= '<a class="NavTopUL_link" href="'.esc_url(get_term_link($sectionObj->term_id)).'" onclick="return false;" data-title="'.esc_html($sectionObj->name).'">';
									$output .= $sectionObj->name;
								$output .= '</a>';
							$output .= '</li>';
						}

					$output .= '</ul>';

					$output .= '<span class="NavTopUL_loading"></span>';

				$output .= '</div>';

				$output .= '<div class="mfNavTopUL_buttons">';

					// Menu icon
					if (!is_page())
					{
						$sectionParams = get_term_meta($section_id);
						if ($sectionParams && isset($sectionParams['section-left-nav']) && $sectionParams['section-left-nav'][0] == 'yes')
						{
							$output .= '<div class="show_menu"><a href="#" onclick="return false;" class="btn btn-default"><i class="dashicons dashicons-menu"></i></a></div>';
						}
					}

				$output .= '</div>';

			$output .= '</div>';

		$output .= '<div class="clearfix"> </div>';

		$output .= '</div>';

		return $output;

	}

	/**
	 * Loads the left navigation.
	 *
	 * @since    	1.0.1
	 */
	public function mf_leftnav_display ($section_id) {

		// Get section params
		$sectionParams = get_term_meta($section_id);

		$topics = $this->dataSource->mf_get_flat_topics($section_id);

		if (empty($topics))
		{
			return;
		}

		$leftnav_class = 'leftnav-hidden';
		if ($sectionParams && isset($sectionParams['section-left-nav']) && $sectionParams['section-left-nav'][0] == 'yes')
		{
			$leftnav_class = '';
		}

		$topics_tree = array();

		foreach ($topics as $topic)
		{
			$topicsTree = $this->mf_leftnav_topic_tree($topic);
			$topics_tree[] = $topicsTree;
		}

		$output = '<div class="mfLeftNavigation_core mf-hidden '.$leftnav_class.'">';

			$output .= '<div class="mfLeftNavigation_root">';

				$output .= '<div id="mf_l_n" class="mfLeftNavigation_wrap">';

					$output .= '<ul id="NavLeftUL" class="NavLeftUL_parent level0">';

					foreach ($topics_tree as $topic_tree)
					{
						$output .= $topic_tree;
					}

					$output .= '</ul>';

				$output .= '</div>';

			$output .= '</div>';

		$output .= '</div>';

		return $output;

	}

	/**
	 * Creates leftnav tree for each topic.
	 *
	 * @since    	1.0.1
	 */
	public function mf_leftnav_topic_tree ($topic, $level = 1) {

		// Get topic params
		$topicParams = get_term_meta($topic->term_id);

		$subtopics = $this->dataSource->mf_get_flat_topics(false, $topic->term_id);
		$output = '';

		if (count($subtopics))
		{
			$depth = $this->dataSource->mf_get_topic_depth($topic->term_id, $levels = 0);

			if ($depth === 0)
			{
				$topic_class = ' NavLeftUL_endpoint';
				$span_class = 'NavLeftUL_endpointIcon';
			}
			else
			{
				$topic_class = '';
				$span_class = 'NavLeftUL_navIcon dashicons dashicons-arrow-right-alt2';
			}

			$output .= '<li id="liid'.$topic->term_id.'" class="NavLeftUL_item'.$topic_class.'">';
			$output .= '<a href="'.esc_url(get_term_link((int)$topic->term_id)).'" class="NavLeftUL_anchor" rel="nofollow" onclick="return false;">';
			$output .= '<span class="catTitle">';
			if ($topicParams && isset($topicParams['topic-icon-class']) && $topicParams['topic-icon-class'][0])
			{
				$output .= '<i class="NavLeftUL_topicIcon dashicons dashicons-'.$topicParams['topic-icon-class'][0].'"></i>';
			}
			$output .= $topic->name.'<span class="'.$span_class.'"></span>';
			$output .= '</span>';
			$output .= '</a>';
			$output .= '<ul class="NavLeftUL_sublist level'.$level.'">';

			foreach ($subtopics as $subtopic)
			{
			  	$output .= $this->mf_leftnav_topic_tree($subtopic, $level + 1);
			}

			$output .= '<li id="backliid'.$topic->term_id.'" class="NavLeftUL_backItem">';
			$output .= '<a href="#" class="NavLeftUL_anchor" rel="nofollow" onclick="return false;"><span>';
			$output .= __( 'Back', 'minitek-faq' );
			$output .= '<span class="NavLeftUL_navBackIcon dashicons dashicons-undo"></span></span></a></li>';
			$output .= '</ul>';
			$output .= '</li>';
		}
		else
		{
		  	$output .= '<li id="liid'.$topic->term_id.'" class="NavLeftUL_item NavLeftUL_endpoint">';
			$output .= '<a href="'.esc_url(get_term_link((int)$topic->term_id)).'" class="NavLeftUL_anchor" rel="nofollow" onclick="return false;">';
			$output .= '<span class="catTitle">';
			if ($topicParams && isset($topicParams['topic-icon-class']) && $topicParams['topic-icon-class'][0])
			{
				$output .= '<i class="NavLeftUL_topicIcon dashicons dashicons-'.$topicParams['topic-icon-class'][0].'"></i>';
			}
			$output .= $topic->name.'<span class="NavLeftUL_endpointIcon"></span>';
			$output .= '</span>';
			$output .= '</a>';
			$output .= '</li>';
		}

		return $output;

	}

}
