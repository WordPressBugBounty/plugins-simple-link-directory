jQuery(document).ready(function($)
{
    // Upvote Button Handler
    $(document).on("click", ".upvote-btn", function(event){
        
        event.preventDefault();

        var data_id = $(this).attr("data-post-id");
        var data_title = $(this).attr("data-item-title");
        var data_link = $(this).attr("data-item-link");

        var parentLI = $(this).closest('li').attr("id");

        var selectorBody = $('.qc-grid-item span[data-post-id="'+data_id+'"][data-item-title="'+data_title+'"][data-item-link="'+data_link+'"]');
        var selectorWidget = $('.widget span[data-post-id="'+data_id+'"][data-item-title="'+data_title+'"][data-item-link="'+data_link+'"]');

        var bodyLiId = $(".qc-grid-item").find(selectorBody).closest('li').attr("id");
        var WidgetLiId = $(selectorWidget).closest('li').attr("id");

        $.post(ajaxurl, {            
            action: 'qcopd_upvote_action', 
            post_id: data_id,
            meta_title: data_title,
            meta_link: data_link,
            li_id: parentLI,
            security: (typeof qc_sld_get_ajax_nonce !== 'undefined') ? qc_sld_get_ajax_nonce : ''
        }, function(data) {
            var json = (typeof data === 'object') ? data : $.parseJSON(data);
            
            if( json && json.vote_status == 'success' )
            {
                $('#'+parentLI+' .upvote-section .upvote-count').html(json.votes);
                $('#'+parentLI+' .upvote-section .upvote-btn').css("color", "green");
                $('#'+parentLI+' .upvote-section .upvote-count').css("color", "green");

                if (bodyLiId) {
                    $('#'+bodyLiId+' .upvote-section .upvote-count').html(json.votes);
                    $('#'+bodyLiId+' .upvote-section .upvote-btn').css("color", "green");
                    $('#'+bodyLiId+' .upvote-section .upvote-count').css("color", "green");
                }

                if (WidgetLiId) {
                    $('#'+WidgetLiId+' .upvote-section .upvote-count').html(json.votes);
                    $('#'+WidgetLiId+' .upvote-section .upvote-btn').css("color", "green");
                    $('#'+WidgetLiId+' .upvote-section .upvote-count').css("color", "green");
                }
            }
        });
       
    });


    // Prevent enter submission on live search
    $(document).on('keypress', '.sld_search_filter', function(e) {
       var code = (e.keyCode ? e.keyCode : e.which);
  
        if ( (code==13) || (code==10)){
            $(this).trigger('blur');
            return false;
        }
    });


    function sld_refresh_packery() {
        var $grid = $('.qc-grid');
        if (typeof $.fn.packery === 'function' && $grid.length > 0) {
            $grid.each(function() {
                var $g = $(this);
                var isRtl = (typeof sld_ajax_object_rtl !== 'undefined' && sld_ajax_object_rtl === 'on');
                if ($g.data('packery')) {
                    $g.packery({
                        itemSelector: '.qc-grid-item',
                        gutter: 10,
                        percentPosition: true,
                    });
                } else {
                    $g.packery({
                        itemSelector: '.qc-grid-item',
                        gutter: 10,
                        percentPosition: true,
                        originLeft: !isRtl
                    });
                }
            });
        }
    }

    // Live Search Filter
    $(document).on('keyup input', '.sld_search_filter', function(){
        var $input = $(this);
        var rawVal = $input.val() || '';
        var filter = rawVal.trim();
        var checkDomWrap = $input.closest('form');

        // Manage clear button
        if ( filter !== '' ) {
            checkDomWrap.addClass('sld_search_filter_clear_wrap');
            if (checkDomWrap.find('.sld_search_filter_clear').length === 0) {
                $input.after('<i class="fa fa-times sld_search_filter_clear"></i>');
            }
        } else {
            checkDomWrap.removeClass('sld_search_filter_clear_wrap');
            checkDomWrap.find('.sld_search_filter_clear').remove();
        }

        // Clean up previous "No results found" messages
        $('.qc_sld_not_item_found').remove();

        // Target list items
        var $items = $('.qcopd-list-holder ul li, #opd-list-holder ul li, .sld-featured-strip ul li');
        if($('.qcld_sld_tabcontent').length > 0){
            $items = $('.qcld_sld_tabcontent:visible .qcopd-list-holder ul li, .qcld_sld_tabcontent:visible #opd-list-holder ul li, .qcld_sld_tabcontent:visible .sld-featured-strip ul li');
        }

        var matchCount = 0;

        if ( filter === '' ) {
            $items.removeClass('jp-hidden').show().addClass('showMe');
            $('.qc-grid-item, .qcopd-list-column, .qcopd-single-list, .qcopd-single-list-1, .sld-featured-strip, .sld-container, .qcopd-list-wrapper').show();
            matchCount = $items.length;

            // Reset heading counts for templates with count badges (e.g. Style-4)
            $('.qc-grid-item, .qcopd-list-column').each(function() {
                var $col = $(this);
                var totalInCol = $col.find('li').length;
                var $countEl = $col.find('.sld-style-4-heading-count');
                if ($countEl.length > 0 && totalInCol > 0) {
                    $countEl.text(totalInCol + (totalInCol === 1 ? ' item →' : ' items →'));
                }
            });
        } else {
            var searchWords = filter.toLowerCase().split(/\s+/).filter(Boolean);

            $items.each(function(){
                var $li = $(this);
                $li.removeClass('jp-hidden');

                var itemText       = ($li.text() || '').replace(/\s+/g, ' ');
                var dataTitleTxt   = $li.find('a').attr('data-title') || $li.children('a').attr('data-title') || '';
                var dataSubtitle   = $li.find('a').attr('data-subtitle') || $li.children('a').attr('data-subtitle') || '';
                var dataTag        = $li.find('a').attr('data-tag') || $li.children('a').attr('data-tag') || '';
                var dataUrl        = $li.find('a').attr('href') || '';
                
                // Only target the specific list category heading <h2> (not item <h3> headings)
                var parentHeading  = ($li.closest('.qcopd-single-list, .qcopd-single-list-1, .qc-grid-item').find('> h2, .qcopd-single-list > h2, .qcopd-single-list-1 > h2, .sld-style-4-heading h2').first().text() || '').replace(/\s+/g, ' ');

                var combinedText = (itemText + ' ' + parentHeading + ' ' + dataTitleTxt + ' ' + dataSubtitle + ' ' + dataTag + ' ' + dataUrl).toLowerCase();

                var isMatched = searchWords.every(function(word) {
                    return combinedText.indexOf(word) !== -1;
                });

                if (isMatched) {
                    $li.show().addClass('showMe');
                    matchCount++;
                } else {
                    $li.hide().removeClass('showMe');
                }

            });

            // Show or hide individual column cards (.qc-grid-item) based on visible items
            var $columns = $('.qc-grid-item, .qcopd-list-column');
            if($('.qcld_sld_tabcontent').length > 0){
                $columns = $('.qcld_sld_tabcontent:visible .qc-grid-item, .qcld_sld_tabcontent:visible .qcopd-list-column');
            }

            $columns.each(function(){
                var $col = $(this);
                var visibleInCol = $col.find('li.showMe').length;
                if(visibleInCol > 0){
                    $col.show();
                    $col.find('.qcopd-single-list, .qcopd-single-list-1').show();
                    var $countEl = $col.find('.sld-style-4-heading-count');
                    if ($countEl.length > 0) {
                        $countEl.text(visibleInCol + (visibleInCol === 1 ? ' item →' : ' items →'));
                    }
                } else {
                    $col.hide();
                }
            });
        }

        // Update featured strip counter and visibility
        $('.sld-featured-strip').each(function(){
            var $strip = $(this);
            var featVisible = $strip.find("li.showMe").length;
            var $countEl = $strip.find('.sld-featured-heading-count');

            if ($countEl.length > 0) {
                var singularTpl = $countEl.attr('data-singular') || '%s item';
                var pluralTpl   = $countEl.attr('data-plural') || '%s items';
                var textTpl     = (featVisible === 1) ? singularTpl : pluralTpl;
                var countText   = textTpl.replace('%s', (filter === '' ? $strip.find('li').length : featVisible)) + ' →';
                $countEl.text(countText);
            }

            if (filter !== '' && featVisible === 0) {
                $strip.hide();
            } else {
                $strip.show();
            }
        });

        // Re-layout packery with immediate and delayed triggers
        sld_refresh_packery();
        setTimeout(function(){
            sld_refresh_packery();
        }, 50);

        // Show "No Results Found" notice if 0 matches
        if(filter !== '' && matchCount === 0){
            var noResultsTxt = (typeof sld_no_results_found !== 'undefined' && sld_no_results_found) ? sld_no_results_found : 'No Results Found for Your Search';
            var $holder = $('.qcopd-list-holder, #opd-list-holder');
            if($('.qcld_sld_tabcontent').length > 0){
                $holder = $('.qcld_sld_tabcontent:visible .qcopd-list-holder, .qcld_sld_tabcontent:visible #opd-list-holder');
            }
            if($holder.length > 0 && $holder.find('.qc_sld_not_item_found').length === 0){
                $holder.first().prepend('<div class="qc_sld_not_item_found">'+noResultsTxt+'</div>');
            }
        }

    });

      
    $(document).on('click', '.sld_search_filter_clear', function(e){
        e.preventDefault();
        var checkDomWrap = $(this).closest('form');
        var $input = checkDomWrap.find('.sld_search_filter');
        $input.val('');
        checkDomWrap.find('.sld_search_filter_clear').remove();
        checkDomWrap.removeClass('sld_search_filter_clear_wrap');
        $input.trigger('input').trigger('keyup');
    });
    

    $(document).on('submit', '#live-search', function(e){
        e.preventDefault();
    });
    
    $(document).on('click', '.qcld-sldquick-flyout', function() {
        $(this).toggleClass('is-open');
        $('body').toggleClass('qcld-sld-flyout');
    }); 
    
});


