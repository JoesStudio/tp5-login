<?php
namespace joeStudio\login\config;


class Config
{
    public static $config = [
        //配置登录模块的路由**
        //验证码配置
        'captcha' => [
            //验证码的字符集
            'codeSet' => '23456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
            //设置验证码大小
            'fontSize' => 18,
            //添加混淆曲线
            'useCurve' => false,
            //设置图片的高度、宽度
            'imageW' => 150,
            'imageH' => 35,
            //验证码位数
            'length' =>4,
            //验证成功后重置
            'reset' =>false
        ],
    ];

}