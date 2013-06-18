jQuery(document).ready(function ($) {
jQuery('#templates-container').isotope();
              
        // filter items when filter link is clicked
        jQuery('#template-filter a').click(function(){      
          var selector = jQuery(this).attr('data-filter');
          //alert(selector);
          jQuery('#templates-container').isotope({ filter: selector });
          return false;
        });
  
    
   

     var current_a_tab = jQuery("#tabs-0").hasClass('nav-tab-special-active');
    if (current_a_tab === true){
        var url_norm = jQuery("#view-post-btn a").attr('href');
        var new_url = url_norm + "?lp-variation-id=0";
        jQuery("#view-post-btn a").attr('href', new_url);
    }
    
    // Fix inactivate theme display
    jQuery("#template-box a").live('click', function () {

    setTimeout(function() {
     jQuery('#TB_window iframe').contents().find("#customize-controls").hide();
        jQuery('#TB_window iframe').contents().find(".wp-full-overlay.expanded").css("margin-left", "0px");
    }, 600);
     
    });
    
    // Fix Split testing iframe size
    jQuery("#lp-metabox-splittesting a.thickbox, #leads-table-container-inside .column-details a").live('click', function () {
        jQuery('#TB_iframeContent, #TB_window').hide();
        setTimeout(function() {

         jQuery('#TB_iframeContent, #TB_window').width( 640 ).height( 800 ).css("margin-left", "0px").css("left", "35%");
         jQuery('#TB_iframeContent, #TB_window').show();
        }, 600);     
    });

    var delay = (function () {
        var timer = 0;
        return function (callback, ms) {
            clearTimeout(timer);
            timer = setTimeout(callback, ms);
        };
    })();
    
    jQuery(function () {
        var pause = 100; // will only process code within delay(function() { ... }) every 100ms.
        jQuery(window).resize(function () {
            delay(function () {
                var width = jQuery(window).width(); 
                jQuery('#TB_iframeContent, #TB_window').width( 640 ).height( 800 ).css("margin-left", "0px").css("left", "35%");
            }, pause);
        });
        jQuery(window).resize();
    });
    
    // Load meta box in correct position on page load
    var current_template = jQuery("input#lp_select_template ").val();
    var current_template_meta = "#lp_" + current_template + "_custom_meta_box";
    jQuery(current_template_meta).removeClass("postbox").appendTo("#template-display-options").addClass("Old-Template");
    var current_template_h3 = "#lp_" + current_template + "_custom_meta_box h3";
    jQuery(current_template_h3).css("background","#f8f8f8");
    jQuery(current_template_meta +' .handlediv').hide();
    jQuery(current_template_meta +' .hndle').css('cursor','default');
        
  
    // Fix Thickbox width/hieght
    jQuery(function($) {
        tb_position = function() {
            var tbWindow = $('#TB_window');
            var width = $(window).width();
            var H = $(window).height();
            var W = ( 1720 < width ) ? 1720 : width;

            if ( tbWindow.size() ) {
                tbWindow.width( W - 50 ).height( H - 45 );
                $('#TB_iframeContent').width( W - 50 ).height( H - 75 );
                tbWindow.css({'margin-left': '-' + parseInt((( W - 50 ) / 2),10) + 'px'});
                if ( typeof document.body.style.maxWidth != 'undefined' )
                    tbWindow.css({'top':'40px','margin-top':'0'});
                //$('#TB_title').css({'background-color':'#fff','color':'#cfcfcf'});
            };

            return $('a.thickbox').each( function() {
                var href = $(this).attr('href');
                if ( ! href ) return;
                href = href.replace(/&width=[0-9]+/g, '');
                href = href.replace(/&height=[0-9]+/g, '');
                $(this).attr( 'href', href + '&width=' + ( W - 80 ) + '&height=' + ( H - 85 ) );
            });

        };

        jQuery('a.thickbox').click(function(){
            if ( typeof tinyMCE != 'undefined' &&  tinyMCE.activeEditor ) {
                tinyMCE.get('content').focus();
                tinyMCE.activeEditor.windowManager.bookmark = tinyMCE.activeEditor.selection.getBookmark('simple');
            }
           
        });

        $(window).resize( function() { tb_position() } );
    });
    
    // Isotope Styling
    jQuery('#template-filter a').first().addClass('button-primary');
    jQuery('#template-filter a').click(function(){
        jQuery("#template-filter a.button-primary").removeClass("button-primary");
        jQuery(this).addClass('button-primary');
    });
    
    jQuery('.lp_select_template').click(function(){
        var template = jQuery(this).attr('id');
        var label = jQuery(this).attr('label');
        jQuery("#template-box.default_template_highlight").removeClass("default_template_highlight");
        var selected_template_id = "#" + template;
        var currentlabel = jQuery(".currently_selected").show();
        jQuery(selected_template_id).parent().addClass("default_template_highlight").prepend(currentlabel);
        jQuery(".lp-template-selector-container").fadeOut(500,function(){
            jQuery(".wrap").fadeIn(500, function(){
            });
        });
        jQuery(current_template_meta).appendTo("#template-display-options");
        jQuery('#lp_metabox_select_template h3').first().html('Current Active Template: '+label);
        jQuery('#lp_select_template').val(template);
        jQuery(".Old-Template").hide();
        var current_template = jQuery("input#lp_select_template ").val();
        var current_template_meta = "#lp_" + current_template + "_custom_meta_box";
        var current_template_h3 = "#lp_" + current_template + "_custom_meta_box h3";
        var current_template_div = "#lp_" + current_template + "_custom_meta_box .handlediv";
        jQuery(current_template_div).css("display","none");
        jQuery(current_template_h3).css("background","#f8f8f8");
        jQuery(current_template_meta).show().appendTo("#template-display-options").removeClass("postbox").addClass("Old-Template");
        //alert(template);
        //alert(label);
    });

    jQuery('#lp-cancel-selection').click(function(){
        jQuery(".lp-template-selector-container").fadeOut(500,function(){
            jQuery(".wrap").fadeIn(500, function(){
            });
        });
    
    });
    
    // the_content default overwrite
    jQuery('#overwrite-content').click(function(){
        if (confirm('Are you sure you want to overwrite what is currently in the main edit box above?')) {
            var default_content = jQuery(".default-content").text();
           jQuery("#content_ifr").contents().find("body").html(default_content);
       } else {
    // Do nothing!
    }  
    });
    
    // Colorpicker fix
    jQuery('.jpicker').one('mouseenter', function () {
        jQuery(this).jPicker({
            window: // used to define the position of the popup window only useful in binded mode
            {
                title: null, // any title for the jPicker window itself - displays "Drag Markers To Pick A Color" if left null
                position: {
                    x: 'screenCenter', // acceptable values "left", "center", "right", "screenCenter", or relative px value
                    y: 'center', // acceptable values "top", "bottom", "center", or relative px value
                },
                expandable: false, // default to large static picker - set to true to make an expandable picker (small icon with popup) - set
                // automatically when binded to input element
                liveUpdate: true, // set false if you want the user to click "OK" before the binded input box updates values (always "true"
                // for expandable picker)
                alphaSupport: false, // set to true to enable alpha picking
                alphaPrecision: 0, // set decimal precision for alpha percentage display - hex codes do not map directly to percentage
                // integers - range 0-2
                updateInputColor: true // set to false to prevent binded input colors from changing
            }
        },
        function(color, context)
        {
          var all = color.val('all');
         // alert('Color chosen - hex: ' + (all && '#' + all.hex || 'none') + ' - alpha: ' + (all && all.a + '%' || 'none'));
           //jQuery(this).attr('rel', all.hex);
           jQuery(this).parent().find(".lp-success-message").remove();
           jQuery(this).parent().find(".new-save-lp").show();
           jQuery(this).parent().find(".new-save-lp-frontend").show();

           //jQuery(this).attr('value', all.hex);
        });
    });

    if (jQuery(".lp-template-selector-container").css("display") == "none"){
        jQuery(".currently_selected").hide(); }
    else {
        jQuery(".currently_selected").show();
    }

    // Add current title of template to selector
    var selected_template = jQuery('#lp_select_template').val();
    var selected_template_id = "#" + selected_template;
    var currentlabel = jQuery(".currently_selected");
    jQuery(selected_template_id).parent().addClass("default_template_highlight").prepend(currentlabel);
    jQuery("#lp_metabox_select_template h3").first().append(' - Current Active Template: <strong>' + selected_template + '</strong>')

    jQuery('#lp-change-template-button').live('click', function () {
        jQuery(".wrap").fadeOut(500,function(){
            jQuery('#templates-container').isotope();
            jQuery(".lp-template-selector-container").fadeIn(500, function(){
                jQuery(".currently_selected").show();
                jQuery('#lp-cancel-selection').show();
            });
            jQuery("#template-filter li a").first().click();
        });
    });
    
    /* Move Slug Box
    var slugs = jQuery("#edit-slug-box");
    jQuery('#main-title-area').after(slugs.show());
    */
    // Background Options
    jQuery('.current_lander .background-style').live('change', function () {
        var input = jQuery(".current_lander .background-style option:selected").val();
        if (input == 'color') {
            jQuery('.current_lander tr.background-color').show();
            jQuery('.current_lander tr.background-image').hide();
            jQuery('.background_tip').hide();
        } 
        else if (input == 'default') {
            jQuery('.current_lander tr.background-color').hide();
            jQuery('.current_lander tr.background-image').hide();
            jQuery('.background_tip').hide();
        } 
        else if (input == 'custom') {
            var obj = jQuery(".current_lander tr.background-style td .lp_tooltip");
            obj.removeClass("lp_tooltip").addClass("background_tip").html("Use the custom css block at the bottom of this page to set up custom CSS rules");
            jQuery('.background_tip').show();
        }
        else {
            jQuery('.current_lander tr.background-color').hide();
            jQuery('.current_lander tr.background-image').show();
            jQuery('.background_tip').hide();
        }

    });

    // Check BG options on page load  
    jQuery(document).ready(function () {
        var input2 = jQuery(".current_lander .background-style option:selected").val();
        if (input2 == 'color') {
            jQuery('.current_lander tr.background-color').show();
            jQuery('.current_lander tr.background-image').hide();
        } else if (input2 == 'custom') {
            var obj = jQuery(".current_lander tr.background-style td .lp_tooltip");
            obj.removeClass("lp_tooltip").addClass("background_tip").html("Use the custom css block at the bottom of this page to set up custom CSS rules");
            jQuery('.background_tip').show();
        } else if (input2 == 'default') {
            jQuery('.current_lander tr.background-color').hide();
            jQuery('.current_lander tr.background-image').hide();   
        } else {
            jQuery('.current_lander tr.background-color').hide();
            jQuery('.current_lander tr.background-image').show();
        }
    });

    //Stylize lead's wp-list-table
    var cnt = $("#leads-table-container").contents();
    $("#lp_conversion_log_metabox").replaceWith(cnt);
    
    //remove inputs from wp-list-table
    jQuery('#leads-table-container-inside input').each(function(){
        jQuery(this).remove();
    });

     var post_status = jQuery("#original_post_status").val();
    
    if (post_status === "draft") {
        // jQuery( ".nav-tab-wrapper.a_b_tabs .lp-ab-tab, #tabs-add-variation").hide();
        jQuery(".new-save-lp-frontend").on("click", function(event) {
            event.preventDefault();
            alert("Must publish this page before you can use the visual editor!");
        });
        var subbox = jQuery("#submitdiv");
        jQuery("#lp_ab_display_stats_metabox").before(subbox)
    } else {
        jQuery("#publish").val("Update All");
    }

    // Ajax Saving for metadata
    jQuery('#lp_metabox_select_template input, #lp_metabox_select_template select, #lp_metabox_select_template textarea').on("change keyup", function (e) {
        // iframe content change needs its own change function $("#iFrame").contents().find("#someDiv")
        // media uploader needs its own change function
        var this_id = jQuery(this).attr("id");
        var parent_el = jQuery(this).parent();
        jQuery(parent_el).find(".lp-success-message").remove();
        jQuery(parent_el).find(".new-save-lp").remove();
        var ajax_save_button = jQuery('<span class="button-primary new-save-lp" id="' + this_id + '" style="margin-left:10px">Update</span>');
        //console.log(parent_el);
        jQuery(ajax_save_button).appendTo(parent_el);
    });
    jQuery('#lp-notes-area input').on("change keyup", function (e) {
       var this_id = jQuery(this).attr("id");
        var parent_el = jQuery(this).parent();
        jQuery(parent_el).find(".lp-success-message").remove();
        jQuery(parent_el).find(".new-save-lp").remove();
        var ajax_save_button = jQuery('<span class="button-primary new-save-lp" id="' + this_id + '" style="margin-left:10px">Update</span>');
        //console.log(parent_el);
        jQuery(ajax_save_button).appendTo(parent_el);
    });

        jQuery('#main-title-area input').on("change keyup", function (e) {
        // iframe content change needs its own change function $("#iFrame").contents().find("#someDiv")
        // media uploader needs its own change function
        var this_id = jQuery(this).attr("id");
        var current_view = jQuery("#lp-current-view").text();
        if (current_view !== "0") {
            this_id = this_id + '-' + current_view;
        }
        var parent_el = jQuery(this).parent();
        jQuery(parent_el).find(".lp-success-message").remove();
        jQuery(parent_el).find(".new-save-lp").remove();
        var ajax_save_button = jQuery('<span class="button-primary new-save-lp" id="' + this_id + '" style="margin-left:10px">Update</span>');
        //console.log(parent_el);
        jQuery(ajax_save_button).appendTo(parent_el);
    });


    var nonce_val = lp_post_edit_ui.wp_landing_page_meta_nonce; // NEED CORRECT NONCE
    jQuery("body").on('click', '.new-save-lp', function () {
        var type_input = jQuery(this).parent().find("input").attr("type");
        var type_select = jQuery(this).parent().find("select");
        jQuery(this).parent().find(".lp-success-message").hide();
        var type_textarea = jQuery(this).parent().find("textarea");
        if (typeof (type_input) != "undefined" && type_input !== null) {
            var type_of_field = type_input;
        } else if (typeof (type_textarea) != "undefined" && type_textarea !== null) {
            var type_of_field = 'textarea';
        } else {
            (typeof (type_select) != "undefined" && type_select)
            var type_of_field = 'select';
        }

        console.log(type_of_field);
        var new_value_meta_input = jQuery(this).parent().find("input").val();
        // console.log(new_value_meta_input); 
        var new_value_meta_select = jQuery(this).parent().find("select").val();
        var new_value_meta_textarea = jQuery(this).parent().find("textarea").val();
        //console.log(new_value_meta_select); 
        var new_value_meta_radio = jQuery(this).parent().find("input:checked").val();
        var new_value_meta_checkbox = jQuery(this).parent().find('input[type="checkbox"]:checked').val();

        // prep data
        if (typeof (new_value_meta_input) != "undefined" && new_value_meta_input !== null && type_of_field == "text") {
            var meta_to_save = new_value_meta_input;
        } else if (typeof (new_value_meta_textarea) != "undefined" && new_value_meta_textarea !== null && type_of_field == "textarea") {
            var meta_to_save = new_value_meta_textarea;
        } else if (typeof (new_value_meta_select) != "undefined" && new_value_meta_select !== null) {
            var meta_to_save = new_value_meta_select;
        } else if (typeof (new_value_meta_radio) != "undefined" && new_value_meta_radio !== null && type_of_field == "radio") {
            var meta_to_save = new_value_meta_radio;
        } else if (typeof (new_value_meta_checkbox) != "undefined" && new_value_meta_checkbox !== null && type_of_field == "checkbox") {
            var meta_to_save = new_value_meta_checkbox;
        } else {
            var meta_to_save = "";
        }

        // if data exists save it
        var this_meta_id = jQuery(this).attr("id");
        var post_id = jQuery("#post_ID").val();

        jQuery.ajax({
            type: 'POST',
            url: lp_post_edit_ui.ajaxurl,
            context: this,
            data: {
                action: 'wp_landing_page_meta_save',
                meta_id: this_meta_id,
                new_meta_val: meta_to_save,
                page_id: post_id,
                nonce: nonce_val
            },

            success: function (data) {
                var self = this;

                //alert(data);
                // jQuery('.lp-form').unbind('submit').submit();
                //var worked = '<span class="success-message-map">Success! ' + this_meta_id + ' set to ' + meta_to_save + '</span>';
                var worked = '<span class="lp-success-message">Updated!</span>';
                var s_message = jQuery(self).parent();
                jQuery(worked).appendTo(s_message);
                jQuery(self).hide();
                jQuery("#switch-lp").text("0");
                //alert("Changes Saved!");
            },

            error: function (MLHttpRequest, textStatus, errorThrown) {
                alert("Ajax not enabled");
            }
        });

        return false;
        
    });
});
