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
   
    
    public function update_account()
    {
        /*post variables*/
        $getdata = $this->input->post('getdata', TRUE);
        
        $aUpdateData = array(
            'tu_idx' => $getdata[0]['value'],
            'tu_password' => hash("sha512",$getdata[2]['value']) #encripted
            
        );
        
        
        $this->db->set('tu_password', $aUpdateData['tu_password']);   
        $this->db->where('tu_idx',$aUpdateData['tu_idx']);
        $this->db->update('tbl_user');  
        
        $bUpdated = $this->db->affected_rows();
        
        
        if($bUpdated > 0){
            $this->logs->set_log("Own Account",'UPDATE');
        }
       
       echo json_encode($aUpdateData);
       
    }
    
    public function get_ajax_tbdata(){
        
        /*post variables*/
        $sTable = $this->input->post('gettable', TRUE);
        
        $query = $this->db->get($sTable);
        
        echo json_encode($query->result_array());
        
    
    }
    

}