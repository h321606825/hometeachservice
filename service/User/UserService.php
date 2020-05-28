<?php


namespace Service\User;


use App\Http\Controllers\Api\ConstVariable;
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
            'area'=>$info['area'],
            'school'=>$info['school'],
        ];
        if (!empty($info['itic'])) {
            $insert['itic'] = $info['itic'];
        }
        if (!empty($info['flanguages'])) {
            $insert['flanguages'] = $info['flanguages'];
        }
        if (!empty($info['band'])) {
            $insert['band'] = $info['band'];
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
            $insert['tea_fee'] = $info['fee'];
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
                'stu.id' => $phone
            ];
        }

        $where['stu.is_delete'] = 0;

        $db = DB::table('hs_stu as stu')
                ->leftJoin('base_class as cla','stu.class_id','=','cla.id');
        $select = [
            'stu.id',
            'phone',
            'password',
            'name',
            'gender',
            'mail as email',
            'school',
            'grade',
            'class_id as classId',
            'cla.class as className',
            'stu_info as stuInfo',
            'parent_name as parentName',
            'parent_appellation as parentAppellation',
            'parent_vx as parentVX',
            'address',
            'class_address as classAddress',
            'time',
            'fee',
            'class_plan as classPlan',
            'tea_num as teaNum',
            'tea_gender as teaGender',
            'tea_way as teaWay',
            'tea_info as teaInfo',
            'stu.is_delete as isDelete',
            'stu.create_time as createTime',
            'stu.update_time as updateTime'
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
                'tea.id' => $phone
            ];
        }

        $where['tea.is_delete'] = 0;

        $db = DB::table('hs_tea as tea')
                ->leftJoin('base_class as cla', 'tea.class_id','=','cla.id');
        $select = [
            'tea.id',
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
            'cla.class as className',
            'motto',
            'school as teaSchool',
            'major',
            'itic',
            'flanguages',
            'band',
            'putonghua',
            'experience',
            'class_plan as classPlan',
            'tea_info as teaInfo',
            'tea_way as teaWay',
            'tea_fee as teaFee',
            'tea_address as teaAddress',
            'tea.is_delete as isDelete',
            'tea.create_time as createTime',
            'tea.update_time as updateTime',
            'area',
            DB::raw('case when tea.is_delete =1 then "已删除" when state = 10 then "招聘中" when state = 20 then"已安排" end as state'),
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
        if(empty($userInfo))
            return null;
        $res['id'] = $userInfo[0]['id'];
        $res['phone'] = $userInfo[0]['phone'];
        $res['password'] = $userInfo[0]['password'];
        unset($userInfo);
        return $res;
    }

    public static function selectAllStu($query = [],$size,$offset){

        $db = DB::table('hs_stu as stu')
                ->leftJoin('base_class as cl', 'stu.class_id','=','cl.id');
        $select = [
            'stu.id',
            'phone',
            'password',
            'name',
            'gender',
            'mail as email',
            'school',
            'grade',
            'class_id as classId',
            'cl.class as class',
            'stu_info as stuInfo',
            'parent_name as parentName',
            'parent_appellation as parentAppellation',
            'parent_vx as parentVX',
            'address',
            'class_address as classAddress',
            'time',
            'fee',
            'class_plan as classPlan',
            'tea_num as teaNum',
            'tea_gender as teaGender',
            'tea_way as teaWay',
            'tea_info as teaInfo',
            'stu.is_delete as isDelete',
            'stu.create_time as createTime',
            'stu.update_time as updateTime',
            DB::raw('case when stu.is_delete = 1 then "已删除" when state = 10 then "招聘中" when state = 20 then"已安排" end as state'),
            ];
        if(!empty($query['name'])){
            $where[] = ['name','like',"%" . $query['name'] . "%",];
        }
        if(!empty($query['phone'])){
            $where[] = ['phone','like',"%" . $query['phone']. "%",];
        }
        if(!empty($query['state'])){
            $where[] = ['state','=',$query['state'],];
        }
        if(!empty($query['class'])){
            $where[] = ['class_id','=',$query['class']];
        }

        $where[] = [
            'stu.is_delete','=',0,
        ];

        $total = $db->select($select)->where($where)->count();
        $data = $db->select($select)->where($where)->limit($size)->offset(($offset-1)*10)->get()->toArray();
        $data['total'] = $total;
        return static::stdToArray($data);
    }

    public static function serializeStu(&$stuList){
        foreach ($stuList as $key => &$value){
            if(is_array($value)){
                $value['teaGender'] = ConstVariable::GENDER[$value['teaGender']];
                $value['grade'] .='年级';
                $value['gender'] = ConstVariable::GENDER[$value['gender']];
                $value['time'] .='小时';
                $value['fee'] .= '元';
                $value['teaWay'] = ConstVariable::TEAWAY[$value['teaWay']];
                $value['classPlan'] = explode(',',$value['classPlan']);
            }
        }
    }

    public static function selectAllTea($query=[],$size,$offset){
        $db = DB::table('hs_tea as tea')
                ->leftJoin('base_class as cl','tea.class_id','=','cl.id');
        $select = [
            'tea.id',
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
            'school',
            'class_id as classId',
            'cl.class as class',
            'motto',
            'area',
            'major',
            'itic',
            'flanguages',
            'band',
            'putonghua',
            DB::raw('case experience when 1 then "有家教经验" else "无家教经验" end as experience'),
            'class_plan as classPlan',
            'tea_info as teaInfo',
            DB::raw('case tea_way when 1 then "教员上门" when 2 then "网络远程" else "不限" end as teaWay'),
            'tea_address as teaAddress',
            'tea_fee as teaFee',
            'tea_speciality as teaSpeciality',
            'tea.is_delete as isDelete',
            'tea.create_time as createTime',
            'tea.update_time as updateTime',
            DB::raw('case when tea.is_delete =1 then "已删除" when state = 10 then "招聘中" when state = 20 then"已安排" end as state'),        ];
        if(!empty($query['name'])){
            $where[] = ['name','like',"%" . $query['name'] . "%",];
        }
        if(!empty($query['phone'])){
            $where[] = ['phone','like',"%" . $query['phone']. "%",];
        }
        if(!empty($query['state'])){
            $where[] = ['state','=',$query['state'],];
        }
        if(!empty($query['class'])){
            $where[] = ['class_id','=',$query['class']];
        }

        $where[] = [
            'tea.is_delete','=',0,
        ];
        
        $total = $db->select($select)->where($where)->count();
        $data = $db->select($select)->where($where)->offset(($offset-1)*10)->limit($size)->get()->toArray();
        $data['total'] = $total;
        return static::stdToArray($data);
    }

    public static function serializeTea(&$tea){
        foreach ($tea as $k => &$v){
            if(is_array($v)){
                $v['gender'] = ConstVariable::GENDER[$v['gender']];
                $v['imgUrl'] = env('APP_URL','http://localhost') . $v['imgUrl'];
                $v['itic'] = ConstVariable::ITIC[$v['itic']];
                $v['flanguages'] = ConstVariable::FLANGUAGE[$v['flanguages']];
                $v['band'] = ConstVariable::BAND[$v['band']];
                $v['putonghua'] = ConstVariable::PUTONGHUA[$v['putonghua']];
                $v['classPlan'] = explode(',',$v['classPlan']);
                $v['age'] = static::age($v['birth']);
            }
        
        }
    }

    public static function updateTea($base,$info,$plan = ''){
        $update = [
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
            $update['itic'] = $info['itic'];
        }
        if (!empty($info['flanguages'])) {
            $update['flanguages'] = $info['flanguages'];
        }
        if (!empty($info['putonghua'])) {
            $update['putonghua'] = $info['putonghua'];
        }
        if (!empty($info['experience'])) {
            $update['experience'] = $info['experience'];
        }
        if (!empty($info['teaSpeciality'])) {
            $update['tea_speciality'] = $info['teaSpeciality'];
        }
        if (!empty($info['class'])) {
            $update['class_id'] = $info['class'];
        }
        if (!empty($info['teaAddress'])) {
            $update['tea_address'] = $info['teaAddress'];
        }
        if (!empty($info['teaWay'])) {
            $update['tea_way'] = $info['teaWay'];
        }
        if (!empty($info['teaFee'])) {
            $update['tea_fee'] = $info['teaFee'];
        }
        if (!empty($plan)) {
            $update['class_plan'] = $plan;
        }
        $db = DB::table('hs_tea');
        $where = [
            'id' => $base['userId'],
        ];
        try {
            $db->where($where)->update($update);
            return true;
        } catch (\Exception $e) {
            Log::error("编辑教员".__CLASS__ ."的". __FUNCTION__ . '异常', [
                'info' => $e->getMessage(),
                'line' => $e->getLine()
            ]);
            return false;
        }
    }

    public static function updateStu($base, $info, $plan = '', $teaAsk = []){
        $db = DB::table('hs_stu');
        $update = [
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
            $update['gender'] = $info['gender'];
        }
        if (!empty($info['parentVX'])) {
            $update['parent_vx'] = $info['parentVX'];
        }
        if (!empty($plan)) {
            $update['class_plan'] = $plan;
        }
        if (!empty($teaAsk)) {
            if (!empty($teaAsk['teaNum'])) {
                $update['tea_num'] = $teaAsk['teaNum'];
            }
            if (!empty($teaAsk['teaGender'])) {
                $update['tea_gender'] = $teaAsk['teaGender'];
            }
            if (!empty($teaAsk['teaWay'])) {
                $update['tea_way'] = $teaAsk['teaWay'];
            }
            if (!empty($teaAsk['teaInfo'])) {
                $update['tea_info'] = $teaAsk['teaInfo'];
            }
        }
        $where = [
            'id' => $base['userId'],
        ];
        try {
            $db->where($where)->update($update);
            return true;
        } catch (\Exception $e) {
            Log::error('学员编辑异常', [
                'info' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile()
            ]);
            return false;
        }
    }

    public static function deleteTea($id){
        $update = [
            'is_delete' => 1,
        ];
        $where = [
            'id' => $id,
        ];
        $db = DB::table('hs_tea');
        try {
            $db->where($where)->update($update);
            return true;
        }catch (\Exception $e){
            Log::error('删除教员异常'.__CLASS__.'的'.__FUNCTION__,[
                'info'=>$e->getMessage(),
                'line'=>$e->getLine()
            ]);
            return false;
        }
    }

    public static function deleteStu($id){
        $update = [
            'is_delete' => 1,
        ];
        $where = [
            'id' => $id,
        ];
        $db = DB::table('hs_stu');
        try {
            $db->where($where)->update($update);
            return true;
        }catch (\Exception $e){
            Log::error('删除学员异常'.__CLASS__.'的'.__FUNCTION__,[
                'info'=>$e->getMessage(),
                'line'=>$e->getLine()
            ]);
            return false;
        }
    }

    public static function recommendStu($offset,$size){
        $where = [];
        $db = DB::table('hs_stu as stu')
            ->leftJoin('base_class as cl', 'stu.class_id','=','cl.id');
        $select = [
            'stu.id',
            'phone',
            'password',
            'name',
            'gender',
            'mail as email',
            'school',
            'grade',
            'class_id as classId',
            'cl.class as class',
            'stu_info as stuInfo',
            'parent_name as parentName',
            'parent_appellation as parentAppellation',
            'parent_vx as parentVX',
            'address',
            'class_address as classAddress',
            'time',
            'fee',
            'class_plan as classPlan',
            'tea_num as teaNum',
            'tea_gender as teaGender',
            'tea_way as teaWay',
            'tea_info as teaInfo',
            'stu.is_delete as isDelete',
            'stu.create_time as createTime',
            'stu.update_time as updateTime',
            DB::raw('case when stu.is_delete = 1 then "已删除" when state = 10 then "招聘中" when state = 20 then"已安排" end as state'),
            ];
        $where = [
            'stu.is_delete'=>0,
        ];
        $data = $db->select($select)->where($where)->orderBy('stu.create_time','desc')->limit(8)->get()->toArray();
        return static::stdToArray($data);

    }

    public static function recommendTea($offset,$size){
        $db = DB::table('hs_tea as tea')
                ->leftJoin('base_class as cl','tea.class_id','=','cl.id');
        $select = [
            'tea.id',
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
            'school',
            'class_id as classId',
            'cl.class as class',
            'motto',
            'major',
            'itic',
            'flanguages',
            'band',
            'putonghua',
            DB::raw('case experience when 1 then "有家教经验" else "无家教经验" end as experience'),
            'applause_rate as applauseRate',
            'class_plan as classPlan',
            'tea_info as teaInfo',
            DB::raw('case tea_way when 1 then "教员上门" when 2 then "网络远程" else "不限" end as teaWay'),
            'tea_address as teaAddress',
            'tea_fee as teaFee',
            'tea_speciality as teaSpeciality',
            'tea.is_delete as isDelete',
            'tea.create_time as createTime',
            'tea.update_time as updateTime',
            DB::raw('case when tea.is_delete =1 then "已删除" when state = 10 then "招聘中" when state = 20 then"已安排" end as state'),        ];
        $where['tea.is_delete'] = 0;

        $data = $db->select($select)->where($where)->orderBy('applause_rate','desc')->limit(8)->get()->toArray();
        return static::stdToArray($data);
    }

    public static function updateApplause($teaId,$evaluatRate){
        $where = [
            'id' => $teaId,
        ];
        $update = [
            'applause_rate' =>$evaluatRate,
        ];

        $db = DB::table('hs_tea');
        try{
            $db->where($where)->update($update);
            return true;
        }catch(\Exception $e){
            Log::error('更新教员好评率异常'.__CLASS__.'的'.__FUNCTION__,[
                'info'=>$e->getMessage(),
                'line'=>$e->getLine(),
                'code'=>$e->getCode(),
            ]);
            return false;
        }
    }

    public static function changeUserState($teaId,$stuId,$state){
        $resTea = self::changeTea($teaid,$state);
        $resStu = self::changeStu($stuId,$state);

        return $resTea & $resStu;
    }

    public static function changeTea($teaid,$state){
        $update = [
            'state' => $state,
        ];
        $where = [
            'id' => $teaId,
        ];

        $db = DB::table('hs_tea');
        try{
            $db->where($where)->update($update);
            return true;
        }catch(\Exception $e){
            Log::error('更新教员状态异常'.__CLASS__.'的'.__FUNCTION__,[
                'info'=>$e->getMessage(),
                'line'=>$e->getLine(),
                'code'=>$e->getCode(),
            ]);
            return false;
        }
    }

    public static function changeStu($stuId,$state){
        $update = [
            'state' => $state,
        ];
        $where = [
            'id' => $stuId,
        ];

        $db = DB::table('hs_stu');
        try{
            $db->where($where)->update($update);
            return true;
        }catch(\Exception $e){
            Log::error('更新学员状态异常'.__CLASS__.'的'.__FUNCTION__,[
                'info'=>$e->getMessage(),
                'line'=>$e->getLine(),
                'code'=>$e->getCode(),
            ]);
            return false;
        }
    }
}