<?php

class Login_checker extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        self::index();
    }

    public function index()
    {   
        if($this->session->userdata('userid') != true){
            redirect('login');
        }
        
    }

}