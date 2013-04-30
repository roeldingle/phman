<?php
class Execmod_category_management extends Getclass{

    private function mainQuery(){
    
    }
    
    public function save_data($sTbName,$aData){
        
        $this->db->insert($sTbName,$aData); 
        return ($this->db->affected_rows() != 1) ? false : true;
    
    }
    
    public function update_data($sTbName,$aData,$aWhere){
    
        $this->db->where($aWhere['field'], $aWhere['value']);
        $this->db->update($sTbName,$aData); 
        return ($this->db->affected_rows() == 0) ? false : true;
        
    }
    
    /*just remove from list (Update to active=0)*/
    public function delete_data($sTbName,$sWhere,$aData,$sSet){
        
        $this->db->query('UPDATE '.$sTbName.' SET '.$sSet.' WHERE '.$sWhere.' IN('.$aData.')');
        return ($this->db->affected_rows() == 0) ? false : true;
        
    }
	
	


}