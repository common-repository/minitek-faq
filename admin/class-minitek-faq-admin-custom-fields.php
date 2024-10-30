<?php
/**
 * The custom-fields-specific functionality of the plugin.
 *
 * @since      	1.0.1
 * @package 		Minitek-Faq
 * @subpackage	Minitek-Faq/admin
 */

class MFaq_Admin_Custom_Fields {

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

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

	}

	/**
	 * Includes the necessary JavaScript in taxonomies.
	 *
	 * @since    	1.0.1
	 */
	public function enqueue_admin_scripts() {

		// Topic form
		if ( 'edit-mf_topic' === get_current_screen()->id ) {

			// Dynamic selection of a Section when selecting a parent Topic
			wp_add_inline_script( 'jquery-migrate', "jQuery(document).ready(function(){
				jQuery('select[name=\"parent\"]').change(function(){
					var topicId = jQuery(this).val();
					if (topicId == '-1') {
						jQuery('select#topic-section option').removeAttr('disabled');
						jQuery('select#topic-section option').removeAttr('hidden');
						jQuery('select#topic-section option:eq(1)').attr('selected', 'selected');

						jQuery('select#topic-section #inherit_option').attr('disabled', '');
						jQuery('select#topic-section #inherit_option').attr('hidden', '');
						jQuery('select#topic-section #inherit_option').removeAttr('selected');
					}
					else
					{
						jQuery('select#topic-section option').attr('disabled', '');
						jQuery('select#topic-section option').attr('hidden', '');
						jQuery('select#topic-section option').removeAttr('selected');

						jQuery('select#topic-section #inherit_option').removeAttr('disabled');
						jQuery('select#topic-section #inherit_option').removeAttr('hidden');
						jQuery('select#topic-section #inherit_option').attr('selected', 'selected');
					}
				});
			});" );

		}

	}

	/**
	 * Adds custom fields to the Topic taxonomy Add form.
	 *
	 * @since    	1.0.1
	 */
	public function mf_topic_add_form_fields ($term) {

		wp_nonce_field( $this->plugin_name, 'topic_options' );

		// Section
		$atts = array();
		$atts['description'] = 'Section is inherited from top-level parent topic.';
		$atts['id'] = 'topic-section';
		$atts['label'] = 'Section';
		$atts['name'] = 'topic-section';
		$atts['type'] = 'select';
		$atts['value'] = array();

		include( plugin_dir_path( __FILE__ ) . 'partials/'.$this->plugin_name . '-admin-field-sections.php' );

  }

	/**
	 * Adds custom fields to the Topic taxonomy Edit form.
	 *
	 * @since    	1.0.1
	 */
	public function mf_topic_edit_form_fields ($term) {

		wp_nonce_field( $this->plugin_name, 'topic_options' );

		// Section
		$atts = array();
		$atts['description'] = 'Section is inherited from top-level parent topic.';
		$atts['id'] = 'topic-section';
		$atts['label'] = 'Section';
		$atts['name'] = 'topic-section';
		$atts['type'] = 'select';
		$atts['value'] = array();

		if ( ! empty( get_term_meta($term->term_id, $atts['id'], true) ) ) {
			$atts['value'] = get_term_meta($term->term_id, $atts['id'], true);
		}

		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );

		?><tr class="form-field term-topic-section-wrap"><?php
		include( plugin_dir_path( __FILE__ ) . 'partials/'.$this->plugin_name . '-admin-table-field-sections.php' );
		?></tr><?php

		// Content
		$atts = array();
		$atts['description'] = '';
		$atts['id'] = 'topic-content';
		$atts['label'] = 'Content';
		$atts['name'] = 'topic-content';
		$atts['placeholder'] = '';
		$atts['type'] = 'editor';
		$atts['value'] = '';

		if ( ! empty( get_term_meta($term->term_id, $atts['id'], true) ) ) {
			$atts['value'] = get_term_meta($term->term_id, $atts['id'], true);
		}

		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );

		?><tr class="form-field term-topic-content-wrap"><?php
		include( plugin_dir_path( __FILE__ ) . 'partials/'.$this->plugin_name . '-admin-table-field-editor.php' );
		?></tr><?php

		// Topic icon class
		$atts = array();
		$atts['description'] = 'A Dashicons icon class.';
		$atts['id'] = 'topic-icon-class';
		$atts['label'] = 'Topic icon class';
		$atts['name'] = 'topic-icon-class';
		$atts['placeholder'] = '';
		$atts['type'] = 'text';
		$atts['value'] = 'category';

		if ( ! empty( get_term_meta($term->term_id, $atts['id'], true) ) ) {
			$atts['value'] = get_term_meta($term->term_id, $atts['id'], true);
		}

		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );

		?><tr class="form-field term-topic-icon-class-wrap"><?php
		include( plugin_dir_path( __FILE__ ) . 'partials/'.$this->plugin_name . '-admin-table-field-text.php' );
		?></tr><?php

		// Topic image
		$atts = array();
		$atts['description'] = '';
		$atts['id'] = 'topic-image';
		$atts['label'] = 'Topic image';
		$atts['name'] = 'topic-image';
		$atts['placeholder'] = '';
		$atts['type'] = 'text';
		$atts['value'] = '-1';

		if ( ! empty( get_term_meta($term->term_id, $atts['id'], true) ) ) {
			$atts['value'] = get_term_meta($term->term_id, $atts['id'], true);
		}

		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );

		?><tr class="form-field term-topic-image-wrap"><?php
		include( plugin_dir_path( __FILE__ ) . 'partials/'.$this->plugin_name . '-admin-table-field-image.php' );
		?></tr><?php

		// Show topic title
		$atts = array();
		$atts['description'] = '';
		$atts['id'] = 'topic-title';
		$atts['label'] = 'Show topic title';
		$atts['name'] = 'topic-title';
		$atts['type'] = 'select';
		$atts['value'] = 'global';
		$atts['selections'] = array(
			array(
			  'value' => 'global',
			  'label' => 'Use global'
			),
			array(
			  'value' => 'yes',
			  'label' => 'Yes'
			),
			array(
			  'value' => 'no',
			  'label' => 'No'
			)
		);

		if ( ! empty( get_term_meta($term->term_id, $atts['id'], true) ) ) {
			$atts['value'] = get_term_meta($term->term_id, $atts['id'], true);
		}

		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );

		?><tr class="form-field term-topic-title-wrap"><?php
		include( plugin_dir_path( __FILE__ ) . 'partials/'.$this->plugin_name . '-admin-table-field-select.php' );
		?></tr><?php

		// Show topic description
		$atts = array();
		$atts['description'] = '';
		$atts['id'] = 'topic-description';
		$atts['label'] = 'Show topic description';
		$atts['name'] = 'topic-description';
		$atts['type'] = 'select';
		$atts['value'] = 'global';
		$atts['selections'] = array(
			array(
			  'value' => 'global',
			  'label' => 'Use global'
			),
			array(
			  'value' => 'yes',
			  'label' => 'Yes'
			),
			array(
			  'value' => 'no',
			  'label' => 'No'
			)
		);

		if ( ! empty( get_term_meta($term->term_id, $atts['id'], true) ) ) {
			$atts['value'] = get_term_meta($term->term_id, $atts['id'], true);
		}

		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );

		?><tr class="form-field term-topic-description-wrap"><?php
		include( plugin_dir_path( __FILE__ ) . 'partials/'.$this->plugin_name . '-admin-table-field-select.php' );
		?></tr><?php

		// Show topic image
		$atts = array();
		$atts['description'] = '';
		$atts['id'] = 'topic-show-image';
		$atts['label'] = 'Show topic image';
		$atts['name'] = 'topic-show-image';
		$atts['type'] = 'select';
		$atts['value'] = 'global';
		$atts['selections'] = array(
			array(
			  'value' => 'global',
			  'label' => 'Use global'
			),
			array(
			  'value' => 'yes',
			  'label' => 'Yes'
			),
			array(
			  'value' => 'no',
			  'label' => 'No'
			)
		);

		if ( ! empty( get_term_meta($term->term_id, $atts['id'], true) ) ) {
			$atts['value'] = get_term_meta($term->term_id, $atts['id'], true);
		}

		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );

		?><tr class="form-field term-topic-show-image-wrap"><?php
		include( plugin_dir_path( __FILE__ ) . 'partials/'.$this->plugin_name . '-admin-table-field-select.php' );
		?></tr><?php

		// Show topic content
		$atts = array();
		$atts['description'] = '';
		$atts['id'] = 'topic-show-content';
		$atts['label'] = 'Show topic content';
		$atts['name'] = 'topic-show-content';
		$atts['type'] = 'select';
		$atts['value'] = 'global';
		$atts['selections'] = array(
			array(
			  'value' => 'global',
			  'label' => 'Use global'
			),
			array(
			  'value' => 'yes',
			  'label' => 'Yes'
			),
			array(
			  'value' => 'no',
			  'label' => 'No'
			)
		);

		if ( ! empty( get_term_meta($term->term_id, $atts['id'], true) ) ) {
			$atts['value'] = get_term_meta($term->term_id, $atts['id'], true);
		}

		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );

		?><tr class="form-field term-topic-show-content-wrap"><?php
		include( plugin_dir_path( __FILE__ ) . 'partials/'.$this->plugin_name . '-admin-table-field-select.php' );
		?></tr><?php

		// Show questions opened
		$atts = array();
		$atts['description'] 	= '';
		$atts['id'] = 'topic-questions-opened';
		$atts['label'] = 'Show questions opened';
		$atts['name'] = 'topic-questions-opened';
		$atts['type'] = 'select';
		$atts['value'] = 'inherit';
		$atts['selections'] = array(
			array(
			  'value' => 'global',
			  'label' => 'Use global'
			),
			array(
			  'value' => 'yes',
			  'label' => 'Yes'
			),
			array(
			  'value' => 'no',
			  'label' => 'No'
			)
		);

		if ( ! empty( get_term_meta($term->term_id, $atts['id'], true) ) ) {
			$atts['value'] = get_term_meta($term->term_id, $atts['id'], true);
		}

		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );

		?><tr class="form-field term-topic-questions-opened-wrap"><?php
		include( plugin_dir_path( __FILE__ ) . 'partials/'.$this->plugin_name . '-admin-table-field-select.php' );
		?></tr><?php

		// Show subtopics
		$atts = array();
		$atts['description'] = '';
		$atts['id'] = 'topic-subtopics';
		$atts['label'] = 'Show subtopics';
		$atts['name'] = 'topic-subtopics';
		$atts['type'] = 'select';
		$atts['value'] = 'global';
		$atts['selections'] = array(
			array(
			  'value' => 'global',
			  'label' => 'Use global'
			),
			array(
			  'value' => 'yes',
			  'label' => 'Yes'
			),
			array(
			  'value' => 'no',
			  'label' => 'No'
			)
		);

		if ( ! empty( get_term_meta($term->term_id, $atts['id'], true) ) ) {
			$atts['value'] = get_term_meta($term->term_id, $atts['id'], true);
		}

		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );

		?><tr class="form-field term-topic-subtopics-wrap"><?php
		include( plugin_dir_path( __FILE__ ) . 'partials/'.$this->plugin_name . '-admin-table-field-select.php' );
		?></tr><?php

		// Show subtopics titles
		$atts = array();
		$atts['description'] = '';
		$atts['id'] = 'topic-subtopics-titles';
		$atts['label'] = 'Show subtopics titles';
		$atts['name'] = 'topic-subtopics-titles';
		$atts['type'] = 'select';
		$atts['value'] = 'global';
		$atts['selections'] = array(
			array(
			  'value' => 'global',
			  'label' => 'Use global'
			),
			array(
			  'value' => 'yes',
			  'label' => 'Yes'
			),
			array(
			  'value' => 'no',
			  'label' => 'No'
			)
		);

		if ( ! empty( get_term_meta($term->term_id, $atts['id'], true) ) ) {
			$atts['value'] = get_term_meta($term->term_id, $atts['id'], true);
		}

		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );

		?><tr class="form-field term-topic-subtopics-titles-wrap"><?php
		include( plugin_dir_path( __FILE__ ) . 'partials/'.$this->plugin_name . '-admin-table-field-select.php' );
		?></tr><?php

		// Show subtopics descriptions
		$atts = array();
		$atts['description'] = '';
		$atts['id'] = 'topic-subtopics-descriptions';
		$atts['label'] = 'Show subtopics descriptions';
		$atts['name'] = 'topic-subtopics-descriptions';
		$atts['type'] = 'select';
		$atts['value'] = 'global';
		$atts['selections'] = array(
			array(
			  'value' => 'global',
			  'label' => 'Use global'
			),
			array(
			  'value' => 'yes',
			  'label' => 'Yes'
			),
			array(
			  'value' => 'no',
			  'label' => 'No'
			)
		);

		if ( ! empty( get_term_meta($term->term_id, $atts['id'], true) ) ) {
			$atts['value'] = get_term_meta($term->term_id, $atts['id'], true);
		}

		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );

		?><tr class="form-field term-topic-subtopics-descriptions-wrap"><?php
		include( plugin_dir_path( __FILE__ ) . 'partials/'.$this->plugin_name . '-admin-table-field-select.php' );
		?></tr><?php

		// Show subtopics images
		$atts = array();
		$atts['description'] = '';
		$atts['id'] = 'topic-subtopics-images';
		$atts['label'] = 'Show subtopics images';
		$atts['name'] = 'topic-subtopics-images';
		$atts['type'] = 'select';
		$atts['value'] = 'global';
		$atts['selections'] = array(
			array(
			  'value' => 'global',
			  'label' => 'Use global'
			),
			array(
			  'value' => 'yes',
			  'label' => 'Yes'
			),
			array(
			  'value' => 'no',
			  'label' => 'No'
			)
		);

		if ( ! empty( get_term_meta($term->term_id, $atts['id'], true) ) ) {
			$atts['value'] = get_term_meta($term->term_id, $atts['id'], true);
		}

		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );

		?><tr class="form-field term-topic-subtopics-images-wrap"><?php
		include( plugin_dir_path( __FILE__ ) . 'partials/'.$this->plugin_name . '-admin-table-field-select.php' );
		?></tr><?php

		// Show subtopics content
		$atts = array();
		$atts['description'] = '';
		$atts['id'] = 'topic-subtopics-content';
		$atts['label'] = 'Show subtopics content';
		$atts['name'] = 'topic-subtopics-content';
		$atts['type'] = 'select';
		$atts['value'] = 'global';
		$atts['selections'] = array(
			array(
			  'value' => 'global',
			  'label' => 'Use global'
			),
			array(
			  'value' => 'yes',
			  'label' => 'Yes'
			),
			array(
			  'value' => 'no',
			  'label' => 'No'
			)
		);

		if ( ! empty( get_term_meta($term->term_id, $atts['id'], true) ) ) {
			$atts['value'] = get_term_meta($term->term_id, $atts['id'], true);
		}

		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );

		?><tr class="form-field term-topic-subtopics-content-wrap"><?php
		include( plugin_dir_path( __FILE__ ) . 'partials/'.$this->plugin_name . '-admin-table-field-select.php' );
		?></tr><?php

		// Show subtopics questions
		$atts = array();
		$atts['description'] = '';
		$atts['id'] = 'topic-subtopics-questions';
		$atts['label'] = 'Show subtopics questions';
		$atts['name'] = 'topic-subtopics-questions';
		$atts['type'] = 'select';
		$atts['value'] = 'global';
		$atts['selections'] = array(
			array(
			  'value' => 'global',
			  'label' => 'Use global'
			),
			array(
			  'value' => 'yes',
			  'label' => 'Yes'
			),
			array(
			  'value' => 'no',
			  'label' => 'No'
			)
		);

		if ( ! empty( get_term_meta($term->term_id, $atts['id'], true) ) ) {
			$atts['value'] = get_term_meta($term->term_id, $atts['id'], true);
		}

		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );

		?><tr class="form-field term-topic-subtopics-questions-wrap"><?php
		include( plugin_dir_path( __FILE__ ) . 'partials/'.$this->plugin_name . '-admin-table-field-select.php' );
		?></tr><?php

  }

	/**
	 * Returns an array of the all the metabox fields and their respective types - Topics
	 *
	 * @since 		1.0.1
	 * @access 		public
	 * @return 		array 		Metabox fields and types
	 */
	private function mf_get_topic_fields() {

		$fields = array();

		$fields[] = array( 'topic-section', 'select' );
		$fields[] = array( 'topic-content', 'editor' );
		$fields[] = array( 'topic-icon-class', 'text' );
		$fields[] = array( 'topic-image', 'text' );
		$fields[] = array( 'topic-title', 'select' );
		$fields[] = array( 'topic-description', 'select' );
		$fields[] = array( 'topic-show-image', 'select' );
		$fields[] = array( 'topic-show-content', 'select' );
		$fields[] = array( 'topic-questions-opened', 'select' );
		$fields[] = array( 'topic-subtopics', 'select' );
		$fields[] = array( 'topic-subtopics-titles', 'select' );
		$fields[] = array( 'topic-subtopics-descriptions', 'select' );
		$fields[] = array( 'topic-subtopics-images', 'select' );
		$fields[] = array( 'topic-subtopics-content', 'select' );
		$fields[] = array( 'topic-subtopics-questions', 'select' );

		return $fields;

	}

	/**
	 * Saves Topic taxonomy custom fields.
	 *
	 * @since    	1.0.1
	 */
	public function save_topic_form_custom_fields ($term_id) {

		if ( ! current_user_can( 'manage_mf_topics' ) ) { return $term_id; }

		$nonce_check = $this->check_nonces( $_POST, $term_type = 'topic' );

		if ( 0 < $nonce_check ) { return $term_id; }

		$metas = $this->mf_get_topic_fields();

		foreach ( $metas as $meta ) {

			$name = $this->sanitizer( 'text', $meta[0] );
			$type = $meta[1];

			if (is_array($_POST[$name])) {
				$new_value = $this->sanitizer( 'array', $_POST[$name] );
			} else {
				$new_value = $this->sanitizer( $type, $_POST[$name] );
			}

			update_term_meta( $term_id, $name, $new_value );

		} // foreach

  }

	/**
	 * Adds custom fields to the Section taxonomy Edit form.
	 *
	 * @since    	1.0.1
	 */
	public function mf_section_edit_form_fields ($term) {

		wp_nonce_field( $this->plugin_name, 'section_options' );

		// Section image
		$atts = array();
		$atts['description'] = 'The section image can be displayed in the "Sections" page.';
		$atts['id'] = 'section-image';
		$atts['label'] = 'Section image';
		$atts['name'] = 'section-image';
		$atts['placeholder'] = '';
		$atts['type'] = 'text';
		$atts['value'] = '-1';

		if ( ! empty( get_term_meta($term->term_id, $atts['id'], true) ) ) {
			$atts['value'] = get_term_meta($term->term_id, $atts['id'], true);
		}

		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );

		?><tr class="form-field term-section-image-wrap"><?php
		include( plugin_dir_path( __FILE__ ) . 'partials/'.$this->plugin_name . '-admin-table-field-image.php' );
		?></tr><?php

		// Separator: General
		$atts = array();
		$atts['label'] = 'General';
		$atts['class'] = 'separator';
		$atts['type'] = 'separator';

		?><tr class="form-field term-separator-wrap"><?php
		include( plugin_dir_path( __FILE__ ) . 'partials/'.$this->plugin_name . '-admin-table-field-separator.php' );
		?></tr><?php

		// Top navigation
		$atts = array();
		$atts['description'] = '';
		$atts['id'] = 'section-top-nav';
		$atts['label'] = 'Top navigation';
		$atts['name'] = 'section-top-nav';
		$atts['type'] = 'radio';
		$atts['value'] = 'yes';
		$atts['selections'] = array(
			array(
			  'value' => 'yes',
			  'label' => 'Yes'
			),
			array(
			  'value' => 'no',
			  'label' => 'No'
			)
		);

		if ( ! empty( get_term_meta($term->term_id, $atts['id'], true) ) ) {
			$atts['value'] = get_term_meta($term->term_id, $atts['id'], true);
		}

		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );

		?><tr class="form-field term-section-top-nav-wrap"><?php
		include( plugin_dir_path( __FILE__ ) . 'partials/'.$this->plugin_name . '-admin-table-field-radio.php' );
		?></tr><?php

		// Top navigation root
		$atts = array();
		$atts['description'] = 'The page that corresponds to the "Home" link in the top navigation. It is recommended to create a "Sections" page. See the documentation for more details.';
		$atts['id'] = 'section-top-nav-root';
		$atts['label'] = 'Top navigation root';
		$atts['name'] = 'section-top-nav-root';
		$atts['placeholder'] = '';
		$atts['type'] = 'select';
		$atts['value'] = '';

		if ( ! empty( get_term_meta($term->term_id, $atts['id'], true) ) ) {
			$atts['value'] = get_term_meta($term->term_id, $atts['id'], true);
		}

		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );

		?><tr class="form-field term-section-top-nav-root-wrap"><?php
		include( plugin_dir_path( __FILE__ ) . 'partials/'.$this->plugin_name . '-admin-table-field-pages.php' );
		?></tr><?php

		// Section description
		$atts = array();
		$atts['description'] = '';
		$atts['id'] = 'section-desc';
		$atts['label'] = 'Show section description';
		$atts['name'] = 'section-desc';
		$atts['type'] = 'radio';
		$atts['value'] = 'yes';
		$atts['selections'] = array(
			array(
			  'value' => 'yes',
			  'label' => 'Yes'
			),
			array(
			  'value' => 'no',
			  'label' => 'No'
			)
		);

		if ( ! empty( get_term_meta($term->term_id, $atts['id'], true) ) ) {
			$atts['value'] = get_term_meta($term->term_id, $atts['id'], true);
		}

		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );

		?><tr class="form-field term-section-desc-wrap"><?php
		include( plugin_dir_path( __FILE__ ) . 'partials/'.$this->plugin_name . '-admin-table-field-radio.php' );
		?></tr><?php

		// Left navigation
		$atts = array();
		$atts['description'] = '';
		$atts['id'] = 'section-left-nav';
		$atts['label'] = 'Left navigation';
		$atts['name'] = 'section-left-nav';
		$atts['type'] = 'radio';
		$atts['value'] = 'yes';
		$atts['selections'] = array(
			array(
			  'value' => 'yes',
			  'label' => 'Yes'
			),
			array(
			  'value' => 'no',
			  'label' => 'No'
			)
		);

		if ( ! empty( get_term_meta($term->term_id, $atts['id'], true) ) ) {
			$atts['value'] = get_term_meta($term->term_id, $atts['id'], true);
		}

		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );

		?><tr class="form-field term-section-left-nav-wrap"><?php
		include( plugin_dir_path( __FILE__ ) . 'partials/'.$this->plugin_name . '-admin-table-field-radio.php' );
		?></tr><?php

		// Load topics
		$atts = array();
		$atts['description'] = '';
		$atts['id'] = 'section-load-topics';
		$atts['label'] = 'Load topics';
		$atts['name'] = 'section-load-topics';
		$atts['type'] = 'radio';
		$atts['value'] = 'all';
		$atts['selections'] = array(
			array(
			  'value' => 'all',
			  'label' => 'All topics'
			),
			array(
			  'value' => 'end',
			  'label' => 'Endpoint topics'
			)
		);

		if ( ! empty( get_term_meta($term->term_id, $atts['id'], true) ) ) {
			$atts['value'] = get_term_meta($term->term_id, $atts['id'], true);
		}

		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );

		?><tr class="form-field term-section-load-topics-wrap"><?php
		include( plugin_dir_path( __FILE__ ) . 'partials/'.$this->plugin_name . '-admin-table-field-radio.php' );
		?></tr><?php

		// Separator: Active Topic
		$atts = array();
		$atts['label'] = 'Active Topic';
		$atts['class'] = 'separator';
		$atts['type'] = 'separator';

		?><tr class="form-field term-separator-wrap"><?php
		include( plugin_dir_path( __FILE__ ) . 'partials/'.$this->plugin_name . '-admin-table-field-separator.php' );
		?></tr><?php

		// Show active topic
		$atts = array();
		$atts['description'] = 'Display the content and questions of a specific topic in the section page.';
		$atts['id'] = 'section-show-active-topic';
		$atts['label'] = 'Show active topic';
		$atts['name'] = 'section-show-active-topic';
		$atts['type'] = 'radio';
		$atts['value'] = 'no';
		$atts['selections'] = array(
			array(
			  'value' => 'yes',
			  'label' => 'Yes'
			),
			array(
			  'value' => 'no',
			  'label' => 'No'
			)
		);

		if ( ! empty( get_term_meta($term->term_id, $atts['id'], true) ) ) {
			$atts['value'] = get_term_meta($term->term_id, $atts['id'], true);
		}

		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );

		?><tr class="form-field term-section-show-active-topic-wrap"><?php
		include( plugin_dir_path( __FILE__ ) . 'partials/'.$this->plugin_name . '-admin-table-field-radio.php' );
		?></tr><?php

		// Active topic
		$atts = array();
		$atts['description'] = '';
		$atts['id'] = 'section-active-topic';
		$atts['label'] = 'Active topic';
		$atts['name'] = 'section-active-topic';
		$atts['placeholder'] = '';
		$atts['type'] = 'select';
		$atts['value'] = '';

		if ( ! empty( get_term_meta($term->term_id, $atts['id'], true) ) ) {
			$atts['value'] = get_term_meta($term->term_id, $atts['id'], true);
		}

		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );

		?><tr class="form-field term-section-active-topic-wrap"><?php
		include( plugin_dir_path( __FILE__ ) . 'partials/'.$this->plugin_name . '-admin-table-field-topics.php' );
		?></tr><?php

		// Separator: Popular Topics
		$atts = array();
		$atts['label'] = 'Popular Topics';
		$atts['class'] = 'separator';
		$atts['type'] = 'separator';

		?><tr class="form-field term-separator-wrap"><?php
		include( plugin_dir_path( __FILE__ ) . 'partials/'.$this->plugin_name . '-admin-table-field-separator.php' );
		?></tr><?php

		// Show popular topics
		$atts = array();
		$atts['description'] = '';
		$atts['id'] = 'section-popular-topics';
		$atts['label'] = 'Popular topics';
		$atts['name'] = 'section-popular-topics';
		$atts['type'] = 'radio';
		$atts['value'] = 'no';
		$atts['selections'] = array(
			array(
			  'value' => 'yes',
			  'label' => 'Yes'
			),
			array(
			  'value' => 'no',
			  'label' => 'No'
			)
		);

		if ( ! empty( get_term_meta($term->term_id, $atts['id'], true) ) ) {
			$atts['value'] = get_term_meta($term->term_id, $atts['id'], true);
		}

		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );

		?><tr class="form-field term-section-popular-topics-wrap"><?php
		include( plugin_dir_path( __FILE__ ) . 'partials/'.$this->plugin_name . '-admin-table-field-radio.php' );
		?></tr><?php

		// Popular topics header
		$atts = array();
		$atts['description'] = '';
		$atts['id'] = 'section-popular-topics-header';
		$atts['label'] = 'Show header';
		$atts['name'] = 'section-popular-topics-header';
		$atts['type'] = 'radio';
		$atts['value'] = 'yes';
		$atts['selections'] = array(
			array(
			  'value' => 'yes',
			  'label' => 'Yes'
			),
			array(
			  'value' => 'no',
			  'label' => 'No'
			)
		);

		if ( ! empty( get_term_meta($term->term_id, $atts['id'], true) ) ) {
			$atts['value'] = get_term_meta($term->term_id, $atts['id'], true);
		}

		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );

		?><tr class="form-field term-section-popular-topics-header-wrap"><?php
		include( plugin_dir_path( __FILE__ ) . 'partials/'.$this->plugin_name . '-admin-table-field-radio.php' );
		?></tr><?php

		// Popular topics count
		$atts = array();
		$atts['description'] = '';
		$atts['id'] = 'section-popular-topics-count';
		$atts['label'] = 'Count';
		$atts['name'] = 'section-popular-topics-count';
		$atts['placeholder'] = '';
		$atts['type'] = 'text';
		$atts['value'] = '6';

		if ( ! empty( get_term_meta($term->term_id, $atts['id'], true) ) ) {
			$atts['value'] = get_term_meta($term->term_id, $atts['id'], true);
		}

		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );

		?><tr class="form-field term-section-popular-topics-count-wrap"><?php
		include( plugin_dir_path( __FILE__ ) . 'partials/'.$this->plugin_name . '-admin-table-field-text.php' );
		?></tr><?php

		// Popular topics columns
		$atts = array();
		$atts['description'] = '';
		$atts['id'] = 'section-popular-topics-columns';
		$atts['label'] = 'Columns';
		$atts['name'] = 'section-popular-topics-columns';
		$atts['type'] = 'select';
		$atts['value'] = '3';
		$atts['selections'] = array(
			array(
			  'value' => '1',
			  'label' => '1'
			),
			array(
			  'value' => '2',
			  'label' => '2'
			),
			array(
			  'value' => '3',
			  'label' => '3'
			)
			,
			array(
			  'value' => '4',
			  'label' => '4'
			)
		);

		if ( ! empty( get_term_meta($term->term_id, $atts['id'], true) ) ) {
			$atts['value'] = get_term_meta($term->term_id, $atts['id'], true);
		}

		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );

		?><tr class="form-field term-section-popular-topics-columns-wrap"><?php
		include( plugin_dir_path( __FILE__ ) . 'partials/'.$this->plugin_name . '-admin-table-field-select.php' );
		?></tr><?php

		// Popular topics title
		$atts = array();
		$atts['description'] = '';
		$atts['id'] = 'section-popular-topics-title';
		$atts['label'] = 'Show title';
		$atts['name'] = 'section-popular-topics-title';
		$atts['type'] = 'radio';
		$atts['value'] = 'yes';
		$atts['selections'] = array(
			array(
			  'value' => 'yes',
			  'label' => 'Yes'
			),
			array(
			  'value' => 'no',
			  'label' => 'No'
			)
		);

		if ( ! empty( get_term_meta($term->term_id, $atts['id'], true) ) ) {
			$atts['value'] = get_term_meta($term->term_id, $atts['id'], true);
		}

		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );

		?><tr class="form-field term-section-popular-topics-title-wrap"><?php
		include( plugin_dir_path( __FILE__ ) . 'partials/'.$this->plugin_name . '-admin-table-field-radio.php' );
		?></tr><?php

		// Popular topics description
		$atts = array();
		$atts['description'] = '';
		$atts['id'] = 'section-popular-topics-desc';
		$atts['label'] = 'Show description';
		$atts['name'] = 'section-popular-topics-desc';
		$atts['type'] = 'radio';
		$atts['value'] = 'yes';
		$atts['selections'] = array(
			array(
			  'value' => 'yes',
			  'label' => 'Yes'
			),
			array(
			  'value' => 'no',
			  'label' => 'No'
			)
		);

		if ( ! empty( get_term_meta($term->term_id, $atts['id'], true) ) ) {
			$atts['value'] = get_term_meta($term->term_id, $atts['id'], true);
		}

		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );

		?><tr class="form-field term-section-popular-topics-desc-wrap"><?php
		include( plugin_dir_path( __FILE__ ) . 'partials/'.$this->plugin_name . '-admin-table-field-radio.php' );
		?></tr><?php

		// Popular topics description limit
		$atts = array();
		$atts['description'] = 'Description will be truncated to this word count.';
		$atts['id'] = 'section-popular-topics-desc-limit';
		$atts['label'] = 'Description limit';
		$atts['name'] = 'section-popular-topics-desc-limit';
		$atts['placeholder'] = '';
		$atts['type'] = 'text';
		$atts['value'] = '15';

		if ( ! empty( get_term_meta($term->term_id, $atts['id'], true) ) ) {
			$atts['value'] = get_term_meta($term->term_id, $atts['id'], true);
		}

		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );

		?><tr class="form-field term-section-popular-topics-desc-limit-wrap"><?php
		include( plugin_dir_path( __FILE__ ) . 'partials/'.$this->plugin_name . '-admin-table-field-text.php' );
		?></tr><?php

		// Popular topics image
		$atts = array();
		$atts['description'] = '';
		$atts['id'] = 'section-popular-topics-image';
		$atts['label'] = 'Show image';
		$atts['name'] = 'section-popular-topics-image';
		$atts['type'] = 'radio';
		$atts['value'] = 'yes';
		$atts['selections'] = array(
			array(
			  'value' => 'yes',
			  'label' => 'Yes'
			),
			array(
			  'value' => 'no',
			  'label' => 'No'
			)
		);

		if ( ! empty( get_term_meta($term->term_id, $atts['id'], true) ) ) {
			$atts['value'] = get_term_meta($term->term_id, $atts['id'], true);
		}

		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );

		?><tr class="form-field term-section-popular-topics-image-wrap"><?php
		include( plugin_dir_path( __FILE__ ) . 'partials/'.$this->plugin_name . '-admin-table-field-radio.php' );
		?></tr><?php

		// Separator: Topics tree
		$atts = array();
		$atts['label'] = 'Topics tree';
		$atts['class'] = 'separator';
		$atts['type'] = 'separator';

		?><tr class="form-field term-separator-wrap"><?php
		include( plugin_dir_path( __FILE__ ) . 'partials/'.$this->plugin_name . '-admin-table-field-separator.php' );
		?></tr><?php

		// Show topics tree
		$atts = array();
		$atts['description'] = '';
		$atts['id'] = 'section-topics-tree';
		$atts['label'] = 'Show topics tree';
		$atts['name'] = 'section-topics-tree';
		$atts['type'] = 'radio';
		$atts['value'] = 'no';
		$atts['selections'] = array(
			array(
			  'value' => 'yes',
			  'label' => 'Yes'
			),
			array(
			  'value' => 'no',
			  'label' => 'No'
			)
		);

		if ( ! empty( get_term_meta($term->term_id, $atts['id'], true) ) ) {
			$atts['value'] = get_term_meta($term->term_id, $atts['id'], true);
		}

		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );

		?><tr class="form-field term-section-topics-tree-wrap"><?php
		include( plugin_dir_path( __FILE__ ) . 'partials/'.$this->plugin_name . '-admin-table-field-radio.php' );
		?></tr><?php

		// Topics tree header
		$atts = array();
		$atts['description'] = '';
		$atts['id'] = 'section-topics-tree-header';
		$atts['label'] = 'Show header';
		$atts['name'] = 'section-topics-tree-header';
		$atts['type'] = 'radio';
		$atts['value'] = 'yes';
		$atts['selections'] = array(
			array(
			  'value' => 'yes',
			  'label' => 'Yes'
			),
			array(
			  'value' => 'no',
			  'label' => 'No'
			)
		);

		if ( ! empty( get_term_meta($term->term_id, $atts['id'], true) ) ) {
			$atts['value'] = get_term_meta($term->term_id, $atts['id'], true);
		}

		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );

		?><tr class="form-field term-section-topics-tree-header-wrap"><?php
		include( plugin_dir_path( __FILE__ ) . 'partials/'.$this->plugin_name . '-admin-table-field-radio.php' );
		?></tr><?php

		// Show topics icons
		$atts = array();
		$atts['description'] = '';
		$atts['id'] = 'section-topics-icons';
		$atts['label'] = 'Show topics icons';
		$atts['name'] = 'section-topics-icons';
		$atts['type'] = 'radio';
		$atts['value'] = 'yes';
		$atts['selections'] = array(
			array(
			  'value' => 'yes',
			  'label' => 'Yes'
			),
			array(
			  'value' => 'no',
			  'label' => 'No'
			)
		);

		if ( ! empty( get_term_meta($term->term_id, $atts['id'], true) ) ) {
			$atts['value'] = get_term_meta($term->term_id, $atts['id'], true);
		}

		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );

		?><tr class="form-field term-section-topics-icons-wrap"><?php
		include( plugin_dir_path( __FILE__ ) . 'partials/'.$this->plugin_name . '-admin-table-field-radio.php' );
		?></tr><?php

		// Topics children level
		$atts = array();
		$atts['description'] = 'Show children topics up to this level.';
		$atts['id'] = 'section-topics-children-level';
		$atts['label'] = 'Children level';
		$atts['name'] = 'section-topics-children-level';
		$atts['placeholder'] = '';
		$atts['type'] = 'text';
		$atts['value'] = '6';

		if ( ! empty( get_term_meta($term->term_id, $atts['id'], true) ) ) {
			$atts['value'] = get_term_meta($term->term_id, $atts['id'], true);
		}

		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );

		?><tr class="form-field term-section-topics-children-level-wrap"><?php
		include( plugin_dir_path( __FILE__ ) . 'partials/'.$this->plugin_name . '-admin-table-field-text.php' );
		?></tr><?php

		// Topics tree columns
		$atts = array();
		$atts['description'] = '';
		$atts['id'] = 'section-topics-tree-columns';
		$atts['label'] = 'Columns';
		$atts['name'] = 'section-topics-tree-columns';
		$atts['type'] = 'select';
		$atts['value'] = '3';
		$atts['selections'] = array(
			array(
			  'value' => '1',
			  'label' => '1'
			),
			array(
			  'value' => '2',
			  'label' => '2'
			),
			array(
			  'value' => '3',
			  'label' => '3'
			)
			,
			array(
			  'value' => '4',
			  'label' => '4'
			)
		);

		if ( ! empty( get_term_meta($term->term_id, $atts['id'], true) ) ) {
			$atts['value'] = get_term_meta($term->term_id, $atts['id'], true);
		}

		apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );

		?><tr class="form-field term-section-topics-tree-columns-wrap"><?php
		include( plugin_dir_path( __FILE__ ) . 'partials/'.$this->plugin_name . '-admin-table-field-select.php' );
		?></tr><?php

  }

	/**
	 * Returns an array of the all the metabox fields and their respective types - Sections
	 *
	 * @since 		1.0.1
	 * @access 		public
	 * @return 		array 		Metabox fields and types
	 */
	private function mf_get_section_fields() {

		$fields = array();

		$fields[] = array( 'section-image', 'text' );
		$fields[] = array( 'section-top-nav', 'radio' );
		$fields[] = array( 'section-top-nav-root', 'select' );
		$fields[] = array( 'section-desc', 'radio' );
		$fields[] = array( 'section-left-nav', 'radio' );
		$fields[] = array( 'section-load-topics', 'radio' );
		$fields[] = array( 'section-show-active-topic', 'radio' );
		$fields[] = array( 'section-active-topic', 'select' );
		$fields[] = array( 'section-popular-topics', 'radio' );
		$fields[] = array( 'section-popular-topics-header', 'radio' );
		$fields[] = array( 'section-popular-topics-count', 'text' );
		$fields[] = array( 'section-popular-topics-columns', 'select' );
		$fields[] = array( 'section-popular-topics-title', 'radio' );
		$fields[] = array( 'section-popular-topics-desc', 'radio' );
		$fields[] = array( 'section-popular-topics-desc-limit', 'text' );
		$fields[] = array( 'section-popular-topics-image', 'radio' );
		$fields[] = array( 'section-topics-tree', 'radio' );
		$fields[] = array( 'section-topics-tree-header', 'radio' );
		$fields[] = array( 'section-topics-icons', 'radio' );
		$fields[] = array( 'section-topics-children-level', 'text' );
		$fields[] = array( 'section-topics-tree-columns', 'select' );

		return $fields;

	}

	/**
	 * Saves Section taxonomy custom fields.
	 *
	 * @since    	1.0.1
	 */
	public function save_section_form_custom_fields ($term_id) {

		if ( ! current_user_can( 'manage_mf_sections' ) ) { return $term_id; }

		$nonce_check = $this->check_nonces( $_POST, $term_type = 'section' );

		if ( 0 < $nonce_check ) { return $term_id; }

		$metas = $this->mf_get_section_fields();

		foreach ( $metas as $meta ) {

			$name = $this->sanitizer( 'text', $meta[0] );
			$type = $meta[1];

			if (is_array($_POST[$name])) {
				$new_value = $this->sanitizer( 'array', $_POST[$name] );
			} else {
				$new_value = $this->sanitizer( $type, $_POST[$name] );
			}

			update_term_meta( $term_id, $name, $new_value );

		} // foreach

  }

	/**
	 * Check each nonce. If any don't verify, $nonce_check is increased.
	 * If all nonces verify, returns 0.
	 *
	 * @since 		1.0.1
	 * @access 		public
	 * @return 		int 		The value of $nonce_check
	 */
	private function check_nonces( $posted, $term_type ) {

		$nonces 		= array();
		$nonce_check 	= 0;

		$nonces[] 		= $term_type.'_options';

		foreach ( $nonces as $nonce ) {

			if ( ! isset( $posted[$nonce] ) ) { $nonce_check++; }
			if ( isset( $posted[$nonce] ) && ! wp_verify_nonce( $posted[$nonce], $this->plugin_name ) ) { $nonce_check++; }

		}

		return $nonce_check;

	}

	/**
	 * Sanitizes input.
	 *
	 * @since 		1.0.1
	 * @access 		public
	 * @return 		array 		Metabox fields and types
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

}
