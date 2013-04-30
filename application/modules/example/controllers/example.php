<?php

class Example extends MX_Controller
{
   public $ilimit;
   public function __construct()
   {
      parent::__construct();
      $this->ilimit = 5;
      $this->load->model("example_model");
      $this->load->module("core/app");
      $this->load->module("site/template");
      $this->app->use_css(array("source"=>"example/style","cache"=>true));
      $this->app->use_js(array("source"=>"site/libs/jquery.validate.min","cache"=>true));
      $this->app->use_js(array("source"=>"example/example","cache"=>false));
   }
   
   public function index()
   {
 
      $adata = array();
      $adata['title'] = "Home";
      $this->app->header('example/header',$adata);
      $this->app->content('example/navigator');
      $this->app->content('example/index');
      $this->app->footer('example/footer');
   }

   public function content()
   {
      $adata = array();
      $adata['title'] = "Content";
      $this->common->set_field_list(array("f"=>"fname","m"=>"mname","l"=>"lname","d"=>"date_created"),array("d"=>"desc"));
      $asort = $this->common->sql_orderby();
      
      $itotal_row = $this->example_model->get_count();
      $alimit = $this->common->sql_limit($itotal_row,$this->ilimit);
      
      $adata['aresult'] = $this->example_model->get_data($asort, $alimit);
      $adata['pager'] = $this->common->pager($itotal_row,$this->ilimit);
      
      $this->app->header('example/header',$adata);
      $this->app->content('example/navigator');
      $this->app->content('example/content');
      $this->app->footer('example/footer');
   }  

   public function add()
   {
      $adata = array();
      $adata['title'] = "Add";
      $this->app->header('example/header',$adata);
      $this->app->content('example/navigator');
      $this->app->content('example/add');
      $this->app->footer('example/footer');
   }
   
   public function ajax_test()
   {
      $adata['title'] = "AJAX";
      $this->app->header('example/header',$adata);
      $this->app->content('example/navigator');
      $this->app->content('example/ajax');

      $this->app->footer('example/footer');   
   }
   
   public function download()
   {
      $adata['title'] = "AJAX";
      $this->app->header('example/header',$adata);
      $this->app->content('example/navigator');
      $this->app->content('example/download');

      $this->app->footer('example/footer');   
   }  

   public function language()
   {
      $adata['title'] = "AJAX";
      $slang = ($this->input->get('lang')) ? $this->input->get('lang') : "en";
      
      $this->app->language('example/translate',$slang);
      $adata['message'] = $this->lang->line('MESSAGE');
      $this->app->header('example/header',$adata);
      $this->app->content('example/navigator');
      $this->app->content('example/language');
      $this->app->footer('example/footer');     
   }
   
   public function validation()
   {
      $adata['title'] = "AJAX";
      $slang = ($this->input->get('lang')) ? $this->input->get('lang') : "en";      
      $this->app->language('example/translate',$slang);
      $adata['message'] = $this->lang->line('MESSAGE');
      $this->app->header('example/header',$adata);
      $this->app->content('example/navigator');
      $this->app->content('example/validation');
      $this->app->footer('example/footer');       
   }
   
   public function upload()
   {
      $adata['title'] = "Upload";
      $this->app->header('example/header',$adata);
      $this->app->content('example/navigator');
      $this->app->content('example/upload');
      $this->app->footer('example/footer');       
   }   
}