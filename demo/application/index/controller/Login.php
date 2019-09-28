<?php
namespace app\index\controller;

use joeStudio\login\LoginHelper;

class Login
{

    public function __construct()
    {
        $this->helperObject = new LoginHelper();
    }

    public function index()
    {
       return view();
    }

    public function test(){

        echo '<pre>';print_r($this->loginObject);exit;

    }

}
