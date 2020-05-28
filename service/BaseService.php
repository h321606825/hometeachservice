<?php


namespace Service;


class BaseService
{
    public function __construct()
    {

    }

    /**
     * @param $std
     * @return mixed
     * std对象转换为数组
     */
    public static function stdToArray($std){
        return json_decode(json_encode($std,true),true);
    }

    public static function age($birthday) {
        if (strtotime($birthday) > 0){
            return (int)((time() - strtotime($birthday))/(86400 * 365));
        }else{
            return '-';
        }
    }
}