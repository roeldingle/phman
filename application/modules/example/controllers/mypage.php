<?php

class Mypage extends MX_Controller
{
   public function __construct()
   {
      parent::__construct();
      $this->load->module('core/app');
      $this->load->module("site/template");
      echo $this->template->generate_breadcrumbs();   
   }
   
   public function index()
   {
   
   }
}