<?php


namespace Service\Base;


use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Service\BaseService;

class InfoService extends BaseService
{
    public static function getInfoList($all = false,$offset,$size,$id = 0){
        $select = [
            'e.id',
            'e.title',
            'e.content',
            'e.type',
            'et.essayType',
            DB::raw('CASE when e.is_delete = 1 then "不显示" else "显示" end as isDelete'),
            'e.create_time as createTime',
        ];
        $db = DB::table('base_essay as e')
            ->leftJoin('base_essay_type as et','e.type','=','et.id');
        $res = null;
        $total = null;
        if(!$all){
            $where = [
                'e.is_delete'=>0,
                'e.type' => $id
            ];
            $total = $db->where($where)->count();
            $res = $db->select($select)->where($where)->limit($size)->offset(($offset-1)*10)->get()->toArray();
        }else{
            $where = [
                // 'e.id' => $id
            ];
            $total = $db->where($where)->count();
            $res = $db->select($select)->where($where)->limit($size)->offset(($offset-1)*10)->get()->toArray();
        }
        $res['total'] = $total;
        return self::stdToArray($res);
    }

    public static function addInfoList($customerId = null,$title,$content,$type){
        $insert = [
            'title'=>$title,
            'content'=>$content,
            'type'=>$type,
        ];

        $db = DB::table('base_essay');
        try {
            if(!empty($customerId)){
                $where = [
                    'id'=>$customerId
                ];
                $db->where($where)->update($insert);
            }else{
                $db->insert($insert);
            }
            return true;
        }catch (\Exception $exception){
            Log::error('添加咨询信息错误'.__CLASS__.__FUNCTION__,[
                'info'=>$exception->getMessage(),
                'code'=>$exception->getCode(),
                'line'=>$exception->getLine(),
            ]);
            return false;
        }
    }

    public static function deleteInfoList($customerId){
        $update = [
            'is_delete'=>1
        ];
        $db = DB::table('base_essay');
        try {
            $db->update($update);
            return true;
        }catch (\Exception $exception){
            Log::error('删除咨询信息错误'.__CLASS__.__FUNCTION__,[
                'info'=>$exception->getMessage(),
                'code'=>$exception->getCode(),
                'line'=>$exception->getLine(),
            ]);
            return false;
        }
    }

    public static function getCustomerType(){
        $select = [
            'id',
            'essayType as type',
        ];
        $db = DB::table('base_essay_type');

        $res = $db->select($select)->get()->toArray();

        return static::stdToArray($res);
    }

    public static function getAffiche($all,$offset,$size){
        $select = [
            'id',
            'title',
            'content',
            'time',
            'is_delete as isDelete',
            DB::raw('case when is_delete = 1 then "禁用" else "启用" end as state'),
            'create_time as createTime',
            'update_time as updateTime'
        ];
        $db = DB::table('base_affiche');
        $where = [
            ['is_delete','=',0],
            DB::raw('UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(create_time) <= time'),
        ];
        $whereRaw = 'is_delete = 0 and UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(create_time) <= time';
        $res = null;
        if($all){
            $res = $db->select($select)->offset(($offset-1)*10)->limit($size)->get()->toArray();
        } else {
            $res = $db->select($select)->whereRaw($whereRaw)->offset(($offset-1)*10)->limit($size)->get()->toArray();
        }

        return static::stdToArray($res);
    }

    public static function Affiche($isAdd = 0,$title,$content,$time,$state = null){
        $insert = [
            'title'=>$title,
            'content'=>$content,
            'time'=>$time,
        ];
        $db = DB::table('base_affiche');
        $where = [
            'id'=>$isAdd,
        ];
        if(!$isAdd){
            try{
                $db->insert($insert);
                return true;
            }catch(\Exception $e){
                Log::info('添加通知公告失败'.__FUNCTION__,[
                    'info'=>$e->getMessage(),
                    'code'=>$e->getCode(),
                    'line'=>$e->getLine(),
                    ]);
                return false;
            }
        }else{
            $insert['is_delete'] = $state;
            try{
                $db->where($where)->update($insert);
                return true;
            }catch(\Exception $e){
                Log::info('更新通知公告失败'.__FUNCTION__,[
                    'info'=>$e->getMessage(),
                    'code'=>$e->getCode(),
                    'line'=>$e->getLine(),
                    ]);
                return false;
            }
        }

    }

    public static function delAffiche($id){
        $db = DB::table('base_affiche');
        $where = [
            'id'=>$id,
        ];
        $update = [
            'is_delete' => 1,
        ];

        try{
            $db->where($where)->update($update);
            return true;
        }catch(\Exception $e){
            Log::info('删除通知公告失败'.__FUNCTION__,[
                    'info'=>$e->getMessage(),
                    'code'=>$e->getCode(),
                    'line'=>$e->getLine(),
                    ]);
                return false;
        }
    }


    public static function getOrder($offset,$size){
        $select = [
            
        ];
    }
}