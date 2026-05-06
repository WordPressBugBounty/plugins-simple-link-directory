(function($) {
    'use strict';

	jQuery(document).ready(function($){

		$( window ).on( "load", function() {

			var data = {
				'action': 'qc_sld_free_ai_function_first_sld_ajax',
				'security': qc_sld_free_ai_ajax_nonce
			};

	        jQuery.post(qc_sld_free_ai_ajaxurl, data, function (response) {

	        	$('.qc_sld_free_ai_loading').remove();

	          // 	$('.qc_sld_free_ai-grid').find('.qc_sld_free_ai-card').html(response);
	           	$('.qc_sld_free_ai-grid').find('.qc_sld_free_ai-card').append(response);


	        });

		});


	});


})(jQuery);