jQuery(document).ready(function($){

	//sld_list_elements
	$('#sld_width_div').css('display', 'none');

	var sld_list_elements = $('#qcopd_list_item01 .field-item[data-class="CMB_Group_Field"]').not(':hidden').length;
	$('.qcld_alert_msg_modal').css({'display': 'none'});
	if ( sld_list_elements === 1  ) {
        $('.qcld_alert_msg_modal').css({'display': 'block'});
    }

	$(document).on( 'click', '.sld_alert_msg_close', function(){
        $('.qcld_alert_msg_modal').css({'display': 'none'});
    })

	$(document).on( 'click', '.sld_add_more_item', function(){
        $('.qcld_alert_msg_modal').css({'display': 'none'});
        var scroll_top = $("body").get(0).scrollHeight;
        var scroll_tops = $(".sld_list_elements").get(0).scrollHeight;
        $("html, body").animate({ scrollTop: scroll_top - scroll_tops }, 1500);
        $('.sld_list_elements .button.repeat-field').trigger('click');
    })
	

    var storedNoticeId = localStorage.getItem('qcld_sld_Notice_set');
    var qcld_sld_Notice_time_set = localStorage.getItem('qcld_sld_Notice_time_set');

    var notice_current_time = Math.round(new Date().getTime() / 1000);

    if ('sld-msg' == storedNoticeId && qcld_sld_Notice_time_set > notice_current_time  ) {
        $('#message-sld').css({'display': 'none'});
    }

    $(document).on('click', '#message-sld .notice-dismiss', function(e){

        var currentDom = $(this);
        var currentWrap = currentDom.closest('.notice');
        currentWrap.css({'display': 'none'});
        localStorage.setItem('qcld_sld_Notice_set', 'sld-msg');

        var ts = Math.round(new Date().getTime() / 1000);
        var tsYesterday = ts + (24 * 3600);
        localStorage.setItem('qcld_sld_Notice_time_set', tsYesterday);
        //console.log(tsYesterday)

    });


	$('#sld_shortcode_generator_meta').on('click', function(e){
		
		$('#sld_shortcode_generator_meta').prop('disabled', true);
		$.post(
			ajaxurl,
			{
				action : 'show_qcsld_shortcodes'
				
			},
			function(data){
				$('#sld_shortcode_generator_meta').prop('disabled', false);
				$('#wpwrap').append(data);
			}
		)
	})
	
	var selector = '';

	$(document).on( 'click', '.sld_copy_close', function(){
        	$(this).parent().parent().parent().parent().parent().remove();
    })
	
    $(document).on( 'click', '.modal-content .close', function(){
        	$(this).parent().parent().remove();
    }).on( 'click', '#qcsld_add_shortcode',function(){
	
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
		  		  
		  if( mode !== 'category' ){
			  shortcodedata +=' mode="'+mode+'"';
		  }
		  
		  if( mode == 'one' && listId != "" ){
			  shortcodedata +=' list_id="'+listId+'"';
		  }
		  
		  
		  if( mode == 'category' && catSlug != "" ){
			  shortcodedata +=' category="'+catSlug+'"';
		  }
		  
		  if( style !== '' ){
			  shortcodedata +=' style="'+style+'"';
		  }
		  
		  var style = $('#sld_style').val();
		
		  if( style == 'simple' || style == 'style-1' || style == 'style-2' || style == 'style-16' || style == 'style-8' || style == 'style-9' ){
		  
			  if( column !== '' ){
				  shortcodedata +=' column="'+column+'"';
			  }
		  
		  }
		  
		  if( sld_itemorderby !== '' ){
			  shortcodedata +=' item_orderby="'+sld_itemorderby+'"';
		  }
		  
		  if( typeof(upvote) != 'undefined' ){
			  shortcodedata +=' upvote="'+upvote+'"';
		  }
		  
		  if( typeof(search)!= 'undefined' ){
			  shortcodedata +=' search="'+search+'"';
		  }
		  if( typeof(embeding)!= 'undefined' ){
			  shortcodedata +=' enable_embedding="'+embeding+'"';
		  }else{
			  shortcodedata +=' enable_embedding="false"';
		  }
		  
		  if( typeof(count)!= 'undefined' ){
			  shortcodedata +=' item_count="'+count+'"';
		  }
		  
		  if( orderby !== '' && mode!=='one'){
			  shortcodedata +=' orderby="'+orderby+'"';
		  }
		  
		  if( order !== '' && mode!=='one'){
			  shortcodedata +=' order="'+order+'"';
		  }

        if(typeof(title_font_size)!='undefined' || title_font_size!=''){
            shortcodedata +=' title_font_size="'+title_font_size+'"';
        }
        if(typeof(subtitle_font_size)!='undefined' || subtitle_font_size!=''){
            shortcodedata +=' subtitle_font_size="'+subtitle_font_size+'"';
        }
        if(typeof(title_line_height)!='undefined' || title_line_height!=''){
            shortcodedata +=' title_line_height="'+title_line_height+'"';
        }
        if(typeof(subtitle_line_height)!='undefined' || subtitle_line_height!=''){
            shortcodedata +=' subtitle_line_height="'+subtitle_line_height+'"';
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
		  

    }).on( 'change', '#sld_mode',function(){
	
		var mode = $('#sld_mode').val();
		
		if( mode == 'one' ){
			$('#sld_list_div').css('display', 'block');
			$('#sld_list_cat').css('display', 'none');
			$('#sld_orderby_div').css('display', 'none');
			$('#sld_order_div').css('display', 'none');
		}
		else if( mode == 'category' ){
			$('#sld_list_cat').css('display', 'block');
			$('#sld_list_div').css('display', 'none');
			$('#sld_orderby_div').css('display', 'block');
			$('#sld_order_div').css('display', 'block');
		}
		else{
			$('#sld_list_div').css('display', 'none');
			$('#sld_list_cat').css('display', 'none');
			$('#sld_orderby_div').css('display', 'block');
			$('#sld_order_div').css('display', 'block');
		}
		
	}).on( 'change', '#sld_style',function(){
	
		var style = $('#sld_style').val();
		
		if( style == 'simple' || style == 'style-1' || style == 'style-16' ){
			$('#sld_column_div').css('display', 'block');

		}
		else{
			$('#sld_column_div').css('display', 'none');
		}
		
		if( style == 'simple' ){
		   $('#demo-preview-link #demo-url').html('<a href="http://dev.quantumcloud.com/sld/" target="_blank">http://dev.quantumcloud.com/sld/</a>');
		}
		else if( style == 'style-1' ){
		   $('#demo-preview-link #demo-url').html('<a href="http://dev.quantumcloud.com/sld/style-1/" target="_blank">http://dev.quantumcloud.com/sld/style-1/</a>');
		}
		else if( style == 'style-2' ){
		   $('#demo-preview-link #demo-url').html('<a href="http://dev.quantumcloud.com/sld/style-2/" target="_blank">http://dev.quantumcloud.com/sld/style-3/</a>');
		}
		else if( style == 'style-3' ){
		   $('#demo-preview-link #demo-url').html('<a href="http://dev.quantumcloud.com/sld/style-3/" target="_blank">http://dev.quantumcloud.com/sld/style-5/</a>');
		}
		else{
		   $('#demo-preview-link #demo-url').html('<a href="http://dev.quantumcloud.com/sld/" target="_blank">http://dev.quantumcloud.com/sld/</a>');
		}

		if(style == 'style-1'){
			$('#sld_column option[value="3"]').hide();
			$('#sld_column option[value="4"]').hide();

		}else{
			$('#sld_column option[value="3"]').show();
			$('#sld_column option[value="4"]').show();
			
		}		
		
	}).on( 'change', '#sld_column',function(){
	
		var sld_column = $('#sld_column').val();
		
		// if( sld_column == 3 || sld_column == 4 ){
		// 	$('#sld_width_div').css('display', 'block');
		// }else{
		// 	$('#sld_width_div').css('display', 'none');
		// }
		
		
	});

	$(document).on( 'click', ' .modal-content .close', function(){
		$(this).parent().parent().remove();
	});
});


function isGutenbergActive() {
    return typeof wp !== 'undefined' && typeof wp.blocks !== 'undefined';
}

jQuery(document).ready(function($){
	
	if($('.sld-Getting-Started').length>0){
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
	$(document).on('click','#Getting_Started', function(){
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
	});
	$(document).on('click','.qcld_getting_started', function(){
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
	});
	
	
	if($('.sld-notice').length>0){
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
	

	$('.sld_click_handle').on('click', function(e){
		e.preventDefault();
		var obj = $(this);
		container_id = obj.attr('href');
		$('.sld_click_handle').each(function(){
			$(this).removeClass('nav-tab-active');
			$($(this).attr('href')).hide();
		})
		obj.addClass('nav-tab-active');
		$(container_id).show();
        window.history.replaceState(null, null, container_id);
	})
	var hash = window.location.hash;
	if(hash!=''){
		$('.sld_click_handle').each(function(){
			
			$($(this).attr('href')).hide();
			if($(this).attr('href')==hash){
				$(this).removeClass('nav-tab-active').addClass('nav-tab-active');
			}else{
				$(this).removeClass('nav-tab-active');
			}
		})
		$(hash).show();
	}

	$('.sld_help_links').on('click', function(e){
		e.preventDefault();
		var obj = $(this);
		container_id = obj.attr('href');
		window.history.replaceState(null,null, container_id);
		location.reload(true);
	});

	$(".qcld_short_genarator_scroll").click(function() {
	    $("html, body").animate({ scrollTop: $(".qcld_short_genarator_scroll_wrap").offset().top }, 1500);
	});
	
})
