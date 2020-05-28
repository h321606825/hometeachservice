<?php


namespace Service\Base;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use \Service\BaseService;

class ServeService extends BaseService
{
    /**
     * 图片及url映射
     * @param $filename
     * @param $url
     */
    public static function fileUploade($filename,$url){
        try {
            $db = DB::table('');
        }catch (\Exception $exception){
            Log::error("class:__CLASS__,function::__FUNCTION__错误",[
                'line'=>__LINE__,
                'info'=>$exception->getMessage(),
                'code'=>$exception->getCode()
            ]);
        }
    }

    /**
     * 客服信息
     * @return mixed
     */
    public static function getCustomer(){
        $where = [
            'customer' => 1,
            'is_delete'=>0,
        ];
        $select = [
            'qq',
            'phone'
        ];

        $db= DB::table('hs_admin');

        $res = $db->select($select)
            ->where($where)
            ->get()
            ->toArray();
        return self::stdToArray($res);
    }

    public static function getClass(){
        $where = [
            'is_delete'=>0
        ];
        $db = DB::table('base_class');
        $select = [
            'id',
            'class as className',
            'create_time as createTime',
            'update_time as updateTime'
        ];

        $res = $db->select($select)->where($where)->get()->toArray();
        return self::stdToArray($res);
    }

    public static function getPicture($all = false){
        $select = [
            'id',
            'name',
            'location',
            'size',
            'url',
            DB::raw('case when is_delete = 1 then "禁用" else "启用" end as state'),
            'is_delete as isDelete',
            'create_time as createTime',
            'update_time as updateTime',
        ];
        $db = DB::table('base_picture');
        $res = [];
        if($all){
            $res = $db->select($select)->get()->toArray();
        }else{
            $where = [
                'is_delete'=>0
            ];
            $res = $db->select($select)->where($where)->get()->toArray();
        }

        return static::stdToArray($res);
    }

    /**
     * @param $location
     * @param $name
     * @param $size
     * @param $url
     * 添加首页图片
     */
    public static function addPicture($location,$name,$size,$url){
        $insert = [
            'name'=>$name,
            'location'=>$location,
            'size'=>$size,
            'url'=>$url,
        ];

        $db = DB::table('base_picture');
        try {
            $db->insert($insert);
            return true;
        }catch (\Exception $exception){
            Log::error('添加首页图片异常'.__CLASS__.__FUNCTION__,[
                'info'=>$exception->getMessage(),
                'line'=>$exception->getLine(),
                'code'=>$exception->getCode(),
            ]);
            return false;
        }
    }

    public static function deletePicture($imgId){
        $update = [
            'is_delete'=>1
        ];
        $where = [
            'id'=>$imgId
        ];

        $db = DB::table('base_picture');
        try {
            $db->where($where)->update($update);
            return true;
        }catch (\Exception $exception){
            Log::error('删除首页图片异常'.__CLASS__.__FUNCTION__,[
                'info'=>$exception->getMessage(),
                'line'=>$exception->getLine(),
                'code'=>$exception->getCode(),
            ]);
            return false;
        }
    }
}