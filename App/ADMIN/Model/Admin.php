<?php
namespace App\ADMIN\Model;

use Core\Lib;
use Core\Base\Model;

class Admin extends Model
{

    public function getList($pageArr = null, $condition = null)
    {
        $data = $this->page($pageArr, 'admin', '*', $condition);
        // 查询所属角色
        foreach ($data['list'] as $k => $v) {
            
            $data['list'][$k]['name'] = [];
            $_data = $this->select('role', [
                'name'
            ], [
                'id' => explode(',', $v['role_id'])
            ]);
            foreach ($_data as $_v) {
                array_push($data['list'][$k]['name'], $_v['name']);
            }
            $data['list'][$k]['name'] = implode(',', $data['list'][$k]['name']);
        }
        return $data;
    }

    public function getRoleList()
    {
        return $this->select('role', '*', [
            'ORDER' => [
                'id' => 'ASC'
            ]
        ]);
    }

    public function add()
    {
        // 查询帐号是否存在，如果存在则提示
        if ($this->has('admin', [
            'account' => Lib::post('account')
        ])) {
            return - 1;
        } else {
            return $this->insert('admin', [
                'role_id' => Lib::post('role_id', '', 'array'),
                'account' => Lib::post('account'),
                'password' => Lib::compilePassword(Lib::post('password')),
                'real_name' => Lib::post('real_name'),
                'tel' => Lib::post('tel'),
                'status' => Lib::post('status')
            ]);
        }
    }

    public function del($id = 0)
    {
        return $this->delete('admin', [
            'id' => $id
        ]);
    }

    public function edit($id = 0, $a = null)
    {
        if ($a == 'get') {
            return $this->get('admin', '*', [
                'id' => $id
            ]);
        } else {
            if (Lib::post('password')) {
                $password = Lib::compilePassword(Lib::post('password'));
            } else {
                $password = $this->edit($id, 'get')['password'];
            }
            return $this->update('admin', [
                'role_id' => Lib::post('role_id', '', 'array'),
                'password' => $password,
                'real_name' => Lib::post('real_name'),
                'tel' => Lib::post('tel'),
                'status' => Lib::post('status')
            ], [
                'id' => $id
            ]);
        }
    }

    public function disable($id = 0)
    {
        return $this->update('admin', [
            'status' => - 1
        ], [
            'id' => $id
        ]);
    }

    public function enable($id = 0)
    {
        return $this->update('admin', [
            'status' => 0
        ], [
            'id' => $id
        ]);
    }
}