<?php


namespace App\Http\Controllers\Api\Admin;


use App\Http\Controllers\Controller;
use Service\Admin\UserInfoService;
use Service\Base\ServeService;
use Service\User\UserService;
use Unit\Checkunit;

class UserController extends Controller
{
    public $loginCheck = false;

    public function updateTea(){
        $base = json_decode($this->req['base'],true);
        $info = json_decode($this->req['info'],true);
        $captcha = $this->req['captcha'];
        $type = $this->req['type'];
        $plan = '';
        if(!empty($this->req['plan']))
            $plan = $this->req['plan'];
        Checkunit::verification($base['id'],4,'教员id',strlen($base['id']));
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

        $res = UserService::updateTea($base,$info,$plan);
        if($res){
            return $this->resp(200,'编辑成功');
        }else{
            return $this->resp(1,'系统异常，请稍后再试');
        }
    }

    public function updateStu(){
        $base = json_decode($this->req['base'],true);
        $info = json_decode($this->req['info'],true);
        $captcha = $this->req['captcha'];
        $type = $this->req['type'];
        $plan = $this->req['plan'] ?? '';
        $teaAsk = $this->req['teaAsk'] ?? '';
        Checkunit::verification($base['id'],4,'学员id',strlen($base['id']));
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

        $res = UserService::updateStu($base,$info,$plan,$teaAsk);
        if($res){
            return $this->resp(200,'编辑成功');
        }else{
            $this->resp(1,'系统异常，请稍后再试');
        }
    }

    public function deleteTea(){
        $teaId = $this->req['teaId'];
        Checkunit::verification($teaId,4,'教员id',strlen($teaId));
        $userInfo = UserService::selectTeaByPhone($teaId);
        if(empty($userInfo)){
            $this->resp(1,'查无此人');
        }
        $res = UserService::deleteTea($teaId);
        if($res){
            return $this->resp(200,'删除教员成功');
        }else{
            $this->resp(1,'系统异常，请稍后再试');
        }
    }

    public function deleteStu(){
        $stuId = $this->req['stuId'];
        Checkunit::verification($stuId,4,'学员id',strlen($stuId));
        $userInfo = UserService::selectStuByPhone($stuId);
        if(empty($userInfo)){
            $this->resp(1,'查无此人');
        }
        $res = UserService::deleteStu($stuId);
        if($res){
            return $this->resp(200,'删除学员成功');
        }else{
            $this->resp(1,'系统异常，请稍后再试');
        }
    }


    public function updateCustomer(){
        $phone = $this->req['phone'] ?? '';
        $qq = $this->req['qq'] ?? '';

        Checkunit::verification($phone,2,'客服电话',11);
        Checkunit::verification($qq,4,'客服qq',strlen($qq));
        $customerInfo = ServeService::getCustomer();
        if($customerInfo[0]['qq'] == $qq && $customerInfo[0]['phone'] == $phone){
            return $this->resp(1,'未作任何修改');
        }
        $res = UserInfoService::updeteAdminCustomer($phone,$qq);
        if($res)
            return $this->resp(200,'修改成功');
        else
            return $this->resp(1,'系统异常，请稍后再试');
    }
}