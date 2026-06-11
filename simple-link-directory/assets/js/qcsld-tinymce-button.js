;(function( $ ) {
    tinymce.PluginManager.add('qcopd_sld_shortcode_btn', function( editor,url )
    {
        var shortcodeValues = [];

        editor.addButton('qcopd_sld_shortcode_btn', {
			title : 'Generate SLD Shortcode',
            //text: 'SLD',
            icon: 'icon qc_sld_btn',
            onclick : function(e){
                $.post(
                    ajaxurl,
                    {
                        action : 'show_qcopd_sld_shortcodes'
                        
                    },
                    function(data){
                        $('#wpwrap').append(data);
                    }
                )
            },
            values: shortcodeValues
        });
    });

    

}(jQuery));
