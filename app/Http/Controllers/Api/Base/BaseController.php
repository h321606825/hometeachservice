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
use Service\Base\InfoService;
use Service\Base\ServeService;
use Unit\Checkunit;


class BaseController extends Controller
{
    public $loginCheck = false;
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
        $captchaData = strtolower($captcha->getPhrase());
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
        Log::info('文件上传',$_FILES);
        //判断文件是否上传成功
        if ($_FILES["file"]["error"] == 0){
            //原文件名
//            $originalName = $file->getClientOriginalName();
            $originalName = $_FILES['file']['name'];
            Log::info($originalName);
            //扩展名
//            $ext = $file->getClientOriginalExtension();
            //MimeType
//            $type = $file->getClientMimeType();
            $type = $_FILES['file']['type'];
            Log::info($type);
            //临时绝对路径
//            $realPath = $file->getRealPath();
            $realPath = $_FILES['file']['tmp_name'];
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

    /*
     * 获取信息资讯
     */
    public function getList(){
        $page = $this->req['page'] ?? 1;
        $size = $this->req['size'] ?? 10;
        $essayType = $this->req['essayType'] ?? '';
        $token = $this->req['token'] ?? '';
        $tdata = Cache::get(md5($token));
        $isAdmin = false;
        if(!empty($tdata)){
            if(strpos($tdata,'isAdmin'))
                $isAdmin = true;
        }
       
        if($isAdmin){
            $res = InfoService::getInfoList(true,$page,$size,$essayType);
        }else{
            $res = InfoService::getInfoList(false,$page,$size,$essayType);
        }
        $res['page'] = [
            'offset'=>$page,
            'size'=>$size,
            'total'=>$res['total'],
        ];
        unset($res['total']);
        return $this->resp($res);
    }

    /**
     * 添加信息咨讯
     */
    public function addList(){
        $title = $this->req['title'] ?? '';
        $content = $this->req['content'] ?? '';
        $type = $this->req['type'] ?? '';
        $customer = $this->req['customerId'] ?? '';

        Checkunit::verification($type,4,'类型');
        Checkunit::verification($title,-1,'标题',strlen($title));
        Checkunit::verification($content,-1,'内容',strlen($content));

        $res = InfoService::addInfoList($customer,$title,$content,$type);
        if($res){
            return $this->resp();
        }else{
            return $this->resp(1,'系统异常，请稍后再试');
        }
    }

//    public function deleteList(){
//        Checkunit::verification($customer,4,'资讯id');
//        $customerInfo = InfoService::getInfoList(false,1,10,$customer);
//        if(empty($customerInfo)){
//            return $this->resp(1,'没有这条资讯');
//        }
//        $res = InfoService::deleteInfoList($customer);
//        if($res){
//            return $this->resp(200,'删除资讯成功');
//        }else{
//            return $this->resp(1,'系统异常，请稍后再试');
//        }
//    }

    /**
     * @return BaseController
     * 获取资讯分类
     */
    public function getCustomerType(){
        $res = InfoService::getCustomerType();

        return $this->resp($res);
    }

    /**
     * [添加通知公告]
     */
    public function addAffiche(){
        $title = $this->req['afficheTitle'];
        $content = $this->req['afficheContent'];
        $time = $this->req['afficheTime'];
        $time = $time * 3600 * 24;

        $res = InfoService::Affiche(0,$title,$content,$time);
        if($res){
            return $this->resp(200,'添加通知成功');
        }else{
            return $this->resp(1,'系统异常，请稍后再试');
        }
    }

    /**
     * [修改通知公告]
     */
    public function updateAffiche(){
        $title = $this->req['afficheTitle'];
        $content = $this->req['afficheContent'];
        $time = $this->req['afficheTime'];
        $id = $this->req['afficheId'];
        $time = $time * 3600 * 24;
        $state = $this->req['state'] ?? '';

        $res = InfoService::Affiche($id,$title,$content,$time,$state);
        if($res){
            return $this->resp(200,'修改通知成功');
        }else{
            return $this->resp(1,'系统异常，请稍后再试');
        }
    }

    /**
     * [删除通知公告]
     * @return [type] [description]
     */
    public function deleteAffiche(){
        $id = $this->req['afficheId'] ?? '';
        if(empty($id)){
            return $this->resp(1,'参数不合法');
        }

        $res = InfoService::delAffiche($id);
        if($res){
            return $this->resp(200,'删除通知成功');
        }else{
            return $this->resp(1,'系统异常，请稍后再试');
        }
    }
}