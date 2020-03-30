<?php


namespace Unit;


class Checkunit
{
    /**
     * 参数格式验证,长度验证
     * @param   $receive    要验证的参数(string)
     * @param   $type       验证方式  -1:参数长度验证  1:email   2:手机号  3:英文  4:数字  5:汉字  6:url地址  7:身份证  8:QQ
     * @param   $desc       字段名称
     * @param   $num        规定字符串长度 如果单位是字节，请在使用时自行除以3
     * @return mixed
     */
    public static function verification($receive, $type = -1, $desc = '参数',$num = 0)
    {
        $res['code'] = 404;
        if (empty($receive)) {
            $res['msg'] = $desc . '不能为空';
            return $res;
        }
        if (!empty($num) && $num > 0) {
            $len = mb_strlen($receive, 'UTF8');
            if ($len > $num) {
                $res['msg'] = $desc . '不能超过' . floor($num) . '个字符';
                return $res;
            }
        }

        switch ($type) {
            case 1:
            case 'email':
                $rules = '/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/';
                break;
            case 2:
            case 'mobile':
                $rules = '/^1[34578]\d{9}$/';
                break;
            case 3:
            case 'en':
                $rules = '/^[a-zA-Z\s]+$/';
                break;
            case 4:
            case 'number':
                $rules = '/^[0-9]*$/';
                break;
            case 5:
            case 'cn':
                $rules = '/^[\u4e00-\u9fa5]{0,}$/';
                break;
            case 6:
            case 'url':
                $rules = '/^http://([\w-]+\.)+[\w-]+(/[\w-./?%&=]*)?$/';
                break;
            case 7:
            case 'id':
                $rules = '/^((\d{18})|([0-9x]{18})|([0-9X]{18}))$/';
                break;
            case 8:
            case 'qq':
                $rules = '/[1-9][0-9]{4,}/';
                break;
            default:
                $rules = '//';
                break;
        }
        if (preg_match($rules, $receive)) {
            $res['code'] = 200;
            $res['msg'] = '校验通过';
            return $res;
        } else {
            $res['msg'] = $desc . '参数不合法';
            return $res;
        }

    }

}