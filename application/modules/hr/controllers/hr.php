<?php

class Hr extends MX_Controller
{
    private $smodule;
    public function __construct()
    {
        parent::__construct();
        $this->smodule = strtolower(__CLASS__);
    }

    public function index()
    {
        $sdefault_page = $this->smodule.'/employee';
        redirect($sdefault_page);
    }

}