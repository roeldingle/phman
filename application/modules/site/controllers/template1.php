<?php

class Template1 extends MX_Controller
{
   public function __construct()
   {
      parent::__construct();
      $this->load->module('core/app');
      $this->load->model('site_model');
   }

   public function breadcrumbs()
   {
      $auri = $this->uri->segment_array();         
      $smodulename = $auri[1];
      $itotal_segments = $this->uri->total_segments();
      $scontroller_name = $this->router->fetch_class();      
      $smethod_name = $this->router->fetch_method();      
      $asearch = array($scontroller_name,$smethod_name);      
    
      $sbreadrumbs = "";      
      $sother_uri = "";         
      $ilast = 0;      
      $iparent_idx = 0;
      
      $subdir = "/";
      
      foreach( $auri as $key => $val ) {
         if( in_array( $val , $asearch ) ) {
         
            if ( $val == $scontroller_name ) {      
               if( $key == 3 ) {
                  $subdir =  '/' . $auri[$key-1] . '/';
               }

               $abreadcrumbs = $this->site_model->get_parent_breadcrumbs($smodulename,$scontroller_name);
               if($abreadcrumbs){
                  $sbreadrumbs .= '<a href="' . base_url() . $smodulename . $subdir .  $val .'">' . $abreadcrumbs->tb_label . '</a> ';               
                  $iparent_idx = $abreadcrumbs->tb_idx;
                  $ilast = $key;
               }else{
                  return false;
               }
            }
            
            if ( $val == $smethod_name ) {       
               $abreadcrumbs = $this->site_model->get_sub_breadcrumbs($smodulename,$smethod_name,$iparent_idx);
                  if($abreadcrumbs){     
                  $sbreadrumbs .= ' &raquo; <a href="' . base_url() . $smodulename . $subdir . $scontroller_name . '/' . $val .'">' . $abreadcrumbs->tb_label . '</a>';                     
                  $ilast = $key;
               }else{
                  return false;
               }
            }
         }
      }

      if ( $ilast ) {
         $surl = base_url() . $smodulename . '/' . $scontroller_name . '/' . $smethod_name . '/';
         for( $i = $ilast + 1 ; $i <= $itotal_segments ; $i++ ) {
            $sother_uri .= $auri [ $i ] . '/';
            $sbreadrumbs .= ' &raquo; <a href="' . base_url() . $smodulename . $subdir . $scontroller_name . '/' . $smethod_name . '/' . $sother_uri .'">' . $auri[ $i ] . '</a>';            
         }
      }      
      return $sbreadrumbs;      
   }
}