<?php

class Statistic extends MX_Controller
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
        $this->load->model("getmod_statistic");
        $this->load->library('common_mod_lib');
        
    }

    public function index(){    
        $this->logs->set_log('Stock','READ');
        redirect($this->smodule.'/statistic/hardware');
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
        
        $aData = self::setData($sFunc);
        
        $this->logs->set_log('Stock','READ');
        $this->template->header();
        $this->template->sidebar();
        $this->template->breadcrumbs();
        $this->app->content($this->smodule.'/statistic/v_'.strtolower(__CLASS__),$aData);
        $this->template->footer();
    
    }
    
    public function setData($sFunc){
    
        /*search*/
        $aSearch = self::search_date();
        
        /*default row limit*/
        $iDefaultLimit = 10;
        $aOption['main_cat_id'] = MAIN_CATEGORY_ID;
        $sDistinctOption = 'tsit_model';
        $iDbTotRows = $this->getmod_statistic->get_db_data_count($aOption,$sDistinctOption);
        
        /*main display array*/ 
        $aLimit = $this->common->sql_limit($iDbTotRows,$iDefaultLimit);
        
        $aOption['main_cat_id'] = MAIN_CATEGORY_ID;
        $aOption['type'] = 'category';
        $sTb_list_category = $this->getmod_statistic->select_withnumrows_query($aOption,null,$aSearch);
        
        $aData['tb_list_category'] = $this->generateTableData($aOption['type'] ,$sTb_list_category,$aLimit['offset']);
        
        $aOption['type'] = 'model';
        $sTb_list_model = $this->getmod_statistic->select_withnumrows_query($aOption,$aLimit,$aSearch);
        $aData['tb_list_model'] = $this->generateTableData($aOption['type'] ,$sTb_list_model,$aLimit['offset']);
        
        $aData['category_name'] = $sFunc;
        $aData['pagination'] =  $this->common->pager($iDbTotRows,$iDefaultLimit, array('active_class'=>'current'));
        
        
        return $aData;
    }
    
    public function search_date(){
    
        if(isset($_GET['from_date']) && isset($_GET['to_date'])){
            $aReturn = array(
                "from_date" => strtotime($_GET['from_date']),
                "to_date" => strtotime((int)$_GET['to_date']+1440) #plus 1day
            );
        }else{
            $aReturn = null;
        }
        
        return $aReturn;
    }
    
    public function generateTableData($sType,$aDbData,$iPage){
        $sData = '';
        
        if(!empty($aDbData)){
            foreach ($aDbData as $key=>$row)
            {
                
               
                switch($sType){
                    case "category":
                        $sCountField = 'tsit_tssc_sscid';
                        $sCountItemId = $row->tsit_tssc_sscid;
                        $iCounter = ($key+1);
                    $sData .= '
                        <tr>
                            <td>'.$iCounter.'</td>
                            <td>'.$row->tssc_name.'</td>
                            <td class="last">'.$row->search_row_count.'</td>
                        </tr>
                    ';
                    break;
                    
                    case "model":
                        $sCountField = 'tsit_model';
                        $sCountItemId = $row->tsit_model;
                        $iCounter = ($key+1)+$iPage;
                        /*<td>'.$row->tsit_brand.'</td>*/
                    $sData .= '
                        <tr>
                            <td>'.$iCounter.'</td>
                            <td>'.$row->tssc_name.'</td>
                            <td>'.$row->tsit_model.'</td>
                             <td class="last">'.$row->search_row_count.'</td>
                        </tr>
                    ';
                    break;
                
                
                }
            }
        }else{
            $sData .= '<tr><td colspan="7">No result(s) found.</td></tr>';
        }
        
        return $sData;
        
    }
    
    
    
    public function message_return($sFunc){
    
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
            
            redirect($base_url.$this->smodule.'/'.strtolower(__CLASS__).'/'.$sFunc);
        }
    
    }
    
}