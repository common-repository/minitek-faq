<?php
/**
 * Partial template: mf-topic-content.
 *
 * @since      	1.0.1
 * @package    	Minitek-Faq
 * @subpackage 	Minitek-Faq/public/templates/partials
 */

// Increment views
$old_views = get_term_meta($topic_id, 'topic-views', true);
if (!$old_views || $old_views == '')
{
  update_term_meta( $topic_id, 'topic-views', 1 );
}
else
{
  update_term_meta( $topic_id, 'topic-views', ($old_views + 1) );
}

// Topic Name
if ($topicParams['topic-title'][0] == 'yes')
{
  ?><h2><a id="topicPermalink_<?php echo $topic_id; ?>" href="<?php echo esc_url(get_term_link($topic->term_id)); ?>"><?php
    echo $topic->name;
  ?></a></h2><?php
}

// Topic Description
if ($topicParams['topic-description'][0] == 'yes' && $topic->description)
{
  ?><div class="topicDesc"><?php echo $topic->description; ?></div><?php
}

// Topic Image
if ($topicParams['topic-show-image'][0] == 'yes' && isset($topicParams['topic-image'][0]) && $topicParams['topic-image'][0] > 0)
{
  ?><div class="mfContent_topicImage"><img src="<?php echo wp_get_attachment_url( (int)$topicParams['topic-image'][0] ); ?>" alt="<?php echo $topic->name; ?>"></div><?php
}

// Topic Content
if ($topicParams['topic-content'][0])
{
  ?><div class="topicDesc"><?php echo apply_filters('mf_format_content', $topicParams['topic-content'][0]); ?></div><?php
}

// Topic Questions
if ($topic->questions && !empty($topic->questions))
{
  ?><div class="topic_section" id="mfTopic_<?php echo $topic_id; ?>"><?php
		$data = array();
		$data['params'] = $topicParams;
		$data['questions'] = $topic->questions;
    do_action('mf_topicquestions', $data, $topic_id);
		?>
	</div><?php

	if (count($topic->questions) && (count($topic->questions) == $this->options['questions-limit']) && $topic_questions['remaining'] > 0)
  {
		?><div class="mfContent_paging" id="mfPaging_<?php echo $topic_id; ?>">
			<a href="#" class="mfContent_paging_button" data-page="2" data-topic="<?php echo $topic_id; ?>">
				<span class="mfContent_paging_text">
					<?php echo __('Load more', 'minitek-faq'); ?>
				</span>
				<span class="mfContent_noresults"><?php echo __('No more items', 'minitek-faq'); ?></span>
        <img class="mfContent_paging_loader" src="<?php echo plugins_url('../images/loader.gif', dirname(__FILE__) ); ?>" alt="" />
      </a>
		</div><?php
	}

}

// Subtopics
if ($topicParams['topic-subtopics'][0] == 'yes' && !empty($topic->subtopics))
{
  foreach ($topic->subtopics as $subtopic)
  {
    ?><div class="subTopic_section" id="mfTopic_<?php echo $subtopic->term_id; ?>"><?php

      if ($topicParams['topic-subtopics-titles'][0] == 'yes')
      {
        ?><h3 class="subTopic_sectionTitle">
          <a id="topicPermalink_<?php echo $subtopic->term_id; ?>" href="<?php echo esc_url(get_term_link((int)$subtopic->term_id)); ?>"><?php echo $subtopic->name; ?></a>
        </h3><?php
      }

      if ($topicParams['topic-subtopics-descriptions'][0] == 'yes' && $subtopic->description)
      {
        ?><div class="subTopic_sectionDescription"><?php echo $subtopic->description; ?></div><?php
      }

      if ($topicParams['topic-subtopics-images'][0] == 'yes')
      {
        if (isset($subtopic->params['topic-image'][0]) && $subtopic->params['topic-image'][0] > 0)
        {
          ?><div class="mfContent_topicImage">
              <img src="<?php echo wp_get_attachment_url( (int)$subtopic->params['topic-image'][0] ); ?>" alt="<?php echo $subtopic->name; ?>">
          </div><?php
        }
      }

      if ($topicParams['topic-subtopics-content'][0] == 'yes' && isset($subtopic->params['topic-content'][0]))
      {
        ?><div class="topicDesc"><?php echo apply_filters('mf_format_content', $subtopic->params['topic-content'][0]); ?></div><?php
      }

      if ($topicParams['topic-subtopics-questions'][0] == 'yes')
      {
        if ($subtopic->questions && !empty($subtopic->questions))
        {
          $data = array();
          $data['params'] = $subtopic->params;
          $data['questions'] = $subtopic->questions;
          do_action('mf_topicquestions', $data, $subtopic->term_id);
        }
      }

    ?></div><?php

    if ($subtopic->questions && (count($subtopic->questions) == $this->options['questions-limit']) && $subtopic_questions['remaining'] > 0)
    {
      ?><div class="mfContent_paging" id="mfPaging_<?php echo $subtopic->term_id; ?>">
        <a href="#" class="mfContent_paging_button" data-page="2" data-topic="<?php echo $subtopic->term_id; ?>">
          <span class="mfContent_paging_text"><?php
            echo __('Load more', 'minitek-faq');
          ?></span>
          <span class="mfContent_noresults"><?php echo __('No more items', 'minitek-faq'); ?></span>
          <img class="mfContent_paging_loader" src="<?php echo plugins_url('../images/loader.gif', dirname(__FILE__) ); ?>" alt="" />
        </a>
      </div><?php
    }

  }
}
