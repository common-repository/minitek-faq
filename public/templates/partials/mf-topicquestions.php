<?php
/**
 * Partial template: topicquestions.
 *
 * @since      	1.0.1
 * @package    	Minitek-Faq
 * @subpackage 	Minitek-Faq/public/templates/partials
 */

foreach ($data['questions'] as $question)
{
	?><div id="faq_<?php echo $question->ID; ?>" class="topic_faqBlock <?php echo ($data['params']['topic-questions-opened'][0] == 'yes') ? 'faq_open' : '' ; ?>">
    <div class="topic_faqPresentation">
      <a href="#" id="faqLink_<?php echo $question->ID; ?>" class="topic_faqToggleLink" onclick="return false;">
        <span class="topic_faqToggleQuestion"><?php
        echo $question->post_title;
        ?></span>
        <span class="topic_faqExpanderIcon"></span><?php
        // Question pre-text
        if ($this->options['questions-introtext'] == 'yes')
        {
          ?><span class="topic_faqAnswerWrapper_preview">
            <span><?php echo $question->introtext; ?></span>
          </span><?php
        } ?>
      </a>
    </div>

		<div id="a_w_<?php echo $question->ID; ?>" class="topic_faqAnswerWrapper">
			<div class="topic_faqAnswerWrapper_inner">
				<?php if ($this->options['questions-image'] == 'yes' && $question->image) { ?>
					<img src="<?php echo $question->image; ?>" alt="<?php echo $question->post_title; ?>" />
				<?php }
				?><div class="faq_text clearfix"><?php
					echo $question->post_content;
				?></div><?php
				// Question date & author
				if ($this->options['questions-date'] == 'yes' || $this->options['questions-author'] == 'yes')
        {
					?><div class="faq_extra"><?php
					if ($this->options['questions-date'] == 'yes')
          {
            ?><span class="faq_date"><?php
							echo __('on', 'minitek-faq').' '.$question->formatted_date;
						?></span><?php
					}
					if ($this->options['questions-author'] == 'yes' && $question->post_author)
          {
						?><span class="faq_author">
							<span><?php echo __('by', 'minitek-faq'); ?></span>
              <span><?php echo get_the_author_meta('nicename', $question->post_author); ?></span>
						</span><?php
					}
          ?></div><?php
				}

			?></div>
		</div>
	</div>
<?php }
