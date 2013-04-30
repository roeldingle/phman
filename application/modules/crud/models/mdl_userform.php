<?php

class Mdl_userform extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

	public function addUser()
	{
	    $query = $this->db->query('SELECT * FROM tbl_user');
		return $query->result();
	}
}