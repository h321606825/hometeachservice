<?php


namespace Service\User;


use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Service\BaseService;

class OrderService extends BaseService
{
    /**
     * 用户顶订单查询
     * @param $userId
     * @param $identity
     * @param int $size
     * @param int $offset
     * @return mixed
     *
     */
    public static function getUserOrderInfo($userId,$identity,$size = 10,$offset = 1){

        $select = [
            'order.id as orderId',
            'tea.id as teaId',
            'tea.name as teaName',
            'tea.phone as teaPhone',
            'stu.id as stuId',
            'stu.name as stuName',
            'stu.phone as stuPhone',
            'order.star',
            'order.evaluate',
            'order.state as orderState',
            DB::raw('case when order.state = 59 then "订单已取消" when order.state = 10 then "预下单" when order.state = 20 then "预下单" when order.state = 30 then "进行中" when order.state = 40 then "待评价" when order.state = 50 then "已完成" END as state'),
           DB::raw('case when order.is_delete = 1 then "订单已删除" else "正常" end as isDelete'),
            'order.is_delete',
            'order.create_time as createTime',
            'order.update_time as updateTime'
        ];

        $db = DB::table('hs_order as order')
            ->leftJoin('hs_tea as tea','order.tea_id','=','tea.id')
            ->leftJoin('hs_stu as stu','order.stu_id','=','stu.id');

        if($identity == 2){
            $where = [
                'tea_id' => $userId
            ];
        }else{
            $where = [
                'stu_id'=>$userId
            ];
        }
        $total = $db->where($where)->count();
        $res = $db->select($select)
            ->where($where)
            ->limit($size)
            ->offset(($offset-1)*10)
            ->orderBy('createTime','desc')
            ->get()
            ->toArray();
        $res['page'] = $total;
        return self::stdToArray($res);
    }

    public static function getOrderById($orderId){
        $where = [
            'id'=>$orderId,
        ];
        $select = [
            'id as orderId',
            'tea_id as teaId',
            'tea_name as teaName',
            'tea_phone as teaPhone',
            'stu_id as stuId',
            'stu_name as stuName',
            'stu_phone as stuPhone',
            'state as orderState',
            DB::raw('case when state = 59 then "订单已取消" when state = 10 then "预下单" when state = 20 then "预下单" when state = 40 then "待评价" when state = 50 then "已完成" END as state'),
            'star',
            'evaluate',
            'is_delete',
            DB::raw('case when is_delete =1 then "已删除" else "正常" end as isDelete'),
            'create_time as createTime',
            'update_time as updateTime'
        ];
        $db = DB::table('hs_order');

        $res = $db->select($select)
            ->where($where)
            ->get()
            ->toArray();

        return self::stdToArray($res);
    }

    /**
     * 用户下单接口
     * @param $teaId
     * @param $stuId
     * @param int $state
     * @return bool
     *
     */
    public static function addOrder($teaId,$stuId,$state = 10){
        $stuInfo = UserService::selectStuByPhone($stuid);
        $teaInfo = UserService::selectTeaByPhone($teaid);
        if(empty($stuInfo[0]['phone']) || empty($teaInfo[0]['phone'])){
            Log::error("下单错误",[
                "stuInfo"=>$stuInfo,
                'teaInfo'=>$teaInfo,
                'line'=>__LINE__,
            ]);
            return false;
        }
        $insert = [
            'stu_id' => $stuId,
            'stu_name' =>$stuInfo[0]['phone'],
            'stu_phone'=>$stuInfo[0]['name'],
            'tea_id' => $teaId,
            'tea_name' =>$teaInfo[0]['phone'],
            'tea_phone'=>$teaInfo[0]['name'],
            'state' =>$state
        ];

        $db = DB::table('hs_order');
        try {
            $db->insert($insert);
            return true;
        }catch (\Exception $e){
            Log::error("下单异常错误",[
                "info"=>$e->getMessage(),
                'code'=>$e->getCode(),
                'line'=>$e->getLine()
            ]);
            return false;
        }
    }

    public static function getOrder($offset,$size){
        $select = [
            'id as orderId',
            'tea_id as teaId',
            'tea_name as teaName',
            'tea_phone as teaPhone',
            'stu_id as stuId',
            'stu_name as stuName',
            'stu_phone as stuPhone',
            DB::raw('case when state = 59 then "订单已取消" when state = 10 then "预下单" when state = 20 then "预下单" when state = 40 then "待评价" when state = 50 then "已完成" END as state'),
            'star',
            'evaluate',
            'is_delete',
            DB::raw('case when is_delete =1 then "已删除" else "正常" end as isDelete'),
            'create_time as createTime',
            'update_time as updateTime'
        ];
        $db = DB::table('hs_order');
        $sum = $db->count();
        $res = $db->select($select)
            ->limit($size)
            ->offset(($offset-1)*10)
            ->orderBy('create_time','desc')
            ->get()
            ->toArray();
        $res['total'] = $sum;
        return self::stdToArray($res);
    }

    /**
     * [取消订单 description]
     * @param  [type] $orderId [description]
     * @return [type]          [description]
     */
    public static function editUserOrder($orderId,$update){

        $where = [
            'id'=>$orderId,
        ];
        $db = DB::table('hs_order');

        try{
            $db->where($where)->update($update);

            return true;
        }catch(\Exception $e){
            Log::error("订单异常" . __FUNCTION__,[
                'update'=>$update,
                "info"=>$e->getMessage(),
                'code'=>$e->getCode(),
                'line'=>$e->getLine(),
            ]);
            return false;
        }
    }

    /**
     * [删除订单 description]
     * @param  [type] $orderId [description]
     * @return [type]          [description]
     */
    public static function deleteOrder($orderId){
        $update = [
            'is_delete' => 1,
        ];
        $where = [
            'id'=>$orderId,
        ];
        $db = DB::table('hs_order');

        try{
            $db->where($where)->update($update);
            return true;
        }catch(\Exception $e){
            Log::error("订单删除异常",[
                "info"=>$e->getMessage(),
                'code'=>$e->getCode(),
                'line'=>$e->getLine(),
            ]);
            return false;
        }
    }

    public static function getEvaluatRate($teaid){
        $sql = "SELECT (a.good+1)/(b.al+1) as evaluateRate
                from (
                    SELECT count(*) as good
                from hs_order
                where tea_id = $teaid AND star >=3 AND state = 50
                ) as a,(SELECT count(*) as al
                from hs_order
                where tea_id = $teaid and state = 50) as b";
        $db = DB::table('hs_order');
        $res = $db->selectRaw($sql)->get()->toArray();

        return $res['evaluateRate'];
    }
}