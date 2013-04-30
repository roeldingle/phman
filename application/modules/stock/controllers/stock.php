<?php

class Stock extends MX_Controller
{
    private $smodule;
    public function __construct()
    {
        parent::__construct();
        $this->smodule = strtolower(__CLASS__);
    }

    public function index()
    {
        $sdefault_page = $this->smodule.'/category_management';
        redirect($sdefault_page);
    }

}