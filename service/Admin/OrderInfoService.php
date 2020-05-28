<?php


namespace Service\Admin;


use Service\BaseService;

class OrderInfoService extends BaseService
{
    public static function getOrderInfo($order = 0,$all = false,$size = 10,$offset = 1){
        $db = DB::table('hs_order as order')
            ->leftJoin('hs_tea as tea','order.tea_id','=','tea.id')
            ->leftJoin('hs_stu as stu','order.stu_id','=','tea.id');
        $select = [
            'order.id as orderId',
            'tea.id as teaId',
            'tes.name as teaName',
            'tea.phone as teaPhone',
            'stu.id as stuId',
            'stu.name as stuName',
            'stu.phone as stuPhone',
            DB::raw('case when order.is_delete = 1 then "订单已取消" when order.state = 10 or order.state = 20 then "预下单" when order.state = 40 then "待评价"
             when order.state = 50 then "已完成" as state'),
//            DB::raw('case when order.is_delete = 1 then "订单已" else ')
            'order.is_delete = isDelete',
            'order.create_time as createTime',
            'order.update_time as updateTime'
        ];
        if($all){
            $res = $db->select($select)->limit($size)->offset(($offset-1)*10)->get()->toArray();
        }else{
            $where = [
                'id'=>$order
            ];
            $res = $db->select($select)->where($where)->limit($size)->offset(($offset-1)*10)->get()->toArray();
        }

        return self::stdToArray($res);
    }

    public static function getOrderByUser($userId,$identity,$size =10,$offset = 1){
        if($identity ==2){
            $where = [
                'tea_id' => $userId
            ];
        }else{
            $where = [
                'stu_id'=>$userId
            ];
        }
        $db = DB::table('hs_order')
            ->leftJoin('hs_tea as tea','order.tea_id','=','tea.id')
            ->leftJoin('hs_stu as stu','order.stu_id','=','tea.id');
        $select = [
            'order.id as orderId',
            'tea.id as teaId',
            'tes.name as teaName',
            'tea.phone as teaPhone',
            'stu.id as stuId',
            'stu.name as stuName',
            'stu.phone as stuPhone',
            DB::raw('case when order.is_delete = 1 then "订单已取消" when order.state = 10 or order.state = 20 then "预下单" when order.state = 40 then "待评价"
             when order.state = 50 then "已完成"'),
//            DB::raw('case when order.is_delete = 1 then "订单已" else ')
            'order.is_delete = isDelete',
            'order.create_time as createTime',
            'order.update_time as updateTime'
        ];
        $res = $db->select($select)->where($where)->limit($size)->offset(($offset-1)*10)->get()->toArray();

        return self::stdToArray($res);
    }
}