;jQuery(document).ready(function($){

   $.validator.addMethod('IP4Checker', function(value) {
      var ip = "\\b(?:\\d{1,3}\\.){3}\\d{1,3}\\b";
      return value.match(ip);
   }, 'Invalid IP address');
   
   $("#ip-form").validate({
      rules: {
         "employee-id": {
            required: true
         },         
         "assign-ip": {
            IP4Checker: true,
            required: true
         },
         "gateway": {
            IP4Checker: true,
            required: true
         },
         "external-ip": {
            required: true,
            IP4Checker: true
         }
      },
      messages: {
         "employee-id": "Please select employee.",
         "assign-ip": {
            required: "Please enter IP address.",
            IP4Checker: "Please enter a valid IP address."
         },
         "gateway": {
            required: "Please enter gateway address.",
            IP4Checker: "Please enter a valid gateway address."
         },
         "external-ip": {
            required: "Please enter external IP.",
            IP4Checker: "Please enter a valid external IP."
         }
      }
   });
   
   $("#modify-ip-form").validate({
      rules: {
         "modify-assign-ip": {
            IP4Checker: true,
            required: true
         },
         "modify-gateway": {
            IP4Checker: true,
            required: true
         },
         "modify-external-ip": {
            required: true,
            IP4Checker: true
         }
      },
      messages: {
         "modify-assign-ip": {
            required: "Please enter IP address.",
            IP4Checker: "Please enter a valid IP address."
         },
         "modify-gateway": {
            required: "Please enter gateway address.",
            IP4Checker: "Please enter a valid gateway address."
         },
         "modify-external-ip": {
            required: "Please enter external IP.",
            IP4Checker: "Please enter a valid external IP."
         }
      }
   });
            
   $(".ip-form-btn").click(function(){
      $("label.error").remove();
      $("#employee-id").val('').removeClass('error');
      $("#seat-no").val('');
      $("#department-name").val('');
      $("#assign-ip").removeClass('error');
      $("#gateway").removeClass('error');
      $("#external-ip").removeClass('error');
      $(".ip-form-dialog").dialog({
         title: "Add New IP",
         width: 400,
         modal: true,
         resizable: false
      });
   });
   
   $("#employee-id").change(function(){
      $("#seat-no").val($('option:selected', this).attr('data-seat-no'));
      $("#department-name").val($('option:selected', this).attr('data-department'));
   });
   
   $(".save-btn").click(function(){
      $("#ip-form").submit();
   });
   
   $(".update-btn").click(function(){
      $("#modify-ip-form").submit();
   });
   
   $(".cancel-btn").click(function(){
      site.close_dialog($(".ip-form-dialog"));
      site.close_dialog($(".modify-ip-form-dialog"));
   });
});