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
        $name    = $request->query->get('name', '');
        $this->setTableData($teacherDb->getTableData($name));
        return $this->return();
    }

    public function edit(): JsonResponse
    {
        $teacherDb = $this->getDoctrine()->getRepository(Teacher::class);
        return $this->search($request);        
    }

    public function delete(): JsonResponse
    {
        $teacherDb = $this->getDoctrine()->getRepository(Teacher::class);
        return $this->search($request);        
    }

    public function changePermit(): JsonResponse
    {
        $teacherDb = $this->getDoctrine()->getRepository(Teacher::class);
        return $this->search($request);        
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
                    'address',
                    'time',
                    'teacher',
                ),
                'type' => 'uri',
                'uri' => '/tableSearch/exam',
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
                        'key' => 'address',
                        'type' => 'input',
                    ),
                    array(
                        'title' => '密码',
                        'key' => 'start',
                        'type' => 'input',
                    ),
                    array(
                        'title' => '手机号',
                        'key' => 'end',
                        'type' => 'input',
                    ),
                ),
                'uri' => '/tableSearch/exam/new',
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
                'title' => '结束时间',
                'dataIndex' => 'end',
                'key' => 'end',
                'type' => 'time',
            ),
            array(
                'title' => '地点',
                'dataIndex' => 'address',
                'key' => 'address',
                'type' => 'input',
            ),
            array(
                'title' => '监考教师',
                'dataIndex' => 'teacher',
                'key' => 'teacher',
                'type' => 'multselect',
            ),
        ));
        $this->setActions(array(
            array(
                'title' => '查看',
                'value' => 'watch',
            ),
            array(
                'title' => '编辑',
                'value' => 'edit',
                'uri' => '/tablesearch/edit',
                'params' => array(
                    'id',
                    'confirm',
                ),
                'method' => 'post',
            ),
            array(
                'title' => '删除',
                'value' => 'uri',
                'uri' => '/tablesearch/delete',
                'params' => array(
                    'id',
                ),
                'method' => 'post',
            ),
            array(
                'title' => '发送短信提醒',
                'value' => 'uri',
                'uri' => '/tablesearch/remind',
                'params' => array(
                    'id',
                ),
                'method' => 'post',
            ),
        ));
    }
}
