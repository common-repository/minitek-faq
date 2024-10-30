<?php
/**
 * Provides the markup for an image selection field.
 *
 * @since      	1.0.1
 * @package    	Minitek-Faq
 * @subpackage 	Minitek-Faq/admin/partials
 */

wp_enqueue_media();

if ( ! empty( $atts['label'] ) ) {

	?><th><label for="<?php echo esc_attr( $atts['id'] ); ?>"><?php esc_html_e( $atts['label'], 'minitek-faq' ); ?></label></th><?php

}

if ( ! empty( $atts['value'] ) && $atts['value'] > 0) {
	$img_height = 'height="150"';
}
else {
	$img_height = 'height=""';
}

?>
<script type='text/javascript'>
		jQuery( document ).ready( function( $ ) {
			// Uploading files
			var file_frame;
			var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
			var set_to_post_id = <?php echo $atts['value']; ?>;
			if (set_to_post_id == -1)
			{
				set_to_post_id = 0;
			}
			jQuery('#upload_image_button').on('click', function( event ){
				event.preventDefault();
				// If the media frame already exists, reopen it.
				if ( file_frame ) {
					// Set the post ID to what we want
					file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
					// Open frame
					file_frame.open();
					return;
				} else {
					// Set the wp.media post id so the uploader grabs the ID we want when initialised
					wp.media.model.settings.post.id = set_to_post_id;
				}
				// Create the media frame.
				file_frame = wp.media.frames.file_frame = wp.media({
					title: 'Select an image to upload',
					button: {
						text: 'Use this image',
					},
					multiple: false	// Set to true to allow multiple files to be selected
				});
				// When an image is selected, run a callback.
				file_frame.on( 'select', function() {
					// We set multiple to false so only get one image from the uploader
					attachment = file_frame.state().get('selection').first().toJSON();
					// Do something with attachment.id and/or attachment.url here
					$( '#image-preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
					$( '#<?php echo esc_attr( $atts['id'] ); ?>' ).val( attachment.id );
					// Restore the main post ID
					wp.media.model.settings.post.id = wp_media_post_id;
					// Set image height
					$( '#image-preview' ).attr( 'height', 150 );
				});
					// Finally, open the modal
					file_frame.open();
			});
			// Restore the main ID when the add media button is pressed
			jQuery( 'a.add_media' ).on( 'click', function() {
				wp.media.model.settings.post.id = wp_media_post_id;
			});
			// Remove image button
			jQuery( '#remove_image_button' ).on( 'click', function() {
				$( '#<?php echo esc_attr( $atts['id'] ); ?>' ).val( -1 );
				$( '#image-preview' ).attr( 'src', '' ).css( 'width', 'auto' );
				$( '#image-preview' ).attr( 'height', 0 );
			});
		});
	</script>

	<td>
		<div class="image-preview">
			<div class='image-preview-wrapper'>
				<img id='image-preview' src='<?php echo wp_get_attachment_url( $atts['value'] ); ?>' <?php echo $img_height; ?>>
			</div>
			<div>
			<input
				id="upload_image_button"
				type="button"
				class="button"
				value="<?php _e( 'Select', 'minitek-faq' ); ?>" />
			<input
				id="remove_image_button"
				type="button"
				class="button"
				value="<?php _e( 'Remove', 'minitek-faq' ); ?>" />
			<input
				type='hidden'
				name=<?php echo esc_attr( $atts['name'] ); ?>
				id=<?php echo esc_attr( $atts['id'] ); ?>
				value='<?php echo $atts['value']; ?>'>
			<br /><br />
			</div>
		</div>

<?php
if ( ! empty( $atts['description'] ) ) {

	?><p class="description">
		<?php
		if (array_key_exists('desc_link', $atts)) {
		?><a href="<?php echo esc_url( $atts['desc_link'] ); ?>" target="_blank">
		<?php }
		esc_html_e( $atts['description'], 'minitek-faq' );
		if (array_key_exists('desc_link', $atts)) {
		?></a>
		<?php } ?>
	</p><?php

}
?></td>
