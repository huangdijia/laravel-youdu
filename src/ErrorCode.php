<?php

namespace Huangdijia\Youdu;

class ErrorCode
{
    public static $OK                     = 0;
    public static $ValidateSignatureError = -40001;
    public static $ComputeSignatureError  = -40002;
    public static $IllegalAesKey          = -40003;
    public static $ValidateAppIdError     = -40004;
    public static $EncryptAESError        = -40005;
    public static $DecryptAESError        = -40006;
    public static $IllegalBuffer          = -40007;
    public static $IllegalHttpReq         = -40008;
}
