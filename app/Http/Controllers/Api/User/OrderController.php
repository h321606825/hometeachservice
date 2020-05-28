<?php


namespace App\Http\Controllers\Api\User;


use App\Http\Controllers\Api\ConstVariable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Service\User\OrderService;
use Service\User\UserService;
use Unit\Checkunit;

class OrderController extends Controller
{
    public $loginCheck = true;

    public function orderInfo(){
        $size = $this->req['size'] ?? 10;
        $page = $this->req['page'] ?? 1;
        $userId = $this->tokendata;
        if(empty($userId)){
            return $this->resp(1,'系统异常，请稍后在试');
        }
        $tokenKey = md5($this->token . 'identity');
        $identity = Cache::get($tokenKey);
        $res = OrderService::getUserOrderInfo($userId,$identity,$size,$page);
        $res['page'] = [
            'offset'=>$page,
            'size'=>$size,
            'total'=>$res['page'],
        ];
        unset($res['total']);

        return $this->resp($res);
    }

    public function stuOrder(){
        $teaId = $this->req['teaId'] ?? '';

        Checkunit::verification($teaId,-1,'教员id');

        $stuId = $this->tokendata;
        if(empty($stuId)){
            return $this->resp(1,'系统异常，请稍后在试');
        }

        $res = OrderService::addOrder($teaId,$stuId,ConstVariable::STUADDSTATE);

        // $tokenKey = md5($this->token . 'identity');
        // $identity = Cache::get($tokenKey);
        // $res = OrderService::getUserOrderInfo($stuId,$identity);

        if($res){
            return $this->resp(200,'下单成功');
        }else{
            return $this->resp(1,'系统异常，请稍后在试');
        }
    }


    public function teaOrder(){
        $stuId = $this->req['stuId'] ?? '';
        Checkunit::verification($stuId,-1,'学员id');

        $teaId = $this->tokendata;
        if(empty($teaId)){
            return $this->resp(1,'系统异常，请稍后在试');
        }
        $res = OrderService::addOrder($teaId,$stuId,ConstVariable::TEAADDSTATE);

        // $tokenKey = md5($this->token . 'identity');
        // $identity = Cache::get($tokenKey);
        // $res = OrderService::getUserOrderInfo($stuId,$identity);

        if($res){
            return $this->resp(200,'下单成功');
        }else{
            return $this->resp(1,'系统异常，请稍后在试');
        }
    }

    /**
     * [取消订单 description]
     * @return [type] [description]
     */
    public function delOrder(){
        $userId = $this->tokendata;
        $orderId = $this->req['orderId'];

        $tokenKey = md5($this->token . 'identity');
        $identity = Cache::get($tokenKey);
        $orderInfo = OrderService::getOrderById($orderId);
        if(empty($orderInfo)){
            return $this->resp(1,'订单不存在');
        }
        if($orderInfo[0]['is_delete'] == 1){
            return $this->resp(1,'订单被删除，您不能继续操作，请联系管理员');
        }
        if($orderInfo[0]['orderState'] == 59){
            return $this->resp(1,'订单已经被取消，如需继续，请重新选TA');
        }
        if(in_array($orderInfo[0]['orderState'], [30,40,50,59])){
            return $this->resp(1,'订单当前进度不能被取消，如需取消，请联系管理员');
        }else{
            Log::info('orderState',[$orderInfo[0]['orderState'],$orderId,$orderInfo]);
        }
        $update = [
            'state' => 59,
        ];
        $res = OrderService::editUserOrder($orderId,$update);
        if($res){
            return $this->resp(200,'取消成功');
        }else{
            return $this->resp(1,'系统异常，请稍后再试');
        }
    }

    public function evaluateOrder(){
        $userId = $this->tokendata;
        $orderId = $this->req['orderId'];
        $star = $this->req['star'];
        $evaluate = $this->req['evaluate'];

        $tokenKey = md5($this->token . 'identity');
        $identity = Cache::get($tokenKey);
        $orderInfo = OrderService::getOrderById($orderId);

        if(empty($orderInfo)){
            return $this->resp(1,'订单不存在');
        }
        if($orderInfo[0]['is_delete'] == 1){
            return $this->resp(1,'订单被删除，您不能继续操作，请联系管理员');
        }
        if(!in_array($orderInfo[0]['orderState'], [40])){
            return $this->resp(1,'订单目前进度不能被评价');
        }else{
            Log::info('orderState',[$orderInfo[0]['orderState'],$orderId,$orderInfo]);
        }

        if($star > 3){
            //好评
            $evaluatRate = OrderService::getEvaluatRate($orderInfo[0]['teaId']);
            $result = UserService::updateApplause($orderInfo[0]['teaId'],$evaluatRate);
            if(!$result) {
                return $this->resp(1,'系统异常，请稍后在试');
            }
        }
        $update = [
            'state' => 50,
            'star' =>$star,
            'evaluate' => $evaluate,
        ];
        if(strlen($evaluate) > 25){
            return $this->resp(1,'评价不能超过25个字');
        }

        $res = OrderService::editUserOrder($orderId,$update);
        if($res){
            return $this->resp(200,'取消成功');
        }else{
            return $this->resp(1,'系统异常，请稍后再试');
        }
    }

    public function finishOrder(){
        $userId = $this->tokendata;
        $orderId = $this->req['orderId'];

        $tokenKey = md5($this->token . 'identity');
        $identity = Cache::get($tokenKey);
        $orderInfo = OrderService::getOrderById($orderId);

        if(empty($orderInfo)){
            return $this->resp(1,'订单不存在');
        }
        if($orderInfo[0]['is_delete'] == 1){
            return $this->resp(1,'订单被删除，您不能继续操作，请联系管理员');
        }
        if(!in_array($orderInfo[0]['orderState'], [30])){
            return $this->resp(1,'订单进度不能被完成');
        }
        $result = UserService::changeUserState($orderInfo[0]['teaId'],$orderInfo[0]['stuId'],10);
        if(!$result) {
            return $this->resp(1,'系统异常，请稍后在试');
        }
        $update = [
            'state' => 40,
        ];
        $res = OrderService::editUserOrder($orderId,$update);
        if($res){
            return $this->resp(200,'操作成功');
        }else{
            return $this->resp(1,'系统异常，请稍后再试');
        }
    }
}