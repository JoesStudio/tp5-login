<?php
namespace joeStudio\login\behavior;

use think\Controller;
use joeStudio\login\logic\Login;
use think\Request;

class CheckLogin extends Controller
{
    public function run(&$params)
    {



        $dispatch = Request::instance()->dispatch();

        if($dispatch['method'][0] == "\joeStudio\login\controller\Login"){

            if($dispatch['method'][1] == 'index' || $dispatch['method'][1] == 'register'){
                ( new Login() )->checkLogin() && $this->success('已登录',url('login/home'));
            }

        }else{
            !( new Login() )->checkLogin() && $this->error('请登录',url('login/index'));
        }

    }
}