jQuery(document).ready(function ($) {
    

    $('input.datepicker').Zebra_DatePicker({
        direction: 1    // boolean true would've made the date picker future only
                        // but starting from today, rather than tomorrow
    });

    $('#datepicker-example13').Zebra_DatePicker({
        always_visible: $('#container')
    });

});