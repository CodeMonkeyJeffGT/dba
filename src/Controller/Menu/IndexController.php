<?php

namespace App\Controller\Menu;

use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Menu\BaseController as Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class IndexController extends Controller
{
    public function list(): JsonResponse
    {
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
                'key' => 'teacher',
                'title' => '教师信息',
                'uri' => $this->generateUrl('table-teacher'),
            ),
        ));
        return $this->return();
    }
}
