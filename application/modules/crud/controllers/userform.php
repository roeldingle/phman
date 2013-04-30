<?php
class Userform extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->module('site/site');
		$this->load->model('mdl_userform');
    }

    public function add()
    {
        // return $this->mdl_userform->addUser();
        echo json_encode(array(1,2,4));
    }
}