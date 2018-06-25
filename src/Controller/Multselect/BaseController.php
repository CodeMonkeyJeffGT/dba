<?php
namespace App\Controller\Multselect;

use App\Controller\BaseController as Controller;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends Controller
{
    private $multselect;

    protected function return($data = false)
    {
        if ($data === false) {
            $data = $this->multselect;
        }
        return parent::return($data);
    }

    protected function setMultselect($multselect)
    {
        $this->multselect = $multselect;
    }
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
            'name' => 'GET /api/multselect/{key}',
            'desc' => '获取多选框内容',
            'uri' => array(
                array(
                    'name' => '/api/multselect/teacher',
                    'desc' => '获取可选教师',
                    'uri' => $this->generateUrl('multselect-teacher'),
                ),
            ),
            'params' => array(
                array(
                    'name' => 'search',
                    'type' => 'string',
                    'default' => '空',
                    'must' => '',
                ),
            ),
            'mock' => array(
                'return' => $this->generateUrl('mock-multselect-return'),
                'confirm' => $this->generateUrl('mock-multselect-confirm'),
                'error' => $this->generateUrl('mock-multselect-error'),
            ),
            'return' => array(
                array(
                    'key' => 1,
                    'name' => '罗嗣卿',
                    'id' => 12,
                ),
                array(
                    'key' => 2,
                    'name' => '李莉',
                    'id' => 13,
                ),
                array(
                    'key' => 3,
                    'name' => '李彦宏',
                    'id' => 14,
                ),
                array(
                    'key' => 4,
                    'name' => '刘强东',
                    'id' => 15,
                ),
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
