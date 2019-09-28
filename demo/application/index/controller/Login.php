<?php
namespace app\index\controller;

use joeStudio\login\Login as loginObject;

class Login
{

    public function __construct()
    {
        $this->loginObject = new LoginObject();
    }

    public function index()
    {
       return view();
    }

    public function test(){

        echo '<pre>';print_r($this->loginObject);exit;

    }

}
