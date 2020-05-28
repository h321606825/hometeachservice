<?php


namespace Service\Admin;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Service\BaseService;

class UserInfoService extends BaseService
{
    public static function getAdmininfoById($id){
        $where = ['account'=>$id];
        $select = [
            'account',
            'password',
        ];
        $db = DB::table('hs_admin');
        $data = $db->select($select)->where($where)->get()->toArray();
        return self::stdToArray($data);
    }

    public static function addAdmin($id,$password){

        $insert = [
            'account' => $id,
            'password'=>$password
        ];
        $db = DB::table('hs_admin');
        try {
            $db->insert($insert);
        }catch (\Exception $e){
            Log::error("添加管理员异常".__CLASS__.__FUNCTION__,[
                'info' => $e->getMessage(),
                'line' => $e->getLine(),
                ]);
            return false;
        }
        return true;
    }

    public static function updeteAdminCustomer($phone, $qq){
        $update = [
            'phone'=>$phone,
            'qq'=>$qq,
        ];

        $where = [
            'customer'=>1
        ];

        $db = DB::table('hs_admin');
        try {
            $db->update($update)->where($where);
            return true;
        }catch (\Exception $e){
            Log::error('修改客服密码异常'.__CLASS__.__FUNCTION__,[
                'info' => $e->getMessage(),
                'line' => $e->getLine(),
            ]);
            return false;
        }
    }
}