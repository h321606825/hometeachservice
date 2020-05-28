<?php


namespace App\Http\Controllers\Api\Base;


use App\Http\Controllers\Controller;
use Service\Base\InfoService;
use Service\Base\ServeService;
use Service\User\UserService;
use Service\User\OrderService;
use Illuminate\Support\Facades\Cache;

class InfoController extends Controller
{
    public $loginCheck = false;
    /**
     * @return InfoController
     * 获取课程列表
     */
    public function getClass(){

        $res = ServeService::getClass();
        return $this->resp($res);
    }

    /**
     * 获取首页图片列表
     */
    public function getPicture(){
        $token = $this->req['token'] ?? '';
        $tdata = Cache::get(md5($token));
        $isAdmin = false;
        if(!empty($tdata)){
            if(strpos($tdata,'isAdmin'))
                $isAdmin = true;
        }
        if($isAdmin)
            $res = ServeService::getPicture(true);
        else
            $res = ServeService::getPicture();
        return $this->resp($res);
    }

    /*
     * 添加首页图片
     */

    public function addPicture(){
        $location = $this->req['imgLocation'] ?? '';
        $url = $this->req['imgUrl'] ??'';
        $name = $this->req['imgName'] ?? '';
        $size = $this->req['size'] ?? '';

        $res = ServeService::addPicture($location,$name,$size,$url);
        if($res){
            return $this->resp(200,'添加成功');
        }else{
            return $this->resp(1,'系统异常，请稍后再试');
        }
    }

    public function deletePicture(){
        $pictureId = $this->req['imgId'] ?? "";

        $res = ServeService::deletePicture($pictureId);
        if($res){
            return $this->resp(200,'删除成功');
        }else{
            return $this->resp(1,'系统异常，请稍后再试');
        }
    }

    public function getStuList(){
        $page = $this->req['page'] ?? 1;
        $size = $this->req['size'] ?? 10;

        $res = UserService::selectAllStu([],$size,$page);
        UserService::serializeStu($res);
        $res['page'] = [
            'offset'=>$page,
            'size'=>$size,
            'total'=>$res['total'],
        ];
        unset($res['total']);
        return $this->resp($res);
    }

    public function getTeaList(){
        $page = $this->req['page'] ?? 1;
        $size = $this->req['size'] ?? 10;

        $res = UserService::selectAllTea([],$size,$page);
        UserService::serializeTea($res);
        $res['page'] = [
            'offset'=>$page,
            'size'=>$size,
            'total'=>$res['total'],
        ];
        unset($res['total']);
        return $this->resp($res);
    }

    /**
     * [获取公告信息]
     * @return [type] [description]
     */
    public function getAffiche(){
        $page = $this->req['page'] ?? 1;
        $size = $this->req['size'] ?? 10;
        $token = $this->req['token'];
        $tdata = Cache::get(md5($token));
        $isAdmin = false;
        if(!empty($tdata)){
            if(strpos($tdata,'isAdmin'))
                $isAdmin = true;
        }
    
        $res = InfoService::getAffiche($isAdmin,$page,$size);

        return $this->resp($res);
    }

}