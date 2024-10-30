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

?><div class="wrap">
<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
<p><?php esc_html_e( 'Minitek FAQ by', 'minitek-faq' ); ?> <a href="<?php echo esc_url( 'https://www.minitek.gr' ); ?>" target="_blank"><?php echo esc_html( 'Minitek.gr' ); ?></a></p>
<br />

<h2><?php esc_html_e( 'Description', 'minitek-faq' ); ?></h2>
<p><?php esc_html_e( 'Allow users to quickly find answers to the most common questions.', 'minitek-faq' ); ?></p>
<br />

<h2><?php esc_html_e( 'Version', 'minitek-faq' ); ?></h2>
<p><?php echo esc_html( $this->version ); ?></p>
<br />

<h2><?php esc_html_e( 'Documentation', 'minitek-faq' ); ?></h2>
<p><a href="<?php echo esc_url( 'https://www.minitek.gr/support/documentation/wordpress/plugins/minitek-faq' ); ?>" target="_blank"><?php echo esc_url( 'https://www.minitek.gr/support/documentation/wordpress/plugins/minitek-faq' ); ?></a></p>
<br />

<h2><?php esc_html_e( 'Pro version', 'minitek-faq' ); ?></h2>
<p><?php esc_html_e( 'For more advanced features (eg. User questions, voting), you can upgrade to the' ); ?> <a href="<?php echo esc_url( 'https://www.minitek.gr/wordpress/plugins/minitek-faq' ); ?>" target="_blank"><?php esc_html_e( 'Pro version', 'minitek-faq' ); ?></a>.</p>
<br />

<h2><?php esc_html_e( 'Support', 'minitek-faq' ); ?></h2>
<p><?php esc_html_e( 'For technical support, please' ); ?> <a href="<?php echo esc_url( 'https://www.minitek.gr/support/tickets/free-wordpress-plugins/minitek-faq' ); ?>" target="_blank"><?php esc_html_e( 'open a ticket', 'minitek-faq' ); ?></a> <?php esc_html_e( 'in our website.', 'minitek-faq' ); ?></p>
<br />
