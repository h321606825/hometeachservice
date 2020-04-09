<?php


namespace App\Http\Controllers\Api\User;


use App\Http\Controllers\Controller;
use Service\User\UserService;
use Unit\Checkunit;

class InfoController extends Controller
{
    /**
     * 学员详情页
     * @return InfoController
     */
    public function stuInfo(){
        $stuid = $this->req['id'];

        Checkunit::verification($stuid,4,'学生id',strlen($stuid));
        $data = UserService::selectStuByPhone($stuid);

        return $this->resp($data);
    }

    /**
     * 教员详情页
     * @return InfoController
     */
    public function teaInfo(){
        $stuid = $this->req['id'];

        Checkunit::verification($stuid,4,'教师id',strlen($stuid));
        $data = UserService::selectTeaByPhone($stuid);

        return $this->resp($data);
    }
}