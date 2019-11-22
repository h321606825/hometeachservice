<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    //登录校验
    public $loginCheck = true;

    //请求参数
    public $req;

    public function __construct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $this->req    = Request::input();
    }

    /**
     * 输出成功结果
     *
     * @param null $data
     * @return Controller
     */
    public function success($data = null)
    {
        return $this->resp([
            'code' => 200,
            'msg'  => '操作成功',
            'data' => $data,
        ]);
    }
    /**
     * 消息体输出
     * @param   $rawcode       int         消息码
     * @param   $msg        string      消息说明
     * @param   $data       mixed       数据
     * @return  $this
     */
    public function resp($rawcode = 200, $msg = 'ok', $data = [], $debug = [])
    {
        if (is_array($rawcode)) {
            if (!isset($rawcode['code']) || !isset($rawcode['msg'])) {
                // 非消息结构的数组，直接输出JSON
                $msg  = 'ok';
                $data = $rawcode;
                $code = 200;
            } else {
                $msg  = isset($rawcode['msg']) ? $rawcode['msg'] : '';
                $data = isset($rawcode['data']) ? $rawcode['data'] : [];
                $code = $rawcode['code'];
            }
        } else {
            $code = (int) $rawcode;
        }

        $resp         = [];
        $resp['msg']  = $msg;
        $resp['code'] = $code ?? 200;
        $resp['data'] = $data;

        return response()->json($resp)->withCallback(Request::input('callback'));
    }
}
