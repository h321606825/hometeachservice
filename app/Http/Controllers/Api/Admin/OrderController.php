<?php


namespace App\Http\Controllers\Api\Admin;


use Service\User\OrderService;
use Service\Admin\OrderInfoService;
use App\Http\Controllers\Controller;
use Service\User\UserService;

class OrderController extends Controller
{
    public function orderInfo(){
        $size = $this->req['size'] ?? 10;
        $page = $this->req['page'] ?? 1;
        if(!empty($this->req['id'])){
            $orderId = $this->req['id'];
            $data = OrderInfoService::getOrderInfo($orderId,true);
        }else{
            $data = OrderInfoService::getOrderInfo(0,false,$size,$page);
        }

        return $this->resp($data);
    }

    public function getOrder(){
        $page = $this->req['page'] ?? 1;
        $size = $this->req['size'] ?? 10;

        $res = OrderService::getOrder($page,$size);
        $res['page'] = [
            'offset'=>$page,
            'size'=>$size,
            'total'=>$res['total'],
        ];
        unset($res['total']);

        return $this->resp($res);
    }

    public function delOrder(){
        $orderId = $this->req['orderId'];
        $orderInfo = OrderService::getOrderById($orderId);
        if(in_array($orderInfo[0]['orderState'], [30,40,50])){
            return $this->resp(1,'订单不能被删除!!!');
        }
        if($orderInfo[0]['is_delete']==1){
            return $this->resp(1,'订单已经被删除!!!');
        }

        $res = OrderService::deleteOrder($orderId);
        if($res){
            return $this->resp(200,'删除成功');
        }else{
            return $this->resp(1,'系统异常，请稍后在试');
        }
    }

    public function updateOrder(){
        $orderId = $this->req['orderId'];
        $orderInfo = OrderService::getOrderById($orderId);
        if(in_array($orderInfo[0]['orderState'], [30,40,50,59])){
            return $this->resp(1,'订单不能被通过!!!');
        }
        if($orderInfo[0]['is_delete']==1){
            return $this->resp(1,'订单已经被删除!!!');
        }

        $result = UserService::changeUserState($orderInfo[0]['teaId'],$orderInfo[0]['stuId'],20);
        if(!$result) {
            return $this->resp(1,'系统异常，请稍后在试');
        }
        $update = [
            'state' => 30,
        ];
        $res = OrderService::editUserOrder($orderId,$update);
        if($res){
            return $this->resp(200,'审核成功');
        }else{
            return $this->resp(1,'系统异常，请稍后在试');
        }
    }
}