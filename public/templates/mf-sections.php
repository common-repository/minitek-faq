<?php
/**
 * Shortcode template: mf-sections.
 *
 * @since      	1.0.1
 * @package    	Minitek-Faq
 * @subpackage 	Minitek-Faq/public/templates
 */

?><div id="mf" class="mfSections"><?php

	if ($this->options['top-navigation'] == 'yes')
	{
		echo apply_filters('mf_topnav', '');
	}

	?><div id="mfcontent" class="mfContent_core noleftnav"><?php

		?><div class="mfContent_root">

			<div class="mfContent_sections"><?php

				if ($sections_ids)
				{
					 ?><div class="mf_columns clearfix"><?php

						$i = 0;
						foreach ($sections_ids as $key => $section_id)
						{
							$section = get_term($section_id, 'mf_section');
							$sectionParams = get_term_meta($section_id);
							$section_image = (isset($sectionParams['section-image'][0]) && $sectionParams['section-image'][0] > 0) ? wp_get_attachment_url( $sectionParams['section-image'][0] ) : false;

							?><div class="mf_column" style="width:<?php echo number_format(100/$this->options['sections-columns'], 2); ?>%;">

								<div class="mf_column_inner"><?php

									if ($this->options['section-image'] == 'yes' && $section_image)
									{
										?><a href="<?php echo esc_url(get_term_link($section_id)); ?>">
											<img src="<?php echo $section_image; ?>" alt="<?php echo $section->name; ?>" />
										</a><?php
									}

									if ($this->options['section-title'] == 'yes')
									{
										 ?><h3 class="mf_column_header">
											<a href="<?php echo esc_url(get_term_link($section_id)); ?>"><?php
												echo $section->name;
											 ?></a>
										</h3><?php
									}

									if ($this->options['section-description'] == 'yes')
									{
										?><div class="mf_column_desc">
											<?php echo $section->description; ?>
										</div><?php
									}

									if ($this->options['section-topics'] == 'yes')
									{
										$section_topics = $this->dataSource->mf_get_flat_topics($section_id);
										if ($section_topics)
										{
											?><div class="mf_column_topics"><?php
												foreach ($section_topics as $topic)
												{
													$topicParams = get_term_meta($topic->term_id);
													?><div class="mf_column_topic">
														<a href="<?php echo esc_url(get_term_link($topic->term_id)); ?>"><?php
															if (isset($topicParams['topic-icon-class'][0]))
															{
																?><i class="dashicons dashicons-<?php echo $topicParams['topic-icon-class'][0]; ?>"></i><?php
															}
															echo $topic->name;
														?></a>
													</div><?php
												}
											?></div><?php
										}
									}

								 ?></div>

							</div><?php

							$i++;
							if (isset($this->options['sections-columns']) && ($i)%$this->options['sections-columns'] == 0)
							{
								?><div class="clearfix"> </div><?php
							}

						}

					?></div><?php
				}

			?></div>

		</div>
	</div>

</div>
