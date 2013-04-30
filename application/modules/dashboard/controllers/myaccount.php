<?php

class Myaccount extends MX_Controller
{
    private $smodule;
    public function __construct()
    {
        parent::__construct();
        $this->smodule = 'dashboard';
        $this->load->library('session');
        $this->load->module("core/app");
        $this->load->module("site/template");
        $this->load->module("settings/logs");
        $this->app->use_css(array("source"=>"dashboard/dashboard","cache"=>false));
       
        $aOptions  = array(            
            'attributes'=>array('data-main' => $this->environment->assets_path.'site/js/apps/r_setup'),
            'source' => 'site/libs/require'
        );

        $this->app->use_js($aOptions);
        $this->app->use_js(array("source"=>"site/libs/jquery.validate.mod","cache"=>false));
    }

    public function index()
    {   
        $this->load->model("getclass");
         $this->load->model("myaccount_model");
        self::display();
        
    }
    
    public function display()
    {
        $this->app->write_style('#update_form{ display:none;}');
        
        $sWhere = 'AND tu_idx = '.$this->session->userdata('userid');
        $aDbData = $this->myaccount_model->get_tb_user_data($sWhere);
        // echo '<pre>';
         // var_dump($aDbData);
         // echo '</pre>';
         $aDbData['employee_name'] = ucwords($aDbData['te_fname']).' '.ucwords($aDbData['te_mname']).' '.ucwords($aDbData['te_lname']);
         
         $aDbData['te_fname'] = ucwords($aDbData['te_fname']);
         $aDbData['te_mname'] = ucwords($aDbData['te_mname']);
         $aDbData['te_lname'] = ucwords($aDbData['te_lname']);
         
         $aResultData = $aDbData;
         
        
        $aData['user_info'] = $aResultData;
        
        $this->logs->set_log('Profile','READ');
         $this->template->header();
        $this->template->sidebar();
        $this->template->breadcrumbs();
        $this->app->content('dashboard/v_'.strtolower(__CLASS__),$aData);
        $this->template->footer();
    }
    
    
    public function message_return(){
    
        if(isset($_GET['type'])){
            switch($_GET['type']){
                case 'add':
                    $this->common->set_message("Saved Successfully!","message-container","success");
                break;
                
                case 'edit':
                    $this->common->set_message("Modified Successfully!","message-container","success");
                break;
                
                
                 case 'delete':
                    $this->common->set_message("Deleted Successfully!","message-container","success");
                break;
                
                case 'nochange':
                    $this->common->set_message("No change(s) had been made.","message-container","warning");
                break;
                
                case 'failed':
                    $this->common->set_message("Failed","message-container","warning");
                break;
                
                default:
                    $this->common->set_message("Unknown message type","message-container","warning");
            }
            
            redirect($_SERVER['HTTP_REFERER']);
        }
    
    }
    
    
    
    
}
