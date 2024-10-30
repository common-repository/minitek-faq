<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      	1.0.1
 * @package    	Minitek-Faq
 * @subpackage 	Minitek-Faq/includes
 */

class MFaq_Activator {

	/**
	 * The code that runs during plugin activation.
	 *
	 * @since    	1.0.1
	 */
	public static function activate() {

	}

	/**
	 * Set default capabilities for default user roles.
	 *
	 * @since 		1.0.1
	 */
	public static function mf_add_default_caps() {

    // Get the Administrator role
    $admins = get_role( 'administrator' );

			// Admins: Questions
	    $admins->add_cap( 'edit_mf_questions' );
	    $admins->add_cap( 'edit_others_mf_questions' );
	    $admins->add_cap( 'publish_mf_questions' );
	    $admins->add_cap( 'read_private_mf_questions' );
			$admins->add_cap( 'read_mf_questions' );
			$admins->add_cap( 'delete_mf_questions' );
			$admins->add_cap( 'delete_private_mf_questions' );
			$admins->add_cap( 'delete_published_mf_questions' );
			$admins->add_cap( 'delete_others_mf_questions' );
			$admins->add_cap( 'edit_private_mf_questions' );
			$admins->add_cap( 'edit_published_mf_questions' );
			$admins->add_cap( 'create_mf_questions' );

			// Admins: Sections
			$admins->add_cap( 'manage_mf_sections' );

			// Admins: Topics
			$admins->add_cap( 'manage_mf_topics' );

		// Get the Editor role
    $editors = get_role( 'editor' );

			// Editors: Questions
			$editors->add_cap( 'edit_mf_questions' );
			$editors->add_cap( 'edit_others_mf_questions' );
			$editors->add_cap( 'publish_mf_questions' );
			$editors->add_cap( 'read_private_mf_questions' );
			$editors->add_cap( 'read_mf_questions' );
			$editors->add_cap( 'delete_mf_questions' );
			$editors->add_cap( 'delete_private_mf_questions' );
			$editors->add_cap( 'delete_published_mf_questions' );
			$editors->add_cap( 'delete_others_mf_questions' );
			$editors->add_cap( 'edit_private_mf_questions' );
			$editors->add_cap( 'edit_published_mf_questions' );
			$editors->add_cap( 'create_mf_questions' );

			// Editors: Sections
			$editors->add_cap( 'manage_mf_sections' );

			// Editors: Topics
			$editors->add_cap( 'manage_mf_topics' );

		// Get the Author role
	  $authors = get_role( 'author' );

			// Authors: Questions
			$authors->add_cap( 'edit_mf_questions' );
			$authors->add_cap( 'publish_mf_questions' );
			$authors->add_cap( 'read_mf_questions' );
			$authors->add_cap( 'delete_mf_questions' );
			$authors->add_cap( 'delete_published_mf_questions' );
			$authors->add_cap( 'edit_published_mf_questions' );
			$authors->add_cap( 'create_mf_questions' );

		// Get the Contributor role
		$contributors = get_role( 'contributor' );

			// Contributors: Questions
			$contributors->add_cap( 'edit_mf_questions' );
			$contributors->add_cap( 'read_mf_questions' );
			$contributors->add_cap( 'delete_mf_questions' );
			$contributors->add_cap( 'create_mf_questions' );

		// Get the Subscriber role
		$subscribers = get_role( 'subscriber' );

			// Subscribers: Questions
			$subscribers->add_cap( 'read_mf_questions' );

	}

}
