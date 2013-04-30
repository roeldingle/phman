jQuery(document).ready(function(){
   $('.expense-sidebar-container').show();
   $('.expense-sidebar-ul').dcAccordion({
      eventType: 'click',
      autoClose: true,
      saveState: true,
      disableLink: true,
      speed: 'fast',
      cookie  : 'dcjq-accordion',
      showCount: true
   });
   
   /*triggers*/
   
   $('.logout_link').click(function(){
   
    Site.logout_clicked();
   });
   
   $('#logout_confirm').live('click',function(){
    Site.page_redirect('/login/logout');
   });
   
    $('.checkall').live('click',function () {
        $(this).parents('fieldset:eq(0)').find(':checkbox').attr('checked', this.checked);
    });
});