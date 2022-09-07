<?php

declare(strict_types=1);
/**
 * This file is part of laravel-youdu.
 *
 * @link     https://github.com/huangdijia/laravel-youdu
 * @document https://github.com/huangdijia/laravel-youdu/blob/3.x/README.md
 * @contact  huangdijia@gmail.com
 */
namespace Huangdijia\Youdu;

use Huangdijia\Youdu\Crypt\Prpcrypt;
use Huangdijia\Youdu\Exceptions\AccessTokenDoesNotExistException;
use Huangdijia\Youdu\Exceptions\ErrorCode;
use Huangdijia\Youdu\Exceptions\Exception;
use Huangdijia\Youdu\Facades\HttpClient;
use Huangdijia\Youdu\Messages\App\Message;
use Huangdijia\Youdu\Messages\App\PopWindow;
use Huangdijia\Youdu\Messages\App\SysMsg;
use Huangdijia\Youdu\Messages\App\Text;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class App
{
    protected Prpcrypt $crypter;

    protected Dept $dept;

    protected Group $group;

    protected User $user;

    protected Session $session;

    protected Media $media;

    public function __construct(protected string $api = '', protected int $buin = 0, protected string $appId = '', protected string $aesKey = '')
    {
        $this->crypter = new Prpcrypt($aesKey);
        $this->dept = new Dept($this);
        $this->group = new Group($this);
        $this->user = new User($this);
        $this->session = new Session($this);
        $this->media = new Media($this);
    }

    /**
     * 部门.
     */
    public function dept(): Dept
    {
        return $this->dept;
    }

    /**
     * 群.
     */
    public function group(): Group
    {
        return $this->group;
    }

    /**
     * 用户.
     */
    public function user(): User
    {
        return $this->user;
    }

    /**
     * 会话.
     */
    public function session(): Session
    {
        return $this->session;
    }

    /**
     * 会话.
     */
    public function media(): Media
    {
        return $this->media;
    }

    /**
     * 获取 buin.
     */
    public function getBuin(): int
    {
        return $this->buin;
    }

    /**
     * 获取 appId.
     */
    public function getAppId(): string
    {
        return $this->appId;
    }

    /**
     * 获取 aesKey.
     */
    public function getAesKey(): string
    {
        return $this->aesKey;
    }

    /**
     * 加密.
     */
    public function encryptMsg(string $unencrypted = ''): bool|string
    {
        [$errcode, $encrypted] = $this->crypter->encrypt($unencrypted, $this->appId);

        if ($errcode != 0) {
            throw new Exception($encrypted, $errcode);
        }

        return $encrypted;
    }

    /**
     * 解密.
     */
    public function decryptMsg(?string $encrypted): bool|string
    {
        if (strlen($this->aesKey) != 44) {
            throw new Exception('Illegal aesKey', ErrorCode::$IllegalAesKey);
        }

        [$errcode, $decrypted] = $this->crypter->decrypt($encrypted, $this->appId);

        if ($errcode != 0) {
            throw new Exception('Decrypt failed:' . $decrypted, (int) $errcode);
        }

        return $decrypted;
    }

    /**
     * Get access token.
     *
     * @return string
     */
    public function getAccessToken()
    {
        $appId = $this->appId;
        $buin = $this->buin;

        return Cache::remember('youdu:tokens:' . $appId, Carbon::now()->addHours(1), function () use ($buin, $appId) {
            $encrypted = $this->encryptMsg((string) time());
            $parameters = [
                'buin' => $buin,
                'appId' => $appId,
                'encrypt' => $encrypted,
            ];

            $url = $this->url('/cgi/gettoken', false);
            $resp = HttpClient::post($url, $parameters);
            $body = json_decode($resp['body'], true, 512, JSON_THROW_ON_ERROR);

            if ($body['errcode'] != 0) {
                throw new Exception($body['errmsg'], $body['errcode']);
            }

            $decrypted = $this->decryptMsg($body['encrypt']);
            $decoded = json_decode($decrypted, true, 512, JSON_THROW_ON_ERROR);

            return $decoded['accessToken'];
        });
    }

    /**
     * 组装 URL.
     *
     * @return string
     */
    public function url(string $uri = '', bool $withAccessToken = true)
    {
        $uri = '/' . ltrim($uri, '/');

        if ($withAccessToken) {
            $token = $this->getAccessToken();

            if (! $token) {
                throw new AccessTokenDoesNotExistException('AccessToken does not exist', 1);
            }

            $uri .= "?accessToken={$token}";
        }

        return $uri;
    }

    /**
     * 发送应用消息.
     *
     * @return bool
     */
    public function send(Message $message)
    {
        $encrypted = $this->encryptMsg($message->toJson());
        $parameters = [
            'buin' => $this->buin,
            'appId' => $this->appId,
            'encrypt' => $encrypted,
        ];

        $url = $this->url('/cgi/msg/send');
        $resp = HttpClient::post($url, $parameters);

        if ($resp['httpCode'] != 200) {
            throw new Exception('http request code ' . $resp['httpCode'], ErrorCode::$IllegalHttpReq);
        }

        $body = json_decode($resp['body'], true, 512, JSON_THROW_ON_ERROR);

        if ($body['errcode'] !== 0) {
            throw new Exception($body['errmsg'], $body['errcode']);
        }

        return true;
    }

    /**
     * 发送消息给用户.
     *
     * @param string $toUser 接收成员的帐号列表。多个接收者用竖线分隔，最多支持1000个
     * @return bool
     */
    public function sendToUser(string $toUser = '', Message|string $message = '')
    {
        if (is_string($message)) {
            $message = new Text($message);
        }

        $message->toUser($toUser);

        return $this->send($message);
    }

    /**
     * 发送消息至部门.
     *
     * @param string $toDept $toDept 接收部门id列表。多个接收者用竖线分隔，最多支持100个
     * @return bool
     */
    public function sendToDept(string $toDept = '', Message|string $message = '')
    {
        if (is_string($message)) {
            $message = new Text($message);
        }

        $message->toDept($toDept);

        return $this->send($message);
    }

    /**
     * 发送系统消息.
     *
     * @return bool
     */
    public function sendToAll(SysMsg|string $message, bool $onlineOnly = false)
    {
        if (is_string($message)) {
            $items = new Messages\App\Items\SysMsg();
            $items->addText($message);
            $message = new SysMsg($items);
        }

        if (! $message instanceof SysMsg) {
            throw new Exception('$message must instanceof' . SysMsg::class);
        }

        $message->toAll($onlineOnly);

        return $this->send($message);
    }

    /**
     * 设置通知数.
     *
     * @return bool
     */
    public function setNoticeCount(string $account = '', string $tip = '', int $msgCount = 0)
    {
        $parameters = [
            'app_id' => $this->appId,
            'msg_encrypt' => $this->encryptMsg(json_encode([
                'account' => $account,
                'tip' => $tip,
                'count' => $msgCount,
            ], JSON_THROW_ON_ERROR)),
        ];

        $resp = HttpClient::post($this->url('/cgi/set.ent.notice'), $parameters);

        if ($resp['httpCode'] != 200) {
            throw new Exception('http request code ' . $resp['httpCode'], ErrorCode::$IllegalHttpReq);
        }

        $body = json_decode($resp['body'], true, 512, JSON_THROW_ON_ERROR);

        if ($body['errcode'] !== 0) {
            throw new Exception($body['errmsg'], $body['errcode']);
        }

        return true;
    }

    /**
     * 应用弹窗.
     *
     * @return bool
     */
    public function popWindow(string $toUser = '', string $toDept = '', PopWindow $message = null)
    {
        if ($toUser) {
            $message->toUser($toUser);
        }

        if ($toDept) {
            $message->toDept($toDept);
        }

        $parameters = [
            'app_id' => $this->appId,
            'msg_encrypt' => $this->encryptMsg($message->toJson()),
        ];

        $resp = HttpClient::post($this->url('/cgi/popwindow'), $parameters);

        if ($resp['httpCode'] != 200) {
            throw new Exception('http request code ' . $resp['httpCode'], ErrorCode::$IllegalHttpReq);
        }

        $body = json_decode($resp['body'], true, 512, JSON_THROW_ON_ERROR);

        if ($body['errcode'] !== 0) {
            throw new Exception($body['errmsg'], $body['errcode']);
        }

        return true;
    }
}
