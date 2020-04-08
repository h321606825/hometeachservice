<?php


namespace Service\User;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Service\BaseService;

class UserService extends BaseService
{
    /**
     * 学生注册
     * @param $base
     * @param $info
     * @param string $plan
     * @param array $teaAsk
     * @return bool
     */
    public static function addStu($base, $info, $plan = '', $teaAsk = [])
    {
        $db = DB::table('hs_stu');
        $insert = [
            'name' => $info['name'],
            'phone' => $base['phone'],
            'password' => $base['password'],
            'mail' => $info['email'],
            'school' => $info['school'],
            'grade' => $info['grade'],
            'class_id' => $info['class'],
            'stu_info' => $info['stuInfo'],
            'parent_name' => $info['parentName'],
            'parent_appellation' => $info['parentAppellation'],
            'address' => $info['address'],
            'class_address' => $info['classAddress'],
            'time' => $info['time'],
            'fee' => $info['fee'],
        ];
        if (!empty($info['gender'])) {
            $insert['gender'] = $info['gender'];
        }
        if (!empty($info['parentVX'])) {
            $insert['parent_vx'] = $info['parentVX'];
        }
        if (!empty($plan)) {
            $insert['class_plan'] = $plan;
        }
        if (!empty($teaAsk)) {
            if (!empty($teaAsk['teaNum'])) {
                $insert['tea_num'] = $teaAsk['teaNum'];
            }
            if (!empty($teaAsk['teaGender'])) {
                $insert['tea_gender'] = $teaAsk['teaGender'];
            }
            if (!empty($teaAsk['teaWay'])) {
                $insert['tea_way'] = $teaAsk['teaWay'];
            }
            if (!empty($teaAsk['teaInfo'])) {
                $insert['tea_info'] = $teaAsk['teaInfo'];
            }
        }
        try {
            $db->insert($insert);
            return true;
        } catch (\Exception $e) {
            Log::error('学员注册异常', [
                'info' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile()
            ]);
            return false;
        }
    }

    /**
     * 教师注册
     * @param $base
     * @param $info
     * @param string $plan
     * @return bool
     */
    public static function addTea($base, $info, $plan = '')
    {
        $insert = [
            'name' => $info['name'],
            'phone' => $base['phone'],
            'password' => $base['password'],
            'img_url'=>$info['imgUrl'],
            'gender'=>$info['gender'],
            'mail' => $info['email'],
            'birth' => $info['birth'],
            "place" => $info['place'],
            "qq" => $info['qq'],
            "vx" => $info['vx'],
            "motto" => $info['motto'],
            "major" => $info['major'],
            "tea_info" => $info['teaInfo'],
            "tea_fee" => $info['fee']
        ];
        if (!empty($info['itic'])) {
            $insert['itic'] = $info['itic'];
        }
        if (!empty($info['flanguages'])) {
            $insert['flanguages'] = $info['flanguages'];
        }
        if (!empty($info['putonghua'])) {
            $insert['putonghua'] = $info['putonghua'];
        }
        if (!empty($info['experience'])) {
            $insert['experience'] = $info['experience'];
        }
        if (!empty($info['teaSpeciality'])) {
            $insert['tea_speciality'] = $info['teaSpeciality'];
        }
        if (!empty($info['class'])) {
            $insert['class_id'] = $info['class'];
        }
        if (!empty($info['teaAddress'])) {
            $insert['tea_address'] = $info['teaAddress'];
        }
        if (!empty($info['teaWay'])) {
            $insert['tea_way'] = $info['teaWay'];
        }
        if (!empty($info['teaFee'])) {
            $insert['tea_fee'] = $info['teaFee'];
        }
        if (!empty($plan)) {
            $insert['class_plan'] = $plan;
        }
        $db = DB::table('hs_tea');

        try {
            $db->insert($insert);
            return true;
        } catch (\Exception $e) {
            Log::error(__CLASS__ ."的". __FUNCTION__ . '异常', [
                'info' => $e->getMessage(),
                'line' => $e->getLine()
            ]);
            return false;
        }
    }

    /**
     * 获取学生个人信息
     * @param $phone
     * @return mixed
     */
    public static function selectStuByPhone($phone)
    {
        if (strlen($phone) == 11) {
            $where = [
                'phone' => $phone
            ];
        } else {
            $where = [
                'id' => $phone
            ];
        }

        $db = DB::table('hs_stu');
        $select = [
            'id',
            'phone',
            'password',
            'name',
            'gender',
            'mail as email',
            'school',
            'grade',
            'class_id as classId',
            'stu_info as stuInfo',
            'parent_name as parentName',
            'parent_appellation as parentAppellation',
            'parent_vx as parentVX',
            'address',
            'class_address',
            'time',
            'fee',
            'class_plan as classPlan',
            'tea_num as teaNum',
            'tea_gender as teaGender',
            'tea_way as teaWay',
            'tea_info as teaInfo',
            'is_delete as isDelete',
            'create_time as createTime',
            'update_time as updateTime'
        ];
        $data = $db->select($select)->where($where)->get()->toArray();
        return static::stdToArray($data);
    }

    public static function selectTeaByPhone($phone)
    {
        if (strlen($phone) == 11) {
            $where = [
                'phone' => $phone
            ];
        } else {
            $where = [
                'id' => $phone
            ];
        }

        $db = DB::table('hs_tea');
        $select = [
            'id',
            'phone',
            'password',
            'name',
            'img_url as imgUrl',
            'gender',
            'mail as email',
            'birth',
            'place',
            'qq',
            'vx',
            'class_id as classId',
            'motto',
            'major',
            'itic',
            'flanguages',
            'band',
            'putonghua',
            'experience',
            'class_plan as classPlan',
            'tea_info as teaInfo',
            'tea_way as teaWay',
            'tea_address as teaAddress',
            'is_delete as isDelete',
            'create_time as createTime',
            'update_time as updateTime'
        ];
        $data = $db->select($select)->where($where)->get()->toArray();
        return static::stdToArray($data);
    }

    /**
     * 用户登录
     * @param $phone
     * @return mixed
     */
    public static function loginByPhone($phone)
    {
        $userInfo = self::selectStuByPhone($phone);
        $res['identity'] = 'isStudent';
        if (empty($userInfo)) {
            unset($res['identity']);
            $userInfo = self::selectTeaByPhone($phone);
        }

        $res['id'] = $userInfo[0]['id'];
        $res['phone'] = $userInfo[0]['phone'];
        $res['password'] = $userInfo[0]['password'];
        unset($userInfo);
        return $res;
    }

    public static function selectAllStu($size,$offset){
        $db = DB::table('hs_stu');
        $select = [
            'id',
            'phone',
            'password',
            'name',
            'gender',
            'mail as email',
            'school',
            'grade',
            'class_id as classId',
            'stu_info as stuInfo',
            'parent_name as parentName',
            'parent_appellation as parentAppellation',
            'parent_vx as parentVX',
            'address',
            'class_address',
            'time',
            'fee',
            'class_plan as classPlan',
            'tea_num as teaNum',
            'tea_gender as teaGender',
            'tea_way as teaWay',
            'tea_info as teaInfo',
            'is_delete as isDelete',
            'create_time as createTime',
            'update_time as updateTime'
        ];
        $data = $db->select($select)->offset($offset)->limit($size)->get()->toArray();
        return static::stdToArray($data);
    }

    public static function selectAllTea($size,$offset){
        $db = DB::table('hs_tea');
        $select = [
            'id',
            'phone',
            'password',
            'name',
            'img_url as imgUrl',
            'gender',
            'mail as email',
            'birth',
            'place',
            'qq',
            'vx',
            'class_id as classId',
            'motto',
            'major',
            'itic',
            'flanguages',
            'band',
            'putonghua',
            'experience',
            'class_plan as classPlan',
            'tea_info as teaInfo',
            'tea_way as teaWay',
            'tea_address as teaAddress',
            'is_delete as isDelete',
            'create_time as createTime',
            'update_time as updateTime'
        ];
        $data = $db->select($select)->offset($offset)->limit($size)->get()->toArray();
        return static::stdToArray($data);
    }
}