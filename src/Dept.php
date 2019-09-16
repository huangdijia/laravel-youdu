<?php

namespace Huangdijia\Youdu;

use Huangdijia\Youdu\Facades\HttpClient;

class Dept
{
    protected $youdu;

    public function __construct(Youdu $youdu)
    {
        $this->youdu = $youdu;
    }

    /**
     * 获取部门列表
     *
     * @param integer $parentDeptId
     * @return array|bool
     */
    public function lists(int $parentDeptId = 0)
    {
        $resp    = HttpClient::get($this->youdu->url('/cgi/dept/list'), ['id' => $parentDeptId]);
        $decoded = json_decode($resp['body'], true);

        if ($decoded['errcode'] !== 0) {
            throw new \Exception($decoded['errmsg'], 1);
        }

        $decrypted = $this->youdu->decryptMsg($decoded['encrypt'] ?? '');

        return json_decode($decrypted, true)['deptList'] ?? [];
    }

    /**
     * 创建部门
     *
     * @param integer $deptId 部门id，整型。必须大于0
     * @param string $name 部门名称。不能超过32个字符（包括汉字和英文字母）
     * @param integer $parentId 父部门id。根部门id为0
     * @param integer $sortId 整型。在父部门中的排序值。值越大排序越靠前。填0自动生成。同级部门不允许重复（推荐全局唯一）
     * @param string $alias 字符串。部门id的别名（通常存放以字符串表示的部门id）。唯一不为空，相同会覆盖旧数据。
     * @return bool
     */
    public function create(int $deptId, string $name, int $parentId = 0, $sortId = 0, string $alias = '')
    {
        $url = $this->youdu->url('/cgi/dept/create');
    }

    /**
     * 更新部门
     *
     * @param integer $deptId 部门id，整型。必须大于0
     * @param string $name 部门名称。不能超过32个字符（包括汉字和英文字母）
     * @param integer $parentId 父部门id。根部门id为0
     * @param integer $sortId 整型。在父部门中的排序值。值越大排序越靠前。填0自动生成。同级部门不允许重复（推荐全局唯一）
     * @param string $alias 字符串。部门id的别名（通常存放以字符串表示的部门id）。唯一不为空，相同会覆盖旧数据。
     * @return bool
     */
    public function update(int $deptId, string $name, int $parentId = 0, $sortId = 0, string $alias = '')
    {
        $url = $this->youdu->url('/cgi/dept/update');
    }

    /**
     * 更新部门
     *
     * @param integer $deptId 部门id，整型。必须大于0
     * @return bool
     */
    public function delete(int $deptId, string $name, int $parentId = 0, $sortId = 0, string $alias = '')
    {
        $url = $this->youdu->url('/cgi/dept/delete');
    }

    /**
     * 获取部门ID
     *
     * @param string $alias 部门alias。携带时查询该alias对应的部门id。不带alias参数时查询全部映射关系。
     * @return void
     */
    public function getId(string $alias = '')
    {
        $resp    = HttpClient::get($this->youdu->url('/cgi/dept/list'), ['alias' => $alias]);
        $decoded = json_decode($resp['body'], true);

        if ($decoded['errcode'] !== 0) {
            throw new \Exception($decoded['errmsg'], 1);
        }

        $decrypted = $this->youdu->decryptMsg($decoded['encrypt'] ?? '');

        return json_decode($decrypted, true)['aliasList'] ?? [];
    }
}
