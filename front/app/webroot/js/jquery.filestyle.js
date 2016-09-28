(function($) {
    
    $.fn.filestyle = function(options) {
                
        /* TODO: This should not override CSS. */
        var settings = {
            width : 500,
			id:''
        };
                
        if(options) {
            $.extend(settings, options);
        };
                        
        return this.each(function() {
            
            var self = this;
            var wrapper = $("<div>")
                            .css({
                                "width": settings.imagewidth + "px",
                                "height": settings.imageheight + "px",
                                "background": "url(" + settings.image + ") 0 0 no-repeat",
                                "background-position": "right",
                                "display": "inline",
                                "position": "absolute",
                                "overflow": "hidden",
								"border":"0px solid black"
                            });
                            
            var filename = $('<input class="file" id="'+settings.id+'">')
                             .addClass($(self).attr("class"))
                             .css({
                                 "display": "inline",
								 "border": "none",
								 'background':'transparent',
                                 "width": settings.width + "%"
                             });

            $(self).before(filename);
            $(self).wrap(wrapper);

            $(self).css({
                        "position": "relative",
                        "height": settings.imageheight + "px",
                        "width": settings.imagewidth + "px",
                        "display": "inline",
                        "cursor": "pointer",
                        "opacity": "0.0"
                    });

            if ($.browser.mozilla) {
                if (/Win/.test(navigator.platform)) {
                    $(self).css("margin-left", "-142px");                    
                } else {
                    $(self).css("margin-left", "-168px");                    
                };
            } else {
                $(self).css("margin-left", "-7px");                
            };

            $(self).bind("change", function() {
                filename.val($(self).val());
            });
        });
    }; 
})(jQuery);
