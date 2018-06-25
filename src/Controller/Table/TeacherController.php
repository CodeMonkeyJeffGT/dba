<?php

namespace App\Controller\Table;

use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Table\BaseController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Teacher;

class TeacherController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        $teacherDb = $this->getDoctrine()->getRepository(Teacher::class);
        $this->setDefaults();
        $name = $request->query->get('name', '');
        $this->setTableData($teacherDb->getTableData($name));
        return $this->return();
    }

    public function edit(Request $request): JsonResponse
    {
        $teacherDb = $this->getDoctrine()->getRepository(Teacher::class);
        $id = $this->request('id', null);
        $name = $this->request('name', null);
        $phone = $this->request('phone', null);

        if (empty($name)) {
            return $this->error('教师姓名不能为空');
        }
        if (empty($phone)) {
            return $this->error('手机号不能为空');
        }
        if (empty($id)) {
            return $this->error('未指定id');
        }

        $rst = $teacherDb->editTeacher($id, $name, $phone);
        if ($rst == true) {
            return $this->search($request);
        } elseif ($rst['type'] = 'confirm') {
            return $this->confirm($rst['msg']);
        } else {
            return $this->error($rst['msg']);
        }
    }

    public function delete(Request $request): JsonResponse
    {
        $teacherDb = $this->getDoctrine()->getRepository(Teacher::class);
        $id = $this->request('id', null);
        
        if (empty($id)) {
            return $this->error('未指定id');
        }

        $rst = $teacherDb->deleteTeacher($id);
        if ($rst == true) {
            return $this->search($request);
        } elseif ($rst['type'] = 'confirm') {
            return $this->confirm($rst['msg']);
        } else {
            return $this->error($rst['msg']);
        }   
    }

    public function changePermit(Request $request): JsonResponse
    {
        $teacherDb = $this->getDoctrine()->getRepository(Teacher::class);
        $id = $this->request('id', null);
        
        if (empty($id)) {
            return $this->error('未指定id');
        }

        $rst = $teacherDb->permitTeacher($id);
        if ($rst == true) {
            return $this->search($request);
        } elseif ($rst['type'] = 'confirm') {
            return $this->confirm($rst['msg']);
        } else {
            return $this->error($rst['msg']);
        }   
    }

    public function new(Request $request): JsonResponse
    {
        $teacherDb = $this->getDoctrine()->getRepository(Teacher::class);
        $name = $this->request('name', null);
        $account = $this->request('account', null);
        $password = $this->request('password', null);
        $phone = $this->request('phone', null);

        if (empty($name)) {
            return $this->error('教师姓名不能为空');
        }
        if (empty($account)) {
            return $this->error('账号不能为空');
        }
        if (empty($password)) {
            return $this->error('密码不能为空');
        }
        $password = strtoupper(md5($password));
        if (empty($phone)) {
            return $this->error('手机号不能为空');
        }

        $rst = $teacherDb->newTeacher($name, $account, $password, $phone);
        if ($rst == true) {
            return $this->search($request);
        } elseif ($rst['type'] = 'confirm') {
            return $this->confirm($rst['msg']);
        } else {
            return $this->error($rst['msg']);
        }   
    }

    private function setDefaults()
    {
        $this->setTableSearch(array(
            array(
                'title' => '教师姓名',
                'key' => 'name',
                'type' => 'input',
            ),
        ));
        $this->setButtons(array(
            array(
                'name' => '搜索',
                'params' => array(
                    'name',
                ),
                'type' => 'uri',
                'uri' => '/table/teacher',
                'method' => 'get',
            ),
            array(
                'name' => '新建教师',
                'type' => 'modal',
                'colums' => array(
                    array(
                        'title' => '姓名',
                        'key' => 'name',
                        'type' => 'input',
                    ),
                    array(
                        'title' => '账号',
                        'key' => 'account',
                        'type' => 'input',
                    ),
                    array(
                        'title' => '密码',
                        'key' => 'password',
                        'type' => 'input',
                    ),
                    array(
                        'title' => '手机号',
                        'key' => 'phone',
                        'type' => 'input',
                    ),
                    array(
                        'title' => '管理员',
                        'key' => 'admin',
                        'type' => 'select',
                        'selections' => array(
                            '1' => '是',
                            '0' => '否',
                        ),
                    ),
                ),
                'uri' => '/table/teacher/new',
                'method' => 'post',
            ),
        ));
        $this->setColums(array(
            array(
                'title' => 'id',
                'dataIndex' => 'id',
                'key' => 'id',
            ),
            array(
                'title' => '姓名',
                'dataIndex' => 'name',
                'key' => 'name',
                'type' => 'input',
            ),
            array(
                'title' => '账号',
                'dataIndex' => 'account',
                'key' => 'account',
                'type' => 'input',
            ),
            array(
                'title' => '手机号',
                'dataIndex' => 'phone',
                'key' => 'phone',
                'type' => 'input',
            ),
            array(
                'title' => '管理员',
                'dataIndex' => 'admin',
                'key' => 'admin',
                'type' => 'select',
                'selections' => array(
                    '1' => '是',
                    '0' => '否',
                )
            )
        ));
        $this->setActions(array(
            array(
                'title' => '查看',
                'value' => 'watch',
            ),
            array(
                'title' => '编辑',
                'value' => 'edit',
                'uri' => '/api/teacher/edit',
                'params' => array(
                    'id',
                    'confirm',
                ),
                'method' => 'post',
            ),
            array(
                'title' => '删除',
                'value' => 'delete',
                'uri' => '/api/teacher/delete',
                'params' => array(
                    'id',
                ),
                'method' => 'post',
            ),
        ));
    }
}
