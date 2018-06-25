<?php
namespace App\Controller\Menu;

use App\Controller\BaseController as Controller;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends Controller
{
    public function index()
    {
        $config = $this->getConfig();
        $config['return'] = $this->toJs($config['return']);
        $config['confirm'] = $this->toJs($config['confirm']);
        $config['error'] = $this->toJs($config['error']);
        return $this->render('base/api.html', $config);
    }
    
    protected function getConfig()
    {
        return array(
            'name' => 'GET /menu',
            'desc' => '获取菜单（此接口无下级接口）',
            'uri' => array(
                array(
                    'name' => '/menu',
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
                            ),
                            array(
                                'key' => 'exam-unassigned',
                                'title' => '未分配监考',
                            ),
                            array(
                                'key' => 'exam-assigned',
                                'title' => '已分配监考',
                            ),
                            array(
                                'key' => 'exam-completed',
                                'title' => '已完成监考',
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
