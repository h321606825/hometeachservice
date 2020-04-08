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
}