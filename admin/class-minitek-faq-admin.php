<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @since      	1.0.1
 * @package    	Minitek-Faq
 * @subpackage 	Minitek-Faq/admin
 */

class MFaq_Admin {

	/**
	 * The plugin options.
	 *
	 * @since			1.0.1
	 * @access 		private
	 * @var 			string 			$options    The plugin options.
	 */
	private $options;

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
	 * Initialize the class and set its properties.
	 *
	 * @since    	1.0.1
	 * @param    	string    $plugin_name       The name of this plugin.
	 * @param    	string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->set_options();

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    	1.0.1
	 */
	public function mf_enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/minitek-faq-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    	1.0.1
	 */
	public function mf_enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/minitek-faq-admin.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name . '-tabs', plugin_dir_url( __FILE__ ) . 'js/minitek-faq-admin-tabs.js', array( 'jquery' ), $this->version );

	}

	/**
	 * Add settings/help page links to plugin menu.
	 *
	 * @since 		1.0.1
	 */
	public function mf_add_menu() {

		add_submenu_page(
			'edit.php?post_type=mf_question',
			apply_filters( $this->plugin_name . '-settings-page-title', esc_html__( 'Settings', 'minitek-faq' ) ),
			apply_filters( $this->plugin_name . '-settings-menu-title', esc_html__( 'Settings', 'minitek-faq' ) ),
			'manage_options',
			$this->plugin_name . '-settings',
			array( $this, 'page_settings' )
		);

		add_submenu_page(
			'edit.php?post_type=mf_question',
			apply_filters( $this->plugin_name . '-about-page-title', esc_html__( 'About', 'minitek-faq' ) ),
			apply_filters( $this->plugin_name . '-about-menu-title', esc_html__( 'About', 'minitek-faq' ) ),
			'manage_options',
			$this->plugin_name . '-about',
			array( $this, 'page_about' )
		);

	}

	/**
	 * Creates the about page.
	 *
	 * @since 		1.0.1
	 * @return 		void
	 */
	public function page_about() {

		include( plugin_dir_path( __FILE__ ) . 'partials/minitek-faq-admin-page-about.php' );

	}

	/**
	 * Creates the settings page.
	 *
	 * @since 		1.0.1
	 * @return 		void
	 */
	public function page_settings() {

		include( plugin_dir_path( __FILE__ ) . 'partials/minitek-faq-admin-page-settings.php' );

	}

	/**
	 * Sets the class variable $options.
	 *
	 * @since 		1.0.1
	 */
	private function set_options() {

		$this->options = get_option( $this->plugin_name . '-options' );

	}

	/**
	 * Returns an array of options names, fields types, and default values.
	 *
	 * @since 		1.0.1
	 * @return 		array 			An array of options
	 */
	public static function get_options_list() {

		$options = array();

		// Sections
		$options[] = array( 'top-navigation', 'radio', '' );
		$options[] = array( 'sections-columns', 'text', '' );
		$options[] = array( 'section-title', 'radio', '' );
		$options[] = array( 'section-description', 'radio', '' );
		$options[] = array( 'section-image', 'radio', '' );
		$options[] = array( 'section-topics', 'radio', '' );

		// Topics
		$options[] = array( 'topic-title', 'radio', '' );
		$options[] = array( 'topic-description', 'radio', '' );
		$options[] = array( 'topic-image', 'radio', '' );
		$options[] = array( 'topic-content', 'radio', '' );

		$options[] = array( 'subtopics', 'radio', '' );
		$options[] = array( 'subtopics-title', 'radio', '' );
		$options[] = array( 'subtopics-description', 'radio', '' );
		$options[] = array( 'subtopics-image', 'radio', '' );
		$options[] = array( 'subtopics-content', 'radio', '' );
		$options[] = array( 'subtopics-questions', 'radio', '' );

		$options[] = array( 'questions-ordering', 'select', '' );
		$options[] = array( 'questions-direction', 'radio', '' );
		$options[] = array( 'questions-limit', 'text', '' );
		$options[] = array( 'questions-opened', 'radio', '' );
		$options[] = array( 'questions-image', 'radio', '' );
		$options[] = array( 'questions-introtext', 'radio', '' );
		$options[] = array( 'questions-introtext-limit', 'text', '' );
		$options[] = array( 'questions-date', 'radio', '' );
		$options[] = array( 'questions-date-format', 'text', '' );
		$options[] = array( 'questions-author', 'radio', '' );

    // Slugs
    $options[] = array( 'root-slug', 'text', '' );
    $options[] = array( 'prefix-slugs', 'text', '' );
		$options[] = array( 'prefix-sections', 'text', '' );
    $options[] = array( 'prefix-topics', 'text', '' );

		return $options;

	}

	/**
	 * Creates a separator field.
	 *
	 * @since 		1.0.1
	 * @param 		array 		$args 			The arguments for the field
	 * @return 		string 						The HTML field
	 */
	public function field_separator( $args ) {

		$defaults['class'] = $this->plugin_name . '-options[' . $args['class'] . ']';
		$defaults['label'] = $this->plugin_name . '-options[' . $args['label'] . ']';
		$defaults['name'] = '';
		$defaults['type'] = 'separator';

		apply_filters( $this->plugin_name . '-field-text-options-defaults', $defaults );

		$atts = wp_parse_args( $args, $defaults );

	}

	/**
	 * Creates a text field.
	 *
	 * @since 		1.0.1
	 * @param 		array 		$args 			The arguments for the field
	 * @return 		string 						The HTML field
	 */
	public function field_text( $args ) {

		$defaults['class'] = 'regular-text';
		$defaults['description'] = '';
		$defaults['label'] = '';
		$defaults['name'] = $this->plugin_name . '-options[' . $args['id'] . ']';
		$defaults['placeholder'] = '';
		$defaults['type'] = 'text';
		$defaults['value'] = '';

		apply_filters( $this->plugin_name . '-field-' . $args['id'], $defaults );

		$atts = wp_parse_args( $args, $defaults );

		if ( ! empty( $this->options[$atts['id']] ) ) {

			$atts['value'] = $this->options[$atts['id']];

		}

		include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-field-text.php' );

	}

	/**
	 * Creates a textarea field.
	 *
	 * @since 		1.0.1
	 * @param 		array 		$args 			The arguments for the field
	 * @return 		string 						The HTML field
	 */
	public function field_textarea( $args ) {

		$defaults['class'] = 'regular-text';
		$defaults['description'] = '';
		$defaults['label'] = '';
		$defaults['name'] = $this->plugin_name . '-options[' . $args['id'] . ']';
		$defaults['placeholder'] = '';
		$defaults['type'] = 'textarea';
		$defaults['value'] = '';

		apply_filters( $this->plugin_name . '-field-' . $args['id'], $defaults );

		$atts = wp_parse_args( $args, $defaults );

		if ( ! empty( $this->options[$atts['id']] ) ) {

			$atts['value'] = $this->options[$atts['id']];

		}

		include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-field-textarea.php' );

	}

	/**
	 * Creates a radio field.
	 *
	 * @since 		1.0.1
	 * @param 		array 		$args 			The arguments for the field
	 * @return 		string 						The HTML field
	 */
	public function field_radio( $args ) {

		$defaults['class'] = '';
		$defaults['description'] = '';
		$defaults['label'] = '';
		$defaults['name'] = $this->plugin_name . '-options[' . $args['id'] . ']';
		$defaults['type'] = 'radio';
		$defaults['value'] = '';

		apply_filters( $this->plugin_name . '-field-' . $args['id'], $defaults );

		$atts = wp_parse_args( $args, $defaults );

		if ( ! empty( $this->options[$atts['id']] ) ) {

			$atts['value'] = $this->options[$atts['id']];

		}

		include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-field-radio.php' );

	}

	/**
	 * Creates a select field.
	 *
	 * @since 		1.0.1
	 * @param 		array 		$args 			The arguments for the field
	 * @return 		string 						The HTML field
	 */
	public function field_select( $args ) {

		$defaults['class'] = '';
		$defaults['description'] = '';
		$defaults['label'] = '';
		$defaults['name'] = $this->plugin_name . '-options[' . $args['id'] . ']';
		$defaults['type'] = 'select';
		$defaults['value'] = '';

		apply_filters( $this->plugin_name . '-field-' . $args['id'], $defaults );

		$atts = wp_parse_args( $args, $defaults );

		if ( ! empty( $this->options[$atts['id']] ) ) {

			$atts['value'] = $this->options[$atts['id']];

		}

		include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-field-select.php' );

	}

	/**
	 * Creates a topic select field.
	 *
	 * @since 		1.0.1
	 * @param 		array 		$args 			The arguments for the field
	 * @return 		string 						The HTML field
	 */
	public function field_topics( $args ) {

		$defaults['class'] = '';
		$defaults['description'] = '';
		$defaults['label'] = '';
		$defaults['name'] = $this->plugin_name . '-options[' . $args['id'] . ']';
		$defaults['type'] = 'select';
		$defaults['value'] = '';

		apply_filters( $this->plugin_name . '-field-' . $args['id'], $defaults );

		$atts = wp_parse_args( $args, $defaults );

		if ( ! empty( $this->options[$atts['id']] ) ) {

			$atts['value'] = $this->options[$atts['id']];

		}

		include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-field-topics.php' );

	}

	/**
	 * Registers settings fields with WordPress.
	 *
	 * @since 		1.0.1
	 */
	public function mf_register_fields() {

		// add_settings_field( $id, $title, $callback, $menu_slug, $section, $args );

		// Top Navigation
		add_settings_field(
			'top-navigation',
			apply_filters( $this->plugin_name . 'label-top-navigation', esc_html__( 'Top navigation', 'minitek-faq' ) ),
			array( $this, 'field_radio' ),
			$this->plugin_name,
			$this->plugin_name . '-sections',
			array(
				'description' => '',
				'id' => 'top-navigation',
				'value' => 'yes',
				'selections' => array(
					array(
					  'value' => 'yes',
					  'label' => 'Yes'
					),
					array(
					  'value' => 'no',
					  'label' => 'No'
					)
				)
			)
		);

		// Sections Columns
		add_settings_field(
			'sections-columns',
			apply_filters( $this->plugin_name . 'label-sections-columns', esc_html__( 'Columns', 'minitek-faq' ) ),
			array( $this, 'field_text' ),
			$this->plugin_name,
			$this->plugin_name . '-sections',
			array(
				'description' => '',
				'id' => 'sections-columns',
				'value' => '3'
			)
		);

		// Section Title
		add_settings_field(
			'section-title',
			apply_filters( $this->plugin_name . 'label-section-title', esc_html__( 'Section title', 'minitek-faq' ) ),
			array( $this, 'field_radio' ),
			$this->plugin_name,
			$this->plugin_name . '-sections',
			array(
				'description' => '',
				'id' => 'section-title',
				'value' => 'yes',
				'selections' => array(
					array(
					  'value' => 'yes',
					  'label' => 'Yes'
					),
					array(
					  'value' => 'no',
					  'label' => 'No'
					)
				)
			)
		);

		// Section Description
		add_settings_field(
			'section-description',
			apply_filters( $this->plugin_name . 'label-section-description', esc_html__( 'Section description', 'minitek-faq' ) ),
			array( $this, 'field_radio' ),
			$this->plugin_name,
			$this->plugin_name . '-sections',
			array(
				'description' => '',
				'id' => 'section-description',
				'value' => 'yes',
				'selections' => array(
					array(
					  'value' => 'yes',
					  'label' => 'Yes'
					),
					array(
					  'value' => 'no',
					  'label' => 'No'
					)
				)
			)
		);

		// Section Image
		add_settings_field(
			'section-image',
			apply_filters( $this->plugin_name . 'label-section-image', esc_html__( 'Section image', 'minitek-faq' ) ),
			array( $this, 'field_radio' ),
			$this->plugin_name,
			$this->plugin_name . '-sections',
			array(
				'description' => '',
				'id' => 'section-image',
				'value' => 'yes',
				'selections' => array(
					array(
					  'value' => 'yes',
					  'label' => 'Yes'
					),
					array(
					  'value' => 'no',
					  'label' => 'No'
					)
				)
			)
		);

		// Section Topics
		add_settings_field(
			'section-topics',
			apply_filters( $this->plugin_name . 'label-section-topics', esc_html__( 'Section topics', 'minitek-faq' ) ),
			array( $this, 'field_radio' ),
			$this->plugin_name,
			$this->plugin_name . '-sections',
			array(
				'description' => '',
				'id' => 'section-topics',
				'value' => 'yes',
				'selections' => array(
					array(
					  'value' => 'yes',
					  'label' => 'Yes'
					),
					array(
					  'value' => 'no',
					  'label' => 'No'
					)
				)
			)
		);

		// Separator: Topic
		add_settings_field(
			'separator-topic',
			apply_filters( $this->plugin_name . 'label-separator', esc_html__( 'Topic:', 'minitek-faq' ) ),
			array( $this, 'field_separator' ),
			$this->plugin_name,
			$this->plugin_name . '-topics',
			array(
				'class' => 'separator',
				'label' => 'Topic',
			)
		);

		// Topic Title
		add_settings_field(
			'topic-title',
			apply_filters( $this->plugin_name . 'label-topic-title', esc_html__( 'Topic title', 'minitek-faq' ) ),
			array( $this, 'field_radio' ),
			$this->plugin_name,
			$this->plugin_name . '-topics',
			array(
				'description' => '',
				'id' => 'topic-title',
				'value' => 'yes',
				'selections' => array(
					array(
					  'value' => 'yes',
					  'label' => 'Yes'
					),
					array(
					  'value' => 'no',
					  'label' => 'No'
					)
				)
			)
		);

		// Topic Description
		add_settings_field(
			'topic-description',
			apply_filters( $this->plugin_name . 'label-topic-description', esc_html__( 'Topic description', 'minitek-faq' ) ),
			array( $this, 'field_radio' ),
			$this->plugin_name,
			$this->plugin_name . '-topics',
			array(
				'description' => '',
				'id' => 'topic-description',
				'value' => 'yes',
				'selections' => array(
					array(
					  'value' => 'yes',
					  'label' => 'Yes'
					),
					array(
					  'value' => 'no',
					  'label' => 'No'
					)
				)
			)
		);

		// Topic Image
		add_settings_field(
			'topic-image',
			apply_filters( $this->plugin_name . 'label-topic-image', esc_html__( 'Topic image', 'minitek-faq' ) ),
			array( $this, 'field_radio' ),
			$this->plugin_name,
			$this->plugin_name . '-topics',
			array(
				'description' => '',
				'id' => 'topic-image',
				'value' => 'yes',
				'selections' => array(
					array(
					  'value' => 'yes',
					  'label' => 'Yes'
					),
					array(
					  'value' => 'no',
					  'label' => 'No'
					)
				)
			)
		);

		// Topic Content
		add_settings_field(
			'topic-content',
			apply_filters( $this->plugin_name . 'label-topic-content', esc_html__( 'Topic content', 'minitek-faq' ) ),
			array( $this, 'field_radio' ),
			$this->plugin_name,
			$this->plugin_name . '-topics',
			array(
				'description' => '',
				'id' => 'topic-content',
				'value' => 'yes',
				'selections' => array(
					array(
					  'value' => 'yes',
					  'label' => 'Yes'
					),
					array(
					  'value' => 'no',
					  'label' => 'No'
					)
				)
			)
		);

		// Separator: Subtopics
		add_settings_field(
			'separator-subtopics',
			apply_filters( $this->plugin_name . 'label-separator', esc_html__( 'Subtopics:', 'minitek-faq' ) ),
			array( $this, 'field_separator' ),
			$this->plugin_name,
			$this->plugin_name . '-topics',
			array(
				'class'	=> 'separator',
				'label'	=> 'Subtopics',
			)
		);

		// Show Subtopics
		add_settings_field(
			'subtopics',
			apply_filters( $this->plugin_name . 'label-subtopics', esc_html__( 'Show subtopics', 'minitek-faq' ) ),
			array( $this, 'field_radio' ),
			$this->plugin_name,
			$this->plugin_name . '-topics',
			array(
				'description' => '',
				'id' => 'subtopics',
				'value' => 'yes',
				'selections' => array(
					array(
					  'value' => 'yes',
					  'label' => 'Yes'
					),
					array(
					  'value' => 'no',
					  'label' => 'No'
					)
				)
			)
		);

		// Subtopics Title
		add_settings_field(
			'subtopics-title',
			apply_filters( $this->plugin_name . 'label-subtopics-title', esc_html__( 'Subtopics title', 'minitek-faq' ) ),
			array( $this, 'field_radio' ),
			$this->plugin_name,
			$this->plugin_name . '-topics',
			array(
				'description' => '',
				'id' => 'subtopics-title',
				'value' => 'yes',
				'selections' => array(
					array(
					  'value' => 'yes',
					  'label' => 'Yes'
					),
					array(
					  'value' => 'no',
					  'label' => 'No'
					)
				)
			)
		);

		// Subtopics Description
		add_settings_field(
			'subtopics-description',
			apply_filters( $this->plugin_name . 'label-subtopics-description', esc_html__( 'Subtopics description', 'minitek-faq' ) ),
			array( $this, 'field_radio' ),
			$this->plugin_name,
			$this->plugin_name . '-topics',
			array(
				'description' => '',
				'id' => 'subtopics-description',
				'value' => 'yes',
				'selections' => array(
					array(
					  'value' => 'yes',
					  'label' => 'Yes'
					),
					array(
					  'value' => 'no',
					  'label' => 'No'
					)
				)
			)
		);

		// Subtopics Image
		add_settings_field(
			'subtopics-image',
			apply_filters( $this->plugin_name . 'label-subtopics-image', esc_html__( 'Subtopics image', 'minitek-faq' ) ),
			array( $this, 'field_radio' ),
			$this->plugin_name,
			$this->plugin_name . '-topics',
			array(
				'description' => '',
				'id' => 'subtopics-image',
				'value' => 'yes',
				'selections' => array(
					array(
					  'value' => 'yes',
					  'label' => 'Yes'
					),
					array(
					  'value' => 'no',
					  'label' => 'No'
					)
				)
			)
		);

		// Subtopics Content
		add_settings_field(
			'subtopics-content',
			apply_filters( $this->plugin_name . 'label-subtopics-content', esc_html__( 'Subtopics content', 'minitek-faq' ) ),
			array( $this, 'field_radio' ),
			$this->plugin_name,
			$this->plugin_name . '-topics',
			array(
				'description' => '',
				'id' => 'subtopics-content',
				'value' => 'yes',
				'selections' => array(
					array(
					  'value' => 'yes',
					  'label' => 'Yes'
					),
					array(
					  'value' => 'no',
					  'label' => 'No'
					)
				)
			)
		);

		// Subtopics Questions
		add_settings_field(
			'subtopics-questions',
			apply_filters( $this->plugin_name . 'label-subtopics-questions', esc_html__( 'Subtopics questions', 'minitek-faq' ) ),
			array( $this, 'field_radio' ),
			$this->plugin_name,
			$this->plugin_name . '-topics',
			array(
				'description' => '',
				'id' => 'subtopics-questions',
				'value' => 'yes',
				'selections' => array(
					array(
					  'value' => 'yes',
					  'label' => 'Yes'
					),
					array(
					  'value' => 'no',
					  'label' => 'No'
					)
				)
			)
		);

		// Separator: Questions
		add_settings_field(
			'separator-questions',
			apply_filters( $this->plugin_name . 'label-separator', esc_html__( 'Questions:', 'minitek-faq' ) ),
			array( $this, 'field_separator' ),
			$this->plugin_name,
			$this->plugin_name . '-topics',
			array(
				'class'	=> 'separator',
				'label'	=> 'Questions',
			)
		);

		// Questions Ordering
		add_settings_field(
			'questions-ordering',
			apply_filters( $this->plugin_name . 'label-questions-ordering', esc_html__( 'Questions ordering', 'minitek-faq' ) ),
			array( $this, 'field_select' ),
			$this->plugin_name,
			$this->plugin_name . '-topics',
			array(
				'description' => 'Questions ordering in topics.',
				'id' => 'questions-ordering',
				'value' => 'post_date',
				'selections' => array(
					array(
					  'value' => 'post_author',
					  'label' => 'Author'
					),
					array(
					  'value' => 'post_title',
					  'label' => 'Title'
					),
					array(
					  'value' => 'post_date',
					  'label' => 'Date created'
					),
					array(
					  'value' => 'post_modified',
					  'label' => 'Date modified'
					)
				)
			)
		);

		// Questions direction
		add_settings_field(
			'questions-direction',
			apply_filters( $this->plugin_name . 'label-questions-direction', esc_html__( 'Ordering direction', 'minitek-faq' ) ),
			array( $this, 'field_radio' ),
			$this->plugin_name,
			$this->plugin_name . '-topics',
			array(
				'description' => '',
				'id' => 'questions-direction',
				'value' => 'DESC',
				'selections' => array(
					array(
					  'value' => 'ASC',
					  'label' => 'Ascending'
					),
					array(
					  'value' => 'DESC',
					  'label' => 'Descending'
					)
				)
			)
		);

		// Questions limit
		add_settings_field(
			'questions-limit',
			apply_filters( $this->plugin_name . 'label-questions-limit', esc_html__( 'Questions limit', 'minitek-faq' ) ),
			array( $this, 'field_text' ),
			$this->plugin_name,
			$this->plugin_name . '-topics',
			array(
				'description' => 'Amount of questions to be displayed for each topic on page load. User will be able to load more questions by clicking on the "Load more" button. Enter "0" for unlimited questions.',
				'id' => 'questions-limit',
				'value' => '5'
			)
		);

		// Show Questions Opened
		add_settings_field(
			'questions-opened',
			apply_filters( $this->plugin_name . 'label-questions-opened', esc_html__( 'Show questions opened', 'minitek-faq' ) ),
			array( $this, 'field_radio' ),
			$this->plugin_name,
			$this->plugin_name . '-topics',
			array(
				'description' => '',
				'id' => 'questions-opened',
				'value' => 'no',
				'selections' => array(
					array(
					  'value' => 'yes',
					  'label' => 'Yes'
					),
					array(
					  'value' => 'no',
					  'label' => 'No'
					)
				)
			)
		);

		// Questions Image
		add_settings_field(
			'questions-image',
			apply_filters( $this->plugin_name . 'label-questions-image', esc_html__( 'Questions image', 'minitek-faq' ) ),
			array( $this, 'field_radio' ),
			$this->plugin_name,
			$this->plugin_name . '-topics',
			array(
				'description' => '',
				'id' => 'questions-image',
				'value' => 'no',
				'selections' => array(
					array(
					  'value' => 'yes',
					  'label' => 'Yes'
					),
					array(
					  'value' => 'no',
					  'label' => 'No'
					)
				)
			)
		);

		// Questions Introtext
		add_settings_field(
			'questions-introtext',
			apply_filters( $this->plugin_name . 'label-questions-introtext', esc_html__( 'Questions introtext', 'minitek-faq' ) ),
			array( $this, 'field_radio' ),
			$this->plugin_name,
			$this->plugin_name . '-topics',
			array(
				'description' => 'Show part of question content before opening question.',
				'id' => 'questions-introtext',
				'value' => 'yes',
				'selections' => array(
					array(
					  'value' => 'yes',
					  'label' => 'Yes'
					),
					array(
					  'value' => 'no',
					  'label' => 'No'
					)
				)
			)
		);

		// Questions Introtext Limit
		add_settings_field(
			'questions-introtext-limit',
			apply_filters( $this->plugin_name . 'label-questions-introtext-limit', esc_html__( 'Introtext limit', 'minitek-faq' ) ),
			array( $this, 'field_text' ),
			$this->plugin_name,
			$this->plugin_name . '-topics',
			array(
				'description' => 'Introtext will be limited to this word count.',
				'id' => 'questions-introtext-limit',
				'value' => '15'
			)
		);

		// Questions Date
		add_settings_field(
			'questions-date',
			apply_filters( $this->plugin_name . 'label-questions-date', esc_html__( 'Questions date', 'minitek-faq' ) ),
			array( $this, 'field_radio' ),
			$this->plugin_name,
			$this->plugin_name . '-topics',
			array(
				'description' => '',
				'id' => 'questions-date',
				'value' => 'yes',
				'selections' => array(
					array(
					  'value' => 'yes',
					  'label' => 'Yes'
					),
					array(
					  'value' => 'no',
					  'label' => 'No'
					)
				)
			)
		);

		// Questions Date Format
		add_settings_field(
			'questions-date-format',
			apply_filters( $this->plugin_name . 'label-questions-date-format', esc_html__( 'Date format', 'minitek-faq' ) ),
			array( $this, 'field_text' ),
			$this->plugin_name,
			$this->plugin_name . '-topics',
			array(
				'description' => 'Documentation on date and time formatting',
				'id' => 'questions-date-format',
				'value' => 'l F d',
				'desc_link' => 'https://codex.wordpress.org/Formatting_Date_and_Time'
			)
		);

		// Questions Author
		add_settings_field(
			'questions-author',
			apply_filters( $this->plugin_name . 'label-questions-author', esc_html__( 'Questions author', 'minitek-faq' ) ),
			array( $this, 'field_radio' ),
			$this->plugin_name,
			$this->plugin_name . '-topics',
			array(
				'description' => '',
				'id' => 'questions-author',
				'value' => 'yes',
				'selections' => array(
					array(
					  'value' => 'yes',
					  'label' => 'Yes'
					),
					array(
					  'value' => 'no',
					  'label' => 'No'
					)
				)
			)
		);

    // Root
		add_settings_field(
			'root-slug',
			apply_filters( $this->plugin_name . 'label-root-slug', esc_html__( 'Root', 'minitek-faq' ) ),
			array( $this, 'field_text' ),
			$this->plugin_name,
			$this->plugin_name . '-slugs',
			array(
				'description' => '',
				'id' => 'root-slug',
				'value' => 'faq'
			)
		);

    // Prefix slugs
		add_settings_field(
			'prefix-slugs',
			apply_filters( $this->plugin_name . 'label-prefix-slugs', esc_html__( 'Prefix all slugs', 'minitek-faq' ) ),
			array( $this, 'field_radio' ),
			$this->plugin_name,
			$this->plugin_name . '-slugs',
			array(
				'description' => 'Prefix all FAQ content with the Root slug.',
				'id' => 'prefix-slugs',
				'value' => 'yes',
				'selections' => array(
					array(
					  'value' => 'yes',
					  'label' => 'Yes'
					),
					array(
					  'value' => 'no',
					  'label' => 'No'
					)
				)
			)
		);

		// Prefix sections
		add_settings_field(
			'prefix-sections',
			apply_filters( $this->plugin_name . 'label-prefix-sections', esc_html__( 'Sections prefix', 'minitek-faq' ) ),
			array( $this, 'field_text' ),
			$this->plugin_name,
			$this->plugin_name . '-slugs',
			array(
				'description' => '',
				'id' => 'prefix-sections',
				'value' => 'mf-section'
			)
		);

		// Prefix topics
		add_settings_field(
			'prefix-topics',
			apply_filters( $this->plugin_name . 'label-prefix-topics', esc_html__( 'Topics prefix', 'minitek-faq' ) ),
			array( $this, 'field_text' ),
			$this->plugin_name,
			$this->plugin_name . '-slugs',
			array(
				'description' => '',
				'id' => 'prefix-topics',
				'value' => 'mf-topic'
			)
		);

	}

	/**
	 * Registers plugin settings.
	 *
	 * @since 		1.0.1
	 * @return 		void
	 */
	public function mf_register_settings() {

		// register_setting( $option_group, $option_name, $sanitize_callback );

		register_setting(
			$this->plugin_name . '-options',
			$this->plugin_name . '-options',
			array( $this, 'validate_options' )
		);

	}

	/**
	 * Registers settings sections with WordPress.
	 *
	 * @since 		1.0.1
	 */
	public function mf_register_sections() {

		// add_settings_section( $id, $title, $callback, $menu_slug );

		// Section: Sections
		add_settings_section(
			$this->plugin_name . '-sections',
			apply_filters( $this->plugin_name . 'section-title-sections', '' ),
			'',
			$this->plugin_name
		);

		// Section: Topics
		add_settings_section(
			$this->plugin_name . '-topics',
			apply_filters( $this->plugin_name . 'section-title-topics', '' ),
			'',
			$this->plugin_name
		);

    // Section: Slugs
		add_settings_section(
			$this->plugin_name . '-slugs',
			apply_filters( $this->plugin_name . 'section-title-slugs', '' ),
			'',
			$this->plugin_name
		);

	}

	/**
	 * Sanitizes plugin options.
	 *
	 * @since 		1.0.1
	 * @param 		array 		$input 			array of submitted plugin options
	 * @return 		array 						array of validated plugin options
	 */
	private function sanitizer( $type, $data ) {

		if ( empty( $type ) ) { return; }
		if ( empty( $data ) ) { return; }

		$return 	= '';
		$sanitizer 	= new MFaq_Sanitize();

		$sanitizer->set_data( $data );
		$sanitizer->set_type( $type );

		$return = $sanitizer->clean();

		unset( $sanitizer );

		return $return;

	}

	/**
	 * Validates saved options.
	 *
	 * @since 		1.0.1
	 * @param 		array 		$input 			array of submitted plugin options
	 * @return 		array 						array of validated plugin options
	 */
	public function validate_options( $input ) {

		//wp_die( print_r( $input ) );

		$valid 		= array();
		$options 	= $this->get_options_list();

		foreach ( $options as $option ) {

			$name = $option[0];
			$type = $option[1];

			$valid[$option[0]] = $this->sanitizer( $type, $input[$name] );

		}

		return $valid;

	}

	/**
	 * Shows an error in Topics page when no sections are found.
	 *
	 * @since 		1.0.1
	 */
	public static function mf_admin_notice_section_error() {

		$args = array(
			'taxonomy' => 'mf_section',
			'orderby' => 'name',
			'show_count' => 0,
			'pad_counts' => 0,
			'hierarchical' => FALSE,
			'title_li' => '',
			'hide_empty' => FALSE
		);

		$all_sections = get_categories( $args );

		if ( count($all_sections) == 0 && 'edit-mf_topic' === get_current_screen()->id ) {

			?><div class="notice notice-error is-dismissible">
				<p><?php _e( 'Topics must be assigned to Sections. Please create a Section first.', 'minitek-faq' ); ?></p>
			</div><?php

		}

	}

	/**
	 * Create a new custom taxonomy (mf_section).
	 *
	 * @since 		1.0.1
	 */
	public static function mf_section_register_taxonomy() {

		$taxonomy_name = 'mf_section';
		$works_with = 'mf_question';
		$plural = 'Sections';
		$single = 'Section';
		$slug = '';

    if (isset($this->options['root-slug']) && $this->options['root-slug'])
    {
      if (isset($this->options['prefix-slugs']) && $this->options['prefix-slugs'] == 'yes')
      {
        $slug .= $this->options['root-slug'].'/';
      }
    }
    if (isset($this->options['prefix-sections']) && $this->options['prefix-sections'])
    {
      $slug .= $this->options['prefix-sections'];
    }
		else
		{
			$slug .= 'mf-section';
		}
    if ($slug == '')
    {
      $slug .= 'mf-section';
    }

		$labels = array(
			'name' => esc_html__( "FAQ {$plural}", 'minitek-faq' ),
			'singular_name' => esc_html__( $single, 'minitek-faq' ),
			'menu_name' => esc_html__( $plural, 'minitek-faq' ),
			'all_items' => esc_html__( "All {$plural}", 'minitek-faq' ),
			'edit_item' => esc_html__( "Edit {$single}", 'minitek-faq' ),
			'view_item' => esc_html__( "View {$single}", 'minitek-faq' ),
			'update_item' => esc_html__( "Update {$single}", 'minitek-faq' ),
			'add_new_item' => esc_html__( "Add New {$single}", 'minitek-faq' ),
			'new_item_name' => esc_html__( "New {$single}", 'minitek-faq' ),
			'parent_item' => null,
			'parent_item_colon' => null,
			'search_items' => esc_html__( "Search {$plural}", 'minitek-faq' ),
			'popular_items' => esc_html__( "Popular {$plural}", 'minitek-faq' ),
			'separate_items_with_commas' => esc_html__( "Separate {$plural} with commas", 'minitek-faq' ),
			'add_or_remove_items' => esc_html__( "Add or remove {$plural}", 'minitek-faq' ),
			'choose_from_most_used' => esc_html__( "Choose from the most used {$plural}", 'minitek-faq' ),
			'not_found' => esc_html__( "No {$plural} found", 'minitek-faq' ),
			'back_to_items' => esc_html__( "← Back to {$plural}", 'minitek-faq' )
    );

		$opts = array(
			'label' => esc_html__( $single, 'minitek-faq' ),
			'labels' => $labels,
			'public' => TRUE,
			'publicly_queryable' => TRUE,
			'show_ui' => TRUE,
			'show_in_menu' => TRUE,
			'show_in_nav_menus' => TRUE,
			'show_in_rest' => FALSE,
			'show_in_quick_edit' => TRUE,
			'meta_box_cb' => null,
			'show_admin_column' => FALSE,
			'description' => TRUE,
			'hierarchical' => FALSE,
			'query_var' => FALSE,
			'update_count_callback' => '',
			'rewrite' => array(
				'slug' => $slug,
				'with_front' => TRUE,
				'hierarchical' => FALSE,
				'ep_mask' => EP_NONE
			),
			'capabilities' => array(
				'manage_terms' => 'manage_mf_sections',
				'edit_terms' => 'manage_mf_sections',
				'delete_terms' => 'manage_mf_sections',
				'assign_terms' => 'edit_mf_questions'
			)
		);

		register_taxonomy( strtolower( $taxonomy_name ), $works_with, $opts );

    global $wp_rewrite;
    $wp_rewrite->flush_rules();

	}

	/**
	 * Create a new custom taxonomy (mf_topic).
	 *
	 * @since 		1.0.1
	 */
	public static function mf_topic_register_taxonomy() {

		$taxonomy_name = 'mf_topic';
		$works_with = 'mf_question';
		$plural = 'Topics';
		$single = 'Topic';
    $slug = '';

    if (isset($this->options['root-slug']) && $this->options['root-slug'])
    {
      if (isset($this->options['prefix-slugs']) && $this->options['prefix-slugs'] == 'yes')
      {
        $slug .= $this->options['root-slug'].'/';
      }
    }
    if (isset($this->options['prefix-topics']) && $this->options['prefix-topics'])
    {
      $slug .= $this->options['prefix-topics'];
    }
		else
		{
			$slug .= 'mf-topic';
		}
    if ($slug == '')
    {
      $slug .= 'mf-topic';
    }

		$labels = array(
			'name' => esc_html__( "FAQ {$plural}", 'minitek-faq' ),
			'singular_name' => esc_html__( $single, 'minitek-faq' ),
			'menu_name' => esc_html__( $plural, 'minitek-faq' ),
			'all_items' => esc_html__( "All {$plural}", 'minitek-faq' ),
			'edit_item' => esc_html__( "Edit {$single}", 'minitek-faq' ),
			'view_item' => esc_html__( "View {$single}", 'minitek-faq' ),
			'update_item' => esc_html__( "Update {$single}", 'minitek-faq' ),
			'add_new_item' => esc_html__( "Add New {$single}", 'minitek-faq' ),
			'new_item_name' => esc_html__( "New {$single}", 'minitek-faq' ),
			'parent_item' => esc_html__( "Parent {$single}", 'minitek-faq' ),
			'parent_item_colon' => esc_html__( "Parent {$single}:", 'minitek-faq' ),
			'search_items' => esc_html__( "Search {$plural}", 'minitek-faq' ),
			'popular_items' => esc_html__( "Popular {$plural}", 'minitek-faq' ),
			'separate_items_with_commas' => esc_html__( "Separate {$plural} with commas", 'minitek-faq' ),
			'add_or_remove_items' => esc_html__( "Add or remove {$plural}", 'minitek-faq' ),
			'choose_from_most_used' => esc_html__( "Choose from the most used {$plural}", 'minitek-faq' ),
			'not_found' => esc_html__( "No {$plural} found", 'minitek-faq' ),
			'back_to_items' => esc_html__( "← Back to {$plural}", 'minitek-faq' )
    );

		$opts = array(
			'label' => esc_html__( $single, 'minitek-faq' ),
			'labels' => $labels,
			'public' => TRUE,
			'publicly_queryable' => TRUE,
			'show_ui' => TRUE,
			'show_in_menu' => TRUE,
			'show_in_nav_menus' => TRUE,
			'show_in_rest' => FALSE,
			'show_in_quick_edit' => TRUE,
			'meta_box_cb' => null,
			'show_admin_column' => TRUE,
			'description' => TRUE,
			'hierarchical' => TRUE,
			'query_var' => FALSE,
			'update_count_callback' => '',
			'rewrite' => array(
				'slug' => $slug,
				'with_front' => TRUE,
				'hierarchical' => FALSE,
				'ep_mask' => EP_NONE
			),
			'capabilities' => array(
				'manage_terms' => 'manage_mf_topics',
				'edit_terms' => 'manage_mf_topics',
				'delete_terms' => 'manage_mf_topics',
				'assign_terms' => 'edit_mf_questions'
			)
		);

		register_taxonomy( strtolower( $taxonomy_name ), $works_with, $opts );

    global $wp_rewrite;
    $wp_rewrite->flush_rules();

	}

	/**
	 * Create a new custom post type (mf_question).
	 *
	 * @since 		1.0.1
	 */
	public static function mf_question_register_post_type() {

		$cap_type = 'mf_question';
		$global_name = 'Minitek FAQ';
		$plural = 'Questions';
		$single = 'Question';
		$post_name = 'mf_question';
		$slug = '';

    if (isset($this->options['root-slug']) && $this->options['root-slug'])
    {
      if (isset($this->options['prefix-slugs']) && $this->options['prefix-slugs'] == 'yes')
      {
        $slug .= $this->options['root-slug'].'/';
      }
    }
    if (isset($this->options['prefix-questions']) && $this->options['prefix-questions'])
    {
      $slug .= $this->options['prefix-questions'];
    }
		else
		{
			$slug .= 'mf-question';
		}
    if ($slug == '')
    {
      $slug .= 'mf-question';
    }

		$opts['can_export'] = FALSE;
		$opts['capability_type'] = $cap_type;
		$opts['description'] = '';
		$opts['exclude_from_search'] = FALSE;
		$opts['has_archive'] = FALSE;
		$opts['hierarchical'] = FALSE;
		$opts['map_meta_cap'] = TRUE;
		$opts['menu_icon'] = 'dashicons-minitek-icon';
		$opts['menu_position'] = 25;
		$opts['public'] = FALSE;
		$opts['publicly_querable'] = FALSE;
		$opts['query_var'] = FALSE;
		$opts['register_meta_box_cb'] = '';
		$opts['show_in_admin_bar'] = FALSE;
		$opts['show_in_menu'] = TRUE;
		$opts['show_in_nav_menus'] = TRUE;
		$opts['show_ui'] = TRUE;
		$opts['supports'] = array( 'title', 'editor', 'thumbnail' );
		$opts['taxonomies'] = array( 'mf_topic' );

		// Meta capabilities
		$opts['capabilities']['edit_post'] = "edit_{$cap_type}";
		$opts['capabilities']['read_post'] = "read_{$cap_type}";
		$opts['capabilities']['delete_post'] = "delete_{$cap_type}";

		// Primitive capabilities used outside of map_meta_cap():
		$opts['capabilities']['edit_posts'] = "edit_{$cap_type}s";
		$opts['capabilities']['edit_others_posts'] = "edit_others_{$cap_type}s";
		$opts['capabilities']['publish_posts'] = "publish_{$cap_type}s";
		$opts['capabilities']['read_private_posts'] = "read_private_{$cap_type}s";

		// Primitive capabilities used within map_meta_cap():
		$opts['capabilities']['read'] = "read_{$cap_type}s";
		$opts['capabilities']['delete_posts'] = "delete_{$cap_type}s";
		$opts['capabilities']['delete_private_posts']	= "delete_private_{$cap_type}s";
		$opts['capabilities']['delete_published_posts']	= "delete_published_{$cap_type}s";
		$opts['capabilities']['delete_others_posts'] = "delete_others_{$cap_type}s";
		$opts['capabilities']['edit_private_posts'] = "edit_private_{$cap_type}s";
		$opts['capabilities']['edit_published_posts']	= "edit_published_{$cap_type}s";
		$opts['capabilities']['create_posts'] = "create_{$cap_type}s";

		// Labels
		$opts['labels']['add_new'] = esc_html__( "Add New {$single}", 'minitek-faq' );
		$opts['labels']['add_new_item'] = esc_html__( "Add New {$single}", 'minitek-faq' );
		$opts['labels']['all_items'] = esc_html__( $plural, 'minitek-faq' );
		$opts['labels']['edit_item'] = esc_html__( "Edit {$single}" , 'minitek-faq' );
		$opts['labels']['menu_name'] = esc_html__( $global_name, 'minitek-faq' );
		$opts['labels']['name'] = esc_html__( 'FAQ Questions', 'minitek-faq' );
		$opts['labels']['name_admin_bar'] = esc_html__( $single, 'minitek-faq' );
		$opts['labels']['new_item'] = esc_html__( "New {$single}", 'minitek-faq' );
		$opts['labels']['not_found'] = esc_html__( "No {$plural} Found", 'minitek-faq' );
		$opts['labels']['not_found_in_trash'] = esc_html__( "No {$plural} Found in Trash", 'minitek-faq' );
		$opts['labels']['parent_item_colon'] = esc_html__( "Parent {$plural} :", 'minitek-faq' );
		$opts['labels']['search_items'] = esc_html__( "Search {$plural}", 'minitek-faq' );
		$opts['labels']['singular_name'] = esc_html__( $single, 'minitek-faq' );
		$opts['labels']['view_item'] = esc_html__( "View {$single}", 'minitek-faq' );

		// Rewrite
		//$opts['rewrite'] = TRUE;
		$opts['rewrite']['ep_mask'] = EP_PERMALINK;
		$opts['rewrite']['feeds'] = FALSE;
		$opts['rewrite']['pages'] = TRUE;
		$opts['rewrite']['slug'] = $slug;
		$opts['rewrite']['with_front'] = FALSE;

		$opts = apply_filters( 'minitek-faq-mf_question-options', $opts );

		register_post_type( strtolower( $post_name ), $opts );

	}

	/**
	 * Edit mf_section list columns.
	 *
	 * @since 		1.0.1
	 */
	public static function mf_section_columns_headings($theme_columns) {

		$new_columns = array(
			'cb' => '<input type="checkbox" />',
			'name' => __( 'Name', 'minitek-faq' ),
			'slug' => __( 'Slug', 'minitek-faq' ),
			'id' => __( 'ID', 'minitek-faq' )
		);

		return $new_columns;

	}

	/**
	 * Manage mf_topic list columns.
	 *
	 * @since 		1.0.1
	 */
	public static function mf_section_columns_content( $out, $column_name, $section_id ) {

		switch ($column_name) {
			case 'id':
				$out .= $section_id;
				break;

			default:
				break;
		}

		return $out;

	}

	/**
	 * Edit mf_question list columns headings.
	 *
	 * @since 		1.0.1
	 */
	public static function mf_question_columns_headings($theme_columns) {

		$new_columns = array(
			'cb' => '<input type="checkbox" />',
			'title' => __( 'Title', 'minitek-faq' ),
			'taxonomy-mf_topic' => __( 'Topic', 'minitek-faq' ),
			'question_author' => __( 'Author', 'minitek-faq' ),
			'date' => __( 'Date', 'minitek-faq' )
		);

		return $new_columns;

	}

	/**
	 * Edit mf_question list columns content.
	 *
	 * @since 		1.0.1
	 */
	public static function mf_question_columns_content( $column_name, $post_id ) {

		switch ($column_name) {
			case 'question_author':
				$author_id = get_post($post_id)->post_author;
				$author = '';
				if (is_numeric($author_id) && $author_id)
				{
					$author_info = get_userdata($author_id);
					$author_name = $author_info->user_nicename;
					$user = '<a href="edit.php?post_type=mf_question&author='.$author_id.'">'.$author_name.'</a>';
				}
				else
				{
					$user = __( 'Guest', 'minitek-faq' );
				}
				echo $user;
				break;

			default:
				break;
		}

	}

	/**
	 * Creates dropdown filter by custom Taxonomies.
	 *
	 * @since 		1.0.1
	 */
	public static function mf_question_custom_filters() {

		if (!is_admin())
		{
			return;
		}

		$screen = get_current_screen();

		if ( $screen->post_type == 'mf_question' )
		{
			global $wp_query;

			// Filter by clicking on Topic link
			if (isset($wp_query->query['taxonomy']) && $wp_query->query['taxonomy'] == 'mf_topic')
			{
				if (isset($wp_query->query['term']))
				{
					$term_obj = get_term_by( 'slug', $wp_query->query['term'], 'mf_topic' );
					$term = $term_obj->term_id;
				}
			}
			// Filter by dropdown selection
			else if (isset($_GET['mf_topic']))
			{
				$term = $_GET['mf_topic'];
			}
			// No filter
			else
			{
				$term = '';
			}

			wp_dropdown_categories( array(
				'show_option_all' => __( 'All Topics', 'minitek-faq' ),
				'taxonomy' => 'mf_topic',
				'name' => 'mf_topic',
				'orderby' => 'name',
				'selected' => $term,
				'hierarchical' => true,
				'depth' => 5,
				'show_count' => true,
				'hide_empty' => true,
			) );
		}

	}

	/**
	 * Performs Questions filtering by custom Taxonomies.
	 *
	 * @since 		1.0.1
	 */
	public static function mf_question_custom_filtering( $wp_query ) {

		if (!is_admin())
		{
			return;
		}

		global $typenow;

		if ( $typenow == 'mf_question' )
		{
			$qv = &$wp_query->query_vars;

			if (isset($_GET['mf_topic']) && is_numeric($_GET['mf_topic']) && $_GET['mf_topic'] > 0)
			{
				$qv['tax_query'] = array(
					array(
						'taxonomy' => 'mf_topic',
						'field' => 'id',
						'terms' => array($_GET['mf_topic'])
					)
				);
			}

		}

	}

	/**
	 * Edit mf_topic list columns.
	 *
	 * @since 		1.0.1
	 */
	public static function mf_topic_columns_headings( $theme_columns ) {

		$new_columns = array(
			'cb' => '<input type="checkbox" />',
			'name' => __( 'Name', 'minitek-faq' ),
			'section' => __( 'Section', 'minitek-faq' ),
			'slug' => __( 'Slug', 'minitek-faq' ),
			'posts' => __( 'Questions', 'minitek-faq' )
		);

		return $new_columns;

	}

	/**
	 * Manage mf_topic list columns.
	 *
	 * @since 		1.0.1
	 */
	public static function mf_topic_columns_content( $out, $column_name, $topic_id ) {

		switch ($column_name) {
			case 'section':
				$section_id = get_term_meta($topic_id, 'topic-section');
				if ($section_id[0] > 0)
				{
					$section = get_term($section_id[0], 'mf_section');
					$section_name = maybe_unserialize($section->name);
					$out .= '<a href="term.php?taxonomy=mf_section&tag_ID='.$section_id[0].'&post_type=mf_question">'.$section_name.'</a>';
				}
				else
				{
					$out .= '—';
				}
				break;

			default:
				break;
		}

		return $out;

	}

	/**
	 * Make mf_topic Section column sortable.
	 *
	 * @since 		1.0.1
	 */
	public static function mf_topic_sortable_columns( $columns ) {

		$columns['section'] = 'section';

  		return $columns;

	}

	/**
	 * Sort Topics by Section.
	 *
	 * @since 		1.0.1
	 */
	public static function mf_topic_custom_sorting( $term_query ) {

		global $pagenow;

		if (!is_admin())
		{
			return $term_query;
		}

		if (is_admin() && $pagenow == 'edit-tags.php' && $term_query->query_vars['taxonomy'][0] == 'mf_topic' && (isset($_GET['orderby']) && $_GET['orderby'] == 'section'))
		{
			$term_query->query_vars['orderby'] = 'order_clause';
			$term_query->query_vars['order'] = isset($_GET['order']) ? $_GET['order'] : "DESC";
			// the OR relation and the NOT EXISTS clause allow for terms without a meta_value at all
			$args = array('relation' => 'OR',
			  'order_clause' => array(
				'key' => 'topic-section',
				'type' => 'NUMERIC'
			  ),
			  array(
				'key' => 'topic-section',
				'compare' => 'NOT EXISTS'
			  )
			);
			$term_query->meta_query = new WP_Meta_Query( $args );
		}

		return $term_query;

	}

}
