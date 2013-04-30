<?php

class Login extends MX_Controller
{
    private $smodule;
    public function __construct()
    {
        parent::__construct();
        $this->smodule = strtolower(__CLASS__);
        
        $this->load->module("core/app");
        $this->app->use_css(array("source"=>"core/style","cache"=>false));
        $this->app->use_css(array("source"=>"login/style","cache"=>false));
        
        $aoptions  = array(            
            'attributes'=>array('data-main' => $this->environment->assets_path.'site/js/apps/r_setup'),
            'source' => 'site/libs/require'
        );

        $this->app->use_js($aoptions);
        
    }

    public function index()
    {   
        if($this->session->userdata('userid') == true){
            redirect('dashboard');
        }else{
            $adata = array();
            $adata['title'] = "Home";
            $this->app->header($this->smodule.'/header');
            $this->app->footer($this->smodule.'/footer');
        }
        
    }
   

}
