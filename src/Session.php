<?php

declare(strict_types=1);
/**
 * This file is part of hyperf/helpers.
 *
 * @link     https://github.com/huangdijia/laravel-youdu
 * @document https://github.com/huangdijia/laravel-youdu/blob/3.x/README.md
 * @contact  huangdijia@gmail.com
 */
namespace Huangdijia\Youdu;

use Huangdijia\Youdu\Exceptions\AccessTokenDoesNotExistException;
use Huangdijia\Youdu\Exceptions\ErrorCode;
use Huangdijia\Youdu\Exceptions\Exception;
use Huangdijia\Youdu\Facades\HttpClient;
use Huangdijia\Youdu\Messages\Session\Message;
use Huangdijia\Youdu\Messages\Session\Text;

class Session
{
    public function __construct(protected App $app)
    {
    }

    /**
     * 创建会话.
     *
     * @param string $title 会话标题。最多允许64个字符
     * @param string $creator 会话创建者账号。最多允许64个字符
     * @param array $member 会话成员账号列表。包括创建者，多人会话的成员数必须在3人及以上
     * @param string $type 会话类型。仅支持多人会话(multi)
     */
    public function create(string $title, string $creator = '', array $member = [], string $type = 'multi'): array
    {
        $parameters = [
            'buin' => $this->app->getBuin(),
            'appId' => $this->app->getAppId(),
            'encrypt' => $this->app->encryptMsg(json_encode([
                'title' => $title,
                'creator' => $creator,
                'type' => $type,
                'member' => $member,
            ], JSON_THROW_ON_ERROR)),
        ];

        if (count($member) < 3) {
            throw new Exception('Members too less', 1);
        }

        $member = array_map(fn ($item) => (string) $item, $member);

        $resp = HttpClient::post($this->app->url('/cgi/session/create'), $parameters);

        if ($resp['httpCode'] != 200) {
            throw new Exception('http request code ' . $resp['httpCode'], ErrorCode::$IllegalHttpReq);
        }

        $body = json_decode($resp['body'], true, 512, JSON_THROW_ON_ERROR);

        if ($body['errcode'] !== 0) {
            throw new Exception($body['errmsg'], $body['errcode']);
        }

        $decrypted = $this->app->decryptMsg($body['encrypt']);
        return json_decode($decrypted, true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * 修改会话.
     *
     * @param string $sessionId 会话id
     * @param string $opUser 操作者账号
     * @param string $title 会话标题
     * @param array $addMember 新增会话成员账号列表
     * @param array $delMember 删除会话成员账号列表
     */
    public function update(string $sessionId, string $opUser = '', string $title = '', array $addMember = [], array $delMember = []): array
    {
        $addMember = array_map(fn ($item) => (string) $item, $addMember);
        $delMember = array_map(fn ($item) => (string) $item, $delMember);

        $parameters = [
            'buin' => $this->app->getBuin(),
            'appId' => $this->app->getAppId(),
            'encrypt' => $this->app->encryptMsg(json_encode([
                'sessionId' => $sessionId,
                'title' => $title,
                'opUser' => $opUser,
                'addMember' => $addMember,
                'delMember' => $delMember,
            ], JSON_THROW_ON_ERROR)),
        ];

        $resp = HttpClient::post($this->app->url('/cgi/session/update'), $parameters);

        if ($resp['httpCode'] != 200) {
            throw new Exception('http request code ' . $resp['httpCode'], ErrorCode::$IllegalHttpReq);
        }

        $body = json_decode($resp['body'], true, 512, JSON_THROW_ON_ERROR);

        if ($body['errcode'] !== 0) {
            throw new Exception($body['errmsg'], $body['errcode']);
        }

        $decrypted = $this->app->decryptMsg($body['encrypt']);

        return json_decode($decrypted, true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * 获取会话.
     */
    public function info(string $sessionId): array
    {
        $resp = HttpClient::get($this->app->url('/cgi/session/get'), ['sessionId' => $sessionId]);
        $decoded = json_decode($resp['body'], true, 512, JSON_THROW_ON_ERROR);

        if ($decoded['errcode'] !== 0) {
            throw new Exception($decoded['errmsg'], 1);
        }

        $decrypted = $this->app->decryptMsg($decoded['encrypt'] ?? '');

        return json_decode($decrypted, true, 512, JSON_THROW_ON_ERROR) ?? [];
    }

    /**
     * 发送会话消息.
     *
     * @throws Exception
     * @throws AccessTokenDoesNotExistException
     */
    public function send(Message $message): bool
    {
        $parameters = [
            'buin' => $this->app->getBuin(),
            'appId' => $this->app->getAppId(),
            'encrypt' => $this->app->encryptMsg($message->toJson()),
        ];

        $resp = HttpClient::post($this->app->url('/cgi/session/send'), $parameters);

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
     * 发送个人会话消息.
     *
     * @param string $message
     */
    public function sendToUser(string $sender, string $receiver, $message = ''): bool
    {
        if (is_string($message)) {
            $message = new Text($message);
        }

        $message->sender($sender);
        $message->receiver($receiver);

        return $this->send($message);
    }

    /**
     * 发送多人会话消息.
     *
     * @param string $message
     */
    public function sendToSession(string $sender, string $sessionId, $message = ''): bool
    {
        if (is_string($message)) {
            $message = new Text($message);
        }

        $message->sender($sender);
        $message->session($sessionId);

        return $this->send($message);
    }
}
