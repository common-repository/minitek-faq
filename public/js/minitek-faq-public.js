(function( $ ) {
	'use strict';

	$(function() {

		// Plugin variables
		var ajax_url = mf_vars.ajax_url;
		var is_section = mf_vars.is_section;
		var is_topic = mf_vars.is_topic;
		var is_question = mf_vars.is_question;
		var sectionId = mf_vars.sectionId;
		var topicId = mf_vars.topicId;
		var activeTopic = mf_vars.activeTopic;
		var topnav = mf_vars.topnav;
		var leftnav = mf_vars.leftnav;
		var loadAllTopics = mf_vars.loadAllTopics;
		var ajax_request;
		var paging_ajax_request;

		// Fix leftnav height on page load
		if (leftnav > 0)
		{
			$(window).on('load', function() {

				// Fix leftnav height on window load
				if (is_topic || is_question)
				{
					// Show left navigation before calculating height
					$('.mfLeftNavigation_core').removeClass('mf-hidden');

					// Fix left navigation topics height
					if ($('#liid'+topicId).hasClass('NavLeftUL_endpoint'))
					{
						var parent_ul = $('#liid'+topicId).parent();
					}
					else
					{
						var parent_ul = $('#liid'+topicId).find('ul:first');
					}

					var vheight = $(parent_ul).height();
					$('.mfLeftNavigation_root').css({"height":vheight+"px"});

					// Hide left navigation
					$('.mfLeftNavigation_core').addClass('mf-hidden');
					$('.show_menu').find('a:first').removeClass('btn-mfactive');
				}
				else if (is_section)
				{
					// Fix left navigation topics height
					$('.mfLeftNavigation_root').css({"height":"auto"});
				}
			});
		}

		// Load Home section page
		function loadHome(event, href)
		{
			$('.show_menu').removeClass('mf-shown');

			// Check if there is a pending ajax request
			if(typeof ajax_request !== "undefined")
			ajax_request.abort();

			$('.mfContent_root').html('');
			$('#mfcontent').find('.mf_loader').show();

			ajax_request = $.ajax({
				type: "POST",
				url: ajax_url,
				data: {
					action: 'mf_section_content_display',
					section: ''+sectionId+'',
					ajax: '1',
				},
				beforeSend: function() {
					// Change url dynamically
					window.history.pushState({}, document.title, href);
				},
				success: function(msg) {
					$(".mfTopNavigation_wrap").removeClass('NavTopULloading');
					$('#top_liid_home').addClass('NavTopUL_lastChild');
					$('#mfcontent').find('.mf_loader').hide();
					$(".mfContent_root").html(msg);

					// Change browser title dynamically
					var page_title = $('.mfTopNavigation_root #top_liid_home').find('.NavTopUL_link').attr('data-title');
					$(document).attr('title', page_title);

					// Activate actions in questions
					activateQuestions();

					// Show left navigation
					$('.mfLeftNavigation_core').removeClass('mf-hidden');
					$('.show_menu').find('a:first').addClass('btn-mfactive');

					// Remove loader from top navigation
					$(".mfTopNavigation_wrap").removeClass('NavTopULloading');
				}
			});
		}

		// Reset leftnav - topnav
		function resetTopics(event, href)
		{
			// Fix left navigation topics height
			$('.mfLeftNavigation_root').css({"height":"auto"});

			$('#NavLeftUL').addClass('ul_loading');

			var li_count = $('.mfTopNavigation_root li.NavTopUL_parent').length;
			var slide_count = parseInt(li_count);
			var righty = $('.mfLeftNavigation_wrap');
			var move_right = slide_count * 100;

			// Remove all li items after home
			$('#top_liid_home').nextAll('li').remove();

			// Keep track of left navigation animation to prevent double clicks
			if ((leftnav && $('.mfLeftNavigation_wrap:animated').length == 0 && !$('.mfTopNavigation_wrap').hasClass('NavTopULloading'))
			|| (!leftnav && !$('.mfTopNavigation_wrap').hasClass('NavTopULloading')))
			{
				// Add loader in top navigation
				$(".mfTopNavigation_wrap").addClass('NavTopULloading');

				$('.NavLeftUL_item').removeClass('li_selected');

				if (leftnav > 0)
				{
					righty.animate(
						{ left:"+="+move_right+"%" },
						{ queue: false, complete: function(){
							$('#NavLeftUL ul').removeClass('NavLeftUL_expanded');
							$('#top_liid_home').addClass('NavTopUL_lastChild');
							$('#NavLeftUL').removeClass('ul_loading');

							loadHome(event, href);

							if (activeTopic > 0)
							{
								$('#liid'+activeTopic).addClass('li_selected');
							}
						}
					});
				}
				else
				{
					$('#top_liid_home').addClass('NavTopUL_lastChild');
					loadHome(event, href);
				}
			}
		}

		// Add topic in top navigation
		function addTopNavTopic(currentActiveTopic)
		{
			if (currentActiveTopic > 0)
			{
				if (!is_section || is_section == 0)
				{
					// Active topnav li
					var parent_ul_class = $('#liid'+currentActiveTopic).parent('ul').attr('class');
					var ul_level = parent_ul_class.split(" ")[1];
					var ul_level_num = ul_level.substring(ul_level.lastIndexOf('level') + 5);
					var parents_num = parseInt(ul_level_num);
					var first_parent_text = $('#liid'+currentActiveTopic).parent().parent().find('> .NavLeftUL_anchor span.catTitle').text();
					var first_parent_id = $('#liid'+currentActiveTopic).parent('ul').parent('li').attr('id');

					$('.mfTopNavigation_root li.NavTopUL_firstChild').removeClass('NavTopUL_lastChild');

					// Add topnav li's
					var $id = $('#'+first_parent_id);
					var $li = $('#'+first_parent_id);

					function findParents()
					{
						$id = $id.parent().parent();
						$li = $li.parent('ul').parent('li');
						var prev_parent_text = $id.find('> .NavLeftUL_anchor span.catTitle').text();
						var prev_parent_id = $li.attr('id');

						// Add topic to breadcrumbs
						$('<li id="top_'+prev_parent_id+'" class="NavTopUL_item NavTopUL_topic NavTopUL_parent"><i class="dashicons dashicons-arrow-right"></i>&nbsp;<a class="NavTopUL_link" href="#" onclick="return false;">'+prev_parent_text+'</a></li>').insertAfter('li.NavTopUL_firstChild');
					}

					// Only for level1+ ul's
					if (ul_level_num > 0)
					{
						for (var i = 1; i < parents_num; i++)
						{
							findParents();
						}

						// Add lastChild parent li in top navigation
						// Endpoint topic - add class NavTopUL_lastChild
						if ($('#liid'+topicId).hasClass('NavLeftUL_endpoint'))
						{
							$('.mfTopNavigation_root').append($('<li id="top_'+first_parent_id+'" class="NavTopUL_item NavTopUL_topic NavTopUL_parent NavTopUL_lastChild"><i class="dashicons dashicons-arrow-right"></i>&nbsp;<a class="NavTopUL_link" href="#" onclick="return false;">'+first_parent_text+'</a></li>'));
						}
						// Not endpoint topic - don't add class NavTopUL_lastChild
						else
						{
							$('.mfTopNavigation_root').append($('<li id="top_'+first_parent_id+'" class="NavTopUL_item NavTopUL_topic NavTopUL_parent"><i class="dashicons dashicons-arrow-right"></i>&nbsp;<a class="NavTopUL_link" href="#" onclick="return false;">'+first_parent_text+'</a></li>'));
						}
					}

					// Add lastChild li in top navigation
					var last_topic_text = $('#liid'+topicId).find('> .NavLeftUL_anchor span.catTitle').text();

					// Endpoint topic - don't add class NavTopUL_parent
					if ($('#liid'+topicId).hasClass('NavLeftUL_endpoint'))
					{
						$('.mfTopNavigation_root').append($('<li id="top_liid'+topicId+'" class="NavTopUL_item NavTopUL_topic NavTopUL_endpoint NavTopUL_lastChild"><i class="dashicons dashicons-arrow-right"></i>&nbsp;<a class="NavTopUL_link" href="#" onclick="return false;">'+last_topic_text+'</a></li>'));
					}
					// Non endpoint topic - add class NavTopUL_parent
					else
					{
						$('.mfTopNavigation_root').append($('<li id="top_liid'+topicId+'" class="NavTopUL_item NavTopUL_topic NavTopUL_parent NavTopUL_lastChild"><i class="dashicons dashicons-arrow-right"></i>&nbsp;<a class="NavTopUL_link" href="#" onclick="return false;">'+last_topic_text+'</a></li>'));
					}
				}
			}
		}

		// Highlight topic in left navigation
		function highlightLeftNavTopic(currentActiveTopic)
		{
			if (currentActiveTopic > 0)
			{
				// Active leftnav li
				$('.NavLeftUL_item').removeClass('li_selected');
				$('#liid'+currentActiveTopic).addClass('li_selected');

				// Active leftnav ul
				$('#liid'+currentActiveTopic).parents('ul.NavLeftUL_sublist').addClass('NavLeftUL_expanded');
				$('#liid'+currentActiveTopic).find('ul.NavLeftUL_sublist:first').addClass('NavLeftUL_expanded');
				var parent_ul_class = $('#liid'+currentActiveTopic).parent('ul').attr('class');

				if (leftnav > 0)
				{
					if (!is_section || is_section == 0)
					{
						var ul_level = parent_ul_class.split(" ")[1];
						var ul_level_num = ul_level.substring(ul_level.lastIndexOf('level') + 5);

						// Endpoint topic - we don't want to see the children topics
						if ($('#liid'+currentActiveTopic).hasClass('NavLeftUL_endpoint'))
						{
							var move_level_num = parseInt(ul_level_num, 10);
						}
						// We want to see the chidlren topics of selected topic, so we need one more level
						else
						{
							var move_level_num = parseInt(ul_level_num, 10) + 1;
						}

						var move_ul = parseInt(move_level_num, 10)*100;
						$('.mfLeftNavigation_wrap').css({"left":"-"+move_ul+"%"});
					}
				}
			}
		}

		if (is_topic || is_question)
		{
			if (leftnav > 0)
			{
				highlightLeftNavTopic(topicId);
			}
			if (topnav > 0)
			{
				addTopNavTopic(topicId);
			}
		}
		else if (is_section && activeTopic)
		{
			if (leftnav > 0)
			{
				highlightLeftNavTopic(activeTopic);
			}
			if (topnav > 0)
			{
				addTopNavTopic(activeTopic);
			}
		}

		// Activate actions in questions
		function activateQuestions()
		{
			// Toggle FAQ in Category
			$('#mf').on('click', '.topic_faqToggleLink', function(event)
			{
				event.stopImmediatePropagation();
				event.preventDefault();

				var this_faqid = $(this).attr('id');
				var faq_id = this_faqid.split("_").pop(0);

				if ($('#faq_'+faq_id).hasClass('faq_open')) {
					$('#faq_'+faq_id).removeClass('faq_open');
				} else {
					$('#faq_'+faq_id).addClass('faq_open');
				}
			});

		}
		activateQuestions();

		// Load topic endpoint
		function loadEndpoint(id, this_liid, href_link, cat_title)
		{
			$('#NavLeftUL').addClass('ul_loading');

			// Check if there is a pending ajax request
			if(typeof ajax_request !== "undefined")
			ajax_request.abort();

			$('.NavLeftUL_item').removeClass('li_loading');

			if (loadAllTopics == '1' || (loadAllTopics == '0' && $('#'+this_liid).hasClass('NavLeftUL_endpoint')))
			{
				if (loadAllTopics == '1' && !$('#'+this_liid).hasClass('NavLeftUL_endpoint'))
				{
					$('.mfContent_root').html('');
					$('#mfcontent').find('.mf_loader').show();
				}

				ajax_request = $.ajax({
					type : "POST",
					url : ajax_url,
					data : {
						action : 'mf_topic_content_display',
						topic : ''+id+'',
						ajax : '1',
					},
					beforeSend: function() {
						$('#'+this_liid).addClass('li_loading');
						window.history.pushState({}, document.title, href_link);
					},
					success: function(msg) {
						// Add 'show menu' button
						$('.show_menu').addClass('mf-shown');

						$(".mfTopNavigation_wrap").removeClass('NavTopULloading');
						$('#NavLeftUL').removeClass('ul_loading');
						$('#'+this_liid).removeClass('li_loading');
						$('.NavLeftUL_item').removeClass('li_selected');
						$('#mfcontent').find('.mf_loader').hide();
						$(".mfContent_root").html(msg);
						$('#'+this_liid).addClass('li_selected');

						// Change browser title dynamically
						$(document).attr('title', cat_title);

						activateQuestions();

						// Hide left navigation
						if ($('#'+this_liid).hasClass('NavLeftUL_endpoint'))
						{
							$('.mfLeftNavigation_core').addClass('mf-hidden');
							$('.show_menu').find('a:first').removeClass('btn-mfactive');
						}
					}
				});
			}
			else
			{
				// Make sure that the loading classes are removed
				$('#mfcontent').find('.mf_loader').hide();
				$(".mfTopNavigation_wrap").removeClass('NavTopULloading');
				$('#NavLeftUL').removeClass('ul_loading');
				$('#'+this_liid).removeClass('li_loading');
			}
		}

		// Left navigation topic links
		$('#NavLeftUL li.NavLeftUL_item').on('click', 'a:first', function(event)
		{
			event.preventDefault();

			var this_liid = $(this).parent('li').attr('id');
			var endpoint_liid = $(this).parent('li').attr('id');
			var endpoint_id = endpoint_liid.split("id").pop(1);
			var this_liclass = $(this).parent('li').attr('class');
			var href_link = $(this).attr('href');
			var cat_title = $(this).text();

			// Slide menu only if is not endpoint
			if (!$(this).parent('li').hasClass('NavLeftUL_endpoint'))
			{
				// Keep track of left navigation animation to prevent double clicks
				if ($('.mfLeftNavigation_wrap:animated').length == 0 && !$('#NavLeftUL').hasClass('ul_loading') && !$('.mfTopNavigation_wrap').hasClass('NavTopULloading'))
				{
					// Fix left navigation topics height
					var parent_li = $(this).parent();
					var child_ul = $(parent_li).find('ul:first');
					var eheight = $(child_ul).height();
					$('.mfLeftNavigation_root').css({"height":eheight+"px"});

					$('.mfLeftNavigation_root').find('ul').removeClass('NavLeftUL_expanded');
					$('#'+this_liid).parents('ul.NavLeftUL_sublist').addClass('NavLeftUL_expanded');
					$('#'+this_liid).find('ul:first').addClass('NavLeftUL_expanded');

					var lefty = $('.mfLeftNavigation_wrap');
					lefty.animate(
						{ left:"-=100%" },
						{ queue: true, complete: function(){
							// Remove lastchild class
							$('.mfTopNavigation_root li').removeClass('NavTopUL_lastChild');

							// Remove last endpoint
							$('.mfTopNavigation_root li.NavTopUL_endpoint').remove();

							// Add topic to breadcrumbs
							var this_title = $('#'+this_liid).find('a:first').text();
							$('.mfTopNavigation_root').append($('<li id="top_'+this_liid+'" class="NavTopUL_item NavTopUL_topic NavTopUL_parent NavTopUL_lastChild"><i class="dashicons dashicons-arrow-right"></i>&nbsp;<a class="NavTopUL_link" href="#" onclick="return false;">'+this_title+'</a></li>'));

							// Load endpoint
							$(".mfTopNavigation_wrap").removeClass('NavTopULloading');
							loadEndpoint(endpoint_id, this_liid, href_link, cat_title);
						}
					});
				}
			}
			else
			{
				// Keep track of left navigation animation to prevent double clicks
				if ($('.mfLeftNavigation_wrap:animated').length == 0 && !$('#NavLeftUL').hasClass('ul_loading') && !$('.mfTopNavigation_wrap').hasClass('NavTopULloading'))
				{
					var this_title = $('#'+this_liid).find('a:first').text();

					// Remove lastchild class from section li
					$('.mfTopNavigation_root li.NavTopUL_firstChild').removeClass('NavTopUL_lastChild');

					// Remove last endpoint
					$('.mfTopNavigation_root li.NavTopUL_endpoint').remove();

					// Add endpoint topic to breadcrumbs
					$('.mfTopNavigation_root').append($('<li id="top_'+this_liid+'" class="NavTopUL_item NavTopUL_topic NavTopUL_endpoint NavTopUL_lastChild"><i class="dashicons dashicons-arrow-right"></i>&nbsp;<a class="NavTopUL_link" href="#" onclick="return false;">'+this_title+'</a></li>'));

					// Load endpoint
					$(".mfTopNavigation_wrap").removeClass('NavTopULloading');
					loadEndpoint(endpoint_id, this_liid, href_link, cat_title);
				}
			}
		});

		// Topic back link - Remove class 'expanded' from 1st parent ul / Move wrap to the right
		$('#NavLeftUL li.NavLeftUL_backItem').on('click', 'a:first', function(event)
		{
			// Keep track of animation to prevent double clicks
			if ($('.mfLeftNavigation_wrap:animated').length == 0 && !$('#NavLeftUL').hasClass('ul_loading') && !$('.mfTopNavigation_wrap').hasClass('NavTopULloading'))
			{
				var this_backliid = $(this).parent().attr('id');

				// Fix left navigation topics height
				var back_child_ul = $(this).parent().parent().parent().parent();
				var wheight = $(back_child_ul).height();
				$('.mfLeftNavigation_root').css({"height":wheight+"px"});

				var righty = $('.mfLeftNavigation_wrap');

				righty.animate(
					{ left:"+=100%" },
					{ queue: false, complete: function(){
						$('#'+this_backliid).parent('ul').removeClass('NavLeftUL_expanded');
						$('.mfTopNavigation_root li.NavTopUL_lastChild').remove();
						$('.mfTopNavigation_root li:last').addClass('NavTopUL_lastChild');
					}
				});
			}
		});

		// Top Navigation
		$('.mfTopNavigation_root').on('click', 'li', function(event, this_liclass) {

			if ($(this).hasClass('NavTopUL_home'))
			{
				return;
			}

			event.preventDefault();

			var this_liclass = $(this).attr('class');
			var this_liid = $(this).attr('id');
			var href = $(this).find('.NavTopUL_link').attr('href');

			// Topic links
			if ($(this).hasClass('NavTopUL_parent') && !$(this).hasClass('NavTopUL_lastChild') && !$('#NavLeftUL').hasClass('ul_loading') && !$('.mfTopNavigation_wrap').hasClass('NavTopULloading'))
			{
				// Keep track of left navigation animation to prevent double clicks
				if ($('.mfLeftNavigation_wrap:animated').length == 0 && !$('#NavLeftUL').hasClass('ul_loading') && !$('.mfTopNavigation_wrap').hasClass('NavTopULloading'))
				{
					// Fix left navigation topics height
					var leftnav_liid = this_liid.split("_").pop(0);
					var leftnav_child_ul = $('.mfLeftNavigation_root li#'+leftnav_liid).find('ul:first');
					var eheight = $(leftnav_child_ul).height();
					$('.mfLeftNavigation_root').css({"height":eheight+"px"});

					var li_count = $('.mfTopNavigation_root li.NavTopUL_parent').length;
					var li_index = $('.mfTopNavigation_root li.NavTopUL_parent').index(this);
					var slide_count = parseInt(li_count) - parseInt(li_index) - 1;

					// Remove li's after specific index
					$('.NavTopUL_topic').eq(li_index).nextAll('li').remove();
					$(this).addClass('NavTopUL_lastChild');

					// Add loader in top navigation
					$(".mfTopNavigation_wrap").addClass('NavTopULloading');

					// Move left navigation
					var righty = $('.mfLeftNavigation_wrap');
					var move_right = slide_count * 100;
					$('.NavLeftUL_item').removeClass('li_selected');

					righty.animate(
						{ left:"+="+move_right+"%" },
						{ queue: false, complete: function(){
							if (this_liclass.indexOf("NavTopUL_firstChild") !== -1)
							{
								resetTopics(event, href);
							}

							var this_id = this_liid.split("_").pop(0);
							if (this_id === 'home')
							{
								$('#NavLeftUL ul').removeClass('NavLeftUL_expanded');
							}
							else
							{
								$('#'+this_id+' ul ul').removeClass('NavLeftUL_expanded');
							}

							// Load topic content
							var topic_id = this_liid.split("id").pop(1);
							var left_liid = 'liid'+topic_id;
							var href_link = $('#NavLeftUL').find('#'+left_liid+' > .NavLeftUL_anchor').attr('href');
							var cat_title = $('#NavLeftUL').find('#'+left_liid+' > .NavLeftUL_anchor span.catTitle').text();

							loadEndpoint(topic_id, left_liid, href_link, cat_title);
						}
					});
				}
			}

			// Home link
			if (leftnav > 0)
			{
				if ($(this).hasClass('NavTopUL_firstChild') && $('.mfLeftNavigation_wrap:animated').length == 0 && !$('#NavLeftUL').hasClass('ul_loading') && !$('.mfTopNavigation_wrap').hasClass('NavTopULloading'))
				{
					resetTopics(event, href);
				}
			}
			else
			{
				if ($(this).hasClass('NavTopUL_firstChild') && !$('.mfTopNavigation_wrap').hasClass('NavTopULloading'))
				{
					resetTopics(event, href);
				}
			}
		});

		// Hide/Show menu button / Show left navigation
		var show_leftnav = $('.show_menu').on('click', function(event)
		{
			event.preventDefault();

			$(this).find('a:first').toggleClass('btn-mfactive');
			$('.mfLeftNavigation_core').toggleClass('mf-hidden');
		});

		// Ajax pagination - Topic
		$('.mfContent_core').on('click', 'a.mfContent_paging_button', function(event)
		{
			event.preventDefault();

			if (!$(this).hasClass('mfContent_btn_disabled'))
			{
				$(this).addClass('page_loading');
				$(this).find('.mfContent_paging_text').hide();
				$(this).find('.mfContent_paging_loader').css('display', 'inline-block');
				$(this).addClass('mfContent_btn_disabled');

				// Get page
				var page = $(this).attr('data-page');
				page = parseInt(page, 10);

				// Get topic
				var topic_id = $(this).attr('data-topic');

				// Check if there is a pending ajax request
				if(typeof paging_ajax_request !== "undefined")
				paging_ajax_request.abort();

				paging_ajax_request = $.ajax({
					type: "POST",
					url: ajax_url,
					data: {
						action: 'mf_topic_load_more',
						page: ''+page+'',
						topic: ''+topic_id+'',
					},
					success: function(msg) {
						try {
							msg = JSON.parse(msg);
						} catch (e) {
							console.log(msg);
							return false;
						}

						$('#mfPaging_'+topic_id).find('.mfContent_paging_loader').hide();

						if (msg.questions)
						{
							$('#mfTopic_'+topic_id).append(msg.questions);
							$('#mfPaging_'+topic_id).find('.mfContent_paging_text').show();
							$('#mfPaging_'+topic_id+' .mfContent_paging_button').removeClass('mfContent_btn_disabled');

							// Increment data-page
							var new_page = page + 1;
							$('#mfPaging_'+topic_id+' .mfContent_paging_button').attr('data-page', new_page);

							// Activate actions in questions
							activateQuestions();

							if (msg.remaining == 0)
							{
								$('#mfPaging_'+topic_id).find('.mfContent_paging_text').hide();
								$('#mfPaging_'+topic_id).find('.mfContent_noresults').show();
								$('#mfPaging_'+topic_id+' .mfContent_paging_button').addClass('mfContent_btn_disabled');
							}
						}

						$('#mfPaging_'+topic_id+' .mfContent_paging_button').removeClass('page_loading');
					},
					error: function(XMLHttpRequest, textStatus, errorThrown) {
						console.log(errorThrown);
					}
				});
			}
		});

		// Handle cb in My Questions list
		$("#mf-questions-list-form").on('change', '#cb-select-all-1', function()
		{
			$('#mf-questions-list-form input:checkbox').not(this).prop('checked', this.checked);
		});

		$('#mf-questions-list-form tbody input:checkbox').change(function () {
			if ($('#mf-questions-list-form tbody input:checkbox:checked').length == $('#mf-questions-list-form tbody input:checkbox').length){
				$('#mf-questions-list-form thead input:checkbox').prop('checked',true);
			}
			else {
				$('#mf-questions-list-form thead input:checkbox').prop('checked',false);
			}
		});

	})

})( jQuery );
