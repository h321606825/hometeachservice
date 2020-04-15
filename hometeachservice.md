#目录

@[toc]

---

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
 
        request
 
        ```json
            {
              "type": 1//1 => '管理员登录',2 => '用户登录',3 => '学生注册',4 => '教师注册'
            }
        ```
 
         return
         ```json
        {
            "code": 200,
            "msg": "ok",
            "data": [
                "captcha":"data:image/jpeg;base64,"
        ]
        }
        ```
2. 文件上传
 - api/base/uploadFile
 
 return
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
        
            request
           ```json
            {
                "id": "root",
                "passport": "passport"
            }
           ```
  2. 登录
  
        - api/admin/passport/login
    
           request
           ```json
            {
              "id": "root",
              "password": "password",
              "captcha": "re23"
            } 
           ```
            return
            ```json
            {
                "code": 200,
                "msg": "",
                "data": [
                    {
                        "token": "******"
                 }
                ]
            }
            ```

  3. 退出
      - api/admin/passport/logout

           return
           ```json
                {
                    "code": 200,
                    "msg": "注销成功",
                     "data": []
                }
           ```
  4. 修改客服信息
      - api/admin/user/customer
      
           request
           ```json
            {
              "phone": 18080808080,
              "qq": ""
            }
           ```
           return
           ```json
            {
                "code": 200,
                "msg": "修改成功",
                 "data": []
            }
           ```
  5. 查看订单列表 
      - api/admin/order/info
            
           request
           ```json
            {
              "page": 1,//当前页
              "size": 10,//页面大小
            }
           ```
           
           return
           ```json
            {
              "code": 200,
              "msg": "ok",
              "data": [
                {
                  "stuName": "",
                  "orderId":20202,
                  "teaName": "",
                  "stuPhone": "",
                  "teaPhone": "",
                  "state": ""//订单状态
                },
                {}
              ]
            }   
           ```
           
## 用户测

1. 教师注册
    - api/user/register/tea
 
         request
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
            "itic":0,//0-无教师证，1-有教师证   非必传
            "flanguages":1,//1-英语，2-俄语   非必传
            "putonghua":1,//1-较差，2-一般，3-标准   非必传
            "experience":0,//1-有家教经验，0-无   非必传
            "teaSpeciality":"",//教员特长   非必传
            "class":id,//辅导课程id   非必传
            "teaAddress":""//授课区域，香坊区    非必传
            "teaWay":0,//授课方式，1-教员上门，2-网络远程，默认不限   非必传
            "teaFee"30,//课程收费，0-面议，30-每小时30元   非必传
            ],
            "plan": ["1,a"], //非必传
            "captcha": "fe4a"
        }
        ```
 2. 学生注册
     - api/user/register/stu
            
          request
          ```json
         {
           "base": [
               "phone":133333333,
               "password":"12324342"
               ],
           "info": [
               "name":"",
               "email":"",
               "school":"",
               "grade":1, 
               "class":id,//辅导科目
               "stuInfo":"",//学员情况
               "parentName":"",//家长姓名
               "parentAppellation":"",//家长称呼
               "address":"",//所在区域
               "classAddress":"",//授课地址
               "time":1, //授课时长
               "fee":30,  //课时费用
               "gender":"1",//1是男，2是女  非必传
               "parentVX":"",//家长微信   非必传
               ],
           //teaAsk 非必传
           "teaAsk":[
               "teaNum":1,//教师数量
               "teaGender":1,//教员性别
               "teaWay":0,//授课方式
               "teaInfo":"",//教员要求详述
           ]
           "plan": ["1,a"],//非必传
           "captcha": "fe4a"
         }
          ```
 
 3. 登录
 
       - api/user/login
 
         **注，同学生登录接口** 
 
         request
         
           ```json
            {
               "phone":13300000000,
               "password":"vava",
               "captcha":"1q2w"
            }
           ```
          return
            ```json
            {
                "code": "fd",
                "msg": "登录成功",
                "data": [
                  "token":""
            ]
            }
            ```

4. 退出
    - api/user/logout
    
    return
    ```json
   {
     "code": 200,
     "msg": "注销成功"
    }
    ```
   
5. 个人中心
    - api/user/self
    
    *注，具体返回参数见注册参数*
    
    return
    ```json
       "code":"",
       "msg":"ok",
       "data":{
           id:"",
           phone:"",
           identity:2,//2-tea,3-student
       } 
    ```
6. 学员列表
    - api/user/stu/list
    
    request
    ```json
    {
       "size": 10,//页面显示10条
       "offset": 1,//第几页
    }
    ```
    
    return
    ```json
     {
       "code":"",
        "msg":"ok",
       "data":{
                {
                  "id": "",
                "phone":133333333,
                "password":"12324342",
                "name":"",
                 "email":"",
                 "school":"",
                 "grade":1, 
                 "class":id,
                 "stuInfo":"",
                 "parentName":"",
                 "parentAppellation":"",
                 "address":"",
                 "classAddress":"",
                 "time":1, 
                 "fee":30,  
                 "gender":"1",
                 "parentVX":""
                },
               {
                 "id": "",
               "phone":133333333,
               "password":"12324342",
               "name":"",
                "email":"",
                "school":"",
                "grade":1, 
                "class":id,
                "stuInfo":"",
                "parentName":"",
                "parentAppellation":"",
                "address":"",
                "classAddress":"",
                "time":1, 
                "fee":30,  
                "gender":"1",
                "parentVX":""
              }
          }
     }
    ```
7. 教员列表
    - api/user/tea/list
    
    request
    ```json
    {
       "size": 10,//页面显示10条
       "offset": 1,//第几页
    }
    ```
        
    return
    ```json
       "code":"",
       "msg":"ok",
       "data":{
               {
                 id:"",
                 phone:"",
               },
              {
                id:"",
                phone:"",
             }
         }
    }
    ```
   
8. 学员详情
    - api/user/stu/info
        
        request
        ```json
          {
            "id": 1//学员id
          }
        ```
      
       return
       ```json
        {
          "id": "",
          "phone":133333333,
          "password":"12324342",
          "name":"",
           "email":"",
           "school":"",
           "grade":1, 
           "class":id,
           "stuInfo":"",
           "parentName":"",
           "parentAppellation":"",
           "address":"",
           "classAddress":"",
           "time":1, 
           "fee":30,  
           "gender":"1",
           "parentVX":""
        }
       ```
      
9. 教员详情
    - api/api/user/tea/info
      
        request
        ```json
          {
            "id": 1//学员id
          }
        ```
        return
        ```json
          {
            "id": 1,
            "phone":133333333,
            "password":"12324342",
            "name":"",
            "gender":"1",//1是男，2是女
            "mail":"",
            "birth":"1996-1-3",
            "QQ":"",
            "vx":"",
            "motto":"教员格言",
            "major":"英语",
            "resume":"个人简介",
            "itic":0,
            "flanguages":1,
            "putonghua":1,
            "experience":0,
            "teaSpeciality":"",
            "class":id,
            "teaAddress":"",
            "teaWay":0,
            "teaFee"30,
          }       
        ```
10. 获取客服信息
    - api/user/info/customer
    
        return
        ```json
          {
            "code":200,
            "msg": "ok",
            "data": {
                "phone": 18880808080,
                "qq": ""
            }
          }
        ```
11. 获取用户订单

      - api/user/order/info
            
           request
           ```json
            {
              "page": 1,//当前页
              "size": 10,//页面大小
            }
           ```
           
           return
           ```json
            {
              "code": 200,
              "msg": "ok",
              "data": [
                {
                  "stuName": "",
                  "orderId":20202,
                  "teaName": "",
                  "stuPhone": "",
                  "teaPhone": "",
                  "state": ""//订单状态
                },
                {}
              ]
            }   
           ```
12. 教员下单
    - api/user/tea/order
    
        request
        ```json
        {
          "stuId": 10101
        }
        ```
        return//返回该用户的所有订单
        ```json
        {
           "code": 200,
            "msg": "ok",
            "data": {
              "stuName": "",
              "orderId":20202,
              "teaName": "",
              "stuPhone": "",
              "teaPhone": "",
              "state": ""//订单状态
            }
        }
        ```
13. 学员下单
    - api/user/stu/order
    
        request
        ```json
        {
          "teaId": 10101
        }
        ```
        return//返回该用户的所有订单
        ```json
        {
           "code": 200,
            "msg": "ok",
            "data": {
              "stuName": "",
              "orderId":20202,
              "teaName": "",
              "stuPhone": "",
              "teaPhone": "",
              "state": ""//订单状态
            }
        }
        ```