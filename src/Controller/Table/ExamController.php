<?php

namespace App\Controller\Table;

use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Table\BaseController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Exam;

class ExamController extends Controller
{

    /**
     * 获取考试信息
     * @param string $name 监考课程名称
     * @param string $address 考试地点
     * @param string $teacher 监考教师
     */
    public function search(Request $request): JsonResponse
    {
        $examDb = $this->getDoctrine()->getRepository(Exam::class);
        $this->setDefaults();
        $name    = $request->query->get('name', '');
        $address = $request->query->get('address', '');
        $teacher = $request->query->get('teacher', '');
        $status = $request->query->get('status', 'all');
        $this->setTableData($examDb->getTableData($name, $address, $teacher, $status));
        return $this->return();
    }

    public function edit(Request $request): JsonResponse
    {
        $examDb = $this->getDoctrine()->getRepository(Exam::class);
        $id = $request->request->get('id', null);
        $name = $request->request->get('name', null);
        $start = $request->request->get('start', null);
        $end = $request->request->get('end', null);
        $address = $request->request->get('address', null);
        $teacher = $request->request->get('teacher', array());

        if (empty($name)) {
            $this->error('课程名称不能为空');
        }
        if (strtotime($start) < time()) {
            $this->error('开始时间不能晚于当前时间');
        }
        if (strtotime($start) < strtotime($end)) {
            $this->error('结束时间不能晚于开始时间');
        }
        if (empty($address)) {
            $this->error('考试地点不能为空');
        }
        if (empty($id)) {
            $this->error('未指定id');
        }

        if ( ! $examDb->editExam($id, $name, $start, $end, $address, $teacher)) {
            $this->error('监考记录不存在');
        }
        return $this->search($request);
    }

    public function delete(Request $request): JsonResponse
    {
        $examDb = $this->getDoctrine()->getRepository(Exam::class);
        $id = $request->request->get('id', null);
        if (empty($id)) {
            $this->error('未指定id');
        }
        if ( ! $examDb->deleteExam($id)) {
            $this->error('监考记录不存在');
        }
        return $this->search($request);
    }

    public function remind(Request $request): JsonResponse
    {
        $examDb = $this->getDoctrine()->getRepository(Exam::class);
        $id = $request->request->get('id', null);
        if (empty($id)) {
            $this->error('未指定id');
        }
        if ( ! $examDb->remindExam($id)) {
            $this->error('监考记录不存在');
        }
        return $this->search($request);
    }

    private function setDefaults()
    {
        $this->setTableSearch(array(
            array(
                'title' => '课程名',
                'key' => 'name',
                'type' => 'input',
            ),
            array(
                'title' => '地点',
                'key' => 'address',
                'type' => 'input',
            ),
            array(
                'title' => '监考教师',
                'key' => 'teacher',
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
                'name' => '新建监考',
                'type' => 'modal',
                'colums' => array(
                    array(
                        'title' => '课程名',
                        'key' => 'name',
                        'type' => 'input',
                    ),
                    array(
                        'title' => '地点',
                        'key' => 'address',
                        'type' => 'input',
                    ),
                    array(
                        'title' => '开始时间',
                        'key' => 'start',
                        'type' => 'time',
                    ),
                    array(
                        'title' => '结束时间',
                        'key' => 'end',
                        'type' => 'time',
                    ),
                    array(
                        'title' => '监考教师',
                        'key' => 'teacher',
                        'type' => 'multselect',
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
                'title' => '课程名',
                'dataIndex' => 'name',
                'key' => 'name',
                'type' => 'input',
            ),
            array(
                'title' => '开始时间',
                'dataIndex' => 'start',
                'key' => 'start',
                'type' => 'time',
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
