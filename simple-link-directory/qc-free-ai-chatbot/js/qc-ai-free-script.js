(function($) {
    'use strict';

	jQuery(document).ready(function($){

		$( window ).on( "load", function() {

			var data = {
				'action': 'qcopd_sld_free_ai_function_first_sld_ajax',
				'security': qcopd_sld_free_ai_ajax_nonce
			};

	        jQuery.post(qcopd_sld_free_ai_ajaxurl, data, function (response) {

	        	$('.qcopd_sld_free_ai_loading').remove();

	          // 	$('.qcopd_sld_free_ai-grid').find('.qcopd_sld_free_ai-card').html(response);
	           	$('.qcopd_sld_free_ai-grid').find('.qcopd_sld_free_ai-card').append(response);


	        });

		});


	});


})(jQuery);