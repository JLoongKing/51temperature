<?php
namespace app\index\controller;
use \think\Request;
use \think\Session;
use app\blog\model\User;
class Index extends Base
{
    //构造函数，在初始化时执行
    public function _initialize()
    {
        
        // $request = Request::instance();
        // if($request->action()=='dologin'||($request->action()=='review'&&$request->get('viewname')=="login")){
           
        // }else{
        //     if(Session::get('username')==null){
        //         $this->error('请登陆！','/tp50/public/blog/admin/review?viewname=login',5);
        //     }
        // }
    }
    //登陆控制器
    public function dologin(Request $request)
    {
        $username= Request::instance()->post('username');
        $password= Request::instance()->post('password');
        //密码的md5加密
        $password=md5($password."非甲即丁");
        $blogger=Blogger::get(['username'=>$username,'password'=>$password]);
        //登陆错误
        if($blogger==null){
            return json(['code'=>203,"info"=>"登陆失败，用户名或者密码错误！"]);
        }
        //保存登陆状态
        Session::set('username',$username);
        return json(['code'=>200,"info"=>"登陆成功即将跳转界面！"]);
    }

}