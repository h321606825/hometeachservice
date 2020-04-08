<?php


namespace App\Http\Controllers\Api\User;


use App\Http\Controllers\Api\ConstVariable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Service\User\UserService;
use Unit\Checkunit;
use Unit\Jwtunit;

class UserController extends Controller
{
    /**
     * 学生注册
     * @return UserController
     */
    public function registerStu(){
        $base = json_decode($this->req['base'],true);
        $info = json_decode($this->req['info'],true);
        $captcha = $this->req['captcha'];
        $type = $this->req['type'];
        $plan = $this->req['plan'] ?? '';
        $teaAsk = $this->req['teaAsk'] ?? '';
        Checkunit::verification($base['phone'],2,'电话号码',11);
        Checkunit::verification($base['password'],-1,'密码',strlen($base['password']));
        Checkunit::verification($info['name'],-1,'姓名',strlen($info['name']));
        Checkunit::verification($info['email'],1,'邮箱',strlen($info['email']));
        Checkunit::verification($info['school'],-1,'学校',strlen($info['school']));
        Checkunit::verification($info['grade'],-1,'年级',strlen($info['grade']));
        Checkunit::verification($info['class'],-1,'辅导科目',strlen($info['class']));
        Checkunit::verification($info['stuInfo'],-1,'学员情况',strlen($info['stuInfo']));
        Checkunit::verification($info['parentName'],-1,'家长姓名',strlen($info['parentName']));
        Checkunit::verification($info['parentAppellation'],-1,'家长称呼',strlen($info['parentAppellation']));
        Checkunit::verification($info['address'],-1,'所在区域',strlen($info['address']));
        Checkunit::verification($info['classAddress'],-1,'授课地址',strlen($info['classAddress']));
        Checkunit::verification($info['time'],4,'授课时长');
        Checkunit::verification($info['fee'],4,'课时费用');
        Checkunit::verification($captcha,-1,'验证码',4);
        if(!array_key_exists($type,ConstVariable::TYPE)){
            return $this->resp(1,'错误的场景类型');
        }
        $captchaKey = md5(ConstVariable::TYPE[$type].$_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);
        $captchaCache = Cache::get($captchaKey);
        if(empty($captchaCache)){
            return $this->resp(1,'您的图片验证码已过期，请重新获取');
        }
        if($captchaCache != $captcha){
            Cache::forget($captchaKey);
            return $this->resp(1,'您的图片验证码错误，请重新输入');
        }
        $base['password'] = Hash::make($base['password']);
        $res = UserService::addStu($base,$info,$plan,$teaAsk);
        if($res){
            Cache::forget($captchaKey);
            return $this->resp(200,'注册成功，请前往登录');
        }else{
            Cache::forget($captchaKey);
            return $this->resp(1,'系统异常，请稍后再试');
        }
    }

    /**
     * 教师注册
     */
    public function registerTea(){
        $base = json_decode($this->req['base'],true);
        $info = json_decode($this->req['info'],true);
        $captcha = $this->req['captcha'];
        $type = $this->req['type'];
        $plan = '';
        if(!empty($this->req['plan']))
            $plan = $this->req['plan'];

        Checkunit::verification($base['phone'],2,'电话号码',11);
        Checkunit::verification($base['password'],-1,'密码',strlen($base['password']));
        Checkunit::verification($info['name'],-1,'姓名',strlen($info['name']));
        Checkunit::verification($info['gender'],4,'性别');
        Checkunit::verification($info['imgUrl'],-1,'照片url',strlen($info['imgUrl']));
        Checkunit::verification($info['email'],1,'邮箱',strlen($info['email']));
        Checkunit::verification($info['birth'],-1,'出生年月',10);
        Checkunit::verification($info['place'],-1,'籍贯',strlen($info['place']));
        Checkunit::verification($info['qq'],-1,'QQ',strlen($info['qq']));
        Checkunit::verification($info['vx'],-1,'微信',strlen($info['vx']));
        Checkunit::verification($info['motto'],-1,'教员格言',strlen($info['motto']));
        Checkunit::verification($info['major'],-1,'所学专业',strlen($info['major']));
        Checkunit::verification($info['teaInfo'],-1,'个人简介');
        Checkunit::verification($info['fee'],4,'课时费用');
        Checkunit::verification($captcha,-1,'验证码',4);
        if(!array_key_exists($type,ConstVariable::TYPE)){
            return $this->resp(1,'错误的场景类型');
        }
        $captchaKey = md5(ConstVariable::TYPE[$type].$_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);
        $captchaCache = Cache::get($captchaKey);
        if(empty($captchaCache)){
            return $this->resp(1,'您的图片验证码已过期，请重新获取');
        }
        if($captchaCache != $captcha){
            Cache::forget($captchaKey);
            return $this->resp(1,'您的图片验证码错误，请重新输入');
        }
        $base['password'] = Hash::make($base['password']);
        $res = UserService::addTea($base,$info,$plan);
        if($res){
            Cache::forget($captchaKey);
            return $this->resp(200,'注册成功，请前往登录');
        }else{
            Cache::forget($captchaKey);
            return $this->resp(1,'系统异常，请稍后在试');
        }
    }

    /**
     * 用户登录
     * @return UserController
     */
    public function login(){
        $account = $this->req['phone'];
        $password = $this->req['password'];
        $captcha = $this->req['captcha'];
        $type = $this->req['type'];
        Checkunit::verification($account,2,'手机号',11);
        Checkunit::verification($password,-1,'密码',strlen($password));
        Checkunit::verification($captcha,-1,'验证码',strlen($captcha));
        if(!array_key_exists($type,ConstVariable::TYPE)){
            return $this->resp(1,'错误的场景类型');
        }
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
            return $this->resp(1,'您的图片验证码错误，请重新获取！');
        }

        $user = UserService::loginByPhone($account);
        if(empty($user)){
            Cache::forget($captchaKey);
            return $this->resp([
                'code' => 1,
                'msg' => '您的账号有误，请检查您的账号'
            ]);
        }
        if(!Hash::check($password,$user['password'])){
            Cache::forget($captchaKey);
            return $this->resp([
                'code' => 1,
                'msg' => '您的密码有误，请检查您的账号'
            ]);
        }
        $token = Jwtunit::getToken($account,1800);
        Cache::put(md5($token),$account,30);
        if(array_key_exists('identity',$user)){
            //学生
            Cache::put(md5($token . 'identity'),'3',30);
        }else{
            Cache::put(md5($token . 'identity'),'2',30);
        }

        return $this->resp(200,'登录成功',['token'=>$token]);
    }

    /**
     * @return UserController
     * 用户退出登录
     */
    public function logout(){
        $token = $this->req['token'];
        Cache::forget($token);
        return $this->resp(200,'注销成功');
    }

    /**
     * 用户个人中心
     * @return UserController
     */
    public function userSelf(){
        $token = $this->req['token'];
        $tokenKey = md5($token . 'identity');
        if(Cache::has($tokenKey)){
            $identity = Cache::get($tokenKey);
        }else{
            return $this->resp(1,'无效的token，请重新登录');
        }
        $account = Cache::get(md5($token));
        if($identity == 3){
            $res = UserService::selectStuByPhone($account);
        }else{
            $res = UserService::selectTeaByPhone($account);
        }
        return $this->resp(200,'ok',$res);
    }

    /**
     * 学员列表页
     */
    public function getStuList(){
        $size = $this->req['size'] ?? 10;
        $offset = $this->req['offset'] ?? 0;
        $res = UserService::selectAllStu($size,$offset);
        return $this->resp($res);
    }

    /**
     * 教员列表页
     * @return UserController
     */
    public function getTeaList(){
        $size = $this->req['size'] ?? 10;
        $offset = $this->req['offset'] ?? 0;
        $res = UserService::selectAllTea($size,$offset);
        return $this->resp($res);
    }
}