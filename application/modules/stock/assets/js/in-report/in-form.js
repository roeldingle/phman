jQuery(document).ready(function(){
   var inreport = new InReport();
   $(".date-reported").datepicker({
      dateFormat: "yy-mm-dd"
   });
   /** select options for main category in add incident report **/
   $("#main-category").live('change',function(){
      $("#assign-to").val('');
      $("#purchased-date").val('');
      $("#serial").html('<option value="">Select Serial</option>');
      $("#sub-category").html('<option value="">Select Sub Category</option>');
      if( $(this).val() === "" ) {
      } else {
         inreport.get_sub_category( $(this).val(), $("#sub-category") );
      }
   });
   
   /** select options for sub category in add incident report**/
   $("#sub-category").live('change',function(){
      $("#assign-to").val('');
      $("#purchased-date").val('');
      $("#serial").html('<option value="">Select Serial</option>');
      if( $(this).val() === "" ) {
         $("#serial").html('<option value="">Select Serial</option>');
      } else {
         inreport.get_stock_item_by_cat_id( $(this).val(), $("#serial") );
      }
   });
   
   /** select options for serial in add incident report**/
   $("#serial").live('change', function(){
      if($(this).val()==="") {
         $("#assign-to").val('');
         $("#purchased-date").val('');
      } else {
         $("#purchased-date").val($('option:selected', this).attr('data-purchased'));
         inreport.get_assign($('option:selected', this).attr('data-user-id'),$("#assign-to"));
      }
   });
   
   /** Initialize validation **/
   $("#incident-report-form").validate({
      validClass : "success",
      errorClass : "error",
      rules: {
         "main-category": {
            required: true
         },
         "sub-category": {
            required: true
         },
         serial: {
            required: true,
            maxlength: 300
         },
         "remarks": {
            required: true,
            maxlength: 250
         },
         "date-reported": {
            required: true
         }
      },
      messages: {
         "main-category": "Please select category.",
         "sub-category": "Please select sub category.",
         serial: "Please select serial.",
         remarks: {
            required: "Please enter remarks.",
            maxlength: "Must be a maximum of 250 characters"
         },
         "date-reported": ""
      }
   });
   
   /** Initialize validation for others incident report**/
   $("#others-form").validate({
      validClass : "success",
      errorClass : "error",
      rules: {
         model: {
            required: true
         },
         remarks: {
            required: true,
            maxlength: 250
         },
         "date-reported": {
            required: true
         }
      },
      messages: {
         model: "Please enter model.",
         remarks: {
            required: "Please enter remarks.",
            maxlength: "Must be a maximum of 250 characters"
         },
         "date-reported": ""
      }
   });
   
   /** Save button for incident report **/
   $("#save-incident-btn").click(function(){
      $("#incident-report-form").submit();
   });
   
   /** Save button for others report **/   
   $("#save-others-btn").click(function(){
      $("#others-form").submit();
   });
   
   /** Hide and show for add incident report (Office equipment and Others radio button) **/
   $("#incident-type").live('change',function(){
      $("label.error").remove();
      if($(this).val() === 'office') {         
         $(".office").show();
         $(".others").hide();
         $("#main-category").val('').removeClass('error');
         $("#sub-category").val('').removeClass('error');
         $("#serial").val('').removeClass('error');
         $("#assign-to").val('');
         $("#purchased-date").val('');
         $("#date-reported").val('');
         $("[name='remarks']").val('').removeClass('error');
      } else {
         $(".office").hide();
         $(".others").show();  
         $("#date-reported-others").val('');
         $("#model").val('').removeClass('error');         
         $("[name='remarks']").val('').removeClass('error');
      }
   });
   
   $(".reset-office-btn").click(function(){
      $("label.error").remove();
      $("#main-category").val('').removeClass('error');
      $("#sub-category").val('').removeClass('error');
      $("#serial").val('').removeClass('error');
      $("#assign-to").val('');
      $("#purchased-date").val('');
      $("[name='remarks']").val('').removeClass('error');
   });
   
   $(".reset-others-btn").click(function(){
      $("label.error").remove();
      $("#model").val('').removeClass('error');         
      $("[name='remarks']").val('').removeClass('error');
   });
});