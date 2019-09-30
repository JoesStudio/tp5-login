<?php
/**
 * Created by dh2y.
 * Blog: http://blog.csdn.net/sinat_22878395
 * Date: 2018/4/26 0026 16:26
 * For: 登录模块
 */

namespace joeStudio\login;


use joeStudio\login\model\Admin;
use joeStudio\login\validate\Admin as adminValidate;
use think\Config;
use think\crypt\Crypt;
use think\Db;
use think\Session;
use joeStudio\login\helper\LoginHelper;
use think\Validate;

class Login
{
    protected $config = [
        'crypt' => 'wstudio',      //Crypt加密秘钥
        'auth_uid' => 'authId',      //用户认证识别号(必配)
        'not_auth_module' => 'login', // 无需认证模块
        'user_auth_gateway' => 'login/index', // 默认网关
        //登录场景默认用户名登录  'user_name' 用户名登录 'mobile' 手机号登录   'user_name|mobile'用户名或者手机号登录
        'scene'     =>   'user_name'
    ];

    protected $model;           //登录模型
    protected $validate;        //登录验证器
    protected $member;          //后台用户
    protected $error;           //错误信息
    protected $scene;           //登录场景

    protected $input;           //输出参数
    protected $output = [
        'status'=>1,                //返回状态
        'msg'=>'用户名可以使用',      //返回描述
        'data'=>[]                  //返回数据
    ];          //输出参数

    /**
     * 加载配置
     * login constructor.
     * @param $model
     */
    public function __construct( $data = []){
        if ($config = Config::get('login')) {
            $this->config = array_merge($this->config,$config);
        }

        $this->input = $data;

        $this->model = new Admin();
        $this->validate = new adminValidate();
    }

    /**
     * 记住登录账户密码
     */
    public function remember(){
        if(!cookie('remember')){
            return false;
        }
        $remember = Crypt::decrypt(cookie('remember'),$this->config['crypt']);
        return unserialize($remember);
    }

    /**
     * 场景登录
     * @param $data
     * @param \Closure|null $function
     * @param string $scene
     * @return array
     */
    public function sceneLogin($data,$scene='',\Closure  $function=null){
        //判断登录场景是否存在
        $this->config['scene'] = ($scene!='')?$scene:$this->config['scene'];

        return $this->doLogin($data,$function);
    }


    /**
     * 登录操作
     * @param $data
     * @param \Closure|null $function 回调函数
     * @return array
     */
    public function doLogin(\Closure  $function=null){
        $result = $this->checkMember();
        if ($result==false){
            $this->output['status'] = false;
            $this->output['msg'] = $this->validate->getError();
            $this->output['data'] = [];

            return $this->output;
        }
        $result = $this->checkPass($this->input['password']);
        if($result['status']==true){

            session($this->config['auth_uid'], $this->member['id']);
            session("user_name", $this->member['user_name']);

            //登录日志更新
            $data['last_login_time'] = time();
            $data['login_ip'] = LoginHelper::get_client_ip(0,true);



            $this->model->where([
                'id'    =>  $this->member['id']
            ])->update($data);

            //如果记住账号密码-vue.js复选框传的是true和false字符串
            if($this->input['remember']=='true'){
                $member['user_name'] = $this->member['user_name'];
                $member['password'] = $this->member['password'];
                $member['remember'] = $this->member['remember'];
                $remember = Crypt::encrypt(serialize($member),$this->config['crypt']);
                cookie('remember', $remember);//记住我
            }else{
                cookie('remember', null);
            }

            if($function!=null){
                $function($this->member);
            }
            $this->output['status'] = 1;
            $this->output['msg'] = '登录成功！';
            $this->output['data'] = [$this->member];
        }else{
            $this->output['status'] = false;
            $this->output['msg'] = '登录失败！';
            $this->output['data'] = [];
        }

        return $this->output;
    }

    /**
     * 退出登录
     */
    public function logout(){
        session($this->config['auth_uid'], null);
        session("user_name", null);
        session(null);

        return true;
    }

    /**
     * 检验用户
     * @param $data
     * @return array
     */
    public function checkMember(){

        $result = $this->validate->scene('login')->check($this->input);

        if ($result==false){

            $this->output['status'] = false;
            $this->output['msg'] = $this->validate->getError();
            $this->output['data'] = [];

            return $this->output;
        }

        //按照登录场景来区分
        $map[$this->config['scene']] = $this->input['user_name'];
        $map['status'] = 1;
        $this->member = $this->model->where($map)->find();
        if ( $this->member){

            $this->output['status'] = true;
            $this->output['msg'] = '登录成功';
            $this->output['data'] = [];

            return $this->output;
        }

        $this->output['status'] = false;
        $this->output['msg'] = '用户不存在或被禁用';
        $this->output['data'] = [];

        return $this->output;
    }

    /**
     * 检查密码是否正确
     * @param $password
     * @return array
     */
    public function checkPass($password){
        if( $this->encryption($password)!= $this->member['password']){
            return ['status'=>false,'message'=>'密码错误'];
        }
        return ['status'=>true,'message'=>'恭喜！密码正确'];
    }


    /**设置错误信息
     * @param $message
     */
    public function setError($message){
        $this->error = $message;
    }

    /**获取错误信息
     * @return mixed
     */
    public function getError(){
        return $this->error;
    }

    public function register(\Closure  $function = null){

        $result = $this->validate->check($this->input);

        if ($result==false){

            $this->output['status'] = false;
            $this->output['msg'] = $this->validate->getError();
            $this->output['data'] = [];

            return $this->output;
        }

        if($result==true){

            //注册用户
            $this->member['user_name'] = $this->input['user_name'];
            $this->member['password'] = $this->encryption($this->input['password']);
            $this->member['mobile'] = $this->input['mobile'];
            $this->member['login_ip'] = LoginHelper::get_client_ip(0,true);
            $this->member['last_login_time'] = time();
            $this->member['create_time'] = time();
            $this->member['update_time'] = time();

            $res = $this->model->save($this->member);

            if($res){
                $user_id = $this->model->getLastInsID();

                //如果记住账号密码-vue.js复选框传的是true和false字符串
                if($this->input['remember']=='true'){
                    $member['user_name'] = $this->input['user_name'];
                    $member['password'] = $this->input['password'];
                    $member['remember'] = $this->input['remember'];
                    $remember = Crypt::encrypt(serialize($member),$this->config['crypt']);
                    cookie('remember', $remember);//记住我
                }else{
                    cookie('remember', null);
                }

                if($function!=null){
                    $function($this->member);
                }

                return [ 'status'=>1, 'msg'=>'注册成功', 'data'=>[]];
            }
        }
    }

    /**
     * 检查用户名是否已经被使用
     *
     * @param $data
     * @return array
     */
    public function checkInput(){

//        $this->validate->scene($this->input['check_key'], [$this->input['check_key']]);
//        $res = $this->validate->scene($this->input['check_key'])->check($this->input);

        $res = $this->validate->_checkItem($this->input['check_scene'],$this->input['check_key'],$this->input);

        if(!$res['status']){

            $this->output['status'] = false;
            $this->output['msg'] = $res['msg'];
            $this->output['data'] = [$this->input];

            return $this->output;
        }else{
            $this->output['status'] = true;
            $this->output['msg'] = $res['msg'];
            $this->output['data'] = [$this->input];

            return $this->output;
        }
    }

    /**
     * @param $password
     * @return string
     */
    public function encryption($password){
        return md5($password);
    }

    /**
     * @return bool
     */
    public function checkLogin(){

        return Session::get($this->config['auth_uid'])?true:false;

    }
}