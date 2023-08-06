<?php

declare(strict_types=1);
/**
 * This file is part of huangdijia/laravel-youdu.
 *
 * @link     https://github.com/huangdijia/laravel-youdu
 * @document https://github.com/huangdijia/laravel-youdu/blob/3.x/README.md
 * @contact  huangdijia@gmail.com
 */
namespace Huangdijia\Youdu\Crypt;

use Huangdijia\Youdu\Exceptions\ErrorCode;
use Throwable;

/**
 * Prpcrypt class.
 *
 * 提供接收和推送给公众平台消息的加解密接口.
 */
class Prpcrypt
{
    protected string $key;

    protected PKCS7Encoder $encoder;

    public function __construct($key = '')
    {
        $this->key = base64_decode($key);
        $this->encoder = new PKCS7Encoder();
    }

    /**
     * 对明文进行加密.
     *
     * @param string $text 需要加密的明文
     */
    public function encrypt(string $text = '', string $appId = ''): array
    {
        try {
            // 获得16位随机字符串，填充到明文之前
            $random = $this->getRandomStr();
            $text = $random . pack('N', strlen($text)) . $text . $appId;
            $iv = substr($this->key, 0, 16);
            $text = $this->encoder->encode($text);
            $encrypted = openssl_encrypt($text, 'AES-256-CBC', $this->key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $iv);

            // 使用BASE64对加密后的字符串进行编码
            return [ErrorCode::$OK, base64_encode($encrypted)];
        } catch (Throwable $e) {
            return [ErrorCode::$EncryptAESError, 'Encrypt AES Error:' . $e->getMessage()];
        }
    }

    /**
     * 对密文进行解密.
     *
     * @param string $encrypted 需要解密的密文
     * @param mixed $appId
     * @return array<int,string>
     */
    public function decrypt(string $encrypted, $appId): array
    {
        try {
            // 使用BASE64对需要解密的字符串进行解码
            $encrypted = base64_decode($encrypted);
            $iv = substr($this->key, 0, 16);
            $decrypted = openssl_decrypt($encrypted, 'AES-256-CBC', $this->key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $iv);
        } catch (Throwable $e) {
            return [ErrorCode::$DecryptAESError, 'Decrypt AES Error:' . $e->getMessage()];
        }

        try {
            // 去除补位字符
            $result = $this->encoder->decode($decrypted);

            // 去除16位随机字符串,网络字节序和AppId
            if (strlen($result) < 16) {
                return '';
            }

            $content = substr($result, 16, strlen($result));
            $lenList = unpack('N', substr($content, 0, 4));
            $jsonLen = $lenList[1];
            $jsonContent = substr($content, 4, $jsonLen);
            $fromAppId = substr($content, $jsonLen + 4);
        } catch (Throwable) {
            return [ErrorCode::$IllegalBuffer, 'Illegal Buffer'];
        }

        if ($fromAppId != 'sysOrgAssistant' && $fromAppId != $appId) {
            return [ErrorCode::$ValidateAppIdError, 'Validate AppId Error'];
        }

        return [0, $jsonContent];
    }

    /**
     * 随机生成16位字符串.
     */
    public function getRandomStr(): string
    {
        $str = '';
        $strPol = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
        $max = strlen($strPol) - 1;

        for ($i = 0; $i < 16; ++$i) {
            $str .= $strPol[random_int(0, $max)];
        }

        return $str;
    }
}
