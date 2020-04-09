<?php


namespace App\Http\Controllers\Api\Base;


use App\Http\Controllers\Api\ConstVariable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Service\Base\ServeService;


class BaseController extends Controller
{
    /**
     * 获取图片验证码
     * @return BaseController
     */
    public function getCaptcha()
    {
        $type = $this->req['type'];
        if(!array_key_exists($type,ConstVariable::TYPE)){
            return $this->resp(1,'错误的场景类型');
        }
        $phraseBuilder = new PhraseBuilder(4);
        $captcha = new CaptchaBuilder(null, $phraseBuilder);
        $captchaData = $captcha->getPhrase();
//        dd($captcha->build()->output());
        $data = $captcha->build()->inline();
        $captchaKey = md5(ConstVariable::TYPE[$type].$_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);
        $cache = Cache::put($captchaKey,$captchaData,30);
        return $this->resp([
            'captcha' => $data,
            'num' =>$captchaData
        ]);
    }

    /**
     * 上传文件
     */
    public function uploadFile(Request $request){
//        $file = $request->file('upload');
        //判断文件是否上传成功
        if ($_FILES["upload"]["error"] == 0){
            //原文件名
//            $originalName = $file->getClientOriginalName();
            $originalName = $_FILES['upload']['name'];
            Log::info($originalName);
            //扩展名
//            $ext = $file->getClientOriginalExtension();
            //MimeType
//            $type = $file->getClientMimeType();
            $type = $_FILES['upload']['type'];
            Log::info($type);
            //临时绝对路径
//            $realPath = $file->getRealPath();
            $realPath = $_FILES['upload']['tmp_name'];
//            $filename = uniqid().$originalName.$ext;
            $filename = date("Y-m-d",time()) . $originalName;
            $bool = Storage::disk('photo')->put($filename,file_get_contents($realPath));
            $url = Storage::url('photo/'.$filename);
            //创建文件url及图片映射
//            ServeService::fileUploade($filename,$url);
            //判断是否上传成功
            if($bool){
                return $this->resp(200,'文件上传成功',[
                    'url'=>$url,
                    'filename'=>$filename,
                ]);
            }else{
                Log::error('文件上传失败'.date('Y-m-d',time()),[
                    'time'=>date('Y-m-d',time()),
                    'line'=>__LINE__,
                    'path'=>$realPath,
                    ]);
                return $this->resp(1,'文件上传失败');
            }
        }else{
            Log::info('文件上传失败', $_FILES);
            return $this->resp(1,'文件上传失败');
        }
    }
}