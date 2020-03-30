<?php


namespace App\Http\Controllers\Api\Base;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;


class BaseController extends Controller
{
    public function getCaptcha()
    {
        $phraseBuilder = new PhraseBuilder(4);
        $captcha = new CaptchaBuilder(null, $phraseBuilder);
        $captchaData = $captcha->getPhrase();
//        dd($captcha->build()->output());
        $data = $captcha->build()->inline();
        $captchaKey = md5($_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);
        $cache = Cache::put($captchaKey,$captchaData,30);
        return $this->resp([
            'captcha' => $data,
            'num' =>$captchaData
        ]);

    }
}