<?php

namespace App\Controller\Table;

use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Table\BaseController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Teacher;
use App\Entity\Task;
use App\Entity\Answer;

class TaskController extends Controller
{
    public function message(Request $request, SessionInterface $session)
    {
        return $this->list($request, $session, 'message');
    }

    public function file(Request $request, SessionInterface $session)
    {
        return $this->list($request, $session, 'file');
    }

    private function list(Request $request, SessionInterface $session, $type)
    {
        $taskDb = $this->getDoctrine()->getRepository(Task::class);
        $teacherDb = $this->getDoctrine()->getRepository(Teacher::class);
        $this->setDefaults($type);
        $status = $request->query->get('status', '');
        $title = $request->query->get('title', '');
        $description = $request->query->get('description', '');
        if ($status != 'expected') {
            $this->setActions(array(
                array(
                    'title' => '查看',
                    'value' => 'watch',
                ),
            ));
        }
        if ( ! $teacherDb->checkPermit($session->get('id'))) {
            $this->setButtons(array(
                array(
                    'name' => '搜索',
                    'params' => array(
                        'title',
                        'description',
                    ),
                    'type' => 'uri',
                    'uri' => '/api/table/task/' . $type,
                    'method' => 'get',
                ),
            ));
        } else {
            $this->setButtons(array(
                array(
                    'name' => '搜索',
                    'params' => array(
                        'title',
                        'description',
                    ),
                    'type' => 'uri',
                    'uri' => '/api/table/task/' . $type,
                    'method' => 'get',
                ),
                array(
                    'name' => '新建任务',
                    'type' => 'modal',
                    'colums' => array(
                        array(
                            'title' => '标题',
                            'key' => 'title',
                            'type' => 'input',
                        ),
                        array(
                            'title' => '类型',
                            'key' => 'tastType',
                            'type' => 'select',
                            'selections' => array(
                                '0' => '回复类',
                                '1' => '文件类',
                            ),
                        ),
                        array(
                            'title' => '描述',
                            'key' => 'description',
                            'type' => 'input',
                        ),
                        array(
                            'title' => '截止时间',
                            'key' => 'deadline',
                            'type' => 'time',
                        ),
                    ),
                    'uri' => '/api/table/task/new',
                    'method' => 'post',
                ),
            ));
        }
        $this->setTableData($taskDb->getTableData($title, $description, $teacher, $type, $status));
        return $this->return();
    }

    private function setDefaults($type)
    {
        $this->setTableSearch(array(
            array(
                'title' => '标题',
                'key' => 'title',
                'type' => 'input',
            ),
            array(
                'title' => '详情',
                'key' => 'description',
                'type' => 'input',
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
                'title' => '回复',
                'value' => 'answer',
                'uri' => '/api/table/task/' . $type . '/answer',
                'method' => 'post',
            ),
        ));
    }
}
