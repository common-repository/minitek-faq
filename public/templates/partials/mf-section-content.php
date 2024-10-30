<?php
/**
 * Partial template: mf-section-content.
 *
 * @since      	1.0.1
 * @package    	Minitek-Faq
 * @subpackage 	Minitek-Faq/public/templates/partials
 */

// Description
if ($sectionParams && isset($sectionParams['section-desc']) && $sectionParams['section-desc'][0] == 'yes')
{
	$section_desc = get_term( $section_id, 'mf_section' )->description;
	?>
	<div class="section-pre-text">
		<?php echo $section_desc; ?>
	</div>
<?php } ?>

<?php
// Active topic
if ($sectionParams && isset($sectionParams['section-show-active-topic']) && $sectionParams['section-show-active-topic'][0] == 'yes')
	do_action('mf_topic_content');

// Popular topics
if ($sectionParams && isset($sectionParams['section-popular-topics']) && $sectionParams['section-popular-topics'][0] == 'yes')
{
	if (isset($sectionParams['section-popular-topics-header']) && $sectionParams['section-popular-topics-header'][0] == 'yes') { ?>
		<h4 class="popularTopics_title"><?php echo __('Popular Topics', 'minitek-faq'); ?></h4>
	<?php }
	do_action('mf_popular_topics', $section_id);
}

// Topics tree
if ($sectionParams && isset($sectionParams['section-topics-tree']) && $sectionParams['section-topics-tree'][0] == 'yes')
{
	if (isset($sectionParams['section-topics-tree-header']) && $sectionParams['section-topics-tree-header'][0] == 'yes') { ?>
		<h4 class="allTopics_title"><?php echo __('All Topics', 'minitek-faq'); ?></h4>
	<?php }
	do_action('mf_topics_tree', $section_id);
}
