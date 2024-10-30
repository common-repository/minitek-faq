<?php
/**
 * The metabox-specific functionality of the plugin.
 *
 * @since      	1.0.1
 * @package 		Minitek-Faq
 * @subpackage 	Minitek-Faq/admin
 */

class MFaq_Admin_Metaboxes {

	/**
	 * The ID of this plugin.
	 *
	 * @since 		1.0.1
	 * @access 		private
	 * @var 			string 			$plugin_name 		The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since 		1.0.1
	 * @access 		private
	 * @var 			string 			$version 			The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 		1.0.1
	 * @param 		string 			$plugin_name 		The name of this plugin.
	 * @param 		string 			$version 			The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_question_admin_scripts' ) );

	}

	/**
	 * Includes the JavaScript necessary to control the selection of parent topic for a question.
	 *
	 * @since    	1.0.1
	 */
	public function enqueue_question_admin_scripts() {

		if ( 'mf_question' === get_current_screen()->id ) {

	 		wp_add_inline_script( 'jquery-migrate', "jQuery(document).ready(function(){

				// Remove 'Add New Topic'
				jQuery('#mf_topic-adder').remove();

				// Select only 1 Topic checkbox
				jQuery('#mf_topic-all input[type=\"checkbox\"]').click(function(){
					  jQuery('#mf_topic-all input[type=\"checkbox\"]').not(this).prop('checked', false);
						jQuery('#mf_topic-pop input[type=\"checkbox\"]').prop('checked', false);
						if (jQuery(this).prop('checked') == true)
						{
					  	jQuery(this).prop('checked', true);
						}
						else
						{
							jQuery(this).prop('checked', false);
						}
				});

				jQuery('#mf_topic-pop input[type=\"checkbox\"]').click(function(){
					  jQuery('#mf_topic-pop input[type=\"checkbox\"]').not(this).prop('checked', false);
						jQuery('#mf_topic-all input[type=\"checkbox\"]').prop('checked', false);
						if (jQuery(this).prop('checked') == true)
						{
					  	jQuery(this).prop('checked', true);
						}
						else
						{
							jQuery(this).prop('checked', false);
						}
				});

			});" );

		}

	}

	/**
	 * Remove Section field from mf_question.
	 *
	 * @since 		1.0.1
	 */
	public function mf_question_update_meta_boxes() {

		remove_meta_box( 'tagsdiv-mf_section', 'mf_question', 'side' );

	}

}
