<?php
namespace App\Controller\Menu;

use App\Controller\BaseController as Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class BaseController extends Controller
{
    private $menu;

    protected function return($data = false): JsonResponse
    {
        if (false === $data) {
            $data = $this->menu;
        }
        return parent::return($data);
    }

    protected function setMenu($menu)
    {
        $this->menu = $menu;
    }

    public function index(): Response
    {
        $config = $this->getConfig();
        $config['return'] = $this->toJs($config['return']);
        $config['confirm'] = $this->toJs($config['confirm']);
        $config['error'] = $this->toJs($config['error']);
        return $this->render('base/api.html', $config);
    }
    
    protected function getConfig(): array
    {
        return array(
            'name' => 'GET /api/menu',
            'desc' => '获取菜单（此接口无下级接口）',
            'uri' => array(
                array(
                    'name' => '/api/menu',
                    'desc' => '获取菜单',
                    'uri' => $this->generateUrl('menu'),
                ),
            ),
            'params' => array(
            ),
            'mock' => array(
                'return' => $this->generateUrl('mock-menu-return'),
                'confirm' => $this->generateUrl('mock-menu-confirm'),
                'error' => $this->generateUrl('mock-menu-error'),
            ),
            'return' => array(
                'data' => array(
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
                ),
                'errno' => 0,
                'errmsg' => 'OK',
            ),
            'confirm' => array(
                'data' => 'null',
                'errno' => '2',
                'errmsg' => ' 此情况一般不存在',
            ),
            'error' => array(
                'data' => 'null',
                'errno' => '1',
                'errmsg' => ' 此情况一般不存在',
            ),
        );
    }
}
