<?php

namespace App\Controller\Menu;

use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Menu\BaseController as Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Teacher;

class IndexController extends Controller
{
    public function list(): JsonResponse
    {
        $teacherDb = $this->getDoctrine()->getRepository(Teacher::class);
        if ( ! $teacherDb->checkPermit($session->get('id'))) {
            $this->setMenu(array(
                array(
                    'title' => '监考管理',
                    'children' => array(
                        array(
                            'key' => 'exam',
                            'title' => '全部监考',
                            'uri' => $this->generateUrl('table-exam'),
                        ),
                        array(
                            'key' => 'exam-unassigned',
                            'title' => '未分配监考',
                            'uri' => $this->generateUrl('table-exam', array(
                                'status' => 'unassigned',
                            )),
                        ),
                        array(
                            'key' => 'exam-assigned',
                            'title' => '已分配监考',
                            'uri' => $this->generateUrl('table-exam', array(
                                'status' => 'assigned',
                            )),
                        ),
                        array(
                            'key' => 'exam-completed',
                            'title' => '已完成监考',
                            'uri' => $this->generateUrl('table-exam', array(
                                'status' => 'completed',
                            )),
                        ),
                    ),
                ),
                array(
                    'title' => '人员管理',
                    'children' => array(
                        array(
                            'title' => '教师信息',
                            'key' => 'teacher',
                            'uri' => $this->generateUrl('table-teacher'),
                        ),
                    ),
                ),
                array(
                    'title' => '消息任务',
                    'children' => array(
                        array(
                            'title' => '未完成任务',
                            'key' => 'task-message-expected',
                            'uri' => $this->generateUrl('table-task-message', array(
                                'status' => 'expected',
                            )),
                        ),
                        array(
                            'title' => '已完成任务',
                            'key' => 'task-message-done',
                            'uri' => $this->generateUrl('table-task-message', array(
                                'status' => 'done',
                            )),
                        ),
                        array(
                            'title' => '超时完成任务',
                            'key' => 'task-message-out',
                            'uri' => $this->generateUrl('table-task-message', array(
                                'status' => 'out',
                            )),
                        ),
                    ),
                ),
                array(
                    'title' => '文件任务',
                    'children' => array(
                        array(
                            'title' => '未完成任务',
                            'key' => 'task-file-expected',
                            'uri' => $this->generateUrl('table-task-file', array(
                                'status' => 'expected',
                            )),
                        ),
                        array(
                            'title' => '已完成任务',
                            'key' => 'task-file-done',
                            'uri' => $this->generateUrl('table-task-file', array(
                                'status' => 'done',
                            )),
                        ),
                        array(
                            'title' => '超时完成任务',
                            'key' => 'task-file-out',
                            'uri' => $this->generateUrl('table-task-file', array(
                                'status' => 'out',
                            )),
                        ),
                    ),
                ),
            ));
        } else {
            $this->setMenu(array(
                array(
                    'title' => '监考管理',
                    'children' => array(
                        array(
                            'key' => 'exam',
                            'title' => '全部监考',
                            'uri' => $this->generateUrl('table-exam'),
                        ),
                        array(
                            'key' => 'exam-unassigned',
                            'title' => '未分配监考',
                            'uri' => $this->generateUrl('table-exam', array(
                                'status' => 'unassigned',
                            )),
                        ),
                        array(
                            'key' => 'exam-assigned',
                            'title' => '已分配监考',
                            'uri' => $this->generateUrl('table-exam', array(
                                'status' => 'assigned',
                            )),
                        ),
                        array(
                            'key' => 'exam-completed',
                            'title' => '已完成监考',
                            'uri' => $this->generateUrl('table-exam', array(
                                'status' => 'completed',
                            )),
                        ),
                    ),
                ),
                array(
                    'title' => '人员管理',
                    'children' => array(
                        array(
                            'title' => '教师信息',
                            'key' => 'teacher',
                            'uri' => $this->generateUrl('table-teacher'),
                        ),
                    ),
                ),
                array(
                    'title' => '消息任务',
                    'children' => array(
                        array(
                            'title' => '任务结果',
                            'key' => 'task-message-result',
                            'uri' => $this->generateUrl('table-task-message', array(
                                'status' => 'result',
                            )),
                        ),
                        array(
                            'title' => '未完成任务',
                            'key' => 'task-message-expected',
                            'uri' => $this->generateUrl('table-task-message', array(
                                'status' => 'expected',
                            )),
                        ),
                        array(
                            'title' => '已完成任务',
                            'key' => 'task-message-done',
                            'uri' => $this->generateUrl('table-task-message', array(
                                'status' => 'done',
                            )),
                        ),
                        array(
                            'title' => '超时完成任务',
                            'key' => 'task-message-out',
                            'uri' => $this->generateUrl('table-task-message', array(
                                'status' => 'out',
                            )),
                        ),
                    ),
                ),
                array(
                    'title' => '文件任务',
                    'children' => array(
                        array(
                            'title' => '任务结果',
                            'key' => 'task-file-result',
                            'uri' => $this->generateUrl('table-task-file', array(
                                'status' => 'result',
                            )),
                        ),
                        array(
                            'title' => '未完成任务',
                            'key' => 'task-file-expected',
                            'uri' => $this->generateUrl('table-task-file', array(
                                'status' => 'expected',
                            )),
                        ),
                        array(
                            'title' => '已完成任务',
                            'key' => 'task-file-done',
                            'uri' => $this->generateUrl('table-task-file', array(
                                'status' => 'done',
                            )),
                        ),
                        array(
                            'title' => '超时完成任务',
                            'key' => 'task-file-out',
                            'uri' => $this->generateUrl('table-task-file', array(
                                'status' => 'out',
                            )),
                        ),
                    ),
                ),
            ));
        }
        return $this->return();
    }
}
