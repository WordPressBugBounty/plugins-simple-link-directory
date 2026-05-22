jQuery(document).ready(function ($) {

	//sld_list_elements
	$('#sld_width_div').css('display', 'none');



	$('.sld-feature-accordion-header, .qc-feature-accordion-header').on('click', function () {
		$(this).parent().toggleClass('active').siblings().removeClass('active');
	});

	var sld_list_elements = $('#qcopd_list_item01 .field-item[data-class="CMB_Group_Field"]').not(':hidden').length;
	$('.qcld_alert_msg_modal').css({ 'display': 'none' });
	if (sld_list_elements === 1) {
		$('.qcld_alert_msg_modal').css({ 'display': 'block' });
	}

	$(document).on('click', '.sld_alert_msg_close', function () {
		$('.qcld_alert_msg_modal').css({ 'display': 'none' });
	})

	$(document).on('click', '.sld_add_more_item', function () {
		$('.qcld_alert_msg_modal').css({ 'display': 'none' });
		var scroll_top = $("body").get(0).scrollHeight;
		var scroll_tops = $(".sld_list_elements").get(0).scrollHeight;
		$("html, body").animate({ scrollTop: scroll_top - scroll_tops }, 1500);
		$('.sld_list_elements .button.repeat-field').trigger('click');
	})


	var storedNoticeId = localStorage.getItem('qcld_sld_Notice_set');
	var qcld_sld_Notice_time_set = localStorage.getItem('qcld_sld_Notice_time_set');

	var notice_current_time = Math.round(new Date().getTime() / 1000);

	if ('sld-msg' == storedNoticeId && qcld_sld_Notice_time_set > notice_current_time) {
		$('#message-sld').css({ 'display': 'none' });
	}

	$(document).on('click', '#message-sld .notice-dismiss', function (e) {

		var currentDom = $(this);
		var currentWrap = currentDom.closest('.notice');
		currentWrap.css({ 'display': 'none' });
		localStorage.setItem('qcld_sld_Notice_set', 'sld-msg');

		var ts = Math.round(new Date().getTime() / 1000);
		var tsYesterday = ts + (24 * 3600);
		localStorage.setItem('qcld_sld_Notice_time_set', tsYesterday);
		//console.log(tsYesterday)

	});


	$('#sld_shortcode_generator_meta').on('click', function (e) {

		$('#sld_shortcode_generator_meta').prop('disabled', true);
		$.post(
			ajaxurl,
			{
				action: 'show_qcsld_shortcodes'

			},
			function (data) {
				$('#sld_shortcode_generator_meta').prop('disabled', false);
				$('#wpwrap').append(data);
			}
		)
	})

	var selector = '';

	$(document).on('click', '.sld_copy_close', function () {
		$(this).parent().parent().parent().parent().parent().remove();
	})

	$(document).on('click', '.modal-content .close', function () {
		$(this).parent().parent().remove();
	}).on('click', '#qcsld_add_shortcode', function () {

		var mode = $('#sld_mode').val();
		var column = $('#sld_column').val();
		//var sld_width = $('#sld_width').val();
		var style = $('#sld_style').val();
		var upvote = $('.sld_upvote:checked').val();
		var search = $('.sld_search:checked').val();
		var embeding = $('.sld_embeding:checked').val();
		var count = $('.sld_item_count:checked').val();
		var orderby = $('#sld_orderby').val();
		var order = $('#sld_order').val();
		var title_font_size = $('#sld_title_font_size').val();
		var subtitle_font_size = $('#sld_subtitle_font_size').val();
		var title_line_height = $('#sld_title_line_height').val();
		var subtitle_line_height = $('#sld_subtitle_line_height').val();
		var sld_itemorderby = $('#sld_itemorderby').val();

		var listId = $('#sld_list_id').val();
		var catSlug = $('#sld_list_cat_id').val();

		var shortcodedata = '[qcopd-directory';

		if (mode !== 'category') {
			shortcodedata += ' mode="' + mode + '"';
		}

		if (mode == 'one' && listId != "") {
			shortcodedata += ' list_id="' + listId + '"';
		}


		if (mode == 'category' && catSlug != "") {
			shortcodedata += ' category="' + catSlug + '"';
		}

		if (style !== '') {
			shortcodedata += ' style="' + style + '"';
		}

		var style = $('#sld_style').val();

		if (style == 'simple' || style == 'style-1' || style == 'style-2' || style == 'style-16' || style == 'style-8' || style == 'style-9') {

			if (column !== '') {
				shortcodedata += ' column="' + column + '"';
			}

		}

		if (sld_itemorderby !== '') {
			shortcodedata += ' item_orderby="' + sld_itemorderby + '"';
		}

		if (typeof (upvote) != 'undefined') {
			shortcodedata += ' upvote="' + upvote + '"';
		}

		if (typeof (search) != 'undefined') {
			shortcodedata += ' search="' + search + '"';
		}
		if (typeof (embeding) != 'undefined') {
			shortcodedata += ' enable_embedding="' + embeding + '"';
		} else {
			shortcodedata += ' enable_embedding="false"';
		}

		if (typeof (count) != 'undefined') {
			shortcodedata += ' item_count="' + count + '"';
		}

		if (orderby !== '' && mode !== 'one') {
			shortcodedata += ' orderby="' + orderby + '"';
		}

		if (order !== '' && mode !== 'one') {
			shortcodedata += ' order="' + order + '"';
		}

		if (typeof (title_font_size) != 'undefined' || title_font_size != '') {
			shortcodedata += ' title_font_size="' + title_font_size + '"';
		}
		if (typeof (subtitle_font_size) != 'undefined' || subtitle_font_size != '') {
			shortcodedata += ' subtitle_font_size="' + subtitle_font_size + '"';
		}
		if (typeof (title_line_height) != 'undefined' || title_line_height != '') {
			shortcodedata += ' title_line_height="' + title_line_height + '"';
		}
		if (typeof (subtitle_line_height) != 'undefined' || subtitle_line_height != '') {
			shortcodedata += ' subtitle_line_height="' + subtitle_line_height + '"';
		}

		//if( sld_width !== '' ){
		//  shortcodedata +=' min_width="'+sld_width+'px"';
		//}

		shortcodedata += ']';

		/*tinyMCE.activeEditor.selection.setContent(shortcodedata);
	    
		$('#sm-modal').remove();*/

		$('.sm_shortcode_list').hide();
		$('.sld_shortcode_container').show();
		$('#sld_shortcode_container').val(shortcodedata);
		$('.sld_copy_close').attr('short-data', shortcodedata);
		$('#sld_shortcode_container').select();
		document.execCommand('copy');


	}).on('change', '#sld_mode', function () {

		var mode = $('#sld_mode').val();

		if (mode == 'one') {
			$('#sld_list_div').css('display', 'block');
			$('#sld_list_cat').css('display', 'none');
			$('#sld_orderby_div').css('display', 'none');
			$('#sld_order_div').css('display', 'none');
		}
		else if (mode == 'category') {
			$('#sld_list_cat').css('display', 'block');
			$('#sld_list_div').css('display', 'none');
			$('#sld_orderby_div').css('display', 'block');
			$('#sld_order_div').css('display', 'block');
		}
		else {
			$('#sld_list_div').css('display', 'none');
			$('#sld_list_cat').css('display', 'none');
			$('#sld_orderby_div').css('display', 'block');
			$('#sld_order_div').css('display', 'block');
		}

	}).on('change', '#sld_style', function () {

		var style = $('#sld_style').val();

		if (style == 'simple' || style == 'style-1' || style == 'style-16') {
			$('#sld_column_div').css('display', 'block');

		}
		else {
			$('#sld_column_div').css('display', 'none');
		}

		if (style == 'simple') {
			$('#demo-preview-link #demo-url').html('<a href="http://dev.quantumcloud.com/sld/" target="_blank">http://dev.quantumcloud.com/sld/</a>');
		}
		else if (style == 'style-1') {
			$('#demo-preview-link #demo-url').html('<a href="http://dev.quantumcloud.com/sld/style-1/" target="_blank">http://dev.quantumcloud.com/sld/style-1/</a>');
		}
		else if (style == 'style-2') {
			$('#demo-preview-link #demo-url').html('<a href="http://dev.quantumcloud.com/sld/style-2/" target="_blank">http://dev.quantumcloud.com/sld/style-3/</a>');
		}
		else if (style == 'style-3') {
			$('#demo-preview-link #demo-url').html('<a href="http://dev.quantumcloud.com/sld/style-3/" target="_blank">http://dev.quantumcloud.com/sld/style-5/</a>');
		}
		else {
			$('#demo-preview-link #demo-url').html('<a href="http://dev.quantumcloud.com/sld/" target="_blank">http://dev.quantumcloud.com/sld/</a>');
		}

		if (style == 'style-1') {
			$('#sld_column option[value="3"]').hide();
			$('#sld_column option[value="4"]').hide();

		} else {
			$('#sld_column option[value="3"]').show();
			$('#sld_column option[value="4"]').show();

		}

	}).on('change', '#sld_column', function () {

		var sld_column = $('#sld_column').val();

		// if( sld_column == 3 || sld_column == 4 ){
		// 	$('#sld_width_div').css('display', 'block');
		// }else{
		// 	$('#sld_width_div').css('display', 'none');
		// }


	});

	$(document).on('click', ' .modal-content .close', function () {
		$(this).parent().parent().remove();
	});



	$(document).on('click', '#sld-start-import-btn', function (e) {
		e.preventDefault();

		var uploadBtn = $(this);
		var messageArea = $('#sld-import-message');

		// Data to send via AJAX
		var data = {
			'action': 'qcld_sld_import_csv_from_folder',
			'security': sld_ajax_object.ajax_nonce,
		};

		$.ajax({
			url: ajaxurl,
			data: data,
			dataType: 'json',
			type: 'POST',
			beforeSend: function () {

				uploadBtn.prop('disabled', true).text('Importing...');
				messageArea.html('<p>Importing data, please wait...</p>');

			},
			success: function (response) {
				if (response.success) {
					messageArea.html('<p style="color: green;">' + response.data.message + '</p>');
					// Redirect to the newly created page
					//window.location.replace(response.data.redirect_url);
					window.open(response.data.redirect_url)
				} else {
					messageArea.html('<p style="color: red;">Error: ' + response.data + '</p>');
					uploadBtn.prop('disabled', false).text('Click to Import Data');
				}

			},
			error: function (xhr, status, errorThrown) {
				uploadBtn.prop('disabled', false).text('Click to Import Data');
				messageArea.html('<p style="color: red;"><strong>Something went wrong:</strong> ' + errorThrown + '</p>');
				window.location.reload();
			}
		});


	});







});


function isGutenbergActive() {
	return typeof wp !== 'undefined' && typeof wp.blocks !== 'undefined';
}

jQuery(document).ready(function ($) {

	if ($('.sld-Getting-Started').length > 0) {
		$('.sld-Getting-Started').show();
		$('.sld_Started_carousel').not('.slick-initialized').slick({
			dots: false,
			infinite: true,
			speed: 1200,
			slidesToShow: 1,
			autoplaySpeed: 3000,
			autoplay: true,
			slidesToScroll: 1,
			//variableWidth: true,
			adaptiveHeight: true,



		});
	}
	$(document).on('click', '#Getting_Started', function () {
		$('.sld-Getting-Started').show();
		if ($('.sld_Started_carousel').hasClass('slick-initialized')) {
			$('.sld_Started_carousel').slick('setPosition');
		} else {
			$('.sld_Started_carousel').slick({
				dots: false,
				infinite: true,
				speed: 1200,
				slidesToShow: 1,
				autoplaySpeed: 3000,
				autoplay: true,
				slidesToScroll: 1,
				variableWidth: true,
				adaptiveHeight: true,


			});
		}
	});
	$(document).on('click', '.qcld_getting_started', function () {
		$('.sld-Getting-Started').show();
		if ($('.sld_Started_carousel').hasClass('slick-initialized')) {
			$('.sld_Started_carousel').slick('setPosition');
		} else {
			$('.sld_Started_carousel').slick({
				dots: false,
				infinite: true,
				speed: 1200,
				slidesToShow: 1,
				autoplaySpeed: 3000,
				autoplay: true,
				slidesToScroll: 1,
				variableWidth: true,
				adaptiveHeight: true,


			});
		}
	});


	if ($('.sld-notice').length > 0) {
		$('.sld-notice').show();
		$('.sld_info_carousel').not('.slick-initialized').slick({
			dots: false,
			infinite: true,
			speed: 1200,
			slidesToShow: 1,
			autoplaySpeed: 3000,
			autoplay: true,
			slidesToScroll: 1,

		});
	}


	$('.sld_click_handle').on('click', function (e) {
		e.preventDefault();
		var obj = $(this);
		container_id = obj.attr('href');
		$('.sld_click_handle').each(function () {
			$(this).removeClass('nav-tab-active');
			$($(this).attr('href')).hide();
		})
		obj.addClass('nav-tab-active');
		$(container_id).show();
		localStorage.setItem('sld_active_tab', container_id);
		window.history.replaceState(null, null, container_id);

		// Update _wp_http_referer input value
		var refererInput = $('.sld-dashboard-wrap form').find('input[name="_wp_http_referer"]');
		if (refererInput.length > 0) {
			var currentVal = refererInput.val().split('#')[0];
			refererInput.val(currentVal + container_id);
		}

		if (container_id === '#custom_js' && window.sld_custom_js_editor) {
			setTimeout(function () { window.sld_custom_js_editor.codemirror.refresh(); }, 50);
		}
		if (container_id === '#custom_css' && window.sld_custom_style_editor) {
			setTimeout(function () { window.sld_custom_style_editor.codemirror.refresh(); }, 50);
		}
	})
	function sld_handle_hash() {
		var hash = window.location.hash || localStorage.getItem('sld_active_tab');
		if (!hash) {
			hash = '#getting_started';
		}
		if ($(hash).length > 0 && $('.sld_click_handle[href="' + hash + '"]').length > 0) {
			$('.sld_click_handle').each(function () {
				$($(this).attr('href')).hide();
				$(this).removeClass('nav-tab-active');
			});
			$(hash).show();
			$('.sld_click_handle[href="' + hash + '"]').addClass('nav-tab-active');

			// Update referer input value
			var refererInput = $('.sld-dashboard-wrap form').find('input[name="_wp_http_referer"]');
			if (refererInput.length > 0) {
				var currentVal = refererInput.val().split('#')[0];
				refererInput.val(currentVal + hash);
			}

			if (hash === '#getting_started' && $('.sld_Started_carousel').hasClass('slick-initialized')) {
				$('.sld_Started_carousel').slick('setPosition');
			}
			if (hash === '#custom_js' && window.sld_custom_js_editor) {
				setTimeout(function () { window.sld_custom_js_editor.codemirror.refresh(); }, 50);
			}
			if (hash === '#custom_css' && window.sld_custom_style_editor) {
				setTimeout(function () { window.sld_custom_style_editor.codemirror.refresh(); }, 50);
			}
		}
	}

	// Update referer value on form submit
	$(document).on('submit', '.sld-dashboard-wrap form', function () {
		var activeTab = localStorage.getItem('sld_active_tab') || window.location.hash || '#getting_started';
		var refererInput = $(this).find('input[name="_wp_http_referer"]');
		if (refererInput.length > 0 && activeTab) {
			var currentVal = refererInput.val().split('#')[0];
			refererInput.val(currentVal + activeTab);
		}
	});

	$(window).on('hashchange', function () {
		sld_handle_hash();
	});

	sld_handle_hash();


	$('.sld_help_links').on('click', function (e) {
		e.preventDefault();
		var obj = $(this);
		container_id = obj.attr('href');
		window.history.replaceState(null, null, container_id);
		location.reload(true);
	});

	$(".qcld_short_genarator_scroll").click(function () {
		$("html, body").animate({ scrollTop: $(".qcld_short_genarator_scroll_wrap").offset().top }, 1500);
	});

	jQuery(document).ready(function ($) {
		jQuery('.qcld-sldquick-flyout').on('click', function () {
			jQuery(this).toggleClass('is-open');
		});
	});

	jQuery(document).ready(function ($) {
		$('.qcld-sldquick-flyout').on('click', function () {
			$('body').toggleClass('qcld-sld-flyout');
		});
	});

	function toggle_ai_settings_fields() {
		var selected = $('input[name="sld_enable_ai_provider"]:checked').val() || 'none';
		if (selected === 'openai') {
			$('.sld-openai-settings-row').show();
			$('.sld-gemini-settings-row').hide();
			$('.sld-openrouter-settings-row').hide();
			$('.sld-ai-general-settings-row').show();
		} else if (selected === 'gemini') {
			$('.sld-openai-settings-row').hide();
			$('.sld-gemini-settings-row').show();
			$('.sld-openrouter-settings-row').hide();
			$('.sld-ai-general-settings-row').show();
		} else if (selected === 'openrouter') {
			$('.sld-openai-settings-row').hide();
			$('.sld-gemini-settings-row').hide();
			$('.sld-openrouter-settings-row').show();
			$('.sld-ai-general-settings-row').show();
		} else {
			$('.sld-openai-settings-row').hide();
			$('.sld-gemini-settings-row').hide();
			$('.sld-openrouter-settings-row').hide();
			$('.sld-ai-general-settings-row').hide();
		}
	}

	$(document).on('change', 'input[name="sld_enable_ai_provider"]', function () {
		$('.sld-ai-provider-card').removeClass('active');
		$(this).closest('.sld-ai-provider-card').addClass('active');
		toggle_ai_settings_fields();
	});

	// Trigger on load
	toggle_ai_settings_fields();

	// Test OpenAI Connection
	$(document).on('click', '#sld_test_openai_btn', function (e) {
		e.preventDefault();
		var apiKey = $('#sld_openai_api_key').val().trim();
		var statusSpan = $('#sld_openai_test_status');
		var modelSelect = $('#sld_openai_model');

		if (!apiKey) {
			statusSpan.css('color', 'red').html('Please enter an API Key first. You can find your API key <a href="https://platform.openai.com/api-keys" target="_blank" style="text-decoration: underline; color: #ef4444;">here</a>.');
			return;
		}

		statusSpan.css('color', '#666').text('Connecting...');

		$.ajax({
			url: ajaxurl,
			type: 'POST',
			data: {
				action: 'sld_test_openai_connection',
				api_key: apiKey
			},
			success: function (response) {
				if (response.success) {
					statusSpan.css('color', 'green').text(response.data.message);

					// Update models dropdown
					if (response.data.models && response.data.models.length > 0) {
						var currentSelected = modelSelect.val();
						modelSelect.empty();
						$.each(response.data.models, function (index, modelId) {
							var selectedAttr = (modelId === currentSelected) ? ' selected="selected"' : '';
							modelSelect.append('<option value="' + modelId + '"' + selectedAttr + '>' + modelId + '</option>');
						});
					}
				} else {
					var err = response.data.message || 'Error occurred.';
					statusSpan.css('color', 'red').html(err + ' You can find your API key <a href="https://platform.openai.com/api-keys" target="_blank" style="text-decoration: underline; color: #ef4444;">here</a>.');
				}
			},
			error: function () {
				statusSpan.css('color', 'red').text('Request failed. Please check network/server logs.');
			}
		});
	});

	// Test Gemini Connection
	$(document).on('click', '#sld_test_gemini_btn', function (e) {
		e.preventDefault();
		var apiKey = $('#sld_gemini_api_key').val().trim();
		var statusSpan = $('#sld_gemini_test_status');
		var modelSelect = $('#sld_gemini_model');

		if (!apiKey) {
			statusSpan.css('color', 'red').html('Please enter an API Key first. You can find your API key <a href="https://aistudio.google.com/app/apikey" target="_blank" style="text-decoration: underline; color: #ef4444;">here</a>.');
			return;
		}

		statusSpan.css('color', '#666').text('Connecting...');

		$.ajax({
			url: ajaxurl,
			type: 'POST',
			data: {
				action: 'sld_test_gemini_connection',
				api_key: apiKey
			},
			success: function (response) {
				if (response.success) {
					statusSpan.css('color', 'green').text(response.data.message);

					// Update models dropdown
					if (response.data.models && response.data.models.length > 0) {
						var currentSelected = modelSelect.val();
						modelSelect.empty();
						$.each(response.data.models, function (index, modelId) {
							var selectedAttr = (modelId === currentSelected) ? ' selected="selected"' : '';
							modelSelect.append('<option value="' + modelId + '"' + selectedAttr + '>' + modelId + '</option>');
						});
					}
				} else {
					var err = response.data.message || 'Error occurred.';
					statusSpan.css('color', 'red').html(err + ' You can find your API key <a href="https://aistudio.google.com/app/apikey" target="_blank" style="text-decoration: underline; color: #ef4444;">here</a>.');
				}
			},
			error: function () {
				statusSpan.css('color', 'red').text('Request failed. Please check network/server logs.');
			}
		});
	});

	// Test OpenRouter Connection
	$(document).on('click', '#sld_test_openrouter_btn', function (e) {
		e.preventDefault();
		var apiKey = $('#sld_openrouter_api_key').val().trim();
		var statusSpan = $('#sld_openrouter_test_status');
		var modelSelect = $('#sld_openrouter_model');

		if (!apiKey) {
			statusSpan.css('color', 'red').html('Please enter an API Key first. You can find your API key <a href="https://openrouter.ai/keys" target="_blank" style="text-decoration: underline; color: #ef4444;">here</a>.');
			return;
		}

		statusSpan.css('color', '#666').text('Connecting...');

		$.ajax({
			url: ajaxurl,
			type: 'POST',
			data: {
				action: 'sld_test_openrouter_connection',
				api_key: apiKey
			},
			success: function (response) {
				if (response.success) {
					statusSpan.css('color', 'green').text(response.data.message);

					// Update models dropdown
					if (response.data.models && response.data.models.length > 0) {
						var currentSelected = modelSelect.val();
						modelSelect.empty();
						$.each(response.data.models, function (index, model) {
							var selectedAttr = (model.id === currentSelected) ? ' selected="selected"' : '';
							modelSelect.append('<option value="' + model.id + '"' + selectedAttr + '>' + model.name + '</option>');
						});
					}
				} else {
					var err = response.data.message || 'Error occurred.';
					statusSpan.css('color', 'red').html(err + ' You can find your API key <a href="https://openrouter.ai/keys" target="_blank" style="text-decoration: underline; color: #ef4444;">here</a>.');
				}
			},
			error: function () {
				statusSpan.css('color', 'red').text('Request failed. Please check network/server logs.');
			}
		});
	});

	// AI Generator Sidebar widget handler
	$(document).on('click', '#sld_btn_ai_generate', function (e) {
		e.preventDefault();
		var prompt = $('#sld_ai_prompt').val();
		var count = $('#sld_ai_count').val();
		var button = $(this);
		var spinner = button.find('.sld-spinner-icon');
		var btnText = button.find('.sld-btn-text');
		var statusDiv = $('#sld_ai_gen_status');

		if (!prompt.trim()) {
			statusDiv.css('color', 'red').text('Please enter a prompt/topic.');
			return;
		}

		button.attr('disabled', 'disabled');
		spinner.css('display', 'inline-block');
		btnText.text('Generating...');
		statusDiv.css('color', '#4f46e5').text('Connecting to AI...');

		$.ajax({
			url: ajaxurl,
			type: 'POST',
			data: {
				action: 'sld_ai_generate_list_items',
				prompt: prompt,
				count: count
			},
			success: function (response) {
				button.removeAttr('disabled');
				spinner.hide();
				btnText.text('Generate & Add Items');

				if (response.success) {
					statusDiv.css('color', 'green').text(response.data.message);

					var postTitleField = $('#title');
					if (postTitleField.length && !postTitleField.val().trim()) {
						postTitleField.val(prompt);
						postTitleField.trigger('change');
						$('#title-prompt-text').addClass('screen-reader-text');
					}

					var itemsToInsert = response.data.items;
					var activeItems = $('#qcopd_list_item01 .field-item[data-class="CMB_Group_Field"]').not('.hidden');
					var firstItemEmpty = false;

					if (activeItems.length === 1) {
						var firstTitle = activeItems.first().find('input[name*="[qcopd_item_title]"]').val();
						var firstLink = activeItems.first().find('input[name*="[qcopd_item_link]"]').val();
						if (!firstTitle && !firstLink) {
							firstItemEmpty = true;
						}
					}

					$.each(itemsToInsert, function (index, item) {
						var targetItem;
						if (index === 0 && firstItemEmpty) {
							targetItem = activeItems.first();
						} else {
							$('#qcopd_list_item01 .repeat-field').first().trigger('click');
							targetItem = $('#qcopd_list_item01 .field-item[data-class="CMB_Group_Field"]').not('.hidden').last();
						}

						targetItem.find('input[name*="[qcopd_item_title]"]').val(item.title || '');
						targetItem.find('input[name*="[qcopd_item_link]"]').val(item.link || '');
						targetItem.find('input[name*="[qcopd_item_subtitle]"]').val(item.subtitle || '');

						var now = new Date();
						var formattedDate = now.getFullYear() + '-' +
							('0' + (now.getMonth() + 1)).slice(-2) + '-' +
							('0' + now.getDate()).slice(-2) + ' ' +
							('0' + now.getHours()).slice(-2) + ':' +
							('0' + now.getMinutes()).slice(-2) + ':' +
							('0' + now.getSeconds()).slice(-2);

						targetItem.find('input[name*="[qcopd_entry_time]"]').val(formattedDate);
						targetItem.find('input[name*="[qcopd_upvote_count]"]').val('0');
					});

					// Scroll to the CMB list elements wrapper
					$('html, body').animate({
						scrollTop: $("#qcopd_list_item01").offset().top - 50
					}, 1000);

					// Automatically save the post if option is enabled
					var autoSaveEnabled = $('#sld_ai_auto_save_val').val();
					if (autoSaveEnabled === '1') {
						setTimeout(function () {
							statusDiv.css('color', '#4f46e5').text('Saving directory...');
							if ($('#publish').length) {
								$('#publish').trigger('click');
							} else if ($('#post').length) {
								$('#post').submit();
							}
						}, 1200);
					}

				} else {
					statusDiv.css('color', 'red').text(response.data.message || 'Generation failed.');
				}
			},
			error: function () {
				button.removeAttr('disabled');
				spinner.hide();
				btnText.text('Generate & Add Items');
				statusDiv.css('color', 'red').text('Request failed. Please check network logs.');
			}
		});
	});

	// Copy Shortcode Clipboard copy handler
	$(document).on('click', '.sld-copy-btn', function (e) {
		e.preventDefault();
		var btn = $(this);
		var shortcode = btn.data('shortcode');
		var tooltip = btn.find('.sld-copy-tooltip');

		if (navigator.clipboard && window.isSecureContext) {
			navigator.clipboard.writeText(shortcode).then(function () {
				showTooltip(btn, tooltip);
			}).catch(function () {
				fallbackCopy(btn, tooltip, shortcode);
			});
		} else {
			fallbackCopy(btn, tooltip, shortcode);
		}
	});

	function showTooltip(btn, tooltip) {
		tooltip.fadeIn(200);
		btn.css('border-color', '#4f46e5');
		setTimeout(function () {
			tooltip.fadeOut(200);
			btn.css('border-color', '#cbd5e1');
		}, 1500);
	}

	function fallbackCopy(btn, tooltip, text) {
		var textArea = document.createElement("textarea");
		textArea.value = text;
		textArea.style.position = "fixed";
		document.body.appendChild(textArea);
		textArea.focus();
		textArea.select();
		try {
			document.execCommand('copy');
			showTooltip(btn, tooltip);
		} catch (err) {
			console.error('Fallback copy failed', err);
		}
		document.body.removeChild(textArea);
	}

	// Reset Custom Prompt to Default Handler
	$(document).on('click', '#sld_reset_ai_prompt_btn', function (e) {
		e.preventDefault();
		var btn = $(this);
		var statusSpan = $('#sld_reset_ai_prompt_status');
		var textarea = $('#sld_ai_prompt_instruction');

		btn.attr('disabled', 'disabled');
		statusSpan.css('color', '#666').text('Resetting...');

		$.ajax({
			url: ajaxurl,
			type: 'POST',
			data: {
				action: 'sld_reset_ai_prompt_instruction'
			},
			success: function (response) {
				btn.removeAttr('disabled');
				if (response.success) {
					textarea.val(response.data.default_prompt);
					statusSpan.css('color', 'green').text(response.data.message);
					setTimeout(function () {
						statusSpan.fadeOut(500, function () {
							$(this).text('').show();
						});
					}, 2000);
				} else {
					statusSpan.css('color', 'red').text(response.data.message || 'Error occurred.');
				}
			},
			error: function () {
				btn.removeAttr('disabled');
				statusSpan.css('color', 'red').text('Request failed. Please check network logs.');
			}
		});
	});

});
