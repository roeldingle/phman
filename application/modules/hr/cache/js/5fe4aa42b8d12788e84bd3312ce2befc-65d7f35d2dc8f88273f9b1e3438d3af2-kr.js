jQuery(document).ready(function($){ $(".ajax-test-button").click(function(){ var options = { url : urls.ajax_url, type : "post", data :{ mod:"example|ajax|ajax_value" },success : function(response){ alert(response); } }; $.ajax(options); }); $(".language-button").click(function(){ alert("이 샘플 메시지입니다"); }); $("#my-form").validate({ validClass : "success", errorClass : "core-form-class-error", errorElement : "div" ,rules : { fname : { required: true },mname : { required: true },lname : { required: true } },messages : { fname : "이름을 입력하세요", mname : "Enter your last name please.", lname : "Require this field" } }); $("#add-form").validate({ validClass : "success", errorClass : "core-form-class-error", errorElement : "div" ,rules : { firstname : { required: true },middlename : { required: true },lastname : { required: true },address : { required: true } },messages : { firstname : "Enter your first name.", middlename : "Enter your middle name.", lastname : "Enter your last name.", address : "Enter your address." } });
});