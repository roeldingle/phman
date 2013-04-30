<?php

class Category_management extends MX_Controller
{
    private $smodule;
    public function __construct(){
        parent::__construct();
        $this->smodule = 'stock';
        
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
        $this->app->use_css(array("source"=>$this->smodule."/cat-man","cache"=>false));
        
        $this->load->model("getclass");
        $this->load->model("getmod_category_management");
        
        $this->load->library('common_mod_lib');
        
    }

    public function index(){    
        /*session message return*/
        self::message_return();
        
        $this->logs->set_log('Stock','READ');
        $aData['main_category'] = $this->cat_mngt_tb_data();
        $this->template->header();
        $this->template->sidebar();
        $this->template->breadcrumbs();
        $this->app->content($this->smodule.'/cat-management/v_'.strtolower(__CLASS__),$aData);
        $this->template->footer();
    }
    
    public function cat_mngt_tb_data(){
    
        $aData = $this->getmod_category_management->get_maincategory_data(null,'rows');
        
        $sReturnData = '';
        
         foreach($aData as $key=>$val){ 
         
             $aSubMenu = $this->getmod_category_management->get_subcategory_data($val['tsmc_smcid']);
             
            $sReturnData .= '<div class="menu_first_level">';
            $sReturnData .= '<a href="javascript:void(0);" alt="main" class="menu_first_level_a" name="'.$val['tsmc_smcid'].'" title="'.$val['tsmc_name'].'" >'.ucwords($val['tsmc_name']).' ('.count($aSubMenu).')</a>';
            
            
                        
            if(!empty($aSubMenu)){
                foreach($aSubMenu as $k=>$v){ 
                    $aStock = $this->getmod_category_management->get_stock_by_subcategory($v['tssc_sscid'],'rows');
                    $sReturnData .= '<div class="menu_second_level">';
                    $sReturnData .= '<a href="javascript:void(0);" class="menu_second_level_a" alt="sub" name="'. $v['tssc_sscid'].'" title="'.$v['tssc_name'].'" >'.ucwords($v['tssc_name']).' ('.count($aStock).')</a>';
                    $sReturnData .= '</div>';
            
                }
            } 
           $sReturnData .= '</div>';
        }
        return $sReturnData;
    }
    
    public function hardware(){
        define('MAIN_CATEGORY_ID','000009');
        self::initSubModule(__FUNCTION__);
    }
    
    public function accessories(){
        define('MAIN_CATEGORY_ID','000010');
        self::initSubModule(__FUNCTION__);
    }
    
    public function software(){
        define('MAIN_CATEGORY_ID','000011');
        self::initSubModule(__FUNCTION__);
    }
    
    public function furnitures(){
        define('MAIN_CATEGORY_ID','000012');
        self::initSubModule(__FUNCTION__);
    }
    
    private function initSubModule($sFunc){
    
        /*session message return*/
        self::message_return($sFunc);
        
        $aData = self::setData();
        $this->logs->set_log('Stock','READ');
        $this->template->header();
        $this->template->sidebar();
        $this->template->breadcrumbs();
        $this->app->content($this->smodule.'/cat-management/v_stock_list',$aData);
        $this->template->footer();
    
    }
    
     public function setData(){
     
        /*search*/
        $aSearch = self::search();
     
        /*default row limit*/
        $iDefaultLimit = 10;
        $iDbTotRows = $this->getmod_category_management->get_db_data_count(MAIN_CATEGORY_ID,$aSearch);

        /*main display array*/ 
        $aLimit = $this->common->sql_limit($iDbTotRows,$iDefaultLimit);
        
        /*main database array data*/
        $aDbData = $this->getmod_category_management->get_db_tb_data(MAIN_CATEGORY_ID,$aLimit,$aSearch);
        
        /*return variables*/
        $aReturnData['db_main_cat_data'] = $this->getmod_category_management->get_maincategory_data(MAIN_CATEGORY_ID);
        $aReturnData['tb_content_rows'] = self::generateTableData($aDbData,$aLimit['offset']);
        $aReturnData['pagination'] =  $this->common->pager($iDbTotRows,$iDefaultLimit, array('active_class'=>'current'));
        $aReturnData['cur_url'] = $this->common_mod_lib->curPageURL();
        $aReturnData['search_breadcrumbs'] = $this->search_breadcrumbs();

        return $aReturnData;
        
    }
    
    public function generateTableData($aDbData,$iPage){
    
        /*get stock item data string*/
        $sData = '';
        
        if(!empty($aDbData)){
            foreach ($aDbData as $key=>$row)
            {
                $iCounter = ($key+1)+$iPage;
                $dLastUpdate = ($row->tsit_last_update == null) ? '--' : date('Y-m-d',$row->tsit_last_update);
                $sVerMod = (MAIN_CATEGORY_ID == "000011") ? $row->tsit_version : $row->tsit_model;
                $sData .= '
                    <tr>
                        <td><input type="checkbox" name="row_stock_id" class="row_stock_id" value="'.$row->tsit_siid.'" /></td>
                        <td>'.$iCounter.'</td>
                        <td>'.$row->tssc_name.'</td>
                        <td>'.$sVerMod.'</td>
                        <td>'.$row->te_fname.' '.$row->te_lname.'</td>
                        <td>'.$row->tshis_history.'</td>
                        <td>
                            <a href="javascript:void(0)" class="viewdetail_stock btn_vmd btn_vmd_1 get_stock" alt="view" stockitem_id="'.$row->tsit_siid.'" >V</a>
                            <a href="javascript:void(0)" class="modify_stock btn_vmd btn_vmd_2 get_stock" alt="modify" stockitem_id="'.$row->tsit_siid.'">M</a>
                            <a href="javascript:void(0)" class="delete-btn btn_vmd btn_vmd_3 delete_stock" alt="delete" stockitem_id="'.$row->tsit_siid.'">D</a>
                        </td>
                        <td>'.date('Y-m-d',$row->tsit_registered_date).'</td>
                        <td>'.$dLastUpdate.'</td>
                    </tr>
                ';
            }
        }else{
            $sData .= '<tr><td colspan="7">No result(s) found.</td></tr>';
        }
        
        return $sData;
        
    }
    
    public function search(){
    
        if(isset($_GET['search_field']) && isset($_GET['search_item'])){
            $aReturn = array(
                "field" => $_GET['search_field'],
                "item" => $_GET['search_item']
            );
        }else{
            $aReturn = null;
        }
        
        return $aReturn;
    }
    
    public function search_breadcrumbs(){
    
        if(isset($_GET['search_field'])){
            $sData ='';
            $sData .= '<strong>Search</strong>&nbsp;&raquo;&nbsp;';
            switch($_GET['search_field']){
                case "tsit_tssc_sscid":
                $sData .= 'Category&nbsp;&raquo;&nbsp;';
                $aData = $this->getclass->select('tbl_stock_sub_category','tssc_sscid = '.$_GET['search_item'],$bRow = "row");
                $sData .= $aData['tssc_name'];
                break;
                
                case "tsit_user_assigned":
                $sData .= 'Employee&nbsp;&raquo;&nbsp;';
                $aData = $this->getclass->select('tbl_employee','te_idx ='.$_GET['search_item'],$bRow = "row");
                $sData .= $aData['te_fname'].' '.$aData['te_lname'];
                break;
                
                default:
                $sData .= (MAIN_CATEGORY_ID == "000011") ? "Version" : "Model";
                $sData .= '&nbsp;&raquo;&nbsp;';
                $sData .= $_GET['search_item'];
                break;
            
            }
            return $sData;
        }
        
    
    }
    
    
    public function message_return($sFunc = null){
    
        if(isset($_GET['mess_return_type'])){
            switch($_GET['mess_return_type']){
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
            if($sFunc == null){
                redirect($base_url.$this->smodule.'/'.strtolower(__CLASS__));
            }else{
                redirect($base_url.$this->smodule.'/'.strtolower(__CLASS__).'/'.$sFunc);
            }
        }
    
    }
    
}
