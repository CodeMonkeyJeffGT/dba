<?php

namespace App\Controller\Table;

use Symfony\Component\Routing\Annotation\Route;
use App\Controller\BaseController as Controller;

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
            'name' => 'GET /table/{key}',
            'desc' => '获取多选框内容',
            'uri' => array(
                array(
                    'name' => '/table/exam',
                    'desc' => '获取考试信息',
                    'uri' => $this->generateUrl('table-exam'),
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
                'return' => $this->generateUrl('mock-table-return'),
                'confirm' => $this->generateUrl('mock-table-confirm'),
                'error' => $this->generateUrl('mock-table-error'),
            ),
            'return' => array(
                'data' => array(
                    'tableSearch' => array(
                        array(
                            'title' => '课程名',
                            'key' => 'subject',
                            'type' => 'input',
                        ),
                        array(
                            'title' => '地点',
                            'key' => 'address',
                            'type' => 'input',
                        ),
                        array(
                            'title' => '时间',
                            'key' => 'time',
                            'type' => 'time',
                        ),
                        array(
                            'title' => '监考教师',
                            'key' => 'teacher',
                            'type' => 'input',
                        ),
                    ),
                    'buttons' => array(
                        array(
                            'name' => '搜索',
                            'params' => array(
                                'subject',
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
                                    'key' => 'subject',
                                    'type' => 'input',
                                ),
                                array(
                                    'title' => '地点',
                                    'key' => 'address',
                                    'type' => 'input',
                                ),
                                array(
                                    'title' => '时间',
                                    'key' => 'time',
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
                        )
                    ),
                    'colums' => array(
                        array(
                            'title' => 'id',
                            'dataIndex' => 'id',
                            'key' => 'id',
                        ),
                        array(
                            'title' => '课程名',
                            'dataIndex' => 'subject',
                            'key' => 'subject',
                        ),
                        array(
                            'title' => '时间',
                            'dataIndex' => 'time',
                            'key' => 'time',
                        ),
                        array(
                            'title' => '地点',
                            'dataIndex' => 'address',
                            'key' => 'address',
                        ),
                        array(
                            'title' => '监考教师',
                            'dataIndex' => 'teacher',
                            'key' => 'teacher',
                        ),
                    ),
                    'tableData' => array(
                        array(
                            'key' => 1,
                            'id' => array(23),
                            'subject' => array('数学'),
                            'time' => array('2018/05.12 13:30-14:30'),
                            'address' => array('丹青912'),
                            'teacher' => array('罗嗣卿', '李彦宏'),
                        ),
                        array(
                            'key' => 2,
                            'id' => array(24),
                            'subject' => array('erp'),
                            'time' => array('2018/05.12 13:30-14:30'),
                            'address' => array('丹青910'),
                            'teacher' => array('李莉', '刘强东'),
                        ),
                    ),
                    'actions' => array(
                        array(
                            'title' => '查看',
                            'value' => 'watch',
                        ),
                        array(
                            'title' => '编辑',
                            'value' => 'edit',
                            'uri' => '/tablesearch/edit',
                            'colums' => array(
                                array(
                                    'title' => '课程名',
                                    'key' => 'subject',
                                    'type' => 'input',
                                ),
                                array(
                                    'title' => '地点',
                                    'key' => 'address',
                                    'type' => 'input',
                                ),
                                array(
                                    'title' => '时间',
                                    'key' => 'time',
                                    'type' => 'time',
                                ),
                                array(
                                    'title' => '监考教师',
                                    'key' => 'teacher',
                                    'type' => 'multselect',
                                ),
                            ),
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
                    ),
                ),
                'errno' => 0,
                'errmsg' => 'OK',
            ),
            'confirm' => array(
                'data' => 'null',
                'errno' => '2',
                'errmsg' => ' 李彦宏 老师已有监考：高数 2018/05.12 13:30-14:30 丹青912<br> 与本次监考时间冲突 30 分钟，是否确认？',
            ),
            'error' => array(
                'data' => 'null',
                'errno' => '1',
                'errmsg' => '不知道为啥就是失败了2333',
            ),
        );
    }
    
}
