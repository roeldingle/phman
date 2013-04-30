<?php
class User extends MX_Controller
{
    private $smodule;
    public function __construct()
    {
        parent::__construct();
        $this->smodule = 'settings';
        
        $this->load->module("core/app");
        $this->load->module("site/template");
        $this->load->module("settings/logs");
       
        $aOptions  = array(            
            'attributes'=>array('data-main' => $this->environment->assets_path.'site/js/apps/r_setup'),
            'source' => 'site/libs/require'
        );

        $this->app->use_js($aOptions);
        $this->app->use_js(array("source"=>"site/libs/table.sorter","cache"=>false));
        $this->app->use_js(array("source"=>"site/libs/jquery.validate.mod","cache"=>false));
    }

    public function index()
    {   
        $this->load->model("getclass");
        $this->load->model("modcustom");
        
        self::display();
    }
    
    
    public function display()
    {
        $this->logs->set_log('User','READ');
        
        $aData = self::display_data();
        
        $this->template->header();
        $this->template->sidebar();
        $this->template->breadcrumbs();
        $this->app->content($this->smodule.'/v_'.strtolower(__CLASS__),$aData);
        $this->template->footer();
    }
    
    
     public function display_data(){
     
        /*default row limit*/
        $iDefaultLimit = 10;
        $iDbTotRows = $this->modcustom->get_db_tb_count();
        
        /*main display array*/ 
        $aLimit = $this->common->sql_limit($iDbTotRows,$iDefaultLimit);

        if($aLimit['offset'] >= 0){         
            $sWhere = ' LIMIT '.$aLimit['offset'].','.$aLimit['limit'];
        }else{
            $sWhere = '';
        }       
        
        $aDbData = $this->modcustom->get_tb_user_data($sWhere);
        // echo"<pre>";
        // var_dump($aDbData);exit;
        
        
        /*modify the array return*/
        $aTbDisplayData = self::modify_data($aDbData);
        
        /*return variables*/
        $aReturnData['tb_content_rows'] = self::tb_string_content($aTbDisplayData);
        $aReturnData['pagination'] =  $this->common->pager($iDbTotRows,$iDefaultLimit);
        
        return $aReturnData;
    
    }
    
    private function modify_data($aData){
        /*modify data*/
        foreach($aData as $key=>$val){
            //$aData[$key]['employee_fullname'] = ucwords($val['te_lname']).', '.ucwords($val['te_fname']);
           // $aData[$key]['tu_username'] = ucwords($val['tu_username']);
            $aData[$key]['tu_date_created'] = date("m/d/y h:i:s",$val['tu_date_created']);
            $aData[$key]['index'] = ($key+1);
        
        }
        
        return $aData;
    
    }
    
    private function tb_string_content($aData){
        /*string setter*/
        $sData = '';
        
        if(!empty($aData)){
            /*loop for display*/
            foreach($aData as $key=>$val){
                $sData .= '<tr id="emp_id_'.$val['tu_idx'].'">
                        <td>';
                        
                        /*remove ability to delete logged in user*/
                        if($val['tu_idx'] != $this->session->userdata('userid')){
                            $sData .= '<input type="checkbox" name="user_idx[]" value="'.$val['tu_idx'].'" />';
                        }
                        
                        $sData .='</td>
                        <td>'.$val['index'].'</td>
                        
                        <td>'.$val['tu_username'].'</td>
                        <td>'.$val['tug_name'].'</td>
                        <td>'.$val['tu_date_created'].'</td>
                        <td class="last">
                            <a href="javascript:;" class="btn_vmd btn_vmd_2 btn_modify_user" title="Modify this user" alt="'.$val['tu_idx'].'">M</a>
                        </td>
                    </tr>';
            }
        }else{
            $sData .= '<tr><td colspan="6" style="text-align:center;" >No result(s) found.</td></tr>';
        }
        
        return $sData;
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
                
                default:
                    $this->common->set_message("Unknown message type","message-container","warning");
            }
            
            redirect($_SERVER['HTTP_REFERER']);
        }
    
    }
    
}
