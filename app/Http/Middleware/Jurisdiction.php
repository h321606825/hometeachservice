<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use Unit\Jwtunit;


class Jurisdiction
{
    public function handle($request, Closure $next, $guard = null)
    {
        $req = Request::input();
        $token = $req['token'] ?? '';
        if(!Cache::has($token)) {
           exit(json_encode([
               'code' => 100,
               'msg' => '您的token已过期，请重新登录'
           ]));
        }
        $tokenData = Cache::get($token);
        $tokenKey = md5($token . 'identity');
        if(!Cache::has($tokenKey)){
            if(strpos($tokenData,'isAdmin'))
                return $next($request);
        }
       exit(json_encode([
           'code' => 144,
           'msg' => '您无权访问该页面，请联系管理员'
       ]));
        return $next($request);
    }
}