jQuery(document).ready(function($) {
   // Code for landing page list view
   var cats = jQuery("#landing_page_category option").length;
  if ( cats === 0 ){
    jQuery("#landing_page_category").hide();
   }

    jQuery('.lp-letter').each(function(){
        var draft = jQuery(this).text();
         if ( draft === "" ){
      jQuery(this).parent().parent().hide();
      }
    });

    jQuery(".lp-impress-num").each(function(){
    var empty = jQuery(this).text();
     if ( empty === "" ){
      jQuery(this).parent().html("<span class='lp-no-stats'>no stats yet</span>");
      }
    });
   /*jQuery(".lp-varation-stat-ul").each(function(){
    var length = jQuery(this).find("li").length;
     if ( length < 3 ){
      jQuery(this).find("li").first().css("padding-top", "18px");
      }
    });
*/
      jQuery(".variation-winner-is").each(function(){
    var target = jQuery(this).text();
      jQuery("." + target).addClass("winner-lp").attr("data-lp", "Current Winner");
    });

    var hidestats = "<span id='hide-stats'>(Hide Stats)</span><span class='show-stats show-stats-top'>Show Stats</span>";
    jQuery("#stats").append(hidestats);

    jQuery("body").on('click', '#hide-stats', function () {
      jQuery(".lp-varation-stat-ul").each(function(){
        jQuery(this).hide();
    });
      jQuery(".show-stats").show();
      jQuery("#hide-stats").hide();
    });

    jQuery("body").on('click', '.show-stats-top', function () {
      jQuery(".lp-varation-stat-ul").each(function(){
        jQuery(this).show();
    });
      jQuery(".show-stats").hide();
      jQuery("#hide-stats").show();
    });

    jQuery("body").on('click', '.show-stats', function () {
      jQuery(this).hide();
      jQuery(this).parent().find(".lp-varation-stat-ul").show();
    });
 
jQuery('.lp-letter, .cr-number').on('mouseenter', function(event) {
  // Bind the qTip within the event handler
  var text_in_tip = jQuery(this).attr("data-notes");
  var letter = jQuery(this).attr("data-letter");
  var status = "<span class='lp-paused'>" + jQuery(this).parent().attr("rel") + "</span>";
  var winner = "<span class='lp-win'>" + jQuery(this).parent().attr("data-lp") + "</span>";
  jQuery(this).qtip({
    overwrite: false, // Make sure the tooltip won't be overridden once created
      content: {
          text: text_in_tip,
          title: {
            text: 'Variation ' + letter + "<span class='lp-extra'>" + status + winner + "</span>" + "<span class='lp-pop-close'>close</span>"
          }
        },
    position: {
          my: 'bottom center', // Use the corner...
          at: 'top center', // ...and opposite corner
          viewport: jQuery(window)
        },
    style: {
          classes: 'qtip-shadow qtip-jtools',
        },
    show: {
      event: event.type, // Use the same show event as the one that triggered the event handler
      ready: true, // Show the tooltip as soon as it's bound, vital so it shows up the first time you hover!
      solo: true  
    },
    hide: 'unfocus'
  }, event); // Pass through our original event to qTip
})
jQuery('.lp-letter').on('mouseleave', function(event) {


});

jQuery("body").on("click", ".lp-pop-close", function(event) {
jQuery(this).parent().parent().parent().hide();
});
jQuery("body").on("click", ".lp-pop-preview a", function(event) {
jQuery(this).parent().parent().parent().parent().hide();
});

 });