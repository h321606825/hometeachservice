<?php


namespace Unit;

use \Firebase\JWT\JWT;
use Illuminate\Support\Facades\Log;

class Jwtunit
{
    /**
     * @param $key 关键字
     * @param $time 有效期
     * @return string
     */
    public static function getToken($key,$time){
        $payload = array(
            "iss" => "http://example.org",
            "aud" => "http://example.com",
            "iat" => 1356999524,
            "nbf" => 1357000000,
        );
        JWT::$leeway = $time; // $leeway in seconds
        $jwt = JWT::encode($payload, $key);
        return $jwt;
    }

    /**
     * @param $jwt token
     * @param $key 关键字
     * @return array token信息
     */
    public static function checkToken($jwt,$key){
        $payload = array(
            "iss" => "http://example.org",
            "aud" => "http://example.com",
            "iat" => 1356999524,
            "nbf" => 1357000000,
        );
        try {
            $decoded = JWT::decode($jwt, $key, array('HS256'));
            return (array)$decoded;
        }catch (\Exception $exception){
            Log::error('token解析异常',__CLASS__ . __FUNCTION__);
            exit(json_encode([
                'code' => 1,
                'msg' => '系统异常',
            ]));
        }
    }
}