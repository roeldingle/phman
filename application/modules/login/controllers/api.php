<?php

class Api extends MX_Controller
{
     public function __construct()
    {
        parent::__construct();
        /*load model class*/
        $this->load->model("getclass");
        
    }
   /***login the user***/
    public function sample_test(){
    
        /*post variables*/
        $auserdata['username']   = mysql_real_escape_string($this->input->post('username', TRUE));
        $auserdata['password']   = mysql_real_escape_string(hash("sha512",$this->input->post('password', TRUE))); #encripted
        
        /*change*/
        $query_string = sprintf("SELECT * FROM tbl_user WHERE tu_username='%s' AND tu_password='%s' && tu_active = 1 LIMIT 1",$auserdata['username'],$auserdata['password']);
            
        $query = $this->db->query($query_string);

        $auserdbdata  = $query->row_array();
        
        
        if(!empty($auserdbdata)){
            /*session data*/
            $this->session->set_userdata('userid',$auserdbdata['tu_idx']);
            $this->session->set_userdata('employeeid',$auserdbdata['tu_te_idx']);
            $this->session->set_userdata('usergradeid',$auserdbdata['tu_tug_idx']);
        }
        
        echo json_encode($auserdbdata);
    }

}