<?php
namespace joeStudio\login\behavior;

use joeStudio\login\Login;
use think\Controller;

class CheckLogin extends Controller
{
    public function run(&$params)
    {
        !( new Login() )->checkLogin()
        &&
        strtolower(request()->controller()) != 'login'
        &&
        $this->error('请登录','index/login/index');

        ( new Login() )->checkLogin()
        &&
        strtolower(request()->controller()) == 'login'
        &&
        strtolower(request()->action()) == 'index'
        &&
        $this->success('已登录','index/index/index');
    }
}