<?php
class Getclass extends CI_Model{
	/*process aData*/
	public function processArrayData($aData){
		if($aData){
			$sField = "";
			$sValue = "";
			foreach($aData as $field => $value)
			{
				$sField .= "`".$field."`,";
				$sValue .= "'".$value."',";
			}
		}else{
			$sData = "";
		}
		return $sData = "(".substr($sField,0,(strlen($sField)-1)).") VALUES (".substr($sValue,0,(strlen($sValue)-1)).")";
	}
	
	/*process aData for update*/
	public function processAData($aData){
		if($aData){
			foreach($aData as $field => $value)
			{
				$sData .= "".$field."=";
				$sData .= "'".$value."',";
			}
		}else{
			$sData = "";
		}
		return $sData = substr($sData,0,(strlen($sData)-1));
	}
	/*
	 desc: to give a WHERE condition for the get function
	@param $sWhere = ex.("pm_userid = '".$this->sUser."'");
	return $sWhere = a where condition for the query;
	*/
	public function setWhere($sWhere){
		if($sWhere){
			$sWhere = ($sWhere ? "WHERE ".$sWhere : "");
		}else{
			$sWhere = "";
		}
		return $sWhere;
	}
	/*
	 desc: select data from the databse
	@param $sTable = ex.("tb_sample");
	@param $sWhere = ex.("pm_userid = '".$this->sUser."'");
	return $sWhere = a where condition for the query;
	*/
	public function select($sTable,$sWhere = '',$bRow = "rows"){
		
		$sWhere = $this->setWhere($sWhere);
		
		$sSql = "SELECT * FROM ".$sTable." ".$sWhere;
		$mResult = $this->db->query($sSql);
		
		return ($bRow == "row")?$mResult->row_array():$mResult->result_array();
	
	}
	
	
	


}