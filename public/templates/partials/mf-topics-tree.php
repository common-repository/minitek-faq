<?php
/**
 * Partial template: topics-tree.
 *
 * @since      	1.0.1
 * @package    	Minitek-Faq
 * @subpackage 	Minitek-Faq/public/templates/partials
 */

?><div id="mf_allTopics">

  <ul class="mfContent_allTopics clearfix"><?php

    foreach ($first_level_topics as $first_level_topic)
    {
      $topicsTree = $this->mf_topic_tree(
        $first_level_topic->term_id,
        $sectionParams['section-topics-children-level'][0],
        $sectionParams['section-topics-tree-columns'][0],
        $level = 1,
        $sectionParams['section-topics-icons'][0]
      );
      $topics_tree[] = $topicsTree;
    }

    foreach ($topics_tree as $topic_tree)
    {
      echo $topic_tree;
    }

  ?></ul>

</div>
