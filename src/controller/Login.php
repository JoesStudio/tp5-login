<?php
namespace joeStudio\login\controller;

use filter\inputFilter;
use joeStudio\login\helper\CreateTable;
use joeStudio\login\helper\LoginHelper;
use joeStudio\login\logic\Login as loginObject;
use think\Controller;
use think\Hook;
use think\View;

class Login extends Controller
{

    public function __construct()
    {

        Hook::listen('check_login');

        //实例化视图引擎
        $this->view = new View([
        // 模板路径
        'view_path'    => dirname(__FILE__) . '/../view/login/',

        ],[
            '__URL__'    =>  'http://hplus.static.com/'
        ]);

        $this->loginObject = new loginObject(input('post.'));
        $this->helperObject = new LoginHelper();
        $this->createDbObject = new CreateTable();
    }

    public function index()
    {
       return $this->view->fetch('index');
    }

    public function register(){
        return $this->view->fetch('register');
    }

    public function doRegister(){

        // 返回JSON数据格式到客户端 包含状态信息

        $res = $this->loginObject->register();

        $res['url'] = url('login/index');

        echo json_encode($res);exit;
    }

    public function checkInput(){
        echo json_encode($this->loginObject->checkInput());exit;
    }

    public function doLogin(){

        $res = $this->loginObject->doLogin();

        if($res['status']){
            $res['url'] = url('login/home');
        }

        echo json_encode($res);exit;
    }

    public function logout(){
        $res = $this->loginObject->logout();

        if($res){
            $this->success('正在退出后台！',url('login/index'));
        }
    }

    public function home()
    {

        $url = url('login/logout');

        return "
        <p>登录成功</p>
        <a href='{$url}'>退出登录</a>
       ";
    }

    public function captchaCheck(){
        $verifycode = trim(input('post.verifycode'));
        //验证验证码
        if(!captcha_check($verifycode)){
            exit(json_encode(array('code'=>1,'msg'=>'验证码错误')));
        }
    }
}
