$(document).ready(function(){      
    $('#calendar_from,#calendar_to').datepicker({
        showOn: 'button',        
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'mm/yy',
        buttonImage: urls.assets_url+'site/images/calendar-day.png', 
        buttonImageOnly: true, 
        yearRange: "-20:+0",
        onClose: function(dateText, inst) { 
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).datepicker('setDate', new Date(year, month, 1));
        }
    });  
    
    $('#calendar_from,#calendar_to').attr('readonly','readonly');
    
    $('#calendar_from,#calendar_to').focus(function () {
        $(".ui-datepicker-calendar").hide();
    });
});
