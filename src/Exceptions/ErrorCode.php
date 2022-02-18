<?php

declare(strict_types=1);
/**
 * This file is part of hyperf/helpers.
 *
 * @link     https://github.com/huangdijia/laravel-youdu
 * @document https://github.com/huangdijia/laravel-youdu/blob/3.x/README.md
 * @contact  huangdijia@gmail.com
 */
namespace Huangdijia\Youdu\Exceptions;

class ErrorCode
{
    public const ERRCODE_OK = 0; //    操作成功,查询结果逻辑真

    public const ERRCODE_INTERNALERR = -1; //   异常,未定义的系统错误

    public const ERRCODE_FALSE = 2; //    查询结果逻辑假

    public const ERRCODE_NORIGHT = 3; //    接口权限不足

    public const ERRCODE_ILLEGAL_ACCESS = 4;

    //    非法访问数据
    // const ERRCODE_INVALID_REQUEST = 5;//    错误的请求
    public const ERRCODE_MISS_ACCESSTOKEN = 100000; //   空的ACCESS_TOKEN

    public const ERRCODE_ACCESSTOKEN_NOTEXIST = 100001; //   ACCESS_TOKEN不存在

    public const ERRCODE_ACCESSTOKEN_EXPIRED = 100002; //   ACCESS_TOKEN过期

    public const ERRCODE_INVALID_REQUEST = 100003; //   错误的请求

    public const ERRCODE_INVALID_FORMAT_JSON = 100004; //   错误的消息格式

    public const ERRCODE_INVALID_APPID = 100005; //   无效的AppId

    public const ERRCODE_INVALID_ENCRYPT = 100006; //   encrypt字段解密失败

    public const ERRCODE_MISS_BUIN = 100008; //   缺少企业号(buin)

    public const ERRCODE_INVALID_BUIN = 100009; //   无效的企业号

    public const ERRCODE_MISS_APPID = 100010; //   缺少应用ID(appId)

    public const ERRCODE_MISS_ENCRYPT = 100011; //   缺少加密字段

    public const ERRCODE_INVALID_FORMAT_BUIN = 100012; //   企业号字段格式不正确（int）

    public const ERRCODE_INVALID_FORMAT_APPID = 100013; //   应用ID字段格式不正确(string)

    public const ERRCODE_INVALID_FORMAT_ENCRYPT = 100014; //   encrypt字段格式不正确(string)

    public const ERRCODE_TOUSER_OVERLIMIT = 200001; //   toUser超过长度

    public const ERRCODE_INVALID_TOUSER = 200007; //   无效的toUser

    public const ERRCODE_INVALID_MULTIPART = 200008; //   上传文件格式失败

    public const ERRCODE_INVALID_FILETYPE = 200009; //   无效的文件类型

    public const ERRCODE_FILE_NOTEXIST = 200010; //   资源文件不存在

    public const ERRCODE_ACCOUNT_NOTEXIST = 300001; //   UserId不存在

    public const ERRCODE_ACCOUNT_AUTHFAILED = 300002; //   帐号验证失败

    public const ERRCODE_DEPT_ROOT_FORBIDDEN = 300101; //   不能操作根部门

    public const ERRCODE_INVALID_FORMAT_DEPTID = 300102; //   无效的部门ID格式(int)

    public const ERRCODE_INVALID_FORMAT_DEPTNAME = 300103; //   无效的部门名格式(string)

    public const ERRCODE_INVALID_FORMAT_DEPTSORTID = 300104; //   无效的部门排序ID格式(int)

    public const ERRCODE_INVALID_FORMAT_DEPTPARENTID = 300105; //   无效的部门父ID格式(string)

    public const ERRCODE_INVALID_DEPTID = 300106; //   无效的部门ID

    public const ERRCODE_INVALID_DEPTNAME = 300107; //   无效的部门名

    public const ERRCODE_INVALID_DEPTPARENTID = 300108; //   无效的部门父ID

    public const ERRCODE_MISS_DEPTID = 300109; //   缺少部门ID

    public const ERRCODE_MISS_DEPTNAME = 300110; //   缺少部门名

    public const ERRCODE_MISS_DEPTPARENTID = 300111; //   缺少部门父ID

    public const ERRCODE_EXIST_DEPT = 300112; //   部门ID已存在

    public const ERRCODE_EXIST_DEPT_SORTID = 300113; //   部门排序ID已存在

    public const ERRCODE_DEPT_NOT_EXIST = 300114; //   部门不存在

    public const ERRCODE_DEPT_HAS_SUBDEPT = 300115; //   部门下存在子部门

    public const ERRCODE_DEPT_HAS_USERS = 300116; //   部门下存在用户

    public const ERRCODE_OVERLIMIT_DEPTNAME = 300117; //   部门名过长

    public const ERRCODE_NOTEXIST_ORG = 300118; //   组织架构不存在

    public const ERRCODE_INVALID_DEPTALIAS = 300119; //   无效的部门索引(alias)

    public const ERRCODE_MISS_USERID = 300201; //   缺少用户ID

    public const ERRCODE_MISS_USERNAME = 300202; //   缺少用户名

    public const ERRCODE_MISS_USERDEPT = 300203; //   用户没有指定部门

    public const ERRCODE_MISS_USERLIST = 300204; //   缺少用户列表

    public const ERRCODE_INVALID_FORMAT_USERID = 300205; //   无效的userId格式

    public const ERRCODE_INVALID_FORMAT_USERNAME = 300206; //   无效的用户名称格式

    public const ERRCODE_INVALID_FORMAT_USERGENDER = 300207; //   无效的gender格式

    public const ERRCODE_INVALID_FORMAT_USEREMAIL = 300208; //   无效的email格式

    public const ERRCODE_INVALID_FORMAT_USERPHONE = 300209; //   无效的phone格式

    public const ERRCODE_INVALID_FORMAT_USERMOBILE = 300210; //   无效的mobile格式

    public const ERRCODE_INVALID_FORMAT_USERDEPT = 300211; //   无效的用户部门格式

    public const ERRCODE_INVALID_FORMAT_USERLIST = 300212; //   无效的用户列表格式

    public const ERRCODE_INVALID_FORMAT_PASSWORD = 300213; //   无效的password格式

    public const ERRCODE_INVALID_FORMAT_AUTHTYPE = 300214; //   无效的认证类型

    public const ERRCODE_INVALID_FORMAT_POSITION = 300215; //   无效的职务格式

    public const ERRCODE_INVALID_FORMAT_WEIGHT = 300216; //   无效的职务权重格式

    public const ERRCODE_INVALID_FORMAT_SORTID = 300217; //   无效的排序格式

    public const ERRCODE_OVERLIMIT_USERID = 300218; //   userId超过长度

    public const ERRCODE_OVERLIMIT_USERNAME = 300219; //   user名称超过长度

    public const ERRCODE_OVERLIMIT_USEREMAIL = 300220; //   email超过长度

    public const ERRCODE_OVERLIMIT_USERDEPT = 300221; //   用户部门超过长度

    public const ERRCODE_OVERLIMIT_USERLIST = 300222; //   userList超过长度

    public const ERRCODE_INVALID_USERID = 300223; //   无效的userId

    public const ERRCODE_USER_EXIST = 300224; //   用户已存在

    public const ERRCODE_USER_NOTEXIST = 300225; //   用户不存在

    public const ERRCODE_EMPTY_DEPT = 300226; //   空的部门

    public const ERRCODE_USER_NOTIN_DEPT = 300227; //   用户不属于该部门

    public const ERRCODE_INVALID_FORMAT_DEPTLIST = 300301; //   无效的部门列表格式

    public const ERRCODE_INVALID_FORMAT_DEPT = 300302; //   无效的部门格式

    public const ERRCODE_INVALID_FORMAT_USER = 300303; //   无效的用户格式

    public const ERRCODE_INVALID_FORMAT_POSITIONS = 300304; //   无效的部门职务信息格式

    public const ERRCODE_MISS_GENDER = 300306; //   缺少性别

    public const ERRCODE_NOTEXIST_DEPT = 300307; //   部门不存在

    public const ERRCODE_NOTEXIST_PARENTDEPT = 300308; //   父部门不存在

    public const ERRCODE_REPEAT_USERID = 300311; //   重复的userId

    public const ERRCODE_NOTEXIST_JOBID = 300311; //   jobId不存在

    public const ERRCODE_EXIST_ENT_REPLACEALL = 300312; //   已存在同步请求

    public const ERRCODE_MISS_GROUPNAME = 500001; //   群名称为空

    public const ERRCODE_INVALID_FORMAT_GROUPNAME = 500002; //   群名称格式不正确(string)

    public const ERRCODE_OVERLIMIT_GROUPNAME = 500003; //   群名称长度超过限制

    public const ERRCODE_GROUP_NOTEXIST = 500004; //   不存在该群

    public const ERRCODE_GROUP_ACCESS_ERROR = 500005; //   非法访问群

    public const ERRCODE_MISS_GROUPID = 500006; //   缺少群ID

    public const ERRCODE_INVALID_GROUP_USERID = 500007; //   无效的userId

    public const ERRCODE_INVALID_FORMAT_GROUPID = 500008; //   无效的群Id

    public static $errors = [
        self::ERRCODE_OK => ' 操作成功,查询结果逻辑真',
        self::ERRCODE_INTERNALERR => '异常,未定义的系统错误s',
        self::ERRCODE_FALSE => ' 查询结果逻辑假',
        self::ERRCODE_NORIGHT => ' 接口权限不足',
        self::ERRCODE_ILLEGAL_ACCESS => ' 非法访问数据',
        // self::ERRCODE_INVALID_REQUEST => '错误的请求',
        self::ERRCODE_MISS_ACCESSTOKEN => '空的ACCESS_TOKEN',
        self::ERRCODE_ACCESSTOKEN_NOTEXIST => 'ACCESS_TOKEN不存在',
        self::ERRCODE_ACCESSTOKEN_EXPIRED => 'ACCESS_TOKEN过期',
        self::ERRCODE_INVALID_REQUEST => '错误的请求',
        self::ERRCODE_INVALID_FORMAT_JSON => '错误的消息格式',
        self::ERRCODE_INVALID_APPID => '无效的AppId',
        self::ERRCODE_INVALID_ENCRYPT => 'encrypt字段解密失败',
        self::ERRCODE_MISS_BUIN => '缺少企业号(buin)',
        self::ERRCODE_INVALID_BUIN => '无效的企业号',
        self::ERRCODE_MISS_APPID => '缺少应用ID(appId)',
        self::ERRCODE_MISS_ENCRYPT => '缺少加密字段',
        self::ERRCODE_INVALID_FORMAT_BUIN => '企业号字段格式不正确',
        self::ERRCODE_INVALID_FORMAT_APPID => '应用ID字段格式不正确',
        self::ERRCODE_INVALID_FORMAT_ENCRYPT => 'encrypt字段格式不正确',
        self::ERRCODE_TOUSER_OVERLIMIT => 'toUser超过长度',
        self::ERRCODE_INVALID_TOUSER => '无效的toUser',
        self::ERRCODE_INVALID_MULTIPART => '上传文件格式失败',
        self::ERRCODE_INVALID_FILETYPE => '无效的文件类型',
        self::ERRCODE_FILE_NOTEXIST => '资源文件不存在',
        self::ERRCODE_ACCOUNT_NOTEXIST => 'UserId不存在',
        self::ERRCODE_ACCOUNT_AUTHFAILED => '帐号验证失败',
        self::ERRCODE_DEPT_ROOT_FORBIDDEN => '不能操作根部门',
        self::ERRCODE_INVALID_FORMAT_DEPTID => '无效的部门ID格式',
        self::ERRCODE_INVALID_FORMAT_DEPTNAME => '无效的部门名格式',
        self::ERRCODE_INVALID_FORMAT_DEPTSORTID => '无效的部门排序ID格式',
        self::ERRCODE_INVALID_FORMAT_DEPTPARENTID => '无效的部门父ID格式',
        self::ERRCODE_INVALID_DEPTID => '无效的部门ID',
        self::ERRCODE_INVALID_DEPTNAME => '无效的部门名',
        self::ERRCODE_INVALID_DEPTPARENTID => '无效的部门父ID',
        self::ERRCODE_MISS_DEPTID => '缺少部门ID',
        self::ERRCODE_MISS_DEPTNAME => '缺少部门名',
        self::ERRCODE_MISS_DEPTPARENTID => '缺少部门父ID',
        self::ERRCODE_EXIST_DEPT => '部门ID已存在',
        self::ERRCODE_EXIST_DEPT_SORTID => '部门排序ID已存在',
        self::ERRCODE_DEPT_NOT_EXIST => '部门不存在',
        self::ERRCODE_DEPT_HAS_SUBDEPT => '部门下存在子部门',
        self::ERRCODE_DEPT_HAS_USERS => '部门下存在用户',
        self::ERRCODE_OVERLIMIT_DEPTNAME => '部门名过长',
        self::ERRCODE_NOTEXIST_ORG => '组织架构不存在',
        self::ERRCODE_INVALID_DEPTALIAS => '无效的部门索引',
        self::ERRCODE_MISS_USERID => '缺少用户ID',
        self::ERRCODE_MISS_USERNAME => '缺少用户名',
        self::ERRCODE_MISS_USERDEPT => '用户没有指定部门',
        self::ERRCODE_MISS_USERLIST => '缺少用户列表',
        self::ERRCODE_INVALID_FORMAT_USERID => '无效的userId格式',
        self::ERRCODE_INVALID_FORMAT_USERNAME => '无效的用户名称格式',
        self::ERRCODE_INVALID_FORMAT_USERGENDER => '无效的gender格式',
        self::ERRCODE_INVALID_FORMAT_USEREMAIL => '无效的email格式',
        self::ERRCODE_INVALID_FORMAT_USERPHONE => '无效的phone格式',
        self::ERRCODE_INVALID_FORMAT_USERMOBILE => '无效的mobile格式',
        self::ERRCODE_INVALID_FORMAT_USERDEPT => '无效的用户部门格式',
        self::ERRCODE_INVALID_FORMAT_USERLIST => '无效的用户列表格式',
        self::ERRCODE_INVALID_FORMAT_PASSWORD => '无效的password格式',
        self::ERRCODE_INVALID_FORMAT_AUTHTYPE => '无效的认证类型',
        self::ERRCODE_INVALID_FORMAT_POSITION => '无效的职务格式',
        self::ERRCODE_INVALID_FORMAT_WEIGHT => '无效的职务权重格式',
        self::ERRCODE_INVALID_FORMAT_SORTID => '无效的排序格式',
        self::ERRCODE_OVERLIMIT_USERID => 'userId超过长度',
        self::ERRCODE_OVERLIMIT_USERNAME => 'user名称超过长度',
        self::ERRCODE_OVERLIMIT_USEREMAIL => 'email超过长度',
        self::ERRCODE_OVERLIMIT_USERDEPT => '用户部门超过长度',
        self::ERRCODE_OVERLIMIT_USERLIST => 'userList超过长度',
        self::ERRCODE_INVALID_USERID => '无效的userId',
        self::ERRCODE_USER_EXIST => '用户已存在',
        self::ERRCODE_USER_NOTEXIST => '用户不存在',
        self::ERRCODE_EMPTY_DEPT => '空的部门',
        self::ERRCODE_USER_NOTIN_DEPT => '用户不属于该部门',
        self::ERRCODE_INVALID_FORMAT_DEPTLIST => '无效的部门列表格式',
        self::ERRCODE_INVALID_FORMAT_DEPT => '无效的部门格式',
        self::ERRCODE_INVALID_FORMAT_USER => '无效的用户格式',
        self::ERRCODE_INVALID_FORMAT_POSITIONS => '无效的部门职务信息格式',
        self::ERRCODE_MISS_GENDER => '缺少性别',
        self::ERRCODE_NOTEXIST_DEPT => '部门不存在',
        self::ERRCODE_NOTEXIST_PARENTDEPT => '父部门不存在',
        self::ERRCODE_REPEAT_USERID => '重复的userId',
        self::ERRCODE_NOTEXIST_JOBID => 'jobId不存在',
        self::ERRCODE_EXIST_ENT_REPLACEALL => '已存在同步请求',
        self::ERRCODE_MISS_GROUPNAME => '群名称为空',
        self::ERRCODE_INVALID_FORMAT_GROUPNAME => '群名称格式不正确',
        self::ERRCODE_OVERLIMIT_GROUPNAME => '群名称长度超过限制',
        self::ERRCODE_GROUP_NOTEXIST => '不存在该群',
        self::ERRCODE_GROUP_ACCESS_ERROR => '非法访问群',
        self::ERRCODE_MISS_GROUPID => '缺少群ID',
        self::ERRCODE_INVALID_GROUP_USERID => '无效的userId',
        self::ERRCODE_INVALID_FORMAT_GROUPID => '无效的群Id',
    ];

    public static $OK = 0;

    public static $ValidateSignatureError = -40001;

    public static $ComputeSignatureError = -40002;

    public static $IllegalAesKey = -40003;

    public static $ValidateAppIdError = -40004;

    public static $EncryptAESError = -40005;

    public static $DecryptAESError = -40006;

    public static $IllegalBuffer = -40007;

    public static $IllegalHttpReq = -40008;
}
