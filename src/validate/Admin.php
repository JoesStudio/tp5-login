<?php
namespace joeStudio\login\validate;

use think\Validate;

class Admin extends Validate
{
    protected $output;

    protected $rule = [
        'user_name'                 =>  'require|checkName',
        'password'                  =>  'require|length:6,16',
        'password_confirm'          =>  'require|length:6,16|confirm',
        'mobile'                    =>  'require|regex:^[1][0-9]{10}$',
        'verifycode|验证码'          =>  'require|captcha',
    ];

    protected $message = [
        'user_name.require'         =>  '用户名必须填写',
        'password.require'          =>  '密码不能为空',
        'password.length'           =>  '请输入6~16位有效字符',
        'password_confirm.require'  =>  '确认密码不能为空',
        'password_confirm.length'   =>  '请输入6~16位有效字符',
        'password_confirm.confirm'  =>  '两次密码不一致',
        'mobile.require'            =>  '手机号码不能为空',
        'mobile.regex'              =>  '手机格式有误',
        'verifycode.require'        =>  '必须填写验证码',
    ];

    protected $scene = [
        'login'     =>  ['user_name'=>'require|checkAdmin','password','verifycode'],
        'register'  =>  ['user_name','password','password_confirm','mobile'],
    ];

    // 自定义验证规则
    protected function checkName($value)
    {

        //按照登录场景来区分
        $map['user_name'] = $value;
        $member = db('admin')->where($map)->find();
        if ( $member){

            return '用户名已经存在!';
        }

        return true;

    }

    protected  function checkAdmin($value){
        $admin = db('admin')->where([
            'user_name' =>  $value
        ])->find();

        if(!$admin){
            return '该用户不存在或者已被冻结';
        }
        return true;
    }


    public function _checkItem($_scene,$_key,$data){
        if(array_key_exists($_scene,$this->scene)){
            if(array_key_exists($_key,$this->scene[$_scene])){
                $this->scene($_scene.'_'.$_key,[$_key=>$this->scene[$_scene][$_key]]);
                $res = $this->scene($_scene.'_'.$_key)->check($data);

                return $res ? ['status'=>true,'msg'=>'执行成功'] : ['status'=>false,'msg'=>$this->getError()];
            }elseif(in_array($_key,$this->scene[$_scene])){
                $this->scene($_scene.'_'.$_key,[$_key]);
                $res = $this->scene($_scene.'_'.$_key)->check($data);

                return $res ? ['status'=>true,'msg'=>'执行成功'] : ['status'=>false,'msg'=>$this->getError()];
            }
            return ['status'=>false,'msg'=>'不存在$_key'];
        }

        return ['status'=>false,'msg'=>'不存在$_scene'];
    }

}