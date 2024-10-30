<?php
/**
 * Provide a admin area view for the plugin.
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @since      	1.0.1
 * @package    	Minitek-Faq
 * @subpackage 	Minitek-Faq/admin/partials
 */

?>
<div class="wrap">
	<h1 class="mf-admin-settings-title"><?php echo esc_html( get_admin_page_title() ); ?></h1>
</div>

<div class="wrap">
	<div id="mf-admin-settings-navigation">
		<div class="nav-tab-wrapper current">
			<a class="nav-tab nav-tab-active" href="javascript:;"><?php echo esc_html__( 'Sections', 'minitek-faq' ); ?></a>
			<a class="nav-tab" href="javascript:;"><?php echo esc_html__( 'Topics', 'minitek-faq' ); ?></a>
			<a class="nav-tab" href="javascript:;"><?php echo esc_html__( 'Slugs', 'minitek-faq' ); ?></a>
		</div>
	</div>

	<div id="mf-admin-settings">
		<form method="post" action="options.php"><?php

		settings_fields( $this->plugin_name . '-options' );

		do_settings_sections( $this->plugin_name );

		submit_button( esc_html__( 'Save Settings', 'minitek-faq' ) );

		?></form>
	</div>
</div>
