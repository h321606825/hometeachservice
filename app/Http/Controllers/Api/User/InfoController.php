<?php


namespace App\Http\Controllers\Api\User;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Service\Admin\OrderInfoService;
use Service\Base\ServeService;
use Service\User\UserService;
use Unit\Checkunit;

class InfoController extends Controller
{
    public $loginCheck = false;
    /**
     * 学员详情页
     * @return InfoController
     */
    public function stuInfo(){
        $stuid = $this->req['id'];

        Checkunit::verification($stuid,4,'学生id',strlen($stuid));
        $data = UserService::selectStuByPhone($stuid);

        return $this->resp($data);
    }

    /**
     * 教员详情页
     * @return InfoController
     */
    public function teaInfo(){
        $teaid = $this->req['id'];

        Checkunit::verification($teaid,4,'教师id',strlen($teaid));
        $data = UserService::selectTeaByPhone($teaid);

        return $this->resp($data);
    }

    public function orderInfoByUser(){
        $token = $this->req['token'];
        $size = $this->req['size'] ?? 10;
        $page = $this->req['page'] ?? 1;
        if(!Cache::has(md5($token))){
            return $this->resp(['code'=>100,'msg' => '您的token已过期，请重新登录']);
        }
        $tokenKey = md5($token . 'identity');
        $userId = Cache::get(md5($token));
        $identity = Cache::get($tokenKey);

        $order = OrderInfoService::getOrderByUser($userId,$identity,$size,$page);
        return $this->resp($order);
    }

    /**
     *
     */
    public function customer(){
        $res = ServeService::getCustomer();
        return $this->resp($res);
    }

    /**
     * [获取推荐教师列表]
     * @return [type] [description]
     */
    public function recommendTea(){
        $size = $this->req['size'] ?? 10;
        $page = $this->req['page'] ?? 1;
        $res = UserService::recommendTea($page,$size);
        UserService::serializeTea($res);
        return $this->resp($res);
    }

    /**
     * [获取推荐学生列表]
     * @return [type] [description]
     */
    public function recommendStu(){
        $size = $this->req['size'] ?? 10;
        $page = $this->req['page'] ?? 1;
        $res = UserService::recommendStu($page,$size);
        UserService::serializeStu($res);
        return $this->resp($res);
    }
}