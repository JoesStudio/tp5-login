<?php
namespace joeStudio\login\config;

class Route
{
    public static $route = [
        //配置登录模块的路由**
        '[login]'   =>  [
            'index'         =>  ['\joeStudio\login\controller\Login@index',['method'=>'get']],
            'register'      =>  ['\joeStudio\login\controller\Login@register',['method'=>'get']],
            'home'          =>  ['\joeStudio\login\controller\Login@home',['method'=>'get']],
            'logout'        =>  ['\joeStudio\login\controller\Login@logout',['method'=>'get']],
            'checkInput'    =>  ['\joeStudio\login\controller\Login@checkInput',['method'=>'post']],
            'doLogin'       =>  ['\joeStudio\login\controller\Login@doLogin',['method'=>'post']],
            'doRegister'    =>  ['\joeStudio\login\controller\Login@doRegister',['method'=>'post']],
        ]

    ];

}