<?php

class Site_model extends MX_Controller
{
   private $TBL_BREADCRUMBS = 'tbl_breadcrumbs';
   
   public function __construct()
   {
      parent::__construct();
   }
   
   public function get_parent_breadcrumbs($smodulename,$scontroller)
   {
      $this->db->select('*');
      $this->db->from($this->TBL_BREADCRUMBS);
      $this->db->where('tb_modulename',$smodulename);
      $this->db->where('tb_page',$scontroller);
      $query = $this->db->get();
      return $query->row();
   }
   
   public function get_sub_breadcrumbs($smodulename,$smethod_name,$iparent_idx)
   {
      $this->db->select('*');
      $this->db->from($this->TBL_BREADCRUMBS);
      $this->db->where('tb_modulename',$smodulename);
      $this->db->where('tb_page',$smethod_name);
      $this->db->where('tb_parent_idx',$iparent_idx);
      $query = $this->db->get();
      return $query->row();
   }   
   
   public function get_menu_data($user_module_access){
        
        switch($user_module_access){
            case '000000':
            $this->db->select('*');
            $this->db->from('tbl_module');
            $this->db->where('tm_active',1);
            $this->db->order_by("tm_sequence", "asc");
            $aMenu = $this->db->get();
            break;
            default:
            $aMenu = $this->db->query('SELECT * FROM tbl_module WHERE tm_idx = '.$user_module_access.' OR tm_idx = 000001 AND tm_active = 1 ORDER BY tm_sequence ASC');
        }
       return $aMenu->result_array();
       
   
   }
   
   public function get_submenu_data($sAction){
        
        $this->db->select('tm_idx');
        $this->db->from('tbl_module');
        $this->db->where('tm_dirname',$sAction);
        $query = $this->db->get();
        
        $iMenuIdx = $query->row_array();
   
        $this->db->select('*');
        $this->db->from('tbl_submenu');
        $this->db->where('tsu_tm_idx',$iMenuIdx['tm_idx']);
        $this->db->where('tsu_active',1);
        $this->db->order_by("tsu_sequence", "asc");
        $aSubMenu = $this->db->get();
        return $aSubMenu->result_array();
        
   
   }
   
   function get_select_data($sGetFields = null,$sTablename,$aWhere,$sRow = null){
        if($sGetFields != null){
            $this->db->select($sGetFields);
        }else{
            $this->db->select('*');
        }
        
        $this->db->from($sTablename);
        $this->db->where($aWhere[0],$aWhere[1]);
        $aReturn = $this->db->get();
        
        if($sRow != null){
            $aReturn = $aReturn->row_array();
        }else{
            $aReturn = $aReturn->result_array();
        }
        return $aReturn;
   
   }
   
   /*search the curl*/
	function search_arr($aArrData,$sSearch){

		$pattern = '/^'.$sSearch.'/i';
		$aReturn = array();

		foreach($aArrData as $key => $val){
			if(preg_match($pattern, $val['page'], $matches, PREG_OFFSET_CAPTURE)){
				array_push($aReturn,$val);
			}
		}
		return $aReturn;

	}

		/*download xml file*/
	function curlXml($sPath)
	{
		$sDataEJson = json_encode(simplexml_load_file($sPath));
		return json_decode($sDataEJson,true);
	}
}