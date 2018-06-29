<?php

namespace App\Controller\Table;

use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Table\BaseController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Teacher;

class TeacherController extends Controller
{
    public function login(Request $request, SessionInterface $session): JsonResponse
    {
        $account = $this->request('account', null);
        $password = $this->request('password', null);
        $password = strtoupper(md5($password));
        $teacherDb = $this->getDoctrine()->getRepository(Teacher::class);
        if (($id = $teacherDb->login($account, $password)) !== false) {
            $session->set('id', $id);
            return $this->search($request, $session);
        } else {
            return $this->error('登录失败');
        }
    }

    public function viewSelf(Request $request, SessionInterface $session): JsonResponse
    {
        $teacherDb = $this->getDoctrine()->getRepository(Teacher::class);
        $id = $session->get('id');
        $data = $teacherDb->viewSelf($id);
        return $this->return($data);
    }

    public function editSelf(Request $request, SessionInterface $session): JsonResponse
    {
        $teacherDb = $this->getDoctrine()->getRepository(Teacher::class);
        $id = $session->get('id');
        $phone = $this->request('phone', null);
        if (empty($phone)) {
            return $this->error('手机号不能为空');
        }
        $rst = $teacherDb->editSelf($id, $phone);
        if ($rst) {
            return $this->return();
        } else {
            return $this->error($rst['msg']);
        }
    }

    public function search(Request $request, SessionInterface $session): JsonResponse
    {
        $teacherDb = $this->getDoctrine()->getRepository(Teacher::class);
        $this->setDefaults();
        if ( ! $teacherDb->checkPermit($session->get('id'))) {
            $this->setActions(array(
                array(
                    'title' => '查看',
                    'value' => 'watch',
                ),
            ));
            $this->setButtons(array(
                array(
                    'name' => '搜索',
                    'params' => array(
                        'name',
                    ),
                    'type' => 'uri',
                    'uri' => '/api/table/teacher',
                    'method' => 'get',
                ),
            ));
        }
        $name = $request->query->get('name', '');
        $this->setTableData($teacherDb->getTableData($name));
        return $this->return();
    }

    public function edit(Request $request, SessionInterface $session): JsonResponse
    {
        $teacherDb = $this->getDoctrine()->getRepository(Teacher::class);
        $id = $this->request('id', null);
        $name = $this->request('name', null);
        $phone = $this->request('phone', null);
        $admin = $this->request('admin', null);

        if (empty($name)) {
            return $this->error('教师姓名不能为空');
        }
        if (empty($phone)) {
            return $this->error('手机号不能为空');
        }
        if (empty($id)) {
            return $this->error('未指定id');
        }
        if (is_null($admin)) {
            return $this->error('未指定权限');
        }

        $rst = $teacherDb->editTeacher($id, $name, $phone, $admin);
        if ($rst === true) {
            return $this->search($request, $session);
        } elseif ($rst['type'] = 'confirm') {
            return $this->confirm($rst['msg']);
        } else {
            return $this->error($rst['msg']);
        }
    }

    public function delete(Request $request, SessionInterface $session): JsonResponse
    {
        $teacherDb = $this->getDoctrine()->getRepository(Teacher::class);
        $id = $this->request('id', null);
        
        if (empty($id)) {
            return $this->error('未指定id');
        }

        $rst = $teacherDb->deleteTeacher($id);
        if ($rst === true) {
            return $this->search($request, $session);
        } elseif ($rst['type'] = 'confirm') {
            return $this->confirm($rst['msg']);
        } else {
            return $this->error($rst['msg']);
        }   
    }

    public function new(Request $request, SessionInterface $session): JsonResponse
    {
        $teacherDb = $this->getDoctrine()->getRepository(Teacher::class);
        $name = $this->request('name', null);
        $account = $this->request('account', null);
        $password = $this->request('password', null);
        $phone = $this->request('phone', null);
        $admin = $this->request('admin', null);

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
        if (is_null($admin)) {
            return $this->error('未指定权限');
        }

        $rst = $teacherDb->newTeacher($name, $account, $password, $phone, $admin);
        if ($rst === true) {
            return $this->search($request, $session);
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
                'uri' => '/api/table/teacher',
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
                'uri' => '/api/table/teacher/new',
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
                'uri' => '/api/table/teacher/edit',
                'method' => 'post',
            ),
            array(
                'title' => '删除',
                'value' => 'delete',
                'uri' => '/api/table/teacher/delete',
                'method' => 'post',
            ),
        ));
    }
}
