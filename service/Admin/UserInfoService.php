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
        return json_decode(json_encode($data,true),true);
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
            Log::error("添加管理员异常".__CLASS__.__FUNCTION__,['line'=>__LINE__]);
            return false;
        }
        return true;
    }
}