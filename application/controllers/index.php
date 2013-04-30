<?php

class Index extends MX_Controller
{
   public function __construct()
   {
      parent::__construct();
   }

   public function index()
   {
      redirect('/dashboard');
   }
   
}