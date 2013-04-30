<?php

class Api extends MX_Controller
{
     public function __construct()
    {
        parent::__construct();
        /*load model class*/
        $this->load->model("getclass");
        $this->load->module("settings/logs");
        
    }
    
    public function check_username(){
    
        $getusername = $this->input->post('username', TRUE);
        $getuseridx = $this->input->post('useridx', TRUE);
        
        if($getuseridx != 'null'){
            $this->db->where('tu_idx !=',$getuseridx);
        }
        
        $this->db->where('tu_username',$getusername);
        $query = $this->db->get('tbl_user');
        $bChecker = ($query->num_rows() > 0) ? true : false;
        
        
        
        echo json_encode($bChecker);
        
    
    }
   /***login the user***/
    public function get_users()
    {
    
        $auserdbdata  = $this->getclass->select('tbl_user');
        
        foreach($auserdbdata as $key=>$val){
            $auserdbdata[$key]['tu_date_created'] = date("m/d/y h:i:s",$val['tu_date_created']);
        }
        
        echo json_encode($auserdbdata);
    }
    
    /***login the user***/
    public function get_user()
    {
        $sIdx = $this->input->post('user_id', TRUE);
        $squery = "SELECT 
                tu_idx,
                tu_tug_idx,
                tu_username,
                tu_password,
                tu_date_created,
            	te_idx,
                te_employee_id,
                te_fname,
                te_lname,
                tecr_tp_idx,
                tp_idx,
                tp_position
                 FROM
                    tbl_user as tu
                    LEFT JOIN
                    tbl_employee as te
                    ON
                    tu.tu_te_idx = te.te_idx
                    LEFT JOIN
                    tbl_employee_company_record as tecr
                    ON
                    te.te_idx = tecr.tecr_te_idx
                    LEFT JOIN
                    tbl_position as tp
                    ON
                    tecr.tecr_tp_idx = tp.tp_idx WHERE tu_idx = ".$sIdx."
            ";
        /*employee data*/
        $auserdbdata['employee']  = $this->getclass->query_db($squery);
        
        foreach($auserdbdata['employee'] as $key=>$val){
            $auserdbdata['employee'][$key]['label'] = $val['te_fname'].' '.$val['te_lname'].' ('.$val['tp_position'].')';
            $i = 3;
            $mask = preg_replace ( "/\S/", "*", $val['tu_password'] );
            $auserdbdata['employee'][$key]['tu_pass_display'] = substr_replace($val['tu_password'],substr($mask, $i),$i,strlen($val['tu_password']));
        }
        
        $auserdbdata['grade']  = $this->getclass->select('tbl_user_grade');
      
        echo json_encode($auserdbdata);
    }
    
    public function get_grade_emp()
    {
    
        $auserdbdata['grade']  = $this->getclass->select('tbl_user_grade');
        
         $squery = "SELECT 
            	te_idx,
                te_employee_id,
                te_fname,
                te_lname,
                tecr_tp_idx,
                tp_idx,
                tp_position
                 FROM
                    tbl_employee as te
                    LEFT JOIN
                    tbl_employee_company_record as tecr
                    ON
                    te.te_idx = tecr.tecr_te_idx
                    LEFT JOIN
                    tbl_position as tp
                    ON
                    tecr.tecr_tp_idx = tp.tp_idx
            ";
        /*employee data*/
        $auserdbdata['employee']  = $this->getclass->query_db($squery);
        
        
        foreach($auserdbdata['employee'] as $key=>$val){
            $auserdbdata['employee'][$key]['label'] = $val['te_fname'].' '.$val['te_lname'].' '.$val['tp_position'];
            $auserdbdata['employee'][$key]['te_fname'] = ucwords($val['te_fname']);
            $auserdbdata['employee'][$key]['te_lname'] = ucwords($val['te_lname']);
            $auserdbdata['employee'][$key]['tp_position'] = ucwords($val['tp_position']);
        }
        
        echo json_encode($auserdbdata);
    
 
  
    }
    
    public function save_user()
    {
        /*post variables*/
        $getdata = $this->input->post('getdata', TRUE);
        
        $auserdata = array(
                'tu_tug_idx' => $getdata['user_grade'],
                'tu_username' =>  $getdata['username'],
                'tu_date_created' => ($getdata['date_created'] != 'new_user') ? $getdata['date_created']: time(),
                'tu_active' => 1
            );
        
        switch($getdata['form_action']){
        
            case 'add':
                //$auserdata['tu_te_idx'] = $getdata['emp_id'];
                $auserdata['tu_te_idx'] = 00000;
                $auserdata['tu_password'] = hash("sha512",$getdata['password']);#encripted
                
                $breturn = $this->db->insert('tbl_user',$auserdata);
                $this->logs->set_log('New user','CREATE');
                break;
            
            case 'edit':
                $this->db->where('tu_idx', $getdata['emp_id']);
                
                /*checck if will change password*/
                if($getdata['change_pass_flag'] == 1){
                    $auserdata['tu_password'] = hash("sha512",$getdata['password']);#encripted
                }
                
                $this->db->update('tbl_user', $auserdata); 
                $this->logs->set_log('User #'.$getdata['emp_id'],'UPDATE');
                $breturn = ($this->db->affected_rows() > 0) ? true : false;
                break;
        }
        

        echo json_encode($breturn);
    }
    
    public function delete_user(){
    
        $sIdx = implode(",",$this->input->post('user_idx', TRUE));
        
    
        $breturn = $this->db->query("UPDATE tbl_user SET tu_active = '0' WHERE tu_idx IN(".$sIdx.")");
        $this->logs->set_log("User #{$sIdx}",'DELETE');
        echo json_encode($breturn);
    }
    
    /*settings->menu*/
    public function update_module_sequence(){
        
        /*post variables*/
        $aModuledata = $this->input->post('moduledata', TRUE);
        $aSubModuledata = $this->input->post('submoduledata', TRUE);
        
        foreach($aModuledata as $k=>$v){
            $aData[$k] = array(
                'tm_idx' => $v['tm_idx'],
                'tm_label' => $v['tm_label'],
                'tm_sequence' => ($k+1)
            );
        }
        
        $this->db->update_batch('tbl_module', $aData, 'tm_idx'); 
        
        $bUpdatedModule = $this->db->affected_rows();
        
        if($bUpdatedModule > 0){
            $this->logs->set_log('Menu settings','UPDATE');
        }
        
        foreach($aSubModuledata as $a=>$b){
            $aSubData[$a] = array(
                'tsu_idx' => $b['tsu_idx'],
                'tsu_label' => $b['tsu_label'],
                'tsu_sequence' => ($a+1)
            );
        }
        
        $this->db->update_batch('tbl_submenu', $aSubData, 'tsu_idx'); 
        
        $bUpdatedSubModule = $this->db->affected_rows();
        
        if($bUpdatedSubModule > 0){
            $this->logs->set_log('Submenu settings','UPDATE');
        }
       
        
        echo json_encode($bUpdatedModule + $bUpdatedSubModule);
    
    
    }
    
    /*end settings->menu*/
    
    public function array_flatten_recursive($array) 
    { 
       if (!$array) return false;
       $flat = array();
       $RII = new RecursiveIteratorIterator(new RecursiveArrayIterator($array));
       foreach ($RII as $key=>$value) $flat[$key] = $value;
       return $flat;
    }

}