<?php

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      	1.0.1
 * @package    	Minitek-Faq
 * @subpackage 	Minitek-Faq/includes
 */

class MFaq {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    	1.0.1
	 * @access   	protected
	 * @var      	MFaq_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    	1.0.1
	 * @access   	protected
	 * @var      	string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    	1.0.1
	 * @access   	protected
	 * @var      	string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    	1.0.1
	 */
	public function __construct() {

		$this->plugin_name = 'minitek-faq';
		$this->version = '1.0.2';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_metabox_hooks();
		$this->define_taxonomy_fields();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - MFaq_Loader. Orchestrates the hooks of the plugin.
	 * - MFaq_i18n. Defines internationalization functionality.
	 * - MFaq_Admin. Defines all hooks for the admin area.
	 * - MFaq_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    	1.0.1
	 * @access   	private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-minitek-faq-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-minitek-faq-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-minitek-faq-admin.php';

		/**
		 * The class responsible for defining all actions relating to post metaboxes.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-minitek-faq-admin-metaboxes.php';

		/**
		 * The class responsible for defining all actions relating to taxonomies custom fields.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-minitek-faq-admin-custom-fields.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-minitek-faq-public.php';

		/**
		 * The class responsible for sanitizing user input
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-minitek-faq-sanitize.php';

		/**
		 * The class that contains all data source functions
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-minitek-faq-data.php';

		$this->loader = new MFaq_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the MFaq_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    	1.0.1
	 * @access   	private
	 */
	private function set_locale() {

		$plugin_i18n = new MFaq_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    	1.0.1
	 * @access   	private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new MFaq_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'mf_enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'mf_enqueue_scripts' );

		// Sections
		$this->loader->add_action( 'init', $plugin_admin, 'mf_section_register_taxonomy' );

		// Topics
		$this->loader->add_action( 'init', $plugin_admin, 'mf_topic_register_taxonomy' );

		// Questions
		$this->loader->add_action( 'init', $plugin_admin, 'mf_question_register_post_type' );

		// Questions columns
		$this->loader->add_filter( 'manage_mf_question_posts_columns', $plugin_admin, 'mf_question_columns_headings', 10, 3 );
		$this->loader->add_action( 'manage_mf_question_posts_custom_column', $plugin_admin, 'mf_question_columns_content', 10, 3 );

		// Questions filters
		$this->loader->add_action( 'restrict_manage_posts', $plugin_admin, 'mf_question_custom_filters' , 10, 2 );
		$this->loader->add_filter( 'parse_query', $plugin_admin, 'mf_question_custom_filtering' );

		// Add admin menu
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'mf_add_menu' );

		// Sections columns
		$this->loader->add_filter( 'manage_edit-mf_section_columns' , $plugin_admin, 'mf_section_columns_headings' );
		$this->loader->add_filter( 'manage_mf_section_custom_column', $plugin_admin, 'mf_section_columns_content', 10, 3 );

		// Topics columns
		$this->loader->add_filter( 'manage_edit-mf_topic_columns' , $plugin_admin, 'mf_topic_columns_headings' );
		$this->loader->add_filter( 'manage_mf_topic_custom_column', $plugin_admin, 'mf_topic_columns_content', 10, 3 );
		$this->loader->add_filter( 'manage_edit-mf_topic_sortable_columns', $plugin_admin, 'mf_topic_sortable_columns' );
		$this->loader->add_filter( 'pre_get_terms', $plugin_admin, 'mf_topic_custom_sorting', 10, 3);

		// Add settings
		$this->loader->add_action( 'admin_init', $plugin_admin, 'mf_register_settings' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'mf_register_sections' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'mf_register_fields' );

		// Admin messages
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'mf_admin_notice_section_error' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    	1.0.1
	 * @access   	private
	 */
	private function define_public_hooks() {

		$plugin_public = new MFaq_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		// Set frontend template
		$this->loader->add_filter( 'template_include', $plugin_public, 'mf_set_template' );

		// Custom actions for template partials
		$this->loader->add_action( 'mf_topnav', $plugin_public, 'mf_topnav_display' );
		$this->loader->add_action( 'mf_leftnav', $plugin_public, 'mf_leftnav_display' );
		$this->loader->add_action( 'mf_topicquestions', $plugin_public, 'mf_topicquestions_display', 10, 2 );
		$this->loader->add_action( 'mf_section_content', $plugin_public, 'mf_section_content_display' );
		$this->loader->add_action( 'mf_topic_content', $plugin_public, 'mf_topic_content_display' );
		$this->loader->add_action( 'mf_popular_topics', $plugin_public, 'mf_popular_topics_display' );
		$this->loader->add_action( 'mf_popular_questions', $plugin_public, 'mf_popular_questions_display' );
		$this->loader->add_action( 'mf_topics_tree', $plugin_public, 'mf_topics_tree_display' );

		// Handle ajax requests (wp_ajax_*action*)
		// Load section
		$this->loader->add_action( 'wp_ajax_mf_section_content_display', $plugin_public, 'mf_section_content_display' );
		$this->loader->add_action( 'wp_ajax_nopriv_mf_section_content_display', $plugin_public, 'mf_section_content_display' );

		// Load topic
		$this->loader->add_action( 'wp_ajax_mf_topic_content_display', $plugin_public, 'mf_topic_content_display' );
		$this->loader->add_action( 'wp_ajax_nopriv_mf_topic_content_display', $plugin_public, 'mf_topic_content_display' );
		$this->loader->add_action( 'wp_ajax_mf_topic_load_more', $plugin_public, 'mf_topic_load_more' );
		$this->loader->add_action( 'wp_ajax_nopriv_mf_topic_load_more', $plugin_public, 'mf_topic_load_more' );

	}

	/**
	 * Register all of the hooks related to metaboxes.
	 *
	 * @since 		1.0.1
	 * @access 		private
	 */
	private function define_metabox_hooks() {

		$plugin_metaboxes = new MFaq_Admin_Metaboxes( $this->get_plugin_name(), $this->get_version() );

		// Remove 'Section' meta box from Question
		$this->loader->add_action( 'load-post.php', $plugin_metaboxes, 'mf_question_update_meta_boxes' );
		$this->loader->add_action( 'load-post-new.php', $plugin_metaboxes, 'mf_question_update_meta_boxes' );

	}

	/**
	 * Register all of the hooks related to taxonomy custom fields.
	 *
	 * @since 		1.0.1
	 * @access 		private
	 */
	private function define_taxonomy_fields() {

		$plugin_custom_fields = new MFaq_Admin_Custom_Fields( $this->get_plugin_name(), $this->get_version() );

		// Add Section custom fields
		$this->loader->add_action( 'mf_section_edit_form_fields', $plugin_custom_fields, 'mf_section_edit_form_fields' );

		// Save Section custom fields
		$this->loader->add_action( 'edited_mf_section', $plugin_custom_fields, 'save_section_form_custom_fields', 10, 2 );

		// Add Topic custom fields
		$this->loader->add_action( 'mf_topic_edit_form_fields', $plugin_custom_fields, 'mf_topic_edit_form_fields' );
		$this->loader->add_action( 'mf_topic_add_form_fields', $plugin_custom_fields, 'mf_topic_add_form_fields' );

		// Save Topic custom fields
		$this->loader->add_action( 'create_mf_topic', $plugin_custom_fields, 'save_topic_form_custom_fields', 10, 2 );
		$this->loader->add_action( 'edited_mf_topic', $plugin_custom_fields, 'save_topic_form_custom_fields', 10, 2 );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    	1.0.1
	 */
	public function run() {

		$this->loader->run();

	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.1
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {

		return $this->plugin_name;

	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.1
	 * @return    MFaq_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {

		return $this->loader;

	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.1
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {

		return $this->version;

	}

}
