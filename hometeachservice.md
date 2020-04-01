##注意事项
1. 所有登录后的接口，都必须传token
2. 返回结构
 ```json
{
   "code": 200,
   "msg": "ok",
    "data": []
}
```
3 . 状态码
```
成功:200,
其他是非成功，具体再各个接口中有返回。

```
## 基础快
1. 获取图片验证码
 - api/base/getCaptcha
 ```json
{
    "code": 200,
    "msg": "ok",
    "data": [
        "captcha":"data:image/jpeg;base64,"
]
}
```
2 . 文件上传
 - api/base/uploadFile
 
 返回
```json
{
    "code": 200,
    "msg": "ok",
    "data": [
        "failname":"***.jpg/png/jpeg"
    ]
}
```
## 后台模块
### 登录快
  1. 添加管理员
     - api/admin/passport/add
   ```json
    {
        "id": "root",
        "passport": "passport"
    }
   ```
  2. 登录
    - api/admin/passport/login
    
   请求
   ```json
{
  "id": "root",
  "password": "password",
  "captcha": "re23"
} 
   ```
返回
```json
{
    "token": "******"
}
```

   3. 退出
     - api/admin/passport/logout

 返回
   ```json
{
    "code": 200,
    "msg": "注销成功",
     "data": []
}
```

## 教师测

1. 注册
 - api/user/register/tea
 
 请求
 ```json
{
    "base": [
    "phone":133333333,
    "password":"12324342"
    ],
    "info": [
    "name":"",
    "gender":"1",//1是男，2是女
    "mail":"",
    "birth":"1996-1-3",
    "QQ":"",
    "vx":"",
    "motto":"教员格言",
    "major":"英语",
    "resume":"个人简介",
    ],
    "plan": ["1,a"],
    "captcha": "fe4a"
}
```
 2 . 登录
 - api/user/login
 
 请求
```json
   "phone":13300000000,
   "password":"vava",
   "captcha":"1q2w"
```
返回
```json
{
    "code": "fd",
    "msg": "登录成功",
    "data": []
}
```

