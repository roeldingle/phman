<?php
class Crud extends MX_Controller
{
	private $sModule;

    public function __construct()
    {
         parent::__construct();
         $this->sModule = strtolower(__CLASS__);
         $this->load->module('core/app');
         $this->load->module('site/template');
         $this->app->use_js(array("source"=>'crud/libs/require'));
         $this->app->use_js(array("source"=>'crud/apps/r_setup',"cache"=>true));
    }

    public function index()
    {
      $this->template->header();
      $this->app->content($this->sModule.'/main');
      $this->template->footer();
    }
}