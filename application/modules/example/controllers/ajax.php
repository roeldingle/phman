<?php

class Ajax extends MX_Controller
{
   public function __construct()
   {
      parent::__construct();
   }
   
   public function _remap()
   {
      show_404();
   }
   
   public function ajax_value()
   {
      echo "Hi, i came form ajax controller!";
   }
}