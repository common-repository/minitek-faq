<?php
/**
 * Taxonomy template: mf_topic.
 *
 * @since      	1.0.1
 * @package    	Minitek-Faq
 * @subpackage 	Minitek-Faq/public/templates
 */

get_header();

$topic_id = get_queried_object()->term_id;
$section_id = get_term_meta($topic_id, 'topic-section', true);

// If no section, inherit from parent
if (!$section_id || $section_id == '-1')
{
	$dataSource = new MFaq_Data();
	$section_id = $dataSource->get_inherited_section($topic_id);
}

$sectionParams = get_term_meta($section_id);

?><div id="content-wrap" class="container clr">
<section class="entry">
<div class="entry-content">
<div id="mf" class="mfTopic"><?php

	if ($sectionParams && isset($sectionParams['section-top-nav'][0]) && $sectionParams['section-top-nav'][0] == 'yes')
		echo apply_filters('mf_topnav', $section_id);

	echo apply_filters('mf_leftnav', $section_id);

	?><div id="mfcontent" class="mfContent_core"><?php

		?><div class="mf_loader">
			<img src="<?php echo plugins_url('images/loaderbig.gif', dirname(__FILE__) ); ?>" alt="" />
		</div>
		<div class="mfContent_root"><?php

			do_action('mf_topic_content');

		?></div>
	</div>
</div>
</div>
</section>
</div>

<div class="clearfix"> </div><?php

get_footer();
