<?php

class Environment
{
   public $assets_path;
   public $asset_path;
   public $exec_path;
   public $ajax_path;
   public $upload_path;
   public $request_path;
   public $getfile_path;
   public $module_path;
   public $module_assets_path;
   public $sjquery_js;
   public $sjquery_ui_js;
   public $sjquery_css;
   public $score_style;
   public $CI;

   public function __construct()
   {
      $this->CI =& get_instance();
      $asegment = $this->CI->uri->segment_array();
      $smain_module = "core/";
      $this->assets_path = base_url() . $smain_module . 'request/assets/';
      $this->asset_path = base_url() . $smain_module . 'request/assets';
      $this->exec_path = base_url() . $smain_module . 'request/exec/';
      $this->ajax_path = base_url() . $smain_module . 'request/ajax/';
      $this->request_path = base_url() . $smain_module . 'request/';
      $this->upload_path = base_url() . $smain_module . 'request/upload/';
      $this->getfile_path = base_url() . $smain_module . 'request/getfile/';
      $this->module_path = base_url() . $asegment[1] . '/';
      $this->module_assets_path = $this->assets_path . $asegment[1] . '/';
      
      $this->sjquery_js_url = $this->assets_path . $smain_module . 'plugins/jquery/js/jquery-1.7.1.min.js';
      $this->sjquery_ui_js_url = $this->assets_path . $smain_module . "plugins/jquery/js/jquery-ui-1.8.17.custom.min.js";
      $this->sjquery_ui_css_url = $this->assets_path . $smain_module .  "plugins/jquery/css/smoothness/jquery-ui-1.8.17.custom.css";
      $this->score_css = $this->assets_path . $smain_module .  "css/core-css.css";
      $this->ssite_js = $this->assets_path . $smain_module . "js/site.js";
      $ainfo['assets_path'] = $this->assets_path;
      $ainfo['exec_path'] = $this->exec_path;
      $ainfo['ajax_path'] = $this->assets_path;
      $ainfo['upload_path'] = $this->upload_path;
      $ainfo['module_path'] = $this->module_path;
      $ainfo['request_path'] = $this->request_path;
      $ainfo['module_assets_path'] = $this->module_assets_path;
   }
}
