<?php
/**
 * Partial template: popular-topics.
 *
 * @since      	1.0.1
 * @package    	Minitek-Faq
 * @subpackage 	Minitek-Faq/public/templates/partials
 */

if ($sectionParams['section-popular-topics-columns'][0] > 1)
{
  $class = 'mfContent_gridItem';
  $anchor_class = 'clearfix';
}
else
{
  $class = 'mfContent_gridItem onecolgrid';
  $anchor_class = '';
}

?><div id="mf_popTopics">

  <ul class="mfContent_grid clearfix"><?php

    $i = 0;
    foreach ($popular_topics as $key => $topic)
    {
      ?><li class="<?php echo $class; ?>" style="width:<?php echo number_format(100/$sectionParams['section-popular-topics-columns'][0], 1); ?>%;">

        <div class="mfContent_gridItemContainer"><?php

          $topicParams = get_term_meta($topic->term_id);
          $title_class = 'text-left';
          if ($sectionParams['section-popular-topics-image'][0] == 'yes')
          {
            $topic_image = (isset($topicParams['topic-image'][0]) && $topicParams['topic-image'][0] > 0) ? wp_get_attachment_url( $topicParams['topic-image'][0] ) : false;
            $title_class = 'text-center';
            if ($topic_image)
            {
              ?><a href="<?php echo esc_url(get_term_link($topic->term_id)); ?>" class="feat-item-img <?php echo $anchor_class; ?>">
                <img src="<?php echo $topic_image; ?>" alt="<?php echo $topic->name; ?>">
              </a><?php
            }
          }
          if ($sectionParams['section-popular-topics-title'][0] == 'yes')
          {
            ?><h4 class="<?php echo $title_class; ?>">
              <a href="<?php echo esc_url(get_term_link($topic->term_id)); ?>" class="feat-item <?php echo $anchor_class; ?>" id="fid<?php echo $topic->term_id; ?>">
                <?php echo $topic->name; ?>
              </a>
            </h4><?php
          }
          if ($sectionParams['section-popular-topics-desc'][0] == 'yes')
          {
            $topic_description = wp_trim_words( $topic->description, $sectionParams['section-popular-topics-desc-limit'][0] );
            ?><div class="index-cat-desc">
              <?php echo $topic_description; ?>
            </div><?php
          }

        ?></div>

      </li><?php

      $i++;
    }

  ?></ul>

</div>
