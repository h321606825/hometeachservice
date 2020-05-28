<?php
namespace App\Http\Controllers\Api\Admin\Passport;


use App\Http\Controllers\Api\ConstVariable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Service\Admin\UserInfoService;
use Unit\Checkunit;
use Unit\Jwtunit;
use Unit\Stringunit;

class PassportController extends Controller
{
    public $loginCheck = false;

    /**
     * @return PassportController
     * 后台登录
     */
    public function login(){
        $id = $this->req['account'];
        $password = $this->req['password'] ?? '';
        $captcha = strtolower($this->req['captcha'] ?? '');
        $type = $this->req['type'] ?? 1;
        if(!array_key_exists($type,ConstVariable::TYPE)){
            return $this->resp(1,'错误的场景类型');
        }
        Checkunit::verification($id,-1,'账号',strlen($id));
        Checkunit::verification($password,-1,"密码",strlen($password));
        Checkunit::verification($captcha,-1,"验证码",4);
        $captchaKey = md5(ConstVariable::TYPE[$type].$_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);
        $captchaInCache = Cache::get($captchaKey);
        if(empty($captchaInCache)){
            return $this->resp([
                'code' => 1,
                'msg' => '您的验证码已过期，请重新获取'
            ]);
        }
        if($captcha != $captchaInCache){
            Cache::forget($captchaKey);
            return $this->resp(1,'您的图片验证码错误，请重新获取！' . $captchaInCache);
        }
        $adminInfo = UserInfoService::getAdmininfoById($id);
        if(empty($adminInfo)) {
            Cache::forget($captchaKey);
            return $this->resp([
                'code' => 1,
                'msg' => '您的账号有误，请检查您的账号'
            ]);
        }
        if(!Hash::check($password,$adminInfo[0]['password'])){
            Cache::forget($captchaKey);
            return $this->resp([
                'code' => 1,
                'msg' => '您的密码有误，请检查您的账号'
            ]);
        }
        $token = Jwtunit::getToken('isAdmin',1800);
        Cache::put(md5($token),$id . 'isAdmin',30);
        return $this->resp(['token'=>$token]);
    }

    /**
     * @return PassportController
     * 后台注册
     */
    public function add(){
        $id = $this->req['id'];
        $password = $this->req['password'];
        if(empty($id)){
            return $this->resp(1,'账号不能为空');
        }
        if(empty($password)){
            return $this->resp(1,'密码不能为空');
        }

        $res = UserInfoService::addAdmin($id,Hash::make($password));
        if($res){
            return $this->resp([
                'code'=>200,
                'msg' => "添加管理员成功"
            ]);
        }else{
           return $this->resp([
                'code'=>1,
                'msg'=>"添加管理员失败",
            ]);
        }
    }

    /**
     * @return PassportController
     * 后台注销
     */
    public function logout(){
        $token = $this->req['token'];
        if(Cache::has($token)){
            Cache::forget($token);
        }
        return $this->resp(200,'注销成功');
    }
}